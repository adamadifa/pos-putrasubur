<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 10px 0;
        }

        .header p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
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
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: 500;
            color: #6b7280;
        }

        .info-value {
            font-weight: 600;
            color: #111827;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .summary-table th,
        .summary-table td {
            padding: 12px;
            border: 1px solid #cbd5e1;
        }

        .summary-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
        }

        .summary-table .summary-row {
            background-color: #f8fafc;
        }

        .summary-table .total-row {
            background-color: #dbeafe;
            font-weight: bold;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .transactions-table th,
        .transactions-table td {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        .transactions-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            font-size: 11px;
        }

        .transactions-table td {
            font-size: 11px;
        }

        .transactions-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .transactions-table tbody tr:hover {
            background-color: #e0f2fe;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-penjualan {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-pembelian {
            background-color: #fed7aa;
            color: #9a3412;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .page-break {
            page-break-before: always;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PEMBAYARAN</h1>
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
    <h3>Detail Transaksi Pembayaran</h3>
    @if ($laporanData['pembayaran_penjualan']->count() > 0 || $laporanData['pembayaran_pembelian']->count() > 0)
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>No. Faktur</th>
                    <th>Pelanggan/Supplier</th>
                    <th>Metode Pembayaran</th>
                    <th>Kas Bank</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <!-- Pembayaran Penjualan -->
                @foreach ($laporanData['pembayaran_penjualan'] as $pembayaran)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <span class="badge badge-penjualan">Penjualan</span>
                        </td>
                        <td>{{ $pembayaran->penjualan->no_faktur ?? '-' }}</td>
                        <td>{{ $pembayaran->penjualan->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $pembayaran->metode_pembayaran ?? '-' }}</td>
                        <td>{{ $pembayaran->kasBank->nama ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                <!-- Pembayaran Pembelian -->
                @foreach ($laporanData['pembayaran_pembelian'] as $pembayaran)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <span class="badge badge-pembelian">Pembelian</span>
                        </td>
                        <td>{{ $pembayaran->pembelian->no_faktur ?? '-' }}</td>
                        <td>{{ $pembayaran->pembelian->supplier->nama ?? '-' }}</td>
                        <td>{{ $pembayaran->metode_pembayaran ?? '-' }}</td>
                        <td>{{ $pembayaran->kasBank->nama ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary by Metode Pembayaran -->
        @if (isset($laporanData['metode_pembayaran_counts']) && count($laporanData['metode_pembayaran_counts']) > 0)
            <div style="margin-top: 20px;">
                <h4 style="font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #1f2937;">Rekap Metode
                    Pembayaran</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <th class="text-center">Jumlah Transaksi</th>
                            <th class="text-right">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanData['metode_pembayaran_counts'] as $metode)
                            <tr>
                                <td>{{ $metode['nama'] }}</td>
                                <td class="text-center">{{ number_format($metode['count'], 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($metode['nilai'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Summary by Kas Bank -->
        @if (isset($laporanData['kas_bank_counts']) && count($laporanData['kas_bank_counts']) > 0)
            <div style="margin-top: 20px;">
                <h4 style="font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #1f2937;">Rekap Kas Bank</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Kas Bank</th>
                            <th class="text-center">Jumlah Transaksi</th>
                            <th class="text-right">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanData['kas_bank_counts'] as $kasBank)
                            <tr>
                                <td>{{ $kasBank['nama'] }}</td>
                                <td class="text-center">{{ number_format($kasBank['count'], 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($kasBank['nilai'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="no-data">
            <p>Tidak ada data pembayaran untuk periode yang dipilih</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem POS pada {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>

</html>
