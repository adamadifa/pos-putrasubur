<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter values from request
        $jenisPeriode = $request->get('jenis_periode', 'bulan');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', '');
        $tanggalSampai = $request->get('tanggal_sampai', '');

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

        // Generate laporan if periode is selected
        if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
            $laporanData = $this->generateLaporanByDateRange($tanggalDari, $tanggalSampai);
        } elseif ($jenisPeriode === 'bulan' && $selectedBulan && $selectedTahun) {
            $laporanData = $this->generateLaporan($selectedBulan, $selectedTahun);
        }

        return view('laporan.penjualan.index', compact(
            'jenisPeriode',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'bulanList',
            'tahunList',
            'laporanData'
        ));
    }

    /**
     * Generate laporan penjualan per bulan
     */
    private function generateLaporan($bulan, $tahun)
    {
        $tanggalAwal = Carbon::create($tahun, $bulan, 1);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        // Get penjualan data
        $penjualan = Penjualan::with(['pelanggan', 'kasir', 'detailPenjualan.produk', 'pembayaranPenjualan'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Calculate summary
        $totalPenjualan = $penjualan->count();
        $totalNilai = $penjualan->sum(function ($p) {
            return $p->total_setelah_diskon;
        });
        $totalLaba = $penjualan->sum(function ($p) {
            return $p->laba;
        });
        $totalQty = $penjualan->sum(function ($p) {
            return $p->detailPenjualan->sum('qty');
        });

        // Status counts
        $statusCounts = [
            'lunas' => $penjualan->where('status_pembayaran', 'lunas')->count(),
            'dp' => $penjualan->where('status_pembayaran', 'dp')->count(),
            'angsuran' => $penjualan->where('status_pembayaran', 'angsuran')->count(),
            'belum_bayar' => $penjualan->where('status_pembayaran', 'belum_bayar')->count(),
        ];

        // Jenis transaksi counts
        $jenisTransaksiCounts = [
            'tunai' => $penjualan->where('jenis_transaksi', 'tunai')->count(),
            'kredit' => $penjualan->where('jenis_transaksi', 'kredit')->count(),
        ];

        // Top products
        $topProducts = DB::table('detail_penjualan as dp')
            ->join('penjualan as p', 'dp.penjualan_id', '=', 'p.id')
            ->join('produk as pr', 'dp.produk_id', '=', 'pr.id')
            ->join('satuan as s', 'pr.satuan_id', '=', 's.id')
            ->whereBetween('p.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->select('pr.nama_produk', 's.nama as nama_satuan', DB::raw('SUM(dp.qty) as total_qty'), DB::raw('SUM(dp.subtotal) as total_nilai'))
            ->groupBy('pr.id', 'pr.nama_produk', 's.nama')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        // Top customers
        $topCustomers = DB::table('penjualan as p')
            ->join('pelanggan as pl', 'p.pelanggan_id', '=', 'pl.id')
            ->whereBetween('p.tanggal', [$tanggalAwal, $tanggalAkhir])
            ->select('pl.nama', DB::raw('COUNT(p.id) as total_transaksi'), DB::raw('SUM(p.total - p.diskon) as total_nilai'))
            ->groupBy('pl.id', 'pl.nama')
            ->orderBy('total_nilai', 'desc')
            ->limit(10)
            ->get();

        return [
            'periode' => [
                'jenis' => 'bulan',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'bulan_nama' => $this->getBulanNama($bulan),
                'tanggal_awal' => $tanggalAwal->format('d/m/Y'),
                'tanggal_akhir' => $tanggalAkhir->format('d/m/Y'),
            ],
            'penjualan' => $penjualan,
            'summary' => [
                'total_penjualan' => $totalPenjualan,
                'total_nilai' => $totalNilai,
                'total_laba' => $totalLaba,
                'total_qty' => $totalQty,
                'rata_rata_nilai' => $totalPenjualan > 0 ? $totalNilai / $totalPenjualan : 0,
            ],
            'status_counts' => $statusCounts,
            'jenis_transaksi_counts' => $jenisTransaksiCounts,
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'statistics' => [
                'jumlah_hari' => $tanggalAwal->diffInDays($tanggalAkhir) + 1,
                'penjualan_per_hari' => $totalPenjualan > 0 ? $totalPenjualan / ($tanggalAwal->diffInDays($tanggalAkhir) + 1) : 0,
            ]
        ];
    }

    /**
     * Generate laporan penjualan by date range
     */
    private function generateLaporanByDateRange($tanggalDari, $tanggalSampai)
    {
        $tanggalDari = Carbon::parse($tanggalDari);
        $tanggalSampai = Carbon::parse($tanggalSampai);

        // Get penjualan data
        $penjualan = Penjualan::with(['pelanggan', 'kasir', 'detailPenjualan.produk', 'pembayaranPenjualan'])
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Calculate summary
        $totalPenjualan = $penjualan->count();
        $totalNilai = $penjualan->sum(function ($p) {
            return $p->total_setelah_diskon;
        });
        $totalLaba = $penjualan->sum(function ($p) {
            return $p->laba;
        });
        $totalQty = $penjualan->sum(function ($p) {
            return $p->detailPenjualan->sum('qty');
        });

        // Status counts
        $statusCounts = [
            'lunas' => $penjualan->where('status_pembayaran', 'lunas')->count(),
            'dp' => $penjualan->where('status_pembayaran', 'dp')->count(),
            'angsuran' => $penjualan->where('status_pembayaran', 'angsuran')->count(),
            'belum_bayar' => $penjualan->where('status_pembayaran', 'belum_bayar')->count(),
        ];

        // Jenis transaksi counts
        $jenisTransaksiCounts = [
            'tunai' => $penjualan->where('jenis_transaksi', 'tunai')->count(),
            'kredit' => $penjualan->where('jenis_transaksi', 'kredit')->count(),
        ];

        // Top products
        $topProducts = DB::table('detail_penjualan as dp')
            ->join('penjualan as p', 'dp.penjualan_id', '=', 'p.id')
            ->join('produk as pr', 'dp.produk_id', '=', 'pr.id')
            ->join('satuan as s', 'pr.satuan_id', '=', 's.id')
            ->whereBetween('p.tanggal', [$tanggalDari, $tanggalSampai])
            ->select('pr.nama_produk', 's.nama as nama_satuan', DB::raw('SUM(dp.qty) as total_qty'), DB::raw('SUM(dp.subtotal) as total_nilai'))
            ->groupBy('pr.id', 'pr.nama_produk', 's.nama')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        // Top customers
        $topCustomers = DB::table('penjualan as p')
            ->join('pelanggan as pl', 'p.pelanggan_id', '=', 'pl.id')
            ->whereBetween('p.tanggal', [$tanggalDari, $tanggalSampai])
            ->select('pl.nama', DB::raw('COUNT(p.id) as total_transaksi'), DB::raw('SUM(p.total - p.diskon) as total_nilai'))
            ->groupBy('pl.id', 'pl.nama')
            ->orderBy('total_nilai', 'desc')
            ->limit(10)
            ->get();

        return [
            'periode' => [
                'jenis' => 'tanggal',
                'tanggal_dari' => $tanggalDari->format('d/m/Y'),
                'tanggal_sampai' => $tanggalSampai->format('d/m/Y'),
                'bulan_nama' => $this->getBulanNama($tanggalDari->month),
                'tahun' => $tanggalDari->year,
            ],
            'penjualan' => $penjualan,
            'summary' => [
                'total_penjualan' => $totalPenjualan,
                'total_nilai' => $totalNilai,
                'total_laba' => $totalLaba,
                'total_qty' => $totalQty,
                'rata_rata_nilai' => $totalPenjualan > 0 ? $totalNilai / $totalPenjualan : 0,
            ],
            'status_counts' => $statusCounts,
            'jenis_transaksi_counts' => $jenisTransaksiCounts,
            'top_products' => $topProducts,
            'top_customers' => $topCustomers,
            'statistics' => [
                'jumlah_hari' => $tanggalDari->diffInDays($tanggalSampai) + 1,
                'penjualan_per_hari' => $totalPenjualan > 0 ? $totalPenjualan / ($tanggalDari->diffInDays($tanggalSampai) + 1) : 0,
            ]
        ];
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
     * Export laporan penjualan to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $request->validate([
                'jenis_periode' => 'required|in:bulan,tanggal',
            ]);

            $laporanData = null;

            if ($request->jenis_periode === 'tanggal') {
                $request->validate([
                    'tanggal_dari' => 'required|date',
                    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
                ]);

                $laporanData = $this->generateLaporanByDateRange(
                    $request->tanggal_dari,
                    $request->tanggal_sampai
                );
            } else {
                $request->validate([
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);

                $laporanData = $this->generateLaporan(
                    $request->bulan,
                    $request->tahun
                );
            }

            // Generate PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.penjualan.pdf', compact('laporanData'));

            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');

            // Generate filename
            if ($laporanData['periode']['jenis'] == 'tanggal') {
                $tanggalDari = str_replace('/', '-', $laporanData['periode']['tanggal_dari']);
                $tanggalSampai = str_replace('/', '-', $laporanData['periode']['tanggal_sampai']);
                $filename = 'Laporan_Penjualan_' . $tanggalDari . '_sampai_' . $tanggalSampai . '.pdf';
            } else {
                $filename = 'Laporan_Penjualan_' . $laporanData['periode']['bulan_nama'] . '_' . $laporanData['periode']['tahun'] . '.pdf';
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
