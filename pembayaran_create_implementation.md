# Implementasi Form Pembayaran Create dengan Pilihan Kas/Bank

## Overview
Form pembayaran create telah diperbarui untuk menambahkan pilihan kas/bank yang mirip dengan preview pesanan di halaman penjualan. Fitur ini memungkinkan user untuk memilih kas/bank yang akan digunakan untuk pembayaran berdasarkan metode pembayaran yang dipilih.

## Fitur yang Ditambahkan

### 1. Pilihan Kas/Bank
- **Tampilan**: Card-based selection dengan gambar dan saldo
- **Kondisi**: Muncul hanya ketika metode pembayaran yang dipilih membutuhkan kas/bank (tunai, transfer, qris)
- **Validasi**: Wajib dipilih untuk metode pembayaran yang membutuhkan kas/bank

### 2. Metode Pembayaran yang Mendukung Kas/Bank
- **Tunai**: Memerlukan pilihan kas/bank
- **Transfer**: Memerlukan pilihan kas/bank  
- **QRIS**: Memerlukan pilihan kas/bank
- **Lainnya**: Tidak memerlukan pilihan kas/bank

### 3. Validasi Dinamis
- **Frontend**: JavaScript validation yang menyesuaikan dengan metode pembayaran
- **Backend**: Validasi server-side yang memastikan kas/bank dipilih sesuai metode pembayaran

## Struktur Kode

### View (`resources/views/pembayaran/create.blade.php`)

#### HTML Structure
```html
<!-- Metode Pembayaran -->
<div class="space-y-2">
    <label class="block text-sm font-semibold text-gray-700">
        Metode Pembayaran <span class="text-red-500">*</span>
    </label>
    <div class="grid grid-cols-2 gap-3">
        @foreach ($metodePembayaran as $metode)
            <!-- Payment method cards -->
        @endforeach
    </div>
</div>

<!-- Kas & Bank Selection -->
<div id="kas-bank-selection" class="space-y-4 hidden">
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-gray-700">
            Pilih Kas/Bank <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($kasBanks as $kasBank)
                <!-- Kas/Bank cards -->
            @endforeach
        </div>
    </div>
</div>
```

#### JavaScript Functionality
```javascript
// Initialize payment method selection
function initializePaymentMethod() {
    const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');
    const kasBankSelection = document.getElementById('kas-bank-selection');

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Show/hide kas/bank selection based on payment method
            const selectedMethod = this.value;
            if (selectedMethod === 'tunai' || selectedMethod === 'transfer' || selectedMethod === 'qris') {
                kasBankSelection.classList.remove('hidden');
                // Add kas_bank_id to validation rules
                if (!validationRules.kas_bank_id) {
                    validationRules.kas_bank_id = { required: true };
                    validationMessages.kas_bank_id = { required: 'Kas/Bank wajib dipilih.' };
                }
            } else {
                kasBankSelection.classList.add('hidden');
                // Remove kas_bank_id from validation rules
                delete validationRules.kas_bank_id;
                delete validationMessages.kas_bank_id;
            }
        });
    });
}

// Initialize kas/bank selection
function initializeKasBankSelection() {
    const kasBankRadios = document.querySelectorAll('.kas-bank-radio');

    kasBankRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update styling and validation
        });
    });
}
```

### Controller (`app/Http/Controllers/PembayaranController.php`)

#### Create Method
```php
public function create(): View
{
    // Get penjualan that need payment
    $penjualan = Penjualan::with(['pelanggan', 'pembayaranPenjualan'])
        ->orderBy('tanggal', 'desc')
        ->get()
        ->filter(function ($p) {
            $sudahDibayar = $p->pembayaranPenjualan->sum('jumlah_bayar');
            $sisaBayar = $p->total - $sudahDibayar;
            return $sisaBayar > 0;
        })
        ->values();

    // Get active payment methods
    $metodePembayaran = \App\Models\MetodePembayaran::where('status', true)
        ->orderBy('nama')
        ->get();

    // Get kas/bank data
    $kasBanks = \App\Models\KasBank::where('status', true)
        ->orderBy('nama')
        ->get();

    return view('pembayaran.create', compact('penjualan', 'metodePembayaran', 'kasBanks'));
}
```

