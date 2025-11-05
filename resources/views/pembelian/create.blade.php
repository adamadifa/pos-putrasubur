@extends('layouts.pos')

@section('title', 'Pembelian Baru')
@section('page-title', 'Pembelian')

@section('content')
    <div class="min-h-screen">
        <!-- Back Button -->
        <div class="px-2 md:px-6 pt-4 md:pt-6 pb-2">
            <a href="{{ route('pembelian.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-orange-600 transition-colors text-sm md:text-base">
                <i class="ti ti-arrow-left text-lg mr-2"></i>
                <span class="hidden md:inline">Kembali ke Daftar Pembelian</span>
                <span class="md:hidden">Kembali</span>
            </a>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mx-2 md:mx-6 mb-4 md:mb-6 bg-red-50 border border-red-200 rounded-xl p-3 md:p-4 shadow-sm">
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

        <!-- Mobile Wizard Stepper -->
        <div class="block lg:hidden mb-4 px-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div id="stepIndicator1"
                            class="w-8 h-8 rounded-full bg-orange-600 text-white flex items-center justify-center text-sm font-semibold transition-all">
                            1
                        </div>
                        <div class="h-1 w-12 md:w-16 bg-gray-200 rounded">
                            <div id="progressBar1" class="h-full bg-orange-600 rounded transition-all" style="width: 0%">
                            </div>
                        </div>
                        <div id="stepIndicator2"
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-semibold transition-all">
                            2
                        </div>
                        <div class="h-1 w-12 md:w-16 bg-gray-200 rounded">
                            <div id="progressBar2" class="h-full bg-orange-600 rounded transition-all" style="width: 0%">
                            </div>
                        </div>
                        <div id="stepIndicator3"
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-semibold transition-all">
                            3
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <h3 id="stepTitle" class="text-sm font-semibold text-gray-900">Langkah 1: Informasi Transaksi</h3>
                    <p id="stepDescription" class="text-xs text-gray-500 mt-1">Isi informasi supplier dan tanggal</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 md:gap-6 px-2 md:px-6">
            <!-- Left Side - Products Menu -->
            <div class="flex-1 order-2 lg:order-1 mobile-step mobile-step-2 hidden lg:block">
                <!-- Mobile: Proceed to Review Button (shown when cart has items) -->
                <div id="proceedToReviewBtnContainer" class="lg:hidden mb-4 hidden">
                    <button type="button" id="proceedToReviewBtn"
                        class="w-full py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-medium hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg">
                        <i class="ti ti-check text-lg mr-2"></i>
                        Lanjut ke Review Pesanan
                        <span id="cartItemCount" class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-sm">0 item</span>
                    </button>
                </div>

                <!-- Category Tabs -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
                    <div class="p-3 md:p-4 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row gap-3 mb-3 md:mb-4">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" id="productSearch" placeholder="Cari produk..."
                                    class="w-full pl-11 pr-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white text-sm md:text-base">
                            </div>
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-scan text-lg text-gray-400"></i>
                                </div>
                                <input type="text" id="barcodeSearch" placeholder="Scan barcode..."
                                    class="w-full pl-11 pr-12 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white text-sm md:text-base">
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
                    <div class="p-3 md:p-4 border-b border-gray-200">
                        <div class="flex space-x-2 overflow-x-auto pb-2 -mb-2">
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4"
                        id="productsGrid">
                        @foreach ($produk as $product)
                            <div class="product-card bg-white border border-gray-200 rounded-lg p-2 md:p-4 hover:shadow-md transition-all duration-200 cursor-pointer group"
                                data-id="{{ $product->id }}" data-name="{{ $product->nama_produk }}"
                                data-code="{{ $product->kode_produk }}" data-price="{{ $product->harga_beli }}"
                                data-unit="{{ $product->satuan->nama ?? '' }}"
                                data-category="{{ $product->kategori->nama ?? '' }}">

                                <!-- Product Image Placeholder -->
                                <div
                                    class="w-full h-20 md:h-24 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg mb-2 md:mb-3 flex items-center justify-center group-hover:from-orange-200 group-hover:to-orange-300 transition-all duration-200">
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
                                    <h3 class="font-semibold text-xs md:text-sm text-gray-900 mb-1 md:mb-2 line-clamp-2">
                                        {{ $product->nama_produk }}</h3>
                                    <p class="text-xs text-gray-500 mb-1 md:mb-2 hidden md:block">
                                        {{ $product->kode_produk }}</p>

                                    <!-- Category Badge -->
                                    <div class="flex items-center justify-center mb-1 md:mb-0">
                                        <div
                                            class="inline-flex items-center px-1.5 md:px-2 py-0.5 md:py-1 rounded-full text-xs font-medium border bg-orange-100 text-orange-800 border-orange-200">
                                            <i class="ti ti-tag text-xs mr-0.5 md:mr-1"></i>
                                            <span
                                                class="truncate max-w-[80px] md:max-w-none">{{ $product->kategori->nama ?? 'Uncategorized' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Button -->
                                <button
                                    class="add-product-btn w-full mt-2 md:mt-3 py-1.5 md:py-2 bg-orange-600 text-white rounded-lg text-xs md:text-sm font-medium hover:bg-orange-700 transition-colors opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                    <i class="ti ti-plus text-xs md:text-sm mr-1"></i>
                                    Tambah
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side - Order Summary -->
            <div class="w-full lg:w-96 order-1 lg:order-2">
                <form action="{{ route('pembelian.store') }}" method="POST" id="purchaseForm">
                    @csrf

                    <!-- Mobile Step 1: Supplier & Invoice Info -->
                    <div
                        class="mobile-step mobile-step-1 lg:hidden bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Transaksi</h3>
                            <button type="button" id="nextToStep2"
                                class="lg:hidden px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                Lanjut <i class="ti ti-arrow-right ml-1"></i>
                            </button>
                        </div>

                        <!-- Invoice Number -->
                        <div class="mb-2">
                            <label class="block text-xs md:text-sm text-gray-700 mb-1">No. Faktur</label>
                            <input type="text" name="no_faktur" id="noFaktur"
                                value="{{ old('no_faktur', $invoiceNumber) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 text-sm md:text-base"
                                placeholder="Nomor Faktur" readonly>
                        </div>

                        <!-- Date -->
                        <div class="mb-2">
                            <label class="block text-xs md:text-sm text-gray-700 mb-1">Tanggal</label>
                            <div class="date-input-wrapper">
                                <input type="text" id="tanggal" value="{{ old('tanggal', date('d/m/Y')) }}"
                                    class="flatpickr-input w-full text-sm md:text-base" placeholder="Pilih tanggal"
                                    required readonly>
                                <i class="ti ti-calendar"></i>
                            </div>
                            <input type="hidden" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>

                        <!-- Supplier -->
                        <div class="mb-2">
                            <label class="block text-xs md:text-sm text-gray-700 mb-1">Supplier <span
                                    class="text-red-500">*</span></label>
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <input type="text" id="supplierDisplay"
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 text-sm md:text-base"
                                        placeholder="Pilih Supplier" readonly>
                                    <button type="button" id="clearSupplierBtn"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors hidden">
                                        <i class="ti ti-x text-lg"></i>
                                    </button>
                                </div>
                                <button type="button" id="searchSupplierBtn"
                                    class="px-3 md:px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="ti ti-search text-base md:text-lg"></i>
                                </button>
                            </div>
                            <input type="hidden" name="supplier_id" id="supplierId" value="{{ old('supplier_id') }}"
                                required>
                        </div>

                        <!-- Info Box -->
                        <div class="mb-3 md:mb-4 p-2 md:p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="ti ti-info-circle text-blue-600 mr-2 mt-0.5"></i>
                                <span class="text-xs md:text-sm text-blue-800">Konfigurasi pembayaran akan diatur pada
                                    preview pesanan</span>
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

                    <!-- Desktop: Supplier & Invoice Info (always visible) -->
                    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h3>
                        <!-- Invoice Number -->
                        <div class="mb-2">
                            <input type="text" name="no_faktur" id="noFakturDesktop"
                                value="{{ old('no_faktur', $invoiceNumber) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                placeholder="Nomor Faktur" readonly>
                        </div>
                        <!-- Date -->
                        <div class="mb-2">
                            <div class="date-input-wrapper">
                                <input type="text" id="tanggalDesktop" value="{{ old('tanggal', date('d/m/Y')) }}"
                                    class="flatpickr-input w-full" placeholder="Pilih tanggal" required readonly>
                                <i class="ti ti-calendar"></i>
                            </div>
                            <input type="hidden" name="tanggal_desktop" value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>
                        <!-- Supplier -->
                        <div class="mb-2">
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <input type="text" id="supplierDisplayDesktop"
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                                        placeholder="Pilih Supplier" readonly>
                                    <button type="button" id="clearSupplierBtnDesktop"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors hidden">
                                        <i class="ti ti-x text-lg"></i>
                                    </button>
                                </div>
                                <button type="button" id="searchSupplierBtnDesktop"
                                    class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="ti ti-search text-lg"></i>
                                </button>
                            </div>
                            <input type="hidden" name="supplier_id_desktop" id="supplierIdDesktop"
                                value="{{ old('supplier_id') }}">
                        </div>
                        <!-- Info Box -->
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="ti ti-info-circle text-blue-600 mr-2 mt-0.5"></i>
                                <span class="text-sm text-blue-800">Konfigurasi pembayaran akan diatur pada preview
                                    pesanan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Step 3: Order Summary & Review -->
                    <div
                        class="mobile-step mobile-step-3 hidden lg:hidden bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Review Pesanan</h3>
                            <button type="button" id="backToStep2"
                                class="lg:hidden px-3 py-1.5 text-orange-600 hover:text-orange-700 transition-colors">
                                <i class="ti ti-arrow-left mr-1"></i> Kembali
                            </button>
                        </div>

                        <!-- Order Items Review -->
                        <div id="orderItemsReview" class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                            <!-- Items will be populated here -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyStateReview" class="text-center py-8 text-gray-500">
                            <i class="ti ti-shopping-cart-off text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada produk dipilih</p>
                        </div>

                        <!-- Discount -->
                        <div class="border-t border-gray-200 pt-4 mb-4">
                            <label class="block text-sm text-gray-700 mb-1">Diskon</label>
                            <input type="text" id="diskonDisplayReview" value="{{ old('diskon', 0) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right"
                                placeholder="Diskon (Rp)">
                            <input type="hidden" name="diskon_review" id="diskonReview"
                                value="{{ old('diskon', 0) }}">
                        </div>

                        <!-- Summary -->
                        <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="subtotalDisplayReview">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="font-medium text-red-600" id="discountDisplayReview">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span id="totalDisplayReview">Rp 0</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="button" id="showOrderPreviewMobile"
                                class="w-full py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors">
                                <i class="ti ti-eye text-lg mr-2"></i>
                                Preview Pesanan
                            </button>
                        </div>
                    </div>

                    <!-- Desktop: Order Summary -->
                    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="flex items-center justify-between mb-3 md:mb-4">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900">Ringkasan Pesanan</h3>
                            <span class="text-xs md:text-sm text-gray-500" id="orderCount">0 item</span>
                        </div>

                        <!-- Order Items -->
                        <div id="orderItems"
                            class="space-y-2 md:space-y-3 mb-3 md:mb-4 max-h-48 md:max-h-64 overflow-y-auto">
                            <!-- Items will be added here dynamically -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="text-center py-6 md:py-8 text-gray-500">
                            <i class="ti ti-shopping-cart-off text-2xl md:text-3xl mb-2"></i>
                            <p class="text-xs md:text-sm">Belum ada produk dipilih</p>
                        </div>

                        <!-- Discount -->
                        <div class="border-t border-gray-200 pt-3 md:pt-4">
                            <label class="block text-xs md:text-sm text-gray-700 mb-1">Diskon</label>
                            <input type="text" id="diskonDisplay" value="{{ old('diskon', 0) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right text-sm md:text-base"
                                placeholder="Diskon (Rp)">
                            <input type="hidden" name="diskon" id="diskon" value="{{ old('diskon', 0) }}">
                        </div>
                    </div>

                    <!-- Desktop: Total Summary -->
                    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="subtotalDisplayDesktop">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="font-medium text-red-600" id="discountDisplayDesktop">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span id="totalDisplayDesktop">Rp 0</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="button" id="showOrderPreviewDesktop"
                                class="w-full py-2.5 md:py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors text-sm md:text-base">
                                <i class="ti ti-eye text-base md:text-lg mr-2"></i>
                                Preview Pesanan
                            </button>
                        </div>
                    </div>

                    <!-- Mobile: Total & Actions (sticky bottom, only visible when not in step 3) -->
                    <div
                        class="lg:hidden bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-4 sticky bottom-0 z-10 mobile-total-actions">
                        <!-- Summary -->
                        <div class="space-y-2 mb-3 md:mb-4">
                            <div class="flex justify-between text-xs md:text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-sm md:text-base" id="subtotalDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-xs md:text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="font-medium text-red-600 text-sm md:text-base" id="discountDisplay">Rp
                                    0</span>
                            </div>
                            <div class="flex justify-between text-base md:text-lg font-bold border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span id="totalDisplay">Rp 0</span>
                            </div>

                            <!-- DP & Remaining Payment (for kredit) -->
                            <div id="paymentBreakdown" class="hidden border-t border-gray-200 pt-2 space-y-2">
                                <div class="flex justify-between text-xs md:text-sm">
                                    <span class="text-green-600">DP Dibayar</span>
                                    <span class="font-medium text-green-600 text-sm md:text-base" id="dpDisplay">Rp
                                        0</span>
                                </div>
                                <div class="flex justify-between text-xs md:text-sm">
                                    <span class="text-orange-600">Sisa Pembayaran</span>
                                    <span class="font-medium text-orange-600 text-sm md:text-base"
                                        id="remainingDisplay">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="button" id="showOrderPreview"
                                class="w-full py-2.5 md:py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors text-sm md:text-base">
                                <i class="ti ti-eye text-base md:text-lg mr-2"></i>
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
        <div
            class="relative top-0 md:top-20 mx-auto p-0 md:p-5 border-0 md:border w-full md:w-11/12 lg:w-1/2 xl:w-2/5 shadow-lg rounded-t-2xl md:rounded-xl bg-white min-h-screen md:min-h-0 flex flex-col">
            <div class="flex-1 overflow-y-auto p-3 md:p-0 md:mt-3">
                <!-- Mobile: Close button at top -->
                <div
                    class="flex items-center justify-between mb-3 md:mb-6 pb-3 md:pb-0 border-b md:border-b-0 border-gray-200">
                    <h3 class="text-base md:text-xl font-bold text-gray-900">Preview Pesanan</h3>
                    <button type="button" id="closeOrderPreviewModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl md:text-2xl"></i>
                    </button>
                </div>

                <!-- Supplier Info -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 md:p-4 mb-4 md:mb-6">
                    <div class="flex items-center space-x-2 md:space-x-3">
                        <div
                            class="w-8 h-8 md:w-10 md:h-10 bg-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-building text-white text-sm md:text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm md:text-base truncate"
                                id="previewSupplierName">-</h4>
                            <p class="text-xs md:text-sm text-gray-600 truncate" id="previewSupplierCode">-</p>
                        </div>
                    </div>
                </div>

                <!-- Transaction Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 mb-4 md:mb-6">
                    <div class="bg-gray-50 rounded-lg p-2 md:p-3">
                        <p class="text-xs text-gray-500 mb-1">No. Faktur</p>
                        <p class="font-semibold text-gray-900 text-sm md:text-base truncate" id="previewInvoiceNumber">-
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2 md:p-3">
                        <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-900 text-sm md:text-base" id="previewDate">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2 md:p-3">
                        <p class="text-xs text-gray-500 mb-1">Jenis Transaksi</p>
                        <p class="font-semibold text-gray-900 text-sm md:text-base" id="previewTransactionType">-</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-4 md:mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2 md:mb-3 text-sm md:text-base">Detail Pesanan</h4>
                    <div class="max-h-48 md:max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-2 md:p-0 md:border-0"
                        id="previewOrderItems">
                        <!-- Items will be populated here -->
                    </div>
                </div>

                <!-- Payment Configuration -->
                <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4 mb-4 md:mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2 md:mb-3 text-sm md:text-base">Konfigurasi Pembayaran</h4>

                    <div class="space-y-3 md:space-y-4">
                        <!-- Jenis Transaksi -->
                        <div>
                            <div class="grid grid-cols-2 gap-2 md:gap-3" id="previewTransactionTypeContainer">
                                <label class="relative cursor-pointer preview-transaction-type-option">
                                    <input type="radio" name="preview_jenis_transaksi" value="tunai"
                                        class="sr-only preview-transaction-type-radio">
                                    <div
                                        class="p-2 md:p-3 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 preview-transaction-type-card">
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-7 h-7 md:w-8 md:h-8 bg-green-100 rounded-lg flex items-center justify-center mb-1 md:mb-2">
                                                <i class="ti ti-cash text-green-600 text-xs md:text-sm"></i>
                                            </div>
                                            <span class="text-xs md:text-sm font-medium text-gray-900">Tunai</span>
                                            <span class="text-xs text-gray-500 hidden md:block">Bayar Langsung</span>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer preview-transaction-type-option">
                                    <input type="radio" name="preview_jenis_transaksi" value="kredit"
                                        class="sr-only preview-transaction-type-radio">
                                    <div
                                        class="p-2 md:p-3 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 preview-transaction-type-card">
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-7 h-7 md:w-8 md:h-8 bg-orange-100 rounded-lg flex items-center justify-center mb-1 md:mb-2">
                                                <i class="ti ti-credit-card text-orange-600 text-xs md:text-sm"></i>
                                            </div>
                                            <span class="text-xs md:text-sm font-medium text-gray-900">Kredit</span>
                                            <span class="text-xs text-gray-500 hidden md:block">Bayar Nanti</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <div class="grid grid-cols-2 md:grid-cols-{{ min(count($metodePembayaran), 4) }} gap-2 md:gap-3"
                                id="previewPaymentMethodContainer">
                                @foreach ($metodePembayaran as $metode)
                                    <label class="relative cursor-pointer preview-payment-method-option">
                                        <input type="radio" name="preview_metode_pembayaran"
                                            value="{{ $metode->kode }}" class="sr-only preview-payment-method-radio">
                                        <div
                                            class="p-2 md:p-3 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 preview-payment-method-card">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-6 h-6 md:w-8 md:h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                                                    <i
                                                        class="ti {{ $metode->icon_display }} text-orange-600 text-xs md:text-sm"></i>
                                                </div>
                                                <span
                                                    class="text-xs md:text-sm font-medium text-gray-900 truncate">{{ $metode->nama }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kas/Bank Selection -->
                        <div>
                            <!-- Message when no payment method selected -->
                            <div id="kasBankMessage" class="text-center py-6 md:py-8 text-gray-500">
                                <i class="ti ti-arrow-up text-xl md:text-2xl mb-2"></i>
                                <p class="text-xs md:text-sm">Pilih metode pembayaran terlebih dahulu untuk melihat pilihan
                                    kas/bank</p>
                            </div>

                            <div class="grid gap-2 md:gap-3" id="previewKasBankContainer">
                                @foreach ($kasBank ?? [] as $kas)
                                    <label class="relative cursor-pointer preview-kas-bank-option">
                                        <input type="radio" name="preview_kas_bank_id" value="{{ $kas->id }}"
                                            data-jenis="{{ $kas->jenis }}" data-image="{{ $kas->image_url ?? '' }}"
                                            class="sr-only preview-kas-bank-radio">
                                        <div
                                            class="p-3 md:p-4 border-2 border-gray-200 rounded-xl hover:border-orange-400 hover:bg-gradient-to-br hover:from-orange-50 hover:to-red-50 transition-all duration-300 preview-kas-bank-card flex items-center justify-between shadow-sm hover:shadow-md">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <div
                                                    class="w-12 h-12 md:w-16 md:h-16 rounded-xl flex items-center justify-center mr-2 md:mr-4 overflow-hidden shadow-sm flex-shrink-0">
                                                    @if ($kas->jenis === 'KAS')
                                                        <div
                                                            class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                                            <i class="ti ti-cash text-green-600 text-base md:text-xl"></i>
                                                        </div>
                                                    @else
                                                        @if ($kas->image)
                                                            <img src="{{ asset('storage/' . $kas->image) }}"
                                                                alt="Logo {{ $kas->nama }}"
                                                                class="w-full h-full object-contain">
                                                        @else
                                                            <div
                                                                class="w-full h-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                                                                <i
                                                                    class="ti ti-building-bank text-purple-600 text-base md:text-xl"></i>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex-1 flex flex-col justify-center min-w-0">
                                                    <div
                                                        class="text-sm md:text-base font-bold text-gray-900 leading-tight truncate">
                                                        {{ $kas->nama }}
                                                    </div>
                                                    @if ($kas->no_rekening)
                                                        <div class="text-xs md:text-sm text-gray-500 font-medium truncate">
                                                            {{ $kas->no_rekening }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- DP Amount (shown only for kredit) -->
                        <div id="previewDpContainer" class="hidden">
                            <label for="previewDpAmount" id="previewDpLabel"
                                class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Down Payment (DP)
                            </label>
                            <input type="text" id="previewDpAmount"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right"
                                placeholder="Jumlah DP (Rp)">
                        </div>

                        <!-- Uang Muka Supplier -->
                        <div id="previewUangMukaContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gunakan Uang Muka (Opsional)
                            </label>
                            <div id="uangMukaList"
                                class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <p class="text-sm text-gray-500 text-center py-4" id="uangMukaEmptyMessage">
                                    Pilih supplier terlebih dahulu untuk melihat uang muka yang tersedia
                                </p>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="ti ti-info-circle mr-1"></i>
                                Pilih uang muka yang akan digunakan dan masukkan jumlahnya
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-3 md:p-4 mb-4 md:mb-6">
                    <h4 class="font-semibold text-gray-900 mb-2 md:mb-3 text-sm md:text-base">Ringkasan Pesanan</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-sm md:text-base" id="previewSubtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm" id="previewDiscountRow"
                            style="display: none;">
                            <span class="text-gray-600">Diskon</span>
                            <span class="font-medium text-red-600 text-sm md:text-base" id="previewDiscount">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-base md:text-lg font-bold border-t border-gray-200 pt-2">
                            <span>Total</span>
                            <span id="previewTotal">Rp 0</span>
                        </div>
                        <div id="previewPaymentBreakdown" class="hidden border-t border-gray-200 pt-2 space-y-2">
                            <div class="flex justify-between text-xs md:text-sm" id="previewUangMukaRow"
                                style="display: none;">
                                <span class="text-blue-600">Uang Muka Digunakan</span>
                                <span class="font-medium text-blue-600 text-sm md:text-base"
                                    id="previewUangMukaDisplay">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-xs md:text-sm">
                                <span class="text-green-600" id="previewDpLabelText">DP Dibayar</span>
                                <span class="font-medium text-green-600 text-sm md:text-base" id="previewDpDisplay">Rp
                                    0</span>
                            </div>
                            <div class="flex justify-between text-xs md:text-sm">
                                <span class="text-orange-600">Sisa Pembayaran</span>
                                <span class="font-medium text-orange-600 text-sm md:text-base"
                                    id="previewRemainingDisplay">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons - Sticky Bottom for Mobile -->
            <div
                class="sticky bottom-0 bg-white border-t border-gray-200 md:border-t-0 p-3 md:p-0 md:relative md:bg-transparent md:mt-3 md:px-0 shadow-lg md:shadow-none z-10">
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-3">
                    <button type="button" id="closePreviewModal"
                        class="w-full md:flex-1 py-2.5 md:py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors text-sm md:text-base">
                        <i class="ti ti-x text-base md:text-lg mr-2"></i>
                        Batal
                    </button>
                    <button type="button" id="confirmOrderSave"
                        class="w-full md:flex-1 py-2.5 md:py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors text-sm md:text-base">
                        <i class="ti ti-device-floppy text-base md:text-lg mr-2"></i>
                        Simpan Pembelian
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quantity Input Modal -->
    <div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative w-full md:w-4/5 lg:w-3/4 xl:w-2/3 max-w-4xl mx-auto p-0 md:p-6 border-0 md:border shadow-lg rounded-t-2xl md:rounded-xl bg-white md:top-1/2 md:transform md:-translate-y-1/2 min-h-screen md:min-h-0 flex flex-col">
            <div class="flex-1 overflow-y-auto p-3 md:p-0 md:mt-3">
                <div
                    class="flex items-center justify-between mb-3 md:mb-4 pb-3 md:pb-0 border-b md:border-b-0 border-gray-200">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Masukkan Quantity</h3>
                    <button type="button" id="closeQuantityModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <!-- Product Info -->
                <div id="modalProductInfo" class="mb-3 md:mb-4 p-2 md:p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2 md:space-x-3">
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg flex items-center justify-center">
                            <i class="ti ti-package text-lg md:text-xl text-orange-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 id="modalProductName" class="font-semibold text-sm md:text-base text-gray-900 truncate">
                            </h4>
                            <p id="modalProductCode" class="text-xs md:text-sm text-gray-500 truncate"></p>
                            <p id="modalProductPrice" class="text-xs md:text-sm font-medium text-orange-600"></p>
                        </div>
                    </div>
                    <div class="mt-2 text-xs md:text-sm text-gray-600">
                        <span>Satuan: </span>
                        <span id="modalProductUnit" class="font-medium text-orange-600"></span>
                    </div>
                </div>

                <!-- Input Fields Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
                    <!-- Left Column -->
                    <div class="space-y-3 md:space-y-4">
                        <!-- Quantity Input -->
                        <div>
                            <label for="quantityInput" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                Quantity
                            </label>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="decreaseQty"
                                    class="w-10 h-10 md:w-10 md:h-10 bg-red-500 hover:bg-red-600 rounded-lg text-white flex items-center justify-center font-bold text-base md:text-lg transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <div class="flex-1 relative">
                                    <input type="text" id="quantityInput"
                                        class="w-full pl-3 md:pl-4 pr-12 md:pr-16 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-center text-base md:text-lg font-semibold"
                                        value="1" placeholder="0">
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2 md:pr-3 pointer-events-none">
                                        <span id="modalProductUnitInInput"
                                            class="text-xs md:text-sm font-medium text-gray-500 bg-gray-100 px-1.5 md:px-2 py-1 rounded"></span>
                                    </div>
                                </div>
                                <button type="button" id="increaseQty"
                                    class="w-10 h-10 md:w-10 md:h-10 bg-orange-600 hover:bg-orange-700 rounded-lg text-white flex items-center justify-center font-bold text-base md:text-lg transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 hidden md:block">Masukkan jumlah yang diinginkan</p>
                        </div>

                        <!-- Quantity Discount Input -->
                        <div>
                            <label for="quantityDiscountInput"
                                class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                Potongan Quantity (Opsional)
                            </label>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="decreaseQtyDiscount"
                                    class="w-10 h-10 md:w-10 md:h-10 bg-red-500 hover:bg-red-600 rounded-lg text-white flex items-center justify-center font-bold text-base md:text-lg transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <div class="flex-1 relative">
                                    <input type="text" id="quantityDiscountInput"
                                        class="w-full pl-3 md:pl-4 pr-12 md:pr-16 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-center text-base md:text-lg font-semibold"
                                        value="0" placeholder="0">
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2 md:pr-3 pointer-events-none">
                                        <span id="modalProductUnitInDiscountInput"
                                            class="text-xs md:text-sm font-medium text-gray-500 bg-gray-100 px-1.5 md:px-2 py-1 rounded"></span>
                                    </div>
                                </div>
                                <button type="button" id="increaseQtyDiscount"
                                    class="w-10 h-10 md:w-10 md:h-10 bg-green-500 hover:bg-green-600 rounded-lg text-white flex items-center justify-center font-bold text-base md:text-lg transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 hidden md:block">Masukkan potongan quantity untuk produk
                                ini (akan mengurangi quantity yang dibeli)</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-3 md:space-y-4">
                        <!-- Price Input -->
                        <div>
                            <label for="modalPriceInput" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                Harga Beli Satuan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 md:pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs md:text-sm font-medium">Rp</span>
                                </div>
                                <input type="text" id="modalPriceInput"
                                    class="w-full pl-10 md:pl-12 pr-3 md:pr-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-right text-base md:text-lg font-semibold"
                                    placeholder="0">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Harga default: <span id="modalDefaultPrice"
                                    class="font-medium text-orange-600"></span></p>
                        </div>

                        <!-- Discount Input -->
                        <div>
                            <label for="discountInput" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                Potongan Harga (Opsional)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 md:pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-xs md:text-sm font-medium">Rp</span>
                                </div>
                                <input type="text" id="discountInput"
                                    class="w-full pl-10 md:pl-12 pr-3 md:pr-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-right text-base md:text-lg font-semibold"
                                    value="0" placeholder="0">
                            </div>
                            <p class="text-xs text-gray-500 mt-1 hidden md:block">Masukkan potongan harga untuk produk ini
                                (dalam Rupiah)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Keterangan Input - Full Width -->
                <div class="mb-4 md:mb-6">
                    <label for="keteranganInput" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea id="keteranganInput" rows="2"
                        class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-xs md:text-sm resize-none"
                        placeholder="Masukkan keterangan untuk item ini..."></textarea>
                    <p class="text-xs text-gray-500 mt-1 hidden md:block">Tambahkan catatan atau keterangan khusus untuk
                        item ini</p>
                </div>

                <!-- Total Price Preview -->
                <div class="mb-4 md:mb-6 p-2 md:p-3 bg-orange-50 rounded-lg">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Quantity:</span>
                            <span id="modalQuantityDisplay" class="font-medium text-gray-800">0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm" id="modalQuantityDiscountRow"
                            style="display: none;">
                            <span class="text-blue-600">Potongan Qty:</span>
                            <span id="modalQuantityDiscountDisplay" class="font-medium text-blue-600">0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm" id="modalEffectiveQuantityRow"
                            style="display: none;">
                            <span class="text-green-600">Qty Efektif:</span>
                            <span id="modalEffectiveQuantityDisplay" class="font-medium text-green-600">0</span>
                        </div>
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

            </div>

            <!-- Action Buttons - Sticky Bottom for Mobile -->
            <div
                class="sticky bottom-0 bg-white border-t border-gray-200 md:border-t-0 p-3 md:p-0 md:relative md:bg-transparent md:mt-3 md:px-0 shadow-lg md:shadow-none z-10">
                <div class="flex space-x-2 md:space-x-3">
                    <button type="button" id="cancelQuantity"
                        class="w-20 md:w-24 py-2.5 md:py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-sm md:text-base">
                        Batal
                    </button>
                    <button type="button" id="confirmQuantity"
                        class="flex-1 py-2.5 md:py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors whitespace-nowrap text-sm md:text-base">
                        <i class="ti ti-plus text-xs md:text-sm mr-1"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Selection Modal -->
    <div id="supplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-0 md:top-20 mx-auto p-0 md:p-5 border-0 md:border w-full md:w-3/4 lg:w-1/2 shadow-lg rounded-t-2xl md:rounded-xl bg-white min-h-screen md:min-h-0 flex flex-col">
            <div class="flex-1 overflow-y-auto p-3 md:p-0 md:mt-3">
                <div
                    class="flex items-center justify-between mb-3 md:mb-4 pb-3 md:pb-0 border-b md:border-b-0 border-gray-200">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Pilih Supplier</h3>
                    <button type="button" id="closeSupplierModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <!-- Search Supplier -->
                <div class="mb-3 md:mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2 md:pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-search text-base md:text-lg text-gray-400"></i>
                        </div>
                        <input type="text" id="supplierSearch" placeholder="Cari nama atau kode supplier..."
                            class="w-full pl-9 md:pl-11 pr-3 md:pr-4 py-2.5 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm md:text-base">
                    </div>
                </div>

                <!-- Supplier List -->
                <div id="supplierList" class="max-h-[calc(100vh-280px)] md:max-h-96 overflow-y-auto space-y-2">
                    @foreach ($suppliers as $supplier)
                        <div class="supplier-item p-3 md:p-4 border border-gray-200 rounded-lg hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition-all duration-200"
                            data-id="{{ $supplier->id }}" data-name="{{ $supplier->nama }}"
                            data-code="{{ $supplier->kode_supplier }}" data-phone="{{ $supplier->telepon ?? '' }}"
                            data-address="{{ $supplier->alamat ?? '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 md:space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="ti ti-building-store text-white text-sm md:text-base"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-semibold text-sm md:text-base text-gray-900 truncate">
                                                {{ $supplier->nama }}</h4>
                                            <p class="text-xs md:text-sm text-gray-500 truncate">
                                                {{ $supplier->kode_supplier }}</p>
                                            @if ($supplier->telepon)
                                                <p class="text-xs text-gray-400 truncate">{{ $supplier->telepon }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($supplier->alamat)
                                        <p class="text-xs text-gray-500 mt-2 ml-0 md:ml-13 truncate">
                                            {{ Str::limit($supplier->alamat, 50) }}</p>
                                    @endif
                                </div>
                                <div class="text-right flex-shrink-0 ml-2">
                                    @if ($supplier->status)
                                        <span
                                            class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="ti ti-check-circle text-xs mr-1"></i>
                                            <span class="hidden md:inline">Aktif</span>
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="ti ti-x-circle text-xs mr-1"></i>
                                            <span class="hidden md:inline">Nonaktif</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Add New Supplier Button - Sticky Bottom for Mobile -->
            <div
                class="sticky bottom-0 bg-white border-t border-gray-200 md:border-t-0 p-3 md:p-0 md:relative md:bg-transparent md:mt-4 md:pt-4 md:px-0 shadow-lg md:shadow-none z-10">
                <a href="{{ route('supplier.create') }}" target="_blank"
                    class="w-full flex items-center justify-center px-4 py-2.5 md:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm md:text-base">
                    <i class="ti ti-plus text-base md:text-lg mr-2"></i>
                    Tambah Supplier Baru
                </a>
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

        /* Order Preview Modal Mobile Styles */
        @media (max-width: 768px) {
            #orderPreviewModal {
                display: flex;
                align-items: flex-end;
            }

            #orderPreviewModal.hidden {
                display: none;
            }

            #orderPreviewModal>div {
                animation: slideUpPreview 0.3s ease-out;
                width: 100%;
                margin-top: auto;
                max-height: 95vh;
            }

            #orderPreviewModal>div>div:first-child {
                padding-bottom: 20px;
                /* Space for sticky button */
            }

            @keyframes slideUpPreview {
                from {
                    opacity: 0;
                    transform: translateY(100%);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
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

        /* Supplier Modal Mobile Styles */
        @media (max-width: 768px) {
            #supplierModal {
                display: flex;
                align-items: flex-end;
            }

            #supplierModal.hidden {
                display: none;
            }

            #supplierModal>div {
                animation: slideUp 0.3s ease-out;
                width: 100%;
                margin-top: auto;
                max-height: 100vh;
                height: 100vh;
            }

            #supplierModal>div>div:first-child {
                padding-bottom: 20px;
                /* Space for sticky button */
            }
        }

        /* Quantity Modal Mobile Styles */
        @media (max-width: 768px) {
            #quantityModal {
                display: flex;
                align-items: flex-end;
            }

            #quantityModal.hidden {
                display: none;
            }

            #quantityModal>div {
                animation: slideUp 0.3s ease-out;
                width: 100%;
                margin-top: auto;
                max-height: 100vh;
                height: 100vh;
            }

            #quantityModal>div>div:first-child {
                padding-bottom: 20px;
                /* Space for sticky button */
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(100%);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
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
    <script>
        // Mobile Step Navigation
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            // Hide all mobile steps
            document.querySelectorAll('.mobile-step').forEach(el => {
                el.classList.add('hidden');
            });

            // Show selected step
            const stepElement = document.querySelector(`.mobile-step-${step}`);
            if (stepElement) {
                stepElement.classList.remove('hidden');
            }

            // Show/hide mobile total actions based on step
            const mobileTotalActions = document.querySelector('.mobile-total-actions');
            if (mobileTotalActions) {
                // Hide sticky bottom actions when in step 1 (transaction info) or step 3 (review)
                if (step === 1 || step === 3) {
                    mobileTotalActions.classList.add('hidden');
                } else {
                    mobileTotalActions.classList.remove('hidden');
                }
            }

            // Update stepper indicators
            updateStepper(step);

            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateStepper(step) {
            currentStep = step;

            // Update step indicators
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.getElementById(`stepIndicator${i}`);
                const progressBar = document.getElementById(`progressBar${i}`);

                if (i < step) {
                    // Completed step
                    indicator.classList.remove('bg-gray-200', 'text-gray-600');
                    indicator.classList.add('bg-green-500', 'text-white');
                    if (progressBar) progressBar.style.width = '100%';
                } else if (i === step) {
                    // Current step
                    indicator.classList.remove('bg-gray-200', 'text-gray-600', 'bg-green-500');
                    indicator.classList.add('bg-orange-600', 'text-white');
                    if (progressBar) progressBar.style.width = '50%';
                } else {
                    // Future step
                    indicator.classList.remove('bg-orange-600', 'text-white', 'bg-green-500');
                    indicator.classList.add('bg-gray-200', 'text-gray-600');
                    if (progressBar) progressBar.style.width = '0%';
                }
            }

            // Update step title and description
            const stepTitle = document.getElementById('stepTitle');
            const stepDescription = document.getElementById('stepDescription');

            const stepConfig = {
                1: {
                    title: 'Langkah 1: Informasi Transaksi',
                    description: 'Isi informasi supplier dan tanggal'
                },
                2: {
                    title: 'Langkah 2: Pilih Produk',
                    description: 'Pilih produk yang akan dibeli'
                },
                3: {
                    title: 'Langkah 3: Review Pesanan',
                    description: 'Periksa dan konfirmasi pesanan'
                }
            };

            if (stepTitle && stepDescription && stepConfig[step]) {
                stepTitle.textContent = stepConfig[step].title;
                stepDescription.textContent = stepConfig[step].description;
            }
        }

        function validateStep(step) {
            if (step === 1) {
                const supplierId = document.getElementById('supplierId').value;
                if (!supplierId) {
                    showToast('Pilih supplier terlebih dahulu!', 'error');
                    return false;
                }
                return true;
            } else if (step === 2) {
                // Check if cart has items
                if (cart.length === 0) {
                    showToast('Pilih minimal 1 produk terlebih dahulu!', 'error');
                    return false;
                }
                return true;
            }
            return true;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Only show step navigation on mobile
            if (window.innerWidth < 1024) {
                showStep(1);
            } else {
                // Desktop: hide all mobile steps (they should use desktop version)
                document.querySelectorAll('.mobile-step').forEach(el => {
                    el.classList.add('hidden');
                });
            }

            // Next to Step 2 button
            const nextToStep2Btn = document.getElementById('nextToStep2');
            if (nextToStep2Btn) {
                nextToStep2Btn.addEventListener('click', function() {
                    if (validateStep(1)) {
                        showStep(2);
                    }
                });
            }

            // Back to Step 2 button (from Step 3)
            const backToStep2Btn = document.getElementById('backToStep2');
            if (backToStep2Btn) {
                backToStep2Btn.addEventListener('click', function() {
                    showStep(2);
                });
            }

            // Proceed to Review button (from Step 2)
            const proceedToReviewBtn = document.getElementById('proceedToReviewBtn');
            if (proceedToReviewBtn) {
                proceedToReviewBtn.addEventListener('click', function() {
                    if (validateStep(2)) {
                        showStep(3);
                        // Update review with current cart
                        updateOrderSummary();
                        calculateTotals();
                    }
                });
            }

            // Add button to proceed to step 3 from step 2 (mobile only)
            // This will be added dynamically in step 2

            // Sync supplier between mobile and desktop
            const supplierIdInput = document.getElementById('supplierId');
            const supplierDisplayInput = document.getElementById('supplierDisplay');

            if (supplierIdInput && supplierDisplayInput) {
                // Sync when supplier changes
                const observer = new MutationObserver(function(mutations) {
                    const supplierIdDesktop = document.getElementById('supplierIdDesktop');
                    const supplierDisplayDesktop = document.getElementById('supplierDisplayDesktop');

                    if (supplierIdDesktop && supplierDisplayDesktop) {
                        supplierIdDesktop.value = supplierIdInput.value;
                        supplierDisplayDesktop.value = supplierDisplayInput.value;
                    }
                });

                observer.observe(supplierIdInput, {
                    attributes: true,
                    attributeFilter: ['value']
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    // Desktop: hide all mobile steps
                    document.querySelectorAll('.mobile-step').forEach(el => {
                        el.classList.add('hidden');
                    });
                } else {
                    // Mobile: show current step
                    showStep(currentStep);
                }
            });
        });
    </script>

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
            const searchSupplierBtnDesktop = document.getElementById('searchSupplierBtnDesktop');
            const supplierModal = document.getElementById('supplierModal');
            const closeSupplierModal = document.getElementById('closeSupplierModal');
            const supplierSearch = document.getElementById('supplierSearch');
            const clearSupplierBtn = document.getElementById('clearSupplierBtn');
            const clearSupplierBtnDesktop = document.getElementById('clearSupplierBtnDesktop');

            // Mobile button
            if (searchSupplierBtn) {
                searchSupplierBtn.addEventListener('click', () => {
                    supplierModal.classList.remove('hidden');
                });
            }

            // Desktop button
            if (searchSupplierBtnDesktop) {
                searchSupplierBtnDesktop.addEventListener('click', () => {
                    supplierModal.classList.remove('hidden');
                });
            }

            closeSupplierModal.addEventListener('click', () => {
                supplierModal.classList.add('hidden');
            });

            // Clear supplier selection (Mobile)
            if (clearSupplierBtn) {
                clearSupplierBtn.addEventListener('click', () => {
                    document.getElementById('supplierDisplay').value = '';
                    document.getElementById('supplierId').value = '';
                    clearSupplierBtn.classList.add('hidden');
                    supplierModal.classList.add('hidden');
                });
            }

            // Clear supplier selection (Desktop)
            if (clearSupplierBtnDesktop) {
                clearSupplierBtnDesktop.addEventListener('click', () => {
                    document.getElementById('supplierDisplayDesktop').value = '';
                    document.getElementById('supplierIdDesktop').value = '';
                    clearSupplierBtnDesktop.classList.add('hidden');
                    supplierModal.classList.add('hidden');
                });
            }

            // Supplier search
            if (supplierSearch) {
                supplierSearch.addEventListener('input', function() {
                    filterSuppliers(this.value);
                });
            }

            // Supplier selection - update both mobile and desktop
            document.querySelectorAll('.supplier-item').forEach(item => {
                item.addEventListener('click', function() {
                    const supplierName = `${this.dataset.name} (${this.dataset.code})`;
                    const supplierId = this.dataset.id;

                    // Update mobile fields
                    const supplierDisplay = document.getElementById('supplierDisplay');
                    const supplierIdInput = document.getElementById('supplierId');
                    if (supplierDisplay) supplierDisplay.value = supplierName;
                    if (supplierIdInput) supplierIdInput.value = supplierId;
                    if (clearSupplierBtn) clearSupplierBtn.classList.remove('hidden');

                    // Update desktop fields
                    const supplierDisplayDesktop = document.getElementById('supplierDisplayDesktop');
                    const supplierIdDesktop = document.getElementById('supplierIdDesktop');
                    if (supplierDisplayDesktop) supplierDisplayDesktop.value = supplierName;
                    if (supplierIdDesktop) supplierIdDesktop.value = supplierId;
                    if (clearSupplierBtnDesktop) clearSupplierBtnDesktop.classList.remove('hidden');

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
            const quantityDiscountInput = document.getElementById('quantityDiscountInput');
            const keteranganInput = document.getElementById('keteranganInput');
            const decreaseQty = document.getElementById('decreaseQty');
            const increaseQty = document.getElementById('increaseQty');
            const decreaseQtyDiscount = document.getElementById('decreaseQtyDiscount');
            const increaseQtyDiscount = document.getElementById('increaseQtyDiscount');

            // Close modal handlers
            function closeQuantityModalHandler() {
                quantityModal.classList.add('hidden');
                selectedProduct = null;
                editingItemIndex = null;
                // Reset form inputs
                document.getElementById('quantityInput').value = '1';
                document.getElementById('modalPriceInput').value = '0';
                document.getElementById('discountInput').value = '0';
                document.getElementById('quantityDiscountInput').value = '0';
                document.getElementById('keteranganInput').value = '';
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
            setupDecimalInput(quantityDiscountInput);

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

            // Quantity discount controls
            decreaseQtyDiscount.addEventListener('click', () => {
                let value = parseFormattedDecimal(quantityDiscountInput.value) || 0;
                if (value > 0) {
                    let newValue = value >= 1 ? value - 1 : Math.max(0, Math.round((value - 0.1) * 10) / 10);
                    quantityDiscountInput.value = formatDecimalInput(newValue);
                    quantityDiscountInput.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    updateModalTotal();
                }
            });

            increaseQtyDiscount.addEventListener('click', () => {
                let value = parseFormattedDecimal(quantityDiscountInput.value) || 0;
                let newValue = value % 1 === 0 ? value + 1 : Math.round((value + 0.1) * 10) / 10;
                quantityDiscountInput.value = formatDecimalInput(newValue);
                quantityDiscountInput.dispatchEvent(new Event('input', {
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

            quantityDiscountInput.addEventListener('input', function() {
                updateModalTotal();
                validateQuantityDiscountInput();
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
            document.getElementById('modalProductUnitInDiscountInput').textContent = selectedProduct.unit;
            document.getElementById('modalDefaultPrice').textContent = `Rp ${formatNumber(selectedProduct.price)}`;

            // Set default values for new items
            if (editingItemIndex === null) {
                document.getElementById('quantityInput').value = '1';
                document.getElementById('modalPriceInput').value = formatNumber(selectedProduct.price);
                document.getElementById('discountInput').value = '0';
                document.getElementById('quantityDiscountInput').value = '0';
                document.getElementById('keteranganInput').value = '';
            }

            // Reset validation styles
            resetValidationStyles();

            updateModalTotal();
            modal.classList.remove('hidden');
        }

        // Update modal total
        function updateModalTotal() {
            const qty = parseFormattedDecimal(document.getElementById('quantityInput').value) || 0;
            const qtyDiscount = parseFormattedDecimal(document.getElementById('quantityDiscountInput').value) || 0;
            const price = parseFormattedNumber(document.getElementById('modalPriceInput').value) || 0;
            const discount = parseFormattedNumber(document.getElementById('discountInput').value) || 0;

            // Calculate effective quantity (qty - qtyDiscount)
            const effectiveQty = Math.max(0, qty - qtyDiscount);
            const subtotal = effectiveQty * price;
            const total = subtotal - discount;

            // Update quantity displays
            document.getElementById('modalQuantityDisplay').textContent =
                `${formatDecimalInput(qty)} ${document.getElementById('modalProductUnitInInput').textContent}`;
            document.getElementById('modalSubtotalPrice').textContent = `Rp ${formatNumber(subtotal)}`;
            document.getElementById('modalDiscountPrice').textContent = `Rp ${formatNumber(discount)}`;
            document.getElementById('modalTotalPrice').textContent = `Rp ${formatNumber(Math.max(0, total))}`;

            // Show/hide quantity discount row
            const quantityDiscountRow = document.getElementById('modalQuantityDiscountRow');
            if (qtyDiscount > 0) {
                quantityDiscountRow.style.display = 'flex';
                document.getElementById('modalQuantityDiscountDisplay').textContent =
                    `${formatDecimalInput(qtyDiscount)} ${document.getElementById('modalProductUnitInDiscountInput').textContent}`;
            } else {
                quantityDiscountRow.style.display = 'none';
            }

            // Show/hide effective quantity row
            const effectiveQuantityRow = document.getElementById('modalEffectiveQuantityRow');
            if (qtyDiscount > 0) {
                effectiveQuantityRow.style.display = 'flex';
                document.getElementById('modalEffectiveQuantityDisplay').textContent =
                    `${formatDecimalInput(effectiveQty)} ${document.getElementById('modalProductUnitInInput').textContent}`;
            } else {
                effectiveQuantityRow.style.display = 'none';
            }

            // Show/hide discount row
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
            const quantityDiscountInput = document.getElementById('quantityDiscountInput');
            const keteranganInput = document.getElementById('keteranganInput');

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
            const qtyDiscount = parseFormattedDecimal(quantityDiscountInput.value) || 0;
            const price = parseFormattedNumber(priceInput.value) || 0;
            const discount = parseFormattedNumber(discountInput.value) || 0;
            const keterangan = keteranganInput.value.trim();

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

            // Validate quantity discount
            if (qtyDiscount >= qty) {
                showToast('Potongan quantity tidak boleh melebihi atau sama dengan quantity!', 'error');
                quantityDiscountInput.focus();
                return;
            }

            // Validate discount
            const effectiveQty = Math.max(0, qty - qtyDiscount);
            const subtotal = price * effectiveQty;
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
                    qtyDiscount: qtyDiscount,
                    price: price,
                    discount: discount,
                    keterangan: keterangan
                };
                showToast(`${selectedProduct.name} berhasil diperbarui`, 'success');
                editingItemIndex = null;
            } else {
                // Always add new item (no merging with existing items)
                cart.push({
                    id: selectedProduct.id,
                    name: selectedProduct.name,
                    code: selectedProduct.code,
                    qty: qty,
                    qtyDiscount: qtyDiscount,
                    price: price,
                    unit: selectedProduct.unit,
                    discount: discount,
                    keterangan: keterangan,
                    index: productIndex++,
                    uniqueId: Date.now() + '_' + Math.random().toString(36).substr(2,
                        9) // Unique identifier for each cart item
                });
                showToast(`${selectedProduct.name} ditambahkan ke pesanan (${qty} ${selectedProduct.unit})`, 'success');
            }

            updateOrderSummary();
            selectedProduct = null;
        }

        // Update order summary
        function updateOrderSummary() {
            const orderItems = document.getElementById('orderItems');
            const emptyState = document.getElementById('emptyState');
            const orderCount = document.getElementById('orderCount');

            // Mobile Step 3 Review
            const orderItemsReview = document.getElementById('orderItemsReview');
            const emptyStateReview = document.getElementById('emptyStateReview');

            if (cart.length === 0) {
                orderItems.innerHTML = '';
                emptyState.style.display = 'block';
                orderCount.textContent = '0 item';

                // Mobile review
                if (orderItemsReview) {
                    orderItemsReview.innerHTML = '';
                }
                if (emptyStateReview) {
                    emptyStateReview.style.display = 'block';
                }
            } else {
                emptyState.style.display = 'none';
                orderCount.textContent = `${cart.length} item${cart.length > 1 ? 's' : ''}`;

                // Mobile review
                if (emptyStateReview) {
                    emptyStateReview.style.display = 'none';
                }

                // Generate order items HTML
                const orderItemsHTML = cart.map((item, index) => {
                    const qtyDiscount = item.qtyDiscount || 0;
                    const effectiveQty = Math.max(0, item.qty - qtyDiscount);
                    const subtotal = item.price * effectiveQty;
                    const discount = item.discount || 0;
                    const total = subtotal - discount;

                    // Count how many times this product appears in cart
                    const sameProductCount = cart.filter(cartItem => cartItem.id === item.id).length;
                    const sameProductIndex = cart.filter(cartItem => cartItem.id === item.id).indexOf(item) + 1;

                    return `
                        <div class="order-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer" data-index="${index}" onclick="editOrderItem(${index})">
                            <!-- Product Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">
                                        ${item.name}
                                        ${sameProductCount > 1 ? `<span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">#${sameProductIndex}</span>` : ''}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">${item.code}</p>
                                    ${item.keterangan ? `<p class="text-xs text-gray-600 mt-1 italic">"${item.keterangan}"</p>` : ''}
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
                                
                                ${qtyDiscount > 0 ? `
                                                                                                    <div class="flex items-center justify-between text-sm">
                                                                                                        <span class="text-blue-600">Potongan Qty: ${formatDecimalInput(qtyDiscount)} ${item.unit}</span>
                                                                                                        <span class="font-medium text-blue-600">-${formatDecimalInput(qtyDiscount)} ${item.unit}</span>
                                                                                                    </div>
                                                                                                    ` : ''}
                                
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Subtotal (${formatDecimalInput(effectiveQty)} × Rp ${formatNumber(item.price)})</span>
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

                orderItems.innerHTML = orderItemsHTML;

                // Update mobile review (step 3)
                if (orderItemsReview) {
                    orderItemsReview.innerHTML = orderItemsHTML;
                }

                // Show proceed to review button on mobile step 2
                if (window.innerWidth < 1024 && currentStep === 2) {
                    showProceedToReviewButton();
                }

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
            document.getElementById('modalProductUnitInDiscountInput').textContent = item.unit;
            document.getElementById('modalDefaultPrice').textContent = `Rp ${formatNumber(item.price)}`;

            // Set current values
            document.getElementById('quantityInput').value = formatDecimalInput(item.qty);
            document.getElementById('modalPriceInput').value = formatNumber(item.price);
            document.getElementById('discountInput').value = formatNumber(item.discount);
            document.getElementById('quantityDiscountInput').value = formatDecimalInput(item.qtyDiscount || 0);
            document.getElementById('keteranganInput').value = item.keterangan || '';

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

        // Show proceed to review button
        function showProceedToReviewButton() {
            const proceedBtnContainer = document.getElementById('proceedToReviewBtnContainer');
            const cartItemCount = document.getElementById('cartItemCount');
            if (proceedBtnContainer && window.innerWidth < 1024 && currentStep === 2) {
                proceedBtnContainer.classList.remove('hidden');
                if (cartItemCount) {
                    cartItemCount.textContent = `${cart.length} item${cart.length > 1 ? 's' : ''}`;
                }
            }
        }

        // Calculate totals
        function calculateTotals() {
            let subtotal = 0;

            cart.forEach(item => {
                const qtyDiscount = item.qtyDiscount || 0;
                const effectiveQty = Math.max(0, item.qty - qtyDiscount);
                subtotal += (effectiveQty * item.price) - (item.discount || 0);
            });

            totalAmount = Math.max(0, subtotal - discountAmount);

            // Desktop displays
            const subtotalDisplay = document.getElementById('subtotalDisplay');
            const discountDisplay = document.getElementById('discountDisplay');
            const totalDisplay = document.getElementById('totalDisplay');

            if (subtotalDisplay) subtotalDisplay.textContent = `Rp ${formatNumber(subtotal)}`;
            if (discountDisplay) discountDisplay.textContent = `Rp ${formatNumber(discountAmount)}`;
            if (totalDisplay) totalDisplay.textContent = `Rp ${formatNumber(totalAmount)}`;

            // Mobile review displays
            const subtotalDisplayReview = document.getElementById('subtotalDisplayReview');
            const discountDisplayReview = document.getElementById('discountDisplayReview');
            const totalDisplayReview = document.getElementById('totalDisplayReview');

            if (subtotalDisplayReview) subtotalDisplayReview.textContent = `Rp ${formatNumber(subtotal)}`;
            if (discountDisplayReview) discountDisplayReview.textContent = `Rp ${formatNumber(discountAmount)}`;
            if (totalDisplayReview) totalDisplayReview.textContent = `Rp ${formatNumber(totalAmount)}`;

            // Desktop summary displays
            const subtotalDisplayDesktop = document.getElementById('subtotalDisplayDesktop');
            const discountDisplayDesktop = document.getElementById('discountDisplayDesktop');
            const totalDisplayDesktop = document.getElementById('totalDisplayDesktop');

            if (subtotalDisplayDesktop) subtotalDisplayDesktop.textContent = `Rp ${formatNumber(subtotal)}`;
            if (discountDisplayDesktop) discountDisplayDesktop.textContent = `Rp ${formatNumber(discountAmount)}`;
            if (totalDisplayDesktop) totalDisplayDesktop.textContent = `Rp ${formatNumber(totalAmount)}`;

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
            const discountReviewInput = document.getElementById('diskonDisplayReview');

            setupNumberInput(discountInput);
            if (discountReviewInput) {
                setupNumberInput(discountReviewInput);
            }

            discountInput.addEventListener('input', function() {
                discountAmount = parseFormattedNumber(this.value);
                document.getElementById('diskon').value = discountAmount;

                // Sync to review
                if (discountReviewInput) {
                    discountReviewInput.value = this.value;
                    document.getElementById('diskonReview').value = discountAmount;
                }
                calculateTotals();
            });
        }

        // Transaction type functionality
        function initializeTransactionType() {
            const jenisTransaksi = document.getElementById('jenisTransaksi');
            const dpContainer = document.getElementById('dpContainer');
            const dpAmountDisplay = document.getElementById('dpAmountDisplay');
            const dpAmount = document.getElementById('dpAmount');

            // Check if dpAmountDisplay exists (it might not exist if DP input was removed)
            if (!dpAmountDisplay) {
                // If dpAmountDisplay doesn't exist, just handle transaction type change without DP input
                jenisTransaksi.addEventListener('change', function() {
                    calculateTotals();
                });
                return;
            }

            // Handle transaction type change
            jenisTransaksi.addEventListener('change', function() {
                if (this.value === 'kredit') {
                    if (dpContainer) {
                        dpContainer.classList.remove('hidden');
                    }
                    if (dpAmountDisplay) {
                        dpAmountDisplay.required = true;
                    }
                } else {
                    if (dpContainer) {
                        dpContainer.classList.add('hidden');
                    }
                    if (dpAmountDisplay) {
                        dpAmountDisplay.required = false;
                        dpAmountDisplay.value = '0';
                    }
                    if (dpAmount) {
                        dpAmount.value = 0;
                    }

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

                if (totalAmount > 0 && dpAmountDisplay) {
                    // Set DP amount to equal the total for cash transactions
                    dpAmountDisplay.value = formatNumber(totalAmount);
                    if (dpAmount) {
                        dpAmount.value = totalAmount;
                    }

                    // Update the summary to reflect the payment
                    calculateTotals();

                    // Show a subtle notification
                    showToast('Pembayaran tunai otomatis diisi sesuai total', 'success');
                }
            }

            // Set initial DP visibility based on old value
            @if (old('jenis_transaksi') == 'kredit')
                if (dpContainer) {
                    dpContainer.classList.remove('hidden');
                }
                if (dpAmountDisplay) {
                    dpAmountDisplay.required = true;
                }
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
            if (dpAmountDisplay) {
                dpAmountDisplay.addEventListener('input', function() {
                    if (dpAmount) {
                        dpAmount.value = parseFormattedNumber(this.value);
                    }
                    calculateTotals();
                });

                // Setup number formatting for DP input
                setupNumberInput(dpAmountDisplay);

                // Format initial DP value
                if (dpAmountDisplay.value && dpAmountDisplay.value !== '0') {
                    dpAmountDisplay.value = formatNumber(dpAmountDisplay.value);
                }
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

                    const qtyDiscountInput = document.createElement('input');
                    qtyDiscountInput.type = 'hidden';
                    qtyDiscountInput.name = `items[${index}][qty_discount]`;
                    qtyDiscountInput.value = item.qtyDiscount || 0;
                    form.appendChild(qtyDiscountInput);

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

                    const keteranganInput = document.createElement('input');
                    keteranganInput.type = 'hidden';
                    keteranganInput.name = `items[${index}][keterangan]`;
                    keteranganInput.value = item.keterangan || '';
                    form.appendChild(keteranganInput);
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

        function validateQuantityDiscountInput() {
            const quantityDiscountInput = document.getElementById('quantityDiscountInput');
            const qtyInput = document.getElementById('quantityInput');

            const value = quantityDiscountInput.value.trim();
            const qty = parseFormattedDecimal(qtyInput.value) || 0;
            const qtyDiscount = parseFormattedDecimal(value) || 0;

            if (value && qtyDiscount > 0) {
                if (qtyDiscount >= qty && qty > 0) {
                    quantityDiscountInput.style.borderColor = '#ef4444';
                    quantityDiscountInput.style.backgroundColor = '#fef2f2';
                    return false;
                }
            }

            if (value && qtyDiscount >= 0) {
                quantityDiscountInput.style.borderColor = '#10b981';
                quantityDiscountInput.style.backgroundColor = '#f0fdf4';
            } else {
                quantityDiscountInput.style.borderColor = '#d1d5db';
                quantityDiscountInput.style.backgroundColor = '#ffffff';
            }

            return true;
        }

        // Reset validation styles
        function resetValidationStyles() {
            const qtyInput = document.getElementById('quantityInput');
            const priceInput = document.getElementById('modalPriceInput');
            const discountInput = document.getElementById('discountInput');
            const quantityDiscountInput = document.getElementById('quantityDiscountInput');

            // Reset to default styles
            qtyInput.style.borderColor = '#d1d5db';
            qtyInput.style.backgroundColor = '#ffffff';
            priceInput.style.borderColor = '#d1d5db';
            priceInput.style.backgroundColor = '#ffffff';
            discountInput.style.borderColor = '#d1d5db';
            discountInput.style.backgroundColor = '#ffffff';
            quantityDiscountInput.style.borderColor = '#d1d5db';
            quantityDiscountInput.style.backgroundColor = '#ffffff';
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
        const showOrderPreviewMobileBtn = document.getElementById('showOrderPreviewMobile');
        const showOrderPreviewDesktopBtn = document.getElementById('showOrderPreviewDesktop');
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
        // Desktop preview button
        if (showOrderPreviewDesktopBtn) {
            showOrderPreviewDesktopBtn.addEventListener('click', function() {
                if (cart.length === 0) {
                    showToast('Pilih produk terlebih dahulu', 'error');
                    return;
                }
                showOrderPreview();
            });
        }

        // Mobile preview button (sticky bottom)
        if (showOrderPreviewBtn) {
            showOrderPreviewBtn.addEventListener('click', function() {
                if (cart.length === 0) {
                    showToast('Pilih produk terlebih dahulu', 'error');
                    return;
                }
                showOrderPreview();
            });
        }

        // Mobile preview button (review section)
        if (showOrderPreviewMobileBtn) {
            showOrderPreviewMobileBtn.addEventListener('click', function() {
                if (cart.length === 0) {
                    showToast('Pilih produk terlebih dahulu', 'error');
                    return;
                }
                showOrderPreview();
            });
        }

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
            const previewDpAmount = document.getElementById('previewDpAmount');
            previewDpAmount.value = '';
            previewDpAmount.readOnly = false;
            previewDpAmount.classList.remove('bg-gray-100', 'cursor-not-allowed');
            previewDpAmount.classList.add('focus:ring-2', 'focus:ring-orange-500');
            previewDpAmount.placeholder = 'Jumlah DP (Rp)';

            // Show/hide DP container based on transaction type (will be updated when user selects transaction type)
            const previewDpContainer = document.getElementById('previewDpContainer');
            previewDpContainer.classList.add('hidden'); // Hide initially until user selects transaction type

            // Populate order items
            const previewOrderItems = document.getElementById('previewOrderItems');
            previewOrderItems.innerHTML = '';

            cart.forEach((item, index) => {
                const qtyDiscount = item.qtyDiscount || 0;
                const effectiveQty = Math.max(0, item.qty - qtyDiscount);
                const subtotal = item.price * effectiveQty;
                const discount = item.discount || 0;
                const total = subtotal - discount;

                // Count how many times this product appears in cart
                const sameProductCount = cart.filter(cartItem => cartItem.id === item.id).length;
                const sameProductIndex = cart.filter(cartItem => cartItem.id === item.id).indexOf(item) + 1;

                const itemHtml = `
                    <div class="bg-white border border-gray-200 rounded-lg p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h5 class="font-medium text-gray-900 text-sm">
                                    ${item.name}
                                    ${sameProductCount > 1 ? `<span class="ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">#${sameProductIndex}</span>` : ''}
                                </h5>
                                <p class="text-xs text-gray-500">${item.code}</p>
                                ${item.keterangan ? `<p class="text-xs text-gray-600 italic">"${item.keterangan}"</p>` : ''}
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">${formatDecimalInput(effectiveQty)} ${item.unit}</p>
                                <p class="text-xs text-gray-500">@ Rp ${formatNumber(item.price)}</p>
                                ${qtyDiscount > 0 ? `<p class="text-xs text-blue-600">Potongan: ${formatDecimalInput(qtyDiscount)} ${item.unit}</p>` : ''}
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
                const qtyDiscount = item.qtyDiscount || 0;
                const effectiveQty = Math.max(0, item.qty - qtyDiscount);
                const itemSubtotal = item.price * effectiveQty;
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
                updatePaymentCalculation(); // This will handle uang muka + DP calculation
            } else {
                previewPaymentBreakdown.classList.add('hidden');
            }

            // Load uang muka jika supplier sudah dipilih
            // Reset container terlebih dahulu
            const uangMukaContainer = document.getElementById('previewUangMukaContainer');
            const uangMukaList = document.getElementById('uangMukaList');
            if (uangMukaList) {
                // Clear existing items
                const existingItems = uangMukaList.querySelectorAll('.bg-white.border');
                existingItems.forEach(item => item.remove());
                // Show loading message
                const emptyMessage = document.getElementById('uangMukaEmptyMessage');
                if (emptyMessage) {
                    emptyMessage.style.display = 'block';
                    emptyMessage.textContent = 'Memuat uang muka yang tersedia...';
                }
            }

            const supplierId = document.getElementById('supplierId').value;
            if (supplierId) {
                loadUangMukaSupplier(supplierId);
            } else {
                if (uangMukaContainer) {
                    uangMukaContainer.classList.add('hidden');
                }
            }

            // Show modal
            orderPreviewModal.classList.remove('hidden');

            // Initialize payment method grid columns
            const paymentMethodContainer = document.getElementById('previewPaymentMethodContainer');
            if (paymentMethodContainer) {
                const paymentMethodCount = {{ count($metodePembayaran) }};
                const maxCols = 4;
                const cols = Math.min(paymentMethodCount, maxCols);
                paymentMethodContainer.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
            }

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
            const uangMukaContainer = document.getElementById('previewUangMukaContainer');
            const supplierId = document.getElementById('supplierId').value;
            const total = parseFormattedNumber(document.getElementById('previewTotal').textContent.replace('Rp ', '')
                .replace(/\./g, ''));

            const dpLabel = document.getElementById('previewDpLabel');

            if (selectedType && selectedType.value === 'tunai') {
                dpContainer.classList.remove('hidden');
                dpAmount.required = false;
                dpAmount.readOnly = true; // Readonly untuk tunai
                dpAmount.classList.add('bg-gray-100', 'cursor-not-allowed');
                dpAmount.classList.remove('focus:ring-2', 'focus:ring-orange-500');
                dpAmount.placeholder = 'Jumlah (Rp)';

                // Ubah label untuk tunai
                if (dpLabel) {
                    dpLabel.textContent = 'Jumlah Bayar';
                }

                // Ubah label di ringkasan pembayaran untuk tunai
                const dpLabelText = document.getElementById('previewDpLabelText');
                if (dpLabelText) {
                    dpLabelText.textContent = 'Jumlah Bayar';
                }

                // Uang muka tetap bisa digunakan untuk tunai
                if (supplierId && uangMukaContainer) {
                    // Jika container hidden, load uang muka
                    if (uangMukaContainer.classList.contains('hidden')) {
                        loadUangMukaSupplier(supplierId);
                    } else {
                        uangMukaContainer.classList.remove('hidden');
                    }
                }

                // Auto-fill DP amount dengan total setelah dikurangi uang muka
                updatePaymentCalculationForTunai();
                document.getElementById('previewPaymentBreakdown').classList.remove('hidden');
            } else if (selectedType && selectedType.value === 'kredit') {
                dpContainer.classList.remove('hidden');
                // Uang muka tetap visible untuk kredit
                if (supplierId && uangMukaContainer) {
                    if (uangMukaContainer.classList.contains('hidden')) {
                        loadUangMukaSupplier(supplierId);
                    } else {
                        uangMukaContainer.classList.remove('hidden');
                    }
                }
                dpAmount.required = true;
                dpAmount.value = '';
                dpAmount.readOnly = false;
                dpAmount.classList.remove('bg-gray-100', 'cursor-not-allowed');
                dpAmount.classList.add('focus:ring-2', 'focus:ring-orange-500');
                dpAmount.placeholder = 'Jumlah DP (Rp)';

                // Ubah label untuk kredit
                if (dpLabel) {
                    dpLabel.textContent = 'Jumlah Down Payment (DP)';
                }

                // Ubah label di ringkasan pembayaran untuk kredit
                const dpLabelText = document.getElementById('previewDpLabelText');
                if (dpLabelText) {
                    dpLabelText.textContent = 'DP Dibayar';
                }

                dpAmount.focus();
                updatePaymentCalculation(); // Update calculation untuk include uang muka
                document.getElementById('previewPaymentBreakdown').classList.remove('hidden');
            }

            updatePreviewTransactionTypeCards();
        }

        // Function untuk update payment calculation untuk tunai (dengan uang muka)
        function updatePaymentCalculationForTunai() {
            const total = parseFormattedNumber(document.getElementById('previewTotal').textContent.replace('Rp ', '')
                .replace(/\./g, ''));
            const uangMukaUsed = getTotalUangMukaUsed();
            const sisaBayar = Math.max(0, total - uangMukaUsed);

            // Auto-fill DP amount dengan sisa bayar
            const dpAmount = document.getElementById('previewDpAmount');
            if (dpAmount && uangMukaUsed > 0) {
                dpAmount.value = formatNumberInput(sisaBayar.toString());
            } else if (dpAmount && uangMukaUsed === 0) {
                dpAmount.value = formatNumberInput(total.toString());
            }

            // Update display
            if (uangMukaUsed > 0) {
                document.getElementById('previewUangMukaRow').style.display = 'flex';
                document.getElementById('previewUangMukaDisplay').textContent = `Rp ${formatNumber(uangMukaUsed)}`;
            } else {
                document.getElementById('previewUangMukaRow').style.display = 'none';
            }

            const dpAmountValue = parseFormattedNumber(dpAmount.value);
            document.getElementById('previewDpDisplay').textContent = `Rp ${formatNumber(dpAmountValue)}`;
            document.getElementById('previewRemainingDisplay').textContent =
                `Rp ${formatNumber(Math.max(0, total - uangMukaUsed - dpAmountValue))}`;
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

        // Function to load uang muka supplier
        function loadUangMukaSupplier(supplierId) {
            // Reset container terlebih dahulu
            const container = document.getElementById('uangMukaList');
            const emptyMessage = document.getElementById('uangMukaEmptyMessage');
            const uangMukaContainer = document.getElementById('previewUangMukaContainer');

            // Clear previous data
            if (container) {
                const existingItems = container.querySelectorAll('.bg-white.border');
                existingItems.forEach(item => item.remove());
            }
            if (uangMukaContainer) {
                uangMukaContainer.classList.add('hidden');
            }
            if (emptyMessage && emptyMessage.parentNode) {
                emptyMessage.style.display = 'block';
            }

            fetch(`/uang-muka-supplier/get-available?supplier_id=${supplierId}`)
                .then(response => response.json())
                .then(data => {
                    // Handle response format (bisa berupa object dengan success dan data, atau langsung array)
                    const uangMukaData = data.success ? data.data : data;

                    if (!uangMukaData || uangMukaData.length === 0) {
                        if (container) {
                            container.innerHTML =
                                '<p class="text-sm text-gray-500 text-center py-4">Tidak ada uang muka yang tersedia untuk supplier ini</p>';
                        }
                        if (uangMukaContainer) {
                            uangMukaContainer.classList.add('hidden');
                        }
                    } else {
                        // Hide empty message if it exists
                        if (emptyMessage) {
                            emptyMessage.style.display = 'none';
                        }
                        container.innerHTML = uangMukaData.map(um => `
                            <div class="bg-white border border-gray-200 rounded-lg p-3 hover:border-orange-300 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" 
                                        class="mt-1 uang-muka-checkbox" 
                                        data-id="${um.id}" 
                                        data-sisa="${um.sisa_uang_muka}"
                                        data-max="${um.sisa_uang_muka}">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="text-sm font-medium text-gray-900">${um.no_uang_muka}</span>
                                            <span class="text-xs text-gray-500">${um.tanggal}</span>
                                        </div>
                                        <div class="text-xs text-gray-600 mb-2">
                                            <span>Sisa: <strong class="text-green-600">Rp ${formatNumber(um.sisa_uang_muka)}</strong></span>
                                        </div>
                                        <input type="text" 
                                            class="uang-muka-amount w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-orange-500 text-right" 
                                            data-id="${um.id}"
                                            placeholder="Jumlah digunakan (Rp)"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        // Setup number formatting for uang muka inputs
                        container.querySelectorAll('.uang-muka-amount').forEach(input => {
                            setupNumberInput(input);
                        });

                        // Setup checkbox handlers
                        container.querySelectorAll('.uang-muka-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const amountInput = container.querySelector(
                                    `.uang-muka-amount[data-id="${this.dataset.id}"]`);
                                if (this.checked) {
                                    amountInput.disabled = false;
                                    amountInput.focus();
                                } else {
                                    amountInput.disabled = true;
                                    amountInput.value = '';
                                    updatePaymentCalculation();
                                }
                            });
                        });

                        // Setup amount input handlers
                        container.querySelectorAll('.uang-muka-amount').forEach(input => {
                            input.addEventListener('input', function() {
                                const selectedTransactionType = document.querySelector(
                                    '.preview-transaction-type-radio:checked')?.value;
                                if (selectedTransactionType === 'tunai') {
                                    updatePaymentCalculationForTunai();
                                } else {
                                    updatePaymentCalculation();
                                }
                            });
                        });

                        // Setup checkbox handlers untuk auto-fill tunai
                        container.querySelectorAll('.uang-muka-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const selectedTransactionType = document.querySelector(
                                    '.preview-transaction-type-radio:checked')?.value;
                                if (selectedTransactionType === 'tunai' && this.checked) {
                                    setTimeout(() => {
                                        updatePaymentCalculationForTunai();
                                    }, 100);
                                }
                            });
                        });

                        document.getElementById('previewUangMukaContainer').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error loading uang muka:', error);
                    document.getElementById('previewUangMukaContainer').classList.add('hidden');
                });
        }

        // Function to calculate total uang muka used
        function getTotalUangMukaUsed() {
            let total = 0;
            document.querySelectorAll('.uang-muka-checkbox:checked').forEach(checkbox => {
                const amountInput = document.querySelector(`.uang-muka-amount[data-id="${checkbox.dataset.id}"]`);
                if (amountInput && amountInput.value) {
                    const amount = parseFormattedNumber(amountInput.value);
                    const maxAmount = parseFloat(checkbox.dataset.max);
                    const validAmount = Math.min(amount, maxAmount);
                    total += validAmount;
                }
            });
            return total;
        }

        // Function to update payment calculation including uang muka
        function updatePaymentCalculation() {
            const total = parseFormattedNumber(document.getElementById('previewTotal').textContent.replace('Rp ', '')
                .replace(/\./g, ''));
            const uangMukaUsed = getTotalUangMukaUsed();
            const dpAmount = parseFormattedNumber(document.getElementById('previewDpAmount').value);
            const totalPembayaran = uangMukaUsed + dpAmount;
            const remaining = Math.max(0, total - totalPembayaran);

            // Update uang muka display
            if (uangMukaUsed > 0) {
                document.getElementById('previewUangMukaRow').style.display = 'flex';
                document.getElementById('previewUangMukaDisplay').textContent = `Rp ${formatNumber(uangMukaUsed)}`;
            } else {
                document.getElementById('previewUangMukaRow').style.display = 'none';
            }

            document.getElementById('previewDpDisplay').textContent = `Rp ${formatNumber(dpAmount)}`;
            document.getElementById('previewRemainingDisplay').textContent = `Rp ${formatNumber(remaining)}`;

            if (totalPembayaran > 0) {
                document.getElementById('previewPaymentBreakdown').classList.remove('hidden');
            } else if (dpAmount === 0) {
                document.getElementById('previewPaymentBreakdown').classList.add('hidden');
            }
        }

        // DP amount input handler (only for kredit, not for tunai)
        document.getElementById('previewDpAmount').addEventListener('input', function(e) {
            // Skip if readonly (tunai transaction)
            if (this.readOnly) {
                return;
            }
            // setupNumberInput already handles formatting, so we don't need to format manually
            updatePaymentCalculation();
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

            // Validate uang muka amounts
            let hasUangMukaError = false;
            const totalUangMukaUsed = getTotalUangMukaUsed();
            document.querySelectorAll('.uang-muka-checkbox:checked').forEach(checkbox => {
                const amountInput = document.querySelector(
                    `.uang-muka-amount[data-id="${checkbox.dataset.id}"]`);
                if (amountInput && amountInput.value) {
                    const jumlahDigunakan = parseFormattedNumber(amountInput.value);
                    const sisaUangMuka = parseFloat(checkbox.dataset.sisa);
                    const maxUangMuka = parseFloat(checkbox.dataset.max);

                    if (jumlahDigunakan <= 0) {
                        showToast('Jumlah uang muka yang digunakan harus lebih dari 0!', 'error');
                        amountInput.focus();
                        hasUangMukaError = true;
                        return;
                    }

                    if (jumlahDigunakan > sisaUangMuka || jumlahDigunakan > maxUangMuka) {
                        showToast(`Jumlah uang muka yang digunakan melebihi sisa uang muka yang tersedia!`,
                            'error');
                        amountInput.focus();
                        hasUangMukaError = true;
                        return;
                    }
                }
            });

            if (hasUangMukaError) {
                return;
            }

            // Validate total uang muka tidak melebihi total pembelian
            if (totalUangMukaUsed > total) {
                showToast('Total uang muka yang digunakan tidak boleh melebihi total pembelian!', 'error');
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

            // Remove any existing uang muka inputs
            form.querySelectorAll('input[name^="uang_muka["]').forEach(input => input.remove());

            // Add uang muka data to form
            let uangMukaIndex = 0;
            document.querySelectorAll('.uang-muka-checkbox:checked').forEach(checkbox => {
                const amountInput = document.querySelector(
                    `.uang-muka-amount[data-id="${checkbox.dataset.id}"]`);
                if (amountInput && amountInput.value) {
                    const amount = parseFormattedNumber(amountInput.value);
                    const maxAmount = parseFloat(checkbox.dataset.max);
                    const validAmount = Math.min(amount, maxAmount);

                    if (validAmount > 0) {
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = `uang_muka[${uangMukaIndex}][id]`;
                        idInput.value = checkbox.dataset.id;
                        form.appendChild(idInput);

                        const jumlahInput = document.createElement('input');
                        jumlahInput.type = 'hidden';
                        jumlahInput.name = `uang_muka[${uangMukaIndex}][jumlah]`;
                        jumlahInput.value = validAmount;
                        form.appendChild(jumlahInput);

                        uangMukaIndex++;
                    }
                }
            });

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

                const qtyDiscountInput = document.createElement('input');
                qtyDiscountInput.type = 'hidden';
                qtyDiscountInput.name = `items[${index}][qty_discount]`;
                qtyDiscountInput.value = item.qtyDiscount || 0;
                form.appendChild(qtyDiscountInput);

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

                const keteranganInput = document.createElement('input');
                keteranganInput.type = 'hidden';
                keteranganInput.name = `items[${index}][keterangan]`;
                keteranganInput.value = item.keterangan || '';
                form.appendChild(keteranganInput);
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
