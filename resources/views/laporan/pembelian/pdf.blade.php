<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian - Toko Putra Subur</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            background: #525659; /* Preview background */
            margin: 0;
            padding: 20px 0;
        }

        .sheet {
            background: white;
            width: 297mm; /* A4 Landscape width */
            min-height: 210mm; /* A4 Landscape height */
            margin: auto;
            padding: 1.5cm;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            box-sizing: border-box;
            position: relative;
        }

        /* Header / Letterhead */
        .page-header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }

        .page-header p {
            font-size: 10pt;
            margin: 2px 0;
        }

        /* Report Meta */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            text-decoration: underline;
        }

        .report-meta {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10pt;
        }

        .report-meta td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        /* Summary Section */
        .summary-container {
            width: 40%;
            margin-left: auto;
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        
        .summary-table td {
            padding: 4px 8px;
        }

        /* Signatures */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 70px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer {
            margin-top: 30px;
            font-size: 8pt;
            text-align: right;
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        /* Print Specifics */
        @media print {
            body { 
                background: none;
                margin: 0;
                padding: 0;
            }
            .sheet {
                box-shadow: none;
                width: 100%;
                margin: 0;
                padding: 0; /* Let @page handle margins if supported, or use body padding */
            }
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <!-- Letterhead -->
        <div class="page-header">
            <h1>TOKO PUTRA SUBUR</h1>
            <p>Jl. Contoh No. 123, Kelurahan Contoh, Kecamatan Contoh, Kota Contoh</p>
            <p>Telp: (021) 123-4567 | Email: info@putrasubur.com</p>
        </div>

        <!-- Report Title -->
        <div class="report-title">
            <h2>Laporan Pembelian</h2>
        </div>

        <!-- Meta Info -->
        <table class="report-meta" style="width: auto; border: none; margin-bottom: 20px;">
            <tr style="border: none;">
                <td style="border: none; width: 100px;">Periode</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none; font-weight: bold;">
                    {{ strtoupper($laporanData['periode']['label']) }}
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Dicetak Oleh</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ Auth::user()->name ?? 'Admin' }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Tanggal Cetak</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ date('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <!-- Data Table -->
        @if ($laporanData['pembelian']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="12%">No. Faktur</th>
                        <th width="15%">Supplier</th>
                        <th width="8%">Status</th>
                        <th width="20%">Produk</th>
                        <th width="5%">Qty</th>
                        <th width="12%">Harga Beli</th>
                        <th width="14%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporanData['pembelian'] as $index => $pembelian)
                        @php 
                             $rowCount = $pembelian->detailPembelian->count();
                             if($rowCount == 0) $rowCount = 1;
                        @endphp
                        
                        @if ($pembelian->detailPembelian->count() > 0)
                            @foreach ($pembelian->detailPembelian as $detailIndex => $detail)
                                <tr>
                                    @if ($detailIndex === 0)
                                        <td class="text-center" rowspan="{{ $rowCount }}">{{ $index + 1 }}</td>
                                        <td class="text-center" rowspan="{{ $rowCount }}">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}</td>
                                        <td class="text-center" rowspan="{{ $rowCount }}">{{ $pembelian->no_faktur }}</td>
                                        <td rowspan="{{ $rowCount }}">{{ strtoupper($pembelian->supplier->nama ?? 'SUPPLIER N/A') }}</td>
                                        <td class="text-center" rowspan="{{ $rowCount }}">{{ strtoupper($pembelian->status_pembayaran) }}</td>
                                    @endif
                                    
                                    <td>{{ strtoupper($detail->produk->nama_produk ?? 'PRODUK DIHAPUS') }}</td>
                                    <td class="text-center">{{ fmod($detail->qty, 1) !== 0.00 ? number_format($detail->qty, 2, ',', '.') : number_format($detail->qty, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $pembelian->no_faktur }}</td>
                                <td>{{ strtoupper($pembelian->supplier->nama ?? 'SUPPLIER N/A') }}</td>
                                <td class="text-center">{{ strtoupper($pembelian->status_pembayaran) }}</td>
                                <td colspan="4" class="text-center" style="font-style: italic;">Tidak ada detail item</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary-container">
                <table class="summary-table">
                    <tr>
                        <td class="text-bold" style="background-color: #f0f0f0;">Grand Total Pembelian</td>
                        <td class="text-right text-bold" style="font-size: 11pt;">Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Transaksi</td>
                        <td class="text-right">{{ number_format($laporanData['summary']['total_pembelian']) }}</td>
                    </tr>
                    <tr>
                        <td>Total Item Terbeli</td>
                        <td class="text-right">{{ fmod($laporanData['summary']['total_qty'], 1) !== 0.00 ? number_format($laporanData['summary']['total_qty'], 2, ',', '.') : number_format($laporanData['summary']['total_qty'], 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

        @else
            <div style="text-align: center; padding: 40px; border: 1px dashed #ccc;">
                <p>Data tidak ditemukan untuk periode ini.</p>
            </div>
        @endif

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p>Manager</p>
                <div class="signature-space"></div>
                <p class="signature-name">( ........................................ )</p>
            </div>
            <div class="signature-box">
                <!-- Space for middle signature if needed -->
            </div>
            <div class="signature-box">
                <p>Dibuat Oleh,</p>
                <p>Admin / Purchasing</p>
                <div class="signature-space"></div>
                <p class="signature-name">( {{ strtoupper(Auth::user()->name ?? '........................................') }} )</p>
            </div>
        </div>

        <!-- Footer Page Info -->
        <div class="footer">
            Dicetak pada tanggal: {{ date('d F Y') }} oleh sistem aplikasi POS Putra Subur.
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
