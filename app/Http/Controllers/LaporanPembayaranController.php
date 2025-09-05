<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PembayaranPenjualan;
use App\Models\PembayaranPembelian;
use App\Models\KasBank;
use App\Models\MetodePembayaran;

class LaporanPembayaranController extends Controller
{
    /**
     * Display laporan pembayaran page
     */
    public function index(Request $request)
    {
        // Get data for filters
        $kasBankList = KasBank::orderBy('nama')->get();
        $metodePembayaranList = MetodePembayaran::orderBy('nama')->get();

        // Generate month and year lists
        $bulanList = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanList[$i] = $this->getBulanNama($i);
        }

        $tahunList = [];
        $currentYear = date('Y');
        for ($i = $currentYear; $i >= 2020; $i--) {
            $tahunList[$i] = $i;
        }

        // Default values
        $selectedKasBank = $request->kas_bank_id;
        $selectedMetodePembayaran = $request->metode_pembayaran_id;
        $selectedJenisTransaksi = $request->jenis_transaksi;
        $selectedBulan = $request->bulan ?: date('n');
        $selectedTahun = $request->tahun ?: date('Y');
        $tanggalDari = $request->tanggal_dari;
        $tanggalSampai = $request->tanggal_sampai;
        $jenisPeriode = $request->jenis_periode ?: 'bulan';
        $laporanData = null;

        // Generate laporan if form is submitted
        if ($request->isMethod('post')) {
            if ($jenisPeriode === 'tanggal') {
                $request->validate([
                    'jenis_transaksi' => 'required|in:penjualan,pembelian,semua',
                    'tanggal_dari' => 'required|date',
                    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
                ]);

                $laporanData = $this->generateLaporanByDateRange(
                    $selectedKasBank,
                    $selectedMetodePembayaran,
                    $selectedJenisTransaksi,
                    $tanggalDari,
                    $tanggalSampai
                );
            } else {
                $request->validate([
                    'jenis_transaksi' => 'required|in:penjualan,pembelian,semua',
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);

                $laporanData = $this->generateLaporan(
                    $selectedKasBank,
                    $selectedMetodePembayaran,
                    $selectedJenisTransaksi,
                    $selectedBulan,
                    $selectedTahun
                );
            }

            // Add statistics and pembayaran data for view
            $laporanData['statistics'] = [
                'total_pembayaran' => $laporanData['summary']['total_pembayaran'],
                'pembayaran_penjualan' => $laporanData['summary']['total_pembayaran_penjualan'],
                'pembayaran_pembelian' => $laporanData['summary']['total_pembayaran_pembelian'],
            ];

            // Combine pembayaran data for table display
            $pembayaranData = collect();

            // Add pembayaran penjualan
            foreach ($laporanData['pembayaran_penjualan'] as $pembayaran) {
                $pembayaranData->push((object) [
                    'tanggal' => $pembayaran->tanggal,
                    'jenis' => 'Penjualan',
                    'no_faktur' => $pembayaran->penjualan->no_faktur ?? '-',
                    'nama_pelanggan_supplier' => $pembayaran->penjualan->pelanggan->nama ?? '-',
                    'metode_pembayaran' => $pembayaran->metode_pembayaran ?? '-',
                    'kas_bank' => $pembayaran->kasBank->nama ?? '-',
                    'jumlah' => $pembayaran->jumlah_bayar,
                ]);
            }

            // Add pembayaran pembelian
            foreach ($laporanData['pembayaran_pembelian'] as $pembayaran) {
                $pembayaranData->push((object) [
                    'tanggal' => $pembayaran->tanggal,
                    'jenis' => 'Pembelian',
                    'no_faktur' => $pembayaran->pembelian->no_faktur ?? '-',
                    'nama_pelanggan_supplier' => $pembayaran->pembelian->supplier->nama ?? '-',
                    'metode_pembayaran' => $pembayaran->metode_pembayaran ?? '-',
                    'kas_bank' => $pembayaran->kasBank->nama ?? '-',
                    'jumlah' => $pembayaran->jumlah_bayar,
                ]);
            }

            // Sort by tanggal
            $laporanData['pembayaran'] = $pembayaranData->sortBy('tanggal')->values();
        }

        return view('laporan.pembayaran.index', compact(
            'kasBankList',
            'metodePembayaranList',
            'bulanList',
            'tahunList',
            'selectedKasBank',
            'selectedMetodePembayaran',
            'selectedJenisTransaksi',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'jenisPeriode',
            'laporanData'
        ));
    }

