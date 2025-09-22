function printInvoiceRaw() {
    // Generate raw invoice content
    const rawContent = generateRawInvoiceContent();

    // Create new window for printing
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    // Write raw content with monospace font
    printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice {{ $pembelian->no_faktur }}</title>
                    <style>
                        body {
                            font-family: 'Courier New', monospace;
                            font-size: 12px;
                            line-height: 1.2;
                            margin: 20px;
                            background: white;
                            color: black;
                        }
                        .invoice-content {
                            white-space: pre;
                            font-size: 11px;
                            line-height: 1.1;
                        }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-content">${rawContent}</div>
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            🖨️ Print Invoice
                        </button>
                        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                            ❌ Close
                        </button>
                    </div>
                </body>
                </html>
            `);

    printWindow.document.close();

    // Focus on the new window
    printWindow.focus();
}

function generateRawInvoiceContent() {
    let content = '';

    // Header
    content += '================================\n';
    content += '        {{ $pengaturanUmum->nama_toko }}\n';
    @if ($pengaturanUmum -> deskripsi)
        content += '       {{ $pengaturanUmum->deskripsi }}\n';
    @endif
    @if ($pengaturanUmum -> alamat)
        content += '      {{ $pengaturanUmum->alamat }}\n';
    @endif
    @if ($pengaturanUmum -> no_telepon)
        content += '      Telp: {{ $pengaturanUmum->no_telepon }}\n';
    @endif
    @if ($pengaturanUmum -> email)
        content += '      Email: {{ $pengaturanUmum->email }}\n';
    @endif
    content += '================================\n\n';

    // Invoice info
    content += 'PEMBELIAN\n';
    content += 'No. Faktur: {{ $pembelian->no_faktur }}\n';
    content += 'Tanggal: {{ $pembelian->created_at->format('d / m / Y H:i') }}\n';
    content += 'Supplier: {{ $pembelian->supplier->nama ?? 'N / A' }}\n';
    content += 'Kasir: {{ $pembelian->kasir->name ?? 'N / A' }}\n';
    content += '================================\n\n';

    // Items
    @foreach($pembelian -> detailPembelian as $detail)
    content += '{{ substr($detail->produk->nama_produk, 0, 30) }}\n';
    content +=
        '  {{ number_format($detail->qty, 0) }} {{ $detail->produk->satuan->nama ?? 'pcs' }} x {{ number_format($detail->harga, 0) }} = {{ number_format($detail->subtotal, 0) }}\n';
    @if ($detail -> discount > 0)
        content += '  Diskon: -{{ number_format($detail->discount, 0) }}\n';
    @endif
    content += '\n';
    @endforeach

    content += '--------------------------------\n';

    // Totals
    content += 'Subtotal: Rp {{ number_format($pembelian->total, 0) }}\n';

    @if ($pembelian -> diskon > 0)
        content += 'Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n';
    @endif

    @if ($pembelian -> ppn > 0)
        content += 'PPN: Rp {{ number_format($pembelian->ppn, 0) }}\n';
    @endif

    content += 'TOTAL: Rp {{ number_format($pembelian->grand_total, 0) }}\n\n';

    // Payment info
    @php
    $totalBayar = $pembelian -> pembayaranPembelian -> sum('jumlah_bayar');
    @endphp
    @if ($totalBayar > 0)
        content += 'Bayar: Rp {{ number_format($totalBayar, 0) }}\n';

    @if ($totalBayar < $pembelian -> grand_total)
        @php
    $sisa = $pembelian -> grand_total - $totalBayar;
    @endphp
    content += 'Sisa: Rp {{ number_format($sisa, 0) }}\n';
    @endif
    content += '\n';
    @endif

    content += '================================\n';
    content += '   Terima kasih atas kunjungan Anda\n';
    content += '     Barang yang sudah dibeli\n';
    content += '     tidak dapat dikembalikan\n';
    content += '================================\n';

    return content;
}

function generateInvoiceData() {
    const invoiceLines = [];

    // Header
    invoiceLines.push("\x1B\x40"); // Initialize printer
    // @if ($pengaturanUmum->logo_url)
    //     invoiceLines.push({
    //         type: 'pixel',
    //         format: 'image',
    //         flavor: 'base64',
    //         data: getBase64FromUrl('{{ $pengaturanUmum->logo_url }}')
    //     });
    // @endif
    invoiceLines.push("\x1B\x61\x01"); // Center align
    invoiceLines.push("{{ $pengaturanUmum->nama_toko }}\n");
    @if ($pengaturanUmum -> deskripsi)
        invoiceLines.push("{{ $pengaturanUmum->deskripsi }}\n");
    @endif
    @if ($pengaturanUmum -> alamat)
        invoiceLines.push("{{ $pengaturanUmum->alamat }}\n");
    @endif
    @if ($pengaturanUmum -> no_telepon)
        invoiceLines.push("Telp: {{ $pengaturanUmum->no_telepon }}\n");
    @endif
    @if ($pengaturanUmum -> email)
        invoiceLines.push("Email: {{ $pengaturanUmum->email }}\n");
    @endif
    invoiceLines.push("================================\n");

    // Invoice info
    invoiceLines.push("\x1B\x61\x00"); // Left align
    invoiceLines.push("PEMBELIAN\n");
    invoiceLines.push("No. Faktur: {{ $pembelian->no_faktur }}\n");
    invoiceLines.push("Tanggal: {{ $pembelian->created_at->format('d/m/Y H:i') }}\n");
    invoiceLines.push("Supplier: {{ $pembelian->supplier->nama ?? 'N/A' }}\n");
    invoiceLines.push("Kasir: {{ $pembelian->kasir->name ?? 'N/A' }}\n");
    invoiceLines.push("================================\n");

    // Items
    @foreach($pembelian -> detailPembelian as $detail)
    invoiceLines.push("{{ substr($detail->produk->nama_produk, 0, 20) }}\n");
    invoiceLines.push(
        "  {{ number_format($detail->qty, 0) }} {{ $detail->produk->satuan->nama ?? 'pcs' }} x {{ number_format($detail->harga, 0) }} = {{ number_format($detail->subtotal, 0) }}\n"
    );
    @if ($detail -> discount > 0)
        invoiceLines.push("  Diskon: -{{ number_format($detail->discount, 0) }}\n");
    @endif
    @endforeach

    invoiceLines.push("--------------------------------\n");

    // Totals
    invoiceLines.push("Subtotal: Rp {{ number_format($pembelian->total, 0) }}\n");

    @if ($pembelian -> diskon > 0)
        invoiceLines.push("Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n");
    @endif

    @if ($pembelian -> ppn > 0)
        invoiceLines.push("PPN: Rp {{ number_format($pembelian->ppn, 0) }}\n");
    @endif

    invoiceLines.push("TOTAL: Rp {{ number_format($pembelian->grand_total, 0) }}\n");

    // Payment info
    @php
    $totalBayar = $pembelian -> pembayaranPembelian -> sum('jumlah_bayar');
    @endphp
    @if ($totalBayar > 0)
        invoiceLines.push("Bayar: Rp {{ number_format($totalBayar, 0) }}\n");

    @if ($totalBayar < $pembelian -> grand_total)
        @php
    $sisa = $pembelian -> grand_total - $totalBayar;
    @endphp
    invoiceLines.push("Sisa: Rp {{ number_format($sisa, 0) }}\n");
    @endif
    @endif

    // Payment history detail
    @if ($pembelian -> pembayaranPembelian -> count() > 0)
        invoiceLines.push("================================\n");
    invoiceLines.push("RIWAYAT PEMBAYARAN:\n");
    invoiceLines.push("--------------------------------\n");
    invoiceLines.push(
        "Faktur: {{ $pembelian->no_faktur }} - {{ $pembelian->created_at->format('d/m/Y H:i') }}\n");
    invoiceLines.push("--------------------------------\n");
    @foreach($pembelian -> pembayaranPembelian -> sortBy('created_at') as $pembayaran)
    invoiceLines.push("{{ $pembayaran->no_bukti }}\n");
    @php
    $statusConfig = [
        'D' => 'DP',
        'A' => 'Angsuran',
        'P' => 'Pelunasan',
    ];
    $status = $statusConfig[$pembayaran -> status_bayar] ?? 'DP';
    @endphp
    invoiceLines.push(
        "{{ $pembayaran->created_at->format('d/m/Y H:i') }} - {{ $pembayaran->metode_pembayaran }} ({{ $status }})\n"
    );
    invoiceLines.push("Rp {{ number_format($pembayaran->jumlah_bayar, 0) }}\n");
    @if ($pembayaran -> keterangan)
        invoiceLines.push("{{ $pembayaran->keterangan }}\n");
    @endif
    invoiceLines.push("--------------------------------\n");
    @endforeach
    @endif

    invoiceLines.push("================================\n");
    invoiceLines.push("\x1B\x61\x01"); // Center align
    invoiceLines.push("Terima kasih atas kunjungan Anda\n");
    invoiceLines.push("\n\n\n");
    invoiceLines.push("\x1D\x56\x42\x00"); // Cut paper

    return invoiceLines;
}
