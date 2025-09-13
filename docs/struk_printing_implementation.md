# Implementasi Cetak Struk dengan Data Pengaturan Umum

## 📋 Overview

Sistem cetak struk telah diimplementasikan untuk **penjualan** dan **pembelian** dengan menggunakan data dari pengaturan umum. Fitur ini mendukung:

1. **QZ Tray Printing** - Cetak langsung ke printer thermal
2. **Browser Printing** - Cetak via browser (PDF/Print Preview)
3. **Dynamic Data** - Menggunakan data pengaturan umum (nama toko, alamat, dll)

## 🚀 Fitur yang Diimplementasikan

### **1. ✅ Template Struk Penjualan**

-   **File**: `resources/views/penjualan/show.blade.php`
-   **QZ Tray**: Template dengan ESC/POS commands
-   **Browser Print**: Template HTML dengan styling khusus
-   **Data Source**: Menggunakan `$pengaturanUmum` dari Service Provider

### **2. ✅ Template Struk Pembelian**

-   **File**: `resources/views/pembelian/show.blade.php`
-   **QZ Tray**: Template dengan ESC/POS commands
-   **Browser Print**: Template HTML dengan styling khusus
-   **Data Source**: Menggunakan `$pengaturanUmum` dari Service Provider

## 🎯 Template Struk Format

### **Header Section**

```
================================
        NAMA TOKO
        Deskripsi Toko (jika ada)
        Alamat Lengkap (jika ada)
        Telp: 081234567890 (jika ada)
        Email: info@toko.com (jika ada)
================================
```

### **Invoice Info Section**

```
PEMBELIAN (atau PENJUALAN)
No. Faktur: INV-20250113-0001
Tanggal: 13/01/2025 14:30
Supplier: Nama Supplier (atau Pelanggan)
Kasir: Nama Kasir
================================
```

### **Items Section**

```
Nama Produk
  2 pcs x 15.000 = 30.000
Nama Produk Lain
  1 kg x 25.000 = 25.000
================================
```

### **Totals Section**

```
                        Subtotal: 55.000
                        Diskon: -5.000
                        PPN: 5.000
                        TOTAL: 55.000
```

### **Payment Section**

```
PEMBAYARAN:
Tunai: 30.000
Transfer: 25.000
--------------------------------
                Total Bayar: 55.000
                    Sisa: 0
```

### **Footer Section**

```
================================
    Terima kasih atas pembelian Anda!
            13/01/2025 14:30:45
```

## 🔧 Technical Implementation

### **QZ Tray Printing**

#### **ESC/POS Commands Used:**

```javascript
// Initialize printer
invoiceLines.push("\x1B\x40");

// Center align
invoiceLines.push("\x1B\x61\x01");

// Left align
invoiceLines.push("\x1B\x61\x00");

// Right align
invoiceLines.push("\x1B\x61\x02");
```

#### **Print Flow:**

1. **Button Click** → `printInvoiceWithQZTray(event)`
2. **Check QZ Tray** → Load script if not available
3. **Generate Data** → `generateInvoiceData()`
4. **Connect Printer** → `qz.websocket.connect()`
5. **Print** → `qz.print(config, data)`
6. **Feedback** → Success/Error toast

### **Browser Printing**

#### **CSS Media Query:**

```css
@media print {
    body * {
        visibility: hidden;
    }

    .print-section,
    .print-section * {
        visibility: visible;
    }

    .print-section {
        position: absolute;
        left: 0;
        top: 0;
    }
}
```

#### **Print Flow:**

1. **Button Click** → `printInvoiceRaw()`
2. **Browser Print** → `window.print()`
3. **CSS Filter** → Only `.print-section` visible
4. **User Action** → Print/Save as PDF

## 📊 Data Integration

### **Pengaturan Umum Integration**

#### **Header Data:**

```blade
{{ $pengaturanUmum->nama_toko }}
@if($pengaturanUmum->deskripsi)
    {{ $pengaturanUmum->deskripsi }}
@endif
@if($pengaturanUmum->alamat)
    {{ $pengaturanUmum->alamat }}
@endif
@if($pengaturanUmum->no_telepon)
    Telp: {{ $pengaturanUmum->no_telepon }}
@endif
@if($pengaturanUmum->email)
    Email: {{ $pengaturanUmum->email }}
@endif
```

#### **Conditional Display:**

-   **Nama Toko**: Selalu ditampilkan (fallback: "Toko Saya")
-   **Deskripsi**: Ditampilkan jika ada
-   **Alamat**: Ditampilkan jika ada
-   **Telepon**: Ditampilkan jika ada (format: "Telp: xxx")
-   **Email**: Ditampilkan jika ada (format: "Email: xxx")

