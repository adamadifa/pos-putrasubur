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
                $sisaBayar = $p->total - $sudahDibayar;

                // Only show transactions that still have remaining balance to pay
                return $sisaBayar > 0;
            })
            ->values(); // Reset array keys

        // Get active payment methods
        $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
            ->orderBy('nama')
            ->get();

        return view('pembayaran.create', compact('penjualan', 'metodePembayaran'));
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
            'penjualan_id' => 'required|exists:penjualan,id',
            'jumlah' => 'required|string',
            'metode_pembayaran' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'penjualan_id.required' => 'Transaksi wajib dipilih.',
            'penjualan_id.exists' => 'Transaksi tidak valid.',
            'jumlah.required' => 'Jumlah bayar wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
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
            // Get the penjualan
            $penjualan = Penjualan::findOrFail($validated['penjualan_id']);

            // Calculate total already paid
            $sudahDibayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
            $totalTransaksi = $penjualan->total;
            $sisaBayar = $totalTransaksi - $sudahDibayar;

            // Validate payment amount
            if ($validated['jumlah_raw'] > $sisaBayar) {
                return back()->withInput()
                    ->with('error', 'Jumlah bayar tidak boleh melebihi sisa yang harus dibayar (Rp ' . number_format($sisaBayar) . ').');
            }

            // Generate payment reference number
            $noBukti = 'PAY-' . date('Ymd') . '-' . str_pad($penjualan->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad(PembayaranPenjualan::where('penjualan_id', $penjualan->id)->count() + 1, 2, '0', STR_PAD_LEFT);

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
            $pembayaran = PembayaranPenjualan::create([
                'penjualan_id' => $validated['penjualan_id'],
                'no_bukti' => $noBukti,
                'tanggal' => $validated['tanggal'],
                'jumlah_bayar' => $validated['jumlah_raw'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_bayar' => $statusBayar,
                'keterangan' => $validated['keterangan'],
                'user_id' => Auth::id(),
            ]);

            // Update penjualan payment status
            $totalDibayar = $sudahDibayar + $validated['jumlah_raw'];

            if ($totalDibayar >= $totalTransaksi) {
                $penjualan->update(['status_pembayaran' => 'lunas']);
            } elseif ($totalDibayar > 0) {
                $penjualan->update(['status_pembayaran' => 'dp']);
            } else {
                $penjualan->update(['status_pembayaran' => 'belum_bayar']);
            }

            DB::commit();

            Log::info('Payment created successfully', [
                'pembayaran_id' => $pembayaran->id,
                'penjualan_id' => $penjualan->id,
                'amount' => $validated['jumlah_raw'],
                'method' => $validated['metode_pembayaran'],
                'status_bayar' => $statusBayar,
                'payment_logic' => [
                    'sudah_dibayar' => $sudahDibayar,
                    'total_transaksi' => $totalTransaksi,
                    'is_first_payment' => $sudahDibayar == 0,
                    'total_after_payment' => $totalDibayar,
                    'penjualan_status' => $totalDibayar >= $totalTransaksi ? 'lunas' : ($totalDibayar > 0 ? 'dp' : 'belum_bayar')
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
                        'jumlah' => $pembayaran->jumlah_bayar
                    ]
                ]);
            }

            return redirect()->route('pembayaran.index')
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
            $totalTransaksi = $penjualan->total;
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
                'keterangan' => $pembayaran->keterangan,
                'user_name' => $pembayaran->user->name,
                'penjualan' => [
                    'no_faktur' => $pembayaran->penjualan->no_faktur,
                    'total_formatted' => number_format($pembayaran->penjualan->total, 0, ',', '.'),
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
                    'total' => number_format($pembayaran->penjualan->total, 0, ',', '.'),
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

            // Delete the payment
            $pembayaran->delete();

            // Recalculate penjualan payment status
            $totalDibayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');

            if ($totalDibayar >= $penjualan->total) {
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
                        'status_pembayaran' => $totalDibayar >= $penjualan->total ? 'lunas' : ($totalDibayar > 0 ? 'dp' : 'belum_bayar')
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
