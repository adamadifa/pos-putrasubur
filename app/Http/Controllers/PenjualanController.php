<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use App\Models\PembayaranPenjualan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Penjualan::with(['pelanggan', 'kasir', 'detailPenjualan', 'pembayaranPenjualan']);

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('no_faktur', 'like', "%{$searchTerm}%")
                    ->orWhereHas('pelanggan', function ($q) use ($searchTerm) {
                        $q->where('nama', 'like', "%{$searchTerm}%")
                            ->orWhere('kode_pelanggan', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Handle date filter
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Default sorting by date descending
        $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');

        // Get sales with pagination
        $penjualan = $query->paginate(15)->appends($request->query());

        // Get statistics - semua data
        $totalPenjualan = Penjualan::count();
        $totalNilai = Penjualan::sum('total');

        // Get statistics - hari ini
        $penjualanHariIni = Penjualan::whereDate('tanggal', today())->count();
        $nilaiHariIni = Penjualan::whereDate('tanggal', today())->sum('total');

        // Status counts - hari ini
        $statusCountsHariIni = [
            'lunas' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'lunas')->count(),
            'dp' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'dp')->count(),
            'angsuran' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'angsuran')->count(),
            'belum_bayar' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'belum_bayar')->count(),
        ];

        // Status counts - keseluruhan
        $statusCounts = [
            'lunas' => Penjualan::where('status_pembayaran', 'lunas')->count(),
            'dp' => Penjualan::where('status_pembayaran', 'dp')->count(),
            'angsuran' => Penjualan::where('status_pembayaran', 'angsuran')->count(),
            'belum_bayar' => Penjualan::where('status_pembayaran', 'belum_bayar')->count(),
        ];

        // Perbandingan dengan kemarin
        $kemarin = Carbon::yesterday();
        $penjualanKemarin = Penjualan::whereDate('tanggal', $kemarin)->count();
        $nilaiKemarin = Penjualan::whereDate('tanggal', $kemarin)->sum('total');

        // Hitung persentase perubahan
        $perubahanPenjualan = $penjualanKemarin > 0 ? (($penjualanHariIni - $penjualanKemarin) / $penjualanKemarin) * 100 : 0;
        $perubahanNilai = $nilaiKemarin > 0 ? (($nilaiHariIni - $nilaiKemarin) / $nilaiKemarin) * 100 : 0;

        return view('penjualan.index', compact(
            'penjualan',
            'totalPenjualan',
            'totalNilai',
            'penjualanHariIni',
            'nilaiHariIni',
            'statusCounts',
            'statusCountsHariIni',
            'penjualanKemarin',
            'nilaiKemarin',
            'perubahanPenjualan',
            'perubahanNilai'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get active customers
        $pelanggan = Pelanggan::where('status', true)->orderBy('nama')->get();

        // Get available products
        $produk = Produk::with(['kategori', 'satuan'])
            ->where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        // Generate next invoice number
        $lastInvoice = Penjualan::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $invoiceNumber = $this->generateInvoiceNumber($lastInvoice);

        return view('penjualan.create', compact('pelanggan', 'produk', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_faktur' => 'required|string|max:50|unique:penjualan',
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'diskon' => 'nullable|numeric|min:0',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ], [
            'no_faktur.required' => 'Nomor faktur wajib diisi.',
            'no_faktur.unique' => 'Nomor faktur sudah digunakan.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'pelanggan_id.exists' => 'Pelanggan tidak valid.',
            'items.required' => 'Minimal harus ada 1 produk.',
            'items.min' => 'Minimal harus ada 1 produk.',
            'items.*.produk_id.required' => 'Produk wajib dipilih.',
            'items.*.produk_id.exists' => 'Produk tidak valid.',
            'items.*.qty.required' => 'Quantity wajib diisi.',
            'items.*.qty.min' => 'Quantity minimal 1.',
            'items.*.harga.required' => 'Harga wajib diisi.',
            'items.*.harga.min' => 'Harga tidak boleh negatif.',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['qty'] * $item['harga'];
            }

            // Apply discount
            $diskon = $validated['diskon'] ?? 0;
            $totalSetelahDiskon = $total - $diskon;

            // Create penjualan
            $penjualan = Penjualan::create([
                'no_faktur' => $validated['no_faktur'],
                'tanggal' => $validated['tanggal'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'total' => $totalSetelahDiskon,
                'diskon' => $diskon,
                'status_pembayaran' => 'belum_bayar',
                'jatuh_tempo' => $validated['jatuh_tempo'],
                'kasir_id' => Auth::id(),
            ]);

            // Create detail penjualan
            foreach ($validated['items'] as $item) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                ]);

                // Update stock
                $produk = Produk::find($item['produk_id']);
                $produk->decrement('stok', $item['qty']);
            }

            DB::commit();

            return redirect()->route('penjualan.show', $penjualan->encrypted_id)
                ->with('success', 'Transaksi penjualan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId): View
    {
        $penjualan = Penjualan::findByEncryptedId($encryptedId);
        $penjualan->load(['pelanggan', 'kasir', 'detailPenjualan.produk.kategori', 'detailPenjualan.produk.satuan', 'pembayaranPenjualan.user']);

        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        $penjualan = Penjualan::findByEncryptedId($encryptedId);

        // Only allow editing if payment status is not 'lunas'
        if ($penjualan->status_pembayaran === 'lunas') {
            return redirect()->route('penjualan.show', $encryptedId)
                ->with('error', 'Transaksi yang sudah lunas tidak dapat diedit.');
        }

        $penjualan->load(['detailPenjualan.produk']);

        // Get active customers
        $pelanggan = Pelanggan::where('status', true)->orderBy('nama')->get();

        // Get available products
        $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama_produk')->get();

        return view('penjualan.edit', compact('penjualan', 'pelanggan', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId): RedirectResponse
    {
        $penjualan = Penjualan::findByEncryptedId($encryptedId);

        // Only allow editing if payment status is not 'lunas'
        if ($penjualan->status_pembayaran === 'lunas') {
            return redirect()->route('penjualan.show', $encryptedId)
                ->with('error', 'Transaksi yang sudah lunas tidak dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'diskon' => 'nullable|numeric|min:0',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Restore stock from old details
            foreach ($penjualan->detailPenjualan as $detail) {
                $produk = Produk::find($detail->produk_id);
                $produk->increment('stok', $detail->qty);
            }

            // Delete old details
            $penjualan->detailPenjualan()->delete();

            // Calculate new total
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['qty'] * $item['harga'];
            }

            // Apply discount
            $diskon = $validated['diskon'] ?? 0;
            $totalSetelahDiskon = $total - $diskon;

            // Update penjualan
            $penjualan->update([
                'tanggal' => $validated['tanggal'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'total' => $totalSetelahDiskon,
                'diskon' => $diskon,
                'jatuh_tempo' => $validated['jatuh_tempo'],
            ]);

            // Create new detail penjualan
            foreach ($validated['items'] as $item) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                ]);

                // Update stock
                $produk = Produk::find($item['produk_id']);
                $produk->decrement('stok', $item['qty']);
            }

            DB::commit();

            return redirect()->route('penjualan.show', $penjualan->encrypted_id)
                ->with('success', 'Transaksi penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId): RedirectResponse
    {
        $penjualan = Penjualan::findByEncryptedId($encryptedId);

        // Only allow deletion if no payments have been made
        if ($penjualan->pembayaranPenjualan()->count() > 0) {
            return redirect()->route('penjualan.index')
                ->with('error', 'Transaksi yang sudah ada pembayaran tidak dapat dihapus.');
        }

        DB::beginTransaction();

        try {
            // Restore stock
            foreach ($penjualan->detailPenjualan as $detail) {
                $produk = Produk::find($detail->produk_id);
                $produk->increment('stok', $detail->qty);
            }

            // Delete the sale (details will be deleted automatically due to cascade)
            $penjualan->delete();

            DB::commit();

            return redirect()->route('penjualan.index')
                ->with('success', 'Transaksi penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('penjualan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Generate next invoice number
     */
    private function generateInvoiceNumber($lastInvoice = null): string
    {
        $today = Carbon::now();
        $prefix = 'INV-' . $today->format('Ymd') . '-';

        if ($lastInvoice && str_starts_with($lastInvoice->no_faktur, $prefix)) {
            $lastNumber = (int) substr($lastInvoice->no_faktur, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Search products for autocomplete
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $produk = Produk::with(['kategori', 'satuan'])
            ->where(function ($q) use ($query) {
                $q->where('nama_produk', 'like', "%{$query}%")
                    ->orWhere('kode_produk', 'like', "%{$query}%");
            })
            ->where('stok', '>', 0)
            ->limit(10)
            ->get(['id', 'kode_produk', 'nama_produk', 'harga_jual', 'stok', 'kategori_id', 'satuan_id']);

        return response()->json($produk);
    }

    /**
     * Get product details by ID
     */
    public function getProduct($id): JsonResponse
    {
        $produk = Produk::with(['kategori', 'satuan'])->find($id);

        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($produk);
    }
}
