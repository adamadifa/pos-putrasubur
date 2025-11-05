<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPembelian;
use App\Models\Pembelian;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PembayaranPembelian::with(['pembelian.supplier', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_bukti', 'like', "%{$search}%")
                    ->orWhereHas('pembelian', function ($pembelianQuery) use ($search) {
                        $pembelianQuery->where('no_faktur', 'like', "%{$search}%")
                            ->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                                $supplierQuery->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Date range filter
        if ($request->filled('tanggal_dari_hidden')) {
            $tanggalDari = Carbon::parse($request->tanggal_dari_hidden)->startOfDay();
            $query->where('tanggal', '>=', $tanggalDari);
        }

        if ($request->filled('tanggal_sampai_hidden')) {
            $tanggalSampai = Carbon::parse($request->tanggal_sampai_hidden)->endOfDay();
            $query->where('tanggal', '<=', $tanggalSampai);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status_bayar', $request->status);
        }

        // Payment method filter
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $pembayaranPembelian = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembayaran-pembelian.index', compact('pembayaranPembelian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get pembelian that need payment (not fully paid)
        $pembelian = Pembelian::with(['supplier', 'pembayaranPembelian'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->filter(function ($p) {
                // Calculate total already paid
                $sudahDibayar = $p->pembayaranPembelian->sum('jumlah_bayar');
                $sisaBayar = $p->total - $sudahDibayar;

                // Only show transactions that still have remaining balance to pay
                return $sisaBayar > 0;
            })
            ->values(); // Reset array keys

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('nama')
            ->get();

        // Get kas/bank data
        $kasBank = \App\Models\KasBank::orderBy('nama')->get();

        return view('pembayaran-pembelian.create', compact('pembelian', 'metodePembayaran', 'kasBank'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the request data for debugging
        Log::info('Payment creation request', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

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
            'pembelian_id' => 'required|exists:pembelian,id',
            'tanggal' => 'required|date_format:d/m/Y',
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
            'pembelian_id.required' => 'Transaksi wajib dipilih.',
            'pembelian_id.exists' => 'Transaksi tidak valid.',
            'jumlah.required' => 'Jumlah bayar wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'kas_bank_id.exists' => 'Kas/Bank yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
            'tanggal.date_format' => 'Format tanggal tidak valid. Gunakan format dd/mm/yyyy.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ];

        try {
            $validated = $request->validate($validationRules, $customMessages);
        } catch (\Illuminate\Validation\ValidationException $e) {
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

        // Convert tanggal from dd/mm/yyyy to Y-m-d format
        try {
            $tanggalFormatted = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->format('Y-m-d');
            $validated['tanggal'] = $tanggalFormatted;
        } catch (\Exception $e) {
            $errorMessage = 'Format tanggal tidak valid. Gunakan format dd/mm/yyyy.';
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
            // Log the request data for debugging
            Log::info('Payment creation request', [
                'request_data' => $request->all(),
                'validated_data' => $validated,
                'jumlah_numeric' => $jumlahNumeric,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'] ?? null
            ]);

            // Get the pembelian
            $pembelian = Pembelian::findOrFail($validated['pembelian_id']);

            // Calculate total already paid (sebelum memproses uang muka baru)
            $sudahDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            $totalTransaksi = $pembelian->total;
            $sisaBayar = $totalTransaksi - $sudahDibayar;

            // Handle uang muka jika ada
            $totalUangMukaDigunakan = 0;
            $uangMukaData = [];

            if ($useUangMuka) {
                // Jika checkbox dicentang, jumlah yang diinput adalah jumlah uang muka yang digunakan
                $jumlahYangAkanDigunakan = $validated['jumlah_raw'];

                // Validasi: jumlah tidak boleh melebihi total sisa uang muka yang tersedia
                $totalSisaUangMuka = \App\Models\UangMukaSupplier::where('supplier_id', $pembelian->supplier_id)
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
                $availableUangMuka = \App\Models\UangMukaSupplier::where('supplier_id', $pembelian->supplier_id)
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
                        $uangMuka = \App\Models\UangMukaSupplier::find($uangMukaId);
                        if ($uangMuka && $uangMuka->supplier_id == $pembelian->supplier_id) {
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
            $noBukti = 'PB-' . date('Ymd') . '-' . str_pad($pembelian->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad(PembayaranPembelian::where('pembelian_id', $pembelian->id)->count() + 1, 2, '0', STR_PAD_LEFT);

            /**
             * LOGIKA STATUS PEMBAYARAN:
             * 
             * 1. PEMBAYARAN PERTAMA (sudahDibayar == 0):
             *    - Jika bayar >= total transaksi → P (Pelunasan)
             *    - Jika bayar < total transaksi → D (DP)
             * 
             * 2. PEMBAYARAN SELANJUTNYA (sudahDibayar > 0):
             *    - Jika (sudahDibayar + bayar) >= total transaksi → P (Pelunasan)
             *    - Jika (sudahDibayar + bayar) < total transaksi → A (Angsuran)
             * 
             * KODE STATUS:
             * P = Pelunasan (Lunas)
             * D = DP (Down Payment)
             * A = Angsuran
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
                $pembayaran = PembayaranPembelian::create([
                    'pembelian_id' => $validated['pembelian_id'],
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
                    $keteranganPenggunaan = $pembayaran && $noBukti ? "Penggunaan uang muka untuk pembayaran " . $noBukti : "Penggunaan uang muka untuk pembelian " . $pembelian->no_faktur;
                    \App\Models\PenggunaanUangMukaPembelian::create([
                        'uang_muka_supplier_id' => $umData['uang_muka_id'],
                        'pembelian_id' => $pembelian->id,
                        'jumlah_digunakan' => $umData['jumlah_digunakan'],
                        'tanggal_penggunaan' => $validated['tanggal'],
                        'keterangan' => $keteranganPenggunaan,
                        'user_id' => Auth::id(),
                    ]);
                }

                // Buat HANYA SATU record PembayaranPembelian untuk uang muka dengan status_uang_muka = 1
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
                // Format: PAY-UM-PO-YYYYMMDD-PEMBELIAN_ID-SEQUENCE
                $existingUmCount = PembayaranPembelian::where('pembelian_id', $pembelian->id)
                    ->where('status_uang_muka', 1)
                    ->whereDate('created_at', today())
                    ->count();

                $noBuktiUm = 'PAY-UM-PO-' . date('Ymd') . '-' . str_pad($pembelian->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($existingUmCount + 1, 3, '0', STR_PAD_LEFT);

                // Pastikan no_bukti unik (jika masih ada duplikasi, tambahkan timestamp)
                $counter = 1;
                while (PembayaranPembelian::where('no_bukti', $noBuktiUm)->exists()) {
                    $noBuktiUm = 'PAY-UM-PO-' . date('Ymd') . '-' . str_pad($pembelian->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($existingUmCount + 1 + $counter, 3, '0', STR_PAD_LEFT);
                    $counter++;
                }

                PembayaranPembelian::create([
                    'pembelian_id' => $pembelian->id,
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

            // Update pembelian payment status
            $totalDibayar = $sudahDibayar + ($pembayaran ? $pembayaran->jumlah_bayar : 0) + $totalUangMukaDigunakan;

            if ($totalDibayar >= $totalTransaksi) {
                $pembelian->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $pembelian->update(['status_pembayaran' => 'dp']);
            } else {
                $pembelian->update(['status_pembayaran' => 'belum_bayar']);
            }

            DB::commit();

            // Get payment record for response (bisa dari uang muka atau normal payment)
            $paymentRecord = $pembayaran;
            if (!$paymentRecord && $totalUangMukaDigunakan > 0) {
                // Jika menggunakan uang muka, ambil record uang muka yang baru dibuat
                $paymentRecord = PembayaranPembelian::where('pembelian_id', $pembelian->id)
                    ->where('status_uang_muka', 1)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            Log::info('Payment created successfully', [
                'pembayaran_id' => $paymentRecord ? $paymentRecord->id : null,
                'pembelian_id' => $pembelian->id,
                'amount' => $validated['jumlah_raw'],
                'method' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'] ?? null,
                'status_bayar' => $paymentRecord ? $paymentRecord->status_bayar : $statusBayar,
                'status_uang_muka' => $totalUangMukaDigunakan > 0 ? 1 : 0,
                'uang_muka_digunakan' => $totalUangMukaDigunakan,
                'payment_logic' => [
                    'sudah_dibayar' => $sudahDibayar,
                    'total_transaksi' => $totalTransaksi,
                    'is_first_payment' => $sudahDibayar == 0,
                    'total_after_payment' => $totalDibayar,
                    'pembelian_status' => $totalDibayar >= $totalTransaksi ? 'lunas' : ($totalDibayar > 0 ? 'dp' : 'belum_bayar')
                ]
            ]);

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil disimpan.' . ($totalUangMukaDigunakan > 0 ? ' Uang muka ' . number_format($totalUangMukaDigunakan, 0, ',', '.') . ' telah digunakan.' : ''),
                    'data' => [
                        'pembayaran_id' => $paymentRecord ? $paymentRecord->id : null,
                        'no_bukti' => $paymentRecord ? $paymentRecord->no_bukti : null,
                        'jumlah' => $paymentRecord ? $paymentRecord->jumlah_bayar : 0,
                        'kas_bank_id' => $paymentRecord ? $paymentRecord->kas_bank_id : null
                    ]
                ]);
            }

            return redirect()->route('pembayaran-pembelian.index')
                ->with('success', 'Pembayaran berhasil disimpan.');
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
    public function show($id)
    {
        try {
            $pembayaranPembelian = PembayaranPembelian::findByEncryptedId($id);

            if (!$pembayaranPembelian) {
                return redirect()->route('pembayaran-pembelian.index')
                    ->with('error', 'Pembayaran pembelian tidak ditemukan.');
            }

            $pembayaranPembelian->load(['pembelian.supplier', 'user']);

            return view('pembayaran-pembelian.show', compact('pembayaranPembelian'));
        } catch (\Exception $e) {
            return redirect()->route('pembayaran-pembelian.index')
                ->with('error', 'Pembayaran pembelian tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $pembayaranPembelian = PembayaranPembelian::findByEncryptedId($id);

            if (!$pembayaranPembelian) {
                return redirect()->route('pembayaran-pembelian.index')
                    ->with('error', 'Pembayaran pembelian tidak ditemukan.');
            }

            $pembayaranPembelian->load(['pembelian.supplier']);
            $pembelian = Pembelian::with(['supplier'])->get();

            return view('pembayaran-pembelian.edit', compact('pembayaranPembelian', 'pembelian'));
        } catch (\Exception $e) {
            return redirect()->route('pembayaran-pembelian.index')
                ->with('error', 'Pembayaran pembelian tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $pembayaranPembelian = PembayaranPembelian::findByEncryptedId($id);

            if (!$pembayaranPembelian) {
                return redirect()->route('pembayaran-pembelian.index')
                    ->with('error', 'Pembayaran pembelian tidak ditemukan.');
            }

            // Clean and parse jumlah input
            $jumlahInput = $request->input('jumlah_raw');
            $jumlahClean = preg_replace('/[^\d]/', '', $jumlahInput);
            $jumlahNumeric = (int) $jumlahClean;

            $validated = $request->validate([
                'pembelian_id' => 'required|exists:pembelian,id',
                'jumlah_raw' => 'required|string',
                'metode_pembayaran' => 'required|string|max:50',
                'kas_bank_id' => 'nullable|exists:kas_bank,id',
                'tanggal' => 'required|date',
                'status_bayar' => 'required|in:P,D,A,B',
                'status_uang_muka' => 'nullable|in:0,1',
                'keterangan' => 'nullable|string',
            ]);

            // Custom validation for jumlah
            if ($jumlahNumeric < 1000) {
                return back()->withInput()
                    ->with('error', 'Jumlah pembayaran minimal Rp 1.000.');
            }

            DB::beginTransaction();

            $pembelian = Pembelian::findOrFail($validated['pembelian_id']);
            $jumlahBayar = $jumlahNumeric;

            // Calculate sisa pembayaran excluding current payment
            $totalDibayarLain = $pembelian->pembayaranPembelian()
                ->where('id', '!=', $pembayaranPembelian->id)
                ->sum('jumlah_bayar');
            $sisaPembayaran = $pembelian->total - $totalDibayarLain;

            // Validate payment amount
            if ($jumlahBayar > $sisaPembayaran) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah pembayaran tidak boleh melebihi sisa pembayaran.');
            }

            // Update pembayaran
            $pembayaranPembelian->update([
                'pembelian_id' => $validated['pembelian_id'],
                'tanggal' => $validated['tanggal'],
                'jumlah_bayar' => $jumlahBayar,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'],
                'status_bayar' => $validated['status_bayar'],
                'status_uang_muka' => $validated['status_uang_muka'] ?? 0,
                'keterangan' => $validated['keterangan'],
            ]);

            // Update pembelian status
            $totalDibayar = $pembelian->pembayaranPembelian()->sum('jumlah_bayar');
            $statusPembayaran = 'belum_bayar';

            if ($totalDibayar >= $pembelian->total) {
                $statusPembayaran = 'lunas';
            } elseif ($totalDibayar > 0) {
                $statusPembayaran = 'dp';
            }

            $pembelian->update(['status_pembayaran' => $statusPembayaran]);

            DB::commit();

            return redirect()->route('pembayaran-pembelian.index')
                ->with('success', 'Pembayaran pembelian berhasil diperbarui.');
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
        $pembayaranPembelian = PembayaranPembelian::findByEncryptedId($id);

        if (!$pembayaranPembelian) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan.'
                ], 404);
            }
            return back()->with('error', 'Pembayaran tidak ditemukan.');
        }

        // Check if payment can be deleted
        $today = \Carbon\Carbon::today();
        $paymentDate = \Carbon\Carbon::parse($pembayaranPembelian->created_at)->startOfDay();

        // Check if this is the latest payment
        $latestPayment = PembayaranPembelian::where('pembelian_id', $pembayaranPembelian->pembelian_id)
            ->orderBy('created_at', 'desc')
            ->first();

        $isLatestPayment = $latestPayment && $latestPayment->id === $pembayaranPembelian->id;

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
            // Get the pembelian
            $pembelian = $pembayaranPembelian->pembelian;

            // Handle uang muka jika pembayaran menggunakan uang muka (status_uang_muka = 1)
            if ($pembayaranPembelian->status_uang_muka == 1) {
                // Cari record penggunaan uang muka yang terkait dengan pembayaran ini
                $penggunaanUangMuka = \App\Models\PenggunaanUangMukaPembelian::where('pembelian_id', $pembelian->id)
                    ->where('jumlah_digunakan', $pembayaranPembelian->jumlah_bayar)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($penggunaanUangMuka) {
                    $uangMuka = $penggunaanUangMuka->uangMukaSupplier;
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
                        'pembayaran_id' => $pembayaranPembelian->id,
                        'uang_muka_id' => $uangMuka->id,
                        'jumlah_dikembalikan' => $jumlahDigunakan,
                        'sisa_uang_muka_baru' => $uangMuka->sisa_uang_muka,
                        'status_uang_muka' => $uangMuka->status
                    ]);
                } else {
                    Log::warning('Penggunaan uang muka tidak ditemukan untuk pembayaran yang dihapus', [
                        'pembayaran_id' => $pembayaranPembelian->id,
                        'pembelian_id' => $pembelian->id,
                        'jumlah_bayar' => $pembayaranPembelian->jumlah_bayar,
                        'no_bukti' => $pembayaranPembelian->no_bukti
                    ]);
                }
            }

            // Delete the payment
            $pembayaranPembelian->delete();

            // Recalculate pembelian payment status
            $totalDibayar = PembayaranPembelian::where('pembelian_id', $pembelian->id)->sum('jumlah_bayar');

            if ($totalDibayar >= $pembelian->total) {
                $pembelian->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $pembelian->update(['status_pembayaran' => 'dp']);
            } else {
                $pembelian->update(['status_pembayaran' => 'belum_bayar']);
            }

            DB::commit();

            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dihapus.',
                    'data' => [
                        'pembelian_id' => $pembelian->id,
                        'total_dibayar' => $totalDibayar,
                        'status_pembayaran' => $totalDibayar >= $pembelian->total ? 'lunas' : ($totalDibayar > 0 ? 'dp' : 'belum_bayar')
                    ]
                ]);
            }

            return redirect()->route('pembelian.show', $pembelian->encrypted_id)
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

    /**
     * Get payment details for AJAX request
     */
    public function detail($id)
    {
        try {
            $pembayaranPembelian = PembayaranPembelian::findByEncryptedId($id);

            if (!$pembayaranPembelian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan.'
                ], 404);
            }

            $pembayaranPembelian->load(['pembelian.supplier', 'user']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaranPembelian->id,
                    'no_bukti' => $pembayaranPembelian->no_bukti,
                    'tanggal' => $pembayaranPembelian->tanggal,
                    'jumlah_bayar' => $pembayaranPembelian->jumlah_bayar,
                    'metode_pembayaran' => $pembayaranPembelian->metode_pembayaran,
                    'metode_pembayaran_display' => $pembayaranPembelian->metode_pembayaran_display,
                    'kas_bank_id' => $pembayaranPembelian->kas_bank_id,
                    'kas_bank' => $pembayaranPembelian->kasBank ? [
                        'id' => $pembayaranPembelian->kasBank->id,
                        'nama' => $pembayaranPembelian->kasBank->nama,
                        'jenis' => $pembayaranPembelian->kasBank->jenis,
                    ] : null,
                    'status_bayar' => $pembayaranPembelian->status_bayar,
                    'status_bayar_display' => $pembayaranPembelian->status_bayar_display,
                    'status_uang_muka' => $pembayaranPembelian->status_uang_muka ?? 0,
                    'keterangan' => $pembayaranPembelian->keterangan,
                    'pembelian' => [
                        'id' => $pembayaranPembelian->pembelian->id,
                        'no_faktur' => $pembayaranPembelian->pembelian->no_faktur,
                        'tanggal' => $pembayaranPembelian->pembelian->tanggal,
                        'total' => $pembayaranPembelian->pembelian->total,
                        'supplier' => [
                            'id' => $pembayaranPembelian->pembelian->supplier->id,
                            'nama' => $pembayaranPembelian->pembelian->supplier->nama,
                        ],
                    ],
                    'user' => [
                        'id' => $pembayaranPembelian->user->id,
                        'name' => $pembayaranPembelian->user->name,
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran pembelian tidak ditemukan.'
            ]);
        }
    }

    /**
     * Get print data for AJAX request
     */
    public function print($id)
    {
        try {
            $pembayaranPembelian = PembayaranPembelian::findByEncryptedId($id);

            if (!$pembayaranPembelian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan.'
                ], 404);
            }

            $pembayaranPembelian->load(['pembelian.supplier', 'user']);

            // Get company info (you might want to create a Company model or use config)
            $company = [
                'name' => config('app.name', 'Toko Saya'),
                'address' => 'Jl. Contoh No. 123, Kota',
                'phone' => '+62 123 456 789',
                'website' => 'www.tokosaya.com'
            ];

            $printData = [
                'pembayaran' => [
                    'no_bukti' => $pembayaranPembelian->no_bukti,
                    'tanggal' => $pembayaranPembelian->tanggal->format('d/m/Y H:i'),
                    'jumlah_bayar' => number_format($pembayaranPembelian->jumlah_bayar, 0, ',', '.'),
                    'metode_pembayaran' => $pembayaranPembelian->metode_pembayaran_display,
                    'kas_bank' => $pembayaranPembelian->kasBank ? $pembayaranPembelian->kasBank->nama : '-',
                    'status_bayar' => $pembayaranPembelian->status_bayar_display,
                    'keterangan' => $pembayaranPembelian->keterangan,
                    'user_name' => $pembayaranPembelian->user->name,
                ],
                'pembelian' => [
                    'no_faktur' => $pembayaranPembelian->pembelian->no_faktur,
                    'supplier' => $pembayaranPembelian->pembelian->supplier->nama,
                    'total' => number_format($pembayaranPembelian->pembelian->total, 0, ',', '.'),
                ],
                'company' => $company
            ];

            return response()->json([
                'success' => true,
                'data' => $printData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran pembelian tidak ditemukan.'
            ]);
        }
    }
}
