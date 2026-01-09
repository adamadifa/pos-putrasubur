<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran - Toko Putra Subur</title>
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

        /* Signatures */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
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
        @php
            // Merge and sort data (Logic copied from Controller index method for consistency)
            $pembayaranData = collect();
            if(isset($laporanData['pembayaran_penjualan'])) {
                foreach ($laporanData['pembayaran_penjualan'] as $pembayaran) {
                    $pembayaranData->push((object) [
                        'tanggal' => $pembayaran->tanggal,
                        'jenis' => 'PENJUALAN',
                        'no_faktur' => $pembayaran->penjualan->no_faktur ?? '-',
                        'nama_pelanggan_supplier' => $pembayaran->penjualan->pelanggan->nama ?? 'UMUM',
                        'metode_pembayaran' => $pembayaran->metode_pembayaran ?? '-',
                        'kas_bank' => $pembayaran->kasBank->nama ?? '-',
                        'jumlah' => $pembayaran->jumlah_bayar,
                    ]);
                }
            }
            if(isset($laporanData['pembayaran_pembelian'])) {
                foreach ($laporanData['pembayaran_pembelian'] as $pembayaran) {
                    $pembayaranData->push((object) [
                        'tanggal' => $pembayaran->tanggal,
                        'jenis' => 'PEMBELIAN',
                        'no_faktur' => $pembayaran->pembelian->no_faktur ?? '-',
                        'nama_pelanggan_supplier' => $pembayaran->pembelian->supplier->nama ?? 'UMUM',
                        'metode_pembayaran' => $pembayaran->metode_pembayaran ?? '-',
                        'kas_bank' => $pembayaran->kasBank->nama ?? '-',
                        'jumlah' => $pembayaran->jumlah_bayar,
                    ]);
                }
            }
            $mergedPembayaran = $pembayaranData->sortBy('tanggal')->values();
        @endphp

        <!-- Letterhead -->
        <div class="page-header">
            <h1>TOKO PUTRA SUBUR</h1>
            <p>Jl. Contoh No. 123, Kelurahan Contoh, Kecamatan Contoh, Kota Contoh</p>
            <p>Telp: (021) 123-4567 | Email: info@putrasubur.com</p>
        </div>

        <!-- Report Title -->
        <div class="report-title">
            <h2>Laporan Pembayaran</h2>
        </div>

        <!-- Meta Info -->
        <table class="report-meta" style="width: auto; border: none; margin-bottom: 20px;">
            <tr style="border: none;">
                <td style="border: none; width: 100px;">Periode</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none; font-weight: bold;">
                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                        {{ $laporanData['periode']['tanggal_dari'] }} s.d. {{ $laporanData['periode']['tanggal_sampai'] }}
                    @else
                        {{ strtoupper($laporanData['periode']['bulan_nama']) }} {{ $laporanData['periode']['tahun'] }}
                    @endif
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Dicetak Oleh</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ Auth::user()->name ?? 'Admin' }}</td>
            </tr>
        </table>

        <!-- Data Table -->
        @if ($mergedPembayaran->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%">Jenis</th>
                        <th width="12%">No. Faktur</th>
                        <th width="20%">Pihak Terkait</th>
                        <th width="12%">Metode</th>
                        <th width="15%">Kas/Bank</th>
                        <th width="16%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mergedPembayaran as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $item->jenis }}</td>
                            <td class="text-center">{{ $item->no_faktur }}</td>
                            <td>{{ strtoupper($item->nama_pelanggan_supplier) }}</td>
                            <td class="text-center">{{ strtoupper($item->metode_pembayaran) }}</td>
                            <td>{{ strtoupper($item->kas_bank) }}</td>
                            <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right text-bold" style="background-color: #f0f0f0;">TOTAL PEMBAYARAN</td>
                        <td class="text-right text-bold" style="background-color: #f0f0f0;">Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Summaries -->
            <div style="width: 100%; display: table; margin-bottom: 20px;">
                <!-- Summary Type -->
                <div style="display: table-cell; width: 48%; vertical-align: top; padding-right: 2%;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="3" style="text-align: left;">REKAP JENIS TRANSAKSI</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>Penjualan</td>
                            <td class="text-center">{{ $laporanData['summary']['total_pembayaran_penjualan'] }} Trx</td>
                            <td class="text-right">Rp {{ number_format($laporanData['summary']['total_nilai_penjualan'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Pembelian</td>
                            <td class="text-center">{{ $laporanData['summary']['total_pembayaran_pembelian'] }} Trx</td>
                            <td class="text-right">Rp {{ number_format($laporanData['summary']['total_nilai_pembelian'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Summary Method -->
                <div style="display: table-cell; width: 48%; vertical-align: top; padding-left: 2%;">
                     @if (isset($laporanData['metode_pembayaran_counts']) && count($laporanData['metode_pembayaran_counts']) > 0)
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th colspan="3" style="text-align: left;">REKAP METODE PEMBAYARAN</th>
                            </tr>
                        </thead>
                        @foreach ($laporanData['metode_pembayaran_counts'] as $metode)
                        <tr>
                            <td>{{ strtoupper($metode['nama']) }}</td>
                            <td class="text-center">{{ $metode['count'] }} Trx</td>
                            <td class="text-right">Rp {{ number_format($metode['nilai'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
                </div>
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
                <p>Admin / Keuangan</p>
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
