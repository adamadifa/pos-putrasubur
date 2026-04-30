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
            if ($transaksi->jenis == 'pembelian') {
                $saldoAwal += $transaksi->jumlah;
            } else {
                $saldoAwal -= $transaksi->jumlah;
            }
        }

        // Get transaksi bulan ini
        $tanggalAkhirBulan = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
        $transaksiBulanIni = $this->getTransaksiProduk($produkId, $tanggalAwalBulanFilter, $tanggalAkhirBulan);

        // Calculate summary (kuantitas)
        $totalPembelian = $transaksiBulanIni->where('jenis', 'pembelian')->sum('jumlah');
        $totalPenjualan = $transaksiBulanIni->where('jenis', 'penjualan')->sum('jumlah');
        $totalPenyesuaian = $transaksiBulanIni->where('jenis', 'penyesuaian')->sum('jumlah');

        // Calculate summary (nominal)
        $totalPembelianUang = $transaksiBulanIni->where('jenis', 'pembelian')->sum('total_harga');
        $totalPenjualanUang = $transaksiBulanIni->where('jenis', 'penjualan')->sum('total_harga');
        $totalPenyesuaianUang = $transaksiBulanIni->where('jenis', 'penyesuaian')->sum('total_harga');
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
                'total_pembelian_uang' => $totalPembelianUang,
                'total_penjualan_uang' => $totalPenjualanUang,
                'total_penyesuaian_uang' => $totalPenyesuaianUang,
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
        // Parse dates using specific format d/m/Y
        try {
            $tanggalDari = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalDari);
            $tanggalSampai = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalSampai);
        } catch (\Exception $e) {
            // Fallback for Y-m-d if needed, or re-throw
            $tanggalDari = \Carbon\Carbon::parse($tanggalDari);
            $tanggalSampai = \Carbon\Carbon::parse($tanggalSampai);
        }

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

        // Calculate summary (kuantitas)
        $totalPembelian = $transaksi->where('jenis', 'pembelian')->sum('jumlah');
        $totalPenjualan = $transaksi->where('jenis', 'penjualan')->sum('jumlah');
        $totalPenyesuaian = $transaksi->where('jenis', 'penyesuaian')->sum('jumlah');

        // Calculate summary (nominal)
        $totalPembelianUang = $transaksi->where('jenis', 'pembelian')->sum('total_harga');
        $totalPenjualanUang = $transaksi->where('jenis', 'penjualan')->sum('total_harga');
        $totalPenyesuaianUang = $transaksi->where('jenis', 'penyesuaian')->sum('total_harga');
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
                'total_pembelian_uang' => $totalPembelianUang,
                'total_penjualan_uang' => $totalPenjualanUang,
                'total_penyesuaian_uang' => $totalPenyesuaianUang,
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
                dpb.harga_beli as harga_satuan,
                dpb.subtotal as total_harga,
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
                dpj.harga as harga_satuan,
                dpj.subtotal as total_harga,
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
                NULL as harga_satuan,
                0 as total_harga,
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
     * Export laporan stok to PDF (Now HTML Print Preview)
     */
    public function exportPdf(Request $request)
    {
        // Redirect to print method for consistency
        return $this->print($request);
    }

    /**
     * Tampilkan laporan stok dalam format yang sama dengan PDF,
     * langsung di browser (tanpa DomPDF), untuk kebutuhan cetak manual.
     */
    public function print(Request $request)
    {
        // Increase limits for large data
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jenis_periode' => 'required|in:bulan,tanggal',
        ]);

        $laporanData = null;

        if ($request->jenis_periode === 'tanggal') {
            $request->validate([
                'tanggal_dari' => 'required',
                'tanggal_sampai' => 'required',
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

        return view('laporan.stok.pdf', compact('laporanData'));
    }

    /**
     * Tampilkan rekap laporan stok untuk semua produk
     */
    public function rekap(Request $request)
    {
        $jenisPeriode = $request->get('jenis_periode', 'bulan');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', date('d/m/Y'));
        $tanggalSampai = $request->get('tanggal_sampai', date('d/m/Y'));

        $bulanList = $this->getBulanList();
        $tahunList = $this->getTahunList();

        $rekapData = null;
        if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
            $rekapData = $this->generateRekapDataByDateRange($tanggalDari, $tanggalSampai);
        } else {
            $rekapData = $this->generateRekapData($selectedBulan, $selectedTahun);
        }

        return view('laporan.stok.rekap', compact(
            'jenisPeriode',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'bulanList',
            'tahunList',
            'rekapData'
        ));
    }

    /**
     * Cetak rekap laporan stok
     */
    public function rekapPrint(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $jenisPeriode = $request->get('jenis_periode', 'bulan');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', date('d/m/Y'));
        $tanggalSampai = $request->get('tanggal_sampai', date('d/m/Y'));

        if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
            $rekapData = $this->generateRekapDataByDateRange($tanggalDari, $tanggalSampai);
        } else {
            $rekapData = $this->generateRekapData($selectedBulan, $selectedTahun);
        }

        return view('laporan.stok.rekap-print', compact('rekapData'));
    }

    private function getBulanList()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }

    private function getTahunList()
    {
        $tahunList = [];
        for ($i = 2020; $i <= date('Y') + 1; $i++) {
            $tahunList[] = $i;
        }
        return $tahunList;
    }

    private function generateRekapData($bulan, $tahun)
    {
        $tanggalDari = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $tanggalSampai = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();

        return $this->processRekapData($tanggalDari, $tanggalSampai, 'bulan', $bulan, $tahun);
    }

    private function generateRekapDataByDateRange($tanggalDariStr, $tanggalSampaiStr)
    {
        try {
            $tanggalDari = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalDariStr)->startOfDay();
            $tanggalSampai = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalSampaiStr)->endOfDay();
        } catch (\Exception $e) {
            $tanggalDari = \Carbon\Carbon::parse($tanggalDariStr)->startOfDay();
            $tanggalSampai = \Carbon\Carbon::parse($tanggalSampaiStr)->endOfDay();
        }

        return $this->processRekapData($tanggalDari, $tanggalSampai, 'tanggal');
    }

    private function processRekapData($tanggalDari, $tanggalSampai, $jenisPeriode, $bulan = null, $tahun = null)
    {
        $produkList = Produk::with(['kategori', 'satuan'])->get();
        $results = [];

        foreach ($produkList as $produk) {
            // Get Saldo Awal at tanggalDari
            $saldoAwal = $this->calculateSaldoAwalAtDate($produk->id, $tanggalDari);
            
            // Get Transactions in period
            $transaksi = $this->getTransaksiProduk($produk->id, $tanggalDari, $tanggalSampai);
            
            $masuk = $transaksi->whereIn('jenis', ['pembelian'])->sum('jumlah');
            $masukNominal = $transaksi->whereIn('jenis', ['pembelian'])->sum('total_harga');
            
            // Penyesuaian positif masuk ke 'masuk', negatif masuk ke 'keluar'
            $masuk += $transaksi->where('jenis', 'penyesuaian')->where('jumlah', '>', 0)->sum('jumlah');
            
            $keluar = $transaksi->where('jenis', 'penjualan')->sum('jumlah');
            $keluarNominal = $transaksi->whereIn('jenis', ['penjualan'])->sum('total_harga');
            
            $keluar += abs($transaksi->where('jenis', 'penyesuaian')->where('jumlah', '<', 0)->sum('jumlah'));
            
            $saldoAkhir = $saldoAwal + $masuk - $keluar;

            $results[] = [
                'produk' => $produk,
                'saldo_awal' => $saldoAwal,
                'masuk' => $masuk,
                'masuk_nominal' => $masukNominal,
                'keluar' => $keluar,
                'keluar_nominal' => $keluarNominal,
                'saldo_akhir' => $saldoAkhir,
            ];
        }

        return [
            'results' => $results,
            'periode' => [
                'jenis' => $jenisPeriode,
                'tanggal_dari' => $tanggalDari->format('d/m/Y'),
                'tanggal_sampai' => $tanggalSampai->format('d/m/Y'),
                'bulan_nama' => $bulan ? $this->getBulanNama($bulan) : null,
                'tahun' => $tahun,
            ]
        ];
    }

    private function calculateSaldoAwalAtDate($produkId, $date)
    {
        $bulan = $date->month;
        $tahun = $date->year;
        
        // Initial Saldo Awal from table
        $saldoAwalBulan = $this->getSaldoAwalProduk($produkId, $bulan, $tahun);
        
        // If 0, check last 12 months
        if ($saldoAwalBulan == 0) {
            $currentDate = \Carbon\Carbon::create($tahun, $bulan, 1);
            for ($i = 0; $i < 12; $i++) {
                $currentDate->subMonth();
                $saldoAwalCari = $this->getSaldoAwalProduk($produkId, $currentDate->month, $currentDate->year);
                if ($saldoAwalCari > 0) {
                    // Calculate from that month to current date
                    $transaksiSebelum = $this->getTransaksiProduk($produkId, \Carbon\Carbon::create($currentDate->year, $currentDate->month, 1), $date->copy()->subDay());
                    $total = $saldoAwalCari;
                    foreach ($transaksiSebelum as $t) {
                        if ($t->jenis == 'pembelian') $total += $t->jumlah;
                        elseif ($t->jenis == 'penjualan') $total -= $t->jumlah;
                        elseif ($t->jenis == 'penyesuaian') $total += $t->jumlah;
                    }
                    return $total;
                }
            }
        } else {
            // Calculate from day 1 of month to date-1
            $transaksiSebelum = $this->getTransaksiProduk($produkId, \Carbon\Carbon::create($tahun, $bulan, 1), $date->copy()->subDay());
            $total = $saldoAwalBulan;
            foreach ($transaksiSebelum as $t) {
                if ($t->jenis == 'pembelian') $total += $t->jumlah;
                elseif ($t->jenis == 'penjualan') $total -= $t->jumlah;
                elseif ($t->jenis == 'penyesuaian') $total += $t->jumlah;
            }
            return $total;
        }
        
        return 0;
    }

    public function getDetailMutasi(Request $request)
    {
        try {
            $produkId = $request->get('produk_id');
            $tanggalDari = $request->get('tanggal_dari');
            $tanggalSampai = $request->get('tanggal_sampai');
            
            // Parse dates from d/m/Y format
            try {
                $dateStart = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalDari)->startOfDay();
                $dateEnd = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalSampai)->endOfDay();
            } catch (\Exception $e) {
                // Fallback for other formats
                $dateStart = \Carbon\Carbon::parse($tanggalDari)->startOfDay();
                $dateEnd = \Carbon\Carbon::parse($tanggalSampai)->endOfDay();
            }
            
            $transaksi = $this->getTransaksiProduk($produkId, $dateStart, $dateEnd);
            $produk = Produk::with('satuan')->find($produkId);
            
            return response()->json([
                'success' => true,
                'produk' => $produk,
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            Log::error('Error getDetailMutasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
