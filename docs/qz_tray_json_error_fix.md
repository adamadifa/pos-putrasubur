# QZ Tray JSON Error Fix

## Masalah

Error: `JSONObject["data"] is not a JSONArray` saat mencetak struk dengan QZ Tray.

## Penyebab

Di `resources/views/pembelian/show.blade.php`, fungsi `generateInvoiceData()` mengembalikan `invoiceLines.join('')` (string), sedangkan QZ Tray mengharapkan array untuk parameter `data`.

## Perbaikan

### 1. Perbaikan Return Type di generateInvoiceData()

**File**: `resources/views/pembelian/show.blade.php`

**Sebelum**:

```javascript
return invoiceLines.join("");
```

**Sesudah**:

```javascript
return invoiceLines;
```

### 2. Tambahan Cut Paper Command

Ditambahkan command untuk memotong kertas di akhir:

```javascript
invoiceLines.push("\x1D\x56\x42\x00"); // Cut paper
```

### 3. Error Handling yang Lebih Baik

Ditambahkan error handling untuk response JSON:

```javascript
.then(response => {
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    return response.json();
})
.then(data => {
    console.log('Print response:', data);
    if (data && data.success) {
        // handle success
    }
})
```

## Konsistensi dengan Penjualan

Sekarang implementasi di pembelian konsisten dengan penjualan:

-   Kedua menggunakan array untuk QZ Tray
-   Kedua memiliki error handling yang sama
-   Kedua memiliki cut paper command

## Testing

1. Test cetak struk pembelian dengan QZ Tray
2. Test cetak struk penjualan dengan QZ Tray
3. Test error handling ketika printer tidak tersedia
4. Test browser print sebagai fallback

## Catatan

QZ Tray mengharapkan data dalam format array untuk thermal printer commands. String concatenation (`join('')`) tidak kompatibel dengan QZ Tray API.
