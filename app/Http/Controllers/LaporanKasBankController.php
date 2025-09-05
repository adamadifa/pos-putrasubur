<?php

namespace App\Http\Controllers;

use App\Models\KasBank;
use App\Models\TransaksiKasBank;
use App\Models\SaldoAwalBulanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanKasBankController extends Controller
{
    /**
     * Display the laporan form
     */
    public function index()
    {
        $kasBankList = KasBank::orderBy('nama')->get();

        // Default values
        $selectedKasBank = request('kas_bank_id');
        $selectedBulan = request('bulan', now()->month);
        $selectedTahun = request('tahun', now()->year);
        $tanggalDari = request('tanggal_dari');
        $tanggalSampai = request('tanggal_sampai');
        $jenisPeriode = request('jenis_periode', 'bulan'); // 'bulan' atau 'tanggal'

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

        $laporanData = null;

        // Generate laporan if parameters are provided
        if ($selectedKasBank) {
            if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
                $laporanData = $this->generateLaporanByDateRange($selectedKasBank, $tanggalDari, $tanggalSampai);
            } elseif ($jenisPeriode === 'bulan' && $selectedBulan && $selectedTahun) {
                $laporanData = $this->generateLaporan($selectedKasBank, $selectedBulan, $selectedTahun);
            }
        }

        return view('laporan.kas-bank.index', compact(
            'kasBankList',
            'bulanList',
            'tahunList',
            'selectedKasBank',
            'selectedBulan',
            'selectedTahun',
            'tanggalDari',
            'tanggalSampai',
            'jenisPeriode',
            'laporanData'
        ));
    }

    /**
     * Generate laporan data
     */
    private function generateLaporan($kasBankId, $bulan, $tahun)
    {
        $kasBank = KasBank::findOrFail($kasBankId);

        // Get saldo awal bulan
        $saldoAwalBulan = SaldoAwalBulanan::getSaldoAwal($kasBankId, $bulan, $tahun);

        // Jika saldo awal bulan tidak ada, cari saldo awal terakhir terdekat
        $saldoAwalTerakhir = null;
        $periodeSaldoAwalTerakhir = null;
        $tanggalMulaiHitung = null;

        if ($saldoAwalBulan === 0) {
            // Cari saldo awal terakhir terdekat (mundur dari bulan yang difilter)
            $currentDate = Carbon::create($tahun, $bulan, 1);

            for ($i = 0; $i < 12; $i++) { // Maksimal cari 12 bulan ke belakang
                $currentDate->subMonth();
                $bulanCari = $currentDate->month;
                $tahunCari = $currentDate->year;

                $saldoAwalCari = SaldoAwalBulanan::getSaldoAwal($kasBankId, $bulanCari, $tahunCari);

                if ($saldoAwalCari > 0) {
                    $saldoAwalTerakhir = $saldoAwalCari;
                    $periodeSaldoAwalTerakhir = $this->getBulanNama($bulanCari) . ' ' . $tahunCari;
                    $tanggalMulaiHitung = Carbon::create($tahunCari, $bulanCari, 1);
                    break;
                }
            }
        }

        // Get transaksi dari awal bulan saldo awal terakhir sampai sebelum bulan yang difilter
        $tanggalAwalBulanFilter = Carbon::create($tahun, $bulan, 1);
        $transaksiSebelumPeriode = collect();

        if ($saldoAwalTerakhir && $tanggalMulaiHitung) {
            $transaksiSebelumPeriode = TransaksiKasBank::where('kas_bank_id', $kasBankId)
                ->whereDate('tanggal', '>=', $tanggalMulaiHitung)
                ->whereDate('tanggal', '<', $tanggalAwalBulanFilter)
                ->orderBy('tanggal', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // Calculate saldo awal periode
        $saldoAwal = $saldoAwalTerakhir ?: $saldoAwalBulan;
        foreach ($transaksiSebelumPeriode as $transaksi) {
            if ($transaksi->jenis_transaksi == 'D') {
                $saldoAwal += $transaksi->jumlah;
            } else {
                $saldoAwal -= $transaksi->jumlah;
            }
        }

        // Get transaksi bulan ini
        $transaksi = TransaksiKasBank::where('kas_bank_id', $kasBankId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate running balance
        $runningBalance = $saldoAwal;
        $transaksiWithBalance = $transaksi->map(function ($item) use (&$runningBalance) {
            if ($item->jenis_transaksi == 'D') {
                $runningBalance += $item->jumlah;
            } else {
                $runningBalance -= $item->jumlah;
            }

            $item->saldo_akhir = $runningBalance;

            // Tambahkan keterangan detail untuk transaksi dari penjualan/pembelian
            $keteranganDetail = $item->keterangan;

            if ($item->referensi_tipe == 'PPJ' && $item->referensi_id) {
                $pembayaranPenjualan = \App\Models\PembayaranPenjualan::with(['penjualan.pelanggan'])->find($item->referensi_id);
                if ($pembayaranPenjualan && $pembayaranPenjualan->penjualan) {
                    $penjualan = $pembayaranPenjualan->penjualan;
                    $keteranganDetail = "Pembayaran Penjualan No. Faktur: " . $penjualan->no_faktur;
                    if ($penjualan->pelanggan) {
                        $keteranganDetail .= " - Atas Nama: " . $penjualan->pelanggan->nama;
                    }
                }
            } elseif ($item->referensi_tipe == 'PPB' && $item->referensi_id) {
                $pembayaranPembelian = \App\Models\PembayaranPembelian::with(['pembelian.supplier'])->find($item->referensi_id);
                if ($pembayaranPembelian && $pembayaranPembelian->pembelian) {
                    $pembelian = $pembayaranPembelian->pembelian;
                    $keteranganDetail = "Pembayaran Pembelian No. Faktur: " . $pembelian->no_faktur;
                    if ($pembelian->supplier) {
                        $keteranganDetail .= " - Atas Nama: " . $pembelian->supplier->nama;
                    }
                }
            }

            $item->keterangan_detail = $keteranganDetail;
            return $item;
        });

        // Calculate summary
        $totalDebet = $transaksi->where('jenis_transaksi', 'D')->sum('jumlah');
        $totalKredit = $transaksi->where('jenis_transaksi', 'K')->sum('jumlah');
        $saldoAkhir = $saldoAwal + $totalDebet - $totalKredit;

        // Get saldo awal bulan berikutnya (if exists)
        $bulanBerikutnya = Carbon::create($tahun, $bulan, 1)->addMonth();
        $saldoAwalBulanBerikutnya = SaldoAwalBulanan::getSaldoAwal(
            $kasBankId,
            $bulanBerikutnya->month,
            $bulanBerikutnya->year
        );

        return [
            'kas_bank' => $kasBank,
            'periode' => [
                'jenis' => 'bulan',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'bulan_nama' => $this->getBulanNama($bulan),
                'tanggal_awal' => Carbon::create($tahun, $bulan, 1)->format('d/m/Y'),
                'tanggal_akhir' => Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('d/m/Y'),
            ],
            'saldo_awal' => $saldoAwal,
            'saldo_awal_bulan' => $saldoAwalBulan,
            'saldo_awal_terakhir' => $saldoAwalTerakhir ? [
                'saldo' => $saldoAwalTerakhir,
                'periode_saldo_awal' => $periodeSaldoAwalTerakhir,
                'tanggal_mulai_hitung' => $tanggalMulaiHitung->format('d/m/Y'),
            ] : null,
            'transaksi_sebelum_periode' => $transaksiSebelumPeriode,
            'transaksi' => $transaksiWithBalance,
            'summary' => [
                'total_debet' => $totalDebet,
                'total_kredit' => $totalKredit,
                'saldo_akhir' => $saldoAkhir,
                'saldo_awal_bulan_berikutnya' => $saldoAwalBulanBerikutnya,
            ],
            'statistics' => [
                'jumlah_transaksi' => $transaksi->count(),
                'transaksi_debet' => $transaksi->where('jenis_transaksi', 'D')->count(),
                'transaksi_kredit' => $transaksi->where('jenis_transaksi', 'K')->count(),
                'rata_rata_transaksi' => $transaksi->count() > 0 ? ($totalDebet + $totalKredit) / $transaksi->count() : 0,
            ]
        ];
    }

    /**
     * Generate laporan data by date range
     */
    private function generateLaporanByDateRange($kasBankId, $tanggalDari, $tanggalSampai)
    {
        $kasBank = KasBank::findOrFail($kasBankId);

        // Parse dates
        $tanggalDari = Carbon::parse($tanggalDari);
        $tanggalSampai = Carbon::parse($tanggalSampai);

        // Get saldo awal bulan dari tanggal "dari"
        $bulanDari = $tanggalDari->month;
        $tahunDari = $tanggalDari->year;
        $saldoAwalBulan = SaldoAwalBulanan::getSaldoAwal($kasBankId, $bulanDari, $tahunDari);

        // Jika saldo awal bulan tidak ada, cari saldo awal terakhir terdekat
        $saldoAwalTerakhir = null;
        $periodeSaldoAwalTerakhir = null;

        if ($saldoAwalBulan === 0) {
            // Cari saldo awal terakhir terdekat (mundur dari bulan yang difilter)
            $currentDate = Carbon::create($tahunDari, $bulanDari, 1);

            for ($i = 0; $i < 12; $i++) { // Maksimal cari 12 bulan ke belakang
                $currentDate->subMonth();
                $bulanCari = $currentDate->month;
                $tahunCari = $currentDate->year;

                $saldoAwalCari = SaldoAwalBulanan::getSaldoAwal($kasBankId, $bulanCari, $tahunCari);

                if ($saldoAwalCari > 0) {
                    $saldoAwalTerakhir = $saldoAwalCari;
                    $periodeSaldoAwalTerakhir = $this->getBulanNama($bulanCari) . ' ' . $tahunCari;
                    break;
                }
            }
        }

        // Get transaksi dari awal bulan saldo awal terakhir sampai sebelum tanggal "dari"
        $tanggalMulaiHitung = $saldoAwalTerakhir ?
            Carbon::create($currentDate->year, $currentDate->month, 1) :
            Carbon::create($tahunDari, $bulanDari, 1);

        $transaksiSebelumPeriode = TransaksiKasBank::where('kas_bank_id', $kasBankId)
            ->whereDate('tanggal', '>=', $tanggalMulaiHitung)
            ->whereDate('tanggal', '<', $tanggalDari)
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate saldo awal periode
        $saldoAwalPeriode = $saldoAwalTerakhir ?: $saldoAwalBulan;
        foreach ($transaksiSebelumPeriode as $transaksi) {
            if ($transaksi->jenis_transaksi == 'D') {
                $saldoAwalPeriode += $transaksi->jumlah;
            } else {
                $saldoAwalPeriode -= $transaksi->jumlah;
            }
        }

        // Get transaksi dalam periode yang dipilih
        $transaksi = TransaksiKasBank::where('kas_bank_id', $kasBankId)
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate running balance
        $runningBalance = $saldoAwalPeriode;
        $transaksiWithBalance = $transaksi->map(function ($item) use (&$runningBalance) {
            if ($item->jenis_transaksi == 'D') {
                $runningBalance += $item->jumlah;
            } else {
                $runningBalance -= $item->jumlah;
            }

            $item->saldo_akhir = $runningBalance;

            // Tambahkan keterangan detail untuk transaksi dari penjualan/pembelian
            $keteranganDetail = $item->keterangan;

            if ($item->referensi_tipe == 'PPJ' && $item->referensi_id) {
                $pembayaranPenjualan = \App\Models\PembayaranPenjualan::with(['penjualan.pelanggan'])->find($item->referensi_id);
                if ($pembayaranPenjualan && $pembayaranPenjualan->penjualan) {
                    $penjualan = $pembayaranPenjualan->penjualan;
                    $keteranganDetail = "Pembayaran Penjualan No. Faktur: " . $penjualan->no_faktur;
                    if ($penjualan->pelanggan) {
                        $keteranganDetail .= " - Atas Nama: " . $penjualan->pelanggan->nama;
                    }
                }
            } elseif ($item->referensi_tipe == 'PPB' && $item->referensi_id) {
                $pembayaranPembelian = \App\Models\PembayaranPembelian::with(['pembelian.supplier'])->find($item->referensi_id);
                if ($pembayaranPembelian && $pembayaranPembelian->pembelian) {
                    $pembelian = $pembayaranPembelian->pembelian;
                    $keteranganDetail = "Pembayaran Pembelian No. Faktur: " . $pembelian->no_faktur;
                    if ($pembelian->supplier) {
                        $keteranganDetail .= " - Atas Nama: " . $pembelian->supplier->nama;
                    }
                }
            }

            $item->keterangan_detail = $keteranganDetail;
            return $item;
        });

        // Calculate summary
        $totalDebet = $transaksi->where('jenis_transaksi', 'D')->sum('jumlah');
        $totalKredit = $transaksi->where('jenis_transaksi', 'K')->sum('jumlah');
        $saldoAkhir = $saldoAwalPeriode + $totalDebet - $totalKredit;

        return [
            'kas_bank' => $kasBank,
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
            'transaksi' => $transaksiWithBalance,
            'summary' => [
                'total_debet' => $totalDebet,
                'total_kredit' => $totalKredit,
                'saldo_akhir' => $saldoAkhir,
            ],
            'statistics' => [
                'jumlah_transaksi' => $transaksi->count(),
                'transaksi_debet' => $transaksi->where('jenis_transaksi', 'D')->count(),
                'transaksi_kredit' => $transaksi->where('jenis_transaksi', 'K')->count(),
                'rata_rata_transaksi' => $transaksi->count() > 0 ? ($totalDebet + $totalKredit) / $transaksi->count() : 0,
                'jumlah_hari' => $tanggalDari->diffInDays($tanggalSampai) + 1,
            ]
        ];
    }

    /**
     * Export laporan to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $request->validate([
                'kas_bank_id' => 'required|exists:kas_bank,id',
                'jenis_periode' => 'required|in:bulan,tanggal',
            ]);

            $laporanData = null;

            if ($request->jenis_periode === 'tanggal') {
                $request->validate([
                    'tanggal_dari' => 'required|date',
                    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
                ]);

                $laporanData = $this->generateLaporanByDateRange(
                    $request->kas_bank_id,
                    $request->tanggal_dari,
                    $request->tanggal_sampai
                );
            } else {
                $request->validate([
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);

                $laporanData = $this->generateLaporan(
                    $request->kas_bank_id,
                    $request->bulan,
                    $request->tahun
                );
            }

            // Generate PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.kas-bank.pdf', compact('laporanData'));

            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');

            // Generate filename
            $kasBankName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $laporanData['kas_bank']['nama']);

            if ($laporanData['periode']['jenis'] == 'tanggal') {
                $tanggalDari = str_replace('/', '-', $laporanData['periode']['tanggal_dari']);
                $tanggalSampai = str_replace('/', '-', $laporanData['periode']['tanggal_sampai']);
                $filename = 'Laporan_Kas_Bank_' . $kasBankName . '_' . $tanggalDari . '_sampai_' . $tanggalSampai . '.pdf';
            } else {
                $filename = 'Laporan_Kas_Bank_' . $kasBankName . '_' . $laporanData['periode']['bulan_nama'] . '_' . $laporanData['periode']['tahun'] . '.pdf';
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
}
