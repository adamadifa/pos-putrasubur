# Logo pada Receipt Print Feature

## Overview

Fitur untuk menampilkan logo toko pada struk penjualan dan pembelian, baik untuk QZ Tray (thermal printer) maupun Browser Print.

## Implementation

### 1. Logo Source

Logo diambil dari `pengaturan_umum` table:

-   **Field**: `logo` (file path)
-   **Accessor**: `logo_url` (full URL)
-   **Conditional**: Logo hanya ditampilkan jika `logo_url` tersedia

### 2. QZ Tray Implementation

#### Penjualan (`resources/views/penjualan/show.blade.php`)

```javascript
@if($pengaturanUmum->logo_url)
    // Logo (if available) - QZ Tray will handle image from URL
    invoiceLines.push("\x1B\x61\x01"); // Center align
    invoiceLines.push("{{ $pengaturanUmum->logo_url }}"); // Logo URL for QZ Tray
    invoiceLines.push("\n");
@endif
```

#### Pembelian (`resources/views/pembelian/show.blade.php`)

```javascript
@if($pengaturanUmum->logo_url)
    // Logo (if available) - QZ Tray will handle image from URL
    invoiceLines.push("\x1B\x61\x01"); // Center align
    invoiceLines.push("{{ $pengaturanUmum->logo_url }}"); // Logo URL for QZ Tray
    invoiceLines.push("\n");
@endif
```

### 3. Browser Print Implementation

#### Penjualan & Pembelian

```blade
@if($pengaturanUmum->logo_url)
    <div style="margin-bottom: 10px;">
        <img src="{{ $pengaturanUmum->logo_url }}" alt="{{ $pengaturanUmum->nama_toko }}"
             style="max-width: 150px; max-height: 60px; object-fit: contain;">
    </div>
@endif
```

## Logo Styling

### Browser Print Styling

-   **Max Width**: 150px
-   **Max Height**: 60px
-   **Object Fit**: contain (maintain aspect ratio)
-   **Position**: Centered above store name
-   **Margin**: 10px bottom spacing

### QZ Tray Styling

-   **Alignment**: Center aligned
-   **Position**: Above store name
-   **Format**: QZ Tray handles image rendering automatically

## Placement

### Receipt Structure

```
┌─────────────────────────┐
│        [LOGO]           │  ← Logo (if available)
│      NAMA TOKO          │
│      Deskripsi          │
│      Alamat             │
│      Telp/Email         │
│  ────────────────────   │
│    FAKTUR PENJUALAN     │
│  ────────────────────   │
│    [Receipt Content]    │
│                         │
└─────────────────────────┘
```

## Features

### Conditional Display

-   Logo hanya muncul jika `$pengaturanUmum->logo_url` tersedia
-   Tidak ada placeholder atau error jika logo tidak ada
-   Graceful fallback ke layout tanpa logo

### Responsive Design

-   Logo di-browser print responsive dengan `object-fit: contain`
-   Ukuran maksimal untuk thermal printer compatibility
-   Center alignment untuk konsistensi visual

### Cross-Platform Support

-   **QZ Tray**: Thermal printer support dengan image handling
-   **Browser Print**: Standard HTML img tag dengan CSS styling
-   **Both**: Conditional rendering berdasarkan availability

## Testing

### Test Cases

1. **With Logo**: Test dengan logo tersedia di pengaturan
2. **Without Logo**: Test tanpa logo (fallback)
3. **QZ Tray Print**: Test logo pada thermal printer
4. **Browser Print**: Test logo pada browser print
5. **Both Modules**: Test di penjualan dan pembelian

### Expected Results

-   Logo muncul di atas nama toko
-   Ukuran logo proporsional dan tidak overflow
-   Tidak ada error jika logo tidak tersedia
-   Konsisten di semua print method

## Files Modified

-   `resources/views/penjualan/show.blade.php` - QZ Tray & Browser Print
-   `resources/views/pembelian/show.blade.php` - QZ Tray & Browser Print

## Dependencies

-   `pengaturan_umum` table dengan field `logo`
-   `PengaturanUmum` model dengan `getLogoUrlAttribute()` accessor
-   Global `$pengaturanUmum` variable via Service Provider













