<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembayaranPembelianController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PrinterSettingController;
use App\Http\Controllers\KasBankController;
use App\Http\Controllers\TransaksiKasBankController;
use App\Http\Controllers\SaldoAwalBulananController;
use App\Http\Controllers\SaldoAwalProdukController;
use App\Http\Controllers\LaporanKasBankController;
use App\Http\Controllers\LaporanStokController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaksi Penjualan Routes
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/create', [PenjualanController::class, 'create'])->name('create');
        Route::post('/', [PenjualanController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [PenjualanController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [PenjualanController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [PenjualanController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [PenjualanController::class, 'destroy'])->name('destroy');
    });

    // Penjualan API Routes (inside auth middleware but outside group to avoid parameter conflicts)
    Route::get('penjualan/search-products', [PenjualanController::class, 'searchProducts'])->name('penjualan.search-products');
    Route::get('penjualan/product/{id}', [PenjualanController::class, 'getProduct'])->name('penjualan.get-product');
    Route::get('penjualan/pending-receipt', [PenjualanController::class, 'getPendingReceipt'])->name('penjualan.pending-receipt');

    // Pembayaran Routes
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/create', [PembayaranController::class, 'create'])->name('create');
        Route::post('/', [PembayaranController::class, 'store'])->name('store');
        Route::get('/{id}', [PembayaranController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PembayaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PembayaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [PembayaranController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/detail', [PembayaranController::class, 'detail'])->name('detail');
        Route::get('/{id}/print', [PembayaranController::class, 'print'])->name('print');
    });

    // Master Data Produk Routes
    Route::resource('produk', ProdukController::class);


    // Kategori Produk Routes
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class)->except(['show']);

    // Satuan Routes
    Route::resource('satuan', \App\Http\Controllers\SatuanController::class)->except(['show']);

    // Metode Pembayaran Routes
    Route::prefix('metode-pembayaran')->name('metode-pembayaran.')->group(function () {
        Route::get('/', [MetodePembayaranController::class, 'index'])->name('index');
        Route::get('/create', [MetodePembayaranController::class, 'create'])->name('create');
        Route::post('/', [MetodePembayaranController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [MetodePembayaranController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [MetodePembayaranController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [MetodePembayaranController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [MetodePembayaranController::class, 'destroy'])->name('destroy');
    });
    Route::get('metode-pembayaran/search', [MetodePembayaranController::class, 'search'])->name('metode-pembayaran.search');

    // Pelanggan Routes
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::get('/create', [PelangganController::class, 'create'])->name('create');
        Route::post('/', [PelangganController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [PelangganController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [PelangganController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [PelangganController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [PelangganController::class, 'destroy'])->name('destroy');
    });
    Route::get('pelanggan/search', [PelangganController::class, 'search'])->name('pelanggan.search');

    // Supplier Routes
    Route::prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [SupplierController::class, 'destroy'])->name('destroy');
    });
    Route::get('supplier/search', [SupplierController::class, 'getSuppliers'])->name('supplier.search');

    // Kas & Bank Routes
    Route::resource('kas-bank', KasBankController::class);

    // Transaksi Kas & Bank Routes
    Route::resource('transaksi-kas-bank', TransaksiKasBankController::class);
    Route::resource('saldo-awal-bulanan', SaldoAwalBulananController::class)->except(['show', 'edit', 'update']);
    Route::resource('saldo-awal-produk', SaldoAwalProdukController::class)->except(['show', 'edit', 'update']);

    // Saldo Awal Bulanan API Routes
    Route::post('saldo-awal-bulanan/get-saldo-akhir', [SaldoAwalBulananController::class, 'getSaldoAkhirBulanSebelumnya'])->name('saldo-awal-bulanan.get-saldo-akhir');

    // Saldo Awal Produk API Routes
    Route::post('saldo-awal-produk/get-all-produk', [SaldoAwalProdukController::class, 'getAllProduk'])->name('saldo-awal-produk.get-all-produk');
    Route::get('saldo-awal-produk/{saldoAwalProduk}/detail', [SaldoAwalProdukController::class, 'showDetail'])->name('saldo-awal-produk.detail');

    // Transaksi Pembelian Routes
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('index');
        Route::get('/create', [PembelianController::class, 'create'])->name('create');
        Route::post('/', [PembelianController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [PembelianController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [PembelianController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [PembelianController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [PembelianController::class, 'destroy'])->name('destroy');
    });
    Route::get('pembelian/search', [PembelianController::class, 'getPembelian'])->name('pembelian.search');

    // Pembayaran Pembelian Routes
    Route::prefix('pembayaran-pembelian')->name('pembayaran-pembelian.')->group(function () {
        Route::get('/', [PembayaranPembelianController::class, 'index'])->name('index');
        Route::get('/create', [PembayaranPembelianController::class, 'create'])->name('create');
        Route::post('/', [PembayaranPembelianController::class, 'store'])->name('store');
        Route::get('/{id}', [PembayaranPembelianController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PembayaranPembelianController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PembayaranPembelianController::class, 'update'])->name('update');
        Route::delete('/{id}', [PembayaranPembelianController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/detail', [PembayaranPembelianController::class, 'detail'])->name('detail');
        Route::get('/{id}/print', [PembayaranPembelianController::class, 'print'])->name('print');
    });

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/penjualan', function () {
            return view('laporan.penjualan');
        })->name('penjualan');
        Route::get('/produk', function () {
            return view('laporan.produk');
        })->name('produk');
        Route::get('/pembayaran', function () {
            return view('laporan.pembayaran');
        })->name('pembayaran');
        Route::get('/pembelian', function () {
            return view('laporan.pembelian');
        })->name('pembelian');
    });

    // Printer Settings Routes
    Route::prefix('printer')->name('printer.')->group(function () {
        Route::get('/settings', [PrinterSettingController::class, 'index'])->name('settings');
        Route::post('/test-print', [PrinterSettingController::class, 'testPrint'])->name('test-print');
        Route::get('/get-settings', [PrinterSettingController::class, 'getPrinterSettings'])->name('get-settings');

        // CRUD Routes for Printer Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::post('/', [PrinterSettingController::class, 'store'])->name('store');
            Route::get('/{printerSetting}', [PrinterSettingController::class, 'show'])->name('show');
            Route::put('/{printerSetting}', [PrinterSettingController::class, 'update'])->name('update');
            Route::delete('/{printerSetting}', [PrinterSettingController::class, 'destroy'])->name('destroy');
            Route::post('/{printerSetting}/set-default', [PrinterSettingController::class, 'setDefault'])->name('set-default');
        });
    });

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::prefix('kas-bank')->name('kas-bank.')->group(function () {
            Route::get('/', [LaporanKasBankController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanKasBankController::class, 'exportPdf'])->name('export-pdf');
        });

        Route::prefix('stok')->name('stok.')->group(function () {
            Route::get('/', [LaporanStokController::class, 'index'])->name('index');
            Route::post('/generate', [LaporanStokController::class, 'generateLaporan'])->name('generate');
            Route::post('/export-pdf', [LaporanStokController::class, 'exportPdf'])->name('export-pdf');
        });
    });
});

// QZ Tray Certificate Routes (outside auth middleware untuk public access)
Route::get('/qz-certificate', [PrinterSettingController::class, 'getCertificate']);
Route::post('/qz-sign', [PrinterSettingController::class, 'signRequest']);

// Certificate generation route (dengan auth)
Route::middleware('auth')->group(function () {
    Route::post('/generate-qz-certificate', [PrinterSettingController::class, 'generateCertificateCommand'])->name('generate-qz-certificate');
});

require __DIR__ . '/auth.php';