    /**
     * Generate laporan pembayaran per bulan
     */
    private function generateLaporan($kasBankId, $metodePembayaranId, $jenisTransaksi, $bulan, $tahun)
    {
        $tanggalAwal = Carbon::create($tahun, $bulan, 1);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        // Get pembayaran penjualan (only if jenis_transaksi is 'penjualan' or 'semua')
        $pembayaranPenjualan = collect();
        if ($jenisTransaksi === 'penjualan' || $jenisTransaksi === 'semua') {
            $pembayaranPenjualan = PembayaranPenjualan::with(['penjualan.pelanggan', 'kasBank'])
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->when($kasBankId, function ($query) use ($kasBankId) {
                    return $query->where('kas_bank_id', $kasBankId);
                })
                ->when($metodePembayaranId, function ($query) use ($metodePembayaranId) {
                    // Get metode pembayaran name from MetodePembayaran model
                    $metodePembayaran = MetodePembayaran::find($metodePembayaranId);
                    if ($metodePembayaran) {
                        return $query->where('metode_pembayaran', $metodePembayaran->nama);
                    }
                    return $query;
                })
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        // Get pembayaran pembelian (only if jenis_transaksi is 'pembelian' or 'semua')
        $pembayaranPembelian = collect();
        if ($jenisTransaksi === 'pembelian' || $jenisTransaksi === 'semua') {
            $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier', 'kasBank'])
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->when($kasBankId, function ($query) use ($kasBankId) {
                    return $query->where('kas_bank_id', $kasBankId);
                })
                ->when($metodePembayaranId, function ($query) use ($metodePembayaranId) {
                    // Get metode pembayaran name from MetodePembayaran model
                    $metodePembayaran = MetodePembayaran::find($metodePembayaranId);
                    if ($metodePembayaran) {
                        return $query->where('metode_pembayaran', $metodePembayaran->nama);
                    }
                    return $query;
                })
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        // Calculate summary
        $totalPembayaranPenjualan = $pembayaranPenjualan->count();
        $totalPembayaranPembelian = $pembayaranPembelian->count();
        $totalNilaiPenjualan = $pembayaranPenjualan->sum('jumlah_bayar');
        $totalNilaiPembelian = $pembayaranPembelian->sum('jumlah_bayar');
        $totalNilai = $totalNilaiPenjualan + $totalNilaiPembelian;

        // Group by metode pembayaran dan kas bank
        $metodePembayaranCounts = [];
        $kasBankCounts = [];

        foreach ($pembayaranPenjualan as $pembayaran) {
            $metodeNama = $pembayaran->metode_pembayaran;
            $kasBankNama = $pembayaran->kasBank->nama ?? 'Tidak Diketahui';

            // Metode pembayaran
            if (!isset($metodePembayaranCounts[$metodeNama])) {
                $metodePembayaranCounts[$metodeNama] = [
                    'nama' => $metodeNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $metodePembayaranCounts[$metodeNama]['count']++;
            $metodePembayaranCounts[$metodeNama]['nilai'] += $pembayaran->jumlah_bayar;

            // Kas bank
            if (!isset($kasBankCounts[$kasBankNama])) {
                $kasBankCounts[$kasBankNama] = [
                    'nama' => $kasBankNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $kasBankCounts[$kasBankNama]['count']++;
            $kasBankCounts[$kasBankNama]['nilai'] += $pembayaran->jumlah_bayar;
        }

        foreach ($pembayaranPembelian as $pembayaran) {
            $metodeNama = $pembayaran->metode_pembayaran;
            $kasBankNama = $pembayaran->kasBank->nama ?? 'Tidak Diketahui';

            // Metode pembayaran
            if (!isset($metodePembayaranCounts[$metodeNama])) {
                $metodePembayaranCounts[$metodeNama] = [
                    'nama' => $metodeNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $metodePembayaranCounts[$metodeNama]['count']++;
            $metodePembayaranCounts[$metodeNama]['nilai'] += $pembayaran->jumlah_bayar;

            // Kas bank
            if (!isset($kasBankCounts[$kasBankNama])) {
                $kasBankCounts[$kasBankNama] = [
                    'nama' => $kasBankNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $kasBankCounts[$kasBankNama]['count']++;
            $kasBankCounts[$kasBankNama]['nilai'] += $pembayaran->jumlah_bayar;
        }

        return [
            'periode' => [
                'jenis' => 'bulan',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'bulan_nama' => $this->getBulanNama($bulan),
                'tanggal_awal' => $tanggalAwal->format('d/m/Y'),
                'tanggal_akhir' => $tanggalAkhir->format('d/m/Y'),
            ],
            'pembayaran_penjualan' => $pembayaranPenjualan,
            'pembayaran_pembelian' => $pembayaranPembelian,
            'summary' => [
                'total_pembayaran_penjualan' => $totalPembayaranPenjualan,
                'total_pembayaran_pembelian' => $totalPembayaranPembelian,
                'total_pembayaran' => $totalPembayaranPenjualan + $totalPembayaranPembelian,
                'total_nilai_penjualan' => $totalNilaiPenjualan,
                'total_nilai_pembelian' => $totalNilaiPembelian,
                'total_nilai' => $totalNilai,
            ],
            'metode_pembayaran_counts' => $metodePembayaranCounts,
            'kas_bank_counts' => $kasBankCounts,
        ];
    }

    /**
     * Generate laporan pembayaran by date range
     */
    private function generateLaporanByDateRange($kasBankId, $metodePembayaranId, $jenisTransaksi, $tanggalDari, $tanggalSampai)
    {
        $tanggalDari = Carbon::parse($tanggalDari);
        $tanggalSampai = Carbon::parse($tanggalSampai);

        // Get pembayaran penjualan (only if jenis_transaksi is 'penjualan' or 'semua')
        $pembayaranPenjualan = collect();
        if ($jenisTransaksi === 'penjualan' || $jenisTransaksi === 'semua') {
            $pembayaranPenjualan = PembayaranPenjualan::with(['penjualan.pelanggan', 'kasBank'])
                ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
                ->when($kasBankId, function ($query) use ($kasBankId) {
                    return $query->where('kas_bank_id', $kasBankId);
                })
                ->when($metodePembayaranId, function ($query) use ($metodePembayaranId) {
                    // Get metode pembayaran name from MetodePembayaran model
                    $metodePembayaran = MetodePembayaran::find($metodePembayaranId);
                    if ($metodePembayaran) {
                        return $query->where('metode_pembayaran', $metodePembayaran->nama);
                    }
                    return $query;
                })
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        // Get pembayaran pembelian (only if jenis_transaksi is 'pembelian' or 'semua')
        $pembayaranPembelian = collect();
        if ($jenisTransaksi === 'pembelian' || $jenisTransaksi === 'semua') {
            $pembayaranPembelian = PembayaranPembelian::with(['pembelian.supplier', 'kasBank'])
                ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
                ->when($kasBankId, function ($query) use ($kasBankId) {
                    return $query->where('kas_bank_id', $kasBankId);
                })
                ->when($metodePembayaranId, function ($query) use ($metodePembayaranId) {
                    // Get metode pembayaran name from MetodePembayaran model
                    $metodePembayaran = MetodePembayaran::find($metodePembayaranId);
                    if ($metodePembayaran) {
                        return $query->where('metode_pembayaran', $metodePembayaran->nama);
                    }
                    return $query;
                })
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        // Calculate summary
        $totalPembayaranPenjualan = $pembayaranPenjualan->count();
        $totalPembayaranPembelian = $pembayaranPembelian->count();
        $totalNilaiPenjualan = $pembayaranPenjualan->sum('jumlah_bayar');
        $totalNilaiPembelian = $pembayaranPembelian->sum('jumlah_bayar');
        $totalNilai = $totalNilaiPenjualan + $totalNilaiPembelian;

        // Group by metode pembayaran dan kas bank
        $metodePembayaranCounts = [];
        $kasBankCounts = [];

        foreach ($pembayaranPenjualan as $pembayaran) {
            $metodeNama = $pembayaran->metode_pembayaran;
            $kasBankNama = $pembayaran->kasBank->nama ?? 'Tidak Diketahui';

            // Metode pembayaran
            if (!isset($metodePembayaranCounts[$metodeNama])) {
                $metodePembayaranCounts[$metodeNama] = [
                    'nama' => $metodeNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $metodePembayaranCounts[$metodeNama]['count']++;
            $metodePembayaranCounts[$metodeNama]['nilai'] += $pembayaran->jumlah_bayar;

            // Kas bank
            if (!isset($kasBankCounts[$kasBankNama])) {
                $kasBankCounts[$kasBankNama] = [
                    'nama' => $kasBankNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $kasBankCounts[$kasBankNama]['count']++;
            $kasBankCounts[$kasBankNama]['nilai'] += $pembayaran->jumlah_bayar;
        }

        foreach ($pembayaranPembelian as $pembayaran) {
            $metodeNama = $pembayaran->metode_pembayaran;
            $kasBankNama = $pembayaran->kasBank->nama ?? 'Tidak Diketahui';

            // Metode pembayaran
            if (!isset($metodePembayaranCounts[$metodeNama])) {
                $metodePembayaranCounts[$metodeNama] = [
                    'nama' => $metodeNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $metodePembayaranCounts[$metodeNama]['count']++;
            $metodePembayaranCounts[$metodeNama]['nilai'] += $pembayaran->jumlah_bayar;

            // Kas bank
            if (!isset($kasBankCounts[$kasBankNama])) {
                $kasBankCounts[$kasBankNama] = [
                    'nama' => $kasBankNama,
                    'count' => 0,
                    'nilai' => 0
                ];
            }
            $kasBankCounts[$kasBankNama]['count']++;
            $kasBankCounts[$kasBankNama]['nilai'] += $pembayaran->jumlah_bayar;
        }

        return [
            'periode' => [
                'jenis' => 'tanggal',
                'tanggal_dari' => $tanggalDari->format('d/m/Y'),
                'tanggal_sampai' => $tanggalSampai->format('d/m/Y'),
                'tanggal_awal' => $tanggalDari->format('d/m/Y'),
                'tanggal_akhir' => $tanggalSampai->format('d/m/Y'),
            ],
            'pembayaran_penjualan' => $pembayaranPenjualan,
            'pembayaran_pembelian' => $pembayaranPembelian,
            'summary' => [
                'total_pembayaran_penjualan' => $totalPembayaranPenjualan,
                'total_pembayaran_pembelian' => $totalPembayaranPembelian,
                'total_pembayaran' => $totalPembayaranPenjualan + $totalPembayaranPembelian,
                'total_nilai_penjualan' => $totalNilaiPenjualan,
                'total_nilai_pembelian' => $totalNilaiPembelian,
                'total_nilai' => $totalNilai,
            ],
            'metode_pembayaran_counts' => $metodePembayaranCounts,
            'kas_bank_counts' => $kasBankCounts,
        ];
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $kasBankId = $request->kas_bank_id ?: null;
            $metodePembayaranId = $request->metode_pembayaran_id ?: null;
            $jenisTransaksi = $request->jenis_transaksi;
            $jenisPeriode = $request->jenis_periode;

            if ($jenisPeriode === 'tanggal') {
                $request->validate([
                    'jenis_transaksi' => 'required|in:penjualan,pembelian,semua',
                    'tanggal_dari' => 'required|date',
                    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
                ]);

                $laporanData = $this->generateLaporanByDateRange(
                    $kasBankId,
                    $metodePembayaranId,
                    $jenisTransaksi,
                    $request->tanggal_dari,
                    $request->tanggal_sampai
                );
            } else {
                $request->validate([
                    'jenis_transaksi' => 'required|in:penjualan,pembelian,semua',
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);

                $laporanData = $this->generateLaporan(
                    $kasBankId,
                    $metodePembayaranId,
                    $jenisTransaksi,
                    $request->bulan,
                    $request->tahun
                );
            }

            // Generate PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pembayaran.pdf', compact('laporanData'));
            $pdf->setPaper('a4', 'landscape');

            // Generate filename
            $filename = 'Laporan_Pembayaran_';
            if ($laporanData['periode']['jenis'] == 'tanggal') {
                $filename .= $request->tanggal_dari . '_sampai_' . $request->tanggal_sampai;
            } else {
                $filename .= $laporanData['periode']['bulan_nama'] . '_' . $laporanData['periode']['tahun'];
            }
            $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename) . '.pdf';

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan dalam mengexport PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bulan name
     */
    private function getBulanNama($bulan)
    {
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
        return $bulanNama[$bulan] ?? '';
    }
}
