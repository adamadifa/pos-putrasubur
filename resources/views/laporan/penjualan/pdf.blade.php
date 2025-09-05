<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
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
            font-weight: bold;
            color: #2c3e50;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 18px;
            color: #7f8c8d;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .info-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            flex: 1;
            min-width: 200px;
            margin-right: 15px;
        }

        .info-card:last-child {
            margin-right: 0;
        }

        .info-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        .info-card p {
            margin: 5px 0;
            font-size: 12px;
        }

        .info-card .label {
            font-weight: bold;
            color: #6c757d;
        }

        .info-card .value {
            color: #212529;
        }

        .summary-section {
            background-color: #e8f5e8;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .summary-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #155724;
            font-weight: bold;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item .label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .summary-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #155724;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }

        .transactions-table th,
        .transactions-table td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
        }

        .transactions-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
            text-align: center;
        }

        .transactions-table .text-right {
            text-align: right;
        }

        .transactions-table .text-center {
            text-align: center;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        .summary-table th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .summary-table .text-right {
            text-align: right;
        }

        .summary-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }

        .page-break {
            page-break-before: always;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-lunas {
            background-color: #d4edda;
            color: #155724;
        }

        .status-dp {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-angsuran {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-belum_bayar {
            background-color: #f8d7da;
            color: #721c24;
        }

        .jenis-tunai {
            background-color: #cce5ff;
            color: #004085;
        }

        .jenis-kredit {
            background-color: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>
            @if ($laporanData['periode']['jenis'] == 'tanggal')
                Periode: {{ $laporanData['periode']['tanggal_dari'] }} s/d
                {{ $laporanData['periode']['tanggal_sampai'] }}
            @else
                Periode: {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
            @endif
        </p>
    </div>




    <!-- Detail Transaksi -->
    <h3>Detail Transaksi Penjualan</h3>
    @if ($laporanData['penjualan']->count() > 0)
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Faktur</th>
                    <th>Pelanggan</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Subtotal</th>
                    <th>Diskon Faktur</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanData['penjualan'] as $index => $penjualan)
                    @if ($penjualan->detailPenjualan->count() > 0)
                        @foreach ($penjualan->detailPenjualan as $detailIndex => $detail)
                            <tr>
                                @if ($detailIndex === 0)
                                    <td class="text-center" rowspan="{{ $penjualan->detailPenjualan->count() }}">
                                        {{ $index + 1 }}</td>
                                    <td rowspan="{{ $penjualan->detailPenjualan->count() }}">
                                        {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</td>
                                    <td rowspan="{{ $penjualan->detailPenjualan->count() }}">
                                        {{ $penjualan->no_faktur }}</td>
                                    <td rowspan="{{ $penjualan->detailPenjualan->count() }}">
                                        {{ $penjualan->pelanggan->nama ?? '-' }}</td>
                                    <td class="text-center" rowspan="{{ $penjualan->detailPenjualan->count() }}">
                                        <span class="status-badge jenis-{{ $penjualan->jenis_transaksi }}">
                                            {{ $penjualan->jenis_transaksi == 'tunai' ? 'T' : 'K' }}
                                        </span>
                                    </td>
                                    <td class="text-center" rowspan="{{ $penjualan->detailPenjualan->count() }}">
                                        <span class="status-badge status-{{ $penjualan->status_pembayaran }}">
                                            {{ $penjualan->status_pembayaran == 'lunas' ? 'L' : 'BL' }}
                                        </span>
                                    </td>
                                @endif
                                <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                                <td class="text-right">{{ number_format($detail->qty, 2, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->discount ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                @if ($detailIndex === 0)
                                    <td class="text-right" rowspan="{{ $penjualan->detailPenjualan->count() }}">Rp
                                        {{ number_format($penjualan->diskon, 0, ',', '.') }}</td>
                                    <td class="text-right" rowspan="{{ $penjualan->detailPenjualan->count() }}">Rp
                                        {{ number_format($penjualan->total_setelah_diskon, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $penjualan->no_faktur }}</td>
                            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                            <td class="text-center">
                                <span class="status-badge jenis-{{ $penjualan->jenis_transaksi }}">
                                    {{ $penjualan->jenis_transaksi == 'tunai' ? 'T' : 'K' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $penjualan->status_pembayaran }}">
                                    {{ $penjualan->status_pembayaran == 'lunas' ? 'L' : 'BL' }}
                                </span>
                            </td>
                            <td colspan="5" class="text-center">-</td>
                            <td class="text-right">Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</td>
                            <td class="text-right">Rp
                                {{ number_format($penjualan->total_setelah_diskon, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada transaksi dalam periode yang dipilih.</p>
        </div>
    @endif

    <!-- Summary Total -->
    @if ($laporanData['penjualan']->count() > 0)
        <table class="summary-table">
            <thead>
                <tr>
                    <th colspan="2">RINGKASAN TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr class="summary-row">
                    <td>Total Transaksi</td>
                    <td class="text-right">{{ number_format($laporanData['summary']['total_penjualan']) }}</td>
                </tr>
                <tr class="summary-row">
                    <td>Total Nilai</td>
                    <td class="text-right">Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="summary-row">
                    <td>Total Qty</td>
                    <td class="text-right">{{ number_format($laporanData['summary']['total_qty'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat pada {{ date('d/m/Y H:i:s') }}</p>
        <p>Sistem POS - Laporan Penjualan</p>
    </div>
</body>

</html>
