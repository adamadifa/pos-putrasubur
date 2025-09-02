<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwalBulanan;
use App\Models\KasBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class SaldoAwalBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaldoAwalBulanan::with(['kasBank', 'user']);

        // Filter by kas/bank
        if ($request->filled('kas_bank_id')) {
            $query->where('kas_bank_id', $request->kas_bank_id);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $request->tahun);
        } else {
            $query->where('periode_tahun', now()->year);
        }

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->where('periode_bulan', $request->bulan);
        }

        $saldoAwal = $query->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(10);

        $kasBankList = KasBank::orderBy('nama')->get();
        $tahunList = range(2020, now()->year + 1);
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return view('saldo-awal-bulanan.index', compact('saldoAwal', 'kasBankList', 'tahunList', 'bulanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kasBankList = KasBank::orderBy('nama')->get();
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $tahunList = range(2020, now()->year + 1);

        return view('saldo-awal-bulanan.create', compact('kasBankList', 'bulanList', 'tahunList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|min:2020',
            'saldo_awal_raw' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Cek apakah sudah ada saldo awal untuk periode yang sama
        $existingSaldo = SaldoAwalBulanan::where('kas_bank_id', $request->kas_bank_id)
            ->where('periode_bulan', $request->periode_bulan)
            ->where('periode_tahun', $request->periode_tahun)
            ->exists();

        if ($existingSaldo) {
            return back()->withErrors(['periode' => 'Saldo awal untuk periode ini sudah ada.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $saldoAwal = SaldoAwalBulanan::create([
                'kas_bank_id' => $request->kas_bank_id,
                'periode_bulan' => $request->periode_bulan,
                'periode_tahun' => $request->periode_tahun,
                'saldo_awal' => $request->saldo_awal_raw,
                'keterangan' => $request->keterangan,
                'user_id' => auth()->id()
            ]);

            DB::commit();

            return redirect()->route('saldo-awal-bulanan.index')
                ->with('success', 'Saldo awal bulanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaldoAwalBulanan $saldoAwalBulanan)
    {
        // Cek apakah bisa dihapus
        if (!SaldoAwalBulanan::canEdit($saldoAwalBulanan->kas_bank_id, $saldoAwalBulanan->periode_bulan, $saldoAwalBulanan->periode_tahun)) {
            return redirect()->route('saldo-awal-bulanan.index')
                ->with('error', 'Saldo awal tidak dapat dihapus karena sudah ada saldo awal bulan berikutnya.');
        }

        try {
            DB::beginTransaction();

            $saldoAwalBulanan->delete();

            DB::commit();

            return redirect()->route('saldo-awal-bulanan.index')
                ->with('success', 'Saldo awal bulanan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('saldo-awal-bulanan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get saldo akhir bulan sebelumnya untuk digunakan sebagai saldo awal
     */
    public function getSaldoAkhirBulanSebelumnya(Request $request): JsonResponse
    {
        $request->validate([
            'kas_bank_id' => 'required|exists:kas_bank,id',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|min:2020',
        ]);

        try {
            $kasBankId = $request->kas_bank_id;
            $periodeBulan = $request->periode_bulan;
            $periodeTahun = $request->periode_tahun;

            // Hitung bulan dan tahun sebelumnya
            $tanggalPeriode = Carbon::create($periodeTahun, $periodeBulan, 1);
            $bulanSebelumnya = $tanggalPeriode->copy()->subMonth();
            $bulanSebelumnyaBulan = $bulanSebelumnya->month;
            $bulanSebelumnyaTahun = $bulanSebelumnya->year;

            // Ambil saldo awal bulan sebelumnya
            $saldoAwalBulanSebelumnya = SaldoAwalBulanan::getSaldoAwal(
                $kasBankId,
                $bulanSebelumnyaBulan,
                $bulanSebelumnyaTahun
            );

            // Ambil kas/bank untuk menghitung transaksi
            $kasBank = KasBank::findOrFail($kasBankId);

            // Hitung total transaksi bulan sebelumnya
            $totalTransaksi = $kasBank->transaksiKasBank()
                ->whereYear('tanggal', $bulanSebelumnyaTahun)
                ->whereMonth('tanggal', $bulanSebelumnyaBulan)
                ->get()
                ->sum(function ($transaksi) {
                    return $transaksi->jenis_transaksi == 'D' ? $transaksi->jumlah : -$transaksi->jumlah;
                });

            // Hitung saldo akhir bulan sebelumnya
            $saldoAkhirBulanSebelumnya = $saldoAwalBulanSebelumnya + $totalTransaksi;

            // Cek apakah sudah ada saldo awal untuk periode yang dipilih
            $existingSaldo = SaldoAwalBulanan::where('kas_bank_id', $kasBankId)
                ->where('periode_bulan', $periodeBulan)
                ->where('periode_tahun', $periodeTahun)
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'saldo_akhir_bulan_sebelumnya' => $saldoAkhirBulanSebelumnya,
                    'saldo_awal_bulan_sebelumnya' => $saldoAwalBulanSebelumnya,
                    'total_transaksi_bulan_sebelumnya' => $totalTransaksi,
                    'bulan_sebelumnya' => $bulanSebelumnya->format('F Y'),
                    'periode_dipilih' => $tanggalPeriode->format('F Y'),
                    'sudah_ada_saldo_awal' => $existingSaldo ? true : false,
                    'saldo_awal_terdaftar' => $existingSaldo ? $existingSaldo->saldo_awal : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
