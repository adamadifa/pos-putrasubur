<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
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
            margin-right: 10px;
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

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
        }

        .info-value {
            color: #495057;
        }

        .summary-section {
            margin-bottom: 30px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .summary-card h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: bold;
            color: #1976d2;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #0d47a1;
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

        .summary-table .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-lunas {
            background-color: #d4edda;
            color: #155724;
        }

        .status-belum-lunas {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PEMBELIAN</h1>
    </div>





    <!-- Detail Transaksi -->
    <h3>Detail Transaksi Pembelian</h3>
    @if ($laporanData['pembelian']->count() > 0)
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. Faktur</th>
                    <th>Supplier</th>
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
                @foreach ($laporanData['pembelian'] as $index => $pembelian)
                    @if ($pembelian->detailPembelian->count() > 0)
                        @foreach ($pembelian->detailPembelian as $detailIndex => $detail)
                            <tr>
                                @if ($detailIndex === 0)
                                    <td class="text-center" rowspan="{{ $pembelian->detailPembelian->count() }}">
                                        {{ $index + 1 }}</td>
                                    <td rowspan="{{ $pembelian->detailPembelian->count() }}">
                                        {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}</td>
                                    <td rowspan="{{ $pembelian->detailPembelian->count() }}">
                                        {{ $pembelian->no_faktur }}</td>
                                    <td rowspan="{{ $pembelian->detailPembelian->count() }}">
                                        {{ $pembelian->supplier->nama ?? '-' }}</td>
                                    <td class="text-center" rowspan="{{ $pembelian->detailPembelian->count() }}">
                                        <span class="status-badge jenis-{{ $pembelian->jenis_transaksi }}">
                                            {{ $pembelian->jenis_transaksi == 'tunai' ? 'T' : 'K' }}
                                        </span>
                                    </td>
                                    <td class="text-center" rowspan="{{ $pembelian->detailPembelian->count() }}">
                                        <span class="status-badge status-{{ $pembelian->status_pembayaran }}">
                                            {{ $pembelian->status_pembayaran == 'lunas' ? 'L' : 'BL' }}
                                        </span>
                                    </td>
                                @endif
                                <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                                <td class="text-right">{{ number_format($detail->qty, 2, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->discount ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                @if ($detailIndex === 0)
                                    <td class="text-right" rowspan="{{ $pembelian->detailPembelian->count() }}">Rp
                                        {{ number_format($pembelian->diskon, 0, ',', '.') }}</td>
                                    <td class="text-right" rowspan="{{ $pembelian->detailPembelian->count() }}">Rp
                                        {{ number_format($pembelian->subtotal - $pembelian->diskon, 0, ',', '.') }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $pembelian->no_faktur }}</td>
                            <td>{{ $pembelian->supplier->nama ?? '-' }}</td>
                            <td class="text-center">
                                <span class="status-badge jenis-{{ $pembelian->jenis_transaksi }}">
                                    {{ $pembelian->jenis_transaksi == 'tunai' ? 'T' : 'K' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $pembelian->status_pembayaran }}">
                                    {{ $pembelian->status_pembayaran == 'lunas' ? 'L' : 'BL' }}
                                </span>
                            </td>
                            <td colspan="5" class="text-center">-</td>
                            <td class="text-right">Rp {{ number_format($pembelian->diskon, 0, ',', '.') }}</td>
                            <td class="text-right">Rp
                                {{ number_format($pembelian->subtotal - $pembelian->diskon, 0, ',', '.') }}</td>
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
    @if ($laporanData['pembelian']->count() > 0)
        <table class="summary-table">
            <thead>
                <tr>
                    <th colspan="2">RINGKASAN TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr class="summary-row">
                    <td>Total Transaksi</td>
                    <td class="text-right">{{ number_format($laporanData['summary']['total_pembelian']) }}</td>
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
        <p>Laporan ini dibuat secara otomatis pada {{ date('d/m/Y H:i:s') }}</p>
        <p>Sistem Point of Sale (POS) - {{ config('app.name') }}</p>
    </div>
</body>

</html>
