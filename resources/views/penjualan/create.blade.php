@extends('layouts.pos')

@section('title', 'Transaksi Baru')
@section('page-title', 'Buat Transaksi Penjualan')

@section('content')
    <div class="min-h-screen">
        <!-- Back Button -->
        <div class="px-6 pt-6 pb-2">
            <a href="{{ route('penjualan.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                <i class="ti ti-arrow-left text-lg mr-2"></i>
                Kembali ke Daftar Penjualan
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
                                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
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
                                class="category-filter active px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium whitespace-nowrap transition-colors"
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
                        @foreach ($produk as $product)
                            <div class="product-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer group"
                                data-id="{{ $product->id }}" data-name="{{ $product->nama_produk }}"
                                data-code="{{ $product->kode_produk }}" data-price="{{ $product->harga_jual }}"
                                data-stock="{{ $product->stok }}" data-unit="{{ $product->satuan->nama ?? '' }}"
                                data-category="{{ $product->kategori->nama ?? '' }}">

                                <!-- Product Image Placeholder -->
                                <div
                                    class="w-full h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg mb-3 flex items-center justify-center group-hover:from-blue-200 group-hover:to-blue-300 transition-all duration-200">
                                    @if ($product->foto)
                                        <img src="{{ asset('storage/' . $product->foto) }}"
                                            alt="{{ $product->nama_produk }}"
                                            class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <i class="ti ti-package text-2xl text-blue-600"></i>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="text-center">
                                    <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2">
                                        {{ $product->nama_produk }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $product->kode_produk }}</p>
                                    <p class="text-lg font-bold text-blue-600 mb-2">Rp
                                        {{ number_format($product->harga_jual, 0, ',', '.') }}</p>

                                    <!-- Category Badge -->
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border bg-blue-100 text-blue-800 border-blue-200">
                                            <i class="ti ti-tag text-xs mr-1"></i>
                                            <span>{{ $product->kategori->nama ?? 'Uncategorized' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Button -->
                                <button
                                    class="add-product-btn w-full mt-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors opacity-0 group-hover:opacity-100">
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
                <form action="{{ route('penjualan.store') }}" method="POST" id="salesForm">
                    @csrf

                    <!-- Customer & Invoice Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h3>

                        <!-- Invoice Number -->
                        <div class="mb-2">
                            <input type="text" name="no_faktur" value="{{ old('no_faktur', $invoiceNumber) }}"
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

                        <!-- Customer -->
                        <div class="mb-2">
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <input type="text" id="customerDisplay"
                                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                                        placeholder="Pilih Pelanggan" readonly>
                                    <button type="button" id="clearCustomerBtn"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors hidden">
                                        <i class="ti ti-x text-lg"></i>
                                    </button>
                                </div>
                                <button type="button" id="searchCustomerBtn"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="ti ti-search text-lg"></i>
                                </button>
                            </div>
                            <input type="hidden" name="pelanggan_id" id="pelangganId"
                                value="{{ old('pelanggan_id') }}" required>
                        </div>

                        <!-- Payment Configuration Info -->
                        <div class="mb-2">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="ti ti-info-circle text-blue-600 mr-2"></i>
                                    <p class="text-sm text-blue-800">
                                        <strong>Konfigurasi pembayaran</strong> akan diatur di halaman ringkasan transaksi
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs for modal data -->
                        <input type="hidden" name="modal_jenis_transaksi" id="modalJenisTransaksi"
                            value="{{ old('jenis_transaksi', 'tunai') }}">
                        <input type="hidden" name="modal_metode_pembayaran" id="modalMetodePembayaran"
                            value="{{ old('metode_pembayaran') }}">
                        <input type="hidden" name="modal_kas_bank_id" id="modalKasBankId"
                            value="{{ old('kas_bank_id') }}">
                        <input type="hidden" name="modal_dp_amount" id="modalDpAmount"
                            value="{{ old('dp_amount', 0) }}">

                        <!-- Hidden inputs for controller -->
                        <input type="hidden" name="jenis_transaksi" id="jenisTransaksi"
                            value="{{ old('jenis_transaksi', 'tunai') }}">
                        <input type="hidden" name="metode_pembayaran" id="metodePembayaran"
                            value="{{ old('metode_pembayaran') }}">
                        <input type="hidden" name="dp_amount" id="dpAmount" value="{{ old('dp_amount', 0) }}">
                        <input type="hidden" name="kas_bank_id" id="kasBankId" value="{{ old('kas_bank_id') }}">

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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-right"
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


                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <i class="ti ti-device-floppy text-lg mr-2"></i>
                                Simpan Transaksi
                            </button>

                        </div>
                    </div>
                </form>
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
                            class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                            <i class="ti ti-package text-xl text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <h4 id="modalProductName" class="font-semibold text-gray-900"></h4>
                            <p id="modalProductCode" class="text-sm text-gray-500"></p>
                            <p id="modalProductPrice" class="text-sm font-medium text-blue-600"></p>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <span>Satuan: </span>
                        <span id="modalProductUnit" class="font-medium text-blue-600"></span>
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
                                class="w-full pl-4 pr-16 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-lg font-semibold"
                                value="1" placeholder="0">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span id="modalProductUnitInInput"
                                    class="text-sm font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded"></span>
                            </div>
                        </div>
                        <button type="button" id="increaseQty"
                            class="w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-xl text-white flex items-center justify-center font-bold text-xl transition-all duration-200 hover:scale-105 active:scale-95 shadow-md hover:shadow-lg">
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan jumlah yang diinginkan</p>
                </div>

                <!-- Price Input -->
                <div class="mb-4">
                    <label for="modalPriceInput" class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Satuan
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
                            class="font-medium text-blue-600"></span></p>
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
                <div class="mb-6 p-3 bg-blue-50 rounded-lg">
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
                        <div class="flex justify-between items-center border-t border-blue-200 pt-2">
                            <span class="text-sm font-medium text-gray-700">Total Harga:</span>
                            <span id="modalTotalPrice" class="text-lg font-bold text-blue-600">Rp 0</span>
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
                        class="flex-1 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors whitespace-nowrap">
                        <i class="ti ti-plus text-sm mr-1"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Selection Modal -->
    <div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Pelanggan</h3>
                    <button type="button" id="closeCustomerModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <!-- Search Customer -->
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-search text-lg text-gray-400"></i>
                        </div>
                        <input type="text" id="customerSearch" placeholder="Cari nama atau kode pelanggan..."
                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Customer List -->
                <div id="customerList" class="max-h-96 overflow-y-auto space-y-2">
                    @foreach ($pelanggan as $customer)
                        <div class="customer-item p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all duration-200"
                            data-id="{{ $customer->id }}" data-name="{{ $customer->nama }}"
                            data-code="{{ $customer->kode_pelanggan }}"
                            data-phone="{{ $customer->nomor_telepon ?? '' }}"
                            data-address="{{ $customer->alamat ?? '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                            <i class="ti ti-user text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $customer->nama }}</h4>
                                            <p class="text-sm text-gray-500">{{ $customer->kode_pelanggan }}</p>
                                            @if ($customer->nomor_telepon)
                                                <p class="text-xs text-gray-400">{{ $customer->nomor_telepon }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($customer->alamat)
                                        <p class="text-xs text-gray-500 mt-2 ml-13">
                                            {{ Str::limit($customer->alamat, 50) }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if ($customer->status)
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

                <!-- Add New Customer Button -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button type="button" id="addNewCustomerBtn"
                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="ti ti-plus text-lg mr-2"></i>
                        Tambah Pelanggan Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Customer Modal -->
    <div id="addCustomerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 xl:w-1/3 shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Tambah Pelanggan Baru</h3>
                    <button type="button" id="closeAddCustomerModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-2xl"></i>
                    </button>
                </div>

                <form id="addCustomerForm" class="space-y-4">
                    @csrf

                    <!-- Kode Pelanggan -->
                    <div>
                        <label for="new_kode_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Pelanggan
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-barcode text-lg text-gray-400"></i>
                            </div>
                            <input type="text" name="kode_pelanggan" id="new_kode_pelanggan"
                                value="{{ $kodePelanggan }}"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="Contoh: PEL2509001" maxlength="20" readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="ti ti-lock text-sm text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Format: PEL{YYMM}{001} (contoh: PEL2509001 untuk September 2025)
                        </p>
                    </div>

                    <!-- Nama Pelanggan -->
                    <div>
                        <label for="new_nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pelanggan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-user text-lg text-gray-400"></i>
                            </div>
                            <input type="text" name="nama" id="new_nama"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama pelanggan" maxlength="100" required>
                        </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="new_nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-phone text-lg text-gray-400"></i>
                            </div>
                            <input type="text" name="nomor_telepon" id="new_nomor_telepon"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nomor telepon" maxlength="20">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="new_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-mail text-lg text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="new_email"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan email" maxlength="100">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="new_alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="ti ti-map-pin text-lg text-gray-400"></i>
                            </div>
                            <textarea name="alamat" id="new_alamat" rows="3"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan alamat"></textarea>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="new_keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="ti ti-note text-lg text-gray-400"></i>
                            </div>
                            <textarea name="keterangan" id="new_keterangan" rows="2"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan keterangan"></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 pt-4">
                        <button type="button" id="cancelAddCustomer"
                            class="flex-1 px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="saveNewCustomer"
                            class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan
                        </button>
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
                    <h3 class="text-xl font-bold text-gray-900">Preview Pesanan</h3>
                    <button type="button" id="closeOrderPreviewModal" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-2xl"></i>
                    </button>
                </div>

                <!-- Customer Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <i class="ti ti-user text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900" id="previewCustomerName">-</h4>
                            <p class="text-sm text-gray-600" id="previewCustomerCode">-</p>
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
                                        class="p-3 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 preview-transaction-type-card">
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
                                        class="p-3 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 preview-transaction-type-card">
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
                                            class="p-3 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 preview-payment-method-card">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="ti {{ $metode->icon_display }} text-blue-600 text-sm"></i>
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

                            <!-- Card Scan Area for CARD payment method -->
                            <div id="cardScanArea" class="hidden">
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-dashed border-blue-300 rounded-xl p-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-credit-card text-blue-600 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tempelkan Kartu RFID</h3>
                                        <p class="text-sm text-gray-600 mb-4">Tempelkan kartu RFID Anda pada area scanner
                                            untuk melanjutkan pembayaran</p>
                                        <div
                                            class="w-full max-w-xs h-16 bg-white border-2 border-blue-200 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-scan text-blue-400 text-2xl animate-pulse"></i>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-3">Menunggu kartu terdeteksi...</p>
                                        <p class="text-xs text-blue-600 font-medium mt-1" id="rfidReadyIndicator">
                                            💳 Input RFID siap menerima data...
                                        </p>

                                        <!-- Input untuk menerima ID RFID -->
                                        <div class="mt-4 w-full hidden">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                ID Kartu RFID
                                            </label>
                                            <div class="flex gap-2">
                                                <input type="text" id="rfidCardId" name="rfid_card_id"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center font-mono text-sm"
                                                    placeholder="Tempelkan kartu RFID atau ketik ID manual">
                                                <button type="button" id="clearRfidBtn"
                                                    class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                                                    Clear
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Input akan terisi otomatis saat kartu RFID di-scan, atau ketik manual untuk
                                                testing
                                            </p>
                                            <!-- Progress indicator untuk 10 karakter -->
                                            <div class="mt-2">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-600">Progress RFID ID:</span>
                                                    <span class="text-xs text-gray-600" id="rfidProgress">0/10</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                                                        id="rfidProgressBar" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex gap-2">
                                                <button type="button" id="testRfidBtn"
                                                    class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                                                    Test RFID
                                                </button>
                                                <button type="button" id="simulateRfidBtn"
                                                    class="px-3 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600 transition-colors">
                                                    Simulate Scan
                                                </button>
                                                <button type="button" id="resetRfidBtn"
                                                    class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 transition-colors">
                                                    Reset
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Display area untuk menampilkan kartu kredit yang terdeteksi -->
                                        <div id="rfidDisplay" class="hidden mt-4">
                                            <div class="relative animate-fadeInUp card-container">
                                                <!-- Kartu Kredit -->
                                                <div class="w-full max-w-lg mx-auto bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300 relative border border-blue-500 card-pattern card-texture card-3d card-glow-effect card-pulse"
                                                    style="aspect-ratio: 1.586; min-width: 400px; min-height: 252px;">
                                                    <!-- Shine effect -->
                                                    <div
                                                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 hover:opacity-10 transition-opacity duration-500 transform -skew-x-12">
                                                    </div>
                                                    <!-- Subtle pattern overlay -->
                                                    <div class="absolute inset-0 opacity-5">
                                                        <div
                                                            class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -translate-y-16 translate-x-16">
                                                        </div>
                                                        <div
                                                            class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full translate-y-12 -translate-x-12">
                                                        </div>
                                                    </div>
                                                    <!-- Header Kartu -->
                                                    <div class="flex justify-between items-start p-5 relative z-10">
                                                        <div class="text-white card-emboss">
                                                            <h3 class="text-lg font-semibold">Kartu Siswa</h3>
                                                            <p class="text-sm text-gray-300"
                                                                style="color:white !important;">Koperasi Tsarwah</p>
                                                        </div>
                                                        <div class="text-right text-white">
                                                            <div
                                                                class="w-8 h-6 card-logo card-hologram rounded-sm flex items-center justify-center shadow-lg">
                                                                <div
                                                                    class="w-6 h-4 bg-yellow-300 rounded-sm flex items-center justify-center">
                                                                    <div class="w-4 h-3 bg-yellow-200 rounded-sm"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Chip EMV dan Nomor Kartu -->
                                                    <div class="px-5 pb-2 relative z-10 flex items-start gap-4">
                                                        <!-- Chip EMV -->
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="w-12 h-8 card-chip card-chip-hologram rounded-sm flex items-center justify-center shadow-lg">
                                                                <div
                                                                    class="w-10 h-6 bg-yellow-300 rounded-sm flex items-center justify-center">
                                                                    <div
                                                                        class="w-8 h-4 bg-yellow-200 rounded-sm flex items-center justify-center">
                                                                        <div class="w-6 h-3 bg-yellow-100 rounded-sm">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nomor Kartu -->
                                                        <div class="flex-1">
                                                            <div class="text-white font-mono text-xl tracking-wider card-emboss card-number"
                                                                id="rfidCardNumber">
                                                                3960 4221 7700 0000
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Nama Pemegang -->
                                                    <div class="px-5 pb-2 relative z-30">
                                                        <div class="text-white text-xl font-bold card-emboss card-name"
                                                            id="rfidCardholderName"
                                                            style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8); background: rgba(0,0,0,0.2); padding: 4px 8px; border-radius: 4px; display: inline-block;">
                                                            -
                                                        </div>
                                                    </div>

                                                    <!-- Decorative Elements -->
                                                    {{-- <div class="px-5 pb-2 relative z-5">
                                                        <!-- Magnetic Strip -->
                                                        <div class="card-magnetic"></div>
                                                        <!-- Security Strip -->
                                                        <div class="card-security"></div>
                                                    </div> --}}

                                                    <!-- Valid Thru and Balance -->
                                                    <div class="px-5 pb-5 flex justify-between items-end relative z-10">
                                                        <div class="text-white text-sm card-emboss">
                                                            <!-- Saldo -->
                                                            <div class="mt-2">
                                                                <div class="text-xs text-gray-300">SALDO</div>
                                                                <div class="font-mono text-sm font-bold"
                                                                    id="rfidBalanceDisplay">
                                                                    Rp 0
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-white text-right card-emboss">
                                                            <div class="text-xs text-gray-300">RFID ID</div>
                                                            <div class="font-mono text-sm card-id" id="rfidCardIdDisplay">
                                                                -</div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-3" id="previewKasBankContainer">
                                @foreach ($kasBank ?? [] as $kas)
                                    <label class="relative cursor-pointer preview-kas-bank-option">
                                        <input type="radio" name="preview_kas_bank" value="{{ $kas->id }}"
                                            data-jenis="{{ $kas->jenis }}" data-image="{{ $kas->image_url ?? '' }}"
                                            class="sr-only preview-kas-bank-radio">
                                        <div
                                            class="p-4 border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 preview-kas-bank-card flex items-center justify-between shadow-sm hover:shadow-md">
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
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Amount -->
                        <div class="hidden" id="previewDpContainer">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span id="paymentAmountLabel">Jumlah Pembayaran</span>
                            </label>
                            <input type="text" id="previewDpAmount"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-right text-sm"
                                placeholder="Jumlah (Rp)" value="0">
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Ringkasan Pembayaran</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="previewSubtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm" id="previewDiscountRow" style="display: none;">
                            <span class="text-gray-600">Diskon Keseluruhan</span>
                            <span class="font-medium text-red-600" id="previewDiscount">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                            <span>Total</span>
                            <span class="text-blue-600" id="previewTotal">Rp 0</span>
                        </div>

                        <!-- DP & Remaining (for kredit) -->
                        <div id="previewPaymentBreakdown" class="hidden border-t border-gray-200 pt-2 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-green-600">DP Dibayar</span>
                                <span class="font-medium text-green-600" id="previewDP">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-orange-600">Sisa Pembayaran</span>
                                <span class="font-medium text-orange-600" id="previewRemaining">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="button" id="cancelOrderPreview"
                        class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        <i class="ti ti-arrow-left text-lg mr-2"></i>
                        Kembali Edit
                    </button>
                    <button type="button" id="confirmOrderSave"
                        class="flex-1 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        <i class="ti ti-device-floppy text-lg mr-2"></i>
                        Konfirmasi & Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .category-filter.active {
            background-color: #2563eb;
            color: white;
        }

        .product-card {
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }

        .product-card.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
        }

        .product-card .add-product-btn {
            transition: all 0.3s ease;
        }

        .product-card:hover .add-product-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .product-card .add-product-btn {
            transform: translateY(10px);
        }

        /* Custom scrollbar */
        #orderItems::-webkit-scrollbar {
            width: 4px;
        }

        #orderItems::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        #orderItems::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        #orderItems::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Quantity buttons */
        .qty-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            transform: scale(1.1);
        }

        /* Order item animation */
        .order-item {
            animation: slideIn 0.3s ease;
            transition: all 0.2s ease;
        }

        .order-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        /* Order item layout improvements */
        .order-item .space-y-2>*+* {
            margin-top: 0.5rem;
        }

        /* Quantity badge styling */
        .order-item .bg-blue-50 {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 1px solid #93c5fd;
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
        .order-item.ring-2 {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        /* Disable product card when product is already in order */
        .product-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
            position: relative;
        }

        .product-card.disabled::after {
            content: "✓ Sudah di pesanan";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            z-index: 10;
            white-space: nowrap;
        }

        /* Price breakdown styling */
        .order-item .text-orange-600 {
            font-weight: 500;
        }

        .order-item .font-bold.text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
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
        .toast {
            min-width: 250px;
        }

        /* Customer modal styles */
        .customer-item {
            transition: all 0.2s ease;
        }

        .customer-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .customer-item.selected {
            background-color: #dbeafe;
            border-color: #3b82f6;
        }

        /* Modal animation */
        #customerModal {
            backdrop-filter: blur(4px);
        }

        #customerModal>div {
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar for customer list */
        #customerList::-webkit-scrollbar {
            width: 6px;
        }

        #customerList::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        #customerList::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        #customerList::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Quantity Modal Styles */
        #quantityModal {
            backdrop-filter: blur(4px);
        }

        #quantityModal>div {
            animation: quantityModalSlideIn 0.3s ease;
        }

        @keyframes quantityModalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50%) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(-50%) scale(1);
            }
        }

        /* Quantity input buttons */
        #decreaseQty,
        #increaseQty {
            transition: all 0.2s ease;
            min-width: 48px;
            min-height: 48px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #decreaseQty:hover,
        #increaseQty:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        #decreaseQty:active,
        #increaseQty:active {
            transform: scale(0.95);
        }

        #decreaseQty {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        #decreaseQty:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        #increaseQty {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        #increaseQty:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        /* Input group with unit */
        #quantityInput {
            padding-right: 70px !important;
        }

        #modalProductUnitInInput {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border: 1px solid #d1d5db;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        #quantityInput {
            -moz-appearance: textfield;
        }

        #quantityInput::-webkit-outer-spin-button,
        #quantityInput::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Quantity input focus effects */
        #quantityInput:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Modal product info styling */
        #modalProductInfo {
            transition: all 0.2s ease;
        }

        /* Total price preview animation */
        #modalTotalPrice {
            transition: all 0.3s ease;
        }



        /* Category badge hover effect */
        .product-card .inline-flex {
            transition: all 0.3s ease;
        }

        .product-card:hover .inline-flex {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }









        /* Barcode search styling */
        #barcodeSearch:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        #scanButton:hover .w-8 {
            transform: scale(1.05);
        }

        /* Search form animations */
        .search-form input {
            transition: all 0.2s ease;
        }

        .search-form input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Order Preview Modal Styles */
        #orderPreviewModal {
            backdrop-filter: blur(4px);
        }

        #orderPreviewModal>div {
            animation: previewModalSlideIn 0.3s ease;
        }

        @keyframes previewModalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Preview order items styling */
        #previewOrderItems::-webkit-scrollbar {
            width: 6px;
        }

        #previewOrderItems::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        #previewOrderItems::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        #previewOrderItems::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Product grid responsive */
        @media (max-width: 1280px) {
            #productsGrid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 1024px) {
            #productsGrid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            #productsGrid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .w-96 {
                width: 100%;
                max-width: 384px;
            }

            .flex {
                flex-direction: column;
            }

            .flex-1 {
                margin-bottom: 1rem;
            }
        }

        /* RFID Card Animations */
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-glow {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .card-hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .card-pattern {
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }

        .card-texture {
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(255, 255, 255, 0.03) 2px, rgba(255, 255, 255, 0.03) 4px);
        }

        .card-emboss {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .card-chip {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .card-logo {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .card-number {
            letter-spacing: 0.1em;
            font-weight: 600;
        }

        .card-name {
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ffffff !important;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            position: relative;
            z-index: 30;
        }

        .card-expiry {
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .card-id {
            font-weight: 500;
            letter-spacing: 0.05em;
        }

        .card-status {
            animation: pulse 2s infinite;
        }

        .card-container {
            perspective: 1000px;
        }

        .card-3d {
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .card-3d:hover {
            transform: rotateY(5deg) rotateX(5deg);
        }

        .card-magnetic {
            background: linear-gradient(90deg, #1f2937 0%, #374151 50%, #1f2937 100%);
            height: 2px;
            margin: 8px 0;
            border-radius: 1px;
        }

        .card-security {
            background: repeating-linear-gradient(90deg, #1f2937, #1f2937 2px, #374151 2px, #374151 4px);
            height: 1px;
            margin: 4px 0;
            border-radius: 0.5px;
        }

        .card-hologram {
            background: linear-gradient(45deg, #fbbf24, #f59e0b, #d97706, #fbbf24);
            background-size: 400% 400%;
            animation: hologram 3s ease-in-out infinite;
        }

        @keyframes hologram {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .card-chip-hologram {
            background: linear-gradient(45deg, #fbbf24, #f59e0b, #d97706, #fbbf24);
            background-size: 400% 400%;
            animation: hologram 3s ease-in-out infinite;
        }

        .card-glow-effect {
            box-shadow:
                0 0 20px rgba(59, 130, 246, 0.3),
                0 0 40px rgba(59, 130, 246, 0.2),
                0 0 60px rgba(59, 130, 246, 0.1);
        }

        .card-pulse {
            animation: cardPulse 2s ease-in-out infinite;
        }

        .card-success {
            animation: cardSuccess 0.6s ease-out;
        }

        .card-flip {
            animation: cardFlip 0.8s ease-in-out;
        }

        .card-bounce {
            animation: cardBounce 0.5s ease-out;
        }

        .card-shake {
            animation: cardShake 0.5s ease-in-out;
        }

        .card-rotate {
            animation: cardRotate 1s ease-in-out;
        }

        .card-final {
            animation: cardFinal 0.5s ease-out;
        }

        .card-complete {
            animation: cardComplete 0.3s ease-out;
        }

        .card-stable {
            animation: none;
            transform: scale(1);
            box-shadow:
                0 0 20px rgba(59, 130, 246, 0.3),
                0 0 40px rgba(59, 130, 246, 0.2),
                0 0 60px rgba(59, 130, 246, 0.1);
        }

        .card-ready {
            animation: cardReady 0.2s ease-out;
        }

        .card-perfect {
            animation: cardPerfect 0.1s ease-out;
        }

        .card-ultimate {
            animation: cardUltimate 0.05s ease-out;
        }

        .card-final-state {
            animation: none;
            transform: scale(1);
            box-shadow:
                0 0 20px rgba(59, 130, 246, 0.3),
                0 0 40px rgba(59, 130, 246, 0.2),
                0 0 60px rgba(59, 130, 246, 0.1);
            transition: all 0.3s ease;
        }

        .card-masterpiece {
            animation: cardMasterpiece 0.02s ease-out;
        }

        .card-legendary {
            animation: cardLegendary 0.01s ease-out;
        }

        .card-epic {
            animation: cardEpic 0.005s ease-out;
        }

        .card-mythical {
            animation: cardMythical 0.001s ease-out;
        }

        .card-divine {
            animation: cardDivine 0.0005s ease-out;
        }

        .card-transcendent {
            animation: cardTranscendent 0.0001s ease-out;
        }

        .card-omnipotent {
            animation: cardOmnipotent 0.00001s ease-out;
        }

        .card-infinite {
            animation: cardInfinite 0.000001s ease-out;
        }

        .card-absolute {
            animation: cardAbsolute 0.0000001s ease-out;
        }

        .card-ultimate-final {
            animation: cardUltimateFinal 0.00000001s ease-out;
        }

        .card-perfect-final {
            animation: cardPerfectFinal 0.000000001s ease-out;
        }

        .card-immortal {
            animation: cardImmortal 0.0000000001s ease-out;
        }

        .card-eternal {
            animation: cardEternal 0.00000000001s ease-out;
        }

        .card-ultimate-absolute {
            animation: cardUltimateAbsolute 0.000000000001s ease-out;
        }

        .card-perfect-ultimate {
            animation: cardPerfectUltimate 0.0000000000001s ease-out;
        }

        .card-absolute-perfect {
            animation: cardAbsolutePerfect 0.00000000000001s ease-out;
        }

        .card-ultimate-perfect {
            animation: cardUltimatePerfect 0.000000000000001s ease-out;
        }

        .card-absolute-ultimate {
            animation: cardAbsoluteUltimate 0.0000000000000001s ease-out;
        }

        .card-perfect-absolute {
            animation: cardPerfectAbsolute 0.00000000000000001s ease-out;
        }

        .card-ultimate-absolute-perfect {
            animation: cardUltimateAbsolutePerfect 0.000000000000000001s ease-out;
        }

        .card-absolute-perfect-ultimate {
            animation: cardAbsolutePerfectUltimate 0.0000000000000000001s ease-out;
        }

        @keyframes cardAbsolutePerfectUltimate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00000000000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardUltimateAbsolutePerfect {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0000000000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardPerfectAbsolute {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.000000000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardAbsoluteUltimate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00000000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardUltimatePerfect {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0000000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardAbsolutePerfect {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.000000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardPerfectUltimate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardUltimateAbsolute {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardEternal {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.000000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardImmortal {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardPerfectFinal {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardUltimateFinal {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.000000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardAbsolute {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardInfinite {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardOmnipotent {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.000001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardTranscendent {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardDivine {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.00005);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardMythical {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardEpic {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0002);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardLegendary {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.0005);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardMasterpiece {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.001);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardUltimate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.002);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardPerfect {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.005);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardReady {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.01);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardComplete {
            0% {
                transform: scale(1);
                box-shadow:
                    0 0 20px rgba(59, 130, 246, 0.3),
                    0 0 40px rgba(59, 130, 246, 0.2),
                    0 0 60px rgba(59, 130, 246, 0.1);
            }

            50% {
                transform: scale(1.02);
                box-shadow:
                    0 0 30px rgba(59, 130, 246, 0.4),
                    0 0 50px rgba(59, 130, 246, 0.3),
                    0 0 70px rgba(59, 130, 246, 0.2);
            }

            100% {
                transform: scale(1);
                box-shadow:
                    0 0 20px rgba(59, 130, 246, 0.3),
                    0 0 40px rgba(59, 130, 246, 0.2),
                    0 0 60px rgba(59, 130, 246, 0.1);
            }
        }

        @keyframes cardFinal {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes cardRotate {
            0% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(5deg);
            }

            50% {
                transform: rotate(-5deg);
            }

            75% {
                transform: rotate(3deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        @keyframes cardShake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-2px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(2px);
            }
        }

        @keyframes cardBounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes cardFlip {
            0% {
                transform: rotateY(0deg);
            }

            50% {
                transform: rotateY(180deg);
            }

            100% {
                transform: rotateY(360deg);
            }
        }

        @keyframes cardSuccess {
            0% {
                transform: scale(0.8) rotateY(180deg);
                opacity: 0;
            }

            50% {
                transform: scale(1.1) rotateY(90deg);
                opacity: 0.8;
            }

            100% {
                transform: scale(1) rotateY(0deg);
                opacity: 1;
            }
        }

        @keyframes cardPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow:
                    0 0 20px rgba(59, 130, 246, 0.3),
                    0 0 40px rgba(59, 130, 246, 0.2),
                    0 0 60px rgba(59, 130, 246, 0.1);
            }

            50% {
                transform: scale(1.02);
                box-shadow:
                    0 0 30px rgba(59, 130, 246, 0.4),
                    0 0 50px rgba(59, 130, 246, 0.3),
                    0 0 70px rgba(59, 130, 246, 0.2);
            }
        }

        .card-shine {
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let productIndex = 0;
        let orderItems = [];
        let currentProduct = null;
        let editingItemIndex = null;

        // Initialize product card states
        document.addEventListener('DOMContentLoaded', function() {
            updateProductCardStates();

            // Initialize Flatpickr for date input
            flatpickr("#tanggal", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                todayHighlight: true,
                maxDate: "today",
                defaultDate: "today",
                animate: "slideDown",
                disableMobile: false,
                enableTime: false,
                time_24hr: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Update hidden input for form submission
                    const dateInput = document.querySelector('input[name="tanggal"]');
                    if (selectedDates[0]) {
                        const year = selectedDates[0].getFullYear();
                        const month = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                        const day = String(selectedDates[0].getDate()).padStart(2, '0');
                        dateInput.value = `${year}-${month}-${day}`;

                        // Add visual feedback
                        const input = document.getElementById('tanggal');
                        input.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
                        setTimeout(() => {
                            input.classList.remove('ring-2', 'ring-blue-500',
                                'ring-opacity-50');
                        }, 300);
                    }
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    // Add smooth animation
                    const calendar = document.querySelector('.flatpickr-calendar');
                    if (calendar) {
                        calendar.style.opacity = '0';
                        calendar.style.transform = 'scale(0.95) translateY(-10px)';
                        setTimeout(() => {
                            calendar.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                            calendar.style.opacity = '1';
                            calendar.style.transform = 'scale(1) translateY(0)';
                        }, 10);
                    }
                }
            });
        });

        // Customer modal functionality
        const customerModal = document.getElementById('customerModal');
        const searchCustomerBtn = document.getElementById('searchCustomerBtn');
        const closeCustomerModal = document.getElementById('closeCustomerModal');
        const customerSearch = document.getElementById('customerSearch');
        const customerDisplay = document.getElementById('customerDisplay');
        const pelangganId = document.getElementById('pelangganId');
        const clearCustomerBtn = document.getElementById('clearCustomerBtn');

        // Open customer modal
        searchCustomerBtn.addEventListener('click', () => {
            customerModal.classList.remove('hidden');
            customerSearch.focus();
        });

        // Close customer modal
        closeCustomerModal.addEventListener('click', () => {
            customerModal.classList.add('hidden');
        });

        // Clear customer selection
        clearCustomerBtn.addEventListener('click', () => {
            pelangganId.value = '';
            customerDisplay.value = '';
            clearCustomerBtn.classList.add('hidden');

            // Remove selection styling from all customer items
            document.querySelectorAll('.customer-item').forEach(item => {
                item.classList.remove('bg-blue-100', 'border-blue-500');
            });

            showToast('Pilihan pelanggan dibersihkan', 'info');
        });

        // Close modal when clicking outside
        customerModal.addEventListener('click', (e) => {
            if (e.target === customerModal) {
                customerModal.classList.add('hidden');
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !customerModal.classList.contains('hidden')) {
                customerModal.classList.add('hidden');
            }
        });

        // Customer search functionality
        customerSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const customerItems = document.querySelectorAll('.customer-item');

            customerItems.forEach(item => {
                const name = item.dataset.name.toLowerCase();
                const code = item.dataset.code.toLowerCase();
                const phone = item.dataset.phone.toLowerCase();

                if (name.includes(searchTerm) || code.includes(searchTerm) || phone.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Customer selection
        document.querySelectorAll('.customer-item').forEach(item => {
            item.addEventListener('click', () => {
                const customerId = item.dataset.id;
                const customerName = item.dataset.name;
                const customerCode = item.dataset.code;

                // Update form fields
                pelangganId.value = customerId;
                customerDisplay.value = `${customerName} (${customerCode})`;

                // Show clear button
                clearCustomerBtn.classList.remove('hidden');

                // Close modal
                customerModal.classList.add('hidden');

                // Show success message
                showToast(`Pelanggan ${customerName} dipilih`, 'success');

                // Remove previous selection styling
                document.querySelectorAll('.customer-item').forEach(i => {
                    i.classList.remove('bg-blue-100', 'border-blue-500');
                });

                // Add selection styling
                item.classList.add('bg-blue-100', 'border-blue-500');
            });
        });

        // Set initial customer if old value exists
        @if (old('pelanggan_id'))
            const initialCustomerId = '{{ old('pelanggan_id') }}';
            const initialCustomerItem = document.querySelector(`[data-id="${initialCustomerId}"]`);
            if (initialCustomerItem) {
                const customerName = initialCustomerItem.dataset.name;
                const customerCode = initialCustomerItem.dataset.code;
                customerDisplay.value = `${customerName} (${customerCode})`;
                clearCustomerBtn.classList.remove('hidden');
                initialCustomerItem.classList.add('bg-blue-100', 'border-blue-500');
            }
        @endif

        // Add New Customer Modal functionality
        const addCustomerModal = document.getElementById('addCustomerModal');
        const addNewCustomerBtn = document.getElementById('addNewCustomerBtn');
        const closeAddCustomerModal = document.getElementById('closeAddCustomerModal');
        const cancelAddCustomer = document.getElementById('cancelAddCustomer');
        const addCustomerForm = document.getElementById('addCustomerForm');

        // Open add customer modal
        addNewCustomerBtn.addEventListener('click', () => {
            addCustomerModal.classList.remove('hidden');
            document.getElementById('new_nama').focus();
        });

        // Close add customer modal
        function closeAddCustomerModalHandler() {
            addCustomerModal.classList.add('hidden');
            addCustomerForm.reset();
            document.getElementById('new_kode_pelanggan').value = '{{ $kodePelanggan }}';
        }

        closeAddCustomerModal.addEventListener('click', closeAddCustomerModalHandler);
        cancelAddCustomer.addEventListener('click', closeAddCustomerModalHandler);

        // Close modal when clicking outside
        addCustomerModal.addEventListener('click', (e) => {
            if (e.target === addCustomerModal) {
                closeAddCustomerModalHandler();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !addCustomerModal.classList.contains('hidden')) {
                closeAddCustomerModalHandler();
            }
        });

        // Handle add customer form submission
        addCustomerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const saveBtn = document.getElementById('saveNewCustomer');
            const originalText = saveBtn.innerHTML;

            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...';

            try {
                const formData = new FormData(addCustomerForm);

                const response = await fetch('{{ route('pelanggan.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // Close modal
                    closeAddCustomerModalHandler();

                    // Add new customer to the list
                    const customerList = document.getElementById('customerList');
                    const newCustomerHtml = `
                        <div class="customer-item p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all duration-200"
                            data-id="${result.pelanggan.id}" data-name="${result.pelanggan.nama}"
                            data-code="${result.pelanggan.kode_pelanggan}"
                            data-phone="${result.pelanggan.nomor_telepon || ''}"
                            data-address="${result.pelanggan.alamat || ''}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                            <i class="ti ti-user text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">${result.pelanggan.nama}</h4>
                                            <p class="text-sm text-gray-500">${result.pelanggan.kode_pelanggan}</p>
                                            ${result.pelanggan.nomor_telepon ? `<p class="text-xs text-gray-400">${result.pelanggan.nomor_telepon}</p>` : ''}
                                        </div>
                                    </div>
                                    ${result.pelanggan.alamat ? `<p class="text-xs text-gray-500 mt-2 ml-13">${result.pelanggan.alamat.substring(0, 50)}${result.pelanggan.alamat.length > 50 ? '...' : ''}</p>` : ''}
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Baru</span>
                                </div>
                            </div>
                        </div>
                    `;

                    customerList.insertAdjacentHTML('beforeend', newCustomerHtml);

                    // Add click event to new customer item
                    const newCustomerItem = customerList.lastElementChild;
                    newCustomerItem.addEventListener('click', () => {
                        const customerId = newCustomerItem.dataset.id;
                        const customerName = newCustomerItem.dataset.name;
                        const customerCode = newCustomerItem.dataset.code;

                        // Update form fields
                        pelangganId.value = customerId;
                        customerDisplay.value = `${customerName} (${customerCode})`;

                        // Show clear button
                        clearCustomerBtn.classList.remove('hidden');

                        // Close customer selection modal
                        customerModal.classList.add('hidden');

                        // Show success message
                        showToast(`Pelanggan ${customerName} dipilih`, 'success');

                        // Remove previous selection styling
                        document.querySelectorAll('.customer-item').forEach(i => {
                            i.classList.remove('bg-blue-100', 'border-blue-500');
                        });

                        // Add selection styling
                        newCustomerItem.classList.add('bg-blue-100', 'border-blue-500');
                    });

                    showToast('Pelanggan berhasil ditambahkan', 'success');
                } else {
                    showToast(result.message || 'Gagal menambahkan pelanggan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menambahkan pelanggan', 'error');
            } finally {
                // Reset button
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        });



        // Setup number formatting for input fields
        const diskonDisplay = document.getElementById('diskonDisplay');
        const diskonHidden = document.getElementById('diskon');

        setupNumberInput(diskonDisplay);

        // Update hidden fields when display fields change
        diskonDisplay.addEventListener('input', function() {
            diskonHidden.value = parseFormattedNumber(this.value);
            updateOrderSummary();
        });

        // Format initial values
        if (diskonDisplay.value && diskonDisplay.value !== '0') {
            diskonDisplay.value = formatNumberInput(diskonDisplay.value);
        }

        // Category filter functionality
        document.querySelectorAll('.category-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const category = this.dataset.category;
                filterProducts(category);
            });
        });

        // Product search functionality
        function searchProducts(searchTerm) {
            const products = document.querySelectorAll('.product-card');
            const term = searchTerm.toLowerCase();

            products.forEach(product => {
                const name = product.dataset.name.toLowerCase();
                const code = product.dataset.code.toLowerCase();

                if (name.includes(term) || code.includes(term)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Product search by name/SKU
        document.getElementById('productSearch').addEventListener('input', function(e) {
            searchProducts(e.target.value);
            // Clear barcode search when typing in product search
            if (e.target.value) {
                document.getElementById('barcodeSearch').value = '';
            }
        });

        // Barcode search functionality
        document.getElementById('barcodeSearch').addEventListener('input', function(e) {
            const barcode = e.target.value.trim();

            if (barcode) {
                // Clear product search when typing in barcode
                document.getElementById('productSearch').value = '';

                // Search by barcode (assuming barcode is stored in kode_produk)
                searchProducts(barcode);

                // Auto-add product if exact match found
                if (barcode.length >= 8) { // Assuming minimum barcode length
                    const exactMatch = Array.from(document.querySelectorAll('.product-card')).find(product =>
                        product.dataset.code.toLowerCase() === barcode.toLowerCase()
                    );

                    if (exactMatch && exactMatch.style.display !== 'none') {
                        // Show quantity modal for the product
                        const productData = {
                            id: exactMatch.dataset.id,
                            name: exactMatch.dataset.name,
                            code: exactMatch.dataset.code,
                            price: parseFloat(exactMatch.dataset.price),
                            stock: parseInt(exactMatch.dataset.stock),
                            unit: exactMatch.dataset.unit
                        };

                        showQuantityModal(productData);

                        // Clear barcode field after showing modal
                        this.value = '';
                    }
                }
            } else {
                // Show all products if barcode field is empty
                document.querySelectorAll('.product-card').forEach(product => {
                    product.style.display = 'block';
                });
            }
        });

        // Scan button functionality (placeholder for actual barcode scanner integration)
        document.getElementById('scanButton').addEventListener('click', function() {
            const barcodeInput = document.getElementById('barcodeSearch');
            barcodeInput.focus();
            showToast('Silakan scan barcode atau ketik manual', 'info');
        });

        // Filter products by category
        function filterProducts(category) {
            const products = document.querySelectorAll('.product-card');

            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Quantity Modal functionality
        const quantityModal = document.getElementById('quantityModal');
        const closeQuantityModal = document.getElementById('closeQuantityModal');
        const cancelQuantity = document.getElementById('cancelQuantity');
        const confirmQuantity = document.getElementById('confirmQuantity');
        const quantityInput = document.getElementById('quantityInput');
        const decreaseQty = document.getElementById('decreaseQty');
        const increaseQty = document.getElementById('increaseQty');

        // Product click handler - show quantity modal
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                // Visual feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);

                const productData = {
                    id: this.dataset.id,
                    name: this.dataset.name,
                    code: this.dataset.code,
                    price: parseFloat(this.dataset.price),
                    stock: parseInt(this.dataset.stock),
                    unit: this.dataset.unit
                };

                // Check if product already exists in order
                const existingIndex = orderItems.findIndex(item => item.id === productData.id);

                if (existingIndex !== -1) {
                    // Product already exists - show message and don't open modal
                    showToast(
                        `${productData.name} sudah ada di pesanan. Klik item di ringkasan pesanan untuk mengubah quantity.`,
                        'info');

                    // Highlight the existing item in the order summary
                    highlightOrderItem(existingIndex);

                    // Prevent modal from opening
                    return;
                }

                showQuantityModal(productData);
            });
        });

        // Show quantity modal
        function showQuantityModal(product, currentQty = 1, currentDiscount = 0, currentPrice = null) {
            currentProduct = product;

            // Update modal content
            document.getElementById('modalProductName').textContent = product.name;
            document.getElementById('modalProductCode').textContent = product.code;
            document.getElementById('modalProductPrice').textContent = `Rp ${formatNumber(product.price)}`;

            document.getElementById('modalProductUnit').textContent = product.unit;

            // Update unit in input group
            document.getElementById('modalProductUnitInInput').textContent = product.unit;

            // Set price (use current price if editing, otherwise use default)
            const priceInput = document.getElementById('modalPriceInput');
            const defaultPrice = currentPrice || product.price;
            priceInput.value = formatNumberInput(defaultPrice.toString());
            document.getElementById('modalDefaultPrice').textContent = `Rp ${formatNumber(product.price)}`;

            // Set quantity (for edit mode or new item)
            quantityInput.value = formatDecimalInput(currentQty);

            // Set discount
            const discountInput = document.getElementById('discountInput');
            discountInput.value = formatNumberInput(currentDiscount.toString());

            // Update modal title based on mode
            const modalTitle = document.querySelector('#quantityModal h3');
            if (editingItemIndex !== null) {
                modalTitle.textContent = 'Edit Quantity';
                document.getElementById('confirmQuantity').innerHTML =
                    '<i class="ti ti-check text-sm mr-1"></i>Update Quantity';
            } else {
                modalTitle.textContent = 'Masukkan Quantity';
                document.getElementById('confirmQuantity').innerHTML =
                    '<i class="ti ti-plus text-sm mr-1"></i>Tambah ke Keranjang';
            }

            // Update total price
            updateModalTotalPrice();

            // Show modal
            quantityModal.classList.remove('hidden');
            quantityInput.focus();
            quantityInput.select();
        }

        // Close quantity modal
        function closeQuantityModalHandler() {
            // Only close if user explicitly wants to close (not during input)
            quantityModal.classList.add('hidden');
            currentProduct = null;
            editingItemIndex = null;
        }

        // Update modal total price
        function updateModalTotalPrice() {
            if (currentProduct) {
                const qty = parseFormattedDecimal(quantityInput.value) || 0;
                const discount = parseFormattedNumber(document.getElementById('discountInput').value) || 0;
                const currentPrice = parseFormattedNumber(document.getElementById('modalPriceInput').value) ||
                    currentProduct.price;
                const subtotal = currentPrice * qty;
                const total = Math.max(0, subtotal - discount);

                // Update subtotal
                document.getElementById('modalSubtotalPrice').textContent = `Rp ${formatNumber(subtotal)}`;

                // Show/hide discount row
                const discountRow = document.getElementById('modalDiscountRow');
                const discountPriceElement = document.getElementById('modalDiscountPrice');

                if (discount > 0) {
                    discountRow.style.display = 'flex';
                    discountPriceElement.textContent = `Rp ${formatNumber(discount)}`;
                } else {
                    discountRow.style.display = 'none';
                }



                // Update total
                document.getElementById('modalTotalPrice').textContent = `Rp ${formatNumber(total)}`;

                // Validate discount doesn't exceed subtotal
                if (discount > subtotal && subtotal > 0) {
                    document.getElementById('discountInput').style.borderColor = '#ef4444';
                    document.getElementById('discountInput').style.backgroundColor = '#fef2f2';
                } else {
                    document.getElementById('discountInput').style.borderColor = '#d1d5db';
                    document.getElementById('discountInput').style.backgroundColor = '#ffffff';
                }
            }
        }

        // Quantity modal event listeners
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

        // Setup quantity input with decimal formatting
        setupDecimalInput(quantityInput);

        // Setup discount input with number formatting
        const discountInput = document.getElementById('discountInput');
        setupNumberInput(discountInput);

        // Setup price input with number formatting
        const priceInput = document.getElementById('modalPriceInput');
        setupNumberInput(priceInput);

        // Price input handlers
        priceInput.addEventListener('input', function() {
            updateModalTotalPrice();
        });

        // Quantity input handlers
        quantityInput.addEventListener('input', function() {
            let numericValue = parseFormattedDecimal(this.value);

            if (numericValue < 0) numericValue = 0;

            updateModalTotalPrice();
        });

        // Discount input handlers
        discountInput.addEventListener('input', function() {
            updateModalTotalPrice();
        });

        // No need for editing flags since modal won't close on outside click

        // Decrease quantity button
        decreaseQty.addEventListener('click', function() {
            let value = parseFormattedDecimal(quantityInput.value) || 1;
            if (value > 0.1) {
                // Decrease by 1 for values >= 1, by 0.1 for decimal values
                let newValue = value >= 1 ? value - 1 : Math.max(0.1, Math.round((value - 0.1) * 10) / 10);

                // Format and set the value properly
                let formattedValue = formatDecimalInput(newValue);
                quantityInput.value = formattedValue;

                // Trigger input event to ensure proper formatting
                quantityInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                updateModalTotalPrice();
            }
        });

        // Increase quantity button
        increaseQty.addEventListener('click', function() {
            let value = parseFormattedDecimal(quantityInput.value) || 1;

            // Increase by 1 for integer values, by 0.1 for decimal values
            let newValue = value % 1 === 0 ? value + 1 : Math.round((value + 0.1) * 10) / 10;

            // Format and set the value properly
            let formattedValue = formatDecimalInput(newValue);
            quantityInput.value = formattedValue;

            // Trigger input event to ensure proper formatting
            quantityInput.dispatchEvent(new Event('input', {
                bubbles: true
            }));
            updateModalTotalPrice();
        });

        // Confirm quantity and add to order
        confirmQuantity.addEventListener('click', function() {
            if (currentProduct) {
                const qty = parseFormattedDecimal(quantityInput.value) || 1;
                const discount = parseFormattedNumber(document.getElementById('discountInput').value) || 0;
                const currentPrice = parseFormattedNumber(document.getElementById('modalPriceInput').value) ||
                    currentProduct.price;

                if (qty <= 0) {
                    showToast('Quantity harus lebih dari 0!', 'error');
                    return;
                }

                if (currentPrice <= 0) {
                    showToast('Harga harus lebih dari 0!', 'error');
                    return;
                }

                // Validate discount
                const subtotal = currentPrice * qty;
                if (discount > subtotal) {
                    showToast('Potongan tidak boleh melebihi subtotal!', 'error');
                    return;
                }



                if (editingItemIndex !== null) {
                    // Edit existing item
                    updateOrderItemQuantity(editingItemIndex, qty, discount, currentPrice);
                } else {
                    // Add new item
                    addToOrder(currentProduct, qty, discount, currentPrice);
                }

                closeQuantityModalHandler();
            }
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

        function addToOrder(product, quantity = 1, discount = 0, customPrice = null) {
            // Check if product already in order
            const existingIndex = orderItems.findIndex(item => item.id === product.id);

            if (existingIndex !== -1) {
                // For existing items, replace with new values
                orderItems[existingIndex].qty = quantity;
                orderItems[existingIndex].discount = discount;
                orderItems[existingIndex].price = customPrice || product.price;
                updateOrderItem(existingIndex);
                showToast(`${product.name} diperbarui dalam pesanan`, 'success');
            } else {
                // Add new item
                const newItem = {
                    ...product,
                    qty: quantity,
                    discount: discount,
                    price: customPrice || product.price,
                    index: productIndex++
                };
                orderItems.push(newItem);
                renderOrderItem(newItem);
                showToast(`${product.name} ditambahkan ke pesanan (${quantity} ${product.unit})`, 'success');
            }

            updateOrderSummary();
        }

        // Highlight order item for better UX
        function highlightOrderItem(index) {
            // Remove previous highlights
            document.querySelectorAll('.order-item').forEach(item => {
                item.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50', 'bg-blue-50');
            });

            // Add highlight to current item
            const orderItem = document.querySelector(`[data-index="${index}"]`);
            if (orderItem) {
                orderItem.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50', 'bg-blue-50');

                // Scroll to the item if it's not visible
                orderItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });

                // Remove highlight after 3 seconds
                setTimeout(() => {
                    orderItem.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50', 'bg-blue-50');
                }, 3000);
            }
        }

        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className =
                `toast fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;

            if (type === 'success') {
                toast.className += ' bg-green-500 text-white';
                toast.innerHTML = `<i class="ti ti-check-circle mr-2"></i>${message}`;
            } else if (type === 'error') {
                toast.className += ' bg-red-500 text-white';
                toast.innerHTML = `<i class="ti ti-alert-circle mr-2"></i>${message}`;
            } else if (type === 'warning') {
                toast.className += ' bg-orange-500 text-white';
                toast.innerHTML = `<i class="ti ti-alert-triangle mr-2"></i>${message}`;
            } else {
                toast.className += ' bg-blue-500 text-white';
                toast.innerHTML = `<i class="ti ti-info-circle mr-2"></i>${message}`;
            }

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }

        function renderOrderItem(item) {
            const orderItemsContainer = document.getElementById('orderItems');
            const emptyState = document.getElementById('emptyState');

            emptyState.style.display = 'none';

            const subtotal = item.price * item.qty;
            const discount = item.discount || 0;
            const total = subtotal - discount;

            const itemHtml = `
                <div class="order-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer" data-index="${item.index}">
                    <!-- Product Header -->
                    <div class="flex items-start justify-between mb-3 edit-item-area">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm">${item.name}</h4>
                            <p class="text-xs text-gray-500 mt-1">${item.code}</p>
                    </div>
                        <div class="flex items-center space-x-2">
                        <div class="text-center edit-item-area">
                                <div class="bg-blue-50 px-3 py-1 rounded-full">
                                    <span class="qty text-sm font-semibold text-blue-700">${formatDecimalInput(item.qty)}</span>
                                    <span class="text-xs text-blue-600 ml-1">${item.unit}</span>
                        </div>
                            </div>
                            <button type="button" class="remove-btn w-8 h-8 bg-red-500 rounded-full text-white hover:bg-red-600 flex items-center justify-center transition-colors" onclick="event.stopPropagation()">
                            <i class="ti ti-trash text-sm"></i>
                        </button>
                    </div>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="space-y-2">
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
                            <span class="font-bold text-lg ${discount > 0 ? 'text-green-600' : 'text-blue-600'}">Rp ${formatNumber(total)}</span>
                        </div>
                    </div>
                    
                    <!-- Edit Hint -->
                    <div class="mt-3 pt-2 border-t border-gray-100">
                        <p class="text-xs text-gray-400 text-center">
                            <i class="ti ti-click text-xs mr-1"></i>
                            Klik untuk edit quantity atau potongan
                        </p>
                    </div>
                    
                    <input type="hidden" name="items[${item.index}][produk_id]" value="${item.id}">
                    <input type="hidden" name="items[${item.index}][qty]" value="${item.qty}" class="qty-input">
                    <input type="hidden" name="items[${item.index}][harga]" value="${item.price}">
                    <input type="hidden" name="items[${item.index}][discount]" value="${discount}" class="discount-input">
                </div>
            `;

            orderItemsContainer.insertAdjacentHTML('beforeend', itemHtml);

            // Add event listeners for the new item
            const newItemElement = orderItemsContainer.lastElementChild;
            addOrderItemListeners(newItemElement, item.index);
        }

        function addOrderItemListeners(element, index) {
            const removeBtn = element.querySelector('.remove-btn');
            removeBtn.addEventListener('click', () => removeOrderItem(index));

            // Add click listener for edit quantity
            element.addEventListener('click', () => editOrderItemQuantity(index));
        }

        function updateOrderItem(itemIndex) {
            const item = orderItems[itemIndex];
            const element = document.querySelector(`[data-index="${item.index}"]`);

            // Update hidden inputs
            element.querySelector('.qty-input').value = item.qty;
            element.querySelector('.discount-input').value = item.discount || 0;
            element.querySelector('input[name*="[harga]"]').value = item.price;

            // Calculate values
            const subtotal = item.price * item.qty;
            const discount = item.discount || 0;
            const total = subtotal - discount;

            // Update quantity display
            const qtyElement = element.querySelector('.qty');
            qtyElement.textContent = formatDecimalInput(item.qty);

            // Rebuild the entire price breakdown section
            const priceBreakdownContainer = element.querySelector('.space-y-2');
            priceBreakdownContainer.innerHTML = `
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
                    <span class="font-bold text-lg ${discount > 0 ? 'text-green-600' : 'text-blue-600'}">Rp ${formatNumber(total)}</span>
                </div>
            `;
        }

        // Edit order item quantity
        function editOrderItemQuantity(index) {
            const itemIndex = orderItems.findIndex(item => item.index === index);
            if (itemIndex === -1) return;

            const item = orderItems[itemIndex];
            editingItemIndex = itemIndex;

            // Show modal with current quantity, discount, and price
            showQuantityModal(item, item.qty, item.discount || 0, item.price);
        }

        // Update order item quantity
        function updateOrderItemQuantity(itemIndex, newQty, newDiscount = 0, newPrice = null) {
            const item = orderItems[itemIndex];

            // Validate new quantity
            if (newQty <= 0) {
                removeOrderItem(item.index);
                showToast(`${item.name} dihapus dari pesanan`, 'info');
                return;
            }



            // Update quantity, discount, and price
            const oldQty = item.qty;
            const oldDiscount = item.discount || 0;
            const oldPrice = item.price;
            item.qty = newQty;
            item.discount = newDiscount;
            if (newPrice !== null) {
                item.price = newPrice;
            }
            updateOrderItem(itemIndex);
            updateOrderSummary();

            let updateMessage = '';
            if (oldPrice !== item.price) {
                updateMessage += `harga Rp ${formatNumber(oldPrice)} → Rp ${formatNumber(item.price)}, `;
            }
            if (oldDiscount !== newDiscount) {
                updateMessage += `potongan Rp ${formatNumber(newDiscount)}, `;
            }
            updateMessage += `quantity ${formatNumber(oldQty)} → ${formatNumber(newQty)} ${item.unit}`;

            showToast(`${item.name} diperbarui: ${updateMessage}`, 'success');
        }

        // Update product card states to show which products are already in order
        function updateProductCardStates() {
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const productId = parseInt(card.dataset.id);
                const isInOrder = orderItems.some(item => item.id === productId);

                if (isInOrder) {
                    card.classList.add('disabled');
                } else {
                    card.classList.remove('disabled');
                }
            });
        }

        function removeOrderItem(index) {
            // Remove from array
            orderItems = orderItems.filter(item => item.index !== index);

            // Remove from DOM
            const element = document.querySelector(`[data-index="${index}"]`);
            element.remove();

            // Show empty state if no items
            if (orderItems.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
            }

            updateOrderSummary();
        }

        function updateOrderSummary() {
            // Calculate subtotal with individual item discounts
            const subtotal = orderItems.reduce((total, item) => {
                const itemSubtotal = item.price * item.qty;
                const itemDiscount = item.discount || 0;
                return total + (itemSubtotal - itemDiscount);
            }, 0);

            // Update product card states based on order items
            updateProductCardStates();

            const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
            const total = subtotal - discount;

            document.getElementById('orderCount').textContent = `${orderItems.length} item`;
            document.getElementById('subtotalDisplay').textContent = `Rp ${formatNumber(subtotal)}`;
            document.getElementById('discountDisplay').textContent = `Rp ${formatNumber(discount)}`;
            document.getElementById('totalDisplay').textContent = `Rp ${formatNumber(total)}`;
        }



        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Format number input with thousand separator
        function formatNumberInput(value) {
            // Remove all non-digit characters
            const numericValue = value.toString().replace(/\D/g, '');
            // Format with thousand separator
            return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Parse formatted number back to numeric value
        function parseFormattedNumber(value) {
            return parseInt(value.replace(/\./g, '')) || 0;
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

        // Parse formatted decimal back to numeric value
        function parseFormattedDecimal(value) {
            if (!value) return 0;

            // Remove thousand separators and convert comma to dot
            let cleanValue = value.toString()
                .replace(/\./g, '') // Remove thousand separators
                .replace(',', '.'); // Convert comma to dot for decimal

            let numValue = parseFloat(cleanValue);
            return isNaN(numValue) ? 0 : numValue;
        }

        // Setup number input formatting
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

            input.addEventListener('focus', function(e) {
                if (e.target.value === '0') {
                    e.target.select();
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

        // Update totals when discount changes
        document.getElementById('diskon').addEventListener('input', updateOrderSummary);

        // Order Preview Modal functionality
        const orderPreviewModal = document.getElementById('orderPreviewModal');
        const closeOrderPreviewModal = document.getElementById('closeOrderPreviewModal');
        const cancelOrderPreview = document.getElementById('cancelOrderPreview');
        const confirmOrderSave = document.getElementById('confirmOrderSave');

        // Close preview modal
        function closePreviewModal() {
            orderPreviewModal.classList.add('hidden');
        }

        closeOrderPreviewModal.addEventListener('click', closePreviewModal);
        cancelOrderPreview.addEventListener('click', closePreviewModal);

        // Close modal when clicking outside
        orderPreviewModal.addEventListener('click', (e) => {
            if (e.target === orderPreviewModal) {
                closePreviewModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !orderPreviewModal.classList.contains('hidden')) {
                closePreviewModal();
            }
        });

        // Show order preview
        function showOrderPreview() {
            // Get customer info
            const customerName = document.getElementById('customerDisplay').value || 'Belum dipilih';
            const selectedCustomer = document.querySelector('.customer-item.bg-blue-100');

            document.getElementById('previewCustomerName').textContent = customerName;
            document.getElementById('previewCustomerCode').textContent = selectedCustomer ?
                selectedCustomer.dataset.code : '-';

            // Get transaction info
            document.getElementById('previewInvoiceNumber').textContent =
                document.querySelector('input[name="no_faktur"]').value;
            document.getElementById('previewDate').textContent =
                new Date(document.querySelector('input[name="tanggal"]').value).toLocaleDateString('id-ID');

            const jenisTransaksi = document.getElementById('jenisTransaksi').value;
            document.getElementById('previewTransactionType').textContent =
                jenisTransaksi === 'kredit' ? 'Kredit' : 'Tunai';

            // Initialize modal form with current values (no auto-select for transaction type)
            // User must choose transaction type manually
            updatePreviewTransactionTypeCards();

            // Set DP amount based on transaction type (will be updated when user selects transaction type)
            document.getElementById('previewDpAmount').value = '0';
            document.getElementById('previewDpAmount').readOnly = false;
            document.getElementById('paymentAmountLabel').textContent = 'Jumlah Pembayaran';
            document.getElementById('previewDpAmount').placeholder = 'Jumlah (Rp)';

            // Show/hide DP container based on transaction type (will be updated when user selects transaction type)
            const previewDpContainer = document.getElementById('previewDpContainer');
            previewDpContainer.classList.add('hidden'); // Hide initially until user selects transaction type

            // Populate order items
            const previewOrderItems = document.getElementById('previewOrderItems');
            previewOrderItems.innerHTML = '';

            orderItems.forEach(item => {
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
            const subtotalItems = orderItems.reduce((total, item) => {
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
                const dpAmount = parseFormattedNumber(document.getElementById('dpAmountDisplay').value);
                const remaining = Math.max(0, total - dpAmount);

                previewPaymentBreakdown.classList.remove('hidden');
                document.getElementById('previewDP').textContent = `Rp ${formatNumber(dpAmount)}`;
                document.getElementById('previewRemaining').textContent = `Rp ${formatNumber(remaining)}`;
            } else {
                previewPaymentBreakdown.classList.add('hidden');
            }

            // Show modal
            orderPreviewModal.classList.remove('hidden');

            // Initialize kas/bank logos
            const selectedKasBank = document.querySelector('.preview-kas-bank-radio:checked');
            if (selectedKasBank) {
                updateKasBankLogo(selectedKasBank);
            }

            // Initialize kas/bank filter based on payment method
            filterKasBankByPaymentMethod();

            // Uncheck any selected kas/bank when modal opens
            const selectedKasBankRadio = document.querySelector('.preview-kas-bank-radio:checked');
            if (selectedKasBankRadio) {
                selectedKasBankRadio.checked = false;
                updatePreviewKasBankCards();
            }
        }

        // Form validation and preview
        document.getElementById('salesForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default first

            if (orderItems.length === 0) {
                showToast('Minimal harus ada 1 produk!', 'error');
                return false;
            }

            if (!pelangganId.value) {
                showToast('Silakan pilih pelanggan terlebih dahulu!', 'error');
                searchCustomerBtn.focus();
                return false;
            }



            // Show preview modal instead of submitting directly
            showOrderPreview();
        });

        // Modal form event listeners
        const previewDpContainer = document.getElementById('previewDpContainer');
        const previewDpAmount = document.getElementById('previewDpAmount');

        // Preview transaction type radio button handling
        const previewTransactionTypeRadios = document.querySelectorAll('.preview-transaction-type-radio');
        const previewTransactionTypeCards = document.querySelectorAll('.preview-transaction-type-card');

        // Function to update preview transaction type card styling
        function updatePreviewTransactionTypeCards() {
            previewTransactionTypeCards.forEach((card, index) => {
                const radio = previewTransactionTypeRadios[index];
                if (radio.checked) {
                    card.classList.remove('border-gray-200', 'bg-white', 'border-red-500', 'bg-red-50',
                        'animate-pulse');
                    card.classList.add('border-blue-500', 'bg-blue-50');
                } else {
                    card.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-500', 'bg-red-50',
                        'animate-pulse');
                    card.classList.add('border-gray-200', 'bg-white');
                }
            });
        }

        // Add event listeners to preview transaction type radio buttons
        previewTransactionTypeRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                updatePreviewTransactionTypeCards();
                handleTransactionTypeChange();
            });

            // Add click event to card for better UX
            const card = previewTransactionTypeCards[index];
            card.addEventListener('click', function() {
                radio.checked = true;
                updatePreviewTransactionTypeCards();
                handleTransactionTypeChange();
            });
        });

        // Initialize preview transaction type card states
        updatePreviewTransactionTypeCards();

        // Handle transaction type change in modal
        function handleTransactionTypeChange() {
            const selectedTransactionType = document.querySelector('.preview-transaction-type-radio:checked').value;
            if (selectedTransactionType === 'kredit') {
                previewDpContainer.classList.remove('hidden');
                previewDpAmount.required = true;
                previewDpAmount.readOnly = false;

                // Reset DP amount to 0 for kredit
                previewDpAmount.value = '0';
                previewDpAmount.focus();

                // Update labels for kredit
                document.getElementById('paymentAmountLabel').textContent = 'Jumlah Down Payment (DP)';
                document.getElementById('previewDpAmount').placeholder = 'Jumlah DP (Rp)';

                // Show info message
                showToast('Jumlah DP direset ke 0. Silakan isi jumlah DP yang diinginkan.', 'info');
            } else {
                // For tunai transactions, auto-fill with total amount
                const subtotal = orderItems.reduce((total, item) => {
                    const itemSubtotal = item.price * item.qty;
                    const itemDiscount = item.discount || 0;
                    return total + (itemSubtotal - itemDiscount);
                }, 0);
                const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
                const total = subtotal - discount;

                previewDpContainer.classList.remove('hidden');
                previewDpAmount.required = false;
                previewDpAmount.value = formatNumberInput(total.toString());
                previewDpAmount.readOnly = true;

                // Update labels for tunai
                document.getElementById('paymentAmountLabel').textContent = 'Jumlah Pembayaran';
                document.getElementById('previewDpAmount').placeholder = 'Jumlah (Rp)';

                // Show info message
                showToast('Pembayaran tunai otomatis diisi sesuai total transaksi', 'info');
            }
        }

        // Setup number formatting for DP input in modal
        setupNumberInput(previewDpAmount);

        // Preview payment method radio button handling
        const previewPaymentRadios = document.querySelectorAll('.preview-payment-method-radio');
        const previewPaymentCards = document.querySelectorAll('.preview-payment-method-card');

        // Function to update preview payment method card styling
        function updatePreviewPaymentMethodCards() {
            previewPaymentCards.forEach((card, index) => {
                const radio = previewPaymentRadios[index];
                if (radio.checked) {
                    card.classList.remove('border-gray-200', 'bg-white', 'border-red-500', 'bg-red-50',
                        'animate-pulse');
                    card.classList.add('border-blue-500', 'bg-blue-50');
                } else {
                    card.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-500', 'bg-red-50',
                        'animate-pulse');
                    card.classList.add('border-gray-200', 'bg-white');
                }
            });
        }

        // Add event listeners to preview radio buttons
        previewPaymentRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                updatePreviewPaymentMethodCards();
                filterKasBankByPaymentMethod();
            });

            // Add click event to card for better UX
            const card = previewPaymentCards[index];
            card.addEventListener('click', function() {
                radio.checked = true;
                updatePreviewPaymentMethodCards();
                filterKasBankByPaymentMethod();
            });
        });

        // Initialize preview card states
        updatePreviewPaymentMethodCards();

        // Card scan area functionality
        const cardScanArea = document.getElementById('cardScanArea');

        // Function to reset card scan area to initial state
        function resetCardScanArea() {
            const scanIcon = cardScanArea.querySelector('.ti-scan');
            const statusText = cardScanArea.querySelector('p:last-child');
            const cardArea = cardScanArea.querySelector('div');
            const rfidDisplay = document.getElementById('rfidDisplay');
            const rfidCardId = document.getElementById('rfidCardId');

            // Reset to initial state
            scanIcon.classList.remove('animate-spin');
            scanIcon.classList.add('animate-pulse');
            // statusText.textContent = 'Menunggu kartu terdeteksi...';
            statusText.classList.remove('text-green-600', 'font-medium');
            statusText.classList.add('text-gray-500');

            // Reset styling
            cardArea.classList.remove('border-green-400', 'bg-green-50');
            cardArea.classList.add('border-blue-300');

            // Hide RFID display and clear values
            if (rfidDisplay) {
                rfidDisplay.classList.add('hidden');
            }
            if (rfidCardId) {
                rfidCardId.value = '';
            }
        }

        // Function to ensure RFID input is focused when card scan area is visible
        function ensureRfidInputFocused() {
            const rfidInput = document.getElementById('rfidCardId');
            if (rfidInput && cardScanArea && !cardScanArea.classList.contains('hidden')) {
                // Check if input is not already focused
                if (document.activeElement !== rfidInput) {
                    rfidInput.focus();
                    console.log('RFID input auto-focused');
                }
            }
        }

        // Function to clear RFID input (manual reset only)
        function clearRfidInput() {
            const rfidCardId = document.getElementById('rfidCardId');
            const rfidDisplay = document.getElementById('rfidDisplay');
            const readyIndicator = document.getElementById('rfidReadyIndicator');

            if (rfidCardId) {
                rfidCardId.value = '';
            }

            // Clear stored RFID ID
            storedRfidId = '';
            console.log('Stored RFID ID cleared');

            // Reset progress bar
            updateRfidProgress(0);

            if (rfidDisplay) {
                rfidDisplay.classList.add('hidden');
            }

            // Reset card display to default values
            resetCardDisplay();

            // Update ready indicator
            if (readyIndicator) {
                readyIndicator.textContent = '💳 Input RFID siap menerima data...';
                readyIndicator.classList.remove('text-green-600', 'text-orange-600');
                readyIndicator.classList.add('text-blue-600');
            }

            // Reset card scan area to initial state
            resetCardScanArea();

            console.log('RFID input manually cleared');
        }

        // Function to reset card display to default values
        function resetCardDisplay() {
            const rfidCardNumber = document.getElementById('rfidCardNumber');
            const rfidCardId = document.getElementById('rfidCardId');
            const rfidCardholderName = document.getElementById('rfidCardholderName');
            const rfidExpiryDate = document.getElementById('rfidExpiryDate');
            const rfidCardIdDisplay = document.getElementById('rfidCardIdDisplay');
            const rfidBalanceDisplay = document.getElementById('rfidBalanceDisplay');

            if (rfidCardNumber) {
                rfidCardNumber.textContent = '-';
            }
            if (rfidCardId) {
                rfidCardId.textContent = '-';
            }
            if (rfidCardholderName) {
                rfidCardholderName.textContent = '-';
            }
            if (rfidExpiryDate) {
                rfidExpiryDate.textContent = '-';
            }
            if (rfidCardIdDisplay) {
                rfidCardIdDisplay.textContent = '-';
            }
            if (rfidBalanceDisplay) {
                rfidBalanceDisplay.textContent = 'Rp 0';
            }

            // Reset saldo RFID dan status
            saldoRfid = 0;
            statusRfid = false;
            storedRfidId = '';
            console.log('🔄 Saldo RFID dan status direset:', {
                saldoRfid,
                statusRfid,
                storedRfidId
            });
        }

        // Interval to keep RFID input focused when card scan area is visible
        let rfidFocusInterval;

        function startRfidFocusMonitoring() {
            if (rfidFocusInterval) {
                clearInterval(rfidFocusInterval);
            }
            rfidFocusInterval = setInterval(ensureRfidInputFocused, 1000);
        }

        function stopRfidFocusMonitoring() {
            if (rfidFocusInterval) {
                clearInterval(rfidFocusInterval);
                rfidFocusInterval = null;
            }
        }

        // Function to handle RFID card detection
        // This function is called when an RFID card is detected
        // It updates the UI and stores the card ID for form submission
        function handleRfidCardDetection(cardId) {
            console.log('RFID Card detected:', cardId);

            const scanIcon = cardScanArea.querySelector('.ti-scan');
            const statusText = cardScanArea.querySelector('p:last-child');
            const cardArea = cardScanArea.querySelector('div');
            const rfidDisplay = document.getElementById('rfidDisplay');
            const rfidCardId = document.getElementById('rfidCardId');

            // Show loading state
            scanIcon.classList.remove('animate-pulse');
            scanIcon.classList.add('animate-spin');

            if (statusText) {
                statusText.textContent = '🔄 Mengambil data dari server...';
                statusText.classList.remove('text-blue-600', 'text-green-600');
                statusText.classList.add('text-orange-600');
            }

            // Update input field
            if (rfidCardId) {
                rfidCardId.value = cardId;
            }

            // Update progress bar
            updateRfidProgress(10);

            // Call API to get RFID data
            fetchRfidData(cardId)
                .then(data => {
                    // Update scan icon and status
                    scanIcon.classList.remove('animate-pulse', 'animate-spin');
                    scanIcon.classList.add('animate-pulse');

                    // Update status text
                    if (statusText) {
                        statusText.textContent = '✅ Kartu RFID berhasil dibaca!';
                        statusText.classList.remove('text-blue-600', 'text-orange-600');
                        statusText.classList.add('text-green-600');
                    }

                    // Add success styling to card scan area
                    cardArea.classList.remove('border-blue-300', 'bg-blue-50');
                    cardArea.classList.add('border-green-500', 'bg-green-50');

                    // Show RFID display with animation
                    if (rfidDisplay) {
                        rfidDisplay.classList.remove('hidden');

                        const cardElement = rfidDisplay.querySelector('.bg-gradient-to-br');
                        if (cardElement) {
                            cardElement.classList.add('animate-pulse');
                            setTimeout(() => {
                                cardElement.classList.remove('animate-pulse');
                            }, 2000);
                        }
                    }

                    // Update card display with API data
                    updateCardDisplayWithApiData(data, cardId);

                    // Set status RFID to true
                    statusRfid = true;
                    storedRfidId = cardId;
                    console.log('✅ Status RFID set to true, stored ID:', storedRfidId);

                    // Update ready indicator
                    const readyIndicator = document.getElementById('rfidReadyIndicator');
                    if (readyIndicator) {
                        readyIndicator.textContent = '✅ Kartu RFID berhasil dibaca!';
                        readyIndicator.classList.remove('text-blue-600', 'text-orange-600');
                        readyIndicator.classList.add('text-green-600');
                    }

                    // Show success message
                    showToast('Kartu RFID berhasil dibaca!', 'success');
                })
                .catch(error => {
                    console.error('Error fetching RFID data:', error);

                    // Reset to error state
                    scanIcon.classList.remove('animate-pulse', 'animate-spin');
                    scanIcon.classList.add('animate-pulse');

                    if (statusText) {
                        statusText.textContent = '❌ Gagal mengambil data RFID';
                        statusText.classList.remove('text-blue-600', 'text-green-600');
                        statusText.classList.add('text-red-600');
                    }

                    // Reset card area styling
                    cardArea.classList.remove('border-blue-300', 'bg-blue-50', 'border-green-500', 'bg-green-50');
                    cardArea.classList.add('border-red-500', 'bg-red-50');

                    // Show error message
                    showToast('Gagal mengambil data RFID: ' + error.message, 'error');
                });
        }

        // Function to fetch RFID data from API
        async function fetchRfidData(rfid) {
            try {
                console.log('🔍 Fetching RFID data for:', rfid);
                const response = await fetch(`/penjualan/rfid/${rfid}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('📡 API Response status:', response.status);
                const result = await response.json();
                console.log('📦 API Response data:', result);

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal mengambil data RFID');
                }

                if (!result.success) {
                    throw new Error(result.message || 'Data RFID tidak ditemukan');
                }

                console.log('✅ RFID data received:', result.data);
                return result.data;
            } catch (error) {
                console.error('❌ Error fetching RFID data:', error);
                throw error;
            }
        }

        // Function to update card display with API data
        function updateCardDisplayWithApiData(apiData, rfidId) {
            console.log('🎯 Updating card display with API data:', apiData);
            console.log('🆔 RFID ID:', rfidId);

            // Extract the actual data from nested response structure
            const actualData = apiData.data || apiData;
            console.log('📊 Actual data extracted:', actualData);

            const rfidCardNumber = document.getElementById('rfidCardNumber');
            const rfidCardId = document.getElementById('rfidCardId');
            const rfidCardholderName = document.getElementById('rfidCardholderName');
            const rfidExpiryDate = document.getElementById('rfidExpiryDate');
            const rfidCardIdDisplay = document.getElementById('rfidCardIdDisplay');

            // Update card display with API data
            if (rfidCardNumber && actualData.no_rekening) {
                // Format account number for display (add spaces every 4 digits)
                const formattedAccount = actualData.no_rekening.replace(/(\d{3})(\d{4})(\d{5})/, '$1 $2 $3');
                console.log('💳 Setting card number:', formattedAccount);
                rfidCardNumber.textContent = formattedAccount;
            } else {
                console.log('⚠️ Card number element or no_rekening not found:', {
                    rfidCardNumber,
                    no_rekening: actualData.no_rekening
                });
            }

            if (rfidCardId && actualData.no_anggota) {
                console.log('🆔 Setting member ID:', actualData.no_anggota);
                rfidCardId.textContent = actualData.no_anggota;
            } else {
                console.log('⚠️ Member ID element or no_anggota not found:', {
                    rfidCardId,
                    no_anggota: actualData.no_anggota
                });
            }

            if (rfidCardholderName && actualData.anggota && actualData.anggota.nama_lengkap) {
                const upperName = actualData.anggota.nama_lengkap.toUpperCase();
                console.log('👤 Setting cardholder name:', upperName);
                rfidCardholderName.textContent = upperName;
            } else {
                console.log('⚠️ Cardholder name element or nama_lengkap not found:', {
                    rfidCardholderName,
                    anggota: actualData.anggota
                });
            }

            if (rfidExpiryDate) {
                // Show account type instead of expiry date
                if (actualData.jenis_tabungan && actualData.jenis_tabungan.jenis_tabungan) {
                    console.log('🏦 Setting account type:', actualData.jenis_tabungan.jenis_tabungan);
                    rfidExpiryDate.textContent = actualData.jenis_tabungan.jenis_tabungan;
                } else {
                    console.log('🏦 Setting default account type: Tabungan');
                    rfidExpiryDate.textContent = 'Tabungan';
                }
            } else {
                console.log('⚠️ Expiry date element not found:', rfidExpiryDate);
            }

            if (rfidCardIdDisplay) {
                console.log('📄 Setting RFID display:', rfidId);
                rfidCardIdDisplay.textContent = rfidId;
            } else {
                console.log('⚠️ RFID display element not found:', rfidCardIdDisplay);
            }

            // Update bank name with account type
            const bankNameElement = document.querySelector('#rfidDisplay .text-gray-300');
            if (bankNameElement) {
                if (actualData.jenis_tabungan && actualData.jenis_tabungan.jenis_tabungan) {
                    bankNameElement.textContent = actualData.jenis_tabungan.jenis_tabungan;
                } else {
                    bankNameElement.textContent = 'Tabungan';
                }
            }

            // Add balance information if available
            if (actualData.saldo !== undefined) {
                const balanceElement = document.getElementById('rfidBalanceDisplay');
                if (balanceElement) {
                    console.log('💰 Setting balance:', formatCurrency(actualData.saldo));
                    balanceElement.textContent = `Rp ${formatCurrency(actualData.saldo)}`;

                    // Simpan saldo ke variabel global
                    saldoRfid = actualData.saldo;
                    console.log('💾 Saldo RFID disimpan:', saldoRfid);
                } else {
                    console.log('⚠️ Balance display element not found:', balanceElement);
                }
            }

            // Update card color based on account type or use default
            const cardElement = document.querySelector('#rfidDisplay .bg-gradient-to-br');
            if (cardElement) {
                // Remove any existing color classes
                cardElement.classList.remove('from-gray-800', 'to-gray-900', 'from-blue-800', 'to-blue-900', 'from-red-800',
                    'to-red-900', 'from-green-800', 'to-green-900', 'from-yellow-800', 'to-yellow-900',
                    'from-purple-800', 'to-purple-900', 'from-indigo-800', 'to-indigo-900', 'from-pink-800',
                    'to-pink-900', 'from-teal-800', 'to-teal-900', 'from-orange-800', 'to-orange-900', 'from-cyan-800',
                    'to-cyan-900', 'from-emerald-800', 'to-emerald-900', 'from-lime-800', 'to-lime-900',
                    'from-amber-800', 'to-amber-900', 'from-violet-800', 'to-violet-900', 'from-fuchsia-800',
                    'to-fuchsia-900', 'from-rose-800', 'to-rose-900');

                // Use different colors based on account type
                if (actualData.jenis_tabungan && actualData.jenis_tabungan.jenis_tabungan) {
                    const accountType = actualData.jenis_tabungan.jenis_tabungan.toLowerCase();
                    if (accountType.includes('siswa') || accountType.includes('student')) {
                        cardElement.classList.add('from-green-600', 'to-green-800');
                    } else if (accountType.includes('umum') || accountType.includes('general')) {
                        cardElement.classList.add('from-blue-600', 'to-blue-800');
                    } else if (accountType.includes('premium') || accountType.includes('vip')) {
                        cardElement.classList.add('from-purple-600', 'to-purple-800');
                    } else {
                        cardElement.classList.add('from-blue-600', 'to-blue-800');
                    }
                } else {
                    cardElement.classList.add('from-blue-600', 'to-blue-800');
                }
            }

            // Store API data for form submission
            window.rfidApiData = actualData;
        }

        // Helper function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }



        // Simulate card scan function removed - RFID should only be detected through actual hardware input

        // Click event removed - RFID should only be detected through actual hardware input

        // RFID Reader Integration
        // Listen for RFID reader input (keyboard input simulation)
        let rfidInputBuffer = '';
        let rfidInputTimeout;
        let storedRfidId = ''; // Variabel untuk menyimpan RFID ID 10 karakter
        let saldoRfid = 0; // Variabel untuk menyimpan saldo RFID
        let statusRfid = false; // Variabel untuk tracking status scan RFID

        // Function untuk update progress bar RFID
        function updateRfidProgress(currentLength) {
            const progressText = document.getElementById('rfidProgress');
            const progressBar = document.getElementById('rfidProgressBar');

            if (progressText) {
                progressText.textContent = `${currentLength}/10`;
            }

            if (progressBar) {
                const percentage = (currentLength / 10) * 100;
                progressBar.style.width = `${percentage}%`;

                // Change color based on progress
                if (currentLength === 10) {
                    progressBar.classList.remove('bg-blue-500', 'bg-yellow-500');
                    progressBar.classList.add('bg-green-500');
                } else if (currentLength >= 7) {
                    progressBar.classList.remove('bg-blue-500', 'bg-green-500');
                    progressBar.classList.add('bg-yellow-500');
                } else {
                    progressBar.classList.remove('bg-yellow-500', 'bg-green-500');
                    progressBar.classList.add('bg-blue-500');
                }
            }
        }

        document.addEventListener('keydown', function(event) {
            // Only process if card scan area is visible
            if (cardScanArea && !cardScanArea.classList.contains('hidden')) {
                // Check if it's a printable character (not special keys)
                if (event.key.length === 1) {
                    rfidInputBuffer += event.key;

                    // Clear buffer after 1 second of inactivity
                    clearTimeout(rfidInputTimeout);
                    rfidInputTimeout = setTimeout(() => {
                        rfidInputBuffer = '';
                    }, 1000);

                    // Check if buffer mencapai 10 karakter (RFID ID length)
                    if (rfidInputBuffer.length === 10) {
                        event.preventDefault();
                        const newCardId = rfidInputBuffer.trim();

                        // Simpan ID ke variabel
                        storedRfidId = newCardId;
                        console.log('RFID ID tersimpan:', storedRfidId);

                        // Trigger card detection dengan ID yang tersimpan
                        handleRfidCardDetection(storedRfidId);

                        // Reset buffer untuk input berikutnya
                        rfidInputBuffer = '';

                        // Reset input field
                        const rfidInput = document.getElementById('rfidCardId');
                        if (rfidInput) {
                            rfidInput.value = '';
                        }
                    }

                    // Update input field dengan buffer saat ini (untuk debugging)
                    const rfidInput = document.getElementById('rfidCardId');
                    if (rfidInput && rfidInputBuffer.length < 10) {
                        rfidInput.value = rfidInputBuffer;
                    }

                    // Update progress bar
                    updateRfidProgress(rfidInputBuffer.length);
                }
            }
        });

        // Alternative: Listen for custom RFID reader events
        // This can be used when integrating with actual RFID hardware
        window.addEventListener('rfidCardDetected', function(event) {
            if (cardScanArea && !cardScanArea.classList.contains('hidden')) {
                const newCardId = event.detail.cardId;
                handleRfidCardDetection(newCardId);
            }
        });

        // Function to manually trigger RFID detection (for testing)
        window.triggerRfidDetection = function(cardId) {
            if (cardScanArea && !cardScanArea.classList.contains('hidden')) {
                handleRfidCardDetection(cardId);
            }
        };

        // Clear RFID input button
        const clearRfidBtn = document.getElementById('clearRfidBtn');
        if (clearRfidBtn) {
            clearRfidBtn.addEventListener('click', function() {
                clearRfidInput();
                showToast('Input RFID telah di-clear', 'info');
            });
        }

        // Manual input detection for testing
        const rfidCardIdInput = document.getElementById('rfidCardId');
        if (rfidCardIdInput) {
            // Add focus event listener
            rfidCardIdInput.addEventListener('focus', function() {
                // Add visual indicator when input is focused
                this.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
                console.log('RFID input is now focused and ready for input');

                // Update ready indicator
                const readyIndicator = document.getElementById('rfidReadyIndicator');
                if (readyIndicator) {
                    readyIndicator.textContent = '✅ Input RFID aktif dan siap menerima data...';
                    readyIndicator.classList.remove('text-blue-600');
                    readyIndicator.classList.add('text-green-600');
                }
            });

            // Add blur event listener
            rfidCardIdInput.addEventListener('blur', function() {
                // Remove visual indicator when input loses focus
                this.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');

                // Update ready indicator
                const readyIndicator = document.getElementById('rfidReadyIndicator');
                if (readyIndicator) {
                    readyIndicator.textContent = '💳 Input RFID siap menerima data...';
                    readyIndicator.classList.remove('text-green-600');
                    readyIndicator.classList.add('text-blue-600');
                }
            });

            rfidCardIdInput.addEventListener('input', function() {
                const value = this.value.trim();

                // Update progress bar
                updateRfidProgress(Math.min(value.length, 10));

                // Jika mencapai 10 karakter, simpan dan reset input
                if (value.length === 10) {
                    storedRfidId = value;
                    console.log('RFID ID tersimpan (manual input):', storedRfidId);

                    // Trigger card detection dengan ID yang tersimpan
                    handleRfidCardDetection(storedRfidId);

                    // Reset input field dan progress
                    this.value = '';
                    updateRfidProgress(0);
                } else if (value.length > 10) {
                    // Potong jika lebih dari 10 karakter
                    this.value = value.substring(0, 10);
                    storedRfidId = this.value;
                    console.log('RFID ID tersimpan (manual input, dipotong):', storedRfidId);
                    handleRfidCardDetection(storedRfidId);
                    this.value = '';
                    updateRfidProgress(0);
                }
            });
        }

        // Test RFID button
        const testRfidBtn = document.getElementById('testRfidBtn');
        if (testRfidBtn) {
            testRfidBtn.addEventListener('click', function() {
                const testCardId = 'TEST-' + Date.now().toString().slice(-6);
                handleRfidCardDetection(testCardId);
            });
        }

        // Simulate RFID button
        const simulateRfidBtn = document.getElementById('simulateRfidBtn');
        if (simulateRfidBtn) {
            simulateRfidBtn.addEventListener('click', function() {
                const simulatedCardId = 'RFID-' + Math.random().toString(36).substr(2, 8).toUpperCase();
                handleRfidCardDetection(simulatedCardId);
            });
        }

        // Reset RFID button
        const resetRfidBtn = document.getElementById('resetRfidBtn');
        if (resetRfidBtn) {
            resetRfidBtn.addEventListener('click', function() {
                clearRfidInput();
                showToast('Input RFID telah di-reset', 'info');
            });
        }

        // Test function for debugging API response
        window.testRfidApiResponse = function() {
            console.log('🧪 Testing RFID API response with sample data...');
            const sampleApiData = {
                "success": true,
                "data": {
                    "no_rekening": "103-2408-00016",
                    "no_anggota": "2408-00016",
                    "kode_tabungan": "103",
                    "saldo": 40000,
                    "rfid": "0001355460",
                    "created_at": "2025-07-21 09:35:43",
                    "updated_at": "2025-09-20 22:19:30",
                    "jenis_tabungan": {
                        "kode_tabungan": "103",
                        "jenis_tabungan": "Tabungan Siswa"
                    },
                    "anggota": {
                        "no_anggota": "2408-00016",
                        "nama_lengkap": "Khairi Messi Rabbani",
                        "alamat": "MTs Persis Sindangkasih",
                        "no_hp": "8080800324016"
                    }
                }
            };

            // Show RFID display first
            const rfidDisplay = document.getElementById('rfidDisplay');
            if (rfidDisplay) {
                rfidDisplay.classList.remove('hidden');
            }

            // Update card display with sample data
            updateCardDisplayWithApiData(sampleApiData, "0001355460");
            console.log('✅ Test completed! Check the card display.');
            console.log('Expected display:');
            console.log('- Card Number: 103 2408 00016');
            console.log('- Member ID: 2408-00016');
            console.log('- Name: KHAIRI MESSI RABBANI');
            console.log('- Account Type: Tabungan Siswa');
            console.log('- Balance: Rp 40.000');
            console.log('💰 RFID Balance stored:', saldoRfid);
        };

        // Console helper message
        console.log('🔧 Debug tools available:');
        console.log('- testRfidApiResponse() - Test card display with sample data');
        console.log('- clearRfidInput() - Clear RFID input and reset card');
        console.log('- triggerRfidDetection("RFIDID") - Simulate RFID scan');
        console.log('- checkRfidBalance() - Check RFID balance and status');
        console.log('- checkRfidStatus() - Check complete RFID status');
        console.log('- testSaldoValidation() - Test saldo validation logic');
        console.log('- saldoRfid - Current RFID balance variable');
        console.log('- statusRfid - Current RFID scan status');

        // Helper function to check RFID balance
        window.checkRfidBalance = function() {
            console.log('💰 Current RFID Balance:', saldoRfid);
            console.log('💳 RFID Card ID:', storedRfidId);
            console.log('✅ RFID Status:', statusRfid);
            return saldoRfid;
        };

        // Helper function to check RFID status
        window.checkRfidStatus = function() {
            console.log('📊 RFID Status Check:');
            console.log('- Status RFID:', statusRfid);
            console.log('- Stored RFID ID:', storedRfidId);
            console.log('- Saldo RFID:', saldoRfid);
            console.log('- Hidden input value:', document.getElementById('rfidCardId')?.value);
            return {
                status: statusRfid,
                id: storedRfidId,
                saldo: saldoRfid,
                inputValue: document.getElementById('rfidCardId')?.value
            };
        };

        // Helper function to test saldo validation
        window.testSaldoValidation = function() {
            console.log('🧪 Testing Saldo Validation:');

            // Check if payment method is CARD
            const selectedPaymentMethod = document.querySelector('.preview-payment-method-radio:checked');
            const isCard = selectedPaymentMethod && (
                selectedPaymentMethod.value.toLowerCase().includes('card') ||
                selectedPaymentMethod.value.toLowerCase().includes('rfid') ||
                selectedPaymentMethod.value.toLowerCase().includes('kartu')
            );

            console.log('💳 Payment Method Check:');
            console.log('- Selected Method:', selectedPaymentMethod?.value);
            console.log('- Is CARD Method:', isCard);

            if (!isCard) {
                console.log('ℹ️ Non-CARD payment method - RFID validation will be skipped');
                return {
                    isCard: false,
                    message: 'RFID validation skipped for non-CARD payment method'
                };
            }

            // Calculate current total
            const subtotal = orderItems.reduce((total, item) => {
                const itemSubtotal = item.price * item.qty;
                const itemDiscount = item.discount || 0;
                return total + (itemSubtotal - itemDiscount);
            }, 0);
            const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
            const total = subtotal - discount;

            console.log('💰 Current Order:');
            console.log('- Subtotal:', subtotal);
            console.log('- Discount:', discount);
            console.log('- Total:', total);
            console.log('- RFID Saldo:', saldoRfid);
            console.log('- Status RFID:', statusRfid);

            if (total > saldoRfid) {
                console.log('❌ VALIDATION SHOULD FAIL - Total exceeds saldo');
                console.log(`Total (${total}) > Saldo (${saldoRfid})`);
            } else {
                console.log('✅ VALIDATION SHOULD PASS - Saldo sufficient');
                console.log(`Total (${total}) <= Saldo (${saldoRfid})`);
            }

            return {
                isCard: true,
                total,
                saldo: saldoRfid,
                shouldFail: total > saldoRfid
            };
        };

        // Preview kas/bank radio button handling
        const previewKasBankRadios = document.querySelectorAll('.preview-kas-bank-radio');
        const previewKasBankCards = document.querySelectorAll('.preview-kas-bank-card');

        // Function to update preview kas/bank card styling
        function updatePreviewKasBankCards() {
            previewKasBankCards.forEach((card, index) => {
                const radio = previewKasBankRadios[index];
                if (radio.checked) {
                    card.classList.remove('border-gray-200', 'bg-white', 'border-red-500', 'bg-red-50',
                        'animate-pulse');
                    card.classList.add('border-blue-500', 'bg-blue-50');
                } else {
                    card.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-500', 'bg-red-50',
                        'animate-pulse');
                    card.classList.add('border-gray-200', 'bg-white');
                }
            });
        }

        // Add event listeners to preview kas/bank radio buttons
        previewKasBankRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                updatePreviewKasBankCards();
                updateKasBankLogo(radio);
            });

            // Add click event to card for better UX
            const card = previewKasBankCards[index];
            card.addEventListener('click', function() {
                radio.checked = true;
                updatePreviewKasBankCards();
                updateKasBankLogo(radio);
            });
        });

        // Function to update kas/bank logo
        function updateKasBankLogo(selectedRadio) {
            const jenis = selectedRadio.getAttribute('data-jenis');
            const image = selectedRadio.getAttribute('data-image');

            // Find the icon container in the selected card
            const card = selectedRadio.closest('.preview-kas-bank-option').querySelector('.preview-kas-bank-card');
            const iconContainer = card.querySelector('.w-16.h-16');

            if (!iconContainer) {
                console.warn('Icon container not found for kas/bank card');
                return;
            }

            if (jenis === 'KAS') {
                iconContainer.innerHTML = `
                    <div class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                        <i class="ti ti-cash text-green-600 text-xl"></i>
                    </div>
                `;
            } else {
                if (image) {
                    iconContainer.innerHTML = `
                        <img src="${image}" alt="Logo" class="w-full h-full object-contain">
                    `;
                } else {
                    iconContainer.innerHTML = `
                        <div class="w-full h-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                            <i class="ti ti-building-bank text-purple-600 text-xl"></i>
                        </div>
                    `;
                }
            }
        }

        // Initialize preview kas/bank card states
        updatePreviewKasBankCards();

        // Function to filter kas/bank based on payment method
        function filterKasBankByPaymentMethod() {
            const selectedPaymentMethod = document.querySelector('.preview-payment-method-radio:checked');
            const kasBankMessage = document.getElementById('kasBankMessage');
            const kasBankContainer = document.getElementById('previewKasBankContainer');
            const cardScanArea = document.getElementById('cardScanArea');

            if (!selectedPaymentMethod) {
                // If no payment method selected, hide all kas/bank and show message
                previewKasBankCards.forEach((card, index) => {
                    card.classList.add('hidden');
                });
                kasBankMessage.classList.remove('hidden');
                cardScanArea.classList.add('hidden');
                return;
            }

            // Hide message when payment method is selected
            kasBankMessage.classList.add('hidden');

            const paymentMethodCode = selectedPaymentMethod.value;
            //alert(paymentMethodCode);
            const isTransfer = paymentMethodCode.toLowerCase().includes('transfer') ||
                paymentMethodCode.toLowerCase().includes('bank') ||
                paymentMethodCode.toLowerCase().includes('bca') ||
                paymentMethodCode.toLowerCase().includes('mandiri') ||
                paymentMethodCode.toLowerCase().includes('bni') ||
                paymentMethodCode.toLowerCase().includes('bri');
            const isCash = paymentMethodCode.toLowerCase().includes('cash') ||
                paymentMethodCode.toLowerCase().includes('tunai') ||
                paymentMethodCode.toLowerCase().includes('kas');
            const isCard = paymentMethodCode.toLowerCase().includes('card') ||
                paymentMethodCode.toLowerCase().includes('rfid') ||
                paymentMethodCode.toLowerCase().includes('kartu');

            // Handle CARD payment method
            if (isCard) {
                // Hide all kas/bank cards and show card scan area
                previewKasBankCards.forEach((card, index) => {
                    card.classList.add('hidden');
                });
                cardScanArea.classList.remove('hidden');
                kasBankContainer.classList.add('hidden');

                // Reset card scan area state
                resetCardScanArea();

                // Auto-focus to RFID input after a short delay
                setTimeout(() => {
                    const rfidInput = document.getElementById('rfidCardId');
                    if (rfidInput) {
                        rfidInput.focus();
                        console.log('RFID input focused for CARD payment method');
                    }
                    // Start monitoring to keep input focused
                    startRfidFocusMonitoring();
                }, 100);

                return;
            } else {
                // Hide card scan area for non-card methods
                cardScanArea.classList.add('hidden');
                kasBankContainer.classList.remove('hidden');

                // Stop RFID focus monitoring for non-card methods
                stopRfidFocusMonitoring();
            }

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
                } else if (!isTransfer && !isCash && !isCard) {
                    // If payment method is not clearly transfer, cash, or card, show all
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

            // Show appropriate message
            if (isTransfer) {
                showToast('Menampilkan kas/bank jenis BANK untuk metode transfer', 'info');
            } else if (isCash) {
                showToast('Menampilkan kas/bank jenis KAS untuk metode tunai', 'info');
            }
        }

        // Confirm order save
        confirmOrderSave.addEventListener('click', function() {
            // Validate modal form first
            const selectedModalTransactionType = document.querySelector('.preview-transaction-type-radio:checked');
            const selectedModalPaymentMethod = document.querySelector('.preview-payment-method-radio:checked');
            const selectedModalKasBank = document.querySelector('.preview-kas-bank-radio:checked');
            const modalDpAmount = parseFormattedNumber(document.getElementById('previewDpAmount').value);

            // Validate transaction type
            if (!selectedModalTransactionType) {
                showToast('Jenis transaksi wajib dipilih!', 'error');

                // Add error highlight to all transaction type cards
                const previewTransactionTypeCards = document.querySelectorAll('.preview-transaction-type-card');
                previewTransactionTypeCards.forEach(card => {
                    card.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                    setTimeout(() => {
                        card.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                    }, 3000);
                });
                return;
            }

            // Validate payment method
            if (!selectedModalPaymentMethod) {
                showToast('Metode pembayaran wajib dipilih!', 'error');

                // Add error highlight to all payment method cards
                const previewPaymentCards = document.querySelectorAll('.preview-payment-method-card');
                previewPaymentCards.forEach(card => {
                    card.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                    setTimeout(() => {
                        card.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                    }, 3000);
                });
                return;
            }

            // Validate kas/bank selection (skip for CARD payment method)
            const paymentMethodCode = selectedModalPaymentMethod.value.toLowerCase();
            const isCard = paymentMethodCode.includes('card') ||
                paymentMethodCode.includes('rfid') ||
                paymentMethodCode.includes('kartu');

            if (!selectedModalKasBank && !isCard) {
                showToast('Kas/Bank wajib dipilih!', 'error');

                // Add error highlight to all kas/bank cards
                const previewKasBankCards = document.querySelectorAll('.preview-kas-bank-card');
                previewKasBankCards.forEach(card => {
                    card.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                    setTimeout(() => {
                        card.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                    }, 3000);
                });
                return;
            }

            // Get the actual value from the hidden input (needed for all payment methods)
            const modalJenisTransaksiValue = modalJenisTransaksi.value;
            console.log("Ini adalah modalJenisTransaksi value:", modalJenisTransaksiValue);

            // Validate RFID card for CARD payment method
            if (isCard) {
                // Check status RFID first
                if (!statusRfid || !storedRfidId) {
                    showToast('Kartu RFID wajib di-scan terlebih dahulu!', 'error');

                    // Add error highlight to card scan area
                    const cardArea = cardScanArea.querySelector('div');
                    cardArea.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                    setTimeout(() => {
                        cardArea.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                    }, 3000);
                    return;
                }

                // Double check hidden input as backup
                const rfidCardId = document.getElementById('rfidCardId');
                if (!rfidCardId || !rfidCardId.value.trim()) {
                    // Update hidden input with stored RFID ID
                    rfidCardId.value = storedRfidId;
                    console.log('🔄 Updated hidden input with stored RFID ID:', storedRfidId);
                }

                // Validate RFID saldo for CARD payment
                console.log('🔍 Validating RFID saldo:', {
                    saldoRfid,
                    modalJenisTransaksi: modalJenisTransaksi.value,
                    modalDpAmount
                });

                // Calculate total amount to be paid
                const subtotal = orderItems.reduce((total, item) => {
                    const itemSubtotal = item.price * item.qty;
                    const itemDiscount = item.discount || 0;
                    return total + (itemSubtotal - itemDiscount);
                }, 0);
                const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
                const total = subtotal - discount;

                console.log('💰 Payment calculation:', {
                    subtotal,
                    discount,
                    total,
                    saldoRfid
                });

                // For tunai transactions, check if total exceeds saldo
                if (modalJenisTransaksiValue === 'tunai') {
                    // console.log(total + ' > ' + saldoRfid);
                    // return false;
                    if (total > saldoRfid) {
                        console.log('❌ Saldo tidak mencukupi untuk tunai:', {
                            total,
                            saldoRfid
                        });
                        showToast(
                            `Saldo RFID tidak mencukupi! Saldo: Rp ${formatNumber(saldoRfid)}, Total: Rp ${formatNumber(total)}`,
                            'error');

                        // Add error highlight to card scan area
                        const cardArea = cardScanArea.querySelector('div');
                        cardArea.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                        setTimeout(() => {
                            cardArea.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                        }, 3000);
                        return;
                    } else {
                        console.log('✅ Saldo mencukupi untuk tunai:', {
                            total,
                            saldoRfid
                        });
                    }
                }

                // For kredit transactions, check if DP exceeds saldo
                if (modalJenisTransaksiValue === 'kredit') {
                    if (modalDpAmount > saldoRfid) {
                        console.log('❌ Saldo tidak mencukupi untuk DP:', {
                            modalDpAmount,
                            saldoRfid
                        });
                        showToast(
                            `Saldo RFID tidak mencukupi untuk DP! Saldo: Rp ${formatNumber(saldoRfid)}, DP: Rp ${formatNumber(modalDpAmount)}`,
                            'error');

                        // Add error highlight to card scan area
                        const cardArea = cardScanArea.querySelector('div');
                        cardArea.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                        setTimeout(() => {
                            cardArea.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                        }, 3000);
                        return;
                    } else {
                        console.log('✅ Saldo mencukupi untuk DP:', {
                            modalDpAmount,
                            saldoRfid
                        });
                    }
                }
            } else {
                // For non-CARD payment methods, skip RFID validation
                console.log('ℹ️ Non-CARD payment method, skipping RFID validation');
            }

            // Validate DP for kredit transactions
            if (modalJenisTransaksiValue === 'kredit') {
                const subtotal = orderItems.reduce((total, item) => {
                    const itemSubtotal = item.price * item.qty;
                    const itemDiscount = item.discount || 0;
                    return total + (itemSubtotal - itemDiscount);
                }, 0);
                const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
                const total = subtotal - discount;

                if (modalDpAmount > total) {
                    showToast('DP tidak boleh melebihi total transaksi!', 'error');
                    document.getElementById('previewDpAmount').focus();
                    return;
                }

                if (modalDpAmount < 0) {
                    showToast('DP tidak boleh kurang dari 0!', 'error');
                    document.getElementById('previewDpAmount').focus();
                    return;
                }
            }

            // Update hidden inputs with modal values
            document.getElementById('modalJenisTransaksi').value = modalJenisTransaksiValue;
            document.getElementById('modalMetodePembayaran').value = selectedModalPaymentMethod.value;
            document.getElementById('modalKasBankId').value = selectedModalKasBank ? selectedModalKasBank.value :
                '';
            document.getElementById('modalDpAmount').value = modalDpAmount;

            // Update controller inputs
            document.getElementById('jenisTransaksi').value = selectedModalTransactionType.value;
            document.getElementById('metodePembayaran').value = selectedModalPaymentMethod.value;
            document.getElementById('dpAmount').value = modalDpAmount;
            document.getElementById('kasBankId').value = selectedModalKasBank ? selectedModalKasBank.value : '';

            // Update RFID card ID for CARD payment method
            if (isCard) {
                const rfidCardId = document.getElementById('rfidCardId');
                if (rfidCardId && rfidCardId.value) {
                    // Add RFID card ID to form data
                    let rfidInput = document.getElementById('rfid_card_id');
                    if (!rfidInput) {
                        rfidInput = document.createElement('input');
                        rfidInput.type = 'hidden';
                        rfidInput.name = 'rfid_card_id';
                        rfidInput.id = 'rfid_card_id';
                        document.getElementById('salesForm').appendChild(rfidInput);
                    }
                    rfidInput.value = rfidCardId.value;
                }
            }

            const button = this;
            const originalText = button.innerHTML;
            const cancelButton = document.getElementById('cancelOrderPreview');

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
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="ti ti-loader animate-spin text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-lg font-semibold text-gray-800" id="loadingText">Memvalidasi data...</p>
                    <p class="text-sm text-gray-500 mt-1">Mohon tunggu sebentar</p>
                    
                    <!-- Progress Bar -->
                    <div class="w-64 bg-gray-200 rounded-full h-2 mt-4 mx-auto">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-1000 ease-out" style="width: 0%"></div>
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
                        const iconBg = loadingOverlay.querySelector('.bg-blue-100');
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
                            const form = document.getElementById('salesForm');
                            form.submit();
                        }, 800);
                    }
                }, state.delay);
            });
        });
    </script>
@endpush
