<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\SaldoAwalProduk;
use App\Models\DetailSaldoAwalProduk;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class LaporanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produkList = \App\Models\Produk::with(['kategori', 'satuan'])
            ->orderBy('kategori_id')
            ->orderBy('nama_produk')
            ->get();

        return view('laporan.stok.index', compact('produkList'));
    }

    /**
     * Generate laporan stok berdasarkan periode
     */
    public function generateLaporan(Request $request): JsonResponse
    {
        $request->validate([
            'periode_type' => 'required|in:bulan,tanggal',
            'periode_bulan' => 'required_if:periode_type,bulan|integer|between:1,12',
            'periode_tahun' => 'required_if:periode_type,bulan|integer|min:2020',
            'tanggal_dari' => 'required_if:periode_type,tanggal|date',
            'tanggal_sampai' => 'required_if:periode_type,tanggal|date|after_or_equal:tanggal_dari',
            'produk_id' => 'nullable|exists:produk,id'
        ]);

        try {
            if ($request->periode_type === 'bulan') {
                return $this->generateLaporanByMonth($request->periode_bulan, $request->periode_tahun, $request->produk_id);
            } else {
                return $this->generateLaporanByDateRange($request->tanggal_dari, $request->tanggal_sampai, $request->produk_id);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate laporan stok berdasarkan bulan
     */
    private function generateLaporanByMonth($bulan, $tahun, $produkId = null): JsonResponse
    {
        $query = Produk::with(['kategori', 'satuan'])
            ->orderBy('kategori_id')
            ->orderBy('nama_produk');

        if ($produkId) {
            $query->where('id', $produkId);
        }

        $produkList = $query->get();

        $laporanData = [];
        $totalProduk = 0;
        $totalNilaiStok = 0;

        foreach ($produkList as $produk) {
            // Hitung saldo awal bulan
            $saldoAwal = $this->calculateSaldoAwalBulan($produk->id, $bulan, $tahun);

            // Hitung pembelian bulan ini
            $pembelianBulan = $this->calculatePembelianBulan($produk->id, $bulan, $tahun);

            // Hitung penjualan bulan ini
            $penjualanBulan = $this->calculatePenjualanBulan($produk->id, $bulan, $tahun);

            // Hitung saldo akhir (mutasi stok: saldo awal - penjualan + pembelian)
            $saldoAkhir = $saldoAwal - $penjualanBulan + $pembelianBulan;

            // Hitung nilai stok (menggunakan harga jual)
            $nilaiStok = $saldoAkhir * ($produk->harga_jual ?? 0);

            $laporanData[] = [
                'produk_id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'kategori' => $produk->kategori->nama ?? '-',
                'satuan' => $produk->satuan->nama ?? '-',
                'foto' => $produk->foto ? asset('storage/' . $produk->foto) : null,
                'saldo_awal' => $saldoAwal,
                'pembelian' => $pembelianBulan,
                'penjualan' => $penjualanBulan,
                'saldo_akhir' => $saldoAkhir,
                'nilai_stok' => $nilaiStok,
                'harga_jual' => $produk->harga_jual ?? 0
            ];

            $totalProduk++;
            $totalNilaiStok += $nilaiStok;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'periode' => $this->getBulanNama($bulan) . ' ' . $tahun,
                'periode_type' => 'bulan',
                'periode_bulan' => $bulan,
                'periode_tahun' => $tahun,
                'produk' => $laporanData,
                'summary' => [
                    'total_produk' => $totalProduk,
                    'total_nilai_stok' => $totalNilaiStok
                ]
            ]
        ]);
    }

    /**
     * Generate laporan stok berdasarkan range tanggal
     */
    private function generateLaporanByDateRange($tanggalDari, $tanggalSampai, $produkId = null): JsonResponse
    {
        $query = Produk::with(['kategori', 'satuan'])
            ->orderBy('kategori_id')
            ->orderBy('nama_produk');

        if ($produkId) {
            $query->where('id', $produkId);
        }

        $produkList = $query->get();

        $laporanData = [];
        $totalProduk = 0;
        $totalNilaiStok = 0;

        foreach ($produkList as $produk) {
            // Hitung saldo awal periode (sampai tanggal dari)
            $saldoAwalPeriode = $this->calculateSaldoAwalPeriode($produk->id, $tanggalDari);

            // Hitung pembelian dalam periode
            $pembelianPeriode = $this->calculatePembelianPeriode($produk->id, $tanggalDari, $tanggalSampai);

            // Hitung penjualan dalam periode
            $penjualanPeriode = $this->calculatePenjualanPeriode($produk->id, $tanggalDari, $tanggalSampai);

            // Hitung saldo akhir (mutasi stok: saldo awal - penjualan + pembelian)
            $saldoAkhir = $saldoAwalPeriode - $penjualanPeriode + $pembelianPeriode;

            // Hitung nilai stok
            $nilaiStok = $saldoAkhir * ($produk->harga_jual ?? 0);

            $laporanData[] = [
                'produk_id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'kategori' => $produk->kategori->nama ?? '-',
                'satuan' => $produk->satuan->nama ?? '-',
                'foto' => $produk->foto ? asset('storage/' . $produk->foto) : null,
                'saldo_awal' => $saldoAwalPeriode,
                'pembelian' => $pembelianPeriode,
                'penjualan' => $penjualanPeriode,
                'saldo_akhir' => $saldoAkhir,
                'nilai_stok' => $nilaiStok,
                'harga_jual' => $produk->harga_jual ?? 0
            ];

            $totalProduk++;
            $totalNilaiStok += $nilaiStok;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'periode' => Carbon::parse($tanggalDari)->format('d M Y') . ' - ' . Carbon::parse($tanggalSampai)->format('d M Y'),
                'periode_type' => 'tanggal',
                'tanggal_dari' => $tanggalDari,
                'tanggal_sampai' => $tanggalSampai,
                'produk' => $laporanData,
                'summary' => [
                    'total_produk' => $totalProduk,
                    'total_nilai_stok' => $totalNilaiStok
                ]
            ]
        ]);
    }

    /**
     * Hitung mutasi stok menggunakan UNION ALL query untuk performa yang lebih baik
     */
    private function getMutasiStokUnionAll($produkId, $bulan, $tahun): array
    {
        $saldoAwal = $this->calculateSaldoAwalBulan($produkId, $bulan, $tahun);

        // Query UNION ALL untuk mendapatkan semua transaksi dalam satu query
        $mutasiQuery = "
            SELECT 
                'pembelian' as jenis,
                SUM(d.qty) as qty,
                p.tanggal
            FROM detail_pembelians d
            INNER JOIN pembelians p ON d.pembelian_id = p.id
            WHERE d.produk_id = ? 
            AND MONTH(p.tanggal) = ? 
            AND YEAR(p.tanggal) = ?
            
            UNION ALL
            
            SELECT 
                'penjualan' as jenis,
                SUM(d.qty) as qty,
                p.tanggal
            FROM detail_penjualans d
            INNER JOIN penjualans p ON d.penjualan_id = p.id
            WHERE d.produk_id = ? 
            AND MONTH(p.tanggal) = ? 
            AND YEAR(p.tanggal) = ?
        ";

        $results = \DB::select($mutasiQuery, [$produkId, $bulan, $tahun, $produkId, $bulan, $tahun]);

        $pembelian = 0;
        $penjualan = 0;

        foreach ($results as $result) {
            if ($result->jenis === 'pembelian') {
                $pembelian += $result->qty ?? 0;
            } else {
                $penjualan += $result->qty ?? 0;
            }
        }

        return [
            'saldo_awal' => $saldoAwal,
            'pembelian' => $pembelian,
            'penjualan' => $penjualan,
            'saldo_akhir' => $saldoAwal - $penjualan + $pembelian
        ];
    }

    /**
     * Hitung saldo awal bulan
     */
    private function calculateSaldoAwalBulan($produkId, $bulan, $tahun): float
    {
        // Cari saldo awal untuk bulan dan tahun yang diminta
        $saldoAwal = SaldoAwalProduk::whereHas('details', function ($query) use ($produkId) {
            $query->where('produk_id', $produkId);
        })
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->first();

        if ($saldoAwal) {
            $detail = $saldoAwal->details()->where('produk_id', $produkId)->first();
            return $detail ? $detail->saldo_awal : 0;
        }

        return 0;
    }

    /**
     * Hitung saldo awal periode (untuk range tanggal)
     */
    private function calculateSaldoAwalPeriode($produkId, $tanggalDari): float
    {
        $tanggalDariCarbon = Carbon::parse($tanggalDari);
        $bulanDari = $tanggalDariCarbon->month;
        $tahunDari = $tanggalDariCarbon->year;

        // Cari saldo awal terakhir yang tersedia
        $saldoAwalTerakhir = SaldoAwalProduk::whereHas('details', function ($query) use ($produkId) {
            $query->where('produk_id', $produkId);
        })
            ->where(function ($query) use ($bulanDari, $tahunDari) {
                $query->where('periode_tahun', '<', $tahunDari)
                    ->orWhere(function ($q) use ($bulanDari, $tahunDari) {
                        $q->where('periode_tahun', $tahunDari)
                            ->where('periode_bulan', '<=', $bulanDari);
                    });
            })
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->first();

        if (!$saldoAwalTerakhir) {
            return 0;
        }

        $detail = $saldoAwalTerakhir->details()->where('produk_id', $produkId)->first();
        $saldoAwal = $detail ? $detail->saldo_awal : 0;

        // Hitung transaksi dari saldo awal terakhir sampai tanggal dari
        $tanggalSaldoAwal = Carbon::create($saldoAwalTerakhir->periode_tahun, $saldoAwalTerakhir->periode_bulan, 1);

        if ($tanggalSaldoAwal->lt($tanggalDariCarbon)) {
            // Hitung pembelian dari saldo awal sampai tanggal dari
            $pembelian = DetailPembelian::whereHas('pembelian', function ($query) use ($tanggalSaldoAwal, $tanggalDariCarbon) {
                $query->whereBetween('tanggal', [$tanggalSaldoAwal->format('Y-m-d'), $tanggalDariCarbon->format('Y-m-d')]);
            })
                ->where('produk_id', $produkId)
                ->sum('qty');

            // Hitung penjualan dari saldo awal sampai tanggal dari
            $penjualan = DetailPenjualan::whereHas('penjualan', function ($query) use ($tanggalSaldoAwal, $tanggalDariCarbon) {
                $query->whereBetween('tanggal', [$tanggalSaldoAwal->format('Y-m-d'), $tanggalDariCarbon->format('Y-m-d')]);
            })
                ->where('produk_id', $produkId)
                ->sum('qty');

            $saldoAwal = $saldoAwal + $pembelian - $penjualan;
        }

        return $saldoAwal;
    }

    /**
     * Hitung pembelian bulan
     */
    private function calculatePembelianBulan($produkId, $bulan, $tahun): float
    {
        return DetailPembelian::whereHas('pembelian', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
        })
            ->where('produk_id', $produkId)
            ->sum('qty');
    }

    /**
     * Hitung penjualan bulan
     */
    private function calculatePenjualanBulan($produkId, $bulan, $tahun): float
    {
        return DetailPenjualan::whereHas('penjualan', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
        })
            ->where('produk_id', $produkId)
            ->sum('qty');
    }

    /**
     * Hitung pembelian periode
     */
    private function calculatePembelianPeriode($produkId, $tanggalDari, $tanggalSampai): float
    {
        return DetailPembelian::whereHas('pembelian', function ($query) use ($tanggalDari, $tanggalSampai) {
            $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
        })
            ->where('produk_id', $produkId)
            ->sum('qty');
    }

    /**
     * Hitung penjualan periode
     */
    private function calculatePenjualanPeriode($produkId, $tanggalDari, $tanggalSampai): float
    {
        return DetailPenjualan::whereHas('penjualan', function ($query) use ($tanggalDari, $tanggalSampai) {
            $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
        })
            ->where('produk_id', $produkId)
            ->sum('qty');
    }

    /**
     * Export laporan stok ke PDF
     */
    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF export
        return response()->json([
            'success' => false,
            'message' => 'Fitur export PDF belum tersedia'
        ]);
    }

    /**
     * Get nama bulan
     */
    private function getBulanNama($bulan): string
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
