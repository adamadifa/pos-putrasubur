<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - {{ $laporanData['produk']['nama_produk'] }}</title>
    <!-- Use Tailwind CSS for consistent styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        @media print {
            body {
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .sheet {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
            }
            thead {
                display: table-header-group;
            }
            tr {
                page-break-inside: avoid;
            }
        }

        body {
            background-color: #525659;
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .sheet {
            background: white;
            width: 297mm; /* A4 Landscape width */
            min-height: 210mm; /* A4 Landscape height */
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            position: relative;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 12pt;
            font-weight: normal;
            margin: 5px 0;
        }

        .header p {
            font-size: 10pt;
            margin: 0;
            font-style: italic;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-separator {
            width: 20px;
            text-align: center;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 20px;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 5px 8px;
        }

        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .data-table td {
            vertical-align: top;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .summary-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="sheet">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Stok Produk</h1>
            <h2>{{ strtoupper($laporanData['produk']['nama_produk']) }}</h2>
            <p>
                @if ($laporanData['periode']['jenis'] == 'tanggal')
                    Periode: {{ $laporanData['periode']['tanggal_dari'] }} s/d {{ $laporanData['periode']['tanggal_sampai'] }}
                @else
                    Periode: {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
                @endif
            </p>
        </div>

        <!-- Product Info -->
        <table class="info-table">
            <tr>
                <td class="info-label">Kategori</td>
                <td class="info-separator">:</td>
                <td>{{ $laporanData['produk']['kategori'] }}</td>
                <td class="info-label">Satuan</td>
                <td class="info-separator">:</td>
                <td>{{ $laporanData['produk']['satuan'] }}</td>
            </tr>
            <tr>
                <td class="info-label">Saldo Awal</td>
                <td class="info-separator">:</td>
                <td>
                    {{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}
                    @if ($laporanData['saldo_awal_terakhir'])
                         <span class="text-xs italic text-gray-600">(Per {{ $laporanData['saldo_awal_terakhir']['periode_saldo_awal'] }})</span>
                    @endif
                </td>
                <td class="info-label">Dicetak Pada</td>
                <td class="info-separator">:</td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <!-- Main Data Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 10%">Tanggal</th>
                    <th style="width: 15%">No Transaksi</th>
                    <th style="width: 30%">Keterangan</th>
                    <th style="width: 10%">Jenis</th>
                    <th style="width: 10%">In</th>
                    <th style="width: 10%">Out</th>
                    <th style="width: 10%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $runningSaldo = $laporanData['saldo_awal'];
                    $no = 1;
                @endphp

                <!-- Saldo Awal Row -->
                <tr style="background-color: #f9fafb;">
                    <td class="text-center">-</td>
                    <td class="text-center">
                        @if ($laporanData['periode']['jenis'] == 'tanggal')
                            {{ $laporanData['periode']['tanggal_dari'] }}
                        @else
                            {{ $laporanData['periode']['tanggal_awal'] }}
                        @endif
                    </td>
                    <td class="text-center">-</td>
                    <td><strong>SALDO AWAL PERIODE</strong></td>
                    <td class="text-center">-</td>
                    <td class="text-right">-</td>
                    <td class="text-right">-</td>
                    <td class="text-right font-bold">{{ number_format($runningSaldo, 2, ',', '.') }}</td>
                </tr>

                @forelse ($laporanData['transaksi'] as $transaksi)
                    @php
                        if ($transaksi->jenis == 'pembelian') {
                            $runningSaldo += $transaksi->jumlah;
                        } elseif ($transaksi->jenis == 'penjualan') {
                            $runningSaldo -= $transaksi->jumlah;
                        } elseif ($transaksi->jenis == 'penyesuaian') {
                            $runningSaldo += $transaksi->jumlah;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $transaksi->no_transaksi }}</td>
                        <td>{{ $transaksi->keterangan }}</td>
                        <td class="text-center text-xs uppercase">{{ $transaksi->jenis }}</td>
                        <td class="text-right">
                            @if ($transaksi->jenis == 'pembelian')
                                {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                            @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah > 0)
                                {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($transaksi->jenis == 'penjualan')
                                {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                            @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah < 0)
                                {{ number_format(abs($transaksi->jumlah), 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($runningSaldo, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                 <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="5" class="text-center">TOTAL MUTASI</td>
                    <td class="text-right">
                        {{ number_format($laporanData['summary']['total_pembelian'] + max(0, $laporanData['summary']['total_penyesuaian']), 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                         {{ number_format($laporanData['summary']['total_penjualan'] + abs(min(0, $laporanData['summary']['total_penyesuaian'])), 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Summary Box -->
        <div class="flex justify-end">
            <div style="width: 300px;" class="summary-box">
                <table style="width: 100%;" class="text-sm">
                    <tr>
                        <td>Total Pembelian</td>
                        <td class="text-right font-bold">{{ number_format($laporanData['summary']['total_pembelian'], 2, ',', '.') }}</td>
                    </tr>
                     <tr>
                        <td>Total Penjualan</td>
                        <td class="text-right font-bold">{{ number_format($laporanData['summary']['total_penjualan'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Penyesuaian</td>
                        <td class="text-right font-bold">{{ number_format($laporanData['summary']['total_penyesuaian'], 2, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #ddd;">
                        <td class="font-bold pt-2">Saldo Akhir</td>
                        <td class="text-right font-bold pt-2">{{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <div class="signature-space"></div>
                <p><strong>( Manager )</strong></p>
            </div>
            <div class="signature-box">
                <p>Dibuat Oleh,</p>
                <div class="signature-space"></div>
                <p><strong>( Admin Gudang )</strong></p>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
