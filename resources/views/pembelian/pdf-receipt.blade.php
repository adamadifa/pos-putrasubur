<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice {{ $pembelian->no_faktur }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 5px 10px;
            background: white;
            color: black;
            max-width: 280px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .store-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 11px;
            line-height: 1.2;
        }

        .separator {
            text-align: center;
            margin: 10px 0;
            font-size: 11px;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }

        .product-item {
            margin: 5px 0;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .product-details {
            font-size: 11px;
            margin-left: 10px;
        }

        .summary {
            margin: 10px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .payment-history {
            margin: 15px 0;
        }

        .payment-item {
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px dashed #ccc;
        }

        .payment-header {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .payment-details {
            font-size: 10px;
            margin-left: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
        }

        @media print {
            body {
                margin: 0;
                padding: 3px 8px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="store-name">{{ $pengaturanUmum->nama_toko }}</div>
        <div class="store-info">
            @if ($pengaturanUmum->deskripsi)
                {{ $pengaturanUmum->deskripsi }}<br>
            @endif
            @if ($pengaturanUmum->alamat)
                {{ $pengaturanUmum->alamat }}<br>
            @endif
            @if ($pengaturanUmum->no_telepon)
                Telp: {{ $pengaturanUmum->no_telepon }}<br>
            @endif
            @if ($pengaturanUmum->email)
                Email: {{ $pengaturanUmum->email }}
            @endif
        </div>
    </div>

    <div class="separator">================================</div>

    <!-- Faktur Pembelian -->
    <div class="section-title">FAKTUR PEMBELIAN</div>

    <div class="info-row">
        <span>No. Faktur:</span>
        <span>{{ $pembelian->no_faktur }}</span>
    </div>
    <div class="info-row">
        <span>Tanggal:</span>
        <span>{{ $pembelian->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-row">
        <span>Supplier:</span>
        <span>{{ $pembelian->supplier->nama ?? 'N/A' }}</span>
    </div>
    <div class="info-row">
        <span>Kasir:</span>
        <span>{{ $pembelian->user->name ?? 'Admin' }}</span>
    </div>

    <div class="separator">================================</div>

    <!-- Product Details -->
    @foreach ($pembelian->detailPembelian as $detail)
        <div class="product-item">
            <div class="product-name">{{ $detail->produk->nama_produk }}</div>
            <div class="product-details">
                {{ number_format($detail->qty, 0) }} {{ $detail->produk->satuan->nama ?? 'Kg' }} x
                {{ number_format($detail->harga_beli, 0) }} = {{ number_format($detail->subtotal, 0) }}
                @if ($detail->discount > 0)
                    <br>Diskon: -{{ number_format($detail->discount, 0) }}
                @endif
            </div>
        </div>
    @endforeach

    <div class="separator">--------------------------------</div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($pembelian->total, 0) }}</span>
        </div>
        @if ($pembelian->diskon > 0)
            <div class="summary-row">
                <span>Diskon:</span>
                <span>-Rp {{ number_format($pembelian->diskon, 0) }}</span>
            </div>
        @endif
        <div class="summary-row total-row">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($pembelian->total, 0) }}</span>
        </div>

        @php
            $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
        @endphp
        @if ($totalBayar > 0)
            <div class="summary-row">
                <span>Bayar:</span>
                <span>Rp {{ number_format($totalBayar, 0) }}</span>
            </div>
            @if ($totalBayar < $pembelian->total)
                @php
                    $sisa = $pembelian->total - $totalBayar;
                @endphp
                <div class="summary-row">
                    <span>Sisa:</span>
                    <span>Rp {{ number_format($sisa, 0) }}</span>
                </div>
            @endif
        @endif
    </div>

    <div class="separator">================================</div>

    <!-- Payment History -->
    <div class="payment-history">
        <div class="section-title">RIWAYAT PEMBAYARAN:</div>
        <div class="separator">--------------------------------</div>

        <div class="payment-item">
            <div class="payment-header">Faktur: {{ $pembelian->no_faktur }} -
                {{ $pembelian->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="separator">--------------------------------</div>

        @foreach ($pembelian->pembayaranPembelian->sortBy('created_at') as $pembayaran)
            <div class="payment-item">
                <div class="payment-header">{{ $pembayaran->no_bukti }}</div>
                <div class="payment-details">
                    {{ $pembayaran->created_at->format('d/m/Y H:i') }} -
                    {{ strtoupper($pembayaran->metode_pembayaran ?? 'Tunai') }}
                    ({{ $pembayaran->status_bayar == 'P' ? 'Pelunasan' : ($pembayaran->status_bayar == 'D' ? 'DP' : 'Angsuran') }})
                    <br>
                    Rp {{ number_format($pembayaran->jumlah_bayar, 0) }}<br>
                    {{ $pembayaran->keterangan ?? 'Pembayaran tunai penuh' }}
                </div>
            </div>
            <div class="separator">--------------------------------</div>
        @endforeach
    </div>

    <div class="separator">================================</div>

    <!-- Footer -->
    <div class="footer">
        Terima kasih atas kunjungan Anda
    </div>
</body>

</html>
