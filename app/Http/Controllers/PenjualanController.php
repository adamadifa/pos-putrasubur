<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use App\Models\PembayaranPenjualan;
use App\Models\KasBank;
use App\Helpers\PengaturanUmumHelper;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
     * Get API configuration for RFID services
     */
    private function getRfidApiConfig()
    {
        return [
            'url' => config('services.rfid_api.url'),
            'token' => config('services.rfid_api.token'),
            'transfer_endpoint' => config('services.rfid_api.transfer_endpoint'),
            'rekening_endpoint' => config('services.rfid_api.rekening_endpoint'),
        ];
    }

    /**
     * Send transfer data to API
     */
    private function sendTransferToApi($rekeningPengirim, $rekeningPenerima, $jumlah, $berita)
    {
        try {
            $apiConfig = $this->getRfidApiConfig();

            if (!$apiConfig['url']) {
                return [
                    'success' => false,
                    'message' => 'API URL tidak dikonfigurasi'
                ];
            }

            $response = Http::withHeaders([
                'X-API-Token' => $apiConfig['token'],
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(10)->post($apiConfig['url'] . $apiConfig['transfer_endpoint'], [
                'rekening_pengirim' => $rekeningPengirim,
                'rekening_penerima' => $rekeningPenerima,
                'jumlah' => $jumlah,
                'berita' => $berita
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if ($responseData['success'] === true) {
                    Log::info('Transfer API berhasil', [
                        'response' => $responseData,
                        'rekening_pengirim' => $rekeningPengirim,
                        'rekening_penerima' => $rekeningPenerima,
                        'jumlah' => $jumlah
                    ]);

                    return [
                        'success' => true,
                        'data' => $responseData['data']
                    ];
                } else {
                    Log::error('Transfer API gagal - response tidak sukses', [
                        'response' => $responseData,
                        'rekening_pengirim' => $rekeningPengirim,
                        'rekening_penerima' => $rekeningPenerima,
                        'jumlah' => $jumlah
                    ]);

                    return [
                        'success' => false,
                        'message' => $responseData['message'] ?? 'Transfer gagal'
                    ];
                }
            } else {
                Log::error('Transfer API gagal - HTTP error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'rekening_pengirim' => $rekeningPengirim,
                    'rekening_penerima' => $rekeningPenerima,
                    'jumlah' => $jumlah
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal menghubungi API transfer'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Transfer API exception', [
                'error' => $e->getMessage(),
                'rekening_pengirim' => $rekeningPengirim,
                'rekening_penerima' => $rekeningPenerima,
                'jumlah' => $jumlah
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim data ke API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug: Log request data
        \Log::info('=== PENJUALAN STORE REQUEST ===');
        \Log::info('Request all:', ['data' => $request->all()]);
        \Log::info('Request uang_muka:', ['data' => $request->uang_muka ?? 'NOT SET']);

        try {
            $validated = $request->validate([
                'no_faktur' => 'required|string|max:50|unique:penjualan',
                'tanggal' => 'required|date',
                'pelanggan_id' => 'required|exists:pelanggan,id',
                'jenis_transaksi' => 'required|in:tunai,kredit',
                'metode_pembayaran' => 'nullable|string|exists:metode_pembayaran,kode',
                'kas_bank_id' => 'nullable|exists:kas_bank,id',
                'dp_amount' => 'nullable|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal',
                'no_rekening' => 'nullable|string|max:50',
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|exists:produk,id',
                'items.*.qty' => 'required|numeric|min:0.1',
                'items.*.harga' => 'required|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
                'uang_muka' => 'nullable|array',
                'uang_muka.*.id' => 'required|exists:uang_muka_pelanggan,id',
                'uang_muka.*.jumlah' => 'required|numeric|min:0.01',
            ], [
                'no_faktur.required' => 'Nomor faktur wajib diisi.',
                'no_faktur.unique' => 'Nomor faktur sudah digunakan.',
                'tanggal.required' => 'Tanggal transaksi wajib diisi.',
                'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
                'pelanggan_id.exists' => 'Pelanggan tidak valid.',
                'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih.',
                'jenis_transaksi.in' => 'Jenis transaksi tidak valid.',
                'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
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

            \Log::info('Validation passed');
            \Log::info('Validated data:', ['data' => $validated]);
            \Log::info('Validated uang_muka:', ['data' => $validated['uang_muka'] ?? 'NOT SET']);

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

            // Determine kas_bank_id based on payment method
            if ($validated['metode_pembayaran'] === 'CARD') {
                // For CARD payment, use the bank with active card payment status
                $activeCardPaymentBank = KasBank::getActiveCardPaymentBank();

                if (!$activeCardPaymentBank) {
                    return back()->withInput()
                        ->withErrors(['metode_pembayaran' => 'Tidak ada bank yang dikonfigurasi untuk card payment. Silakan aktifkan status card payment pada salah satu bank.']);
                }

                $validated['kas_bank_id'] = $activeCardPaymentBank->id;
            } elseif (in_array($validated['metode_pembayaran'], ['TUNAI', 'TRANSFER', 'QRIS'])) {
                // For other payment methods, kas_bank_id is required
                if (empty($validated['kas_bank_id'])) {
                    return back()->withInput()
                        ->withErrors(['kas_bank_id' => 'Kas/Bank wajib dipilih untuk metode pembayaran ini.']);
                }
            }

            DB::beginTransaction();
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

            // Debug: Dump uang muka data
            \Log::info('=== DEBUG UANG MUKA ===');
            \Log::info('Request uang_muka:', ['data' => $request->uang_muka]);
            \Log::info('Request all:', ['data' => $request->all()]);

            // Handle uang muka jika ada - SIMPAN DATA SEMENTARA DULU (hanya validasi)
            $totalUangMukaDigunakan = 0;
            $uangMukaData = []; // Simpan data untuk dibuat setelah penjualan dibuat

            if ($request->filled('uang_muka') && is_array($request->uang_muka)) {
                \Log::info('Uang muka array found:', ['count' => count($request->uang_muka)]);
                foreach ($request->uang_muka as $index => $um) {
                    \Log::info("Processing uang muka index {$index}:", ['data' => $um]);
                    $uangMukaId = $um['id'] ?? null;
                    $jumlahDigunakan = $um['jumlah'] ?? 0;

                    \Log::info("Uang muka {$index} - ID: {$uangMukaId}, Jumlah: {$jumlahDigunakan}");

                    if ($uangMukaId && $jumlahDigunakan > 0) {
                        $uangMuka = \App\Models\UangMukaPelanggan::find($uangMukaId);

                        \Log::info("Uang muka found:", [
                            'exists' => $uangMuka ? true : false,
                            'status' => $uangMuka ? $uangMuka->status : 'not found',
                            'sisa_uang_muka' => $uangMuka ? $uangMuka->sisa_uang_muka : 0
                        ]);

                        if ($uangMuka && $uangMuka->status === 'aktif') {
                            // Validasi jumlah tidak melebihi sisa uang muka
                            \Log::info("Validating jumlah:", [
                                'jumlah_digunakan' => $jumlahDigunakan,
                                'sisa_uang_muka' => $uangMuka->sisa_uang_muka,
                                'valid' => $jumlahDigunakan <= $uangMuka->sisa_uang_muka
                            ]);

                            if ($jumlahDigunakan > $uangMuka->sisa_uang_muka) {
                                \Log::error("Jumlah uang muka melebihi sisa!");
                                throw new \Exception("Jumlah uang muka yang digunakan ({$jumlahDigunakan}) melebihi sisa uang muka yang tersedia ({$uangMuka->sisa_uang_muka}).");
                            }

                            // Validasi jumlah tidak melebihi sisa pembayaran penjualan
                            $sisaPembayaran = $totalSetelahDiskon - ($dpAmount ?? 0);
                            \Log::info("Validating sisa pembayaran:", [
                                'jumlah_digunakan' => $jumlahDigunakan,
                                'total_setelah_diskon' => $totalSetelahDiskon,
                                'dp_amount' => $dpAmount ?? 0,
                                'sisa_pembayaran' => $sisaPembayaran,
                                'valid' => $jumlahDigunakan <= $sisaPembayaran
                            ]);

                            if ($jumlahDigunakan > $sisaPembayaran) {
                                \Log::error("Jumlah uang muka melebihi sisa pembayaran!");
                                throw new \Exception("Jumlah uang muka yang digunakan ({$jumlahDigunakan}) melebihi sisa pembayaran ({$sisaPembayaran}).");
                            }

                            // Simpan data untuk dibuat setelah penjualan dibuat
                            $uangMukaData[] = [
                                'uang_muka' => $uangMuka,
                                'uang_muka_id' => $uangMukaId,
                                'jumlah_digunakan' => $jumlahDigunakan,
                            ];

                            // Update sisa uang muka DULU (sebelum create penjualan)
                            $uangMuka->sisa_uang_muka -= $jumlahDigunakan;
                            if ($uangMuka->sisa_uang_muka <= 0) {
                                $uangMuka->status = 'habis';
                            }
                            $uangMuka->save();

                            $totalUangMukaDigunakan += $jumlahDigunakan;

                            \Log::info('✅ Uang muka validasi berhasil:', [
                                'uang_muka_id' => $uangMukaId,
                                'jumlah_digunakan' => $jumlahDigunakan,
                                'sisa_uang_muka' => $uangMuka->sisa_uang_muka,
                                'total_uang_muka_digunakan' => $totalUangMukaDigunakan
                            ]);
                        } else {
                            \Log::warning("Uang muka tidak aktif atau tidak ditemukan:", [
                                'uang_muka_id' => $uangMukaId,
                                'exists' => $uangMuka ? true : false,
                                'status' => $uangMuka ? $uangMuka->status : 'not found'
                            ]);
                        }
                    } else {
                        \Log::warning("Uang muka data tidak valid:", [
                            'uang_muka_id' => $uangMukaId,
                            'jumlah_digunakan' => $jumlahDigunakan
                        ]);
                    }
                }

                \Log::info('=== TOTAL UANG MUKA DIGUNAKAN ===', ['total' => $totalUangMukaDigunakan]);
            } else {
                \Log::info('Uang muka tidak ada dalam request');
            }

            // Determine payment status and amount
            $statusPembayaran = 'belum_bayar';
            $paymentAmount = 0;

            if ($jenisTransaksi === 'tunai') {
                // For cash transactions, payment amount = total - uang muka
                // Sisa setelah dikurangi uang muka dibayar cash/transfer
                $paymentAmount = $totalSetelahDiskon - $totalUangMukaDigunakan;
                // Jika uang muka >= total, maka tidak perlu bayar lagi (full prepayment)
                if ($paymentAmount < 0) {
                    $paymentAmount = 0;
                }
                $statusPembayaran = 'lunas'; // Tunai selalu lunas
            } elseif ($dpAmount > 0 || $totalUangMukaDigunakan > 0) {
                // For credit transactions, use DP amount + uang muka
                $paymentAmount = $dpAmount;
                $totalPembayaran = $dpAmount + $totalUangMukaDigunakan;
                $statusPembayaran = $totalPembayaran < $totalSetelahDiskon ? 'dp' : 'lunas';
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

            // Buat record penggunaan uang muka SETELAH penjualan dibuat
            if ($totalUangMukaDigunakan > 0 && !empty($uangMukaData)) {
                foreach ($uangMukaData as $umData) {
                    // Buat record penggunaan uang muka
                    \App\Models\PenggunaanUangMukaPenjualan::create([
                        'uang_muka_pelanggan_id' => $umData['uang_muka_id'],
                        'penjualan_id' => $penjualan->id,
                        'jumlah_digunakan' => $umData['jumlah_digunakan'],
                        'tanggal_penggunaan' => $validated['tanggal'],
                        'keterangan' => "Penggunaan uang muka untuk faktur " . $validated['no_faktur'],
                        'user_id' => Auth::id(),
                    ]);

                    // Buat record PembayaranPenjualan untuk uang muka
                    // dengan kas_bank_id = null agar tidak update saldo kas bank
                    // (karena uang sudah masuk saat uang muka dibuat)
                    $noBuktiUm = 'PAY-UM-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($umData['uang_muka_id'], 3, '0', STR_PAD_LEFT);

                    $uangMuka = $umData['uang_muka'];
                    // Tentukan status_bayar untuk uang muka (menggunakan logika normal)
                    $statusBayarUm = 'D'; // Default to DP
                    $totalPembayaranDenganUm = $penjualan->pembayaranPenjualan->sum('jumlah_bayar') + $umData['jumlah_digunakan'];
                    if ($totalPembayaranDenganUm >= $penjualan->total_setelah_diskon) {
                        $statusBayarUm = 'P'; // Pelunasan (full payment)
                    } else {
                        $statusBayarUm = 'D'; // DP (partial payment)
                    }

                    PembayaranPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'no_bukti' => $noBuktiUm,
                        'tanggal' => $validated['tanggal'],
                        'jumlah_bayar' => $umData['jumlah_digunakan'],
                        'metode_pembayaran' => $uangMuka->metode_pembayaran,
                        'status_bayar' => $statusBayarUm, // D, A, atau P sesuai logika
                        'status_uang_muka' => 1, // Menandakan penggunaan uang muka
                        'keterangan' => "Pembayaran dari uang muka " . $uangMuka->no_uang_muka,
                        'user_id' => Auth::id(),
                        'kas_bank_id' => null, // NULL agar tidak update saldo kas bank
                    ]);

                    \Log::info('✅ Record penggunaan uang muka dibuat:', [
                        'uang_muka_id' => $umData['uang_muka_id'],
                        'penjualan_id' => $penjualan->id,
                        'jumlah_digunakan' => $umData['jumlah_digunakan'],
                        'pembayaran_penjualan_created' => true
                    ]);
                }
            }

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
                    'status_uang_muka' => 0, // Tidak menggunakan uang muka
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

            // Check if payment method is CARD and no_rekening is provided
            $metodePembayaran = $validated['metode_pembayaran'] ?? null;
            $noRekening = $validated['no_rekening'] ?? null;

            if ($metodePembayaran === 'CARD' && $noRekening && $paymentAmount > 0) {
                // // Get no_rekening_koperasi from pengaturan umum
                $noRekeningKoperasi = PengaturanUmumHelper::getNoRekeningKoperasi();

                if (!$noRekeningKoperasi) {
                    throw new \Exception('Nomor rekening koperasi belum dikonfigurasi di pengaturan umum.');
                }

                // Send transfer to API
                $berita = "PAYMENT-KOPERASI-" . $validated['no_faktur'];
                $transferResult = $this->sendTransferToApi(
                    $noRekening, // rekening_pengirim (dari RFID scan)
                    $noRekeningKoperasi, // rekening_penerima (dari pengaturan umum)
                    $paymentAmount, // jumlah
                    $berita // berita
                );

                //dd($transferResult);
                if (!$transferResult['success']) {
                    throw new \Exception('Transfer gagal: ' . $transferResult['message']);
                }

                Log::info('Transfer API berhasil diproses', [
                    'penjualan_id' => $penjualan->id,
                    'no_faktur' => $validated['no_faktur'],
                    'transfer_data' => $transferResult['data']
                ]);
            }

            DB::commit();

            // Auto-print receipt jika pengaturan aktif
            $this->autoPrintReceipt($penjualan);

            $successMessage = 'Transaksi penjualan berhasil dibuat.';
            if ($totalUangMukaDigunakan > 0) {
                $successMessage .= ' Uang muka ' . number_format($totalUangMukaDigunakan, 0, ',', '.') . ' telah digunakan.';
            }

            return redirect()->route('penjualan.show', $penjualan->encrypted_id)
                ->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation error
            \Log::error('=== VALIDATION ERROR ===');
            \Log::error('Validation errors:', ['errors' => $e->errors()]);
            \Log::error('Request uang_muka:', ['data' => $request->uang_muka ?? null]);
            \Log::error('Request all:', ['data' => $request->all()]);

            DB::rollback();

            // if (config('app.debug')) {
            //     dd([
            //         'type' => 'validation_error',
            //         'errors' => $e->errors(),
            //         'request_uang_muka' => $request->uang_muka ?? null,
            //         'request_all' => $request->all()
            //     ]);
            // }

            return back()->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal: ' . implode(', ', array_map(function ($errors) {
                    return implode(', ', $errors);
                }, $e->errors())));
        } catch (\Exception $e) {
            // Other errors
            \Log::error('=== ERROR SAVING TRANSACTION ===');
            \Log::error('Error message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request uang_muka:', ['data' => $request->uang_muka ?? null]);
            \Log::error('Request all keys:', ['keys' => array_keys($request->all())]);

            DB::rollback();

            // Dump untuk debug langsung di browser (hanya untuk development)
            // if (config('app.debug')) {
            //     dd([
            //         'type' => 'exception',
            //         'error' => $e->getMessage(),
            //         'file' => $e->getFile(),
            //         'line' => $e->getLine(),
            //         'request_uang_muka' => $request->uang_muka ?? null,
            //         'request_dp_amount' => $request->dp_amount ?? null,
            //         'request_all' => $request->all()
            //     ]);
            // }

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
        $penjualan->load(['pelanggan', 'kasir', 'detailPenjualan.produk.kategori', 'detailPenjualan.produk.satuan']);

        // Query terpisah untuk riwayat pembayaran, diorder by id
        $riwayatPembayaran = PembayaranPenjualan::where('penjualan_id', $penjualan->id)
            ->with(['user', 'kasBank'])
            ->orderBy('id', 'asc') // Urutkan berdasarkan ID

            ->get();

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBank = \App\Models\KasBank::orderBy('nama')->get();

        return view('penjualan.show', compact('penjualan', 'metodePembayaran', 'kasBank', 'riwayatPembayaran'));
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
                    'status_uang_muka' => 0, // Tidak menggunakan uang muka
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

            // Check if request is AJAX
            if (request()->ajax() || request()->wantsJson()) {
                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'message' => $result['message']
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message']
                    ], 422);
                }
            }

            if ($result['success']) {
                return redirect()->route('penjualan.index')
                    ->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting penjualan', [
                'encrypted_id' => $encryptedId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Check if request is AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    /**
     * Get RFID data from external API
     */
    public function getRfidData($rfid): JsonResponse
    {
        try {
            $apiConfig = $this->getRfidApiConfig();

            if (!$apiConfig['url']) {
                return response()->json([
                    'success' => false,
                    'message' => 'API URL tidak dikonfigurasi'
                ], 500);
            }

            // Make API call to external service
            $client = new \GuzzleHttp\Client();
            $response = $client->get($apiConfig['url'] . $apiConfig['rekening_endpoint'] . '/' . $rfid, [
                'headers' => [
                    'X-API-Token' => $apiConfig['token'],
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10, // 10 seconds timeout
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();

            Log::error('RFID API Client Error: ' . $e->getMessage(), [
                'rfid' => $rfid,
                'status_code' => $statusCode,
                'error_body' => $errorBody
            ]);

            return response()->json([
                'success' => false,
                'message' => 'RFID tidak ditemukan atau tidak valid',
                'error_code' => $statusCode
            ], 404);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            Log::error('RFID API Server Error: ' . $e->getMessage(), [
                'rfid' => $rfid
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server API sedang bermasalah, silakan coba lagi nanti'
            ], 500);
        } catch (\Exception $e) {
            Log::error('RFID API Error: ' . $e->getMessage(), [
                'rfid' => $rfid,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data RFID'
            ], 500);
        }
    }
}
