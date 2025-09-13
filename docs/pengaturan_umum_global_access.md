# Panduan Global Access untuk Data Pengaturan Umum

## 📋 Overview

Data pengaturan umum (nama toko, logo, alamat, dll) sekarang dapat diakses di **semua view** dalam aplikasi tanpa perlu pass data dari controller. Implementasi ini menggunakan:

1. **Service Provider** - Inject data ke semua view
2. **Helper Functions** - Akses mudah dengan function global
3. **Cache System** - Performa optimal dengan cache
4. **Fallback Data** - Data default jika belum ada pengaturan

## 🚀 Cara Menggunakan

### **Method 1: Menggunakan Variabel `$pengaturanUmum`**

Variabel ini tersedia di **semua view** berkat Service Provider:

```blade
<!-- Di view apapun -->
<div>
    <h1>{{ $pengaturanUmum->nama_toko }}</h1>
    <p>{{ $pengaturanUmum->alamat }}</p>
    <p>{{ $pengaturanUmum->no_telepon }}</p>
    <p>{{ $pengaturanUmum->email }}</p>

    @if($pengaturanUmum->logo_url)
        <img src="{{ $pengaturanUmum->logo_url }}" alt="{{ $pengaturanUmum->nama_toko }}">
    @endif
</div>
```

### **Method 2: Menggunakan Helper Functions**

Helper functions tersedia di **semua file PHP**:

```blade
<!-- Di view -->
<div>
    <h1>{{ nama_toko() }}</h1>
    <p>{{ alamat_toko() }}</p>
    <p>{{ no_telepon_toko() }}</p>
    <p>{{ email_toko() }}</p>

    @if(has_logo_toko())
        <img src="{{ logo_toko() }}" alt="{{ nama_toko() }}">
    @endif
</div>
```

```php
// Di controller atau class PHP lainnya
public function someMethod()
{
    $namaToko = nama_toko();
    $emailToko = email_toko();
    $logoUrl = logo_toko();

    // ... logic lainnya
}
```

## 📚 Daftar Helper Functions

| Function                   | Description             | Return Type  |
| -------------------------- | ----------------------- | ------------ |
| `nama_toko()`              | Nama toko               | string       |
| `alamat_toko()`            | Alamat toko             | string\|null |
| `no_telepon_toko()`        | Nomor telepon           | string\|null |
| `email_toko()`             | Email toko              | string\|null |
| `deskripsi_toko()`         | Deskripsi toko          | string\|null |
| `logo_toko()`              | URL logo toko           | string\|null |
| `has_logo_toko()`          | Cek apakah ada logo     | bool         |
| `pengaturan_umum()`        | Data lengkap pengaturan | object\|null |
| `clear_pengaturan_cache()` | Clear cache pengaturan  | void         |

## 🎯 Contoh Penggunaan Praktis

### **1. Header/Navbar**

```blade
<header class="bg-white shadow-sm">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            @if(has_logo_toko())
                <img src="{{ logo_toko() }}" alt="{{ nama_toko() }}" class="h-10 w-10">
            @endif
            <h1 class="text-xl font-bold">{{ nama_toko() }}</h1>
        </div>
        <div class="text-sm text-gray-600">
            @if(no_telepon_toko())
                <span>{{ no_telepon_toko() }}</span>
            @endif
        </div>
    </div>
</header>
```

### **2. Footer**

```blade
<footer class="bg-gray-800 text-white p-6">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="font-semibold mb-2">{{ nama_toko() }}</h3>
                @if(deskripsi_toko())
                    <p class="text-sm text-gray-300">{{ deskripsi_toko() }}</p>
                @endif
            </div>
            <div>
                <h4 class="font-semibold mb-2">Kontak</h4>
                @if(no_telepon_toko())
                    <p class="text-sm">{{ no_telepon_toko() }}</p>
                @endif
                @if(email_toko())
                    <p class="text-sm">{{ email_toko() }}</p>
                @endif
            </div>
            <div>
                <h4 class="font-semibold mb-2">Alamat</h4>
                @if(alamat_toko())
                    <p class="text-sm text-gray-300">{{ alamat_toko() }}</p>
                @endif
            </div>
        </div>
    </div>
</footer>
```

### **3. Invoice/Receipt**

