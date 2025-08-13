# QZ Tray Certificate Setup Guide

## Mengatasi "Untrusted Website" Warning di QZ Tray

Ketika menggunakan QZ Tray, Anda mungkin akan melihat peringatan "Untrusted Website" seperti yang ditunjukkan dalam gambar. Berikut adalah panduan lengkap untuk mengatasi masalah ini.

## Metode 1: Automatic Certificate Generation (Recommended)

### Langkah-langkah:

1. **Buka Halaman Pengaturan Printer**

    - Akses menu "Pengaturan Printer" di sidebar aplikasi
    - Atau kunjungi `/printer/settings`

2. **Generate Certificate**

    - Klik tombol "Generate Certificate" (tombol orange)
    - Tunggu hingga proses selesai
    - Lihat log messages untuk konfirmasi

3. **Restart QZ Tray**

    - Tutup QZ Tray dari system tray
    - Buka kembali QZ Tray
    - Atau restart service QZ Tray

4. **Test Koneksi**
    - Refresh halaman pengaturan printer
    - Klik "Connect to QZ Tray"
    - Peringatan "Untrusted Website" seharusnya sudah tidak muncul

## Metode 2: Manual Certificate Setup

### Untuk Windows:

1. **Generate Certificate menggunakan OpenSSL**

    ```bash
    openssl req -newkey rsa:2048 -nodes -keyout private-key.pem -x509 -days 365 -out certificate.pem
    ```

2. **Copy Certificate ke QZ Tray Directory**

    ```bash
    copy certificate.pem "C:\Program Files\QZ Tray\override.crt"
    ```

3. **Restart QZ Tray Service**

### Untuk Linux:

1. **Generate Certificate**

    ```bash
    openssl req -newkey rsa:2048 -nodes -keyout private-key.pem -x509 -days 365 -out certificate.pem
    ```

2. **Copy Certificate**

    ```bash
    sudo cp certificate.pem /opt/qz-tray/override.crt
    ```

3. **Restart QZ Tray**
    ```bash
    sudo systemctl restart qz-tray
    ```

### Untuk macOS:

1. **Generate Certificate**

    ```bash
    openssl req -newkey rsa:2048 -nodes -keyout private-key.pem -x509 -days 365 -out certificate.pem
    ```

2. **Copy Certificate**

    ```bash
    cp certificate.pem "/Applications/QZ Tray.app/Contents/Resources/override.crt"
    ```

3. **Restart QZ Tray**

## Metode 3: Development Mode (Temporary)

Jika Anda hanya ingin testing sementara tanpa certificate:

1. **Klik "Allow" pada dialog**
2. **Centang "Remember this decision"**
3. **Klik "Allow" sekali lagi**

**Catatan:** Metode ini tidak disarankan untuk production.

## Troubleshooting

### 1. Certificate tidak terbaca

-   Pastikan file `override.crt` ada di direktori QZ Tray
-   Periksa permissions file (harus readable)
-   Restart QZ Tray setelah copy certificate

### 2. Masih muncul warning setelah setup certificate

-   Hapus cache browser
-   Restart browser
-   Periksa console browser untuk error messages

### 3. QZ Tray tidak bisa connect

-   Pastikan QZ Tray service berjalan
-   Periksa firewall settings
-   Test dengan URL: `https://demo.qz.io`

### 4. Certificate expired

-   Generate certificate baru
-   Update `override.crt` di QZ Tray directory
-   Restart QZ Tray

## File Locations

### Certificate Storage (Automatic Generation):

-   **Laravel Storage:** `storage/app/qz-tray/`
    -   `certificate.pem` - Public certificate
    -   `private-key.pem` - Private key

### QZ Tray Override Certificate:

-   **Windows:** `C:\Program Files\QZ Tray\override.crt`
-   **Linux:** `/opt/qz-tray/override.crt`
-   **macOS:** `/Applications/QZ Tray.app/Contents/Resources/override.crt`

## API Endpoints

### Certificate Management:

-   `GET /qz-certificate` - Get public certificate
-   `POST /qz-sign` - Sign request with private key
-   `POST /generate-qz-certificate` - Generate new certificate pair

## Security Notes

1. **Private Key Security**

    - Private key disimpan di `storage/app/qz-tray/`
    - Pastikan directory tidak accessible via web
    - Backup private key secara aman

2. **Certificate Validity**

    - Default certificate valid selama 365 hari
    - Monitor expiry date
    - Auto-renewal bisa diimplementasikan

3. **Production Considerations**
    - Gunakan proper SSL certificate untuk production
    - Consider menggunakan certificate authority yang trusted
    - Implement proper key management

## Testing

Setelah setup certificate, test dengan:

1. **Connection Test**

    - Buka halaman pengaturan printer
    - Klik "Connect to QZ Tray"
    - Tidak ada warning dialog

2. **Print Test**

    - Pilih printer
    - Klik "Test Print"
    - Dokumen terprint tanpa error

3. **Browser Console**
    - Buka Developer Tools
    - Tidak ada SSL/Certificate errors
    - QZ Tray connection successful

## Support

Jika masih mengalami masalah:

1. Periksa log messages di halaman pengaturan printer
2. Lihat Laravel logs: `storage/logs/laravel.log`
3. Periksa QZ Tray logs
4. Contact support team

