<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pembayaran = PembayaranPenjualan::with(['penjualan.pelanggan', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pembayaran.index', compact('pembayaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get penjualan that need payment (not fully paid)
        $penjualan = Penjualan::with(['pelanggan', 'pembayaranPenjualan'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->filter(function ($p) {
                // Calculate total already paid
                $sudahDibayar = $p->pembayaranPenjualan->sum('jumlah_bayar');
                $sisaBayar = $p->total_setelah_diskon - $sudahDibayar;

                // Only show transactions that still have remaining balance to pay
                return $sisaBayar > 0;
            })
            ->values(); // Reset array keys

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBanks = \App\Models\KasBank::orderBy('nama')->get();

        return view('pembayaran.create', compact('penjualan', 'metodePembayaran', 'kasBanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        // Log the request data for debugging
        Log::info('Payment creation request', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'kas_bank_id_raw' => $request->input('kas_bank_id'),
            'metode_pembayaran_raw' => $request->input('metode_pembayaran')
        ]);

        try {
            // Clean and parse jumlah input
            $jumlahInput = $request->input('jumlah');
            $jumlahClean = preg_replace('/[^\d]/', '', $jumlahInput);
            $jumlahNumeric = (int) $jumlahClean;

            // Check if using uang muka - jika checkbox dicentang, jumlah yang diinput adalah jumlah uang muka yang digunakan
            $useUangMuka = $request->input('use_uang_muka', false); // Flag checkbox
            $hasUangMuka = false;
            $totalUangMukaInput = 0;

            if ($useUangMuka) {
                // Jika checkbox dicentang, jumlah yang diinput adalah jumlah uang muka yang digunakan
                $hasUangMuka = true;
                $totalUangMukaInput = $jumlahNumeric; // Jumlah dari field "Jumlah Pembayaran"
            } else {
                // Jika tidak dicentang, cek apakah ada data uang_muka dari form
                $uangMukaFromRequest = $request->input('uang_muka');
                if ($uangMukaFromRequest && is_array($uangMukaFromRequest)) {
                    $validUangMuka = array_filter($uangMukaFromRequest, function ($um) {
                        return isset($um['id']) && !empty($um['id']) && isset($um['jumlah']) && floatval($um['jumlah']) > 0;
                    });
                    $hasUangMuka = count($validUangMuka) > 0;

                    foreach ($uangMukaFromRequest as $um) {
                        if (isset($um['jumlah']) && is_numeric($um['jumlah'])) {
                            $totalUangMukaInput += floatval($um['jumlah']);
                        }
                    }
                }
            }

            // Validate request - adjust rules based on whether uang muka is used
            $validationRules = [
                'penjualan_id' => 'required|exists:penjualan,id',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string|max:255',
            ];

            // Jumlah hanya required jika tidak menggunakan uang muka
            if (!$hasUangMuka) {
                $validationRules['jumlah'] = 'required|string';
            } else {
                $validationRules['jumlah'] = 'nullable|string';
            }

            // Metode pembayaran dan kas_bank_id selalu required - sama seperti tanpa checklist
            $validationRules['metode_pembayaran'] = 'required|string|max:50';
            $validationRules['kas_bank_id'] = 'nullable|exists:kas_bank,id';

            $customMessages = [
                'penjualan_id.required' => 'Transaksi wajib dipilih.',
                'penjualan_id.exists' => 'Transaksi tidak valid.',
                'jumlah.required' => 'Jumlah bayar wajib diisi.',
                'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
                'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
                'kas_bank_id.exists' => 'Kas/Bank yang dipilih tidak valid.',
                'tanggal.date' => 'Format tanggal tidak valid.',
                'keterangan.max' => 'Keterangan maksimal 255 karakter.',
            ];

            $validated = $request->validate($validationRules, $customMessages);
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Validate metode pembayaran exists
        if (!isset($validated['metode_pembayaran'])) {
            $errorMessage = 'Metode pembayaran wajib dipilih.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return back()->withInput()->with('error', $errorMessage);
        }

        $metodePembayaran = \App\Models\MetodePembayaran::where('kode', $validated['metode_pembayaran'])
            ->where('status', true)
            ->first();

        if (!$metodePembayaran) {
            $errorMessage = 'Metode pembayaran yang dipilih tidak valid atau tidak aktif.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return back()->withInput()->with('error', $errorMessage);
        }

        // Validate kas_bank_id based on payment method
        if (in_array(strtolower($validated['metode_pembayaran']), ['tunai', 'transfer', 'qris'])) {
            if (empty($validated['kas_bank_id'])) {
                $errorMessage = 'Kas/Bank wajib dipilih untuk metode pembayaran ' . $metodePembayaran->nama . '.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->withInput()->with('error', $errorMessage);
            }

            // Validate kas_bank exists
            $kasBank = \App\Models\KasBank::find($validated['kas_bank_id']);

            if (!$kasBank) {
                $errorMessage = 'Kas/Bank yang dipilih tidak valid.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->withInput()->with('error', $errorMessage);
            }
        } else {
            // For other payment methods, set kas_bank_id to null
            $validated['kas_bank_id'] = null;
        }

        // Custom validation for jumlah
        if ($jumlahNumeric < 1000) {
            $errorMessage = 'Jumlah pembayaran minimal Rp 1.000.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return back()->withInput()->with('error', $errorMessage);
        }

        // Update validated data with clean numeric value (jumlah pembayaran asli, tanpa dikurangi uang muka)
        $validated['jumlah_raw'] = $jumlahNumeric;

        // Convert datetime-local to proper format
        try {
            $tanggal = \Carbon\Carbon::parse($validated['tanggal']);
            $validated['tanggal'] = $tanggal->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $errorMessage = 'Format tanggal tidak valid.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return back()->withInput()->with('error', $errorMessage);
        }

        DB::beginTransaction();

        try {
            // Get the penjualan
            $penjualan = Penjualan::findOrFail($validated['penjualan_id']);

            // Calculate total already paid (sebelum memproses uang muka baru)
            $sudahDibayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
            $totalTransaksi = $penjualan->total_setelah_diskon; // Gunakan total setelah diskon
            $sisaBayar = $totalTransaksi - $sudahDibayar;

            // Handle uang muka jika ada
            $totalUangMukaDigunakan = 0;
            $uangMukaData = [];

            if ($useUangMuka) {
                // Jika checkbox dicentang, jumlah yang diinput adalah jumlah uang muka yang digunakan
                $jumlahYangAkanDigunakan = $validated['jumlah_raw'];

                // Validasi: jumlah tidak boleh melebihi total sisa uang muka yang tersedia
                $totalSisaUangMuka = \App\Models\UangMukaPelanggan::where('pelanggan_id', $penjualan->pelanggan_id)
                    ->where('status', 'aktif')
                    ->where('sisa_uang_muka', '>', 0)
                    ->sum('sisa_uang_muka');

                if ($jumlahYangAkanDigunakan > $totalSisaUangMuka) {
                    DB::rollback();
                    $errorMessage = "Jumlah uang muka yang digunakan (Rp " . number_format($jumlahYangAkanDigunakan, 0, ',', '.') . ") melebihi total sisa uang muka yang tersedia (Rp " . number_format($totalSisaUangMuka, 0, ',', '.') . ").";
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage
                        ], 422);
                    }
                    return back()->withInput()->with('error', $errorMessage);
                }

                // Validasi: jumlah tidak boleh melebihi sisa pembayaran
                if ($jumlahYangAkanDigunakan > $sisaBayar) {
                    DB::rollback();
                    $errorMessage = "Jumlah uang muka yang digunakan (Rp " . number_format($jumlahYangAkanDigunakan, 0, ',', '.') . ") melebihi sisa pembayaran (Rp " . number_format($sisaBayar, 0, ',', '.') . ").";
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage
                        ], 422);
                    }
                    return back()->withInput()->with('error', $errorMessage);
                }

                // Ambil uang muka yang tersedia dan distribusikan jumlah yang akan digunakan
                $availableUangMuka = \App\Models\UangMukaPelanggan::where('pelanggan_id', $penjualan->pelanggan_id)
                    ->where('status', 'aktif')
                    ->where('sisa_uang_muka', '>', 0)
                    ->orderBy('tanggal', 'asc')
                    ->get();

                $jumlahTersisa = $jumlahYangAkanDigunakan;

                foreach ($availableUangMuka as $uangMuka) {
                    if ($jumlahTersisa <= 0) break;

                    $jumlahDigunakan = min($jumlahTersisa, $uangMuka->sisa_uang_muka);

                    $uangMukaData[] = [
                        'uang_muka' => $uangMuka,
                        'uang_muka_id' => $uangMuka->id,
                        'jumlah_digunakan' => $jumlahDigunakan,
                    ];

                    $totalUangMukaDigunakan += $jumlahDigunakan;
                    $jumlahTersisa -= $jumlahDigunakan;
                }
            } elseif ($hasUangMuka && $uangMukaFromRequest && is_array($uangMukaFromRequest)) {
                // Logika lama untuk backward compatibility
                foreach ($uangMukaFromRequest as $umItem) {
                    $uangMukaIdRaw = $umItem['id'] ?? '';
                    try {
                        $uangMukaId = decrypt($uangMukaIdRaw);
                    } catch (\Exception $e) {
                        // If decrypt fails, assume it's already a plain ID
                        $uangMukaId = $uangMukaIdRaw;
                    }
                    $jumlahDigunakan = floatval($umItem['jumlah'] ?? 0);

                    if ($uangMukaId && $jumlahDigunakan > 0) {
                        $uangMuka = \App\Models\UangMukaPelanggan::find($uangMukaId);
                        if ($uangMuka && $uangMuka->pelanggan_id == $penjualan->pelanggan_id) {
                            // Validasi: jumlah tidak boleh lebih dari sisa
                            if ($jumlahDigunakan > $uangMuka->sisa_uang_muka) {
                                DB::rollback();
                                $errorMessage = "Jumlah uang muka yang digunakan melebihi sisa uang muka untuk {$uangMuka->no_uang_muka}";
                                if ($request->ajax() || $request->wantsJson()) {
                                    return response()->json([
                                        'success' => false,
                                        'message' => $errorMessage
                                    ], 422);
                                }
                                return back()->withInput()->with('error', $errorMessage);
                            }

                            $uangMukaData[] = [
                                'uang_muka' => $uangMuka,
                                'uang_muka_id' => $uangMukaId,
                                'jumlah_digunakan' => $jumlahDigunakan,
                            ];

                            $totalUangMukaDigunakan += $jumlahDigunakan;
                        }
                    }
                }
            }

            // Validate payment amount (hanya jumlah pembayaran, uang muka tidak dihitung)
            // Catatan: $sisaBayar, $sudahDibayar, $totalTransaksi sudah dihitung di atas
            if (!$useUangMuka && $validated['jumlah_raw'] > $sisaBayar) {
                DB::rollback();
                $errorMessage = 'Jumlah bayar tidak boleh melebihi sisa yang harus dibayar (Rp ' . number_format($sisaBayar) . ').';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->withInput()->with('error', $errorMessage);
            }

            // Generate payment reference number
            $noBukti = 'PAY-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad(PembayaranPenjualan::where('penjualan_id', $penjualan->id)->count() + 1, 2, '0', STR_PAD_LEFT);

            /**
             * LOGIKA STATUS PEMBAYARAN:
             * 
             * 1. PEMBAYARAN PERTAMA (sudahDibayar == 0):
             *    - Jika bayar >= total setelah diskon → P (Pelunasan)
             *    - Jika bayar < total setelah diskon → D (DP)
             * 
             * 2. PEMBAYARAN SELANJUTNYA (sudahDibayar > 0):
             *    - Jika (sudahDibayar + bayar) >= total setelah diskon → P (Pelunasan)
             *    - Jika (sudahDibayar + bayar) < total setelah diskon → A (Angsuran)
             * 
             * KODE STATUS:
             * P = Pelunasan (Lunas)
             * D = DP (Down Payment)
             * A = Angsuran
             * 
             * CATATAN: Total transaksi menggunakan total_setelah_diskon untuk perhitungan yang benar
             */
            $statusBayar = 'D'; // Default to DP

            if ($sudahDibayar == 0) {
                // First payment
                if ($validated['jumlah_raw'] >= $totalTransaksi) {
                    $statusBayar = 'P'; // Pelunasan (full payment)
                } else {
                    $statusBayar = 'D'; // DP (partial payment)
                }
            } else {
                // Subsequent payments
                $totalAfterPayment = $sudahDibayar + $validated['jumlah_raw'];
                if ($totalAfterPayment >= $totalTransaksi) {
                    $statusBayar = 'P'; // Pelunasan (final payment)
                } else {
                    $statusBayar = 'A'; // Angsuran (partial payment)
                }
            }

            // Create payment record
            // Jika menggunakan uang muka, jangan buat record untuk jumlah pembayaran yang diinput
            // Hanya buat record untuk uang muka saja dengan status_uang_muka = 1
            $pembayaran = null;
            if ($totalUangMukaDigunakan > 0) {
                // Jika menggunakan uang muka, skip pembuatan record untuk jumlah pembayaran
                // Akan dibuat record uang muka dengan status_uang_muka = 1 di bawah
            } elseif ($validated['jumlah_raw'] > 0) {
                // Hanya buat record jika tidak menggunakan uang muka
                $pembayaran = PembayaranPenjualan::create([
                    'penjualan_id' => $validated['penjualan_id'],
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $validated['jumlah_raw'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'kas_bank_id' => $validated['kas_bank_id'] ?? null,
                    'status_bayar' => $statusBayar,
                    'status_uang_muka' => 0, // Tidak menggunakan uang muka
                    'keterangan' => $validated['keterangan'],
                    'user_id' => Auth::id(),
                ]);
            }

            // Process uang muka jika ada
            if ($totalUangMukaDigunakan > 0 && !empty($uangMukaData)) {
                // Update sisa uang muka untuk semua uang muka yang digunakan
                foreach ($uangMukaData as $umData) {
                    // Update sisa uang muka
                    $uangMuka = $umData['uang_muka'];
                    $uangMuka->sisa_uang_muka -= $umData['jumlah_digunakan'];
                    if ($uangMuka->sisa_uang_muka <= 0) {
                        $uangMuka->status = 'habis';
                    }
                    $uangMuka->save();

                    // Buat record penggunaan uang muka
                    $keteranganPenggunaan = $pembayaran && $noBukti ? "Penggunaan uang muka untuk pembayaran " . $noBukti : "Penggunaan uang muka untuk penjualan " . $penjualan->no_faktur;
                    \App\Models\PenggunaanUangMukaPenjualan::create([
                        'uang_muka_pelanggan_id' => $umData['uang_muka_id'],
                        'penjualan_id' => $penjualan->id,
                        'jumlah_digunakan' => $umData['jumlah_digunakan'],
                        'tanggal_penggunaan' => $validated['tanggal'],
                        'keterangan' => $keteranganPenggunaan,
                        'user_id' => Auth::id(),
                    ]);
                }

                // Buat HANYA SATU record PembayaranPenjualan untuk uang muka dengan status_uang_muka = 1
                // Menggunakan jumlah yang diinput user (bukan total dari semua uang muka)
                if ($useUangMuka) {
                    // Jika checkbox dicentang, gunakan jumlah yang diinput sebagai jumlah_bayar
                    $jumlahBayarUangMuka = $validated['jumlah_raw'];
                } else {
                    // Jika tidak, gunakan total dari uang muka yang diproses
                    $jumlahBayarUangMuka = $totalUangMukaDigunakan;
                }

                // Hitung status_bayar untuk uang muka (menggunakan logika normal)
                $statusBayarUangMuka = 'D'; // Default to DP
                if ($sudahDibayar == 0) {
                    // First payment dengan uang muka
                    if ($jumlahBayarUangMuka >= $totalTransaksi) {
                        $statusBayarUangMuka = 'P'; // Pelunasan (full payment)
                    } else {
                        $statusBayarUangMuka = 'D'; // DP (partial payment)
                    }
                } else {
                    // Subsequent payments dengan uang muka
                    $totalAfterPayment = $sudahDibayar + $jumlahBayarUangMuka;
                    if ($totalAfterPayment >= $totalTransaksi) {
                        $statusBayarUangMuka = 'P'; // Pelunasan (final payment)
                    } else {
                        $statusBayarUangMuka = 'A'; // Angsuran (partial payment)
                    }
                }

                // Generate unique no_bukti untuk uang muka
                // Format: PAY-UM-YYYYMMDD-PENJUALAN_ID-SEQUENCE
                $existingUmCount = PembayaranPenjualan::where('penjualan_id', $penjualan->id)
                    ->where('status_uang_muka', 1)
                    ->whereDate('created_at', today())
                    ->count();

                $noBuktiUm = 'PAY-UM-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($existingUmCount + 1, 3, '0', STR_PAD_LEFT);

                // Pastikan no_bukti unik (jika masih ada duplikasi, tambahkan timestamp)
                $counter = 1;
                while (PembayaranPenjualan::where('no_bukti', $noBuktiUm)->exists()) {
                    $noBuktiUm = 'PAY-UM-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($existingUmCount + 1 + $counter, 3, '0', STR_PAD_LEFT);
                    $counter++;
                }

                PembayaranPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'no_bukti' => $noBuktiUm,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $jumlahBayarUangMuka,
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_bayar' => $statusBayarUangMuka, // D, A, atau P sesuai logika
                    'status_uang_muka' => 1, // Menandakan penggunaan uang muka
                    'keterangan' => $validated['keterangan'] ?? "Pembayaran menggunakan uang muka",
                    'user_id' => Auth::id(),
                    'kas_bank_id' => null, // NULL agar tidak update saldo kas bank
                ]);
            }

            // Debug: Log pembayaran yang baru dibuat
            if ($pembayaran) {
                Log::info('Pembayaran created with kas_bank_id', [
                    'pembayaran_id' => $pembayaran->id,
                    'kas_bank_id' => $pembayaran->kas_bank_id,
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'validated_kas_bank_id' => $validated['kas_bank_id'] ?? null
                ]);
            }

            // Update penjualan payment status
            // Jika menggunakan uang muka, total dibayar hanya dari uang muka
            // Jika tidak menggunakan uang muka, total dibayar dari jumlah pembayaran
            if ($totalUangMukaDigunakan > 0) {
                // Hanya menghitung uang muka yang digunakan
                $totalDibayar = $sudahDibayar + $totalUangMukaDigunakan;
            } else {
                // Hanya menghitung jumlah pembayaran yang diinput
                $totalDibayar = $sudahDibayar + $validated['jumlah_raw'];
            }

            if ($totalDibayar >= $totalTransaksi) {
                $penjualan->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $penjualan->update(['status_pembayaran' => 'dp']);
            } else {
                $penjualan->update(['status_pembayaran' => 'belum_bayar']);
            }

            // Catat transaksi kas/bank jika kas_bank_id ada
            if ($validated['kas_bank_id']) {
                try {
                    // Trigger database akan otomatis menangani update saldo dan pencatatan transaksi
                    Log::info('Pembayaran dengan kas_bank_id akan diproses oleh trigger database', [
                        'pembayaran_id' => $pembayaran->id,
                        'kas_bank_id' => $validated['kas_bank_id'],
                        'jumlah' => $validated['jumlah_raw']
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error in payment processing: ' . $e->getMessage());
                    // Don't throw error, just log it
                }
            }

            DB::commit();

            Log::info('Payment created successfully', [
                'pembayaran_id' => $pembayaran ? $pembayaran->id : null,
                'penjualan_id' => $penjualan->id,
                'amount' => $validated['jumlah_raw'],
                'uang_muka_digunakan' => $totalUangMukaDigunakan,
                'method' => $validated['metode_pembayaran'] ?? null,
                'kas_bank_id' => $validated['kas_bank_id'] ?? null,
                'status_bayar' => $pembayaran ? $statusBayar : null,
                'payment_logic' => [
                    'sudah_dibayar' => $sudahDibayar,
                    'total_sebelum_diskon' => $penjualan->total,
                    'total_setelah_diskon' => $penjualan->total_setelah_diskon,
                    'total_transaksi' => $totalTransaksi,
                    'is_first_payment' => $sudahDibayar == 0,
                    'total_after_payment' => $totalDibayar,
                    'penjualan_status' => $totalDibayar >= $totalTransaksi ? 'lunas' : ($totalDibayar > 0 ? 'dp' : 'belum_bayar')
                ]
            ]);

            $responseMessage = 'Pembayaran berhasil disimpan!';
            if ($totalUangMukaDigunakan > 0 && $validated['jumlah_raw'] == 0) {
                $responseMessage = 'Pembayaran menggunakan uang muka berhasil disimpan!';
            } elseif ($totalUangMukaDigunakan > 0) {
                $responseMessage = "Pembayaran berhasil disimpan! (Rp " . number_format($validated['jumlah_raw'], 0, ',', '.')  . ")";
            }

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $responseMessage,
                    'data' => [
                        'pembayaran_id' => $pembayaran ? $pembayaran->id : null,
                        'no_bukti' => $pembayaran ? $pembayaran->no_bukti : null,
                        'jumlah' => $pembayaran ? $pembayaran->jumlah_bayar : 0,
                        'uang_muka_digunakan' => $totalUangMukaDigunakan
                    ]
                ]);
            }

            return redirect()->route('pembayaran.index')
                ->with('success', $responseMessage);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating payment: ' . $e->getMessage());

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $pembayaran = PembayaranPenjualan::with(['penjualan.pelanggan', 'user'])->findOrFail($id);

        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pembayaran = PembayaranPenjualan::findOrFail($id);

        // Get penjualan for dropdown
        $penjualan = Penjualan::with(['pelanggan'])->orderBy('tanggal', 'desc')->get();

        return view('pembayaran.edit', compact('pembayaran', 'penjualan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $pembayaran = PembayaranPenjualan::findOrFail($id);

        $validated = $request->validate([
            'penjualan_id' => 'required|exists:penjualan,id',
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'status_bayar' => 'required|string|max:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Get the penjualan
            $penjualan = Penjualan::findOrFail($validated['penjualan_id']);

            // Calculate total already paid (excluding current payment)
            $sudahDibayar = $penjualan->pembayaranPenjualan->where('id', '!=', $id)->sum('jumlah_bayar');
            $totalTransaksi = $penjualan->total_setelah_diskon;
            $sisaBayar = $totalTransaksi - $sudahDibayar;

            // Validate payment amount
            if ($validated['jumlah_bayar'] > $sisaBayar) {
                return back()->withInput()
                    ->with('error', 'Jumlah bayar tidak boleh melebihi sisa yang harus dibayar (Rp ' . number_format($sisaBayar) . ').');
            }

            // Update payment record
            $pembayaran->update([
                'penjualan_id' => $validated['penjualan_id'],
                'tanggal' => $validated['tanggal'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_bayar' => $validated['status_bayar'],
                'status_uang_muka' => $pembayaran->status_uang_muka ?? 0, // Keep existing value
                'keterangan' => $validated['keterangan'],
            ]);

            // Update penjualan payment status
            $totalDibayar = $sudahDibayar + $validated['jumlah_bayar'];

            if ($totalDibayar >= $totalTransaksi) {
                $penjualan->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $penjualan->update(['status_pembayaran' => 'dp']);
            } else {
                $penjualan->update(['status_pembayaran' => 'belum_bayar']);
            }

            DB::commit();

            return redirect()->route('pembayaran.index')
                ->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating payment: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Get payment detail for modal
     */
    public function detail($id)
    {
        try {
            $pembayaran = PembayaranPenjualan::with(['penjualan.pelanggan', 'user'])->findOrFail($id);

            // Format data for JSON response
            $formattedPembayaran = [
                'id' => $pembayaran->id,
                'no_bukti' => $pembayaran->no_bukti,
                'tanggal_formatted' => $pembayaran->tanggal->format('d/m/Y H:i'),
                'jumlah_bayar_formatted' => number_format($pembayaran->jumlah_bayar, 0, ',', '.'),
                'metode_pembayaran' => $pembayaran->metode_pembayaran,
                'metode_pembayaran_display' => ucfirst($pembayaran->metode_pembayaran),
                'status_bayar' => $pembayaran->status_bayar,
                'status_bayar_display' => $this->getStatusBayarDisplay($pembayaran->status_bayar),
                'status_uang_muka' => $pembayaran->status_uang_muka ?? 0,
                'keterangan' => $pembayaran->keterangan,
                'user_name' => $pembayaran->user->name,
                'penjualan' => [
                    'no_faktur' => $pembayaran->penjualan->no_faktur,
                    'total_formatted' => number_format($pembayaran->penjualan->total_setelah_diskon, 0, ',', '.'),
                    'tanggal_formatted' => $pembayaran->penjualan->tanggal->format('d/m/Y'),
                    'pelanggan' => [
                        'nama' => $pembayaran->penjualan->pelanggan->nama ?? 'Pelanggan Umum'
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'pembayaran' => $formattedPembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data pembayaran'
            ], 500);
        }
    }

    /**
     * Get payment data for printing
     */
    public function print($id)
    {
        try {
            $pembayaran = PembayaranPenjualan::with(['penjualan.pelanggan', 'user'])->findOrFail($id);

            // Get printer settings
            $printerSettings = PrinterSetting::first();

            // Format data for printing
            $printData = [
                'pembayaran' => [
                    'no_bukti' => $pembayaran->no_bukti,
                    'tanggal' => $pembayaran->tanggal->format('d/m/Y H:i'),
                    'jumlah_bayar' => number_format($pembayaran->jumlah_bayar, 0, ',', '.'),
                    'metode_pembayaran' => ucfirst($pembayaran->metode_pembayaran),
                    'status_bayar' => $this->getStatusBayarDisplay($pembayaran->status_bayar),
                    'keterangan' => $pembayaran->keterangan,
                    'user_name' => $pembayaran->user->name,
                ],
                'penjualan' => [
                    'no_faktur' => $pembayaran->penjualan->no_faktur,
                    'total' => number_format($pembayaran->penjualan->total_setelah_diskon, 0, ',', '.'),
                    'tanggal' => $pembayaran->penjualan->tanggal->format('d/m/Y'),
                    'pelanggan' => $pembayaran->penjualan->pelanggan->nama ?? 'Pelanggan Umum',
                ],
                'printer' => [
                    'name' => $printerSettings->printer_name ?? 'POS-58',
                    'paper_size' => $printerSettings->paper_size ?? '58mm',
                    'orientation' => $printerSettings->orientation ?? 'portrait',
                    'margin_top' => $printerSettings->margin_top ?? 0,
                    'margin_bottom' => $printerSettings->margin_bottom ?? 0,
                    'margin_left' => $printerSettings->margin_left ?? 0,
                    'margin_right' => $printerSettings->margin_right ?? 0,
                ],
                'company' => [
                    'name' => $printerSettings->company_name ?? 'Nama Perusahaan',
                    'address' => $printerSettings->company_address ?? 'Alamat Perusahaan',
                    'phone' => $printerSettings->company_phone ?? 'Telepon',
                    'website' => $printerSettings->company_website ?? 'Website',
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $printData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data untuk cetak'
            ], 500);
        }
    }

    /**
     * Get status bayar display text
     */
    private function getStatusBayarDisplay($status)
    {
        switch ($status) {
            case 'P':
                return 'Pelunasan';
            case 'D':
                return 'DP';
            case 'A':
                return 'Angsuran';
            case 'B':
                return 'Bayar Sebagian';
            default:
                return $status;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pembayaran = PembayaranPenjualan::findOrFail($id);

        // Check if payment can be deleted
        $today = \Carbon\Carbon::today();
        $paymentDate = \Carbon\Carbon::parse($pembayaran->created_at)->startOfDay();

        // Check if this is the latest payment
        $latestPayment = PembayaranPenjualan::where('penjualan_id', $pembayaran->penjualan_id)
            ->orderBy('created_at', 'desc')
            ->first();

        $isLatestPayment = $latestPayment && $latestPayment->id === $pembayaran->id;

        // Payment can only be deleted if:
        // 1. It's created today AND
        // 2. It's the latest payment (no newer payments exist)
        if (!$today->equalTo($paymentDate) || !$isLatestPayment) {
            $errorMessage = '';
            if (!$today->equalTo($paymentDate)) {
                $errorMessage = 'Pembayaran hanya bisa dihapus pada hari yang sama dengan pembuatan.';
            } else {
                $errorMessage = 'Pembayaran tidak dapat dihapus karena sudah ada pembayaran baru.';
            }

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }

            return back()->with('error', $errorMessage);
        }

        DB::beginTransaction();

        try {
            // Get the penjualan
            $penjualan = $pembayaran->penjualan;

            // Handle uang muka jika pembayaran menggunakan uang muka (status_uang_muka = 1)
            if (($pembayaran->status_uang_muka ?? 0) == 1) {
                // Cari record penggunaan uang muka yang terkait dengan pembayaran ini
                // Dicari berdasarkan penjualan_id dan jumlah yang sama
                // Note: Biasanya satu pembayaran uang muka = satu penggunaan uang muka dengan jumlah yang sama
                $penggunaanUangMuka = \App\Models\PenggunaanUangMukaPenjualan::where('penjualan_id', $penjualan->id)
                    ->where('jumlah_digunakan', $pembayaran->jumlah_bayar)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($penggunaanUangMuka) {
                    $uangMuka = $penggunaanUangMuka->uangMukaPelanggan;
                    $jumlahDigunakan = $penggunaanUangMuka->jumlah_digunakan;

                    // Kembalikan sisa uang muka (tambah kembali jumlah yang digunakan)
                    $uangMuka->sisa_uang_muka += $jumlahDigunakan;

                    // Update status uang muka jika perlu
                    // Jika sisa kembali > 0 dan status 'habis', ubah ke 'aktif'
                    if ($uangMuka->sisa_uang_muka > 0 && $uangMuka->status === 'habis') {
                        $uangMuka->status = 'aktif';
                    }

                    $uangMuka->save();

                    // Hapus record penggunaan uang muka
                    $penggunaanUangMuka->delete();

                    Log::info('Uang muka dikembalikan setelah pembayaran dihapus', [
                        'pembayaran_id' => $pembayaran->id,
                        'uang_muka_id' => $uangMuka->id,
                        'jumlah_dikembalikan' => $jumlahDigunakan,
                        'sisa_uang_muka_baru' => $uangMuka->sisa_uang_muka,
                        'status_uang_muka' => $uangMuka->status
                    ]);
                } else {
                    Log::warning('Penggunaan uang muka tidak ditemukan untuk pembayaran yang dihapus', [
                        'pembayaran_id' => $pembayaran->id,
                        'penjualan_id' => $penjualan->id,
                        'jumlah_bayar' => $pembayaran->jumlah_bayar,
                        'no_bukti' => $pembayaran->no_bukti
                    ]);
                }
            }

            // Hapus transaksi kas/bank jika ada (kecuali untuk uang muka yang kas_bank_id = null)
            if ($pembayaran->kas_bank_id) {
                try {
                    // Trigger database akan otomatis menangani penghapusan transaksi dan update saldo
                    Log::info('Pembayaran dengan kas_bank_id akan dihapus oleh trigger database', [
                        'pembayaran_id' => $pembayaran->id,
                        'kas_bank_id' => $pembayaran->kas_bank_id,
                        'jumlah' => $pembayaran->jumlah_bayar
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error in payment deletion: ' . $e->getMessage());
                    // Don't throw error, just log it
                }
            }

            // Delete the payment
            $pembayaran->delete();

            // Recalculate penjualan payment status
            $totalDibayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');

            if ($totalDibayar >= $penjualan->total_setelah_diskon) {
                $penjualan->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $penjualan->update(['status_pembayaran' => 'dp']);
            } else {
                $penjualan->update(['status_pembayaran' => 'belum_bayar']);
            }

            DB::commit();

            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dihapus.',
                    'data' => [
                        'penjualan_id' => $penjualan->id,
                        'total_dibayar' => $totalDibayar,
                        'status_pembayaran' => $totalDibayar >= $penjualan->total_setelah_diskon ? 'lunas' : ($totalDibayar > 0 ? 'dp' : 'belum_bayar')
                    ]
                ]);
            }

            return redirect()->route('pembayaran.index')
                ->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting payment: ' . $e->getMessage());

            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage()
                ], 422);
            }

            return back()->with('error', 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
