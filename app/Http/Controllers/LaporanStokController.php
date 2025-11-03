<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter values from request
        $jenisPeriode = $request->get('jenis_periode', 'bulan');
        $selectedProduk = $request->get('produk_id', '');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', '');
        $tanggalSampai = $request->get('tanggal_sampai', '');

        // Get produk list
        $produkList = Produk::with(['kategori', 'satuan'])
            ->orderBy('kategori_id')
            ->orderBy('nama_produk')
            ->get();

        // Generate bulan list
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

        // Generate tahun list
        $tahunList = [];
        for ($i = 2020; $i <= date('Y') + 1; $i++) {
            $tahunList[] = $i;
        }

        $laporanData = null;

        // Generate laporan if produk is selected
        if ($selectedProduk) {
            if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
                $laporanData = $this->generateLaporanByDateRange($selectedProduk, $tanggalDari, $tanggalSampai);
            } elseif ($jenisPeriode === 'bulan' && $selectedBulan && $selectedTahun) {
                $laporanData = $this->generateLaporan($selectedProduk, $selectedBulan, $selectedTahun);
            }
        }

        return view('laporan.stok.index', compact(
            'jenisPeriode',
            'selectedProduk',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'produkList',
            'bulanList',
            'tahunList',
            'laporanData'
        ));
    }

    /**
     * Generate laporan stok per bulan
     */
    private function generateLaporan($produkId, $bulan, $tahun)
    {
        $produk = Produk::with(['kategori', 'satuan'])->findOrFail($produkId);

        // Get saldo awal bulan dari saldo awal produk
        $saldoAwalBulan = $this->getSaldoAwalProduk($produkId, $bulan, $tahun);

        // Jika saldo awal bulan tidak ada, cari saldo awal terakhir terdekat
        $saldoAwalTerakhir = null;
        $periodeSaldoAwalTerakhir = null;
        $tanggalMulaiHitung = null;

        if ($saldoAwalBulan === 0) {
            // Cari saldo awal terakhir terdekat (mundur dari bulan yang difilter)
            $currentDate = \Carbon\Carbon::create($tahun, $bulan, 1);

            for ($i = 0; $i < 12; $i++) { // Maksimal cari 12 bulan ke belakang
                $currentDate->subMonth();
                $bulanCari = $currentDate->month;
                $tahunCari = $currentDate->year;

                $saldoAwalCari = $this->getSaldoAwalProduk($produkId, $bulanCari, $tahunCari);

                if ($saldoAwalCari > 0) {
                    $saldoAwalTerakhir = $saldoAwalCari;
                    $periodeSaldoAwalTerakhir = $this->getBulanNama($bulanCari) . ' ' . $tahunCari;
                    $tanggalMulaiHitung = \Carbon\Carbon::create($tahunCari, $bulanCari, 1);
                    break;
                }
            }
        }

        // Get transaksi dari awal bulan saldo awal terakhir sampai sebelum bulan yang difilter
        $tanggalAwalBulanFilter = \Carbon\Carbon::create($tahun, $bulan, 1);
        $transaksiSebelumPeriode = collect();

        if ($saldoAwalTerakhir && $tanggalMulaiHitung) {
            $transaksiSebelumPeriode = $this->getTransaksiProduk(
                $produkId,
                $tanggalMulaiHitung,
                $tanggalAwalBulanFilter->copy()->subDay()
            );
        }

        // Calculate saldo awal periode
        $saldoAwal = $saldoAwalTerakhir ?: $saldoAwalBulan;
        foreach ($transaksiSebelumPeriode as $transaksi) {
            if ($transaksi['jenis'] == 'pembelian') {
                $saldoAwal += $transaksi['jumlah'];
            } else {
                $saldoAwal -= $transaksi['jumlah'];
            }
        }

        // Get transaksi bulan ini
        $tanggalAkhirBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
        $transaksiBulanIni = $this->getTransaksiProduk($produkId, $tanggalAwalBulanFilter, $tanggalAkhirBulan);

        // Calculate summary
        $totalPembelian = $transaksiBulanIni->where('jenis', 'pembelian')->sum('jumlah');
        $totalPenjualan = $transaksiBulanIni->where('jenis', 'penjualan')->sum('jumlah');
        $totalPenyesuaian = $transaksiBulanIni->where('jenis', 'penyesuaian')->sum('jumlah');
        $saldoAkhir = $saldoAwal + $totalPembelian - $totalPenjualan + $totalPenyesuaian;

        return [
            'produk' => [
                'id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'kategori' => $produk->kategori->nama ?? '-',
                'satuan' => $produk->satuan->nama ?? '-',
                'foto' => $produk->foto,
                'harga_beli' => $produk->harga_beli,
                'harga_jual' => $produk->harga_jual,
            ],
            'periode' => [
                'jenis' => 'bulan',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'bulan_nama' => $this->getBulanNama($bulan),
                'tanggal_awal' => $tanggalAwalBulanFilter->format('d/m/Y'),
                'tanggal_akhir' => $tanggalAkhirBulan->format('d/m/Y'),
            ],
            'saldo_awal' => $saldoAwal,
            'saldo_awal_bulan' => $saldoAwalBulan,
            'saldo_awal_terakhir' => $saldoAwalTerakhir ? [
                'saldo' => $saldoAwalTerakhir,
                'periode_saldo_awal' => $periodeSaldoAwalTerakhir,
                'tanggal_mulai_hitung' => $tanggalMulaiHitung->format('d/m/Y'),
            ] : null,
            'transaksi_sebelum_periode' => $transaksiSebelumPeriode,
            'transaksi' => $transaksiBulanIni,
            'summary' => [
                'total_pembelian' => $totalPembelian,
                'total_penjualan' => $totalPenjualan,
                'total_penyesuaian' => $totalPenyesuaian,
                'saldo_akhir' => $saldoAkhir,
                'nilai_stok' => $saldoAkhir * $produk->harga_beli,
            ],
            'statistics' => [
                'jumlah_transaksi' => $transaksiBulanIni->count(),
                'transaksi_pembelian' => $transaksiBulanIni->where('jenis', 'pembelian')->count(),
                'transaksi_penjualan' => $transaksiBulanIni->where('jenis', 'penjualan')->count(),
                'transaksi_penyesuaian' => $transaksiBulanIni->where('jenis', 'penyesuaian')->count(),
            ]
        ];
    }

    /**
     * Generate laporan stok by date range
     */
    private function generateLaporanByDateRange($produkId, $tanggalDari, $tanggalSampai)
    {
        $produk = Produk::with(['kategori', 'satuan'])->findOrFail($produkId);

        // Parse dates
        $tanggalDari = \Carbon\Carbon::parse($tanggalDari);
        $tanggalSampai = \Carbon\Carbon::parse($tanggalSampai);

        // Get saldo awal bulan dari tanggal "dari"
        $bulanDari = $tanggalDari->month;
        $tahunDari = $tanggalDari->year;
        $saldoAwalBulan = $this->getSaldoAwalProduk($produkId, $bulanDari, $tahunDari);

        // Jika saldo awal bulan tidak ada, cari saldo awal terakhir terdekat
        $saldoAwalTerakhir = null;
        $periodeSaldoAwalTerakhir = null;
        $tanggalMulaiHitung = null;

        if ($saldoAwalBulan === 0) {
            // Cari saldo awal terakhir terdekat (mundur dari bulan yang difilter)
            $currentDate = \Carbon\Carbon::create($tahunDari, $bulanDari, 1);

            for ($i = 0; $i < 12; $i++) { // Maksimal cari 12 bulan ke belakang
                $currentDate->subMonth();
                $bulanCari = $currentDate->month;
                $tahunCari = $currentDate->year;

                $saldoAwalCari = $this->getSaldoAwalProduk($produkId, $bulanCari, $tahunCari);

                if ($saldoAwalCari > 0) {
                    $saldoAwalTerakhir = $saldoAwalCari;
                    $periodeSaldoAwalTerakhir = $this->getBulanNama($bulanCari) . ' ' . $tahunCari;
                    $tanggalMulaiHitung = \Carbon\Carbon::create($tahunCari, $bulanCari, 1);
                    break;
                }
            }
        }

        // Get transaksi dari awal bulan saldo awal terakhir sampai sebelum tanggal "dari"
        $transaksiSebelumPeriode = collect();

        if ($saldoAwalTerakhir && $tanggalMulaiHitung) {
            $transaksiSebelumPeriode = $this->getTransaksiProduk(
                $produkId,
                $tanggalMulaiHitung,
                $tanggalDari->copy()->subDay()
            );
        } elseif ($saldoAwalBulan > 0) {
            // Jika ada saldo awal bulan, hitung dari awal bulan sampai sebelum tanggal "dari"
            $tanggalAwalBulan = \Carbon\Carbon::create($tahunDari, $bulanDari, 1);
            $transaksiSebelumPeriode = $this->getTransaksiProduk(
                $produkId,
                $tanggalAwalBulan,
                $tanggalDari->copy()->subDay()
            );
        }

        // Calculate saldo awal periode
        $saldoAwalPeriode = $saldoAwalTerakhir ?: $saldoAwalBulan;
        foreach ($transaksiSebelumPeriode as $transaksi) {
            if ($transaksi->jenis == 'pembelian') {
                $saldoAwalPeriode += $transaksi->jumlah;
            } else {
                $saldoAwalPeriode -= $transaksi->jumlah;
            }
        }

        // Get transaksi dalam periode yang dipilih
        $transaksi = $this->getTransaksiProduk($produkId, $tanggalDari, $tanggalSampai);

        // Calculate summary
        $totalPembelian = $transaksi->where('jenis', 'pembelian')->sum('jumlah');
        $totalPenjualan = $transaksi->where('jenis', 'penjualan')->sum('jumlah');
        $totalPenyesuaian = $transaksi->where('jenis', 'penyesuaian')->sum('jumlah');
        $saldoAkhir = $saldoAwalPeriode + $totalPembelian - $totalPenjualan + $totalPenyesuaian;

        return [
            'produk' => [
                'id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'kategori' => $produk->kategori->nama ?? '-',
                'satuan' => $produk->satuan->nama ?? '-',
                'foto' => $produk->foto,
                'harga_beli' => $produk->harga_beli,
                'harga_jual' => $produk->harga_jual,
            ],
            'periode' => [
                'jenis' => 'tanggal',
                'tanggal_dari' => $tanggalDari->format('d/m/Y'),
                'tanggal_sampai' => $tanggalSampai->format('d/m/Y'),
                'bulan_nama' => $this->getBulanNama($bulanDari),
                'tahun' => $tahunDari,
            ],
            'saldo_awal' => $saldoAwalPeriode,
            'saldo_awal_bulan' => $saldoAwalBulan,
            'saldo_awal_terakhir' => $saldoAwalTerakhir ? [
                'saldo' => $saldoAwalTerakhir,
                'periode_saldo_awal' => $periodeSaldoAwalTerakhir,
                'tanggal_mulai_hitung' => $tanggalMulaiHitung->format('d/m/Y'),
            ] : null,
            'transaksi_sebelum_periode' => $transaksiSebelumPeriode,
            'transaksi' => $transaksi,
            'summary' => [
                'total_pembelian' => $totalPembelian,
                'total_penjualan' => $totalPenjualan,
                'total_penyesuaian' => $totalPenyesuaian,
                'saldo_akhir' => $saldoAkhir,
                'nilai_stok' => $saldoAkhir * $produk->harga_beli,
            ],
            'statistics' => [
                'jumlah_transaksi' => $transaksi->count(),
                'transaksi_pembelian' => $transaksi->where('jenis', 'pembelian')->count(),
                'transaksi_penjualan' => $transaksi->where('jenis', 'penjualan')->count(),
                'transaksi_penyesuaian' => $transaksi->where('jenis', 'penyesuaian')->count(),
                'jumlah_hari' => $tanggalDari->diffInDays($tanggalSampai) + 1,
            ]
        ];
    }

    /**
     * Get saldo awal produk untuk bulan dan tahun tertentu
     */
    private function getSaldoAwalProduk($produkId, $bulan, $tahun)
    {
        // Cari saldo awal produk dari tabel detail_saldo_awal_produk
        $saldoAwal = DB::table('detail_saldo_awal_produks as dsap')
            ->join('saldo_awal_produk as sap', 'dsap.saldo_awal_produk_id', '=', 'sap.id')
            ->where('dsap.produk_id', $produkId)
            ->where('sap.periode_bulan', $bulan)
            ->where('sap.periode_tahun', $tahun)
            ->value('dsap.saldo_awal');

        return $saldoAwal ?? 0;
    }

    /**
     * Get transaksi produk dalam periode tertentu
     */
    private function getTransaksiProduk($produkId, $tanggalDari, $tanggalSampai)
    {
        // Format tanggal untuk query
        $tanggalDariStr = $tanggalDari->format('Y-m-d');
        $tanggalSampaiStr = $tanggalSampai->format('Y-m-d');

        // Query UNION untuk menggabungkan transaksi pembelian, penjualan, dan penyesuaian stok
        $transaksi = DB::select("
            SELECT 
                'pembelian' as jenis,
                pb.tanggal,
                (dpb.qty - COALESCE(dpb.qty_discount, 0)) as jumlah,
                CONCAT('Pembelian dari ', COALESCE(s.nama, 'Supplier')) COLLATE utf8mb4_unicode_ci as keterangan,
                pb.no_faktur COLLATE utf8mb4_unicode_ci as no_transaksi
            FROM detail_pembelian dpb
            LEFT JOIN pembelian pb ON dpb.pembelian_id = pb.id
            LEFT JOIN supplier s ON pb.supplier_id = s.id
            WHERE dpb.produk_id = ? 
            AND pb.tanggal BETWEEN ? AND ?
            
            UNION ALL
            
            SELECT 
                'penjualan' as jenis,
                pj.tanggal,
                dpj.qty as jumlah,
                CONCAT('Penjualan ke ', COALESCE(p.nama, 'Pelanggan')) COLLATE utf8mb4_unicode_ci as keterangan,
                pj.no_faktur COLLATE utf8mb4_unicode_ci as no_transaksi
            FROM detail_penjualan dpj
            LEFT JOIN penjualan pj ON dpj.penjualan_id = pj.id
            LEFT JOIN pelanggan p ON pj.pelanggan_id = p.id
            WHERE dpj.produk_id = ? 
            AND pj.tanggal BETWEEN ? AND ?
            
            UNION ALL
            
            SELECT 
                'penyesuaian' as jenis,
                ps.tanggal_penyesuaian as tanggal,
                ps.jumlah_penyesuaian as jumlah,
                COALESCE(ps.keterangan, 'Penyesuaian Stok') COLLATE utf8mb4_unicode_ci as keterangan,
                ps.kode_penyesuaian COLLATE utf8mb4_unicode_ci as no_transaksi
            FROM penyesuaian_stok ps
            WHERE ps.produk_id = ? 
            AND ps.tanggal_penyesuaian BETWEEN ? AND ?
            
            ORDER BY tanggal ASC, jenis ASC
        ", [$produkId, $tanggalDariStr, $tanggalSampaiStr, $produkId, $tanggalDariStr, $tanggalSampaiStr, $produkId, $tanggalDariStr, $tanggalSampaiStr]);

        return collect($transaksi);
    }

    /**
     * Get bulan name
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

        return $bulanList[$bulan] ?? 'Bulan Tidak Valid';
    }

    /**
     * Export laporan stok to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'jenis_periode' => 'required|in:bulan,tanggal',
            ]);

            $laporanData = null;

            if ($request->jenis_periode === 'tanggal') {
                $request->validate([
                    'tanggal_dari' => 'required|date',
                    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
                ]);

                $laporanData = $this->generateLaporanByDateRange(
                    $request->produk_id,
                    $request->tanggal_dari,
                    $request->tanggal_sampai
                );
            } else {
                $request->validate([
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);

                $laporanData = $this->generateLaporan(
                    $request->produk_id,
                    $request->bulan,
                    $request->tahun
                );
            }

            // Generate PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.stok.pdf', compact('laporanData'));

            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');

            // Generate filename
            $produkName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $laporanData['produk']['nama_produk']);

            if ($laporanData['periode']['jenis'] == 'tanggal') {
                $tanggalDari = str_replace('/', '-', $laporanData['periode']['tanggal_dari']);
                $tanggalSampai = str_replace('/', '-', $laporanData['periode']['tanggal_sampai']);
                $filename = 'Laporan_Stok_' . $produkName . '_' . $tanggalDari . '_sampai_' . $tanggalSampai . '.pdf';
            } else {
                $filename = 'Laporan_Stok_' . $produkName . '_' . $laporanData['periode']['bulan_nama'] . '_' . $laporanData['periode']['tahun'] . '.pdf';
            }

            // Return PDF as stream (preview in browser)
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());

            // Return JSON error response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage()
                ], 500);
            }

            // For non-AJAX requests, redirect back with error
            return back()->with('error', 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage());
        }
    }
}
