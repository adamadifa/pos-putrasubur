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
use Illuminate\Support\Facades\Log;

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

        // Handle jenis transaksi filter
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Default sorting by date descending
        $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');

        // Get sales with pagination
        $penjualan = $query->paginate(15)->appends($request->query());

        // Get statistics - semua data
        $totalPenjualan = Penjualan::count();
        $totalNilai = Penjualan::get()->sum(function ($penjualan) {
            return $penjualan->total_setelah_diskon;
        });

        // Get statistics - hari ini
        $penjualanHariIni = Penjualan::whereDate('tanggal', today())->count();
        $nilaiHariIni = Penjualan::whereDate('tanggal', today())->get()->sum(function ($penjualan) {
            return $penjualan->total_setelah_diskon;
        });

        // Status counts - hari ini
        $statusCountsHariIni = [
            'lunas' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'lunas')->count(),
            'dp' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'dp')->count(),
            'angsuran' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'angsuran')->count(),
            'belum_bayar' => Penjualan::whereDate('tanggal', today())->where('status_pembayaran', 'belum_bayar')->count(),
        ];

        // Jenis transaksi counts - hari ini
        $jenisTransaksiCountsHariIni = [
            'tunai' => Penjualan::whereDate('tanggal', today())->where('jenis_transaksi', 'tunai')->count(),
            'kredit' => Penjualan::whereDate('tanggal', today())->where('jenis_transaksi', 'kredit')->count(),
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
        $nilaiKemarin = Penjualan::whereDate('tanggal', $kemarin)->get()->sum(function ($penjualan) {
            return $penjualan->total_setelah_diskon;
        });

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
            'jenisTransaksiCountsHariIni',
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
            ->orderBy('nama_produk')
            ->get();

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBank = \App\Models\KasBank::orderBy('nama')->get();

        // Generate next invoice number
        $lastInvoice = Penjualan::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $invoiceNumber = $this->generateInvoiceNumber($lastInvoice);

        // Generate automatic customer code
        $kodePelanggan = $this->generateKodePelanggan();

        return view('penjualan.create', compact('pelanggan', 'produk', 'metodePembayaran', 'kasBank', 'invoiceNumber', 'kodePelanggan'));
    }

    /**
     * Generate automatic customer code with format PEL{YYMM}{001}
     * Example: PEL2509001 (September 2025, customer #001)
     */
    private function generateKodePelanggan()
    {
        // Get current year and month (YYMM format)
        $currentYearMonth = date('ym'); // e.g., 2509 for September 2025

        // Find the last customer code for current month/year
        $lastPelanggan = Pelanggan::where('kode_pelanggan', 'LIKE', 'PEL' . $currentYearMonth . '%')
            ->orderBy('kode_pelanggan', 'desc')
            ->first();

        if ($lastPelanggan) {
            // Extract number from last code (e.g., PEL2509001 -> 001)
            $lastCode = $lastPelanggan->kode_pelanggan;
            if (preg_match('/PEL' . $currentYearMonth . '(\d{3})/', $lastCode, $matches)) {
                $lastNumber = (int) $matches[1];
                $newNumber = $lastNumber + 1;

                // If we reach 1000 for current month, throw an exception
                if ($newNumber > 999) {
                    throw new \Exception('Tidak dapat membuat kode pelanggan baru. Sudah mencapai limit 999 pelanggan untuk bulan ' . date('F Y') . '.');
                }
            } else {
                $newNumber = 1;
            }
        } else {
            $newNumber = 1;
        }

        // Format: PEL + YYMM + 001
        return 'PEL' . $currentYearMonth . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
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
            'jenis_transaksi' => 'required|in:tunai,kredit',
            'metode_pembayaran' => 'nullable|string|exists:metode_pembayaran,kode',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'dp_amount' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ], [
            'no_faktur.required' => 'Nomor faktur wajib diisi.',
            'no_faktur.unique' => 'Nomor faktur sudah digunakan.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'pelanggan_id.exists' => 'Pelanggan tidak valid.',
            'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih.',
            'jenis_transaksi.in' => 'Jenis transaksi tidak valid.',
            'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
            'kas_bank_id.required' => 'Kas/Bank wajib dipilih.',
            'kas_bank_id.exists' => 'Kas/Bank tidak valid.',
            'dp_amount.min' => 'Jumlah pembayaran tidak boleh negatif.',
            'items.required' => 'Minimal harus ada 1 produk.',
            'items.min' => 'Minimal harus ada 1 produk.',
            'items.*.produk_id.required' => 'Produk wajib dipilih.',
            'items.*.produk_id.exists' => 'Produk tidak valid.',
            'items.*.qty.required' => 'Quantity wajib diisi.',
            'items.*.qty.min' => 'Quantity minimal 0.1.',
            'items.*.harga.required' => 'Harga wajib diisi.',
            'items.*.harga.min' => 'Harga tidak boleh negatif.',
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

        DB::beginTransaction();

        try {
            // Calculate total with item discounts
            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga'];
                $itemDiscount = $item['discount'] ?? 0;
                $total += ($itemSubtotal - $itemDiscount);
            }

            // Apply discount
            $diskon = $validated['diskon'] ?? 0;
            $totalSetelahDiskon = $total - $diskon;

            // Get payment info
            $jenisTransaksi = $validated['jenis_transaksi'];
            $dpAmount = $validated['dp_amount'] ?? 0;

            // Determine payment status and amount
            $statusPembayaran = 'belum_bayar';
            $paymentAmount = 0;

            if ($jenisTransaksi === 'tunai') {
                // For cash transactions, payment amount equals total after discount
                $paymentAmount = $totalSetelahDiskon;
                $statusPembayaran = 'lunas';
            } elseif ($dpAmount > 0) {
                // For credit transactions, use DP amount
                $paymentAmount = $dpAmount;
                $statusPembayaran = $dpAmount < $totalSetelahDiskon ? 'dp' : 'lunas';
            }

            // Create penjualan
            $penjualan = Penjualan::create([
                'no_faktur' => $validated['no_faktur'],
                'tanggal' => $validated['tanggal'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'total' => $total, // Simpan total sebelum diskon
                'diskon' => $diskon,
                'status_pembayaran' => $statusPembayaran,
                'jatuh_tempo' => $validated['jatuh_tempo'] ?? null,
                'kasir_id' => Auth::id(),
            ]);

            // Create detail penjualan
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga'];
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = $itemSubtotal - $itemDiscount;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $itemTotal, // Store final amount after item discount
                    'discount' => $itemDiscount,
                ]);

                // Update stock
                $produk = Produk::find($item['produk_id']);
                $produk->decrement('stok', $item['qty']);
            }

            // Create payment record if there's payment amount
            if ($paymentAmount > 0) {
                // Generate payment reference number
                $noBukti = 'PAY-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT);

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

                $pembayaranPenjualan = PembayaranPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $paymentAmount,
                    'metode_pembayaran' => $metodePembayaran,
                    'status_bayar' => $jenisTransaksi === 'tunai' ? 'P' : 'D', // P = Pelunasan, D = DP
                    'keterangan' => $jenisTransaksi === 'tunai'
                        ? 'Pembayaran tunai penuh'
                        : 'Pembayaran DP awal',
                    'user_id' => Auth::id(),
                    'kas_bank_id' => $validated['kas_bank_id'],
                ]);

                // Note: Saldo kas/bank akan otomatis terupdate melalui database trigger

                Log::info('Created payment record', [
                    'penjualan_id' => $penjualan->id,
                    'no_bukti' => $noBukti,
                    'amount' => $paymentAmount,
                    'jenis_transaksi' => $jenisTransaksi,
                    'metode_pembayaran' => $metodePembayaran
                ]);
            } else {
                // No payment amount - this could be a pure credit transaction
                Log::info('No payment record created', [
                    'penjualan_id' => $penjualan->id,
                    'jenis_transaksi' => $jenisTransaksi,
                    'dp_amount' => $dpAmount,
                    'status_pembayaran' => $statusPembayaran
                ]);
            }

            DB::commit();

            // Auto-print receipt jika pengaturan aktif
            $this->autoPrintReceipt($penjualan);

            return redirect()->route('penjualan.show', $penjualan->encrypted_id)
                ->with('success', 'Transaksi penjualan berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Error saving transaction: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

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

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBank = \App\Models\KasBank::orderBy('nama')->get();

        return view('penjualan.show', compact('penjualan', 'metodePembayaran', 'kasBank'));
    }

    /**
     * Get penjualan detail for API (JSON response)
     */
    public function getDetail($id): JsonResponse
    {
        try {
            $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])
                ->findOrFail($id);

            // Format the data for JSON response
            $penjualanData = [
                'id' => $penjualan->id,
                'no_faktur' => $penjualan->no_faktur,
                'tanggal' => \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y'),
                'jam' => \Carbon\Carbon::parse($penjualan->created_at)->format('H:i'),
                'pelanggan' => $penjualan->pelanggan,
                'status_pembayaran' => $penjualan->status_pembayaran,
                'jenis_transaksi' => $penjualan->jenis_transaksi,
                'total' => $penjualan->total,
                'diskon' => $penjualan->diskon,
                'total_setelah_diskon' => $penjualan->total_setelah_diskon,
                'detail_penjualan' => $penjualan->detailPenjualan->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'qty' => $detail->qty,
                        'harga' => $detail->harga,
                        'diskon' => $detail->discount ?? 0,
                        'subtotal' => $detail->subtotal,
                        'produk' => $detail->produk
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'penjualan' => $penjualanData
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting penjualan detail: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Penjualan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        $penjualan = Penjualan::findByEncryptedId($encryptedId);

        // Check if transaction is within H+1 (today and yesterday)
        $today = Carbon::today();
        $transactionDate = Carbon::parse($penjualan->created_at)->startOfDay();
        $isMoreThanOneDay = $today->diffInDays($transactionDate) > 1;

        if ($isMoreThanOneDay) {
            return redirect()->route('penjualan.show', $encryptedId)
                ->with('error', 'Transaksi yang sudah lebih dari H+1 tidak dapat diedit.');
        }

        $penjualan->load(['detailPenjualan.produk']);

        // Get active customers
        $pelanggan = Pelanggan::where('status', true)->orderBy('nama')->get();

        // Get available products
        $produk = Produk::with(['kategori', 'satuan'])->orderBy('nama_produk')->get();

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBank = \App\Models\KasBank::orderBy('nama')->get();

        return view('penjualan.edit', compact('penjualan', 'pelanggan', 'produk', 'metodePembayaran', 'kasBank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId): RedirectResponse
    {


        $penjualan = Penjualan::findByEncryptedId($encryptedId);

        // Check if transaction is within H+1 (today and yesterday)
        $today = Carbon::today();
        $transactionDate = Carbon::parse($penjualan->created_at)->startOfDay();
        $isMoreThanOneDay = $today->diffInDays($transactionDate) > 1;

        if ($isMoreThanOneDay) {
            return redirect()->route('penjualan.show', $encryptedId)
                ->with('error', 'Transaksi yang sudah lebih dari H+1 tidak dapat diedit.');
        }



        $validated = $request->validate([
            'tanggal' => 'required|date',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'jenis_transaksi' => 'required|in:tunai,kredit',
            'dp_amount' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|string|max:50',
            'kas_bank_id' => 'nullable|exists:kas_bank,id',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:0.1',
            'items.*.harga' => 'required|numeric|min:0',
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

        try {
            // Restore stock from old details
            foreach ($penjualan->detailPenjualan as $detail) {
                $produk = Produk::find($detail->produk_id);
                $produk->increment('stok', $detail->qty);
            }

            // Delete old details
            $penjualan->detailPenjualan()->delete();

            // Delete old pembayaran (saldo kas/bank akan otomatis terupdate melalui database trigger)
            $penjualan->pembayaranPenjualan()->delete();

            // Calculate new total with item discounts
            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga'];
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

            // Update penjualan
            $penjualan->update([
                'tanggal' => $validated['tanggal'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'jenis_transaksi' => $validated['jenis_transaksi'],
                'total' => $totalSetelahDiskon,
                'diskon' => $diskon,
                'status_pembayaran' => $statusPembayaran,
                'kasir_id' => auth()->id(),
            ]);

            // Create new detail penjualan
            foreach ($validated['items'] as $item) {
                $itemSubtotal = $item['qty'] * $item['harga'];
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = $itemSubtotal - $itemDiscount;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $itemTotal, // Store final amount after item discount
                    'discount' => $itemDiscount,
                ]);

                // Update stock
                $produk = Produk::find($item['produk_id']);
                $produk->decrement('stok', $item['qty']);
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
                $noBukti = 'PAY-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT);

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

                $pembayaranPenjualan = PembayaranPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $paymentAmount,
                    'metode_pembayaran' => $metodePembayaran,
                    'status_bayar' => $validated['jenis_transaksi'] === 'tunai' ? 'P' : 'D', // P = Pelunasan, D = DP
                    'keterangan' => $validated['jenis_transaksi'] === 'tunai'
                        ? 'Pembayaran tunai penuh'
                        : 'Pembayaran DP awal',
                    'user_id' => Auth::id(),
                    'kas_bank_id' => $validated['kas_bank_id'],
                ]);

                // Note: Saldo kas/bank akan otomatis terupdate melalui database trigger

                Log::info('Created payment record', [
                    'penjualan_id' => $penjualan->id,
                    'no_bukti' => $noBukti,
                    'amount' => $paymentAmount,
                    'jenis_transaksi' => $validated['jenis_transaksi'],
                    'metode_pembayaran' => $metodePembayaran
                ]);
            } else {
                // No payment amount - this could be a pure credit transaction
                Log::info('No payment record created', [
                    'penjualan_id' => $penjualan->id,
                    'jenis_transaksi' => $validated['jenis_transaksi'],
                    'dp_amount' => $validated['dp_amount'] ?? 0,
                    'status_pembayaran' => $statusPembayaran
                ]);
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
    public function destroy($encryptedId)
    {
        try {
            // Decrypt ID dan cari penjualan
            $penjualan = Penjualan::findByEncryptedId($encryptedId);

            // Gunakan TransactionService untuk menghapus penjualan
            $transactionService = new \App\Services\TransactionService();
            $result = $transactionService->deletePenjualan($penjualan);

            if ($result['success']) {
                return redirect()->route('penjualan.index')
                    ->with('success', $result['message']);
            } else {
                return back()->withErrors(['error' => $result['message']]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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

    /**
     * Auto-print receipt setelah transaksi berhasil
     */
    private function autoPrintReceipt($penjualan)
    {
        try {
            // Cek pengaturan auto-print
            $autoPrint = session('printer_settings.auto_print', false);

            if (!$autoPrint) {
                Log::info('Auto-print disabled, skipping receipt generation');
                return; // Auto-print tidak aktif
            }

            // Load data lengkap untuk receipt
            $penjualan->load([
                'pelanggan',
                'kasir',
                'detailPenjualan.produk.satuan',
                'pembayaranPenjualan'
            ]);

            // Generate receipt content
            $receiptData = $this->generateReceiptData($penjualan);

            // Log untuk debugging
            Log::info('Auto-print receipt triggered', [
                'no_faktur' => $penjualan->no_faktur,
                'printer_settings' => session('printer_settings', []),
                'receipt_data_size' => count($receiptData['receipt_data'] ?? [])
            ]);

            // Simpan receipt data ke session untuk diambil frontend
            session(['pending_receipt' => $receiptData]);

            Log::info('Pending receipt saved to session', [
                'no_faktur' => $penjualan->no_faktur
            ]);
        } catch (\Exception $e) {
            Log::error('Auto-print receipt failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate receipt data untuk thermal printer
     */
    private function generateReceiptData($penjualan)
    {
        try {
            $receiptLines = [];

            // Header
            $receiptLines[] = "\x1B\x40"; // Initialize printer
            $receiptLines[] = "\x1B\x61\x01"; // Center align
            $receiptLines[] = "PUTRA SUBUR\n";
            $receiptLines[] = "Toko Kelontong\n";
            $receiptLines[] = "Jl. Raya No. 123\n";
            $receiptLines[] = "Telp: 021-1234567\n";
            $receiptLines[] = "================================\n";

            // Transaction info
            $receiptLines[] = "\x1B\x61\x00"; // Left align
            $receiptLines[] = "No. Faktur: " . $penjualan->no_faktur . "\n";
            $receiptLines[] = "Tanggal: " . $penjualan->tanggal->format('d/m/Y H:i') . "\n";
            $receiptLines[] = "Pelanggan: " . ($penjualan->pelanggan->nama ?? 'Umum') . "\n";
            $receiptLines[] = "Kasir: " . ($penjualan->kasir->name ?? 'Unknown') . "\n";
            $receiptLines[] = "================================\n";

            // Items
            foreach ($penjualan->detailPenjualan as $detail) {
                $produkNama = substr($detail->produk->nama_produk, 0, 20);
                $qty = number_format($detail->qty, 0);
                $harga = number_format($detail->harga, 0);
                $subtotal = number_format($detail->subtotal, 0);
                $satuan = $detail->produk->satuan->nama ?? 'pcs';

                $receiptLines[] = $produkNama . "\n";
                $receiptLines[] = sprintf(
                    "  %s %s x %s = %s\n",
                    $qty,
                    $satuan,
                    $harga,
                    $subtotal
                );

                if ($detail->discount > 0) {
                    $receiptLines[] = sprintf("  Diskon: -%s\n", number_format($detail->discount, 0));
                }
            }

            $receiptLines[] = "--------------------------------\n";

            // Totals
            $subtotalSebelumDiskon = $penjualan->total + $penjualan->diskon;
            $receiptLines[] = sprintf("Subtotal: Rp %s\n", number_format($subtotalSebelumDiskon, 0));

            if ($penjualan->diskon > 0) {
                $receiptLines[] = sprintf("Diskon: -Rp %s\n", number_format($penjualan->diskon, 0));
            }

            $receiptLines[] = sprintf("TOTAL: Rp %s\n", number_format($penjualan->total, 0));

            // Payment info
            $totalBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
            if ($totalBayar > 0) {
                $receiptLines[] = sprintf("Bayar: Rp %s\n", number_format($totalBayar, 0));

                if ($totalBayar >= $penjualan->total) {
                    $kembalian = $totalBayar - $penjualan->total;
                    $receiptLines[] = sprintf("Kembalian: Rp %s\n", number_format($kembalian, 0));
                } else {
                    $sisa = $penjualan->total - $totalBayar;
                    $receiptLines[] = sprintf("Sisa: Rp %s\n", number_format($sisa, 0));
                }
            }

            $receiptLines[] = "================================\n";
            $receiptLines[] = "\x1B\x61\x01"; // Center align
            $receiptLines[] = "Terima kasih atas kunjungan Anda\n";
            $receiptLines[] = "Barang yang sudah dibeli\n";
            $receiptLines[] = "tidak dapat dikembalikan\n";
            $receiptLines[] = "\n\n\n";
            $receiptLines[] = "\x1D\x56\x42\x00"; // Cut paper

            return [
                'no_faktur' => $penjualan->no_faktur,
                'receipt_data' => $receiptLines,
                'timestamp' => now()->toISOString()
            ];
        } catch (\Exception $e) {
            Log::error('Error generating receipt data: ' . $e->getMessage(), [
                'penjualan_id' => $penjualan->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            // Return minimal receipt data to prevent complete failure
            return [
                'no_faktur' => $penjualan->no_faktur ?? 'UNKNOWN',
                'receipt_data' => ["Error generating receipt: " . $e->getMessage()],
                'timestamp' => now()->toISOString(),
                'error' => true
            ];
        }
    }

    /**
     * API endpoint untuk mendapatkan pending receipt
     */
    public function getPendingReceipt(): JsonResponse
    {
        try {
            $pendingReceipt = session('pending_receipt');

            if ($pendingReceipt) {
                // Clear dari session setelah diambil
                session()->forget('pending_receipt');

                return response()->json([
                    'success' => true,
                    'receipt' => $pendingReceipt
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No pending receipt'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getPendingReceipt: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving pending receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export penjualan to PDF with receipt format
     */
    public function exportPdf($encryptedId)
    {
        try {
            $penjualan = Penjualan::findByEncryptedId($encryptedId);
            $penjualan->load(['pelanggan', 'kasir', 'detailPenjualan.produk.satuan', 'pembayaranPenjualan.user', 'pembayaranPenjualan.kasBank']);

            // Generate PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('penjualan.pdf-receipt', compact('penjualan'));

            // Set paper size and orientation
            $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait'); // 80mm width, A4 height

            // Generate filename
            $filename = 'Invoice_' . $penjualan->no_faktur . '_' . date('Y-m-d_H-i-s') . '.pdf';

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Error exporting penjualan PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengexport PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
