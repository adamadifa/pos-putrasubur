<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hutang</title>
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
        <h1>Laporan Hutang</h1>
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
            <h4>Total Hutang</h4>
            <div class="value">Rp {{ number_format($laporanData['summary']['total_hutang'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <h4>Total Terbayar</h4>
            <div class="value">Rp {{ number_format($laporanData['summary']['total_terbayar'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <h4>Sisa Hutang</h4>
            <div class="value">Rp {{ number_format($laporanData['summary']['total_sisa'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <h4>Belum Bayar</h4>
            <div class="value">Rp {{ number_format($laporanData['summary']['belum_bayar'], 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th class="text-right">Total</th>
                <th class="text-right">Terbayar</th>
                <th class="text-right">Sisa</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanData['hutangs'] ?? [] as $hutang)
                <tr>
                    <td>{{ $hutang['no_faktur'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($hutang['tanggal'])->format('d/m/Y') }}</td>
                    <td>{{ $hutang['supplier'] }}</td>
                    <td class="text-right">Rp {{ number_format($hutang['total'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($hutang['terbayar'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($hutang['sisa'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if ($hutang['status'] == 'lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($hutang['status'] == 'dp')
                            <span class="status-dp">DP</span>
                        @elseif($hutang['status'] == 'angsuran')
                            <span class="status-angsuran">Angsuran</span>
                        @else
                            <span class="status-belum-bayar">Belum Bayar</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data hutang untuk periode yang dipilih</td>
                </tr>
            @endforelse
        </tbody>
        @if (isset($laporanData['hutangs']) && $laporanData['hutangs']->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['hutangs']->sum('total'), 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['hutangs']->sum('terbayar'), 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['hutangs']->sum('sisa'), 0, ',', '.') }}</strong></td>
                    <td class="text-center"><strong>{{ $laporanData['hutangs']->count() }} Transaksi</strong></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Tabel Rekap Supplier -->
    @if (isset($laporanData['rekap_supplier']) && $laporanData['rekap_supplier']->count() > 0)
        <h3
            style="margin-top: 30px; margin-bottom: 15px; font-size: 18px; color: #333; border-bottom: 2px solid #333; padding-bottom: 10px;">
            Rekap Berdasarkan Supplier
        </h3>

        <table style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th class="text-center">Transaksi</th>
                    <th class="text-right">Total Hutang</th>
                    <th class="text-right">Terbayar</th>
                    <th class="text-right">Sisa</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanData['rekap_supplier'] as $rekap)
                    <tr>
                        <td>{{ $rekap['supplier'] }}</td>
                        <td class="text-center">{{ $rekap['total_transaksi'] }}</td>
                        <td class="text-right">Rp {{ number_format($rekap['total_hutang'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($rekap['total_terbayar'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($rekap['sisa_hutang'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($rekap['sisa_hutang'] <= 0)
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
                            {{ number_format($laporanData['rekap_supplier']->sum('total_hutang'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['rekap_supplier']->sum('total_terbayar'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($laporanData['rekap_supplier']->sum('sisa_hutang'), 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-center"><strong>{{ $laporanData['rekap_supplier']->count() }} Supplier</strong>
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
