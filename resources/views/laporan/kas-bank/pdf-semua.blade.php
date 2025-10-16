<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Semua Kas & Bank</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 10px 0;
        }

        .header h2 {
            font-size: 18px;
            font-weight: normal;
            color: #666;
            margin: 0;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .info-item:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .info-item:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .info-label {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .saldo-info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .saldo-info p {
            margin: 5px 0;
            font-size: 11px;
        }

        .saldo-info .saldo-awal {
            font-size: 16px;
            font-weight: bold;
            color: #1d4ed8;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .transactions-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #cbd5e1;
            font-size: 11px;
        }

        .transactions-table td {
            padding: 10px 8px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .transactions-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .transactions-table tr.saldo-awal-row {
            background-color: #dbeafe;
            font-weight: bold;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background-color: #fecaca;
            color: #991b1b;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Semua Kas & Bank</h1>
        <h2>Gabungan Semua Kas/Bank</h2>
        <p>
            @if ($laporanData['periode']['jenis'] == 'tanggal')
                Periode: {{ $laporanData['periode']['tanggal_dari'] }} s/d
                {{ $laporanData['periode']['tanggal_sampai'] }}
            @else
                Periode: {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
            @endif
        </p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Info Cards -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Jenis Laporan</div>
                <div class="info-value">Gabungan</div>
            </div>
            <div class="info-item">
                <div class="info-label">Jumlah Kas/Bank</div>
                <div class="info-value">{{ \App\Models\KasBank::count() }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Transaksi</div>
                <div class="info-value">{{ $laporanData['statistics']['jumlah_transaksi'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Periode</div>
                <div class="info-value">
                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                        {{ $laporanData['statistics']['jumlah_hari'] ?? 'N/A' }} hari
                    @else
                        1 bulan
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Saldo Awal Info -->
    <div class="saldo-info">
        <p class="saldo-awal">Total Saldo Awal: Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}</p>
        <p><strong>Catatan:</strong> Saldo awal merupakan gabungan dari semua kas/bank yang aktif</p>
    </div>

    <!-- Transactions Table -->
    <table class="transactions-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kas/Bank</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>No. Transaksi</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Kredit</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Saldo Awal Row -->
            <tr class="saldo-awal-row">
                <td>
                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                        {{ $laporanData['periode']['tanggal_dari'] }}
                    @else
                        {{ $laporanData['periode']['tanggal_awal'] }}
                    @endif
                </td>
                <td>
                    <span class="badge badge-warning">Semua Kas/Bank</span>
                </td>
                <td>Total Saldo Awal</td>
                <td class="text-center">
                    <span class="badge badge-info">Saldo Awal</span>
                </td>
                <td>-</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right"><strong>Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}</strong>
                </td>
            </tr>

            <!-- Transactions -->
            @foreach ($laporanData['transaksi'] as $transaksi)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                    <td>
                        <div>
                            <strong>{{ $transaksi->kas_bank_nama ?? 'N/A' }}</strong>
                            <br>
                            <small class="text-muted">{{ $transaksi->kas_bank_jenis ?? 'N/A' }}</small>
                        </div>
                    </td>
                    <td>{{ $transaksi->keterangan_detail ?? $transaksi->keterangan }}</td>
                    <td class="text-center">
                        @if ($transaksi->jenis_transaksi == 'D')
                            <span class="badge badge-success">Debit</span>
                        @else
                            <span class="badge badge-danger">Kredit</span>
                        @endif
                    </td>
                    <td>{{ $transaksi->no_bukti }}</td>
                    <td class="text-right">
                        @if ($transaksi->jenis_transaksi == 'D')
                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($transaksi->jenis_transaksi == 'K')
                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($transaksi->saldo_akhir, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Table -->
    <table class="summary-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr class="summary-row">
                <td>Total Saldo Awal</td>
                <td class="text-right">Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}</td>
            </tr>
            <tr class="summary-row">
                <td>Total Debet</td>
                <td class="text-right">Rp {{ number_format($laporanData['summary']['total_debet'], 0, ',', '.') }}</td>
            </tr>
            <tr class="summary-row">
                <td>Total Kredit</td>
                <td class="text-right">Rp {{ number_format($laporanData['summary']['total_kredit'], 0, ',', '.') }}
                </td>
            </tr>
            <tr class="total-row">
                <td><strong>Total Saldo Akhir</strong></td>
                <td class="text-right"><strong>Rp
                        {{ number_format($laporanData['summary']['saldo_akhir'], 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Statistics -->
    <div class="info-section">
        <h3 style="color: #2563eb; margin-bottom: 15px;">Statistik Transaksi</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Total Transaksi</div>
                <div class="info-value">{{ $laporanData['statistics']['jumlah_transaksi'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Transaksi Debet</div>
                <div class="info-value">{{ $laporanData['statistics']['transaksi_debet'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Transaksi Kredit</div>
                <div class="info-value">{{ $laporanData['statistics']['transaksi_kredit'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Rata-rata/Transaksi</div>
                <div class="info-value">Rp
                    {{ number_format($laporanData['statistics']['rata_rata_transaksi'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem POS pada {{ now()->format('d F Y H:i:s') }}</p>
        <p><strong>Catatan:</strong> Laporan ini merupakan gabungan dari semua kas/bank yang aktif dalam sistem</p>
    </div>
</body>

</html>
