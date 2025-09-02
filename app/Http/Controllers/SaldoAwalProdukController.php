<?php

namespace App\Http\Controllers;

use App\Models\SaldoAwalProduk;
use App\Models\DetailSaldoAwalProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class SaldoAwalProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaldoAwalProduk::with(['details.produk.kategori', 'details.produk.satuan', 'user']);

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
            ->orderBy('created_at', 'desc')
            ->paginate(20);

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

        return view('saldo-awal-produk.index', compact('saldoAwal', 'tahunList', 'bulanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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

        return view('saldo-awal-produk.create', compact('bulanList', 'tahunList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|min:2020',
            'saldo_awal' => 'required|array',
            'saldo_awal.*' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah sudah ada saldo awal untuk periode yang sama
            $existingSaldoAwal = SaldoAwalProduk::where('periode_bulan', $request->periode_bulan)
                ->where('periode_tahun', $request->periode_tahun)
                ->first();

            if ($existingSaldoAwal) {
                return redirect()->back()
                    ->with('error', 'Saldo awal untuk periode ' . $this->getBulanNama($request->periode_bulan) . ' ' . $request->periode_tahun . ' sudah ada.');
            }

            // Cek apakah saldo awal bulan sebelumnya sudah di-set
            $bulanSebelumnya = $request->periode_bulan - 1;
            $tahunSebelumnya = $request->periode_tahun;

            if ($bulanSebelumnya <= 0) {
                $bulanSebelumnya = 12;
                $tahunSebelumnya = $request->periode_tahun - 1;
            }

            // Cek apakah ada saldo awal bulan sebelumnya
            $saldoAwalSebelumnya = SaldoAwalProduk::where('periode_bulan', $bulanSebelumnya)
                ->where('periode_tahun', $tahunSebelumnya)
                ->first();

            if (!$saldoAwalSebelumnya) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menyimpan saldo awal ' . $this->getBulanNama($request->periode_bulan) . ' ' . $request->periode_tahun . ' karena saldo awal ' . $this->getBulanNama($bulanSebelumnya) . ' ' . $tahunSebelumnya . ' belum di-set. Silakan set saldo awal bulan sebelumnya terlebih dahulu.')
                    ->withInput();
            }

            // Buat header saldo awal
            $saldoAwalHeader = SaldoAwalProduk::create([
                'periode_bulan' => $request->periode_bulan,
                'periode_tahun' => $request->periode_tahun,
                'keterangan' => $request->keterangan,
                'user_id' => auth()->id()
            ]);

            $createdCount = 0;
            foreach ($request->saldo_awal as $produkId => $saldoAwalValue) {
                // Skip jika saldo awal 0 atau kosong
                if ($saldoAwalValue <= 0) {
                    continue;
                }

                // Cek apakah produk ada
                $produk = Produk::find($produkId);
                if (!$produk) {
                    continue;
                }

                // Buat detail saldo awal
                DetailSaldoAwalProduk::create([
                    'saldo_awal_produk_id' => $saldoAwalHeader->id,
                    'produk_id' => $produkId,
                    'saldo_awal' => $saldoAwalValue
                ]);
                $createdCount++;
            }

            DB::commit();

            if ($createdCount > 0) {
                return redirect()->route('saldo-awal-produk.index')
                    ->with('success', "Berhasil menyimpan {$createdCount} saldo awal produk untuk periode {$this->getBulanNama($request->periode_bulan)} {$request->periode_tahun}");
            } else {
                return redirect()->back()
                    ->with('error', 'Tidak ada saldo awal yang disimpan. Pastikan minimal satu produk memiliki saldo awal > 0.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaldoAwalProduk $saldoAwalProduk)
    {
        // Cek apakah bisa dihapus
        if (!SaldoAwalProduk::canEdit($saldoAwalProduk->periode_bulan, $saldoAwalProduk->periode_tahun)) {
            return redirect()->route('saldo-awal-produk.index')
                ->with('error', 'Saldo awal tidak dapat dihapus karena sudah ada saldo awal bulan berikutnya.');
        }

        try {
            DB::beginTransaction();

            // Hapus header akan otomatis menghapus semua detail karena cascade delete
            $saldoAwalProduk->delete();

            DB::commit();

            return redirect()->route('saldo-awal-produk.index')
                ->with('success', 'Saldo awal periode ' . $saldoAwalProduk->bulan_nama . ' ' . $saldoAwalProduk->periode_tahun . ' berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('saldo-awal-produk.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show detail saldo awal produk
     */
    public function showDetail(SaldoAwalProduk $saldoAwalProduk): JsonResponse
    {
        try {
            $saldoAwalProduk->load(['details.produk.kategori', 'details.produk.satuan', 'user']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $saldoAwalProduk->id,
                    'periode_bulan' => $saldoAwalProduk->periode_bulan,
                    'periode_tahun' => $saldoAwalProduk->periode_tahun,
                    'bulan_nama' => $saldoAwalProduk->bulan_nama,
                    'keterangan' => $saldoAwalProduk->keterangan,
                    'created_at' => $saldoAwalProduk->created_at->format('d/m/Y H:i'),
                    'user' => $saldoAwalProduk->user,
                    'details' => $saldoAwalProduk->details->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'saldo_awal' => $detail->saldo_awal,
                            'produk' => [
                                'id' => $detail->produk->id,
                                'nama_produk' => $detail->produk->nama_produk,
                                'foto' => $detail->produk->foto,
                                'kategori' => $detail->produk->kategori,
                                'satuan' => $detail->produk->satuan,
                            ]
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get semua produk untuk form create
     */
    public function getAllProduk(Request $request): JsonResponse
    {
        $request->validate([
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|min:2020',
        ]);

        try {
            $produkList = Produk::with(['kategori', 'satuan'])
                ->orderBy('kategori_id')
                ->orderBy('nama_produk')
                ->get();

            $produkData = [];
            foreach ($produkList as $produk) {
                // Cek apakah sudah ada saldo awal untuk periode ini
                $existingSaldo = SaldoAwalProduk::whereHas('details', function ($query) use ($produk) {
                    $query->where('produk_id', $produk->id);
                })
                    ->where('periode_bulan', $request->periode_bulan)
                    ->where('periode_tahun', $request->periode_tahun)
                    ->first();

                $existingSaldoValue = 0;
                if ($existingSaldo) {
                    $detail = $existingSaldo->details()->where('produk_id', $produk->id)->first();
                    $existingSaldoValue = $detail ? $detail->saldo_awal : 0;
                }

                // Hitung saldo awal otomatis jika belum ada
                $calculatedSaldo = 0;
                $saldoSebelumnyaBelumDiSet = false;

                if (!$existingSaldo) {
                    $calculatedSaldo = $this->calculateSaldoAwal($produk->id, $request->periode_bulan, $request->periode_tahun);

                    // Jika hasil null, berarti saldo sebelumnya belum di-set
                    if ($calculatedSaldo === null) {
                        $saldoSebelumnyaBelumDiSet = true;
                        $calculatedSaldo = 0; // Set ke 0 untuk display
                    }
                }

                $produkData[] = [
                    'id' => $produk->id,
                    'nama' => $produk->nama_produk,
                    'kategori' => $produk->kategori->nama ?? '-',
                    'satuan' => $produk->satuan->nama ?? '-',
                    'foto' => $produk->foto ? asset('storage/' . $produk->foto) : null,
                    'existing_saldo' => $existingSaldoValue,
                    'has_existing' => $existingSaldo ? true : false,
                    'calculated_saldo' => $calculatedSaldo,
                    'saldo_sebelumnya_belum_diset' => $saldoSebelumnyaBelumDiSet
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $produkData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hitung saldo awal otomatis berdasarkan saldo awal bulan sebelumnya
     * ditambah pembelian dan dikurangi penjualan bulan sebelumnya
     */
    private function calculateSaldoAwal($produkId, $periodeBulan, $periodeTahun)
    {
        try {
            // Hitung bulan dan tahun sebelumnya
            $bulanSebelumnya = $periodeBulan - 1;
            $tahunSebelumnya = $periodeTahun;

            if ($bulanSebelumnya <= 0) {
                $bulanSebelumnya = 12;
                $tahunSebelumnya = $periodeTahun - 1;
            }

            // Cari saldo awal bulan sebelumnya
            $saldoAwalSebelumnya = SaldoAwalProduk::whereHas('details', function ($query) use ($produkId) {
                $query->where('produk_id', $produkId);
            })
                ->where('periode_bulan', $bulanSebelumnya)
                ->where('periode_tahun', $tahunSebelumnya)
                ->first();

            // Jika saldo awal bulan sebelumnya belum di-set, return null sebagai indikator
            if (!$saldoAwalSebelumnya) {
                return null; // Indikator bahwa saldo sebelumnya belum di-set
            }

            $detail = $saldoAwalSebelumnya->details()->where('produk_id', $produkId)->first();
            $saldoAwal = $detail ? $detail->saldo_awal : 0;

            // Hitung total pembelian bulan sebelumnya
            $totalPembelian = \App\Models\DetailPembelian::whereHas('pembelian', function ($query) use ($bulanSebelumnya, $tahunSebelumnya) {
                $query->whereMonth('tanggal', $bulanSebelumnya)
                    ->whereYear('tanggal', $tahunSebelumnya);
            })
                ->where('produk_id', $produkId)
                ->sum('qty');

            // Hitung total penjualan bulan sebelumnya
            $totalPenjualan = \App\Models\DetailPenjualan::whereHas('penjualan', function ($query) use ($bulanSebelumnya, $tahunSebelumnya) {
                $query->whereMonth('tanggal', $bulanSebelumnya)
                    ->whereYear('tanggal', $tahunSebelumnya);
            })
                ->where('produk_id', $produkId)
                ->sum('qty');

            // Hitung saldo akhir bulan sebelumnya
            $saldoAkhir = $saldoAwal + $totalPembelian - $totalPenjualan;

            return max(0, $saldoAkhir); // Pastikan tidak negatif

        } catch (\Exception $e) {
            // Jika terjadi error, return 0
            \Log::error('Error in calculateSaldoAwal: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get nama bulan
     */
    private function getBulanNama($bulan)
    {
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

        return $bulanList[$bulan] ?? '';
    }
}
