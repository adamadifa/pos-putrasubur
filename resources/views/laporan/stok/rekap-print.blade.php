<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Stok - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 11px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        .info { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; }
        .footer-table { width: 100%; border: none; }
        .footer-table td { border: none; text-align: center; width: 33%; }
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h1>REKAP LAPORAN STOK PRODUK</h1>
        <p>Periode: 
            @if ($rekapData['periode']['jenis'] == 'bulan')
                {{ $rekapData['periode']['bulan_nama'] }} {{ $rekapData['periode']['tahun'] }}
            @else
                {{ $rekapData['periode']['tanggal_dari'] }} s/d {{ $rekapData['periode']['tanggal_sampai'] }}
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>NAMA PRODUK</th>
                <th>KATEGORI</th>
                <th width="60" class="text-right">SALDO AWAL</th>
                <th width="60" class="text-right">MASUK (+)</th>
                <th width="90" class="text-right">NOMINAL MASUK</th>
                <th width="60" class="text-right">KELUAR (-)</th>
                <th width="90" class="text-right">NOMINAL KELUAR</th>
                <th width="60" class="text-right">SALDO AKHIR</th>
                <th width="50" class="text-center">SATUAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapData['results'] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['produk']->nama_produk }}</td>
                    <td>{{ $item['produk']->kategori->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item['saldo_awal'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['masuk'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['masuk_nominal'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['keluar'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['keluar_nominal'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($item['saldo_akhir'], 2, ',', '.') }}</td>
                    <td class="text-center">{{ $item['produk']->satuan->nama ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }} oleh {{ auth()->user()->name }}</p>
        <br><br>
        <table class="footer-table">
            <tr>
                <td>Pemeriksa,</td>
                <td></td>
                <td>Gudang/Admin,</td>
            </tr>
            <tr>
                <td height="60"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>( ................. )</td>
                <td></td>
                <td>( ................. )</td>
            </tr>
        </table>
    </div>
</body>
</html>
