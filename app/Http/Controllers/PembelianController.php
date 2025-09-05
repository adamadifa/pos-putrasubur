<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Produk;
use App\Models\DetailPembelian;
use App\Models\PembayaranPembelian;
use App\Models\MetodePembayaran;
use App\Models\KasBank;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBank = \App\Models\KasBank::orderBy('nama')->get();

        // Generate invoice number
        $invoiceNumber = 'PO-' . date('YmdHis');

        return view('pembelian.create', compact('suppliers', 'produk', 'metodePembayaran', 'kasBank', 'invoiceNumber'));
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
            'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'dp_amount' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
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
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
            'kas_bank_id.required' => 'Kas/Bank wajib dipilih.',
            'kas_bank_id.exists' => 'Kas/Bank tidak valid.',
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

        // Additional validation for metode_pembayaran to ensure it's active
        if ($validated['metode_pembayaran']) {
            $metodeExists = \App\Models\MetodePembayaran::where('kode', $validated['metode_pembayaran'])
                ->where('status', true)
                ->exists();

            if (!$metodeExists) {
                return back()->withInput()
                    ->withErrors(['metode_pembayaran' => 'Metode pembayaran yang dipilih tidak aktif.']);
            }
        }

        try {
            // Debug logging
            Log::info('Pembelian store method called', [
                'request_data' => $request->all(),
                'validated_data' => $validated
            ]);

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
                'keterangan' => $validated['keterangan'] ?? null,
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

                // Get metode_pembayaran from form, with proper validation
                $metodePembayaran = $validated['metode_pembayaran'] ?? null;

                // If no metode_pembayaran selected, get default based on transaction type
                if (!$metodePembayaran) {
                    if ($jenisTransaksi === 'tunai') {
                        // For cash transactions, get the first active 'tunai' method
                        $defaultMetode = \App\Models\MetodePembayaran::where('status', true)
                            ->where('kode', 'like', '%tunai%')
                            ->first();
                        $metodePembayaran = $defaultMetode ? $defaultMetode->kode : null;
                    } else {
                        // For credit transactions, get the first active method
                        $defaultMetode = \App\Models\MetodePembayaran::where('status', true)
                            ->orderBy('urutan')
                            ->first();
                        $metodePembayaran = $defaultMetode ? $defaultMetode->kode : null;
                    }
                }

                // Validate that metode_pembayaran exists in database
                if ($metodePembayaran) {
                    $metodeExists = \App\Models\MetodePembayaran::where('kode', $metodePembayaran)
                        ->where('status', true)
                        ->exists();

                    if (!$metodeExists) {
                        throw new \Exception('Metode pembayaran yang dipilih tidak valid atau tidak aktif.');
                    }
                }

                \App\Models\PembayaranPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $dpAmount,
                    'metode_pembayaran' => $metodePembayaran,
                    'kas_bank_id' => $validated['kas_bank_id'],
                    'status_bayar' => $jenisTransaksi === 'tunai' ? 'P' : 'D', // P = Pelunasan, D = DP
                    'keterangan' => $jenisTransaksi === 'tunai'
                        ? 'Pembayaran tunai penuh'
                        : 'Pembayaran DP awal',
                    'user_id' => auth()->id(),
                ]);

                Log::info('Created payment record', [
                    'pembelian_id' => $pembelian->id,
                    'no_bukti' => $noBukti,
                    'amount' => $dpAmount,
                    'jenis_transaksi' => $jenisTransaksi,
                    'metode_pembayaran' => $metodePembayaran,
                    'kas_bank_id' => $validated['kas_bank_id']
                ]);
            } else {
                // No payment amount - this could be a pure credit transaction
                Log::info('No payment record created', [
                    'pembelian_id' => $pembelian->id,
                    'jenis_transaksi' => $jenisTransaksi,
                    'dp_amount' => $dpAmount,
                    'status_pembayaran' => $statusPembayaran
                ]);
            }

            DB::commit();

            $successMessage = 'Pembelian berhasil ditambahkan.';

            if ($dpAmount > 0) {
                $successMessage .= ' Pembayaran ' . number_format($dpAmount, 0, ',', '.') . ' telah dicatat.';
            }

            // Debug logging
            Log::info('Pembelian created successfully', [
                'pembelian_id' => $pembelian->id,
                'encrypted_id' => $pembelian->encrypted_id,
                'redirect_url' => route('pembelian.show', $pembelian->encrypted_id)
            ]);

            return redirect()->route('pembelian.show', $pembelian->encrypted_id)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Error in Pembelian store method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId)
    {
        try {
            $pembelian = Pembelian::findByEncryptedId($encryptedId);
            $pembelian->load(['supplier', 'detailPembelian.produk', 'pembayaranPembelian', 'user']);

            // Calculate sisa pembayaran
            $sisaPembayaran = $pembelian->sisa_pembayaran;

            // Get active payment methods
            $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
                ->orderBy('urutan')
                ->orderBy('nama')
                ->get();

            // Get kas/bank data
            $kasBank = KasBank::orderBy('nama')->get();

            return view('pembelian.show', compact('pembelian', 'sisaPembayaran', 'metodePembayaran', 'kasBank'));
        } catch (\Exception $e) {
            return redirect()->route('pembelian.index')
                ->with('error', 'Pembelian tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        try {
            $pembelian = Pembelian::findByEncryptedId($encryptedId);
            $pembelian->load(['detailPembelian.produk', 'supplier']);

            // Check if transaction is within H+1 (today and yesterday)
            $today = Carbon::today();
            $transactionDate = Carbon::parse($pembelian->created_at)->startOfDay();
            $isMoreThanOneDay = $today->diffInDays($transactionDate) > 1;

            if ($isMoreThanOneDay) {
                return redirect()->route('pembelian.show', $encryptedId)
                    ->with('error', 'Transaksi yang sudah lebih dari H+1 tidak dapat diedit.');
            }

            $suppliers = Supplier::where('status', true)->orderBy('nama')->get();
            $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama_produk')->get();
            $metodePembayaran = MetodePembayaran::where('status', true)->orderBy('nama')->get();
            $kasBank = KasBank::orderBy('nama')->get();

            return view('pembelian.edit', compact('pembelian', 'suppliers', 'produk', 'metodePembayaran', 'kasBank'));
        } catch (\Exception $e) {
            return redirect()->route('pembelian.index')
                ->with('error', 'Pembelian tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId): RedirectResponse
    {
        try {
            $pembelian = Pembelian::findByEncryptedId($encryptedId);

            // Check if transaction is within H+1 (today and yesterday)
            $today = Carbon::today();
            $transactionDate = Carbon::parse($pembelian->created_at)->startOfDay();
            $isMoreThanOneDay = $today->diffInDays($transactionDate) > 1;

            if ($isMoreThanOneDay) {
                return redirect()->route('pembelian.show', $encryptedId)
                    ->with('error', 'Transaksi yang sudah lebih dari H+1 tidak dapat diedit.');
            }

            $validated = $request->validate([
                'no_faktur' => 'required|string|max:50|unique:pembelian,no_faktur,' . $pembelian->id,
                'supplier_id' => 'required|exists:supplier,id',
                'tanggal' => 'required|date',
                'jenis_transaksi' => 'required|in:tunai,kredit',
                'metode_pembayaran' => 'nullable|string|max:50',
                'kas_bank_id' => 'nullable|exists:kas_bank,id',
                'dp_amount' => 'nullable|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:produk,id',
                'items.*.qty' => 'required|numeric|min:0.01',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
            ]);

            // Additional validation for metode_pembayaran to ensure it's active
            if ($validated['metode_pembayaran']) {
                $metodeExists = \App\Models\MetodePembayaran::where('kode', $validated['metode_pembayaran'])
                    ->where('status', true)
                    ->exists();

                if (!$metodeExists) {
                    return back()->withInput()
                        ->withErrors(['metode_pembayaran' => 'Metode pembayaran yang dipilih tidak aktif.']);
                }
            }

            DB::beginTransaction();

            // Restore stock from old details
            foreach ($pembelian->detailPembelian as $detail) {
                $produk = Produk::find($detail->produk_id);
                $produk->decrement('stok', $detail->qty);
            }

            // Delete existing detail pembelian
            $pembelian->detailPembelian()->delete();

            // Delete old pembayaran (saldo kas/bank akan otomatis terupdate melalui database trigger)
            $pembelian->pembayaranPembelian()->delete();

            // Calculate totals with item discounts
            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['price'];
                $itemDiscount = $item['discount'] ?? 0;
                $total += ($itemSubtotal - $itemDiscount);
            }

            // Apply discount
            $diskon = $validated['diskon'] ?? 0;
            $totalSetelahDiskon = $total - $diskon;

            // Determine payment status based on transaction type
            $statusPembayaran = 'lunas';
            if ($validated['jenis_transaksi'] === 'kredit') {
                $dpAmount = $validated['dp_amount'] ?? 0;
                if ($dpAmount > 0 && $dpAmount < $totalSetelahDiskon) {
                    $statusPembayaran = 'dp';
                } elseif ($dpAmount >= $totalSetelahDiskon) {
                    $statusPembayaran = 'lunas';
                } else {
                    // DP = 0 means no payment has been made yet
                    $statusPembayaran = 'belum_bayar';
                }
            }

            // Update pembelian
            $pembelian->update([
                'no_faktur' => $validated['no_faktur'],
                'supplier_id' => $validated['supplier_id'],
                'tanggal' => $validated['tanggal'],
                'total' => $totalSetelahDiskon,
                'diskon' => $diskon,
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'status_pembayaran' => $statusPembayaran,
            ]);

            // Create new detail pembelian
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['price'];
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = $itemSubtotal - $itemDiscount;

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['price'],
                    'subtotal' => $itemTotal, // Store final amount after item discount
                    'discount' => $itemDiscount,
                ]);

                // Update stock
                $produk = Produk::find($item['id']);
                $produk->increment('stok', $item['qty']);
            }

            // Create payment record if there's payment amount
            $paymentAmount = 0;
            if ($validated['jenis_transaksi'] === 'tunai') {
                // For cash transactions, payment amount equals total after discount
                $paymentAmount = $totalSetelahDiskon;
            } elseif ($validated['jenis_transaksi'] === 'kredit') {
                $dpAmount = $validated['dp_amount'] ?? 0;
                if ($dpAmount > 0) {
                    $paymentAmount = $dpAmount;
                }
            }

            if ($paymentAmount > 0) {
                // Generate payment reference number
                $noBukti = 'PAY-PUR-' . date('Ymd') . '-' . str_pad($pembelian->id, 4, '0', STR_PAD_LEFT);

                // Get metode_pembayaran from form, with proper validation
                $metodePembayaran = $validated['metode_pembayaran'] ?? null;

                // If no metode_pembayaran selected, get default based on transaction type
                if (!$metodePembayaran) {
                    if ($validated['jenis_transaksi'] === 'tunai') {
                        // For cash transactions, get the first active 'tunai' method
                        $defaultMetode = \App\Models\MetodePembayaran::where('status', true)
                            ->where('kode', 'like', '%tunai%')
                            ->first();
                        $metodePembayaran = $defaultMetode ? $defaultMetode->kode : null;
                    } else {
                        // For credit transactions, get the first active method
                        $defaultMetode = \App\Models\MetodePembayaran::where('status', true)
                            ->orderBy('urutan')
                            ->first();
                        $metodePembayaran = $defaultMetode ? $defaultMetode->kode : null;
                    }
                }

                // Validate that metode_pembayaran exists in database
                if ($metodePembayaran) {
                    $metodeExists = \App\Models\MetodePembayaran::where('kode', $metodePembayaran)
                        ->where('status', true)
                        ->exists();

                    if (!$metodeExists) {
                        throw new \Exception('Metode pembayaran yang dipilih tidak valid atau tidak aktif.');
                    }
                }

                PembayaranPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $paymentAmount,
                    'metode_pembayaran' => $metodePembayaran,
                    'status_bayar' => $validated['jenis_transaksi'] === 'tunai' ? 'P' : 'D', // P = Pelunasan, D = DP
                    'keterangan' => $validated['jenis_transaksi'] === 'tunai'
                        ? 'Pembayaran tunai penuh'
                        : 'Pembayaran DP awal',
                    'user_id' => auth()->id(),
                    'kas_bank_id' => $validated['kas_bank_id'],
                ]);

                // Note: Saldo kas/bank akan otomatis terupdate melalui database trigger

                Log::info('Created payment record', [
                    'pembelian_id' => $pembelian->id,
                    'no_bukti' => $noBukti,
                    'amount' => $paymentAmount,
                    'jenis_transaksi' => $validated['jenis_transaksi'],
                    'metode_pembayaran' => $metodePembayaran
                ]);
            } else {
                // No payment amount - this could be a pure credit transaction
                Log::info('No payment record created', [
                    'pembelian_id' => $pembelian->id,
                    'jenis_transaksi' => $validated['jenis_transaksi'],
                    'dp_amount' => $validated['dp_amount'] ?? 0,
                    'status_pembayaran' => $statusPembayaran
                ]);
            }

            DB::commit();

            return redirect()->route('pembelian.show', $pembelian->encrypted_id)
                ->with('success', 'Transaksi pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in Pembelian update method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId)
    {
        try {
            // Decrypt ID dan cari pembelian
            $pembelian = Pembelian::findByEncryptedId($encryptedId);

            // Gunakan TransactionService untuk menghapus pembelian
            $transactionService = new \App\Services\TransactionService();
            $result = $transactionService->deletePembelian($pembelian);

            if ($result['success']) {
                return redirect()->route('pembelian.index')
                    ->with('success', $result['message']);
            } else {
                return back()->withErrors(['error' => $result['message']]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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

    /**
     * Get detail pembelian for modal
     */
    public function getDetail($id)
    {
        try {
            $pembelian = Pembelian::with(['supplier', 'detailPembelian.produk'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'pembelian' => [
                    'id' => $pembelian->id,
                    'no_faktur' => $pembelian->no_faktur,
                    'tanggal' => \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y'),
                    'jam' => \Carbon\Carbon::parse($pembelian->created_at)->format('H:i'),
                    'supplier' => $pembelian->supplier,
                    'subtotal' => $pembelian->subtotal,
                    'diskon' => $pembelian->diskon,
                    'total' => $pembelian->total,
                    'status_pembayaran' => $pembelian->status_pembayaran,
                    'keterangan' => $pembelian->keterangan,
                    'detail_pembelian' => $pembelian->detailPembelian->map(function ($detail) {
                        return [
                            'produk' => $detail->produk,
                            'qty' => $detail->qty,
                            'harga_beli' => $detail->harga_beli,
                            'diskon' => $detail->discount ?? 0,
                            'subtotal' => $detail->subtotal
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail pembelian'
            ], 500);
        }
    }
}
