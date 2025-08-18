<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\DetailPembelian;
use App\Models\PembayaranPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'detailPembelian', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode_supplier', 'like', "%{$search}%");
                    });
            });
        }

        // Date range filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Jenis transaksi filter
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Get paginated results
        $pembelian = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics for today
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Pembelian hari ini
        $pembelianHariIni = Pembelian::whereDate('tanggal', $today)->count();
        $pembelianKemarin = Pembelian::whereDate('tanggal', $yesterday)->count();
        $perubahanPembelian = $pembelianKemarin > 0 ?
            (($pembelianHariIni - $pembelianKemarin) / $pembelianKemarin) * 100 : 0;

        // Nilai pembelian hari ini
        $nilaiHariIni = Pembelian::whereDate('tanggal', $today)->sum('total');
        $nilaiKemarin = Pembelian::whereDate('tanggal', $yesterday)->sum('total');
        $perubahanNilai = $nilaiKemarin > 0 ?
            (($nilaiHariIni - $nilaiKemarin) / $nilaiKemarin) * 100 : 0;

        // Status counts hari ini
        $statusCountsHariIni = Pembelian::whereDate('tanggal', $today)
            ->selectRaw('status_pembayaran, COUNT(*) as count')
            ->groupBy('status_pembayaran')
            ->pluck('count', 'status_pembayaran')
            ->toArray();

        // Jenis transaksi counts hari ini
        $jenisTransaksiCountsHariIni = Pembelian::whereDate('tanggal', $today)
            ->selectRaw('jenis_transaksi, COUNT(*) as count')
            ->groupBy('jenis_transaksi')
            ->pluck('count', 'jenis_transaksi')
            ->toArray();

        return view('pembelian.index', compact(
            'pembelian',
            'pembelianHariIni',
            'perubahanPembelian',
            'nilaiHariIni',
            'perubahanNilai',
            'statusCountsHariIni',
            'jenisTransaksiCountsHariIni'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('status', true)->orderBy('nama')->get();
        $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama_produk')->get();

        // Generate invoice number
        $invoiceNumber = 'PO-' . date('YmdHis');

        return view('pembelian.create', compact('suppliers', 'produk', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_faktur' => 'required|string|max:50|unique:pembelian',
            'supplier_id' => 'required|exists:supplier,id',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:tunai,kredit',
            'dp_amount' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga_beli' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ], [
            'no_faktur.required' => 'Nomor faktur wajib diisi.',
            'no_faktur.unique' => 'Nomor faktur sudah digunakan.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih.',
            'jenis_transaksi.in' => 'Jenis transaksi tidak valid.',
            'dp_amount.min' => 'Jumlah pembayaran tidak boleh negatif.',
            'items.required' => 'Minimal harus ada 1 produk.',
            'items.min' => 'Minimal harus ada 1 produk.',
            'items.*.produk_id.required' => 'Produk wajib dipilih.',
            'items.*.produk_id.exists' => 'Produk tidak valid.',
            'items.*.qty.required' => 'Quantity wajib diisi.',
            'items.*.qty.min' => 'Quantity minimal 0.01.',
            'items.*.harga_beli.required' => 'Harga wajib diisi.',
            'items.*.harga_beli.min' => 'Harga tidak boleh negatif.',
            'items.*.discount.min' => 'Diskon tidak boleh negatif.',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals with item discounts
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga_beli'];
                $itemDiscount = $item['discount'] ?? 0;
                $subtotal += ($itemSubtotal - $itemDiscount);
            }

            // Apply discount
            $diskon = $validated['diskon'] ?? 0;
            $totalSetelahDiskon = $subtotal - $diskon;

            // Get payment info
            $jenisTransaksi = $validated['jenis_transaksi'];
            $dpAmount = $validated['dp_amount'] ?? 0;

            // Determine payment status
            $statusPembayaran = 'belum_bayar';
            if ($jenisTransaksi === 'tunai' && $dpAmount >= $totalSetelahDiskon) {
                $statusPembayaran = 'lunas';
            } elseif ($dpAmount > 0) {
                $statusPembayaran = $dpAmount < $totalSetelahDiskon ? 'dp' : 'lunas';
            }

            // Create pembelian
            $pembelian = Pembelian::create([
                'no_faktur' => $validated['no_faktur'],
                'supplier_id' => $validated['supplier_id'],
                'tanggal' => $validated['tanggal'],
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $totalSetelahDiskon,
                'status_pembayaran' => $statusPembayaran,
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'keterangan' => $validated['keterangan'],
                'user_id' => auth()->id(),
            ]);

            // Create detail pembelian
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga_beli'];
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = $itemSubtotal - $itemDiscount;

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $itemTotal, // Store final amount after item discount
                    'discount' => $itemDiscount,
                ]);

                // Update stock
                $produk = Produk::find($item['produk_id']);
                $produk->increment('stok', $item['qty']);
            }

            // Create payment record if there's payment amount
            if ($dpAmount > 0) {
                // Generate payment reference number
                $noBukti = 'PAY-PO-' . date('Ymd') . '-' . str_pad($pembelian->id, 4, '0', STR_PAD_LEFT);

                // Determine payment method based on transaction type
                $metodePembayaran = $jenisTransaksi === 'tunai' ? 'tunai' : 'dp';

                \App\Models\PembayaranPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $dpAmount,
                    'metode_pembayaran' => $metodePembayaran,
                    'status_bayar' => $jenisTransaksi === 'tunai' ? 'P' : 'D', // P = Pelunasan, D = DP
                    'keterangan' => $jenisTransaksi === 'tunai'
                        ? 'Pembayaran tunai penuh'
                        : 'Pembayaran DP awal',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            $successMessage = 'Pembelian berhasil ditambahkan.';

            if ($dpAmount > 0) {
                $successMessage .= ' Pembayaran ' . number_format($dpAmount, 0, ',', '.') . ' telah dicatat.';
            }

            return redirect()->route('pembelian.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $pembelian = Pembelian::with(['supplier', 'detailPembelian.produk', 'pembayaranPembelian', 'user'])
                ->findOrFail(Crypt::decryptString($id));

            // Calculate sisa pembayaran
            $sisaPembayaran = $pembelian->sisa_pembayaran;

            return view('pembelian.show', compact('pembelian', 'sisaPembayaran'));
        } catch (\Exception $e) {
            return redirect()->route('pembelian.index')
                ->with('error', 'Pembelian tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $pembelian = Pembelian::with(['detailPembelian.produk', 'supplier'])->findOrFail(Crypt::decryptString($id));
            $suppliers = Supplier::where('status', true)->orderBy('nama')->get();
            $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama_produk')->get();

            return view('pembelian.edit', compact('pembelian', 'suppliers', 'produk'));
        } catch (\Exception $e) {
            return redirect()->route('pembelian.index')
                ->with('error', 'Pembelian tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $pembelian = Pembelian::findOrFail(Crypt::decryptString($id));

            $validated = $request->validate([
                'no_faktur' => 'required|string|max:50|unique:pembelian,no_faktur,' . $pembelian->id,
                'supplier_id' => 'required|exists:supplier,id',
                'tanggal' => 'required|date',
                'jenis_transaksi' => 'required|in:tunai,kredit',
                'keterangan' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|exists:produk,id',
                'items.*.qty' => 'required|numeric|min:0.01',
                'items.*.harga_beli' => 'required|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
            ]);

            DB::beginTransaction();

            // Delete existing detail pembelian
            $pembelian->detailPembelian()->delete();

            // Calculate totals
            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga_beli'];
                $itemDiscount = $item['discount'] ?? 0;
                $subtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;
            }

            $total = $subtotal - $totalDiscount;

            // Update pembelian
            $pembelian->update([
                'no_faktur' => $validated['no_faktur'],
                'supplier_id' => $validated['supplier_id'],
                'tanggal' => $validated['tanggal'],
                'subtotal' => $subtotal,
                'diskon' => $totalDiscount,
                'total' => $total,
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'keterangan' => $validated['keterangan'],
            ]);

            // Create new detail pembelian
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga_beli'];
                $itemDiscount = $item['discount'] ?? 0;

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $itemSubtotal,
                    'discount' => $itemDiscount,
                ]);
            }

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pembelian = Pembelian::findOrFail(Crypt::decryptString($id));

            // Check if pembelian has payments
            if ($pembelian->pembayaranPembelian()->count() > 0) {
                return redirect()->route('pembelian.index')
                    ->with('error', 'Pembelian tidak dapat dihapus karena sudah memiliki pembayaran.');
            }

            $pembelian->delete();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pembelian.index')
                ->with('error', 'Pembelian tidak ditemukan.');
        }
    }

    /**
     * Get pembelian for AJAX request
     */
    public function getPembelian(Request $request)
    {
        $search = $request->get('search');

        $pembelian = Pembelian::with(['supplier'])
            ->where('no_faktur', 'like', "%{$search}%")
            ->orWhereHas('supplier', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get(['id', 'no_faktur', 'supplier_id', 'tanggal', 'total']);

        return response()->json($pembelian);
    }
}