### **Transaction Data**

#### **Penjualan:**

-   No. Faktur
-   Tanggal & Waktu
-   Pelanggan
-   Kasir
-   Items dengan detail
-   Subtotal, Diskon, PPN, Total
-   Pembayaran (jika ada)
-   Sisa (jika belum lunas)

#### **Pembelian:**

-   No. Faktur
-   Tanggal & Waktu
-   Supplier
-   Kasir
-   Items dengan detail
-   Subtotal, Diskon, PPN, Total
-   Pembayaran (jika ada)
-   Sisa (jika belum lunas)

## 🎨 Styling & Layout

### **QZ Tray (Thermal Printer)**

-   **Font**: Monospace (Courier New)
-   **Width**: 48 characters
-   **Alignment**: ESC/POS commands
-   **Formatting**: Plain text with separators

### **Browser Print**

-   **Font**: 'Courier New', monospace
-   **Size**: 12px
-   **Width**: 300px max
-   **Layout**: Centered, structured sections
-   **Styling**: Inline CSS untuk konsistensi print

## 🔄 Error Handling

### **QZ Tray Errors:**

```javascript
.catch(error => {
    console.error('Print failed:', error);
    showPrintError('Gagal mencetak: ' + error.message);
    resetPrintButton(button, buttonText);
});
```

### **Toast Notifications:**

-   **Success**: Green toast dengan icon check
-   **Error**: Red toast dengan icon X
-   **Auto-dismiss**: 3 detik
-   **Animation**: Slide in/out

### **Button States:**

-   **Loading**: Disabled dengan spinner
-   **Error**: Reset ke state normal
-   **Success**: Reset ke state normal

## 📝 Usage Examples

### **1. Cetak Struk Penjualan**

```javascript
// QZ Tray
<button onclick="printInvoiceWithQZTray(event)">
    <i class="ti ti-printer"></i>Cetak Struk
</button>

// Browser Print
<button onclick="printInvoiceRaw()">
    <i class="ti ti-printer"></i>Print Preview
</button>
```

### **2. Cetak Struk Pembelian**

```javascript
// Sama seperti penjualan, template disesuaikan
<button onclick="printInvoiceWithQZTray(event)">
    <i class="ti ti-printer"></i>Cetak Struk
</button>
```

## 🚀 Benefits

### **1. Dynamic Branding**

-   Logo, nama toko, alamat otomatis dari pengaturan
-   Konsisten di semua struk
-   Mudah update di satu tempat

### **2. Dual Print Support**

-   Thermal printer via QZ Tray
-   Browser print untuk PDF/backup
-   Fallback mechanism

### **3. Professional Layout**

-   Structured sections
-   Proper alignment
-   Clear separators
-   Complete transaction info

### **4. Error Handling**

-   Graceful failure handling
-   User feedback via toast
-   Button state management

## 🔧 Configuration

### **Printer Settings**

```javascript
// Default printer name
const printerName =
    localStorage.getItem("selectedPrinter") || "EPSON TM-T20III Receipt";
```

### **Print Preferences**

-   **Paper Size**: 80mm thermal paper
-   **Character Set**: ASCII/UTF-8
-   **Font**: Monospace
-   **Alignment**: ESC/POS compatible

## 📋 Testing Checklist

### **✅ QZ Tray Testing**

-   [ ] Printer connection
-   [ ] ESC/POS commands
-   [ ] Data formatting
-   [ ] Error handling
-   [ ] Success feedback

### **✅ Browser Print Testing**

-   [ ] Print preview
-   [ ] PDF generation
-   [ ] Layout consistency
-   [ ] Media query CSS
-   [ ] Print dialog

### **✅ Data Integration Testing**

-   [ ] Pengaturan umum data
-   [ ] Conditional fields
-   [ ] Transaction data
-   [ ] Number formatting
-   [ ] Date formatting

## 🎉 Conclusion

Sistem cetak struk telah berhasil diimplementasikan dengan:

1. **✅ Penjualan**: Template lengkap dengan QZ Tray + Browser print
2. **✅ Pembelian**: Template lengkap dengan QZ Tray + Browser print
3. **✅ Data Integration**: Menggunakan pengaturan umum secara dinamis
4. **✅ Error Handling**: Graceful failure dengan user feedback
5. **✅ Professional Layout**: Struktur yang rapi dan informatif

**Struk sekarang menggunakan data pengaturan umum dan siap digunakan! 🚀**
