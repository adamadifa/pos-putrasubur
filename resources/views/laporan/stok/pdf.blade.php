<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - {{ $laporanData['produk']['nama_produk'] }}</title>
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

        .saldo-info {
            background-color: #e8f5e8;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .saldo-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #155724;
            font-weight: bold;
        }

        .saldo-info p {
            margin: 5px 0;
            font-size: 12px;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11px;
        }

        .transactions-table th,
        .transactions-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
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

        .pembelian-row {
            background-color: #f8f9fa;
        }

        .penjualan-row {
            background-color: #fff5f5;
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
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN STOK PRODUK</h1>
        <h2>{{ $laporanData['produk']['nama_produk'] }}</h2>
        <p>
            @if ($laporanData['periode']['jenis'] == 'tanggal')
                Periode: {{ $laporanData['periode']['tanggal_dari'] }} s/d
                {{ $laporanData['periode']['tanggal_sampai'] }}
            @else
                Periode: {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
            @endif
        </p>
    </div>

    <!-- Info Cards -->
    <div class="info-section">
        <div class="info-card">
            <h3>Informasi Produk</h3>
            <p><span class="label">Nama Produk:</span> <span
                    class="value">{{ $laporanData['produk']['nama_produk'] }}</span></p>
            <p><span class="label">Kategori:</span> <span class="value">{{ $laporanData['produk']['kategori'] }}</span>
            </p>
            <p><span class="label">Satuan:</span> <span class="value">{{ $laporanData['produk']['satuan'] }}</span>
            </p>
        </div>

        <div class="info-card">
            <h3>Periode Laporan</h3>
            <p><span class="label">Jenis Periode:</span> <span
                    class="value">{{ ucfirst($laporanData['periode']['jenis']) }}</span></p>
            @if ($laporanData['periode']['jenis'] == 'tanggal')
                <p><span class="label">Tanggal Dari:</span> <span
                        class="value">{{ $laporanData['periode']['tanggal_dari'] }}</span></p>
                <p><span class="label">Tanggal Sampai:</span> <span
                        class="value">{{ $laporanData['periode']['tanggal_sampai'] }}</span></p>
            @else
                <p><span class="label">Bulan:</span> <span
                        class="value">{{ $laporanData['periode']['bulan_nama'] }}</span></p>
                <p><span class="label">Tahun:</span> <span
                        class="value">{{ $laporanData['periode']['tahun'] }}</span></p>
            @endif
        </div>
    </div>

    <!-- Saldo Awal Information -->
    <div class="saldo-info">
        <h3>Informasi Saldo Awal</h3>
        <p><strong>Saldo Awal Periode:</strong> {{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}
            {{ $laporanData['produk']['satuan'] }}</p>

        @if ($laporanData['saldo_awal_terakhir'])
            <p><strong>Saldo Awal Terakhir:</strong>
                {{ number_format($laporanData['saldo_awal_terakhir']['saldo'], 2, ',', '.') }}
                {{ $laporanData['produk']['satuan'] }}
                ({{ $laporanData['saldo_awal_terakhir']['periode_saldo_awal'] }})</p>
            <p><strong>Dihitung dari:</strong> {{ $laporanData['saldo_awal_terakhir']['tanggal_mulai_hitung'] }} s/d
                @if ($laporanData['periode']['jenis'] == 'tanggal')
                    {{ $laporanData['periode']['tanggal_dari'] }}
                @else
                    {{ $laporanData['periode']['tanggal_awal'] }}
                @endif
            </p>
        @else
            <p><strong>Saldo Awal Bulan:</strong> {{ number_format($laporanData['saldo_awal_bulan'], 2, ',', '.') }}
                {{ $laporanData['produk']['satuan'] }}</p>
        @endif
    </div>

    <!-- Transactions Table -->
    <h3>Detail Transaksi</h3>
    @if ($laporanData['transaksi']->count() > 0)
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>No. Transaksi</th>
                    <th>In</th>
                    <th>Out</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $runningSaldo = $laporanData['saldo_awal'];
                    $no = 1;
                @endphp

                @foreach ($laporanData['transaksi'] as $transaksi)
                    @if ($transaksi->jenis == 'pembelian')
                        @php $runningSaldo += $transaksi->jumlah; @endphp
                    @else
                        @php $runningSaldo -= $transaksi->jumlah; @endphp
                    @endif

                    <tr class="{{ $transaksi->jenis == 'pembelian' ? 'pembelian-row' : 'penjualan-row' }}">
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $transaksi->keterangan }}</td>
                        <td class="text-center">{{ ucfirst($transaksi->jenis) }}</td>
                        <td>{{ $transaksi->no_transaksi }}</td>
                        <td class="text-right">
                            @if ($transaksi->jenis == 'pembelian')
                                {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($transaksi->jenis == 'penjualan')
                                {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($runningSaldo, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada transaksi dalam periode yang dipilih.</p>
        </div>
    @endif

    <!-- Summary Table -->
    <table class="summary-table">
        <thead>
            <tr>
                <th colspan="2">RINGKASAN LAPORAN</th>
            </tr>
        </thead>
        <tbody>
            <tr class="summary-row">
                <td>Saldo Awal</td>
                <td class="text-right">{{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}
                    {{ $laporanData['produk']['satuan'] }}</td>
            </tr>
            <tr class="summary-row">
                <td>Total Pembelian</td>
                <td class="text-right">{{ number_format($laporanData['summary']['total_pembelian'], 2, ',', '.') }}
                    {{ $laporanData['produk']['satuan'] }}</td>
            </tr>
            <tr class="summary-row">
                <td>Total Penjualan</td>
                <td class="text-right">{{ number_format($laporanData['summary']['total_penjualan'], 2, ',', '.') }}
                    {{ $laporanData['produk']['satuan'] }}</td>
            </tr>
            <tr class="summary-row">
                <td>Saldo Akhir</td>
                <td class="text-right">{{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}
                    {{ $laporanData['produk']['satuan'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Statistics -->
    <div class="info-section" style="margin-top: 30px;">
        <div class="info-card">
            <h3>Statistik Transaksi</h3>
            <p><span class="label">Jumlah Transaksi:</span> <span
                    class="value">{{ $laporanData['statistics']['jumlah_transaksi'] }}</span></p>
            <p><span class="label">Transaksi Pembelian:</span> <span
                    class="value">{{ $laporanData['statistics']['transaksi_pembelian'] }}</span></p>
            <p><span class="label">Transaksi Penjualan:</span> <span
                    class="value">{{ $laporanData['statistics']['transaksi_penjualan'] }}</span></p>
            @if (isset($laporanData['statistics']['jumlah_hari']))
                <p><span class="label">Jumlah Hari:</span> <span
                        class="value">{{ $laporanData['statistics']['jumlah_hari'] }} hari</span></p>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat pada {{ date('d/m/Y H:i:s') }}</p>
        <p>Sistem POS - Laporan Stok Produk</p>
    </div>
</body>

</html>
