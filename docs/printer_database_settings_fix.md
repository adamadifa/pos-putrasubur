# Printer Database Settings Fix

## Masalah

Error: `Cannot find printer with name "EPSON TM-T20III Receipt"` saat mencetak struk pembelian.

## Penyebab

Di `resources/views/pembelian/show.blade.php`, sistem masih menggunakan hardcoded printer name dan localStorage alih-alih menggunakan pengaturan printer yang tersimpan di database.

## Perbaikan

### 1. Mengganti Hardcoded Printer Name

**File**: `resources/views/pembelian/show.blade.php`

**Sebelum**:

```javascript
const printerName =
    localStorage.getItem("selectedPrinter") || "EPSON TM-T20III Receipt";
```

**Sesudah**:

```javascript
// Get default printer from database
fetch('{{ route('printer.get-settings') }}')
    .then(response => response.json())
    .then(data => {
        if (data && data.success && data.settings && data.settings.default_printer) {
            printInvoice(data.settings.default_printer);
        } else {
            // Fallback to first available printer
            qz.printers.find().then(function(printers) {
                if (printers && printers.length > 0) {
                    printInvoice(printers[0]);
                }
            });
        }
    });
```

### 2. Menambahkan Fungsi yang Hilang

#### A. Fungsi `printInvoice(printerName)`

```javascript
function printInvoice(printerName) {
    const invoiceData = generateInvoiceData();
    const config = qz.configs.create(printerName);

    qz.print(config, invoiceData)
        .then(function () {
            console.log(`Invoice printed to ${printerName}`);
            updatePrinterInfo(printerName);
            showPrintSuccess("✅ Invoice berhasil dicetak ke " + printerName);
            resetPrintButton();
        })
        .catch(function (err) {
            console.log("Invoice printing failed:", err);
            showPrintError("⚠️ Cetak invoice gagal: " + err.message);
            resetPrintButton();
        });
}
```

#### B. Fungsi `updatePrinterInfo(printerName)`

```javascript
function updatePrinterInfo(printerName) {
    const printerInfo = document.getElementById("printerInfo");
    if (printerInfo) {
        printerInfo.textContent = `Printer: ${printerName}`;
    }
}
```

### 3. Konsistensi dengan Penjualan

Sekarang implementasi di pembelian konsisten dengan penjualan:

-   Kedua menggunakan database settings
-   Kedua memiliki fallback ke auto-detect printer
-   Kedua memiliki error handling yang sama
-   Kedua memiliki fungsi `updatePrinterInfo`

## Flow Baru

1. **Connect QZ Tray** - Terhubung ke QZ Tray
2. **Fetch Database Settings** - Ambil pengaturan printer dari database
3. **Use Default Printer** - Gunakan printer default jika tersedia
4. **Fallback to Auto-detect** - Jika tidak ada default, gunakan printer pertama yang tersedia
5. **Print Invoice** - Cetak menggunakan printer yang dipilih
6. **Update UI** - Update informasi printer di UI

## Testing

1. Test dengan printer default yang sudah diset di database
2. Test dengan tidak ada printer default (fallback ke auto-detect)
3. Test dengan tidak ada printer tersedia (error handling)
4. Test dengan database settings tidak tersedia (fallback)

## Catatan

-   Sistem sekarang menggunakan pengaturan printer yang tersimpan di database
-   Tidak lagi bergantung pada localStorage atau hardcoded printer name
-   Konsisten dengan implementasi di modul penjualan