#### Store Method Validation
```php
// Validate kas_bank_id based on payment method
if (in_array($validated['metode_pembayaran'], ['tunai', 'transfer', 'qris'])) {
    if (empty($validated['kas_bank_id'])) {
        return back()->withInput()
            ->with('error', 'Kas/Bank wajib dipilih untuk metode pembayaran ' . $metodePembayaran->nama . '.');
    }

    // Validate kas_bank exists and is active
    $kasBank = \App\Models\KasBank::where('id', $validated['kas_bank_id'])
        ->where('status', true)
        ->first();

    if (!$kasBank) {
        return back()->withInput()
            ->with('error', 'Kas/Bank yang dipilih tidak valid atau tidak aktif.');
    }
} else {
    // For other payment methods, set kas_bank_id to null
    $validated['kas_bank_id'] = null;
}
```

## CSS Styling

### Payment Method Cards
```css
.payment-method-card {
    transition: all 0.2s ease-in-out;
}

.payment-method-card:hover {
    transform: translateY(-2px);
}

.payment-method-radio:checked+.payment-method-card {
    border-color: #3b82f6;
    background-color: #eff6ff;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}
```

### Kas/Bank Cards
```css
.kas-bank-card {
    transition: all 0.2s ease-in-out;
}

.kas-bank-card:hover {
    transform: translateY(-2px);
}

.kas-bank-radio:checked+.kas-bank-card {
    border-color: #3b82f6;
    background-color: #eff6ff;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}
```

## Validasi

### Frontend Validation
- **Real-time**: Validasi saat user memilih opsi
- **Dynamic**: Menyesuaikan dengan metode pembayaran yang dipilih
- **Visual**: Feedback visual dengan border colors

### Backend Validation
- **Required**: Kas/bank wajib untuk metode tertentu
- **Exists**: Memastikan kas/bank yang dipilih valid
- **Active**: Memastikan kas/bank masih aktif

## Database Integration

### Model Relationships
- `PembayaranPenjualan` belongs to `KasBank`
- `KasBank` has many `PembayaranPenjualan`

### Trigger System
- Otomatis update saldo kas/bank saat pembayaran dibuat
- Otomatis hapus transaksi kas/bank saat pembayaran dihapus

## User Experience

### Flow Penggunaan
1. User memilih transaksi yang belum lunas
2. User mengisi jumlah pembayaran
3. User memilih metode pembayaran
4. Jika metode membutuhkan kas/bank, pilihan kas/bank muncul
5. User memilih kas/bank yang akan digunakan
6. User mengisi keterangan (opsional)
7. User submit form

### Visual Feedback
- **Hover effects**: Cards bergerak ke atas saat di-hover
- **Selection states**: Border dan background berubah saat dipilih
- **Error states**: Border merah saat ada error
- **Success states**: Border hijau saat valid

## Testing

### Test Cases
1. **Valid Payment**: Pembayaran dengan kas/bank yang valid
2. **Invalid Kas/Bank**: Pembayaran dengan kas/bank yang tidak valid
3. **No Kas/Bank**: Pembayaran tanpa kas/bank untuk metode yang membutuhkannya
4. **Different Methods**: Test semua metode pembayaran

### Expected Results
- Saldo kas/bank terupdate otomatis
- Transaksi kas/bank tercatat
- Validasi berfungsi dengan benar
- UI responsive dan user-friendly

## Kesimpulan

Form pembayaran create telah berhasil diperbarui dengan fitur pilihan kas/bank yang terintegrasi dengan baik. Fitur ini memberikan user experience yang konsisten dengan halaman penjualan dan memastikan data integrity melalui validasi yang komprehensif.
