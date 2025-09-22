# Perancangan Database Pembelian

## Overview

Sistem pembelian dirancang dengan struktur yang mirip dengan sistem penjualan, namun disesuaikan untuk menangani pembelian barang dari supplier. Sistem ini terdiri dari 4 tabel utama: `supplier`, `pembelian`, `detail_pembelian`, dan `pembayaran_pembelian`.

## Struktur Tabel

### 1. Tabel `supplier`

Tabel untuk menyimpan data supplier/pemasok barang.

**Kolom:**

-   `id` (Primary Key)
-   `nama` (VARCHAR 100) - Nama supplier
-   `alamat` (TEXT) - Alamat supplier
-   `telepon` (VARCHAR 20) - Nomor telepon
-   `email` (VARCHAR 100) - Email supplier
-   `npwp` (VARCHAR 50) - Nomor NPWP
-   `keterangan` (TEXT) - Catatan tambahan
-   `status` (BOOLEAN) - Status aktif/nonaktif
-   `created_at`, `updated_at` (TIMESTAMP)

**Indexes:**

-   `nama` - Untuk pencarian berdasarkan nama
-   `status` - Untuk filter supplier aktif

### 2. Tabel `pembelian`

Tabel untuk menyimpan data transaksi pembelian.

**Kolom:**

-   `id` (Primary Key)
-   `no_faktur` (VARCHAR 50, UNIQUE) - Nomor faktur pembelian
-   `supplier_id` (Foreign Key) - Referensi ke tabel supplier
-   `tanggal` (DATE) - Tanggal pembelian
-   `subtotal` (DECIMAL 15,2) - Total sebelum diskon
-   `diskon` (DECIMAL 15,2) - Jumlah diskon
-   `total` (DECIMAL 15,2) - Total setelah diskon
-   `status_pembayaran` (ENUM) - 'belum_bayar', 'dp', 'lunas'
-   `jenis_transaksi` (ENUM) - 'tunai', 'kredit'
-   `keterangan` (TEXT) - Catatan tambahan
-   `user_id` (Foreign Key) - User yang membuat transaksi
-   `created_at`, `updated_at` (TIMESTAMP)

**Foreign Keys:**

-   `supplier_id` → `supplier.id` (RESTRICT)
-   `user_id` → `users.id` (RESTRICT)

**Indexes:**

-   `no_faktur` - Untuk pencarian berdasarkan nomor faktur
-   `tanggal` - Untuk filter berdasarkan tanggal
-   `supplier_id` - Untuk relasi dengan supplier
-   `status_pembayaran` - Untuk filter status pembayaran
-   `jenis_transaksi` - Untuk filter jenis transaksi
-   `user_id` - Untuk relasi dengan user

### 3. Tabel `detail_pembelian`

Tabel untuk menyimpan detail item yang dibeli.

**Kolom:**

-   `id` (Primary Key)
-   `pembelian_id` (Foreign Key) - Referensi ke tabel pembelian
-   `produk_id` (Foreign Key) - Referensi ke tabel produk
-   `qty` (DECIMAL 10,2) - Jumlah yang dibeli
-   `harga_beli` (DECIMAL 15,2) - Harga beli per unit
-   `subtotal` (DECIMAL 15,2) - Total harga (qty × harga_beli)
-   `discount` (DECIMAL 15,2) - Diskon per item
-   `created_at`, `updated_at` (TIMESTAMP)

**Foreign Keys:**

-   `pembelian_id` → `pembelian.id` (CASCADE)
-   `produk_id` → `produk.id` (RESTRICT)

**Indexes:**

-   `pembelian_id` - Untuk relasi dengan pembelian
-   `produk_id` - Untuk relasi dengan produk

### 4. Tabel `pembayaran_pembelian`

Tabel untuk menyimpan data pembayaran pembelian.

**Kolom:**

