# Fitur "Get Saldo" untuk Saldo Awal Bulanan

## Deskripsi

Fitur "Get Saldo" adalah fitur baru yang ditambahkan pada form input saldo awal bulanan untuk memudahkan pengguna dalam menghitung saldo awal berdasarkan saldo akhir bulan sebelumnya secara otomatis.

## Cara Kerja

### 1. Logika Dasar

-   **Jika belum ada saldo awal**: Form input aktif untuk input manual
-   **Jika sudah ada saldo awal sebelumnya**: Button "Get Saldo" tersedia untuk menghitung saldo akhir bulan sebelumnya

### 2. Perhitungan Saldo

Fitur ini menghitung saldo akhir bulan sebelumnya dengan rumus:

```
Saldo Akhir Bulan Sebelumnya = Saldo Awal Bulan Sebelumnya + Total Transaksi Bulan Sebelumnya
```

### 3. Implementasi Teknis

#### Backend (Controller)

-   **Method**: `getSaldoAkhirBulanSebelumnya()` di `SaldoAwalBulananController`
-   **Route**: `POST /saldo-awal-bulanan/get-saldo-akhir`
-   **Validasi**:
    -   `kas_bank_id` (required, exists)
    -   `periode_bulan` (required, 1-12)
    -   `periode_tahun` (required, min 2020)

#### Frontend (JavaScript)

-   **Form Create**: `resources/views/saldo-awal-bulanan/create.blade.php`
-   **Form Edit**: `resources/views/saldo-awal-bulanan/edit.blade.php`
-   **Fitur**:
    -   Input group dengan button "Get Saldo"
    -   Loading state saat menghitung
    -   Result info dengan detail perhitungan
    -   Auto-fill input field dengan hasil perhitungan

## Komponen UI

### 1. Input Group

```html
<div class="flex space-x-2">
    <!-- Input field -->
    <div class="relative group flex-1">
        <input type="number" name="saldo_awal" id="saldo_awal" ... />
    </div>

    <!-- Get Saldo Button -->
    <button type="button" id="getSaldoBtn" ...>
        <i class="ti ti-calculator"></i>
        Get Saldo
    </button>
</div>
```

### 2. Loading State

```html
<div id="saldoLoading" class="hidden">
    <div class="flex items-center space-x-2 text-sm text-blue-600">
        <i class="ti ti-loader-2 animate-spin"></i>
        <span>Menghitung saldo akhir bulan sebelumnya...</span>
    </div>
</div>
```

### 3. Result Info

```html
<div id="saldoResultInfo" class="hidden">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
        <div class="font-medium text-blue-800" id="saldoResultTitle"></div>
        <div class="text-blue-700" id="saldoResultDetails"></div>
    </div>
</div>
```

## Alur Kerja

### 1. Persiapan Form

1. User memilih kas/bank
2. User memilih periode (bulan dan tahun)
3. Button "Get Saldo" menjadi aktif

### 2. Proses Get Saldo

1. User klik button "Get Saldo"
2. Loading state ditampilkan
3. AJAX request dikirim ke backend
4. Backend menghitung saldo akhir bulan sebelumnya
5. Hasil dikembalikan ke frontend
6. Input field diisi otomatis dengan hasil
7. Detail perhitungan ditampilkan

### 3. Response Data

```json
{
    "success": true,
    "data": {
        "saldo_akhir_bulan_sebelumnya": 1500000,
        "saldo_awal_bulan_sebelumnya": 1000000,
        "total_transaksi_bulan_sebelumnya": 500000,
        "bulan_sebelumnya": "January 2024",
        "periode_dipilih": "February 2024",
        "sudah_ada_saldo_awal": false,
        "saldo_awal_terdaftar": null
    }
}
```

## Validasi dan Error Handling

### 1. Frontend Validation

-   Kas/bank harus dipilih
-   Periode bulan dan tahun harus dipilih
-   Button disabled jika form belum lengkap

### 2. Backend Validation

-   Validasi input parameters
-   Cek keberadaan kas/bank
-   Handle error database

### 3. Error Messages

-   "Pilih kas/bank dan periode terlebih dahulu"
-   "Gagal menghitung saldo: [error message]"
-   "Terjadi kesalahan saat menghitung saldo"

## Keuntungan Fitur

### 1. Efisiensi

-   Mengurangi input manual
-   Menghindari kesalahan perhitungan
-   Konsistensi data

### 2. User Experience

-   Interface yang intuitif
-   Feedback visual yang jelas
-   Loading state yang informatif

### 3. Akurasi

-   Perhitungan otomatis berdasarkan data transaksi
-   Validasi data real-time
-   Konsistensi dengan sistem akuntansi

## Penggunaan

### 1. Untuk Saldo Awal Pertama

-   Input manual saldo awal
-   Button "Get Saldo" tidak tersedia

### 2. Untuk Saldo Awal Berikutnya

-   Pilih kas/bank dan periode
-   Klik "Get Saldo"
-   Review hasil perhitungan
-   Simpan atau edit jika perlu

### 3. Untuk Edit Saldo Awal

-   Fitur yang sama tersedia di form edit
-   Berguna untuk koreksi saldo awal

## Maintenance

### 1. Monitoring

-   Log AJAX requests untuk debugging
-   Monitor response time
-   Track error rates

### 2. Performance

-   Query database yang efisien
-   Caching jika diperlukan
-   Optimasi perhitungan

### 3. Updates

-   Perbaikan bug
-   Enhancement fitur
-   Penambahan validasi

## Dependencies

### 1. Backend

-   Laravel Framework
-   Carbon (untuk manipulasi tanggal)
-   Database dengan tabel `saldo_awal_bulanan` dan `transaksi_kas_bank`

### 2. Frontend

-   jQuery
-   Tailwind CSS
-   Tabler Icons
-   AJAX untuk komunikasi dengan backend

## Testing

### 1. Unit Tests

-   Test method `getSaldoAkhirBulanSebelumnya()`
-   Test validasi input
-   Test perhitungan saldo

### 2. Integration Tests

-   Test AJAX request/response
-   Test UI interactions
-   Test error handling

### 3. User Acceptance Tests

-   Test workflow lengkap
-   Test berbagai skenario
-   Test edge cases





