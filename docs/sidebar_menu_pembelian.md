# Menu Sidebar Sistem Pembelian

## Overview

Menu sidebar telah berhasil ditambahkan untuk sistem pembelian dengan struktur yang konsisten dengan menu yang sudah ada. Menu ini memungkinkan pengguna untuk mengakses semua fitur pembelian dengan mudah.

## Menu yang Ditambahkan

### 1. Menu Pembelian

**Lokasi**: Bagian "Pembelian" di sidebar

#### Menu Pembelian

-   **Route**: `/pembelian`
-   **Icon**: Shopping cart (keranjang belanja)
-   **Fungsi**: Mengakses halaman daftar pembelian
-   **Active State**: `request()->routeIs('pembelian.*')`

#### Menu Pembayaran

-   **Route**: `/pembayaran-pembelian`
-   **Icon**: Credit card (kartu kredit)
-   **Fungsi**: Mengakses halaman daftar pembayaran pembelian
-   **Active State**: `request()->routeIs('pembayaran-pembelian.*')`

### 2. Menu Master Data Supplier

**Lokasi**: Bagian "Master Data" di sidebar (dipindah ke posisi pertama)

#### Menu Supplier

-   **Route**: `/supplier`
-   **Icon**: Building storefront (toko)
-   **Fungsi**: Mengakses halaman daftar supplier
-   **Active State**: `request()->routeIs('supplier.*')`

### 3. Menu Laporan Pembelian

**Lokasi**: Bagian "Laporan" di sidebar

#### Menu Laporan Pembelian

-   **Route**: `/laporan/pembelian`
-   **Icon**: Chart bar (grafik batang)
-   **Fungsi**: Mengakses halaman laporan pembelian
-   **Active State**: `request()->routeIs('laporan.pembelian')`

## Struktur Menu Lengkap

```
📊 Dashboard
├── 📋 Master Data
│   ├── Produk
│   ├── Kategori
│   ├── Satuan
│   ├── Pelanggan
│   └── Supplier ← NEW
├── 🛒 Penjualan
│   ├── Penjualan
│   └── Pembayaran
├── 🛒 Pembelian
│   ├── Pembelian ← NEW
│   └── Pembayaran ← NEW
├── 📈 Laporan
│   ├── Laporan Penjualan
│   ├── Laporan Produk
│   ├── Laporan Pembayaran
│   └── Laporan Pembelian ← NEW
└── ⚙️ Pengaturan
    └── Pengaturan Printer
```

## File yang Dimodifikasi

### 1. Layout Sidebar

**File**: `resources/views/layouts/pos.blade.php`

**Perubahan**:

-   Memindahkan Master Data ke posisi pertama (sebelum transaksi)
-   Memisahkan Transaksi menjadi dua grup: "Penjualan" dan "Pembelian"
-   Menambahkan menu Pembelian di bagian "Pembelian"
-   Menambahkan menu Pembayaran di bagian "Pembelian"
-   Menambahkan menu Supplier di bagian Master Data
-   Menambahkan menu Laporan Pembelian di bagian Laporan

### 2. Routes

**File**: `routes/web.php`

**Perubahan**:

-   Menambahkan route untuk laporan pembelian: `Route::get('/pembelian', ...)->name('pembelian');`

### 3. View Laporan

**File**: `resources/views/laporan/pembelian.blade.php`

**Fitur**:

-   Filter berdasarkan tanggal dan supplier
-   Statistik cards (Total Pembelian, Total Nilai, Pembelian Tunai, Pembelian Kredit)
-   Data table dengan pagination
-   Export Excel dan PDF
-   Modal detail pembelian
-   Responsive design

## Icon yang Digunakan

### Menu Pembelian

```html
<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    class="w-5 h-5 mr-3"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
    />
</svg>
```

### Menu Pembayaran Pembelian

```html
<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    class="w-5 h-5 mr-3"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"
    />
</svg>
```

### Menu Supplier

```html
<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    class="w-5 h-5 mr-3"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8a3 3 0 100-6 3 3 0 000 6z"
    />
</svg>
```

### Menu Laporan Pembelian

```html
<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    class="w-5 h-5 mr-3"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"
    />
</svg>
```

## Styling dan Active States

### Active State Styling

```css
bg-primary-50 text-primary-700 border-r-2 border-primary-600
```

### Hover State Styling

```css
text-gray-700 hover:bg-gray-50
```

### Default State Styling

```css
text-gray-700
```

## Responsive Design

### Desktop (lg:)

-   Sidebar selalu terlihat
-   Menu items dengan padding dan spacing yang optimal

### Mobile (sm:)

-   Sidebar dapat di-toggle dengan hamburger menu
-   Overlay background saat sidebar terbuka
-   Touch-friendly menu items

## JavaScript Functionality

### Laporan Pembelian

-   **Flatpickr**: Date picker untuk filter tanggal
-   **AJAX**: Load data secara dinamis
-   **Modal**: Detail pembelian dalam modal
-   **Export**: Export Excel dan PDF (placeholder)

### Features

-   Real-time filtering
-   Responsive table
-   Loading states
-   Error handling
-   Currency formatting
-   Date formatting

## Testing Menu

### Cara Testing

1. **Dashboard**: `http://localhost:8000/dashboard`
2. **Pembelian**: `http://localhost:8000/pembelian`
3. **Pembayaran Pembelian**: `http://localhost:8000/pembayaran-pembelian`
4. **Supplier**: `http://localhost:8000/supplier`
5. **Laporan Pembelian**: `http://localhost:8000/laporan/pembelian`

### Expected Behavior

-   Menu aktif akan memiliki background biru dan border kanan
-   Hover effect pada menu items
-   Responsive design pada mobile
-   Smooth transitions

## Keamanan

### Route Protection

-   Semua routes dilindungi dengan middleware `auth`
-   Encrypted IDs untuk keamanan
-   CSRF protection

### Access Control

-   User authentication required
-   Role-based access (jika diperlukan)

## Maintenance

### Menambah Menu Baru

1. Tambahkan route di `routes/web.php`
2. Tambahkan menu item di `resources/views/layouts/pos.blade.php`
3. Gunakan icon yang konsisten
4. Test responsive design

### Mengubah Styling

1. Modifikasi CSS classes di sidebar
2. Pastikan konsistensi dengan design system
3. Test di berbagai ukuran layar

## Kesimpulan

Menu sidebar untuk sistem pembelian telah berhasil diimplementasikan dengan:

✅ **Struktur yang konsisten** dengan menu yang sudah ada  
✅ **Icon yang sesuai** untuk setiap menu  
✅ **Active states** yang jelas  
✅ **Responsive design** untuk mobile dan desktop  
✅ **Hover effects** yang smooth  
✅ **Route protection** yang aman  
✅ **Documentation** yang lengkap

Menu ini memungkinkan pengguna untuk mengakses semua fitur pembelian dengan mudah dan intuitif, sesuai dengan best practices UI/UX modern.