-   `id` (Primary Key)
-   `pembelian_id` (Foreign Key) - Referensi ke tabel pembelian
-   `no_bukti` (VARCHAR 50, UNIQUE) - Nomor bukti pembayaran
-   `tanggal` (DATETIME) - Tanggal dan waktu pembayaran
-   `jumlah_bayar` (DECIMAL 15,2) - Jumlah yang dibayar
-   `metode_pembayaran` (VARCHAR 50) - Metode pembayaran
-   `status_bayar` (ENUM) - 'P'=Pelunasan, 'D'=DP, 'A'=Angsuran, 'B'=Bayar Sebagian
-   `keterangan` (TEXT) - Catatan tambahan
-   `user_id` (Foreign Key) - User yang melakukan pembayaran
-   `created_at`, `updated_at` (TIMESTAMP)

**Foreign Keys:**

-   `pembelian_id` → `pembelian.id` (CASCADE)
-   `user_id` → `users.id` (RESTRICT)

**Indexes:**

-   `pembelian_id` - Untuk relasi dengan pembelian
-   `no_bukti` - Untuk pencarian berdasarkan nomor bukti
-   `tanggal` - Untuk filter berdasarkan tanggal
-   `metode_pembayaran` - Untuk filter metode pembayaran
-   `status_bayar` - Untuk filter status pembayaran
-   `user_id` - Untuk relasi dengan user

## Relasi Antar Tabel

```
supplier (1) ←→ (N) pembelian
pembelian (1) ←→ (N) detail_pembelian
pembelian (1) ←→ (N) pembayaran_pembelian
produk (1) ←→ (N) detail_pembelian
users (1) ←→ (N) pembelian
users (1) ←→ (N) pembayaran_pembelian
```

## Perbedaan dengan Sistem Penjualan

### 1. Nama Tabel

-   `pelanggan` → `supplier`
-   `penjualan` → `pembelian`
-   `detail_penjualan` → `detail_pembelian`
-   `pembayaran_penjualan` → `pembayaran_pembelian`

### 2. Kolom Khusus Supplier

-   `npwp` - Nomor NPWP supplier
-   `status` - Status aktif/nonaktif supplier

### 3. Kolom Khusus Pembelian

-   `harga_beli` - Harga beli per unit (bukan harga jual)
-   `status_pembayaran` - Status pembayaran ke supplier
-   `jenis_transaksi` - Tunai/Kredit

### 4. Status Pembayaran

-   `belum_bayar` - Belum dibayar ke supplier
-   `dp` - Sudah bayar DP
-   `lunas` - Sudah lunas

## Fitur yang Didukung

### 1. Manajemen Supplier

-   CRUD supplier
-   Status aktif/nonaktif
-   Informasi lengkap (alamat, telepon, email, NPWP)

### 2. Transaksi Pembelian

-   Pembelian tunai dan kredit
-   Multiple item per transaksi
-   Diskon per item dan per transaksi
-   Status pembayaran

### 3. Pembayaran Pembelian

-   Multiple pembayaran per transaksi
-   Berbagai metode pembayaran
-   Status pembayaran (Pelunasan, DP, Angsuran, Bayar Sebagian)
-   Tracking sisa pembayaran

### 4. Reporting

-   Laporan pembelian per supplier
-   Laporan pembayaran pembelian
-   Analisis hutang ke supplier
-   Statistik pembelian

## Keamanan dan Integritas Data

### 1. Foreign Key Constraints

-   RESTRICT untuk supplier dan user (mencegah penghapusan data yang masih digunakan)
-   CASCADE untuk detail dan pembayaran (otomatis terhapus jika pembelian dihapus)

### 2. Unique Constraints

-   `no_faktur` - Mencegah duplikasi nomor faktur
-   `no_bukti` - Mencegah duplikasi nomor bukti pembayaran

### 3. Data Validation

-   Validasi format email
-   Validasi format NPWP
-   Validasi jumlah pembayaran tidak melebihi total pembelian

## Migration Files

1. `2025_08_17_000001_create_supplier_table.php`
2. `2025_08_17_000002_create_pembelian_table.php`
3. `2025_08_17_000003_create_detail_pembelian_table.php`
4. `2025_08_17_000004_create_pembayaran_pembelian_table.php`

## Model Files

1. `app/Models/Supplier.php`
2. `app/Models/Pembelian.php`
3. `app/Models/DetailPembelian.php`
4. `app/Models/PembayaranPembelian.php`

## Seeder Files

1. `database/seeders/SupplierSeeder.php`
2. `database/factories/SupplierFactory.php`

