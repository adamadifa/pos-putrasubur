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

        $validated = $request->validate([
            'pembelian_id' => 'required|exists:pembelian,id',
            'jumlah' => 'required|string',
            'metode_pembayaran' => 'required|string|max:50',
            'kas_bank_id' => 'nullable|exists:kas_bank,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'pembelian_id.required' => 'Transaksi wajib dipilih.',
            'pembelian_id.exists' => 'Transaksi tidak valid.',
            'jumlah.required' => 'Jumlah bayar wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'kas_bank_id.exists' => 'Kas/Bank yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        // Validate metode pembayaran exists
        $metodePembayaran = \App\Models\MetodePembayaran::where('kode', $validated['metode_pembayaran'])
            ->where('status', true)
            ->first();

        if (!$metodePembayaran) {
            return back()->withInput()
                ->with('error', 'Metode pembayaran yang dipilih tidak valid atau tidak aktif.');
        }

        // Custom validation for jumlah
        if ($jumlahNumeric < 1000) {
            return back()->withInput()
                ->with('error', 'Jumlah pembayaran minimal Rp 1.000.');
        }

        // Update validated data with clean numeric value
        $validated['jumlah_raw'] = $jumlahNumeric;

        // Convert datetime-local to proper format
        try {
            $tanggal = \Carbon\Carbon::parse($validated['tanggal']);
            $validated['tanggal'] = $tanggal->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Format tanggal tidak valid.');
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

            // Calculate total already paid
            $sudahDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            $totalTransaksi = $pembelian->total;
            $sisaBayar = $totalTransaksi - $sudahDibayar;

            // Validate payment amount
            if ($validated['jumlah_raw'] > $sisaBayar) {
                return back()->withInput()
                    ->with('error', 'Jumlah bayar tidak boleh melebihi sisa yang harus dibayar (Rp ' . number_format($sisaBayar) . ').');
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
            $pembayaran = PembayaranPembelian::create([
                'pembelian_id' => $validated['pembelian_id'],
                'no_bukti' => $noBukti,
                'tanggal' => $validated['tanggal'],
                'jumlah_bayar' => $validated['jumlah_raw'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'],
                'status_bayar' => $statusBayar,
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id(),
            ]);

            // Update pembelian payment status
            $totalDibayar = $sudahDibayar + $validated['jumlah_raw'];

            if ($totalDibayar >= $totalTransaksi) {
                $pembelian->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $pembelian->update(['status_pembayaran' => 'dp']);
            } else {
                $pembelian->update(['status_pembayaran' => 'belum_bayar']);
            }

            DB::commit();

            Log::info('Payment created successfully', [
                'pembayaran_id' => $pembayaran->id,
                'pembelian_id' => $pembelian->id,
                'amount' => $validated['jumlah_raw'],
                'method' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'] ?? null,
                'status_bayar' => $statusBayar,
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
                    'message' => 'Pembayaran berhasil disimpan.',
                    'data' => [
                        'pembayaran_id' => $pembayaran->id,
                        'no_bukti' => $pembayaran->no_bukti,
                        'jumlah' => $pembayaran->jumlah_bayar,
                        'kas_bank_id' => $pembayaran->kas_bank_id
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

    /**
     * Get transactions for AJAX request (for search modal)
     */
    public function getTransactions(Request $request)
    {
        try {
            // Untuk testing yang sangat sederhana
            $testData = [
                [
                    'id' => 'TEST001',
                    'encrypted_id' => encrypt(1),
                    'no_faktur' => 'TEST001',
                    'tanggal' => '01/01/2024',
                    'total' => 1000000,
                    'sisa_pembayaran' => 500000,
                    'status_pembayaran' => 'belum_bayar',
                    'supplier' => [
                        'nama' => 'Supplier Test'
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'transactions' => $testData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get transaction details for AJAX request
     */
    public function getTransactionDetails(Request $request)
    {
        try {
            $encryptedId = $request->get('id');
            
            Log::info('getTransactionDetails called', [
                'encrypted_id' => $encryptedId,
                'request_data' => $request->all()
            ]);

            $pembelian = Pembelian::findByEncryptedId($encryptedId);

            if (!$pembelian) {
                Log::warning('Pembelian not found', [
                    'encrypted_id' => $encryptedId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.'
                ]);
            }

            $pembelian->load(['supplier', 'pembayaranPembelian']);
            
            $totalDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            $sisaPembayaran = $pembelian->total - $totalDibayar;

            $transaction = [
                'id' => $pembelian->id,
                'no_faktur' => $pembelian->no_faktur,
                'tanggal' => $pembelian->tanggal->format('d/m/Y'),
                'total' => $pembelian->total,
                'total_dibayar' => $totalDibayar,
                'sisa_pembayaran' => $sisaPembayaran,
                'status_pembayaran' => $pembelian->status_pembayaran,
                'supplier' => [
                    'id' => $pembelian->supplier->id,
                    'nama' => $pembelian->supplier->nama
                ]
            ];

            Log::info('Transaction details loaded', [
                'pembelian_id' => $pembelian->id,
                'transaction_data' => $transaction
            ]);

            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getTransactionDetails', [
                'encrypted_id' => $request->get('id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat detail transaksi: ' . $e->getMessage()
            ]);
        }
    }
}
