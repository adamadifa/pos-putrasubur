<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPembelian;
use App\Models\Pembelian;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class PembayaranPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pembayaran-pembelian.index', compact('pembayaranPembelian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pembelian = Pembelian::with(['supplier'])
            ->whereIn('status_pembayaran', ['belum_bayar', 'dp'])
            ->get()
            ->filter(function ($p) {
                return $p->sisa_pembayaran > 0;
            });

        return view('pembayaran-pembelian.create', compact('pembelian'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if it's an AJAX request (from modal)
        if ($request->ajax()) {
            $validated = $request->validate([
                'pembelian_id' => 'required|exists:pembelian,id',
                'jumlah' => 'required|numeric|min:0.01',
                'metode_pembayaran' => 'required|string|max:50',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
            ]);

            try {
                DB::beginTransaction();

                $pembelian = Pembelian::findOrFail($validated['pembelian_id']);
                $jumlahBayar = $validated['jumlah'];
                $sisaPembayaran = $pembelian->sisa_pembayaran;

                // Calculate total already paid
                $sudahDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');

                // Validate payment amount
                if ($jumlahBayar > $sisaPembayaran) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah pembayaran tidak boleh melebihi sisa pembayaran.'
                    ], 422);
                }

                // Generate no_bukti
                $noBukti = 'PB-' . date('Ymd') . '-' . str_pad(PembayaranPembelian::whereDate('tanggal', date('Y-m-d'))->count() + 1, 4, '0', STR_PAD_LEFT);

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
                    if ($jumlahBayar >= $pembelian->total) {
                        $statusBayar = 'P'; // Pelunasan (full payment)
                    } else {
                        $statusBayar = 'D'; // DP (partial payment)
                    }
                } else {
                    // Subsequent payments
                    $totalAfterPayment = $sudahDibayar + $jumlahBayar;
                    if ($totalAfterPayment >= $pembelian->total) {
                        $statusBayar = 'P'; // Pelunasan (final payment)
                    } else {
                        $statusBayar = 'A'; // Angsuran (partial payment)
                    }
                }

                // Create pembayaran
                $pembayaran = PembayaranPembelian::create([
                    'pembelian_id' => $validated['pembelian_id'],
                    'no_bukti' => $noBukti,
                    'tanggal' => $validated['tanggal'],
                    'jumlah_bayar' => $jumlahBayar,
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_bayar' => $statusBayar,
                    'keterangan' => $validated['keterangan'],
                    'user_id' => auth()->id(),
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

                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil ditambahkan. No. Bukti: ' . $noBukti
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }

        // Handle regular form submission (from create page)
        $validated = $request->validate([
            'pembelian_id' => 'required|exists:pembelian,id',
            'jumlah_raw' => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'status_bayar' => 'required|in:P,D,A,B',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $pembelian = Pembelian::findOrFail($validated['pembelian_id']);
            $jumlahBayar = $validated['jumlah_raw'];
            $sisaPembayaran = $pembelian->sisa_pembayaran;

            // Calculate total already paid
            $sudahDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');

            // Validate payment amount
            if ($jumlahBayar > $sisaPembayaran) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah pembayaran tidak boleh melebihi sisa pembayaran.');
            }

            // Generate no_bukti
            $noBukti = 'PB-' . date('Ymd') . '-' . str_pad(PembayaranPembelian::whereDate('tanggal', date('Y-m-d'))->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create pembayaran
            $pembayaran = PembayaranPembelian::create([
                'pembelian_id' => $validated['pembelian_id'],
                'no_bukti' => $noBukti,
                'tanggal' => $validated['tanggal'],
                'jumlah_bayar' => $jumlahBayar,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_bayar' => $validated['status_bayar'],
                'keterangan' => $validated['keterangan'],
                'user_id' => auth()->id(),
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
                ->with('success', 'Pembayaran pembelian berhasil ditambahkan.');
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
            $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier', 'user'])
                ->findOrFail(Crypt::decryptString($id));

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
            $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier'])->findOrFail(Crypt::decryptString($id));
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
            $pembayaranPembelian = PembayaranPembelian::findOrFail(Crypt::decryptString($id));

            $validated = $request->validate([
                'pembelian_id' => 'required|exists:pembelian,id',
                'jumlah_raw' => 'required|numeric|min:0.01',
                'metode_pembayaran' => 'required|string|max:50',
                'tanggal' => 'required|date',
                'status_bayar' => 'required|in:P,D,A,B',
                'keterangan' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $pembelian = Pembelian::findOrFail($validated['pembelian_id']);
            $jumlahBayar = $validated['jumlah_raw'];

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
        try {
            $pembayaranPembelian = PembayaranPembelian::findOrFail(Crypt::decryptString($id));

            // Check if payment is from today
            if ($pembayaranPembelian->tanggal->format('Y-m-d') !== date('Y-m-d')) {
                return redirect()->route('pembayaran-pembelian.index')
                    ->with('error', 'Pembayaran hanya dapat dihapus pada hari yang sama.');
            }

            DB::beginTransaction();

            $pembelian = $pembayaranPembelian->pembelian;
            $pembayaranPembelian->delete();

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
                ->with('success', 'Pembayaran pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pembayaran-pembelian.index')
                ->with('error', 'Pembayaran pembelian tidak ditemukan.');
        }
    }

    /**
     * Get payment details for AJAX request
     */
    public function detail($id)
    {
        try {
            $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier', 'user'])
                ->findOrFail(Crypt::decryptString($id));

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaranPembelian->id,
                    'no_bukti' => $pembayaranPembelian->no_bukti,
                    'tanggal' => $pembayaranPembelian->tanggal->format('d/m/Y H:i'),
                    'jumlah_bayar' => number_format($pembayaranPembelian->jumlah_bayar, 0, ',', '.'),
                    'metode_pembayaran' => $pembayaranPembelian->metode_pembayaran_display,
                    'status_bayar' => $pembayaranPembelian->status_bayar_display,
                    'keterangan' => $pembayaranPembelian->keterangan,
                    'pembelian' => [
                        'no_faktur' => $pembayaranPembelian->pembelian->no_faktur,
                        'tanggal' => $pembayaranPembelian->pembelian->tanggal->format('d/m/Y'),
                        'total' => number_format($pembayaranPembelian->pembelian->total, 0, ',', '.'),
                        'supplier' => $pembayaranPembelian->pembelian->supplier->nama,
                    ],
                    'user' => $pembayaranPembelian->user->name,
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
            $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier', 'user'])
                ->findOrFail(Crypt::decryptString($id));

            $printerSetting = PrinterSetting::first();

            $printData = [
                'printer' => $printerSetting ? $printerSetting->printer_name : 'Default Printer',
                'data' => [
                    'title' => 'BUKTI PEMBAYARAN PEMBELIAN',
                    'no_bukti' => $pembayaranPembelian->no_bukti,
                    'tanggal' => $pembayaranPembelian->tanggal->format('d/m/Y H:i'),
                    'supplier' => $pembayaranPembelian->pembelian->supplier->nama,
                    'no_faktur' => $pembayaranPembelian->pembelian->no_faktur,
                    'jumlah_bayar' => number_format($pembayaranPembelian->jumlah_bayar, 0, ',', '.'),
                    'metode_pembayaran' => $pembayaranPembelian->metode_pembayaran_display,
                    'status_bayar' => $pembayaranPembelian->status_bayar_display,
                    'keterangan' => $pembayaranPembelian->keterangan,
                    'user' => $pembayaranPembelian->user->name,
                ]
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
