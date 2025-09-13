<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanHutangController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $jenisPeriode = $request->get('jenis_periode', '');
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $tanggalDari = $request->get('tanggal_dari');
        $tanggalSampai = $request->get('tanggal_sampai');
        $supplierId = $request->get('supplier_id');

        // Set default period type
        if ($jenisPeriode == '') {
            $jenisPeriode = 'semua';
        }

        $laporanData = null;

        if ($request->has('jenis_periode') || $request->get('jenis_periode') == '' || $request->get('jenis_periode') == null) {
            // Build query for pembelian with kredit transaction type
            $query = Pembelian::with(['supplier', 'pembayaranPembelian'])
                ->where('jenis_transaksi', 'kredit')
                ->where('status_pembayaran', '!=', 'batal');

            // Apply period filter
            if ($jenisPeriode == 'bulan') {
                $query->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan);
            } elseif ($jenisPeriode == 'tanggal') {
                if ($tanggalDari && $tanggalSampai) {
                    $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
                }
            }

            // Apply supplier filter
            if ($supplierId) {
                $query->where('supplier_id', $supplierId);
            }

            // Get pembelian data
            $pembelianList = $query->orderBy('tanggal', 'asc')->get();

            // Process data to calculate hutang
            $hutangs = $pembelianList->map(function ($pembelian) {
                $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
                $sisaHutang = $pembelian->total - $totalBayar;
                $status = 'belum_bayar';
                if ($sisaHutang <= 0) {
                    $status = 'lunas';
                } elseif ($totalBayar > 0 && $sisaHutang > 0) {
                    $status = 'angsuran';
                }
                return [
                    'no_faktur' => $pembelian->no_faktur,
                    'tanggal' => $pembelian->tanggal,
                    'supplier' => $pembelian->supplier->nama ?? 'Tidak ada',
                    'total' => $pembelian->total,
                    'terbayar' => $totalBayar,
                    'sisa' => $sisaHutang,
                    'status' => $status
                ];
            })->filter(function ($item) {
                return $item['sisa'] > 0; // Only show unpaid invoices
            });

            // Calculate summary
            $summary = [
                'total_hutang' => $hutangs->sum('total'),
                'total_terbayar' => $hutangs->sum('terbayar'),
                'total_sisa' => $hutangs->sum('sisa'),
                'lunas' => $hutangs->where('status', 'lunas')->sum('total'),
                'dp' => $hutangs->where('status', 'dp')->sum('total'),
                'angsuran' => $hutangs->where('status', 'angsuran')->sum('total'),
                'belum_bayar' => $hutangs->where('status', 'belum_bayar')->sum('total')
            ];

            // Group by supplier for rekap
            $rekapSupplier = $hutangs->groupBy('supplier')->map(function ($hutangGroup, $supplier) {
                return [
                    'supplier' => $supplier,
                    'total_transaksi' => $hutangGroup->count(),
                    'total_hutang' => $hutangGroup->sum('total'),
                    'total_terbayar' => $hutangGroup->sum('terbayar'),
                    'sisa_hutang' => $hutangGroup->sum('sisa'),
                    'belum_bayar' => $hutangGroup->where('status', 'belum_bayar')->count(),
                    'dp' => $hutangGroup->where('status', 'dp')->count(),
                    'angsuran' => $hutangGroup->where('status', 'angsuran')->count()
                ];
            })->sortByDesc('sisa_hutang');

            // Prepare period information
            $periode = [
                'jenis' => $jenisPeriode,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tanggal_dari' => $tanggalDari,
                'tanggal_sampai' => $tanggalSampai
            ];

            if ($jenisPeriode == 'bulan') {
                $bulanNama = [
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
                $periode['bulan_nama'] = $bulanNama[$bulan];
            } elseif ($jenisPeriode == 'semua') {
                $periode['deskripsi'] = 'Semua Waktu';
            }

            $laporanData = [
                'hutangs' => $hutangs,
                'summary' => $summary,
                'rekap_supplier' => $rekapSupplier,
                'periode' => $periode
            ];
        }

        // Get suppliers for filter
        $suppliers = Supplier::orderBy('nama')->get();

        // Get years for filter
        $tahunList = range(date('Y') - 5, date('Y') + 1);

        // Get months
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

        return view('laporan.hutang.index', compact('laporanData', 'suppliers', 'tahunList', 'bulanList', 'tahun'));
    }

    public function exportPdf(Request $request)
    {
        // Get filter parameters
        $jenisPeriode = $request->get('jenis_periode', 'semua');
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));
        $tanggalDari = $request->get('tanggal_dari');
        $tanggalSampai = $request->get('tanggal_sampai');
        $supplierId = $request->get('supplier_id');

        // Build query for pembelian with kredit transaction type
        $query = Pembelian::with(['supplier', 'pembayaranPembelian'])
            ->where('jenis_transaksi', 'kredit')
            ->where('status_pembayaran', '!=', 'batal');

        // Apply period filter
        if ($jenisPeriode == 'bulan') {
            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        } elseif ($jenisPeriode == 'tanggal') {
            if ($tanggalDari && $tanggalSampai) {
                $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            }
        }

        // Apply supplier filter
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        // Get pembelian data
        $pembelianList = $query->orderBy('tanggal', 'asc')->get();

        // Process data to calculate hutang
        $hutangs = $pembelianList->map(function ($pembelian) {
            $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            $sisaHutang = $pembelian->total - $totalBayar;
            $status = 'belum_bayar';
            if ($sisaHutang <= 0) {
                $status = 'lunas';
            } elseif ($totalBayar > 0 && $sisaHutang > 0) {
                $status = 'angsuran';
            }
            return [
                'no_faktur' => $pembelian->no_faktur,
                'tanggal' => $pembelian->tanggal,
                'supplier' => $pembelian->supplier->nama ?? 'Tidak ada',
                'total' => $pembelian->total,
                'terbayar' => $totalBayar,
                'sisa' => $sisaHutang,
                'status' => $status
            ];
        })->filter(function ($item) {
            return $item['sisa'] > 0; // Only show unpaid invoices
        });

        // Calculate summary
        $summary = [
            'total_hutang' => $hutangs->sum('total'),
            'total_terbayar' => $hutangs->sum('terbayar'),
            'total_sisa' => $hutangs->sum('sisa'),
            'lunas' => $hutangs->where('status', 'lunas')->sum('total'),
            'dp' => $hutangs->where('status', 'dp')->sum('total'),
            'angsuran' => $hutangs->where('status', 'angsuran')->sum('total'),
            'belum_bayar' => $hutangs->where('status', 'belum_bayar')->sum('total')
        ];

        // Group by supplier for rekap
        $rekapSupplier = $hutangs->groupBy('supplier')->map(function ($hutangGroup, $supplier) {
            return [
                'supplier' => $supplier,
                'total_transaksi' => $hutangGroup->count(),
                'total_hutang' => $hutangGroup->sum('total'),
                'total_terbayar' => $hutangGroup->sum('terbayar'),
                'sisa_hutang' => $hutangGroup->sum('sisa'),
                'belum_bayar' => $hutangGroup->where('status', 'belum_bayar')->count(),
                'dp' => $hutangGroup->where('status', 'dp')->count(),
                'angsuran' => $hutangGroup->where('status', 'angsuran')->count()
            ];
        })->sortByDesc('sisa_hutang');

        // Prepare period information
        $periode = [
            'jenis' => $jenisPeriode,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai
        ];

        if ($jenisPeriode == 'bulan') {
            $bulanNama = [
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
            $periode['bulan_nama'] = $bulanNama[$bulan];
        } elseif ($jenisPeriode == 'semua') {
            $periode['deskripsi'] = 'Semua Waktu';
        }

        $laporanData = [
            'hutangs' => $hutangs,
            'summary' => $summary,
            'rekap_supplier' => $rekapSupplier,
            'periode' => $periode
        ];

        $pdf = Pdf::loadView('laporan.hutang.pdf', compact('laporanData'));
        return $pdf->download('laporan_hutang_' . date('Y-m-d') . '.pdf');
    }
}
