# Number Helper Documentation

Helper untuk formatting angka dalam format Indonesia dengan pemisah ribuan (titik) dan desimal (koma).

## Functions Available

### 1. `formatRupiah($number, $decimals = 0)`

Format angka ke format rupiah Indonesia.

```php
formatRupiah(1000.30, 2);     // Output: "1.000,30"
formatRupiah(1500000);        // Output: "1.500.000"
formatRupiah(250.5, 1);       // Output: "250,5"
```

### 2. `formatNumber($number, $decimals = 0)`

Format angka dengan pemisah ribuan (titik) dan desimal (koma).

```php
formatNumber(1000.30, 2);     // Output: "1.000,30"
formatNumber(1500000);        // Output: "1.500.000"
formatNumber(250.5, 1);       // Output: "250,5"
```

### 3. `formatCurrency($number, $decimals = 0, $showSymbol = true)`

Format angka ke format mata uang rupiah lengkap.

```php
formatCurrency(1000.30, 2);           // Output: "Rp 1.000,30"
formatCurrency(1500000);              // Output: "Rp 1.500.000"
formatCurrency(250.5, 1, false);     // Output: "250,5"
```

### 4. `parseNumber($formattedNumber)`

Parse string format Indonesia ke float.

```php
parseNumber("1.000,50");      // Output: 1000.5
parseNumber("Rp 1.500.000");  // Output: 1500000
parseNumber("250,30");        // Output: 250.3
```

### 5. `formatPercentage($number, $decimals = 1)`

Format angka ke persentase.

```php
formatPercentage(75.5);       // Output: "75,5%"
formatPercentage(100);        // Output: "100,0%"
formatPercentage(33.33, 2);   // Output: "33,33%"
```

### 6. `formatFileSize($bytes, $precision = 2)`

Format ukuran file ke format yang mudah dibaca.

```php
formatFileSize(1024);         // Output: "1 KB"
formatFileSize(1048576);      // Output: "1 MB"
formatFileSize(1073741824);   // Output: "1 GB"
```

### 7. `formatQuantity($quantity, $unit = '', $decimals = 0)`

Format quantity dengan satuan.

```php
formatQuantity(1000, 'KG');           // Output: "1.000 KG"
formatQuantity(250.5, 'Liter', 1);   // Output: "250,5 Liter"
formatQuantity(50, 'Pcs');           // Output: "50 Pcs"
```

## Usage Examples

### Dalam Controller

```php
<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::find(1);
        $price = formatCurrency($product->price, 0);        // Rp 150.000
        $stock = formatQuantity($product->stock, 'KG');     // 25 KG
        $percentage = formatPercentage(85.5);               // 85,5%

        return view('products.index', compact('price', 'stock', 'percentage'));
    }
}
```

### Dalam Blade Template

```blade
{{-- Format harga --}}
<p>Harga: {{ formatCurrency($product->price, 0) }}</p>

{{-- Format stok --}}
<p>Stok: {{ formatQuantity($product->stock, $product->unit) }}</p>

{{-- Format persentase --}}
<p>Diskon: {{ formatPercentage($product->discount) }}</p>

{{-- Format angka biasa --}}
<p>Total: {{ formatNumber($total, 2) }}</p>
```

### Dalam Model (Accessors)

```php
<?php

namespace App\Models;

class Product extends Model
{
    public function getPriceFormattedAttribute()
    {
        return formatCurrency($this->price, 0);
    }

    public function getStockFormattedAttribute()
    {
        return formatQuantity($this->stock, $this->unit->name ?? '', 0);
    }
}

// Usage:
// $product->price_formatted  // Rp 150.000
// $product->stock_formatted  // 25 KG
```

## JavaScript Integration

Untuk konsistensi dengan frontend, Anda dapat membuat fungsi JavaScript yang serupa:

```javascript
function formatRupiah(number, decimals = 0) {
    return new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(number);
}

function formatCurrency(number, decimals = 0) {
    return "Rp " + formatRupiah(number, decimals);
}

// Usage:
// formatRupiah(1000.30, 2)     // "1.000,30"
// formatCurrency(1500000)      // "Rp 1.500.000"
```

## Installation

1. Helper sudah tersedia secara global setelah `composer dump-autoload`
2. Tidak perlu import atau use statement
3. Dapat digunakan langsung di Controller, Model, atau Blade template

## Notes

-   Semua fungsi menangani nilai null dan empty string dengan aman
-   Format mengikuti standar Indonesia (titik untuk ribuan, koma untuk desimal)
-   Fungsi `parseNumber()` berguna untuk mengkonversi input user kembali ke format database
-   Helper ini thread-safe dan dapat digunakan dalam environment concurrent
