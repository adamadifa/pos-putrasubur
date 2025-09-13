# QZ Tray Connection Fix

## Masalah

Error: `An open connection with QZ Tray already exists` saat mencetak struk tanpa refresh halaman terlebih dahulu.

## Penyebab

Setiap kali fungsi print dipanggil, kode selalu mencoba membuat koneksi baru dengan `qz.websocket.connect()` tanpa mengecek apakah sudah ada koneksi yang aktif dari session sebelumnya.

## Perbaikan

### 1. Pengecekan Status Koneksi

**Sebelum**:

```javascript
// Selalu mencoba connect baru
qz.websocket
    .connect({
        retries: 2,
        delay: 1,
    })
    .then(function () {
        // print logic
    })
    .catch(function (err) {
        // error handling
    });
```

**Sesudah**:

```javascript
// Global flag untuk mencegah multiple connection attempts
let qzConnecting = false;

// Cek apakah sedang connecting dan QZ Tray tersedia
if (qzConnecting) {
    console.log("QZ Tray connection already in progress, skipping...");
    return;
}

if (qz.websocket && qz.websocket.connect) {
    console.log("QZ Tray available, attempting connection...");
    qzConnecting = true;

    qz.websocket
        .connect({
            retries: 2,
            delay: 1,
        })
        .then(function () {
            qzConnecting = false;
            connectAndPrint();
        })
        .catch(function (err) {
            qzConnecting = false;
            console.log("QZ Tray connection failed:", err);
            showPrintError("Koneksi QZ Tray gagal: " + err.message);
            resetPrintButton();
        });
} else {
    console.log("QZ Tray not available");
    showPrintError("QZ Tray tidak tersedia");
    resetPrintButton();
}
```

### 2. Refactor Logic ke Function Terpisah

```javascript
const connectAndPrint = function() {
    console.log('QZ Tray connected for invoice printing');

    // Get default printer from database
    fetch('{{ route('printer.get-settings') }}')
        .then(response => response.json())
        .then(data => {
            if (data && data.success && data.settings && data.settings.default_printer) {
                printInvoice(data.settings.default_printer);
            } else {
                // Fallback to auto-detect
                qz.printers.find().then(function(printers) {
                    if (printers && printers.length > 0) {
                        printInvoice(printers[0]);
                    } else {
                        showPrintError('Tidak ada printer yang tersedia');
                        resetPrintButton();
                    }
                });
            }
        })
        .catch(error => {
            // Error handling with fallback
        });
};
```

### 3. Perbaikan di Dua Fungsi

-   **`printInvoiceWithQZTray()`** - Manual print button
-   **`autoPrintReceipt()`** - Auto print after transaction

### 4. Error Handling yang Lebih Baik

-   Added `resetPrintButton()` calls pada error scenarios
-   Better fallback handling ketika printer tidak ditemukan
-   More descriptive error messages

### 5. Connection Cleanup

-   **Auto Disconnect**: Koneksi ditutup otomatis setelah print selesai
-   **Timeout Delay**: Menunggu 1 detik sebelum disconnect untuk memastikan print selesai
-   **Error Cleanup**: Koneksi juga ditutup jika terjadi error

```javascript
// Close connection after successful print
setTimeout(function () {
    if (qz.websocket && qz.websocket.disconnect) {
        console.log("Closing QZ Tray connection after print");
        qz.websocket.disconnect();
    }
}, 1000); // Wait 1 second before closing
```

## Manfaat

### Performance

-   **Clean Connections**: Koneksi bersih untuk setiap print
-   **No Connection Conflicts**: Tidak ada konflik koneksi yang sudah ada
-   **Better UX**: Tidak perlu refresh halaman

### Reliability

-   **Connection Cleanup**: Koneksi ditutup setelah setiap print
-   **Fallback Handling**: Auto-detect printer jika database settings gagal
-   **Error Recovery**: Proper error handling dan connection cleanup

### User Experience

-   **No Refresh Required**: Print berfungsi tanpa refresh
-   **Consistent Behavior**: Print button behavior yang konsisten
-   **Clear Feedback**: Error messages yang jelas

## Testing

1. **Multiple Prints** - Test print berulang tanpa refresh
2. **Connection Persistence** - Test koneksi tetap aktif antar print
3. **Error Scenarios** - Test ketika printer tidak tersedia
4. **Auto Print** - Test auto print setelah transaksi
5. **Manual Print** - Test manual print button

## Flow Baru

1. **Check Flag** → Cek apakah sedang connecting (`qzConnecting`)
2. **Check Availability** → Cek apakah QZ Tray tersedia (`qz.websocket.connect`)
3. **Set Flag** → Set `qzConnecting = true`
4. **Connect** → Attempt connection dengan retry
5. **Print** → Execute print logic
6. **Reset Flag** → Set `qzConnecting = false` setelah success/error
7. **Cleanup** → Close connection setelah 1 detik (success/error)

## Catatan

-   QZ Tray connection bersifat persistent dalam session browser
-   `qz.websocket.isConnected()` tidak tersedia di semua versi QZ Tray
-   Menggunakan global flag `qzConnecting` untuk mencegah multiple connection attempts
-   QZ Tray akan menangani koneksi yang sudah ada secara otomatis
-   Connection reuse mengurangi overhead dan meningkatkan performance
