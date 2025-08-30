@extends('layouts.pos')

@section('title', 'Pembelian Baru')
@section('page-title', 'Buat Transaksi Pembelian')

@section('content')
    <div class="min-h-screen">
        <!-- Back Button -->
        <div class="px-6 pt-6 pb-2">
            <a href="{{ route('pembelian.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-orange-600 transition-colors">
                <i class="ti ti-arrow-left text-lg mr-2"></i>
                Kembali ke Daftar Pembelian
            </a>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mx-6 mb-6 bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-alert-circle text-lg text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terdapat {{ $errors->count() }} kesalahan yang perlu diperbaiki:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex gap-6 px-6">
            <!-- Left Side - Products Menu -->
            <div class="flex-1">
                <!-- Category Tabs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex gap-3 mb-4">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" id="productSearch" placeholder="Cari nama atau SKU produk..."
                                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white">
                            </div>
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-scan text-lg text-gray-400"></i>
                                </div>
                                <input type="text" id="barcodeSearch" placeholder="Scan barcode..."
                                    class="w-full pl-11 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                <button type="button" id="scanButton"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-green-600 hover:text-green-700">
                                    <div
                                        class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center hover:bg-green-200 transition-colors">
                                        <i class="ti ti-qrcode text-lg"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex space-x-2 overflow-x-auto">
                            <button
                                class="category-filter active px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium whitespace-nowrap transition-colors"
                                data-category="all">
                                Semua
                            </button>
                            @php
                                $categories = collect($produk)->groupBy('kategori.nama')->keys();
                            @endphp
                            @foreach ($categories as $category)
                                <button
                                    class="category-filter px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium whitespace-nowrap transition-colors"
                                    data-category="{{ $category }}">
                                    {{ $category ?: 'Tanpa Kategori' }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="productsGrid">
                        @foreach ($produk->take(10) as $product)
                            <div class="product-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer group"
                                data-id="{{ $product->id }}" data-name="{{ $product->nama_produk }}"
                                data-code="{{ $product->kode_produk }}" data-price="{{ $product->harga_beli }}"
                                data-unit="{{ $product->satuan->nama ?? '' }}"
                                data-category="{{ $product->kategori->nama ?? '' }}">

                                <!-- Product Image Placeholder -->
                                <div
                                    class="w-full h-24 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg mb-3 flex items-center justify-center group-hover:from-orange-200 group-hover:to-orange-300 transition-all duration-200">
                                    @if ($product->foto)
                                        <img src="{{ asset('storage/' . $product->foto) }}"
                                            alt="{{ $product->nama_produk }}"
                                            class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <i class="ti ti-package text-2xl text-orange-600"></i>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="text-center">
                                    <h3 class="font-semibold text-sm text-gray-900 mb-2 line-clamp-2">
                                        {{ $product->nama_produk }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $product->kode_produk }}</p>

                                    <!-- Category Badge -->
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border bg-orange-100 text-orange-800 border-orange-200">
                                            <i class="ti ti-tag text-xs mr-1"></i>
                                            <span>{{ $product->kategori->nama ?? 'Uncategorized' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Button -->
                                <button
                                    class="add-product-btn w-full mt-3 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors opacity-0 group-hover:opacity-100">
                                    <i class="ti ti-plus text-sm mr-1"></i>
                                    Tambah
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side - Order Summary -->
            <div class="w-96">
                <form action="{{ route('pembelian.store') }}" method="POST" id="purchaseForm">
                    @csrf

                    <!-- Supplier & Invoice Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h3>

                        <!-- Invoice Number -->
                        <div class="mb-2">
                            <input type="text" name="no_faktur" id="noFaktur"
                                value="{{ old('no_faktur', $invoiceNumber) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                placeholder="Nomor Faktur" readonly>
                        </div>

                        <!-- Date -->
                        <div class="mb-2">
                            <div class="date-input-wrapper">
                                <input type="text" id="tanggal" value="{{ old('tanggal', date('d/m/Y')) }}"
                                    class="flatpickr-input w-full" placeholder="Pilih tanggal" required readonly>
                                <i class="ti ti-calendar"></i>
                            </div>
                            <input type="hidden" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>

                        <!-- Supplier -->
                        <div class="mb-2">
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <input type="text" id="supplierDisplay"
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                                        placeholder="Pilih Supplier" readonly>
                                    <button type="button" id="clearSupplierBtn"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors hidden">
                                        <i class="ti ti-x text-lg"></i>
                                    </button>
                                </div>
                                <button type="button" id="searchSupplierBtn"
                                    class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="ti ti-search text-lg"></i>
                                </button>
                            </div>
                            <input type="hidden" name="supplier_id" id="supplierId" value="{{ old('supplier_id') }}"
                                required>
                        </div>

                        <!-- Info Box -->
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="ti ti-info-circle text-blue-600 mr-2"></i>
                                <span class="text-sm text-blue-800">Konfigurasi pembayaran akan diatur pada preview
                                    pesanan</span>
                            </div>
                        </div>

                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" name="jenis_transaksi" id="jenisTransaksi"
                            value="{{ old('jenis_transaksi', 'tunai') }}">
                        <input type="hidden" name="metode_pembayaran" id="metodePembayaran"
                            value="{{ old('metode_pembayaran') }}">
                        <input type="hidden" name="kas_bank_id" id="kasBankId" value="{{ old('kas_bank_id') }}">
                        <input type="hidden" name="dp_amount" id="dpAmount" value="{{ old('dp_amount', 0) }}">


                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Pesanan</h3>
                            <span class="text-sm text-gray-500" id="orderCount">0 item</span>
                        </div>

                        <!-- Order Items -->
                        <div id="orderItems" class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                            <!-- Items will be added here dynamically -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="text-center py-8 text-gray-500">
                            <i class="ti ti-shopping-cart-off text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada produk dipilih</p>
                        </div>

                        <!-- Discount -->
                        <div class="border-t border-gray-200 pt-4">
                            <input type="text" id="diskonDisplay" value="{{ old('diskon', 0) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right"
                                placeholder="Diskon (Rp)">
                            <input type="hidden" name="diskon" id="diskon" value="{{ old('diskon', 0) }}">
                        </div>
                    </div>

                    <!-- Total & Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <!-- Summary -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="subtotalDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="font-medium text-red-600" id="discountDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span id="totalDisplay">Rp 0</span>
                            </div>

                            <!-- DP & Remaining Payment (for kredit) -->
                            <div id="paymentBreakdown" class="hidden border-t border-gray-200 pt-2 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-green-600">DP Dibayar</span>
                                    <span class="font-medium text-green-600" id="dpDisplay">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-orange-600">Sisa Pembayaran</span>
                                    <span class="font-medium text-orange-600" id="remainingDisplay">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="button" id="showOrderPreview"
                                class="w-full py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors">
                                <i class="ti ti-eye text-lg mr-2"></i>
                                Preview Pesanan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Preview Modal -->
    <div id="orderPreviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Preview Pesanan Pembelian</h3>
                    <button type="button" id="closeOrderPreviewModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-2xl"></i>
                    </button>
                </div>

                <!-- Supplier Info -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-600 rounded-full flex items-center justify-center">
                            <i class="ti ti-building text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900" id="previewSupplierName">-</h4>
                            <p class="text-sm text-gray-600" id="previewSupplierCode">-</p>
                        </div>
                    </div>
                </div>

                <!-- Transaction Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">No. Faktur</p>
                        <p class="font-semibold text-gray-900" id="previewInvoiceNumber">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-900" id="previewDate">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Jenis Transaksi</p>
                        <p class="font-semibold text-gray-900" id="previewTransactionType">-</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Detail Pesanan</h4>
                    <div class="max-h-64 overflow-y-auto" id="previewOrderItems">
                        <!-- Items will be populated here -->
                    </div>
                </div>

                <!-- Payment Configuration -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Konfigurasi Pembayaran</h4>

                    <div class="space-y-4">
                        <!-- Jenis Transaksi -->
                        <div>
                            <div class="grid grid-cols-2 gap-3" id="previewTransactionTypeContainer">
                                <label class="relative cursor-pointer preview-transaction-type-option">
                                    <input type="radio" name="preview_jenis_transaksi" value="tunai"
                                        class="sr-only preview-transaction-type-radio">
                                    <div
                                        class="p-3 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 preview-transaction-type-card">
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mb-2">
                                                <i class="ti ti-cash text-green-600 text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Tunai</span>
                                            <span class="text-xs text-gray-500">Bayar Langsung</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer preview-transaction-type-option">
                                    <input type="radio" name="preview_jenis_transaksi" value="kredit"
                                        class="sr-only preview-transaction-type-radio">
                                    <div
                                        class="p-3 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 preview-transaction-type-card">
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mb-2">
                                                <i class="ti ti-credit-card text-orange-600 text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Kredit</span>
                                            <span class="text-xs text-gray-500">Bayar Nanti</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <div class="grid grid-cols-1 gap-3" id="previewPaymentMethodContainer">
                                @foreach ($metodePembayaran as $metode)
                                    <label class="relative cursor-pointer preview-payment-method-option">
                                        <input type="radio" name="preview_metode_pembayaran"
                                            value="{{ $metode->kode }}" class="sr-only preview-payment-method-radio">
                                        <div
                                            class="p-3 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 preview-payment-method-card">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="ti {{ $metode->icon_display }} text-orange-600 text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $metode->nama }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kas/Bank Selection -->
                        <div>
                            <!-- Message when no payment method selected -->
                            <div id="kasBankMessage" class="text-center py-8 text-gray-500">
                                <i class="ti ti-arrow-up text-2xl mb-2"></i>
                                <p class="text-sm">Pilih metode pembayaran terlebih dahulu untuk melihat pilihan kas/bank
                                </p>
                            </div>

                            <div class="grid gap-3" id="previewKasBankContainer">
                                @foreach ($kasBank ?? [] as $kas)
                                    <label class="relative cursor-pointer preview-kas-bank-option">
                                        <input type="radio" name="preview_kas_bank_id" value="{{ $kas->id }}"
                                            data-saldo="{{ $kas->saldo_terkini }}" data-jenis="{{ $kas->jenis }}"
                                            data-image="{{ $kas->image_url ?? '' }}"
                                            class="sr-only preview-kas-bank-radio">
                                        <div
                                            class="p-4 border-2 border-gray-200 rounded-xl hover:border-orange-400 hover:bg-gradient-to-br hover:from-orange-50 hover:to-red-50 transition-all duration-300 preview-kas-bank-card flex items-center justify-between shadow-sm hover:shadow-md">
                                            <div class="flex items-center flex-1">
                                                <div
                                                    class="w-16 h-16 rounded-xl flex items-center justify-center mr-4 overflow-hidden shadow-sm flex-shrink-0">
                                                    @if ($kas->jenis === 'KAS')
                                                        <div
                                                            class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                                            <i class="ti ti-cash text-green-600 text-xl"></i>
                                                        </div>
                                                    @else
                                                        @if ($kas->image)
                                                            <img src="{{ asset('storage/' . $kas->image) }}"
                                                                alt="Logo {{ $kas->nama }}"
                                                                class="w-full h-full object-contain">
                                                        @else
                                                            <div
                                                                class="w-full h-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                                                                <i class="ti ti-building-bank text-purple-600 text-xl"></i>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex-1 flex flex-col justify-center">
                                                    <div class="text-base font-bold text-gray-900 leading-tight">
                                                        {{ $kas->nama }}
                                                    </div>
                                                    @if ($kas->no_rekening)
                                                        <div class="text-sm text-gray-500 font-medium">
                                                            {{ $kas->no_rekening }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right ml-4 flex flex-col justify-center flex-shrink-0">
                                                <div class="text-sm text-gray-500 font-medium">Saldo</div>
                                                <div class="text-base font-bold text-green-600">
                                                    Rp {{ number_format($kas->saldo_terkini, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- DP Amount (shown only for kredit) -->
                        <div id="previewDpContainer" class="hidden">
                            <label for="previewDpAmount" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Down Payment (DP)
                            </label>
                            <input type="text" id="previewDpAmount"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right"
                                placeholder="Jumlah DP (Rp)">
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Ringkasan Pesanan</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="previewSubtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm" id="previewDiscountRow" style="display: none;">
                            <span class="text-gray-600">Diskon</span>
                            <span class="font-medium text-red-600" id="previewDiscount">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                            <span>Total</span>
                            <span id="previewTotal">Rp 0</span>
                        </div>
                        <div id="previewPaymentBreakdown" class="hidden border-t border-gray-200 pt-2 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600">DP Dibayar</span>
                                <span class="font-medium text-green-600" id="previewDpDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-orange-600">Sisa Pembayaran</span>
                                <span class="font-medium text-orange-600" id="previewRemainingDisplay">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="button" id="closePreviewModal"
                        class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors">
                        <i class="ti ti-x text-lg mr-2"></i>
                        Batal
                    </button>
                    <button type="button" id="confirmOrderSave"
                        class="flex-1 py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors">
                        <i class="ti ti-device-floppy text-lg mr-2"></i>
                        Simpan Pembelian
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quantity Input Modal -->
    <div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Masukkan Quantity</h3>
                    <button type="button" id="closeQuantityModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <!-- Product Info -->
                <div id="modalProductInfo" class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg flex items-center justify-center">
                            <i class="ti ti-package text-xl text-orange-600"></i>
                        </div>
                        <div class="flex-1">
                            <h4 id="modalProductName" class="font-semibold text-gray-900"></h4>
                            <p id="modalProductCode" class="text-sm text-gray-500"></p>
                            <p id="modalProductPrice" class="text-sm font-medium text-orange-600"></p>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <span>Satuan: </span>
                        <span id="modalProductUnit" class="font-medium text-orange-600"></span>
                    </div>
                </div>

                <!-- Quantity Input -->
                <div class="mb-4">
                    <label for="quantityInput" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantity
                    </label>
                    <div class="flex items-center space-x-2">
                        <button type="button" id="decreaseQty"
                            class="w-12 h-12 bg-red-500 hover:bg-red-600 rounded-xl text-white flex items-center justify-center font-bold text-xl transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                            <i class="ti ti-minus"></i>
                        </button>
                        <div class="flex-1 relative">
                            <input type="text" id="quantityInput"
                                class="w-full pl-4 pr-16 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-center text-lg font-semibold"
                                value="1" placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span id="modalProductUnitInInput"
                                    class="text-sm font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded"></span>
                            </div>
                        </div>
                        <button type="button" id="increaseQty"
                            class="w-12 h-12 bg-orange-600 hover:bg-orange-700 rounded-xl text-white flex items-center justify-center font-bold text-xl transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan jumlah yang diinginkan</p>
                </div>

                <!-- Price Input -->
                <div class="mb-4">
                    <label for="modalPriceInput" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Beli Satuan
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-medium">Rp</span>
                        </div>
                        <input type="text" id="modalPriceInput"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-right text-lg font-semibold"
                            placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Harga default: <span id="modalDefaultPrice"
                            class="font-medium text-orange-600"></span></p>
                </div>

                <!-- Discount Input -->
                <div class="mb-6">
                    <label for="discountInput" class="block text-sm font-medium text-gray-700 mb-2">
                        Potongan Harga (Opsional)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-medium">Rp</span>
                        </div>
                        <input type="text" id="discountInput"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right text-lg font-semibold"
                            value="0" placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan potongan harga untuk produk ini (dalam Rupiah)</p>
                </div>

                <!-- Total Price Preview -->
                <div class="mb-6 p-3 bg-orange-50 rounded-lg">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="modalSubtotalPrice" class="font-medium text-gray-800">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm" id="modalDiscountRow"
                            style="display: none;">
                            <span class="text-orange-600">Potongan:</span>
                            <span id="modalDiscountPrice" class="font-medium text-orange-600">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-orange-200 pt-2">
                            <span class="text-sm font-medium text-gray-700">Total Harga:</span>
                            <span id="modalTotalPrice" class="text-lg font-bold text-orange-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="button" id="cancelQuantity"
                        class="w-24 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="button" id="confirmQuantity"
                        class="flex-1 py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors whitespace-nowrap">
                        <i class="ti ti-plus text-sm mr-1"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Selection Modal -->
    <div id="supplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Supplier</h3>
                    <button type="button" id="closeSupplierModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <!-- Search Supplier -->
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-search text-lg text-gray-400"></i>
                        </div>
                        <input type="text" id="supplierSearch" placeholder="Cari nama atau kode supplier..."
                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <!-- Supplier List -->
                <div id="supplierList" class="max-h-96 overflow-y-auto space-y-2">
                    @foreach ($suppliers as $supplier)
                        <div class="supplier-item p-4 border border-gray-200 rounded-lg hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition-all duration-200"
                            data-id="{{ $supplier->id }}" data-name="{{ $supplier->nama }}"
                            data-code="{{ $supplier->kode_supplier }}" data-phone="{{ $supplier->telepon ?? '' }}"
                            data-address="{{ $supplier->alamat ?? '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center">
                                            <i class="ti ti-building-store text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $supplier->nama }}</h4>
                                            <p class="text-sm text-gray-500">{{ $supplier->kode_supplier }}</p>
                                            @if ($supplier->telepon)
                                                <p class="text-xs text-gray-400">{{ $supplier->telepon }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($supplier->alamat)
                                        <p class="text-xs text-gray-500 mt-2 ml-13">
                                            {{ Str::limit($supplier->alamat, 50) }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if ($supplier->status)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="ti ti-check-circle text-xs mr-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="ti ti-x-circle text-xs mr-1"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add New Supplier Button -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('supplier.create') }}" target="_blank"
                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="ti ti-plus text-lg mr-2"></i>
                        Tambah Supplier Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* POS Form Styling */
        .date-input-wrapper {
            position: relative;
        }

        .flatpickr-input {
            padding-right: 40px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            background: #f9fafb !important;
            color: #374151 !important;
            cursor: pointer !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .date-input-wrapper i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            z-index: 10;
        }

        /* Enhanced Product Card */
        .product-card {
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .product-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-2px);
            border-color: #ea580c;
        }

        .product-card.selected {
            border-color: #dc2626;
            background-color: #fef3c7;
        }

        .product-card .add-product-btn {
            transition: all 0.3s ease;
            transform: translateY(10px);
        }

        .product-card:hover .add-product-btn {
            opacity: 1;
            transform: translateY(0);
        }

        /* Order Items Styling */
        .order-item {
            animation: slideIn 0.3s ease;
            transition: all 0.2s ease;
        }

        .order-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .order-item.highlight {
            background: #fef3c7;
            border-color: #f59e0b;
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        /* Order item layout improvements */
        .order-item .space-y-2>*+* {
            margin-top: 0.5rem;
        }

        /* Quantity badge styling */
        .order-item .bg-orange-50 {
            background: linear-gradient(135deg, #fed7aa, #fdba74);
            border: 1px solid #fb923c;
        }

        /* Remove button improved styling */
        .order-item .remove-btn {
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }

        .order-item .remove-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        /* Order item highlight styles */

        /* Order Preview Modal Styles */
        #orderPreviewModal {
            backdrop-filter: blur(4px);
        }

        #orderPreviewModal>div {
            animation: slideInDown 0.3s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Preview card styles */
        .preview-transaction-type-card,
        .preview-payment-method-card,
        .preview-kas-bank-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .preview-transaction-type-card:hover,
        .preview-payment-method-card:hover,
        .preview-kas-bank-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .preview-transaction-type-card.selected,
        .preview-payment-method-card.selected,
        .preview-kas-bank-card.selected {
            border-color: #ea580c !important;
            background-color: #fff7ed !important;
        }

        .order-item.ring-2 {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.3);
        }

        /* Payment Method Styling */
        .payment-method-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .payment-method-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .payment-method-card.selected {
            border-color: #ea580c !important;
            background-color: #fff7ed !important;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.2);
        }

        .payment-method-radio:checked+.payment-method-card {
            border-color: #ea580c !important;
            background-color: #fff7ed !important;
        }

        /* Price breakdown styling */
        .order-item .text-orange-600 {
            font-weight: 500;
        }

        .order-item .font-bold.text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .animate-pulse-custom {
            animation: pulse 0.6s ease-in-out;
        }

        /* Loading animation */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Toast animation */
        .toast-enter {
            transform: translateX(100%);
            opacity: 0;
        }

        .toast-enter-active {
            transform: translateX(0);
            opacity: 1;
            transition: all 0.3s ease;
        }

        .toast-exit {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-exit-active {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
        }

        /* Focus styles */
        .product-card:focus {
            outline: 2px solid #ea580c;
            outline-offset: 2px;
        }

        .order-item:focus {
            outline: 2px solid #ea580c;
            outline-offset: 2px;
        }

        /* Disabled state */
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Hover effects for buttons */
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Category Filter Active State */
        .category-filter.active {
            background: #ea580c !important;
            color: white !important;
        }

        /* Modal Overlay */
        #quantityModal,
        #supplierModal {
            backdrop-filter: blur(8px);
        }

        /* Flatpickr Customization for Purchase Theme */
        .flatpickr-months {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
        }

        .flatpickr-day.selected {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
        }

        .flatpickr-day:hover {
            background: #fed7aa !important;
            color: #ea580c !important;
        }

        /* Custom scrollbar for order items */
        #orderItems::-webkit-scrollbar {
            width: 6px;
        }

        #orderItems::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        #orderItems::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        #orderItems::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Line clamp for product names */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@push('scripts')
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script>
        // Purchase Form State
        let cart = [];
        let selectedProduct = null;
        let totalAmount = 0;
        let discountAmount = 0;
        let editingItemIndex = null;
        let productIndex = 0;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeFlatpickr();
            initializeProductSearch();
            initializeCategoryFilter();
            initializeProductSelection();
            initializeSupplierModal();
            initializeQuantityModal();
            initializeFormSubmission();
            initializeDiscountInput();
            initializeTransactionType();
            initializePaymentMethod();
            updateOrderSummary();
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="ti ti-${type === 'success' ? 'check-circle' : 'alert-circle'} text-lg"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Remove toast
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Flatpickr initialization
        function initializeFlatpickr() {
            flatpickr("#tanggal", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    document.querySelector('input[name="tanggal"]').value = selectedDates[0].toISOString()
                        .split('T')[0];
                }
            });
        }

        // Product search functionality
        function initializeProductSearch() {
            const productSearch = document.getElementById('productSearch');
            const barcodeSearch = document.getElementById('barcodeSearch');

            productSearch.addEventListener('input', function() {
                filterProducts(this.value);
            });

            barcodeSearch.addEventListener('input', function() {
                searchByBarcode(this.value);
            });
        }

        // Category filter functionality
        function initializeCategoryFilter() {
            document.querySelectorAll('.category-filter').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.category-filter').forEach(btn => {
                        btn.classList.remove('active', 'bg-orange-600', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'bg-orange-600', 'text-white');
                    this.classList.remove('bg-gray-100', 'text-gray-700');

                    // Filter products by category
                    filterProductsByCategory(this.dataset.category);
                });
            });
        }

        // Filter products by search term
        function filterProducts(searchTerm) {
            const productCards = document.querySelectorAll('.product-card');
            searchTerm = searchTerm.toLowerCase();

            productCards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const code = card.dataset.code.toLowerCase();

                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Filter products by category
        function filterProductsByCategory(category) {
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Product selection functionality
        function initializeProductSelection() {
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Remove previous selection
                    document.querySelectorAll('.product-card').forEach(c => c.classList.remove('selected'));

                    // Add selection to current card
                    this.classList.add('selected');

                    selectedProduct = {
                        id: this.dataset.id,
                        name: this.dataset.name,
                        code: this.dataset.code,
                        price: parseFloat(this.dataset.price) || 0,
                        unit: this.dataset.unit,
                        category: this.dataset.category
                    };
                    openQuantityModal();
                });
            });
        }

        // Supplier modal functionality
        function initializeSupplierModal() {
            const searchSupplierBtn = document.getElementById('searchSupplierBtn');
            const supplierModal = document.getElementById('supplierModal');
            const closeSupplierModal = document.getElementById('closeSupplierModal');
            const supplierSearch = document.getElementById('supplierSearch');
            const clearSupplierBtn = document.getElementById('clearSupplierBtn');

            searchSupplierBtn.addEventListener('click', () => {
                supplierModal.classList.remove('hidden');
            });

            closeSupplierModal.addEventListener('click', () => {
                supplierModal.classList.add('hidden');
            });

            // Clear supplier selection
            clearSupplierBtn.addEventListener('click', () => {
                document.getElementById('supplierDisplay').value = '';
                document.getElementById('supplierId').value = '';
                clearSupplierBtn.classList.add('hidden');
            });

            // Supplier search
            supplierSearch.addEventListener('input', function() {
                filterSuppliers(this.value);
            });

            // Supplier selection
            document.querySelectorAll('.supplier-item').forEach(item => {
                item.addEventListener('click', function() {
                    const supplierDisplay = document.getElementById('supplierDisplay');
                    const supplierId = document.getElementById('supplierId');

                    supplierDisplay.value = `${this.dataset.name} (${this.dataset.code})`;
                    supplierId.value = this.dataset.id;

                    clearSupplierBtn.classList.remove('hidden');
                    supplierModal.classList.add('hidden');
                });
            });
        }

        // Filter suppliers
        function filterSuppliers(searchTerm) {
            const supplierItems = document.querySelectorAll('.supplier-item');
            searchTerm = searchTerm.toLowerCase();

            supplierItems.forEach(item => {
                const name = item.dataset.name.toLowerCase();
                const code = item.dataset.code.toLowerCase();

                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Quantity modal functionality
        function initializeQuantityModal() {
            const quantityModal = document.getElementById('quantityModal');
            const closeQuantityModal = document.getElementById('closeQuantityModal');
            const cancelQuantity = document.getElementById('cancelQuantity');
            const confirmQuantity = document.getElementById('confirmQuantity');
            const quantityInput = document.getElementById('quantityInput');
            const priceInput = document.getElementById('modalPriceInput');
            const discountInput = document.getElementById('discountInput');
            const decreaseQty = document.getElementById('decreaseQty');
            const increaseQty = document.getElementById('increaseQty');

            // Close modal handlers
            function closeQuantityModalHandler() {
                quantityModal.classList.add('hidden');
                selectedProduct = null;
                editingItemIndex = null;
            }

            closeQuantityModal.addEventListener('click', closeQuantityModalHandler);
            cancelQuantity.addEventListener('click', closeQuantityModalHandler);

            // Disable closing modal when clicking outside - user must use buttons to close
            // quantityModal.addEventListener('click', (e) => {
            //     if (e.target === quantityModal) {
            //         closeQuantityModalHandler();
            //     }
            // });

            // Disable closing modal with ESC key - user must use buttons to close
            // document.addEventListener('keydown', (e) => {
            //     if (e.key === 'Escape' && !quantityModal.classList.contains('hidden')) {
            //         closeQuantityModalHandler();
            //     }
            // });

            // Setup input formatting
            setupDecimalInput(quantityInput);
            setupNumberInput(priceInput);
            setupNumberInput(discountInput);

            // Quantity controls
            decreaseQty.addEventListener('click', () => {
                let value = parseFormattedDecimal(quantityInput.value) || 1;
                if (value > 0.1) {
                    let newValue = value >= 1 ? value - 1 : Math.max(0.1, Math.round((value - 0.1) * 10) / 10);
                    quantityInput.value = formatDecimalInput(newValue);
                    quantityInput.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    updateModalTotal();
                }
            });

            increaseQty.addEventListener('click', () => {
                let value = parseFormattedDecimal(quantityInput.value) || 1;
                let newValue = value % 1 === 0 ? value + 1 : Math.round((value + 0.1) * 10) / 10;
                quantityInput.value = formatDecimalInput(newValue);
                quantityInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                updateModalTotal();
            });

            // Input events with real-time validation
            quantityInput.addEventListener('input', function() {
                updateModalTotal();
                validateQuantityInput();
            });

            priceInput.addEventListener('input', function() {
                updateModalTotal();
                validatePriceInput();
            });

            discountInput.addEventListener('input', function() {
                updateModalTotal();
                validateDiscountInput();
            });

            // Confirm quantity
            confirmQuantity.addEventListener('click', () => {
                addToCart();
                closeQuantityModalHandler();
            });

            // Allow Enter key to confirm quantity
            quantityInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    confirmQuantity.click();
                }
            });

            // Prevent Enter key from closing modal on other inputs
            [priceInput, discountInput].forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Prevent form submission or modal closing
                        // Optionally, you can move focus to the next input or confirm button
                        if (this.id === 'modalPriceInput') {
                            discountInput.focus();
                        } else if (this.id === 'discountInput') {
                            confirmQuantity.focus();
                        }
                    }
                });
            });
        }

        // Open quantity modal
        function openQuantityModal() {
            if (!selectedProduct) return;

            const modal = document.getElementById('quantityModal');
            document.getElementById('modalProductName').textContent = selectedProduct.name;
            document.getElementById('modalProductCode').textContent = selectedProduct.code;
            document.getElementById('modalProductPrice').textContent = `Rp ${formatNumber(selectedProduct.price)}`;
            document.getElementById('modalProductUnit').textContent = selectedProduct.unit;
            document.getElementById('modalProductUnitInInput').textContent = selectedProduct.unit;
            document.getElementById('modalDefaultPrice').textContent = `Rp ${formatNumber(selectedProduct.price)}`;

            // Set default values for new items
            if (editingItemIndex === null) {
                document.getElementById('quantityInput').value = '1';
                document.getElementById('modalPriceInput').value = formatNumber(selectedProduct.price);
                document.getElementById('discountInput').value = '0';
            }

            // Reset validation styles
            resetValidationStyles();

            updateModalTotal();
            modal.classList.remove('hidden');
        }

        // Update modal total
        function updateModalTotal() {
            const qty = parseFormattedDecimal(document.getElementById('quantityInput').value) || 0;
            const price = parseFormattedNumber(document.getElementById('modalPriceInput').value) || 0;
            const discount = parseFormattedNumber(document.getElementById('discountInput').value) || 0;

            const subtotal = qty * price;
            const total = subtotal - discount;

            document.getElementById('modalSubtotalPrice').textContent = `Rp ${formatNumber(subtotal)}`;
            document.getElementById('modalDiscountPrice').textContent = `Rp ${formatNumber(discount)}`;
            document.getElementById('modalTotalPrice').textContent = `Rp ${formatNumber(Math.max(0, total))}`;

            const discountRow = document.getElementById('modalDiscountRow');
            if (discount > 0) {
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }

            // Validate discount doesn't exceed subtotal
            if (discount > subtotal && subtotal > 0) {
                document.getElementById('discountInput').style.borderColor = '#ef4444';
                document.getElementById('discountInput').style.backgroundColor = '#fef2f2';
            } else {
                document.getElementById('discountInput').style.borderColor = '#d1d5db';
                document.getElementById('discountInput').style.backgroundColor = '#ffffff';
            }
        }

        // Add to cart
        function addToCart() {
            if (!selectedProduct) return;

            const qtyInput = document.getElementById('quantityInput');
            const priceInput = document.getElementById('modalPriceInput');
            const discountInput = document.getElementById('discountInput');

            // Check if inputs are empty first
            if (!qtyInput.value.trim()) {
                showToast('Quantity tidak boleh kosong!', 'error');
                qtyInput.focus();
                return;
            }

            if (!priceInput.value.trim()) {
                showToast('Harga tidak boleh kosong!', 'error');
                priceInput.focus();
                return;
            }

            const qty = parseFormattedDecimal(qtyInput.value) || 0;
            const price = parseFormattedNumber(priceInput.value) || 0;
            const discount = parseFormattedNumber(discountInput.value) || 0;

            if (qty <= 0) {
                showToast('Quantity harus lebih dari 0!', 'error');
                qtyInput.focus();
                return;
            }

            if (price <= 0) {
                showToast('Harga harus lebih dari 0!', 'error');
                priceInput.focus();
                return;
            }

            // Validate discount
            const subtotal = price * qty;
            if (discount > subtotal) {
                showToast('Potongan tidak boleh melebihi subtotal!', 'error');
                discountInput.focus();
                return;
            }

            if (editingItemIndex !== null) {
                // Edit existing item
                cart[editingItemIndex] = {
                    ...cart[editingItemIndex],
                    qty: qty,
                    price: price,
                    discount: discount
                };
                showToast(`${selectedProduct.name} berhasil diperbarui`, 'success');
                editingItemIndex = null;
            } else {
                const existingIndex = cart.findIndex(item => item.id === selectedProduct.id);

                if (existingIndex >= 0) {
                    // Update existing item
                    cart[existingIndex] = {
                        ...cart[existingIndex],
                        qty: qty,
                        price: price,
                        discount: discount
                    };
                    showToast(`${selectedProduct.name} diperbarui dalam pesanan`, 'success');
                } else {
                    // Add new item
                    cart.push({
                        id: selectedProduct.id,
                        name: selectedProduct.name,
                        code: selectedProduct.code,
                        qty: qty,
                        price: price,
                        unit: selectedProduct.unit,
                        discount: discount,
                        index: productIndex++
                    });
                    showToast(`${selectedProduct.name} ditambahkan ke pesanan (${qty} ${selectedProduct.unit})`, 'success');
                }
            }

            updateOrderSummary();
            selectedProduct = null;
        }

        // Update order summary
        function updateOrderSummary() {
            const orderItems = document.getElementById('orderItems');
            const emptyState = document.getElementById('emptyState');
            const orderCount = document.getElementById('orderCount');

            if (cart.length === 0) {
                orderItems.innerHTML = '';
                emptyState.style.display = 'block';
                orderCount.textContent = '0 item';
            } else {
                emptyState.style.display = 'none';
                orderCount.textContent = `${cart.length} item${cart.length > 1 ? 's' : ''}`;

                orderItems.innerHTML = cart.map((item, index) => {
                    const subtotal = item.price * item.qty;
                    const discount = item.discount || 0;
                    const total = subtotal - discount;

                    return `
                        <div class="order-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer" data-index="${index}" onclick="editOrderItem(${index})">
                            <!-- Product Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">${item.name}</h4>
                                    <p class="text-xs text-gray-500 mt-1">${item.code}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="text-center">
                                        <div class="bg-orange-50 px-3 py-1 rounded-full">
                                            <span class="qty text-sm font-semibold text-orange-700">${formatDecimalInput(item.qty)}</span>
                                            <span class="text-xs text-orange-600 ml-1">${item.unit}</span>
                                        </div>
                                    </div>
                                    <button type="button" class="remove-btn w-8 h-8 bg-red-500 rounded-full text-white hover:bg-red-600 flex items-center justify-center transition-colors" onclick="event.stopPropagation(); removeFromCart(${index})">
                                        <i class="ti ti-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Price Breakdown -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Harga Beli Satuan</span>
                                    <span class="font-medium text-gray-800">Rp ${formatNumber(item.price)}</span>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Subtotal (${formatDecimalInput(item.qty)} × Rp ${formatNumber(item.price)})</span>
                                    <span class="font-medium text-gray-800">Rp ${formatNumber(subtotal)}</span>
                                </div>
                                
                                ${discount > 0 ? `
                                                                                                                                    <div class="flex items-center justify-between text-sm">
                                                                                                                                        <span class="text-orange-600 flex items-center">
                                                                                                                                            <i class="ti ti-discount-2 text-xs mr-1"></i>
                                                                                                                                            Potongan Harga
                                                                                                                                        </span>
                                                                                                                                        <span class="font-medium text-orange-600">-Rp ${formatNumber(discount)}</span>
                                                                                                                                    </div>
                                                                                                                                ` : ''}
                                
                                <!-- Total Line -->
                                <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-200">
                                    <span class="font-semibold text-gray-900">Total</span>
                                    <span class="font-bold text-lg ${discount > 0 ? 'text-green-600' : 'text-orange-600'}">Rp ${formatNumber(total)}</span>
                                </div>
                            </div>
                            
                            <!-- Edit Hint -->
                            <div class="mt-3 pt-2 border-t border-gray-100">
                                <p class="text-xs text-gray-400 text-center">
                                    <i class="ti ti-click text-xs mr-1"></i>
                                    Klik untuk edit quantity atau potongan
                                </p>
                            </div>
                        </div>
                    `;
                }).join('');

                // Add highlight animation to newly added items
                setTimeout(() => {
                    const newItems = orderItems.querySelectorAll('.order-item');
                    if (newItems.length > 0) {
                        const lastItem = newItems[newItems.length - 1];
                        lastItem.classList.add('highlight');
                        setTimeout(() => {
                            lastItem.classList.remove('highlight');
                        }, 1000);
                    }
                }, 100);
            }

            calculateTotals();
        }

        // Edit order item
        function editOrderItem(index) {
            const item = cart[index];
            if (!item) return;

            // Set current product for editing
            selectedProduct = {
                id: item.id,
                name: item.name,
                code: item.code,
                price: item.price,
                unit: item.unit
            };

            editingItemIndex = index;

            // Open modal with current values
            const modal = document.getElementById('quantityModal');
            document.getElementById('modalProductName').textContent = item.name;
            document.getElementById('modalProductCode').textContent = item.code;
            document.getElementById('modalProductPrice').textContent = `Rp ${formatNumber(item.price)}`;
            document.getElementById('modalProductUnit').textContent = item.unit;
            document.getElementById('modalProductUnitInInput').textContent = item.unit;
            document.getElementById('modalDefaultPrice').textContent = `Rp ${formatNumber(item.price)}`;

            // Set current values
            document.getElementById('quantityInput').value = formatDecimalInput(item.qty);
            document.getElementById('modalPriceInput').value = formatNumber(item.price);
            document.getElementById('discountInput').value = formatNumber(item.discount);

            updateModalTotal();
            modal.classList.remove('hidden');

            showToast(`Edit ${item.name}`, 'success');
        }

        // Remove from cart
        function removeFromCart(index) {
            const item = cart[index];
            cart.splice(index, 1);
            updateOrderSummary();
            showToast(`${item.name} dihapus dari pesanan`, 'success');
        }

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;

            cart.forEach(item => {
                subtotal += (item.qty * item.price) - item.discount;
            });

            totalAmount = Math.max(0, subtotal - discountAmount);

            document.getElementById('subtotalDisplay').textContent = `Rp ${formatNumber(subtotal)}`;
            document.getElementById('discountDisplay').textContent = `Rp ${formatNumber(discountAmount)}`;
            document.getElementById('totalDisplay').textContent = `Rp ${formatNumber(totalAmount)}`;

            // Handle DP and payment breakdown
            const jenisTransaksi = document.getElementById('jenisTransaksi').value;
            const dpAmount = parseFormattedNumber(document.getElementById('dpAmount').value) || 0;
            const paymentBreakdown = document.getElementById('paymentBreakdown');
            const dpDisplay = document.getElementById('dpDisplay');
            const remainingDisplay = document.getElementById('remainingDisplay');

            if (jenisTransaksi === 'kredit' && dpAmount > 0) {
                paymentBreakdown.classList.remove('hidden');
                dpDisplay.textContent = `Rp ${formatNumber(dpAmount)}`;

                const remaining = Math.max(0, totalAmount - dpAmount);
                remainingDisplay.textContent = `Rp ${formatNumber(remaining)}`;
            } else if (jenisTransaksi === 'tunai' && dpAmount > 0) {
                paymentBreakdown.classList.remove('hidden');
                dpDisplay.textContent = `Rp ${formatNumber(dpAmount)}`;
                remainingDisplay.textContent = `Rp 0`;
            } else {
                paymentBreakdown.classList.add('hidden');
            }
        }

        // Initialize discount input
        function initializeDiscountInput() {
            const discountInput = document.getElementById('diskonDisplay');

            setupNumberInput(discountInput);

            discountInput.addEventListener('input', function() {
                discountAmount = parseFormattedNumber(this.value);
                document.getElementById('diskon').value = discountAmount;
                calculateTotals();
            });
        }

        // Transaction type functionality
        function initializeTransactionType() {
            const jenisTransaksi = document.getElementById('jenisTransaksi');
            const dpContainer = document.getElementById('dpContainer');
            const dpAmountDisplay = document.getElementById('dpAmountDisplay');
            const dpAmount = document.getElementById('dpAmount');

            // Handle transaction type change
            jenisTransaksi.addEventListener('change', function() {
                if (this.value === 'kredit') {
                    dpContainer.classList.remove('hidden');
                    dpAmountDisplay.required = true;
                } else {
                    dpContainer.classList.add('hidden');
                    dpAmountDisplay.required = false;
                    dpAmountDisplay.value = '0';
                    dpAmount.value = 0;

                    // Auto-fill payment for tunai (cash) transactions
                    if (this.value === 'tunai') {
                        autoFillCashPayment();
                    }
                }
                calculateTotals();
            });

            // Auto-fill cash payment function
            function autoFillCashPayment() {
                // Get the current total
                const totalText = document.getElementById('totalDisplay').textContent;
                const totalAmount = parseFormattedNumber(totalText.replace('Rp ', ''));

                if (totalAmount > 0) {
                    // Set DP amount to equal the total for cash transactions
                    dpAmountDisplay.value = formatNumber(totalAmount);
                    dpAmount.value = totalAmount;

                    // Update the summary to reflect the payment
                    calculateTotals();

                    // Show a subtle notification
                    showToast('Pembayaran tunai otomatis diisi sesuai total', 'success');
                }
            }

            // Set initial DP visibility based on old value
            @if (old('jenis_transaksi') == 'kredit')
                dpContainer.classList.remove('hidden');
                dpAmountDisplay.required = true;
            @else
                // Auto-fill payment for initial cash transactions
                if (jenisTransaksi.value === 'tunai') {
                    // Delay to ensure DOM is ready
                    setTimeout(() => {
                        autoFillCashPayment();
                    }, 100);
                }
            @endif

            // Update DP when amount changes
            dpAmountDisplay.addEventListener('input', function() {
                dpAmount.value = parseFormattedNumber(this.value);
                calculateTotals();
            });

            // Setup number formatting for DP input
            setupNumberInput(dpAmountDisplay);

            // Format initial DP value
            if (dpAmountDisplay.value && dpAmountDisplay.value !== '0') {
                dpAmountDisplay.value = formatNumber(dpAmountDisplay.value);
            }
        }

        // Payment method functionality
        function initializePaymentMethod() {
            const paymentMethodRadios = document.querySelectorAll('input[name="metode_pembayaran"]');
            const paymentMethodCards = document.querySelectorAll('.payment-method-card');

            // Handle payment method selection
            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove active state from all cards
                    paymentMethodCards.forEach(card => {
                        card.classList.remove('border-orange-500', 'bg-orange-50');
                        card.classList.add('border-gray-200');
                    });

                    // Add active state to selected card
                    if (this.checked) {
                        const card = this.closest('.payment-method-option').querySelector(
                            '.payment-method-card');
                        card.classList.remove('border-gray-200');
                        card.classList.add('border-orange-500', 'bg-orange-50');
                    }
                });
            });

            // Handle card click to select radio
            paymentMethodCards.forEach(card => {
                card.addEventListener('click', function() {
                    const radio = this.closest('.payment-method-option').querySelector(
                        'input[type="radio"]');
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                });
            });

            // Set initial selection based on old value
            const initialSelection = document.querySelector('input[name="metode_pembayaran"]:checked');
            if (initialSelection) {
                initialSelection.dispatchEvent(new Event('change'));
            }
        }

        // Form submission
        function initializeFormSubmission() {
            const form = document.getElementById('purchaseForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (cart.length === 0) {
                    showToast('Keranjang masih kosong!', 'error');
                    return;
                }

                if (!document.getElementById('supplierId').value) {
                    showToast('Pilih supplier terlebih dahulu!', 'error');
                    return;
                }

                // Validate payment method
                const selectedPaymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
                if (!selectedPaymentMethod) {
                    showToast('Pilih metode pembayaran terlebih dahulu!', 'error');
                    return;
                }

                // Validate total amount
                if (totalAmount <= 0) {
                    showToast('Total pembelian harus lebih dari 0!', 'error');
                    return;
                }

                // Add cart items to form
                cart.forEach((item, index) => {
                    const productIdInput = document.createElement('input');
                    productIdInput.type = 'hidden';
                    productIdInput.name = `items[${index}][produk_id]`;
                    productIdInput.value = item.id;
                    form.appendChild(productIdInput);

                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = `items[${index}][qty]`;
                    qtyInput.value = item.qty;
                    form.appendChild(qtyInput);

                    const priceInput = document.createElement('input');
                    priceInput.type = 'hidden';
                    priceInput.name = `items[${index}][harga_beli]`;
                    priceInput.value = item.price;
                    form.appendChild(priceInput);

                    const discountInput = document.createElement('input');
                    discountInput.type = 'hidden';
                    discountInput.name = `items[${index}][discount]`;
                    discountInput.value = item.discount;
                    form.appendChild(discountInput);
                });

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;

                // Submit form
                this.submit();
            });
        }

        // Utility functions for number formatting
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Format number input with thousand separator
        function formatNumberInput(value) {
            // Remove all non-digit characters
            const numericValue = value.toString().replace(/\D/g, '');
            // Format with thousand separator
            return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function formatCurrency(value) {
            // Remove non-digits
            let cleanValue = value.replace(/[^\d]/g, '');

            // Format with thousand separators
            if (cleanValue) {
                return new Intl.NumberFormat('id-ID').format(parseInt(cleanValue));
            }
            return '';
        }

        function parseCurrency(formattedValue) {
            return parseFloat(formattedValue.replace(/[^\d]/g, '')) || 0;
        }

        // Setup number input with Indonesian formatting
        function setupNumberInput(input) {
            input.addEventListener('input', function(e) {
                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;
                const newValue = formatNumberInput(e.target.value);

                e.target.value = newValue;

                // Adjust cursor position
                const diff = newValue.length - oldValue.length;
                e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
            });

            input.addEventListener('blur', function(e) {
                if (e.target.value === '' || e.target.value === '0') {
                    e.target.value = '0';
                }
            });
        }

        // Setup decimal input formatting
        function setupDecimalInput(input) {
            let isFormatting = false;
            let lastValidValue = '1';

            input.addEventListener('input', function(e) {
                if (isFormatting) return;

                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;

                // Get the raw numeric value by removing all formatting
                let rawValue = e.target.value.replace(/[^\d,]/g, ''); // Keep digits and comma only

                // If input is empty, don't format yet
                if (!rawValue) {
                    return;
                }

                // Handle decimal separator (comma)
                let hasDecimal = rawValue.includes(',');
                let integerPart = '';
                let decimalPart = '';

                if (hasDecimal) {
                    let parts = rawValue.split(',');
                    integerPart = parts[0] || '';
                    decimalPart = parts[1] || '';

                    // If multiple commas, keep only the first one
                    if (parts.length > 2) {
                        decimalPart = parts.slice(1).join('');
                    }
                } else {
                    integerPart = rawValue;
                }

                // Format the value
                let newValue = '';
                if (integerPart) {
                    // Add thousand separators to integer part
                    if (integerPart.length >= 4) {
                        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }
                    newValue = integerPart;
                }

                // Add decimal part if exists
                if (hasDecimal) {
                    newValue += ',' + decimalPart;
                }

                // Store last valid value
                let numericValue = parseFormattedDecimal(newValue);
                if (numericValue > 0) {
                    lastValidValue = newValue;
                }

                if (newValue !== oldValue) {
                    isFormatting = true;
                    e.target.value = newValue;

                    // Calculate new cursor position
                    let newCursorPos = cursorPosition;

                    // Count dots before cursor in old and new values
                    let oldDots = (oldValue.substring(0, cursorPosition).match(/\./g) || []).length;
                    let newDots = (newValue.substring(0, cursorPosition).match(/\./g) || []).length;

                    // Adjust cursor position based on dot difference
                    if (newValue.length > oldValue.length) {
                        // Text was added (likely a dot for thousand separator)
                        let addedDots = (newValue.match(/\./g) || []).length - (oldValue.match(/\./g) || []).length;
                        if (addedDots > 0) {
                            // Find where the dot was added relative to cursor
                            let textBeforeCursor = oldValue.substring(0, cursorPosition);
                            let digitsBeforeCursor = textBeforeCursor.replace(/[^\d]/g, '').length;

                            // Count how many dots should be before this many digits in the new value
                            let newTextUpToDigits = newValue.replace(/[^\d.]/g, '');
                            let dotsBeforeDigits = 0;
                            let digitCount = 0;

                            for (let i = 0; i < newTextUpToDigits.length && digitCount < digitsBeforeCursor; i++) {
                                if (newTextUpToDigits[i] === '.') {
                                    dotsBeforeDigits++;
                                } else {
                                    digitCount++;
                                }
                            }

                            newCursorPos = cursorPosition + dotsBeforeDigits - oldDots;
                        }
                    }

                    // Make sure cursor position is within bounds
                    newCursorPos = Math.max(0, Math.min(newCursorPos, newValue.length));

                    e.target.setSelectionRange(newCursorPos, newCursorPos);
                    isFormatting = false;
                }
            });

            input.addEventListener('blur', function(e) {
                let inputValue = e.target.value.trim();

                // If empty, set to last valid value or 1
                if (!inputValue) {
                    e.target.value = lastValidValue || '1';
                    return;
                }

                let value = parseFormattedDecimal(inputValue);
                if (value <= 0 || isNaN(value)) {
                    // Use last valid value instead of defaulting to 1
                    e.target.value = lastValidValue || '1';
                } else {
                    // Format the value properly
                    let formattedValue = formatDecimalInput(value);
                    e.target.value = formattedValue;
                    lastValidValue = formattedValue;
                }
            });

            input.addEventListener('focus', function(e) {
                if (e.target.value === '1') {
                    e.target.select();
                }
            });
        }

        function parseFormattedNumber(value) {
            if (!value) return 0;

            // Remove thousand separators (dots) and convert comma to dot for decimal
            let cleanValue = value.toString()
                .replace(/\./g, '') // Remove thousand separators (dots)
                .replace(',', '.'); // Convert comma to dot for decimal

            let numValue = parseFloat(cleanValue);
            return isNaN(numValue) ? 0 : numValue;
        }

        function parseFormattedDecimal(value) {
            if (!value) return 0;

            // Remove thousand separators (dots) and convert comma to dot for decimal
            let cleanValue = value.toString()
                .replace(/\./g, '') // Remove thousand separators (dots)
                .replace(',', '.'); // Convert comma to dot for decimal

            let numValue = parseFloat(cleanValue);
            return isNaN(numValue) ? 0 : numValue;
        }

        // Format decimal number with thousand separator and comma for decimal
        function formatDecimalInput(value) {
            if (!value && value !== 0) return '';

            // Convert to string first
            let strValue = value.toString();

            // If value already contains comma, handle it carefully
            if (strValue.includes(',')) {
                let parts = strValue.split(',');
                let integerPart = parts[0];
                let decimalPart = parts[1] || '';

                // Remove existing dots from integer part and format
                integerPart = integerPart.replace(/\./g, '');

                // Format integer part with thousand separator
                if (integerPart && integerPart !== '0') {
                    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                // Return with decimal part
                if (decimalPart !== '') {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart;
                }
            } else {
                // Handle numeric input
                let numValue;
                if (typeof value === 'number') {
                    numValue = value;
                } else {
                    // Convert string to number, handle both comma and dot as decimal separator
                    let cleanValue = strValue.replace(',', '.');
                    numValue = parseFloat(cleanValue);
                }

                if (isNaN(numValue)) return '';

                // Split integer and decimal parts
                let parts = numValue.toString().split('.');
                let integerPart = parts[0];
                let decimalPart = parts[1] || '';

                // Format integer part with thousand separator
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Return formatted number
                if (decimalPart) {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart;
                }
            }
        }

        // Real-time validation functions
        function validateQuantityInput() {
            const qtyInput = document.getElementById('quantityInput');
            const value = qtyInput.value.trim();

            if (!value) {
                qtyInput.style.borderColor = '#ef4444';
                qtyInput.style.backgroundColor = '#fef2f2';
                return false;
            }

            const qty = parseFormattedDecimal(value);
            if (qty <= 0) {
                qtyInput.style.borderColor = '#ef4444';
                qtyInput.style.backgroundColor = '#fef2f2';
                return false;
            }

            qtyInput.style.borderColor = '#10b981';
            qtyInput.style.backgroundColor = '#f0fdf4';
            return true;
        }

        function validatePriceInput() {
            const priceInput = document.getElementById('modalPriceInput');
            const value = priceInput.value.trim();

            if (!value) {
                priceInput.style.borderColor = '#ef4444';
                priceInput.style.backgroundColor = '#fef2f2';
                return false;
            }

            const price = parseFormattedNumber(value);
            if (price <= 0) {
                priceInput.style.borderColor = '#ef4444';
                priceInput.style.backgroundColor = '#fef2f2';
                return false;
            }

            priceInput.style.borderColor = '#10b981';
            priceInput.style.backgroundColor = '#f0fdf4';
            return true;
        }

        function validateDiscountInput() {
            const discountInput = document.getElementById('discountInput');
            const qtyInput = document.getElementById('quantityInput');
            const priceInput = document.getElementById('modalPriceInput');

            const value = discountInput.value.trim();
            const qty = parseFormattedDecimal(qtyInput.value) || 0;
            const price = parseFormattedNumber(priceInput.value) || 0;
            const discount = parseFormattedNumber(value) || 0;

            if (value && discount > 0) {
                const subtotal = qty * price;
                if (discount > subtotal && subtotal > 0) {
                    discountInput.style.borderColor = '#ef4444';
                    discountInput.style.backgroundColor = '#fef2f2';
                    return false;
                }
            }

            if (value && discount >= 0) {
                discountInput.style.borderColor = '#10b981';
                discountInput.style.backgroundColor = '#f0fdf4';
            } else {
                discountInput.style.borderColor = '#d1d5db';
                discountInput.style.backgroundColor = '#ffffff';
            }

            return true;
        }

        // Reset validation styles
        function resetValidationStyles() {
            const qtyInput = document.getElementById('quantityInput');
            const priceInput = document.getElementById('modalPriceInput');
            const discountInput = document.getElementById('discountInput');

            // Reset to default styles
            qtyInput.style.borderColor = '#d1d5db';
            qtyInput.style.backgroundColor = '#ffffff';
            priceInput.style.borderColor = '#d1d5db';
            priceInput.style.backgroundColor = '#ffffff';
            discountInput.style.borderColor = '#d1d5db';
            discountInput.style.backgroundColor = '#ffffff';
        }

        // Barcode search
        function searchByBarcode(barcode) {
            if (barcode.length < 3) return;

            const productCards = document.querySelectorAll('.product-card');
            let found = false;

            productCards.forEach(card => {
                if (card.dataset.code === barcode) {
                    card.click();
                    found = true;
                    document.getElementById('barcodeSearch').value = '';
                    showToast(`Produk ditemukan: ${card.dataset.name}`, 'success');
                }
            });

            if (!found && barcode.length > 5) {
                showToast(`Barcode "${barcode}" tidak ditemukan`, 'error');
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to submit form
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                const form = document.getElementById('purchaseForm');
                if (form) {
                    form.dispatchEvent(new Event('submit'));
                }
            }

            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('productSearch').focus();
            }

            // Ctrl/Cmd + B to focus barcode search
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                document.getElementById('barcodeSearch').focus();
            }
        });

        // Auto-focus search on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.getElementById('productSearch').focus();
            }, 500);
        });

        // Order Preview Modal Functions
        const orderPreviewModal = document.getElementById('orderPreviewModal');
        const closeOrderPreviewModal = document.getElementById('closeOrderPreviewModal');
        const closePreviewModalBtn = document.getElementById('closePreviewModal');
        const showOrderPreviewBtn = document.getElementById('showOrderPreview');
        const confirmOrderSaveBtn = document.getElementById('confirmOrderSave');

        function closePreviewModal() {
            orderPreviewModal.classList.add('hidden');
        }

        // Close modal events
        closeOrderPreviewModal.addEventListener('click', closePreviewModal);
        closePreviewModalBtn.addEventListener('click', closePreviewModal);

        orderPreviewModal.addEventListener('click', (e) => {
            if (e.target === orderPreviewModal) {
                closePreviewModal();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !orderPreviewModal.classList.contains('hidden')) {
                closePreviewModal();
            }
        });

        // Show order preview
        showOrderPreviewBtn.addEventListener('click', function() {
            if (cart.length === 0) {
                showToast('Pilih produk terlebih dahulu', 'error');
                return;
            }

            const supplierId = document.getElementById('supplierId');
            const supplierDisplay = document.getElementById('supplierDisplay');

            if (!supplierId || !supplierId.value) {
                showToast('Pilih supplier terlebih dahulu', 'error');
                return;
            }

            if (!supplierDisplay || !supplierDisplay.value) {
                showToast('Supplier belum dipilih dengan benar', 'error');
                return;
            }

            showOrderPreview();
        });

        function showOrderPreview() {
            // Get supplier info
            const supplierName = document.getElementById('supplierDisplay').value || 'Belum dipilih';
            const selectedSupplier = document.querySelector('.supplier-item.bg-orange-100');

            document.getElementById('previewSupplierName').textContent = supplierName;
            document.getElementById('previewSupplierCode').textContent = selectedSupplier ?
                selectedSupplier.dataset.code : '-';

            // Get transaction info
            document.getElementById('previewInvoiceNumber').textContent = document.getElementById('noFaktur').value;
            // Get date from hidden input which has Y-m-d format
            const dateValue = document.querySelector('input[name="tanggal"]').value;
            if (dateValue) {
                document.getElementById('previewDate').textContent = new Date(dateValue)
                    .toLocaleDateString('id-ID');
            } else {
                document.getElementById('previewDate').textContent = '-';
            }

            const jenisTransaksi = document.getElementById('jenisTransaksi').value;
            document.getElementById('previewTransactionType').textContent = jenisTransaksi === 'kredit' ? 'Kredit' :
                'Tunai';

            // Initialize modal form with current values (no auto-select for transaction type)
            // User must choose transaction type manually
            updatePreviewTransactionTypeCards();

            // Set DP amount based on transaction type (will be updated when user selects transaction type)
            document.getElementById('previewDpAmount').value = '';
            document.getElementById('previewDpAmount').readOnly = false;
            document.getElementById('previewDpAmount').placeholder = 'Jumlah DP (Rp)';

            // Show/hide DP container based on transaction type (will be updated when user selects transaction type)
            const previewDpContainer = document.getElementById('previewDpContainer');
            previewDpContainer.classList.add('hidden'); // Hide initially until user selects transaction type

            // Populate order items
            const previewOrderItems = document.getElementById('previewOrderItems');
            previewOrderItems.innerHTML = '';

            cart.forEach(item => {
                const subtotal = item.price * item.qty;
                const discount = item.discount || 0;
                const total = subtotal - discount;

                const itemHtml = `
                    <div class="bg-white border border-gray-200 rounded-lg p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h5 class="font-medium text-gray-900 text-sm">${item.name}</h5>
                                <p class="text-xs text-gray-500">${item.code}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">${formatDecimalInput(item.qty)} ${item.unit}</p>
                                <p class="text-xs text-gray-500">@ Rp ${formatNumber(item.price)}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-100 space-y-1">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Subtotal</span>
                                <span>Rp ${formatNumber(subtotal)}</span>
                            </div>
                            ${discount > 0 ? `
                                                                                    <div class="flex justify-between text-xs">
                                                                                        <span class="text-orange-600">Potongan</span>
                                                                                        <span class="text-orange-600">-Rp ${formatNumber(discount)}</span>
                                                                                    </div>
                                                                                ` : ''}
                            <div class="flex justify-between text-sm font-medium">
                                <span>Total</span>
                                <span class="text-blue-600">Rp ${formatNumber(total)}</span>
                            </div>
                        </div>
                    </div>
                `;
                previewOrderItems.insertAdjacentHTML('beforeend', itemHtml);
            });

            // Calculate totals
            const subtotalItems = cart.reduce((total, item) => {
                const itemSubtotal = item.price * item.qty;
                const itemDiscount = item.discount || 0;
                return total + (itemSubtotal - itemDiscount);
            }, 0);
            const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
            const total = subtotalItems - discount;

            // Update summary
            document.getElementById('previewSubtotal').textContent = `Rp ${formatNumber(subtotalItems)}`;

            const previewDiscountRow = document.getElementById('previewDiscountRow');
            if (discount > 0) {
                previewDiscountRow.style.display = 'flex';
                document.getElementById('previewDiscount').textContent = `Rp ${formatNumber(discount)}`;
            } else {
                previewDiscountRow.style.display = 'none';
            }

            document.getElementById('previewTotal').textContent = `Rp ${formatNumber(total)}`;

            // Show/hide payment breakdown for kredit
            const previewPaymentBreakdown = document.getElementById('previewPaymentBreakdown');
            if (jenisTransaksi === 'kredit') {
                const dpAmount = parseFormattedNumber(document.getElementById('dpAmount').value);
                const remaining = Math.max(0, total - dpAmount);

                previewPaymentBreakdown.classList.remove('hidden');
                document.getElementById('previewDpDisplay').textContent = `Rp ${formatNumber(dpAmount)}`;
                document.getElementById('previewRemainingDisplay').textContent = `Rp ${formatNumber(remaining)}`;
            } else {
                previewPaymentBreakdown.classList.add('hidden');
            }

            // Show modal
            orderPreviewModal.classList.remove('hidden');

            // Initialize kas/bank filter based on payment method
            filterKasBankByPaymentMethod();

            // Show message initially when no payment method selected
            const kasBankMessage = document.getElementById('kasBankMessage');
            const selectedPaymentMethod = document.querySelector('input[name="preview_metode_pembayaran"]:checked');
            if (!selectedPaymentMethod) {
                kasBankMessage.classList.remove('hidden');
            }

            // Uncheck any selected kas/bank when modal opens
            const selectedKasBankRadio = document.querySelector('.preview-kas-bank-radio:checked');
            if (selectedKasBankRadio) {
                selectedKasBankRadio.checked = false;
                updatePreviewKasBankCards();
            }
        }

        // Initialize transaction type elements
        const previewTransactionTypeRadios = document.querySelectorAll('input[name="preview_jenis_transaksi"]');
        const previewTransactionTypeCards = document.querySelectorAll('.preview-transaction-type-card');

        // Transaction type change handler
        previewTransactionTypeRadios.forEach(radio => {
            radio.addEventListener('change', handleTransactionTypeChange);
        });

        // Add click event to transaction type cards for better UX
        previewTransactionTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.closest('.preview-transaction-type-option').querySelector(
                    'input[type="radio"]');
                radio.checked = true;
                handleTransactionTypeChange();
            });
        });

        function handleTransactionTypeChange() {
            const selectedType = document.querySelector('input[name="preview_jenis_transaksi"]:checked');
            const dpContainer = document.getElementById('previewDpContainer');
            const dpAmount = document.getElementById('previewDpAmount');
            const total = parseFormattedNumber(document.getElementById('previewTotal').textContent.replace('Rp ', '')
                .replace(/\./g, ''));

            if (selectedType && selectedType.value === 'tunai') {
                dpContainer.classList.remove('hidden');
                dpAmount.required = false;
                dpAmount.value = formatNumberInput(total.toString());
                dpAmount.readOnly = true;
                dpAmount.placeholder = 'Jumlah (Rp)';
            } else if (selectedType && selectedType.value === 'kredit') {
                dpContainer.classList.remove('hidden');
                dpAmount.required = true;
                dpAmount.value = '';
                dpAmount.readOnly = false;
                dpAmount.placeholder = 'Jumlah DP (Rp)';
                dpAmount.focus();
            }

            updatePreviewTransactionTypeCards();
        }

        // Initialize payment method elements
        const previewPaymentMethodRadios = document.querySelectorAll('input[name="preview_metode_pembayaran"]');
        const previewPaymentMethodCards = document.querySelectorAll('.preview-payment-method-card');

        // Payment method change handler
        previewPaymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                filterKasBankByPaymentMethod();
                updatePreviewPaymentMethodCards();
            });
        });

        function filterKasBankByPaymentMethod() {
            const selectedPaymentMethod = document.querySelector('input[name="preview_metode_pembayaran"]:checked');
            const kasBankMessage = document.getElementById('kasBankMessage');
            const kasBankContainer = document.getElementById('previewKasBankContainer');

            if (!selectedPaymentMethod) {
                // If no payment method selected, hide all kas/bank and show message
                previewKasBankCards.forEach((card, index) => {
                    card.classList.add('hidden');
                });
                kasBankMessage.classList.remove('hidden');
                return;
            }

            // Hide message when payment method is selected
            kasBankMessage.classList.add('hidden');

            const paymentMethodCode = selectedPaymentMethod.value;
            const isTransfer = paymentMethodCode.toLowerCase().includes('transfer') ||
                paymentMethodCode.toLowerCase().includes('bank') ||
                paymentMethodCode.toLowerCase().includes('bca') ||
                paymentMethodCode.toLowerCase().includes('mandiri') ||
                paymentMethodCode.toLowerCase().includes('bni') ||
                paymentMethodCode.toLowerCase().includes('bri');
            const isCash = paymentMethodCode.toLowerCase().includes('cash') ||
                paymentMethodCode.toLowerCase().includes('tunai') ||
                paymentMethodCode.toLowerCase().includes('kas');

            let visibleCount = 0;

            previewKasBankCards.forEach((card, index) => {
                const radio = previewKasBankRadios[index];
                const kasBankJenis = radio.getAttribute('data-jenis');

                if (isTransfer && kasBankJenis === 'BANK') {
                    // Show only BANK for transfer methods
                    card.classList.remove('hidden');
                    visibleCount++;
                } else if (isCash && kasBankJenis === 'KAS') {
                    // Show only KAS for cash methods
                    card.classList.remove('hidden');
                    visibleCount++;
                } else if (!isTransfer && !isCash) {
                    // If payment method is not clearly transfer or cash, show all
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    // Hide the card
                    card.classList.add('hidden');
                }
            });

            // Update grid columns based on visible count
            if (visibleCount === 1) {
                kasBankContainer.className = 'grid gap-3 grid-cols-1';
            } else if (visibleCount === 2) {
                kasBankContainer.className = 'grid gap-3 grid-cols-2';
            } else if (visibleCount === 3) {
                kasBankContainer.className = 'grid gap-3 grid-cols-3';
            } else if (visibleCount >= 4) {
                kasBankContainer.className = 'grid gap-3 grid-cols-4';
            }

            // Uncheck any hidden kas/bank selections
            previewKasBankRadios.forEach((radio, index) => {
                const card = previewKasBankCards[index];
                if (card.classList.contains('hidden') && radio.checked) {
                    radio.checked = false;
                    updatePreviewKasBankCards();
                }
            });
        }

        // Initialize kas/bank elements
        const previewKasBankRadios = document.querySelectorAll('.preview-kas-bank-radio');
        const previewKasBankCards = document.querySelectorAll('.preview-kas-bank-card');

        // Kas/Bank selection handler
        previewKasBankRadios.forEach(radio => {
            radio.addEventListener('change', updatePreviewKasBankCards);
        });

        // Add click event to kas/bank cards for better UX
        previewKasBankCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.closest('.preview-kas-bank-option').querySelector('input[type="radio"]');
                radio.checked = true;
                updatePreviewKasBankCards();
            });
        });

        function updatePreviewKasBankCards() {
            previewKasBankCards.forEach(card => {
                card.classList.remove('border-orange-500', 'bg-orange-50');
                card.classList.add('border-gray-200');
            });

            const selectedRadio = document.querySelector('input[name="preview_kas_bank_id"]:checked');
            if (selectedRadio) {
                const selectedCard = selectedRadio.parentElement.querySelector('.preview-kas-bank-card');
                selectedCard.classList.remove('border-gray-200');
                selectedCard.classList.add('border-orange-500', 'bg-orange-50');
            }
        }

        // Update transaction type cards
        function updatePreviewTransactionTypeCards() {
            previewTransactionTypeCards.forEach(card => {
                card.classList.remove('border-orange-500', 'bg-orange-50');
                card.classList.add('border-gray-200');
            });

            const selectedRadio = document.querySelector('input[name="preview_jenis_transaksi"]:checked');
            if (selectedRadio) {
                const selectedCard = selectedRadio.parentElement.querySelector('.preview-transaction-type-card');
                selectedCard.classList.remove('border-gray-200');
                selectedCard.classList.add('border-orange-500', 'bg-orange-50');
            }
        }

        // Update payment method cards
        function updatePreviewPaymentMethodCards() {
            previewPaymentMethodCards.forEach(card => {
                card.classList.remove('border-orange-500', 'bg-orange-50');
                card.classList.add('border-gray-200');
            });

            const selectedRadio = document.querySelector('input[name="preview_metode_pembayaran"]:checked');
            if (selectedRadio) {
                const selectedCard = selectedRadio.parentElement.querySelector('.preview-payment-method-card');
                selectedCard.classList.remove('border-gray-200');
                selectedCard.classList.add('border-orange-500', 'bg-orange-50');
            }
        }

        // Add click event to payment method cards for better UX
        previewPaymentMethodCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.closest('.preview-payment-method-option').querySelector(
                    'input[type="radio"]');
                radio.checked = true;
                filterKasBankByPaymentMethod();
                updatePreviewPaymentMethodCards();
            });
        });

        // Setup number formatting for DP amount input
        setupNumberInput(document.getElementById('previewDpAmount'));

        // DP amount input handler
        document.getElementById('previewDpAmount').addEventListener('input', function(e) {
            // setupNumberInput already handles formatting, so we don't need to format manually
            const dpAmount = parseFormattedNumber(e.target.value);
            const total = parseFormattedNumber(document.getElementById('previewTotal').textContent.replace('Rp ',
                '').replace(/\./g, ''));
            const remaining = Math.max(0, total - dpAmount);

            document.getElementById('previewDpDisplay').textContent = `Rp ${formatNumber(dpAmount)}`;
            document.getElementById('previewRemainingDisplay').textContent = `Rp ${formatNumber(remaining)}`;

            if (dpAmount > 0) {
                document.getElementById('previewPaymentBreakdown').classList.remove('hidden');
            } else {
                document.getElementById('previewPaymentBreakdown').classList.add('hidden');
            }
        });

        // Confirm order save
        confirmOrderSaveBtn.addEventListener('click', function() {
            const selectedTransactionType = document.querySelector('input[name="preview_jenis_transaksi"]:checked');
            const selectedPaymentMethod = document.querySelector('input[name="preview_metode_pembayaran"]:checked');
            const selectedKasBank = document.querySelector('input[name="preview_kas_bank_id"]:checked');

            if (!selectedTransactionType) {
                showToast('Pilih jenis transaksi', 'error');
                highlightCard('preview-transaction-type-card');
                return;
            }

            if (!selectedPaymentMethod) {
                showToast('Pilih metode pembayaran', 'error');
                highlightCard('preview-payment-method-card');
                return;
            }

            if (!selectedKasBank) {
                showToast('Pilih kas/bank', 'error');
                highlightCard('preview-kas-bank-card');
                return;
            }

            // Validate cart items
            if (cart.length === 0) {
                showToast('Minimal harus ada 1 produk!', 'error');
                return;
            }

            // Validate supplier
            if (!document.getElementById('supplierId').value) {
                showToast('Pilih supplier terlebih dahulu!', 'error');
                return;
            }

            // Validate total amount
            const total = parseFormattedNumber(document.getElementById('previewTotal').textContent.replace('Rp ',
                '').replace(/\./g, ''));
            if (total <= 0) {
                showToast('Total pembelian harus lebih dari 0!', 'error');
                return;
            }

            // Update hidden inputs
            document.getElementById('jenisTransaksi').value = selectedTransactionType.value;
            document.getElementById('metodePembayaran').value = selectedPaymentMethod.value;
            document.getElementById('kasBankId').value = selectedKasBank.value;

            const dpAmount = parseFormattedNumber(document.getElementById('previewDpAmount').value);
            document.getElementById('dpAmount').value = dpAmount;

            // Add cart items to form
            const form = document.getElementById('purchaseForm');

            // Remove any existing cart item inputs
            form.querySelectorAll('input[name^="items["]').forEach(input => input.remove());

            // Add cart items to form
            cart.forEach((item, index) => {
                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = `items[${index}][produk_id]`;
                productIdInput.value = item.id;
                form.appendChild(productIdInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `items[${index}][qty]`;
                qtyInput.value = item.qty;
                form.appendChild(qtyInput);

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = `items[${index}][harga_beli]`;
                priceInput.value = item.price;
                form.appendChild(priceInput);

                const discountInput = document.createElement('input');
                discountInput.type = 'hidden';
                discountInput.name = `items[${index}][discount]`;
                discountInput.value = item.discount || 0;
                form.appendChild(discountInput);
            });

            // Log form data for debugging
            console.log('Submitting form with data:', {
                no_faktur: document.getElementById('noFaktur').value,
                supplier_id: document.getElementById('supplierId').value,
                tanggal: document.querySelector('input[name="tanggal"]').value,
                jenis_transaksi: document.getElementById('jenisTransaksi').value,
                metode_pembayaran: document.getElementById('metodePembayaran').value,
                kas_bank_id: document.getElementById('kasBankId').value,
                dp_amount: document.getElementById('dpAmount').value,
                diskon: document.getElementById('diskon').value,
                cart_items: cart.length
            });

            // Show loading overlay
            const button = this;
            const originalText = button.innerHTML;
            const cancelButton = document.getElementById('closePreviewModal');

            // Disable both buttons and show loading state
            button.disabled = true;
            cancelButton.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            cancelButton.classList.add('opacity-50', 'cursor-not-allowed');

            // Add loading overlay to modal content
            const modalContent = orderPreviewModal.querySelector('.bg-white');
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className =
                'absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50 rounded-2xl';
            loadingOverlay.innerHTML = `
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                        <i class="ti ti-loader animate-spin text-2xl text-orange-600"></i>
                    </div>
                    <p class="text-lg font-semibold text-gray-800" id="loadingText">Memvalidasi data...</p>
                    <p class="text-sm text-gray-500 mt-1">Mohon tunggu sebentar</p>
                    
                    <!-- Progress Bar -->
                    <div class="w-64 bg-gray-200 rounded-full h-2 mt-4 mx-auto">
                        <div id="progressBar" class="bg-orange-600 h-2 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2" id="progressText">0%</p>
                </div>
            `;
            modalContent.appendChild(loadingOverlay);

            // Animation sequence for better UX
            const loadingStates = [{
                    text: 'Memvalidasi data...',
                    buttonText: '<i class="ti ti-loader animate-spin mr-2"></i>Memvalidasi...',
                    progress: 25,
                    delay: 0
                },
                {
                    text: 'Menyimpan ke database...',
                    buttonText: '<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...',
                    progress: 60,
                    delay: 1000
                },
                {
                    text: 'Berhasil tersimpan!',
                    buttonText: '<i class="ti ti-check animate-pulse mr-2"></i>Berhasil!',
                    progress: 90,
                    delay: 2000
                },
                {
                    text: 'Mengalihkan halaman...',
                    buttonText: '<i class="ti ti-check animate-pulse mr-2"></i>Mengalihkan...',
                    progress: 100,
                    delay: 2500
                }
            ];

            // Execute loading animation sequence
            loadingStates.forEach((state, index) => {
                setTimeout(() => {
                    // Update overlay text
                    const loadingText = document.getElementById('loadingText');
                    const progressBar = document.getElementById('progressBar');
                    const progressText = document.getElementById('progressText');

                    if (loadingText) {
                        loadingText.textContent = state.text;
                    }

                    // Update progress bar
                    if (progressBar) {
                        progressBar.style.width = state.progress + '%';
                    }
                    if (progressText) {
                        progressText.textContent = state.progress + '%';
                    }

                    // Update button text
                    button.innerHTML = state.buttonText;

                    // Change icon color for success states
                    if (state.progress >= 90) {
                        const icon = loadingOverlay.querySelector('.ti-loader');
                        if (icon) {
                            icon.className = 'ti ti-check animate-pulse text-2xl text-green-600';
                        }
                        const iconBg = loadingOverlay.querySelector('.bg-orange-100');
                        if (iconBg) {
                            iconBg.className =
                                'inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4';
                        }
                        if (progressBar) {
                            progressBar.className =
                                'bg-green-600 h-2 rounded-full transition-all duration-1000 ease-out';
                        }
                    }

                    // If this is the last state, submit the form
                    if (index === loadingStates.length - 1) {
                        setTimeout(() => {
                            form.submit();
                        }, 800);
                    }
                }, state.delay);
            });
        });

        function highlightCard(className) {
            const cards = document.querySelectorAll('.' + className);
            cards.forEach(card => {
                card.style.borderColor = '#ef4444';
                card.style.backgroundColor = '#fef2f2';
                setTimeout(() => {
                    card.style.borderColor = '';
                    card.style.backgroundColor = '';
                }, 2000);
            });
        }
    </script>
@endpush
