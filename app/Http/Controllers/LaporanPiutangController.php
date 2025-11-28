<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\PembayaranPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get filter values from request
        $jenisPeriode = $request->get('jenis_periode', '');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', '');
        $tanggalSampai = $request->get('tanggal_sampai', '');
        $pelangganId = $request->get('pelanggan_id', '');

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

        // Get pelanggan list
        $pelangganList = Pelanggan::orderBy('nama')->get();

        $laporanData = null;

        // Generate laporan if periode is selected or if it's the first load (default to semua)
        if ($request->has('jenis_periode') || $request->get('jenis_periode') == '' || $request->get('jenis_periode') == null) {
            // Set default to 'semua' if not specified
            if ($jenisPeriode == '') {
                $jenisPeriode = 'semua';
            }
            try {
                $query = Penjualan::with(['pelanggan', 'pembayaranPenjualan'])
                    ->where('status_pembayaran', '!=', 'batal')
                    ->where('jenis_transaksi', 'kredit');

                // Apply date filters
                if ($jenisPeriode === 'bulan') {
                    $query->whereMonth('tanggal', $selectedBulan)
                        ->whereYear('tanggal', $selectedTahun);
                } elseif ($jenisPeriode === 'tanggal') {
                    if ($tanggalDari) {
                        $query->whereDate('tanggal', '>=', $tanggalDari);
                    }
                    if ($tanggalSampai) {
                        $query->whereDate('tanggal', '<=', $tanggalSampai);
                    }
                }

                // Apply pelanggan filter
                if ($pelangganId) {
                    $query->where('pelanggan_id', $pelangganId);
                }

                $penjualanList = $query->orderBy('tanggal', 'asc')->get();

                // Calculate piutang for each penjualan
                $piutangs = $penjualanList->map(function ($penjualan) {
                    $totalBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
                    $totalSetelahDiskon = $penjualan->total - $penjualan->diskon; // Gunakan total setelah diskon
                    $sisaPiutang = $totalSetelahDiskon - $totalBayar;

                    // Determine status
                    $status = 'belum_bayar';
                    if ($sisaPiutang <= 0) {
                        $status = 'lunas';
                    } elseif ($totalBayar > 0 && $sisaPiutang > 0) {
                        $status = 'angsuran';
                    }

                    return [
                        'no_faktur' => $penjualan->no_faktur,
                        'tanggal' => $penjualan->tanggal,
                        'pelanggan' => $penjualan->pelanggan->nama ?? 'Tidak ada',
                        'total' => $totalSetelahDiskon, // Gunakan total setelah diskon
                        'terbayar' => $totalBayar,
                        'sisa' => $sisaPiutang,
                        'status' => $status
                    ];
                })->filter(function ($item) {
                    return $item['sisa'] > 0; // Only show unpaid invoices
                });

                // Calculate summary
                $totalPiutang = $piutangs->sum('sisa');
                $totalTransaksi = $piutangs->count();
                $belumBayar = $piutangs->where('status', 'belum_bayar')->count();
                $dp = $piutangs->where('status', 'dp')->count();

                // Group by pelanggan for rekap
                $rekapPelanggan = $piutangs->groupBy('pelanggan')->map(function ($piutangGroup, $pelanggan) {
                    return [
                        'pelanggan' => $pelanggan,
                        'total_transaksi' => $piutangGroup->count(),
                        'total_piutang' => $piutangGroup->sum('total'),
                        'total_terbayar' => $piutangGroup->sum('terbayar'),
                        'sisa_piutang' => $piutangGroup->sum('sisa'),
                        'belum_bayar' => $piutangGroup->where('status', 'belum_bayar')->count(),
                        'dp' => $piutangGroup->where('status', 'dp')->count(),
                        'angsuran' => $piutangGroup->where('status', 'angsuran')->count()
                    ];
                })->sortByDesc('sisa_piutang');

                // Prepare periode info
                $periode = [
                    'jenis' => $jenisPeriode,
                    'deskripsi' => 'Semua Waktu'
                ];

                if ($jenisPeriode === 'bulan') {
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
                    $periode['bulan_nama'] = $bulanList[$selectedBulan];
                    $periode['tahun'] = $selectedTahun;
                } elseif ($jenisPeriode === 'tanggal') {
                    $periode['tanggal_dari'] = $tanggalDari;
                    $periode['tanggal_sampai'] = $tanggalSampai;
                }

                $laporanData = [
                    'piutangs' => $piutangs,
                    'summary' => [
                        'total_piutang' => $totalPiutang,
                        'total_transaksi' => $totalTransaksi,
                        'belum_bayar' => $belumBayar,
                        'dp' => $dp
                    ],
                    'rekap_pelanggan' => $rekapPelanggan,
                    'periode' => $periode
                ];
            } catch (\Exception $e) {
                Log::error('Error generating laporan piutang: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data laporan piutang.');
            }
        }

        return view('laporan.piutang.index', compact(
            'laporanData',
            'jenisPeriode',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'pelangganId',
            'bulanList',
            'tahunList',
            'pelangganList'
        ));
    }

    /**
     * Export laporan piutang to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            // Get filter values from request
            $jenisPeriode = $request->get('jenis_periode', 'semua');
            $selectedBulan = $request->get('bulan', date('n'));
            $selectedTahun = $request->get('tahun', date('Y'));
            $tanggalDari = $request->get('tanggal_dari', '');
            $tanggalSampai = $request->get('tanggal_sampai', '');
            $pelangganId = $request->get('pelanggan_id', '');

            $query = Penjualan::with(['pelanggan', 'pembayaranPenjualan'])
                ->where('status_pembayaran', '!=', 'batal')
                ->where('jenis_transaksi', 'kredit');

            // Apply date filters
            if ($jenisPeriode === 'bulan') {
                $query->whereMonth('tanggal', $selectedBulan)
                    ->whereYear('tanggal', $selectedTahun);
            } elseif ($jenisPeriode === 'tanggal') {
                if ($tanggalDari) {
                    $query->whereDate('tanggal', '>=', $tanggalDari);
                }
                if ($tanggalSampai) {
                    $query->whereDate('tanggal', '<=', $tanggalSampai);
                }
            }

            // Apply pelanggan filter
            if ($pelangganId) {
                $query->where('pelanggan_id', $pelangganId);
            }

            $penjualanList = $query->orderBy('tanggal', 'asc')->get();

            // Calculate piutang for each penjualan
            $piutangs = $penjualanList->map(function ($penjualan) {
                $totalBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
                $totalSetelahDiskon = $penjualan->total - $penjualan->diskon; // Gunakan total setelah diskon
                $sisaPiutang = $totalSetelahDiskon - $totalBayar;

                // Determine status
                $status = 'belum_bayar';
                if ($sisaPiutang <= 0) {
                    $status = 'lunas';
                } elseif ($totalBayar > 0 && $sisaPiutang > 0) {
                    $status = 'angsuran';
                }

                return [
                    'no_faktur' => $penjualan->no_faktur,
                    'tanggal' => $penjualan->tanggal,
                    'pelanggan' => $penjualan->pelanggan->nama ?? 'Tidak ada',
                    'total' => $totalSetelahDiskon, // Gunakan total setelah diskon
                    'terbayar' => $totalBayar,
                    'sisa' => $sisaPiutang,
                    'status' => $status
                ];
            })->filter(function ($item) {
                return $item['sisa'] > 0; // Only show unpaid invoices
            });

            // Calculate summary
            $totalPiutang = $piutangs->sum('sisa');
            $totalTransaksi = $piutangs->count();
            $belumBayar = $piutangs->where('status', 'belum_bayar')->count();
            $dp = $piutangs->where('status', 'dp')->count();

            // Group by pelanggan for rekap
            $rekapPelanggan = $piutangs->groupBy('pelanggan')->map(function ($piutangGroup, $pelanggan) {
                return [
                    'pelanggan' => $pelanggan,
                    'total_transaksi' => $piutangGroup->count(),
                    'total_piutang' => $piutangGroup->sum('total'),
                    'total_terbayar' => $piutangGroup->sum('terbayar'),
                    'sisa_piutang' => $piutangGroup->sum('sisa'),
                    'belum_bayar' => $piutangGroup->where('status', 'belum_bayar')->count(),
                    'dp' => $piutangGroup->where('status', 'dp')->count(),
                    'angsuran' => $piutangGroup->where('status', 'angsuran')->count()
                ];
            })->sortByDesc('sisa_piutang');

            // Prepare periode info
            $periode = [
                'jenis' => $jenisPeriode,
                'deskripsi' => 'Semua Waktu'
            ];

            if ($jenisPeriode === 'bulan') {
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
                $periode['bulan_nama'] = $bulanList[$selectedBulan];
                $periode['tahun'] = $selectedTahun;
            } elseif ($jenisPeriode === 'tanggal') {
                $periode['tanggal_dari'] = $tanggalDari;
                $periode['tanggal_sampai'] = $tanggalSampai;
            }

            $laporanData = [
                'piutangs' => $piutangs,
                'summary' => [
                    'total_piutang' => $totalPiutang,
                    'total_transaksi' => $totalTransaksi,
                    'belum_bayar' => $belumBayar,
                    'dp' => $dp
                ],
                'rekap_pelanggan' => $rekapPelanggan,
                'periode' => $periode
            ];

            // Generate PDF
            $pdf = Pdf::loadView('laporan.piutang.pdf', compact(
                'laporanData',
                'jenisPeriode',
                'selectedBulan',
                'selectedTahun',
                'tanggalDari',
                'tanggalSampai'
            ));

            $filename = 'laporan_piutang_' . date('Y-m-d_H-i-s') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error exporting laporan piutang PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengexport PDF.');
        }
    }

    public function print(Request $request)
    {
        // Get filter values from request
        $jenisPeriode = $request->get('jenis_periode', '');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', '');
        $tanggalSampai = $request->get('tanggal_sampai', '');
        $pelangganId = $request->get('pelanggan_id', '');

        // Set default period type
        if ($jenisPeriode == '') {
            $jenisPeriode = 'semua';
        }

        // Build query for penjualan with kredit transaction type
        $query = Penjualan::with(['pelanggan', 'pembayaranPenjualan'])
            ->where('jenis_transaksi', 'kredit')
            ->where('status_pembayaran', '!=', 'batal');

        // Apply period filter
        if ($jenisPeriode == 'bulan') {
            $query->whereYear('tanggal', $selectedTahun)
                ->whereMonth('tanggal', $selectedBulan);
        } elseif ($jenisPeriode == 'tanggal') {
            if ($tanggalDari && $tanggalSampai) {
                $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            }
        }

        // Apply pelanggan filter
        if ($pelangganId) {
            $query->where('pelanggan_id', $pelangganId);
        }

        // Get penjualan data
        $penjualanList = $query->orderBy('tanggal', 'asc')->get();

        // Process data to calculate piutang
        $piutangs = $penjualanList->map(function ($penjualan) {
            $totalBayar = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');
            $sisaPiutang = $penjualan->total - $totalBayar;
            $status = 'belum_bayar';
            if ($sisaPiutang <= 0) {
                $status = 'lunas';
            } elseif ($totalBayar > 0 && $sisaPiutang > 0) {
                $status = 'angsuran';
            }
            return [
                'no_faktur' => $penjualan->no_faktur,
                'tanggal' => $penjualan->tanggal,
                'pelanggan' => $penjualan->pelanggan->nama ?? 'Tidak ada',
                'total' => $penjualan->total,
                'terbayar' => $totalBayar,
                'sisa' => $sisaPiutang,
                'status' => $status
            ];
        })->filter(function ($item) {
            return $item['sisa'] > 0;
        });

        // Calculate summary
        $totalPiutang = $piutangs->sum('sisa');
        $totalTransaksi = $piutangs->count();
        $belumBayar = $piutangs->where('status', 'belum_bayar')->count();
        $dp = $piutangs->where('status', 'dp')->count();

        // Group by pelanggan for rekap
        $rekapPelanggan = $piutangs->groupBy('pelanggan')->map(function ($piutangGroup, $pelanggan) {
            return [
                'pelanggan' => $pelanggan,
                'total_transaksi' => $piutangGroup->count(),
                'total_piutang' => $piutangGroup->sum('total'),
                'total_terbayar' => $piutangGroup->sum('terbayar'),
                'sisa_piutang' => $piutangGroup->sum('sisa'),
                'belum_bayar' => $piutangGroup->where('status', 'belum_bayar')->count(),
                'dp' => $piutangGroup->where('status', 'dp')->count(),
                'angsuran' => $piutangGroup->where('status', 'angsuran')->count()
            ];
        })->sortByDesc('sisa_piutang');

        // Prepare periode info
        $periode = [
            'jenis' => $jenisPeriode,
            'deskripsi' => 'Semua Waktu'
        ];

        if ($jenisPeriode === 'bulan') {
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
            $periode['bulan_nama'] = $bulanList[$selectedBulan];
            $periode['bulan'] = $selectedBulan;
            $periode['tahun'] = $selectedTahun;
        } elseif ($jenisPeriode === 'tanggal') {
            $periode['tanggal_dari'] = $tanggalDari;
            $periode['tanggal_sampai'] = $tanggalSampai;
        }

        $laporanData = [
            'piutangs' => $piutangs,
            'summary' => [
                'total_piutang' => $totalPiutang,
                'total_transaksi' => $totalTransaksi,
                'belum_bayar' => $belumBayar,
                'dp' => $dp
            ],
            'rekap_pelanggan' => $rekapPelanggan,
            'periode' => $periode
        ];

        return view('laporan.piutang.pdf', compact('laporanData'));
    }
}
