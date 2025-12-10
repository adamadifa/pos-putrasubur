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
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPembayaranController;
use App\Http\Controllers\LaporanPiutangController;
use App\Http\Controllers\LaporanHutangController;
use App\Http\Controllers\PenyesuaianStokController;
use App\Http\Controllers\PengaturanUmumController;
use App\Http\Controllers\MenuVisibilityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UangMukaSupplierController;
use App\Http\Controllers\UangMukaPelangganController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\PeminjamController;

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
        Route::post('/{encryptedId}/export-pdf', [PenjualanController::class, 'exportPdf'])->name('export-pdf');
    });

    // API Routes for Penjualan
    Route::get('/penjualan/{id}/detail', [PenjualanController::class, 'getDetail'])->name('penjualan.detail');
    Route::get('/pembelian/{id}/detail', [PembelianController::class, 'getDetail'])->name('pembelian.detail');

    // Penjualan API Routes (inside auth middleware but outside group to avoid parameter conflicts)
    Route::get('penjualan/search-products', [PenjualanController::class, 'searchProducts'])->name('penjualan.search-products');
    Route::get('penjualan/product/{id}', [PenjualanController::class, 'getProduct'])->name('penjualan.get-product');
    Route::get('penjualan/pending-receipt', [PenjualanController::class, 'getPendingReceipt'])->name('penjualan.pending-receipt');
    Route::get('penjualan/rfid/{rfid}', [PenjualanController::class, 'getRfidData'])->name('penjualan.rfid-data');

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

    // User Management Routes (Admin only)
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [UserController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [UserController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [UserController::class, 'destroy'])->name('destroy');
    });


    // Kategori Produk Routes (Admin & Kasir only)
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class)->except(['show'])->middleware('role:admin,kasir');

    // Satuan Routes (Admin & Kasir only)
    Route::resource('satuan', \App\Http\Controllers\SatuanController::class)->except(['show'])->middleware('role:admin,kasir');

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

    // Supplier Routes (Admin & Kasir only)
    Route::prefix('supplier')->name('supplier.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [SupplierController::class, 'destroy'])->name('destroy');
    });
    Route::get('supplier/search', [SupplierController::class, 'getSuppliers'])->name('supplier.search');

    // Peminjam Routes (Admin & Kasir only)
    Route::prefix('peminjam')->name('peminjam.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [PeminjamController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamController::class, 'create'])->name('create');
        Route::post('/', [PeminjamController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [PeminjamController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [PeminjamController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [PeminjamController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [PeminjamController::class, 'destroy'])->name('destroy');
    });

    // Kas & Bank Routes (Admin & Kasir only)
    Route::resource('kas-bank', KasBankController::class)->middleware('role:admin,kasir');

    // Transaksi Kas & Bank Routes (Admin & Kasir only)
    Route::resource('transaksi-kas-bank', TransaksiKasBankController::class)->middleware('role:admin,kasir');
    Route::resource('saldo-awal-bulanan', SaldoAwalBulananController::class)->except(['show', 'edit', 'update'])->middleware('role:admin,kasir');
    Route::resource('saldo-awal-produk', SaldoAwalProdukController::class)->except(['show', 'edit', 'update'])->middleware('role:admin,kasir');
    Route::resource('penyesuaian-stok', PenyesuaianStokController::class)->middleware('role:admin,kasir');

    // Saldo Awal Bulanan API Routes
    Route::post('saldo-awal-bulanan/get-saldo-akhir', [SaldoAwalBulananController::class, 'getSaldoAkhirBulanSebelumnya'])->name('saldo-awal-bulanan.get-saldo-akhir');

    // Saldo Awal Produk API Routes
    Route::post('saldo-awal-produk/get-all-produk', [SaldoAwalProdukController::class, 'getAllProduk'])->name('saldo-awal-produk.get-all-produk');
    Route::get('saldo-awal-produk/{saldoAwalProduk}/detail', [SaldoAwalProdukController::class, 'showDetail'])->name('saldo-awal-produk.detail');

    // Transaksi Pembelian Routes (Admin & Kasir only)
    Route::prefix('pembelian')->name('pembelian.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('index');
        Route::get('/create', [PembelianController::class, 'create'])->name('create');
        Route::post('/', [PembelianController::class, 'store'])->name('store');
        Route::get('/{encryptedId}', [PembelianController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [PembelianController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [PembelianController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [PembelianController::class, 'destroy'])->name('destroy');
        Route::post('/{encryptedId}/export-pdf', [PembelianController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/{encryptedId}/cetak-rawbt', [PembelianController::class, 'cetakRawBT'])->name('cetak-rawbt');
    });
    Route::get('pembelian/search', [PembelianController::class, 'getPembelian'])->name('pembelian.search');

    // Pembayaran Pembelian Routes (Admin & Kasir only)
    Route::prefix('pembayaran-pembelian')->name('pembayaran-pembelian.')->middleware('role:admin,kasir')->group(function () {
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

    // Pinjaman Routes (Admin & Kasir only)
    Route::prefix('pinjaman')->name('pinjaman.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::get('/create', [PinjamanController::class, 'create'])->name('create');
        Route::post('/', [PinjamanController::class, 'store'])->name('store');
        Route::delete('/pembayaran/{encryptedPembayaranId}', [PinjamanController::class, 'destroyPayment'])->name('pembayaran.destroy');
        Route::get('/{encryptedId}', [PinjamanController::class, 'show'])->name('show');
        Route::get('/{encryptedId}/edit', [PinjamanController::class, 'edit'])->name('edit');
        Route::put('/{encryptedId}', [PinjamanController::class, 'update'])->name('update');
        Route::delete('/{encryptedId}', [PinjamanController::class, 'destroy'])->name('destroy');
        Route::post('/{encryptedId}/pembayaran', [PinjamanController::class, 'storePayment'])->name('pembayaran.store');
    });

    // Uang Muka Supplier Routes (Admin & Kasir only)
    Route::prefix('uang-muka-supplier')->name('uang-muka-supplier.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [UangMukaSupplierController::class, 'index'])->name('index');
        Route::get('/create', [UangMukaSupplierController::class, 'create'])->name('create');
        Route::post('/', [UangMukaSupplierController::class, 'store'])->name('store');
        Route::get('/get-available', [UangMukaSupplierController::class, 'getAvailableUangMuka'])->name('get-available');
        Route::get('/{encryptedId}', [UangMukaSupplierController::class, 'show'])->name('show');
        Route::post('/{encryptedId}/return', [UangMukaSupplierController::class, 'return'])->name('return');
        Route::delete('/{encryptedId}/return/{transaksiId}', [UangMukaSupplierController::class, 'deleteReturn'])->name('return.delete');
        Route::delete('/{encryptedId}/cancel', [UangMukaSupplierController::class, 'cancel'])->name('cancel');
        Route::delete('/{encryptedId}', [UangMukaSupplierController::class, 'destroy'])->name('destroy');
    });

    // Uang Muka Pelanggan Routes (Admin & Kasir only)
    Route::prefix('uang-muka-pelanggan')->name('uang-muka-pelanggan.')->middleware('role:admin,kasir')->group(function () {
        Route::get('/', [UangMukaPelangganController::class, 'index'])->name('index');
        Route::get('/create', [UangMukaPelangganController::class, 'create'])->name('create');
        Route::post('/', [UangMukaPelangganController::class, 'store'])->name('store');
        Route::get('/get-available', [UangMukaPelangganController::class, 'getAvailableUangMuka'])->name('get-available');
        Route::get('/{encryptedId}', [UangMukaPelangganController::class, 'show'])->name('show');
        Route::post('/{encryptedId}/return', [UangMukaPelangganController::class, 'return'])->name('return');
        Route::delete('/{encryptedId}/return/{transaksiId}', [UangMukaPelangganController::class, 'deleteReturn'])->name('return.delete');
        Route::delete('/{encryptedId}/cancel', [UangMukaPelangganController::class, 'cancel'])->name('cancel');
        Route::delete('/{encryptedId}', [UangMukaPelangganController::class, 'destroy'])->name('destroy');
    });

    // Laporan Routes (Admin & Manager only)
    Route::prefix('laporan')->name('laporan.')->middleware('role:admin,manager')->group(function () {
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

    // Printer Settings Routes (Admin & Manager only)
    Route::prefix('printer')->name('printer.')->middleware('role:admin,manager')->group(function () {
        Route::get('/settings', [PrinterSettingController::class, 'index'])->name('settings');
        Route::post('/test-print', [PrinterSettingController::class, 'testPrint'])->name('test-print');
        Route::get('/get-settings', [PrinterSettingController::class, 'getSettings'])->name('get-settings');

        // CRUD Routes for Printer Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::post('/', [PrinterSettingController::class, 'store'])->name('store');
            Route::get('/{printerSetting}', [PrinterSettingController::class, 'show'])->name('show');
            Route::put('/{printerSetting}', [PrinterSettingController::class, 'update'])->name('update');
            Route::delete('/{printerSetting}', [PrinterSettingController::class, 'destroy'])->name('destroy');
            Route::post('/{printerSetting}/set-default', [PrinterSettingController::class, 'setDefault'])->name('set-default');
        });
    });

    // Laporan Routes (Admin, Manager & Kasir)
    Route::prefix('laporan')->name('laporan.')->middleware('role:admin,manager,kasir')->group(function () {
        Route::get('/', function () {
            return view('laporan.index');
        })->name('index');

        Route::prefix('kas-bank')->name('kas-bank.')->group(function () {
            Route::get('/', [LaporanKasBankController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanKasBankController::class, 'exportPdf'])->name('export-pdf');
        });

        Route::prefix('stok')->name('stok.')->group(function () {
            Route::get('/', [LaporanStokController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanStokController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/print', [LaporanStokController::class, 'print'])->name('print');
        });

        Route::prefix('penjualan')->name('penjualan.')->group(function () {
            Route::get('/', [LaporanPenjualanController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/print', [LaporanPenjualanController::class, 'print'])->name('print');
        });

        Route::prefix('pembelian')->name('pembelian.')->group(function () {
            Route::get('/', [LaporanPembelianController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanPembelianController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/print', [LaporanPembelianController::class, 'print'])->name('print');
        });

        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [LaporanPembayaranController::class, 'index'])->name('index');
            Route::post('/generate', [LaporanPembayaranController::class, 'index'])->name('generate');
            Route::post('/export-pdf', [LaporanPembayaranController::class, 'exportPdf'])->name('export-pdf');
        });

        Route::prefix('hutang')->name('hutang.')->group(function () {
            Route::get('/', [LaporanHutangController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanHutangController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/print', [LaporanHutangController::class, 'print'])->name('print');
        });

        Route::prefix('piutang')->name('piutang.')->group(function () {
            Route::get('/', [LaporanPiutangController::class, 'index'])->name('index');
            Route::post('/export-pdf', [LaporanPiutangController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/print', [LaporanPiutangController::class, 'print'])->name('print');
        });
    });

    // Pengaturan Umum (Admin & Manager only)
    Route::prefix('pengaturan-umum')->name('pengaturan-umum.')->middleware('role:admin,manager')->group(function () {
        Route::get('/', [PengaturanUmumController::class, 'index'])->name('index');
        Route::get('/create', [PengaturanUmumController::class, 'create'])->name('create');
        Route::post('/', [PengaturanUmumController::class, 'store'])->name('store');
        Route::get('/{pengaturanUmum}', [PengaturanUmumController::class, 'show'])->name('show');
        Route::get('/{pengaturanUmum}/edit', [PengaturanUmumController::class, 'edit'])->name('edit');
        Route::put('/{pengaturanUmum}', [PengaturanUmumController::class, 'update'])->name('update');
        Route::delete('/{pengaturanUmum}', [PengaturanUmumController::class, 'destroy'])->name('destroy');
        Route::post('/{pengaturanUmum}/set-active', [PengaturanUmumController::class, 'setActive'])->name('set-active');
    });

    // Menu Visibility (All authenticated users)
    Route::prefix('menu-visibility')->name('menu-visibility.')->group(function () {
        Route::get('/', [MenuVisibilityController::class, 'index'])->name('index');
        Route::post('/toggle', [MenuVisibilityController::class, 'toggle'])->name('toggle');
        Route::put('/', [MenuVisibilityController::class, 'update'])->name('update');
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
