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
            if ($selectedKasBank === 'semua') {
                // Generate laporan untuk semua kas/bank
                if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
                    $laporanData = $this->generateLaporanSemuaByDateRange($tanggalDari, $tanggalSampai);
                } elseif ($jenisPeriode === 'bulan' && $selectedBulan && $selectedTahun) {
                    $laporanData = $this->generateLaporanSemua($selectedBulan, $selectedTahun);
                }
            } else {
                // Generate laporan untuk kas/bank tertentu
                if ($jenisPeriode === 'tanggal' && $tanggalDari && $tanggalSampai) {
                    $laporanData = $this->generateLaporanByDateRange($selectedKasBank, $tanggalDari, $tanggalSampai);
                } elseif ($jenisPeriode === 'bulan' && $selectedBulan && $selectedTahun) {
                    $laporanData = $this->generateLaporan($selectedKasBank, $selectedBulan, $selectedTahun);
                }
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
     * Generate laporan data for all kas/bank
     */
    private function generateLaporanSemua($bulan, $tahun)
    {
        $allKasBank = KasBank::orderBy('nama')->get();
        $totalSaldoAwal = 0;
        $totalDebet = 0;
        $totalKredit = 0;
        $allTransaksi = collect();

        foreach ($allKasBank as $kasBank) {
            // Get saldo awal bulan untuk kas/bank ini
            $saldoAwalBulan = SaldoAwalBulanan::getSaldoAwal($kasBank->id, $bulan, $tahun);

            // Jika saldo awal bulan tidak ada, cari saldo awal terakhir terdekat
            $saldoAwalTerakhir = null;
            if ($saldoAwalBulan === 0) {
                $currentDate = Carbon::create($tahun, $bulan, 1);
                for ($i = 0; $i < 12; $i++) {
                    $currentDate->subMonth();
                    $bulanCari = $currentDate->month;
                    $tahunCari = $currentDate->year;
                    $saldoAwalCari = SaldoAwalBulanan::getSaldoAwal($kasBank->id, $bulanCari, $tahunCari);
                    if ($saldoAwalCari > 0) {
                        $saldoAwalTerakhir = $saldoAwalCari;
                        break;
                    }
                }
            }

            // Calculate saldo awal periode
            $tanggalAwalBulanFilter = Carbon::create($tahun, $bulan, 1);
            $transaksiSebelumPeriode = collect();

            if ($saldoAwalTerakhir) {
                $tanggalMulaiHitung = Carbon::create($currentDate->year, $currentDate->month, 1);
                $transaksiSebelumPeriode = TransaksiKasBank::where('kas_bank_id', $kasBank->id)
                    ->whereDate('tanggal', '>=', $tanggalMulaiHitung)
                    ->whereDate('tanggal', '<', $tanggalAwalBulanFilter)
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get();
            }

            $saldoAwal = $saldoAwalTerakhir ?: $saldoAwalBulan;
            foreach ($transaksiSebelumPeriode as $transaksi) {
                if ($transaksi->jenis_transaksi == 'D') {
                    $saldoAwal += $transaksi->jumlah;
                } else {
                    $saldoAwal -= $transaksi->jumlah;
                }
            }

            // Get transaksi bulan ini
            $transaksi = TransaksiKasBank::where('kas_bank_id', $kasBank->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->orderBy('tanggal', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            // Add kas/bank info to each transaction
            $transaksiWithKasBank = $transaksi->map(function ($item) use ($kasBank) {
                $item->kas_bank_nama = $kasBank->nama;
                $item->kas_bank_jenis = $kasBank->jenis;
                return $item;
            });

            $allTransaksi = $allTransaksi->merge($transaksiWithKasBank);
            $totalSaldoAwal += $saldoAwal;
            $totalDebet += $transaksi->where('jenis_transaksi', 'D')->sum('jumlah');
            $totalKredit += $transaksi->where('jenis_transaksi', 'K')->sum('jumlah');
        }

        // Sort all transactions by date
        $allTransaksi = $allTransaksi->sortBy(function ($item) {
            return $item->tanggal . ' ' . $item->created_at;
        });

        // Calculate running balance for all transactions
        $runningBalance = $totalSaldoAwal;
        $transaksiWithBalance = $allTransaksi->map(function ($item) use (&$runningBalance) {
            if ($item->jenis_transaksi == 'D') {
                $runningBalance += $item->jumlah;
            } else {
                $runningBalance -= $item->jumlah;
            }

            $item->saldo_akhir = $runningBalance;

            // Add detailed description
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

        $saldoAkhir = $totalSaldoAwal + $totalDebet - $totalKredit;

        return [
            'kas_bank' => (object) ['nama' => 'Semua Kas/Bank', 'jenis' => 'Gabungan'],
            'periode' => [
                'jenis' => 'bulan',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'bulan_nama' => $this->getBulanNama($bulan),
                'tanggal_awal' => Carbon::create($tahun, $bulan, 1)->format('d/m/Y'),
                'tanggal_akhir' => Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('d/m/Y'),
            ],
            'saldo_awal' => $totalSaldoAwal,
            'transaksi' => $transaksiWithBalance,
            'summary' => [
                'total_debet' => $totalDebet,
                'total_kredit' => $totalKredit,
                'saldo_akhir' => $saldoAkhir,
            ],
            'statistics' => [
                'jumlah_transaksi' => $allTransaksi->count(),
                'transaksi_debet' => $allTransaksi->where('jenis_transaksi', 'D')->count(),
                'transaksi_kredit' => $allTransaksi->where('jenis_transaksi', 'K')->count(),
                'rata_rata_transaksi' => $allTransaksi->count() > 0 ? ($totalDebet + $totalKredit) / $allTransaksi->count() : 0,
            ],
            'is_semua' => true
        ];
    }

    /**
     * Generate laporan data for all kas/bank by date range
     */
    private function generateLaporanSemuaByDateRange($tanggalDari, $tanggalSampai)
    {
        $allKasBank = KasBank::orderBy('nama')->get();
        $totalSaldoAwal = 0;
        $totalDebet = 0;
        $totalKredit = 0;
        $allTransaksi = collect();

        // Parse dates
        $tanggalDari = Carbon::parse($tanggalDari);
        $tanggalSampai = Carbon::parse($tanggalSampai);

        foreach ($allKasBank as $kasBank) {
            // Get saldo awal bulan dari tanggal "dari"
            $bulanDari = $tanggalDari->month;
            $tahunDari = $tanggalDari->year;
            $saldoAwalBulan = SaldoAwalBulanan::getSaldoAwal($kasBank->id, $bulanDari, $tahunDari);

            // Jika saldo awal bulan tidak ada, cari saldo awal terakhir terdekat
            $saldoAwalTerakhir = null;
            if ($saldoAwalBulan === 0) {
                $currentDate = Carbon::create($tahunDari, $bulanDari, 1);
                for ($i = 0; $i < 12; $i++) {
                    $currentDate->subMonth();
                    $bulanCari = $currentDate->month;
                    $tahunCari = $currentDate->year;
                    $saldoAwalCari = SaldoAwalBulanan::getSaldoAwal($kasBank->id, $bulanCari, $tahunCari);
                    if ($saldoAwalCari > 0) {
                        $saldoAwalTerakhir = $saldoAwalCari;
                        break;
                    }
                }
            }

            // Get transaksi dari awal bulan saldo awal terakhir sampai sebelum tanggal "dari"
            $tanggalMulaiHitung = $saldoAwalTerakhir ?
                Carbon::create($currentDate->year, $currentDate->month, 1) :
                Carbon::create($tahunDari, $bulanDari, 1);

            $transaksiSebelumPeriode = TransaksiKasBank::where('kas_bank_id', $kasBank->id)
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
            $transaksi = TransaksiKasBank::where('kas_bank_id', $kasBank->id)
                ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
                ->orderBy('tanggal', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            // Add kas/bank info to each transaction
            $transaksiWithKasBank = $transaksi->map(function ($item) use ($kasBank) {
                $item->kas_bank_nama = $kasBank->nama;
                $item->kas_bank_jenis = $kasBank->jenis;
                return $item;
            });

            $allTransaksi = $allTransaksi->merge($transaksiWithKasBank);
            $totalSaldoAwal += $saldoAwalPeriode;
            $totalDebet += $transaksi->where('jenis_transaksi', 'D')->sum('jumlah');
            $totalKredit += $transaksi->where('jenis_transaksi', 'K')->sum('jumlah');
        }

        // Sort all transactions by date
        $allTransaksi = $allTransaksi->sortBy(function ($item) {
            return $item->tanggal . ' ' . $item->created_at;
        });

        // Calculate running balance for all transactions
        $runningBalance = $totalSaldoAwal;
        $transaksiWithBalance = $allTransaksi->map(function ($item) use (&$runningBalance) {
            if ($item->jenis_transaksi == 'D') {
                $runningBalance += $item->jumlah;
            } else {
                $runningBalance -= $item->jumlah;
            }

            $item->saldo_akhir = $runningBalance;

            // Add detailed description
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

        $saldoAkhir = $totalSaldoAwal + $totalDebet - $totalKredit;

        return [
            'kas_bank' => (object) ['nama' => 'Semua Kas/Bank', 'jenis' => 'Gabungan'],
            'periode' => [
                'jenis' => 'tanggal',
                'tanggal_dari' => $tanggalDari->format('d/m/Y'),
                'tanggal_sampai' => $tanggalSampai->format('d/m/Y'),
                'bulan_nama' => $this->getBulanNama($tanggalDari->month),
                'tahun' => $tanggalDari->year,
            ],
            'saldo_awal' => $totalSaldoAwal,
            'transaksi' => $transaksiWithBalance,
            'summary' => [
                'total_debet' => $totalDebet,
                'total_kredit' => $totalKredit,
                'saldo_akhir' => $saldoAkhir,
            ],
            'statistics' => [
                'jumlah_transaksi' => $allTransaksi->count(),
                'transaksi_debet' => $allTransaksi->where('jenis_transaksi', 'D')->count(),
                'transaksi_kredit' => $allTransaksi->where('jenis_transaksi', 'K')->count(),
                'rata_rata_transaksi' => $allTransaksi->count() > 0 ? ($totalDebet + $totalKredit) / $allTransaksi->count() : 0,
                'jumlah_hari' => $tanggalDari->diffInDays($tanggalSampai) + 1,
            ],
            'is_semua' => true
        ];
    }

    /**
     * Export laporan to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $request->validate([
                'kas_bank_id' => 'required',
                'jenis_periode' => 'required|in:bulan,tanggal',
            ]);

            // Validate kas_bank_id if not 'semua'
            if ($request->kas_bank_id !== 'semua') {
                $request->validate([
                    'kas_bank_id' => 'exists:kas_bank,id',
                ]);
            }

            $laporanData = null;

            if ($request->jenis_periode === 'tanggal') {
                $request->validate([
                    'tanggal_dari' => 'required|date',
                    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
                ]);

                if ($request->kas_bank_id === 'semua') {
                    $laporanData = $this->generateLaporanSemuaByDateRange(
                        $request->tanggal_dari,
                        $request->tanggal_sampai
                    );
                } else {
                    $laporanData = $this->generateLaporanByDateRange(
                        $request->kas_bank_id,
                        $request->tanggal_dari,
                        $request->tanggal_sampai
                    );
                }
            } else {
                $request->validate([
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);

                if ($request->kas_bank_id === 'semua') {
                    $laporanData = $this->generateLaporanSemua(
                        $request->bulan,
                        $request->tahun
                    );
                } else {
                    $laporanData = $this->generateLaporan(
                        $request->kas_bank_id,
                        $request->bulan,
                        $request->tahun
                    );
                }
            }

            // Choose appropriate PDF template based on kas_bank selection
            $template = 'laporan.kas-bank.pdf';
            if ($request->kas_bank_id === 'semua' || isset($laporanData['is_semua'])) {
                $template = 'laporan.kas-bank.pdf-semua';
            }

            // Generate PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($template, compact('laporanData'));

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
