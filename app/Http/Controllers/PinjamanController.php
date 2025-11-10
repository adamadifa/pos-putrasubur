<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\PembayaranPinjaman;
use App\Models\Peminjam;
use App\Models\MetodePembayaran;
use App\Models\KasBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pinjaman::with(['peminjam', 'user', 'pembayaranPinjaman']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_pinjaman', 'like', "%{$search}%")
                    ->orWhereHas('peminjam', function ($peminjamQuery) use ($search) {
                        $peminjamQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode_peminjam', 'like', "%{$search}%");
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

        // Get paginated results
        $pinjaman = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // Calculate statistics
        $today = Carbon::today();
        $totalPinjaman = Pinjaman::count();
        $pinjamanHariIni = Pinjaman::whereDate('tanggal', $today)->count();
        $totalNilaiPinjaman = Pinjaman::sum('total_pinjaman');
        $totalDibayar = PembayaranPinjaman::sum('jumlah_bayar');
        $totalSisaPinjaman = $totalNilaiPinjaman - $totalDibayar;

        return view('pinjaman.index', compact(
            'pinjaman',
            'totalPinjaman',
            'pinjamanHariIni',
            'totalNilaiPinjaman',
            'totalDibayar',
            'totalSisaPinjaman'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate pinjaman number
        $noPinjaman = 'PIN-' . date('YmdHis');
        $peminjam = Peminjam::where('status', true)->orderBy('nama')->get();

        return view('pinjaman.create', compact('noPinjaman', 'peminjam'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Clean jumlah_pinjaman from currency format
        $jumlahInput = $request->input('jumlah_pinjaman');
        $jumlahClean = preg_replace('/[^\d]/', '', $jumlahInput);
        $jumlahNumeric = (float) $jumlahClean;

        $validated = $request->validate([
            'no_pinjaman' => 'required|string|max:50|unique:pinjaman',
            'peminjam_id' => 'required|exists:peminjam,id',
            'tanggal' => 'required|date',
            'jumlah_pinjaman' => 'required|string',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'no_pinjaman.required' => 'Nomor pinjaman wajib diisi.',
            'no_pinjaman.unique' => 'Nomor pinjaman sudah digunakan.',
            'peminjam_id.required' => 'Peminjam wajib dipilih.',
            'peminjam_id.exists' => 'Peminjam tidak valid.',
            'tanggal.required' => 'Tanggal pinjaman wajib diisi.',
            'jumlah_pinjaman.required' => 'Jumlah pinjaman wajib diisi.',
        ]);

        // Validate jumlah numeric
        if ($jumlahNumeric <= 0) {
            return back()->withInput()
                ->withErrors(['jumlah_pinjaman' => 'Jumlah pinjaman harus lebih dari 0.']);
        }

        try {
            DB::beginTransaction();

            $totalPinjaman = $jumlahNumeric;

            $pinjaman = Pinjaman::create([
                'no_pinjaman' => $validated['no_pinjaman'],
                'peminjam_id' => $validated['peminjam_id'],
                'tanggal' => $validated['tanggal'],
                'jumlah_pinjaman' => $jumlahNumeric,
                'total_pinjaman' => $totalPinjaman,
                'status_pembayaran' => 'belum_bayar',
                'keterangan' => $validated['keterangan'] ?? null,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            Log::info('Pinjaman created', [
                'pinjaman_id' => $pinjaman->id,
                'no_pinjaman' => $pinjaman->no_pinjaman,
                'total_pinjaman' => $pinjaman->total_pinjaman,
            ]);

            return redirect()->route('pinjaman.show', $pinjaman->encrypted_id)
                ->with('success', 'Pinjaman berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pinjaman: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat membuat pinjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId)
    {
        $pinjaman = Pinjaman::findByEncryptedId($encryptedId);
        $pinjaman->load(['peminjam', 'user', 'pembayaranPinjaman.user', 'pembayaranPinjaman.kasBank']);

        // Get payment history
        $riwayatPembayaran = PembayaranPinjaman::where('pinjaman_id', $pinjaman->id)
            ->with(['user', 'kasBank'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalDibayar = $riwayatPembayaran->sum('jumlah_bayar');
        $sisaPinjaman = $pinjaman->total_pinjaman - $totalDibayar;

        // Update status pembayaran
        if ($totalDibayar >= $pinjaman->total_pinjaman) {
            $pinjaman->status_pembayaran = 'lunas';
        } elseif ($totalDibayar > 0) {
            $pinjaman->status_pembayaran = 'sebagian';
        } else {
            $pinjaman->status_pembayaran = 'belum_bayar';
        }
        $pinjaman->save();

        return view('pinjaman.show', compact('pinjaman', 'riwayatPembayaran', 'totalDibayar', 'sisaPinjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        $pinjaman = Pinjaman::findByEncryptedId($encryptedId);
        $peminjam = Peminjam::where('status', true)->orderBy('nama')->get();

        return view('pinjaman.edit', compact('pinjaman', 'peminjam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        $pinjaman = Pinjaman::findByEncryptedId($encryptedId);

        // Clean jumlah_pinjaman from currency format
        $jumlahInput = $request->input('jumlah_pinjaman');
        $jumlahClean = preg_replace('/[^\d]/', '', $jumlahInput);
        $jumlahNumeric = (float) $jumlahClean;

        $validated = $request->validate([
            'peminjam_id' => 'required|exists:peminjam,id',
            'tanggal' => 'required|date',
            'jumlah_pinjaman' => 'required|string',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'peminjam_id.required' => 'Peminjam wajib dipilih.',
            'peminjam_id.exists' => 'Peminjam tidak valid.',
            'tanggal.required' => 'Tanggal pinjaman wajib diisi.',
            'jumlah_pinjaman.required' => 'Jumlah pinjaman wajib diisi.',
        ]);

        // Validate jumlah numeric
        if ($jumlahNumeric <= 0) {
            return back()->withInput()
                ->withErrors(['jumlah_pinjaman' => 'Jumlah pinjaman harus lebih dari 0.']);
        }

        try {
            DB::beginTransaction();

            $totalPinjaman = $jumlahNumeric;

            // Check if total pembayaran sudah melebihi total pinjaman baru
            $totalDibayar = $pinjaman->pembayaranPinjaman->sum('jumlah_bayar');
            if ($totalDibayar > $totalPinjaman) {
                return back()->withInput()
                    ->withErrors(['error' => 'Total pembayaran (' . number_format($totalDibayar, 0, ',', '.') . ') tidak boleh melebihi total pinjaman baru (' . number_format($totalPinjaman, 0, ',', '.') . ').']);
            }

            $pinjaman->update([
                'peminjam_id' => $validated['peminjam_id'],
                'tanggal' => $validated['tanggal'],
                'jumlah_pinjaman' => $jumlahNumeric,
                'total_pinjaman' => $totalPinjaman,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Update status pembayaran
            if ($totalDibayar >= $totalPinjaman) {
                $pinjaman->status_pembayaran = 'lunas';
            } elseif ($totalDibayar > 0) {
                $pinjaman->status_pembayaran = 'sebagian';
            } else {
                $pinjaman->status_pembayaran = 'belum_bayar';
            }
            $pinjaman->save();

            DB::commit();

            Log::info('Pinjaman updated', [
                'pinjaman_id' => $pinjaman->id,
                'no_pinjaman' => $pinjaman->no_pinjaman,
            ]);

            return redirect()->route('pinjaman.show', $pinjaman->encrypted_id)
                ->with('success', 'Pinjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pinjaman: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pinjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId)
    {
        $pinjaman = Pinjaman::findByEncryptedId($encryptedId);

        // Check if there are any payments
        if ($pinjaman->pembayaranPinjaman->count() > 0) {
            return back()->withErrors(['error' => 'Pinjaman tidak dapat dihapus karena sudah ada pembayaran.']);
        }

        try {
            DB::beginTransaction();

            $pinjaman->delete();

            DB::commit();

            Log::info('Pinjaman deleted', [
                'pinjaman_id' => $pinjaman->id,
                'no_pinjaman' => $pinjaman->no_pinjaman,
            ]);

            return redirect()->route('pinjaman.index')
                ->with('success', 'Pinjaman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting pinjaman: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus pinjaman: ' . $e->getMessage()]);
        }
    }

    /**
     * Store payment for pinjaman
     */
    public function storePayment(Request $request, $encryptedId)
    {
        $pinjaman = Pinjaman::findByEncryptedId($encryptedId);

        // Clean and parse jumlah input
        $jumlahInput = $request->input('jumlah');
        $jumlahClean = preg_replace('/[^\d]/', '', $jumlahInput);
        $jumlahNumeric = (float) $jumlahClean;

        $validated = $request->validate([
            'tanggal' => 'required|date_format:d/m/Y',
            'jumlah' => 'required|string',
            'metode_pembayaran' => 'required|string|exists:metode_pembayaran,kode',
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'tanggal.required' => 'Tanggal pembayaran wajib diisi.',
            'tanggal.date_format' => 'Format tanggal tidak valid. Gunakan format dd/mm/yyyy.',
            'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.exists' => 'Metode pembayaran tidak valid.',
            'kas_bank_id.required' => 'Kas/Bank wajib dipilih.',
            'kas_bank_id.exists' => 'Kas/Bank tidak valid.',
        ]);

        // Validate jumlah numeric
        if ($jumlahNumeric <= 0) {
            return back()->withInput()
                ->withErrors(['jumlah' => 'Jumlah pembayaran harus lebih dari 0.']);
        }

        try {
            DB::beginTransaction();

            $totalDibayar = $pinjaman->pembayaranPinjaman->sum('jumlah_bayar');
            $sisaPinjaman = $pinjaman->total_pinjaman - $totalDibayar;

            if ($jumlahNumeric > $sisaPinjaman) {
                return back()->withInput()
                    ->withErrors(['jumlah' => 'Jumlah pembayaran (' . number_format($jumlahNumeric, 0, ',', '.') . ') melebihi sisa pinjaman (' . number_format($sisaPinjaman, 0, ',', '.') . ').']);
            }

            // Generate unique no_bukti
            $existingCount = PembayaranPinjaman::where('pinjaman_id', $pinjaman->id)
                ->whereDate('created_at', today())
                ->count();

            $noBukti = 'PAY-PIN-' . date('Ymd') . '-' . str_pad($pinjaman->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($existingCount + 1, 3, '0', STR_PAD_LEFT);

            // Pastikan no_bukti unik
            $counter = 1;
            while (PembayaranPinjaman::where('no_bukti', $noBukti)->exists()) {
                $noBukti = 'PAY-PIN-' . date('Ymd') . '-' . str_pad($pinjaman->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($existingCount + 1 + $counter, 3, '0', STR_PAD_LEFT);
                $counter++;
            }

            // Tentukan status_bayar
            $totalSetelahPembayaran = $totalDibayar + $jumlahNumeric;
            $statusBayar = ($totalSetelahPembayaran >= $pinjaman->total_pinjaman) ? 'P' : 'A';

            $pembayaran = PembayaranPinjaman::create([
                'pinjaman_id' => $pinjaman->id,
                'no_bukti' => $noBukti,
                'tanggal' => Carbon::createFromFormat('d/m/Y', $validated['tanggal']),
                'jumlah_bayar' => $jumlahNumeric,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'kas_bank_id' => $validated['kas_bank_id'],
                'status_bayar' => $statusBayar,
                'keterangan' => $validated['keterangan'] ?? null,
                'user_id' => Auth::id(),
            ]);

            // Update status pembayaran pinjaman
            if ($totalSetelahPembayaran >= $pinjaman->total_pinjaman) {
                $pinjaman->status_pembayaran = 'lunas';
            } elseif ($totalSetelahPembayaran > 0) {
                $pinjaman->status_pembayaran = 'sebagian';
            } else {
                $pinjaman->status_pembayaran = 'belum_bayar';
            }
            $pinjaman->save();

            // Update kas/bank saldo (Debet = menambah saldo)
            $kasBank = KasBank::find($validated['kas_bank_id']);
            if ($kasBank) {
                $kasBank->updateSaldo($jumlahNumeric, 'D');
            }

            // Create transaksi kas/bank
            \App\Models\TransaksiKasBank::create([
                'kas_bank_id' => $validated['kas_bank_id'],
                'tanggal' => Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->toDateString(),
                'no_bukti' => 'TRX-PIN-' . date('Ymd') . '-' . str_pad($pembayaran->id, 4, '0', STR_PAD_LEFT),
                'jenis_transaksi' => 'D', // Debet = pemasukan
                'kategori_transaksi' => 'MN', // Manual
                'referensi_id' => $pembayaran->id,
                'referensi_tipe' => 'PPN', // Pembayaran Pinjaman
                'jumlah' => $jumlahNumeric,
                'saldo_sebelum' => $kasBank->saldo - $jumlahNumeric,
                'saldo_sesudah' => $kasBank->saldo,
                'keterangan' => "Pembayaran pinjaman {$noBukti} - {$pinjaman->no_pinjaman}",
                'user_id' => Auth::id()
            ]);

            DB::commit();

            Log::info('Pembayaran pinjaman created', [
                'pembayaran_id' => $pembayaran->id,
                'pinjaman_id' => $pinjaman->id,
                'jumlah_bayar' => $jumlahNumeric,
            ]);

            return redirect()->route('pinjaman.show', $pinjaman->encrypted_id)
                ->with('success', 'Pembayaran pinjaman berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pembayaran pinjaman: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambahkan pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete payment for pinjaman
     */
    public function destroyPayment($encryptedPembayaranId)
    {
        try {
            $pembayaran = PembayaranPinjaman::findByEncryptedId($encryptedPembayaranId);
            $pinjaman = $pembayaran->pinjaman;

            // Simpan data untuk log sebelum dihapus
            $pembayaranId = $pembayaran->id;
            $jumlahBayar = $pembayaran->jumlah_bayar;
            $pinjamanId = $pinjaman->id;

            DB::beginTransaction();

            // Hapus transaksi kas/bank terkait
            $transaksi = \App\Models\TransaksiKasBank::where('referensi_id', $pembayaran->id)
                ->where('referensi_tipe', 'PPN')
                ->first();

            if ($transaksi) {
                $kasBank = KasBank::find($transaksi->kas_bank_id);
                if ($kasBank) {
                    // Kembalikan saldo (Kredit = mengurangi saldo yang sebelumnya ditambah dengan Debet)
                    $kasBank->updateSaldo($pembayaran->jumlah_bayar, 'K');
                }
                $transaksi->delete();
            } else {
                // Jika transaksi tidak ditemukan, tetap update saldo jika ada kas_bank_id
                if ($pembayaran->kas_bank_id) {
                    $kasBank = KasBank::find($pembayaran->kas_bank_id);
                    if ($kasBank) {
                        $kasBank->updateSaldo($pembayaran->jumlah_bayar, 'K');
                    }
                }
            }

            // Hapus pembayaran
            $pembayaran->delete();

            // Update status pinjaman
            $totalDibayar = $pinjaman->pembayaranPinjaman->sum('jumlah_bayar');

            if ($totalDibayar >= $pinjaman->total_pinjaman) {
                $pinjaman->status_pembayaran = 'lunas';
            } elseif ($totalDibayar > 0) {
                $pinjaman->status_pembayaran = 'sebagian';
            } else {
                $pinjaman->status_pembayaran = 'belum_bayar';
            }
            $pinjaman->save();

            DB::commit();

            Log::info('Pembayaran pinjaman deleted', [
                'pembayaran_id' => $pembayaranId,
                'pinjaman_id' => $pinjamanId,
                'jumlah_bayar' => $jumlahBayar,
            ]);

            return redirect()->route('pinjaman.show', $pinjaman->encrypted_id)
                ->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting pembayaran pinjaman: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus pembayaran: ' . $e->getMessage()]);
        }
    }
}
