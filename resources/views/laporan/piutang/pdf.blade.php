<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Piutang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }

        .periode {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .periode h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: nowrap;
        }

        .summary-item {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
        }

        .summary-item h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
        }

        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-lunas {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-dp {
            background-color: #cce5ff;
            color: #004085;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-angsuran {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
        }

        .status-belum-bayar {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Piutang</h1>
        <p>Periode:
            @if ($laporanData['periode']['jenis'] == 'semua')
                {{ $laporanData['periode']['deskripsi'] }}
            @elseif ($laporanData['periode']['jenis'] == 'bulan')
                {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
            @else
                {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_dari'])->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_sampai'])->format('d M Y') }}
            @endif
        </p>
        <p>Tanggal Cetak: {{ date('d M Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h4>Total Piutang</h4>
            <div class="value">Rp {{ number_format($laporanData['summary']['total_piutang'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <h4>Total Transaksi</h4>
            <div class="value">{{ $laporanData['summary']['total_transaksi'] }} Transaksi</div>
        </div>
        <div class="summary-item">
            <h4>Belum Bayar</h4>
            <div class="value">{{ $laporanData['summary']['belum_bayar'] }} Transaksi</div>
        </div>
        <div class="summary-item">
            <h4>DP</h4>
            <div class="value">{{ $laporanData['summary']['dp'] }} Transaksi</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th class="text-right">Total</th>
                <th class="text-right">Terbayar</th>
                <th class="text-right">Sisa</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanData['piutangs'] ?? [] as $piutang)
                <tr>
                    <td>{{ $piutang['no_faktur'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($piutang['tanggal'])->format('d/m/Y') }}</td>
                    <td>{{ $piutang['pelanggan'] }}</td>
                    <td class="text-right">Rp {{ number_format($piutang['total'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($piutang['terbayar'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($piutang['sisa'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if ($piutang['status'] == 'lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($piutang['status'] == 'dp')
                            <span class="status-dp">DP</span>
                        @elseif($piutang['status'] == 'angsuran')
                            <span class="status-angsuran">Angsuran</span>
                        @else
                            <span class="status-belum-bayar">Belum Bayar</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data piutang untuk periode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
        @if (isset($laporanData['piutangs']) && $laporanData['piutangs']->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['piutangs']->sum('total'), 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['piutangs']->sum('terbayar'), 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['piutangs']->sum('sisa'), 0, ',', '.') }}</strong></td>
                    <td class="text-center"><strong>{{ $laporanData['piutangs']->count() }} Transaksi</strong></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Tabel Rekap Pelanggan -->
    @if (isset($laporanData['rekap_pelanggan']) && $laporanData['rekap_pelanggan']->count() > 0)
        <h3
            style="margin-top: 30px; margin-bottom: 15px; font-size: 18px; color: #333; border-bottom: 2px solid #333; padding-bottom: 10px;">
            Rekap Berdasarkan Pelanggan
        </h3>

        <table style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th class="text-center">Transaksi</th>
                    <th class="text-right">Total Piutang</th>
                    <th class="text-right">Terbayar</th>
                    <th class="text-right">Sisa</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanData['rekap_pelanggan'] as $rekap)
                    <tr>
                        <td>{{ $rekap['pelanggan'] }}</td>
                        <td class="text-center">{{ $rekap['total_transaksi'] }}</td>
                        <td class="text-right">Rp {{ number_format($rekap['total_piutang'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($rekap['total_terbayar'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($rekap['sisa_piutang'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($rekap['sisa_piutang'] <= 0)
                                <span class="status-lunas">Lunas</span>
                            @elseif ($rekap['total_terbayar'] > 0)
                                <span class="status-angsuran">Angsuran</span>
                            @else
                                <span class="status-belum-bayar">Belum Bayar</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['rekap_pelanggan']->sum('total_piutang'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['rekap_pelanggan']->sum('total_terbayar'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['rekap_pelanggan']->sum('sisa_piutang'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center"><strong>{{ $laporanData['rekap_pelanggan']->count() }} Pelanggan</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis pada {{ date('d M Y H:i:s') }}</p>
    </div>
</body>

</html>
