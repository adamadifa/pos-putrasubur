<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::get('penjualan/search-products', [PenjualanController::class, 'searchProducts'])->name('penjualan.search-products');
    Route::get('penjualan/product/{id}', [PenjualanController::class, 'getProduct'])->name('penjualan.get-product');

    // Pembayaran Routes
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', function () {
            return view('pembayaran.index');
        })->name('index');
        Route::get('/create', function () {
            return view('pembayaran.create');
        })->name('create');
        Route::get('/{id}', function ($id) {
            return view('pembayaran.show', compact('id'));
        })->name('show');
    });

    // Master Data Produk Routes
    Route::resource('produk', ProdukController::class);


    // Kategori Produk Routes
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class)->except(['show']);

    // Satuan Routes
    Route::resource('satuan', \App\Http\Controllers\SatuanController::class)->except(['show']);

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
    });
});

require __DIR__ . '/auth.php';
