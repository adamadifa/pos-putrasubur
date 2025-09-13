# Payment History in Sales Receipt Feature

## Fitur Baru

Menambahkan histori pembayaran di struk penjualan untuk memberikan informasi lengkap tentang semua pembayaran yang telah dilakukan.

## Implementasi

### 1. Browser Print Receipt

**File**: `resources/views/penjualan/show.blade.php` - Print Section

**Fitur yang Ditambahkan**:

```html
<!-- Payment History Detail -->
<div style="margin-bottom: 15px;">
    <h3 style="font-size: 12px; font-weight: bold; margin: 0 0 8px 0;">
        RIWAYAT PEMBAYARAN:
    </h3>
    <hr style="border: none; border-top: 1px solid #000; margin: 4px 0;" />
    @foreach ($penjualan->pembayaranPenjualan->sortBy('created_at') as
    $pembayaran)
    <div style="margin-bottom: 6px; font-size: 10px;">
        <div
            style="display: flex; justify-content: space-between; margin-bottom: 2px;"
        >
            <span style="font-weight: bold;">{{ $pembayaran->no_bukti }}</span>
            <span style="font-weight: bold;"
                >Rp {{ number_format($pembayaran->jumlah_bayar, 0) }}</span
            >
        </div>
        <div style="font-size: 9px; color: #666;">
            <span
                >{{ $pembayaran->tanggal->format('d/m/Y') }} - {{
                $pembayaran->metode_pembayaran }}</span
            >
            <span style="margin-left: 8px;">({{ $status }})</span>
        </div>
        @if ($pembayaran->keterangan)
        <div style="font-size: 9px; color: #666; margin-top: 1px;">
            {{ $pembayaran->keterangan }}
        </div>
        @endif
    </div>
    @endforeach
</div>
```

### 2. QZ Tray Thermal Printer

**File**: `resources/views/penjualan/show.blade.php` - generateInvoiceData() function

**Fitur yang Ditambahkan**:

```javascript
// Payment history detail
@if ($penjualan->pembayaranPenjualan->count() > 0)
    invoiceLines.push("================================\n");
    invoiceLines.push("RIWAYAT PEMBAYARAN:\n");
    invoiceLines.push("--------------------------------\n");
    @foreach ($penjualan->pembayaranPenjualan->sortBy('created_at') as $pembayaran)
        invoiceLines.push("{{ $pembayaran->no_bukti }}\n");
        invoiceLines.push("{{ $pembayaran->tanggal->format('d/m/Y') }} - {{ $pembayaran->metode_pembayaran }} ({{ $status }})\n");
        invoiceLines.push("Rp {{ number_format($pembayaran->jumlah_bayar, 0) }}\n");
        @if ($pembayaran->keterangan)
            invoiceLines.push("{{ $pembayaran->keterangan }}\n");
        @endif
        invoiceLines.push("--------------------------------\n");
    @endforeach
@endif
```

## Informasi yang Ditampilkan

### Browser Print Receipt

-   **Judul**: FAKTUR PENJUALAN (bukan INVOICE)
-   **Faktur**: Nomor faktur dan tanggal faktur (format: dd/mm/yyyy hh:mm)
-   **No. Bukti**: Nomor bukti pembayaran
-   **Jumlah**: Jumlah pembayaran dengan format Rupiah
-   **Tanggal**: Tanggal dan waktu pembayaran (format: dd/mm/yyyy hh:mm)
-   **Metode**: Metode pembayaran (tunai, transfer, qris, dll)
-   **Status**: Status pembayaran (DP, Angsuran, Pelunasan)
-   **Keterangan**: Keterangan tambahan jika ada

### QZ Tray Thermal Printer

-   **Judul**: FAKTUR PENJUALAN (bukan INVOICE)
-   **Faktur**: Nomor faktur dan tanggal faktur (format: dd/mm/yyyy hh:mm)
-   **No. Bukti**: Nomor bukti pembayaran
-   **Tanggal & Metode**: Tanggal, waktu dan metode pembayaran
-   **Status**: Status pembayaran dalam kurung
-   **Jumlah**: Jumlah pembayaran dengan format Rupiah
-   **Keterangan**: Keterangan tambahan jika ada

## Status Pembayaran

Sistem mendukung 3 status pembayaran:

-   **D** → **DP** (Down Payment)
-   **A** → **Angsuran** (Installment)
-   **P** → **Pelunasan** (Full Payment)

## Urutan Tampilan

Histori pembayaran diurutkan berdasarkan `created_at` (tanggal pembuatan) secara ascending, sehingga pembayaran pertama akan muncul di atas.

## Kondisi Tampil

Histori pembayaran hanya akan ditampilkan jika:

-   Ada minimal 1 pembayaran (`$penjualan->pembayaranPenjualan->count() > 0`)
-   Jika tidak ada pembayaran, akan menampilkan "BELUM ADA PEMBAYARAN"

## Testing

1. **Test dengan Multiple Payments** - Struk dengan beberapa pembayaran (DP + Angsuran + Pelunasan)
2. **Test dengan Single Payment** - Struk dengan 1 pembayaran saja
3. **Test tanpa Payment** - Struk tanpa pembayaran
4. **Test Browser Print** - Pastikan format tampilan sesuai
5. **Test QZ Tray Print** - Pastikan format thermal printer sesuai

## Manfaat

-   **Transparansi**: Pelanggan dapat melihat semua pembayaran yang telah dilakukan
-   **Audit Trail**: Bukti lengkap transaksi pembayaran
-   **Customer Service**: Memudahkan penanganan keluhan terkait pembayaran
-   **Record Keeping**: Dokumentasi lengkap untuk keperluan administrasi