```blade
<div class="invoice-header">
    <div class="flex items-center space-x-4">
        @if(has_logo_toko())
            <img src="{{ logo_toko() }}" alt="{{ nama_toko() }}" class="h-16 w-16">
        @endif
        <div>
            <h2 class="text-2xl font-bold">{{ nama_toko() }}</h2>
            @if(alamat_toko())
                <p class="text-sm">{{ alamat_toko() }}</p>
            @endif
            @if(no_telepon_toko())
                <p class="text-sm">Tel: {{ no_telepon_toko() }}</p>
            @endif
        </div>
    </div>
</div>
```

### **4. Email Template**

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Email dari {{ nama_toko() }}</title>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="text-align: center; padding: 20px;">
            @if(has_logo_toko())
                <img src="{{ logo_toko() }}" alt="{{ nama_toko() }}" style="height: 60px;">
            @endif
            <h1 style="margin: 10px 0;">{{ nama_toko() }}</h1>
        </div>

        <div style="padding: 20px;">
            <!-- Email content -->
        </div>

        <div style="background: #f5f5f5; padding: 20px; text-align: center; font-size: 12px;">
            <p>{{ nama_toko() }}</p>
            @if(alamat_toko())
                <p>{{ alamat_toko() }}</p>
            @endif
            @if(no_telepon_toko())
                <p>Tel: {{ no_telepon_toko() }}</p>
            @endif
        </div>
    </div>
</body>
</html>
```

## ⚡ Performa & Cache

### **Cache System**

-   Data pengaturan di-cache selama **1 jam** (3600 detik)
-   Cache otomatis di-clear saat data diupdate
-   Fallback ke database jika cache tidak ada

### **Clear Cache Manual**

```php
// Di controller atau command
clear_pengaturan_cache();
```

### **Monitor Cache**

```php
// Cek apakah data ada di cache
if (Cache::has('pengaturan_umum_aktif')) {
    echo "Data ada di cache";
} else {
    echo "Data akan diambil dari database";
}
```

## 🔧 Konfigurasi

### **Service Provider**

File: `app/Providers/PengaturanUmumServiceProvider.php`

-   Inject data ke semua view (`*`)
-   Cache data selama 1 jam
-   Fallback data jika tidak ada pengaturan

### **Helper Functions**

File: `app/helpers.php`

-   Global functions untuk akses mudah
-   Menggunakan helper class untuk logic
-   Auto-loaded via composer

### **Cache Key**

-   Key: `pengaturan_umum_aktif`
-   TTL: 3600 detik (1 jam)
-   Driver: Default Laravel cache driver

## 🐛 Troubleshooting

### **Problem: Data tidak muncul**

```php
// 1. Cek apakah service provider terdaftar
// File: config/app.php
'providers' => [
    // ...
    App\Providers\PengaturanUmumServiceProvider::class,
]

// 2. Clear cache
clear_pengaturan_cache();

// 3. Cek apakah ada data di database
php artisan tinker
>>> App\Models\PengaturanUmum::getActive()
```

### **Problem: Helper functions tidak ditemukan**

```bash
# Regenerate autoload files
composer dump-autoload

# Pastikan file helpers.php terdaftar di composer.json
```

### **Problem: Cache tidak update**

```php
// Clear cache manual setelah update
PengaturanUmumHelper::clearCache();
// atau
clear_pengaturan_cache();
```

## 📝 Best Practices

### **1. Conditional Display**

```blade
@if($pengaturanUmum->no_telepon)
    <span>{{ $pengaturanUmum->no_telepon }}</span>
@endif
```

### **2. Fallback Values**

```blade
{{ $pengaturanUmum->nama_toko ?? 'Toko Saya' }}
```

### **3. Null Safety**

```blade
@if(has_logo_toko())
    <img src="{{ logo_toko() }}" alt="{{ nama_toko() }}">
@else
    <div class="placeholder-logo">{{ substr(nama_toko(), 0, 1) }}</div>
@endif
```

### **4. Performance**

-   Data sudah di-cache, jadi aman untuk digunakan di loop
-   Tidak perlu khawatir dengan N+1 query problem
-   Cache otomatis di-clear saat data berubah

## 🎉 Keuntungan

1. **DRY Principle** - Tidak perlu pass data dari setiap controller
2. **Consistency** - Data sama di semua halaman
3. **Performance** - Cache system untuk performa optimal
4. **Maintainability** - Mudah update data di satu tempat
5. **Fallback Safety** - Data default jika belum ada pengaturan
6. **Developer Experience** - Helper functions yang mudah digunakan

---

**Note**: Data pengaturan umum sekarang tersedia di semua view tanpa perlu setup tambahan! 🚀
