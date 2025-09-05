<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pembelian;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        // Set default values
        $jenisPeriode = $request->get('jenis_periode', 'bulan');
        $selectedBulan = $request->get('bulan', date('n'));
        $selectedTahun = $request->get('tahun', date('Y'));
        $tanggalDari = $request->get('tanggal_dari', date('01/m/Y'));
        $tanggalSampai = $request->get('tanggal_sampai', date('d/m/Y'));

        // Bulan list
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

        $laporanData = null;

        // Generate laporan if periode is selected
        if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
            $laporanData = $this->generateLaporanByDateRange($tanggalDari, $tanggalSampai);
        } elseif ($jenisPeriode === 'bulan' && $selectedBulan && $selectedTahun) {
            $laporanData = $this->generateLaporanByMonth($selectedBulan, $selectedTahun);
        }

        return view('laporan.pembelian.index', compact(
            'jenisPeriode',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'bulanList',
            'laporanData'
        ));
    }

    public function generateLaporan(Request $request)
    {
        try {
            $periode = $request->input('jenis_periode');
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $tanggal_mulai = $request->input('tanggal_dari');
            $tanggal_selesai = $request->input('tanggal_sampai');

            if ($periode === 'bulan') {
                return $this->generateLaporanByMonth($bulan, $tahun);
            } else {
                return $this->generateLaporanByDateRange($tanggal_mulai, $tanggal_selesai);
            }
        } catch (\Exception $e) {
            Log::error('Error generating laporan pembelian: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan dalam generate laporan'], 500);
        }
    }

    public function generateLaporanByMonth($bulan, $tahun)
    {
        // Ambil data pembelian berdasarkan bulan dan tahun
        $pembelians = Pembelian::with(['detailPembelian.produk', 'supplier'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Hitung summary
        $total_pembelian = $pembelians->count();
        $total_nilai = $pembelians->sum('total');
        $total_qty = $pembelians->sum(function ($pembelian) {
            return $pembelian->detailPembelian->sum('qty');
        });

        // Status pembelian
        $status_lunas = $pembelians->where('status_pembayaran', 'lunas')->count();
        $status_belum_lunas = $pembelians->where('status_pembayaran', 'belum_lunas')->count();

        // Top supplier
        $top_suppliers = $pembelians->groupBy('supplier_id')
            ->map(function ($group) {
                return [
                    'supplier' => $group->first()->supplier,
                    'total_transaksi' => $group->count(),
                    'total_nilai' => $group->sum('total')
                ];
            })
            ->sortByDesc('total_nilai')
            ->take(5);

        // Top produk
        $top_produks_array = [];
        foreach ($pembelians as $pembelian) {
            foreach ($pembelian->detailPembelian as $detail) {
                $key = $detail->produk_id;
                if (isset($top_produks_array[$key])) {
                    $top_produks_array[$key]['total_qty'] += $detail->qty;
                    $top_produks_array[$key]['total_nilai'] += $detail->subtotal;
                } else {
                    $top_produks_array[$key] = [
                        'produk' => $detail->produk,
                        'total_qty' => $detail->qty,
                        'total_nilai' => $detail->subtotal
                    ];
                }
            }
        }
        $top_produks = collect($top_produks_array)->sortByDesc('total_nilai')->take(5);

        return [
            'periode' => [
                'jenis' => 'bulan',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'label' => $this->getMonthName($bulan) . ' ' . $tahun
            ],
            'summary' => [
                'total_pembelian' => $total_pembelian,
                'total_nilai' => $total_nilai,
                'total_qty' => $total_qty
            ],
            'status' => [
                'lunas' => $status_lunas,
                'belum_lunas' => $status_belum_lunas
            ],
            'top_suppliers' => $top_suppliers->values(),
            'top_produks' => $top_produks->values(),
            'pembelians' => $pembelians->map(function ($pembelian) {
                return [
                    'id' => $pembelian->id,
                    'no_faktur' => $pembelian->no_faktur,
                    'tanggal' => $pembelian->tanggal,
                    'created_at' => $pembelian->created_at,
                    'supplier' => $pembelian->supplier->nama ?? 'N/A',
                    'total' => $pembelian->subtotal - $pembelian->diskon,
                    'status_pembayaran' => $pembelian->status_pembayaran,
                    'keterangan' => $pembelian->keterangan
                ];
            }),
            'pembelian' => $pembelians
        ];
    }

    public function generateLaporanByDateRange($tanggal_mulai, $tanggal_selesai)
    {
        // Ambil data pembelian berdasarkan range tanggal
        $pembelians = Pembelian::with(['detailPembelian.produk', 'supplier'])
            ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_selesai])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Hitung summary
        $total_pembelian = $pembelians->count();
        $total_nilai = $pembelians->sum('total');
        $total_qty = $pembelians->sum(function ($pembelian) {
            return $pembelian->detailPembelian->sum('qty');
        });

        // Status pembelian
        $status_lunas = $pembelians->where('status_pembayaran', 'lunas')->count();
        $status_belum_lunas = $pembelians->where('status_pembayaran', 'belum_lunas')->count();

        // Top supplier
        $top_suppliers = $pembelians->groupBy('supplier_id')
            ->map(function ($group) {
                return [
                    'supplier' => $group->first()->supplier,
                    'total_transaksi' => $group->count(),
                    'total_nilai' => $group->sum('total')
                ];
            })
            ->sortByDesc('total_nilai')
            ->take(5);

        // Top produk
        $top_produks_array = [];
        foreach ($pembelians as $pembelian) {
            foreach ($pembelian->detailPembelian as $detail) {
                $key = $detail->produk_id;
                if (isset($top_produks_array[$key])) {
                    $top_produks_array[$key]['total_qty'] += $detail->qty;
                    $top_produks_array[$key]['total_nilai'] += $detail->subtotal;
                } else {
                    $top_produks_array[$key] = [
                        'produk' => $detail->produk,
                        'total_qty' => $detail->qty,
                        'total_nilai' => $detail->subtotal
                    ];
                }
            }
        }
        $top_produks = collect($top_produks_array)->sortByDesc('total_nilai')->take(5);

        return [
            'periode' => [
                'jenis' => 'tanggal',
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'label' => $this->formatDate($tanggal_mulai) . ' - ' . $this->formatDate($tanggal_selesai)
            ],
            'summary' => [
                'total_pembelian' => $total_pembelian,
                'total_nilai' => $total_nilai,
                'total_qty' => $total_qty
            ],
            'status' => [
                'lunas' => $status_lunas,
                'belum_lunas' => $status_belum_lunas
            ],
            'top_suppliers' => $top_suppliers->values(),
            'top_produks' => $top_produks->values(),
            'pembelians' => $pembelians->map(function ($pembelian) {
                return [
                    'id' => $pembelian->id,
                    'no_faktur' => $pembelian->no_faktur,
                    'tanggal' => $pembelian->tanggal,
                    'created_at' => $pembelian->created_at,
                    'supplier' => $pembelian->supplier->nama ?? 'N/A',
                    'total' => $pembelian->subtotal - $pembelian->diskon,
                    'status_pembayaran' => $pembelian->status_pembayaran,
                    'keterangan' => $pembelian->keterangan
                ];
            }),
            'pembelian' => $pembelians
        ];
    }

    public function exportPdf(Request $request)
    {
        try {
            $periode = $request->input('jenis_periode');
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $tanggal_mulai = $request->input('tanggal_dari');
            $tanggal_selesai = $request->input('tanggal_sampai');

            if ($periode === 'bulan') {
                $data = $this->generateLaporanByMonth($bulan, $tahun);
            } else {
                $data = $this->generateLaporanByDateRange($tanggal_mulai, $tanggal_selesai);
            }

            $laporanData = $data;

            // Generate filename
            if ($periode === 'bulan') {
                $filename = 'Laporan_Pembelian_' . $this->getMonthName($bulan) . '_' . $tahun . '.pdf';
            } else {
                $filename = 'Laporan_Pembelian_' . str_replace('/', '-', $tanggal_mulai) . '_' . str_replace('/', '-', $tanggal_selesai) . '.pdf';
            }

            $pdf = Pdf::loadView('laporan.pembelian.pdf', compact('laporanData'));
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Error exporting PDF laporan pembelian: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan dalam mengexport PDF'], 500);
        }
    }

    private function getMonthName($month)
    {
        $months = [
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
        return $months[$month] ?? 'Unknown';
    }

    private function formatDate($date)
    {
        return \Carbon\Carbon::parse($date)->format('d/m/Y');
    }
}
