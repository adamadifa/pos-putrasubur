@extends('layouts.pos')

@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi Penjualan')

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
                        @foreach ($produk->take(10) as $product)
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

                                    <!-- Stock Badge -->
                                    <div class="flex items-center justify-center">
                                        @php
                                            $stockLevel = 'normal';
                                            $stockColor = 'bg-green-100 text-green-800 border-green-200';
                                            $stockIcon = 'ti-check-circle';
                                            $stockClass = '';
                                            $tooltip = 'Stok Normal';

                                            if ($product->stok <= 10) {
                                                $stockLevel = 'low';
                                                $stockColor = 'bg-red-100 text-red-800 border-red-200';
                                                $stockIcon = 'ti-alert-circle';
                                                $stockClass = 'stock-low';
                                                $tooltip = 'Stok Rendah - Segera Restock!';
                                            } elseif ($product->stok <= 50) {
                                                $stockLevel = 'medium';
                                                $stockColor = 'bg-orange-100 text-orange-800 border-orange-200';
                                                $stockIcon = 'ti-alert-triangle';
                                                $stockClass = 'stock-medium';
                                                $tooltip = 'Stok Menengah - Perlu Perhatian';
                                            } elseif ($product->stok >= 1000) {
                                                $stockLevel = 'high';
                                                $stockColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                                $stockIcon = 'ti-package';
                                                $stockClass = 'stock-high';
                                                $tooltip = 'Stok Tinggi - Persediaan Aman';
                                            }
                                        @endphp

                                        <div class="stock-badge inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $stockColor }} {{ $stockClass }}"
                                            data-tooltip="{{ $tooltip }}">
                                            <i class="ti {{ $stockIcon }} text-xs mr-1"></i>
                                            <span
                                                class="font-semibold">{{ number_format($product->stok, 0, ',', '.') }}</span>
                                            <span class="ml-1 opacity-75">{{ $product->satuan->nama ?? '' }}</span>
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
                <form action="{{ route('penjualan.update', $penjualan->encrypted_id) }}" method="POST" id="salesForm">
                    @csrf
                    @method('PUT')

                    <!-- Customer & Invoice Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h3>

                        <!-- Invoice Number -->
                        <div class="mb-2">
                            <input type="text" name="no_faktur" value="{{ old('no_faktur', $penjualan->no_faktur) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500"
                                placeholder="Nomor Faktur" readonly>
                        </div>

                        <!-- Date -->
                        <div class="mb-2">
                            <input type="date" name="tanggal" value="{{ old('tanggal', $penjualan->tanggal) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
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
                                value="{{ old('pelanggan_id', $penjualan->pelanggan_id) }}" required>
                        </div>

                        <!-- Transaction Type -->
                        <div class="mb-2">
                            <select name="jenis_transaksi" id="jenisTransaksi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="tunai"
                                    {{ old('jenis_transaksi', $penjualan->jenis_transaksi) == 'tunai' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="kredit"
                                    {{ old('jenis_transaksi', $penjualan->jenis_transaksi) == 'kredit' ? 'selected' : '' }}>
                                    Kredit
                                </option>
                            </select>
                        </div>

                        <!-- DP Amount (shown only for kredit) -->
                        <div class="mb-2 hidden" id="dpContainer">
                            <input type="text" id="dpAmountDisplay"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-right"
                                placeholder="Jumlah DP (Rp)" value="{{ old('dp_amount', $penjualan->dp_amount ?? 0) }}">
                            <input type="hidden" name="dp_amount" id="dpAmount"
                                value="{{ old('dp_amount', $penjualan->dp_amount ?? 0) }}">
                        </div>
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
                            <input type="text" id="diskonDisplay"
                                value="{{ old('diskon', $penjualan->diskon ?? 0) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-right"
                                placeholder="Diskon (Rp)">
                            <input type="hidden" name="diskon" id="diskon"
                                value="{{ old('diskon', $penjualan->diskon ?? 0) }}">
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
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <i class="ti ti-device-floppy text-lg mr-2"></i>
                                Update Transaksi
                            </button>
                        </div>

                        <!-- Hidden inputs for order items -->
                        <div id="hiddenItemsContainer">
                            <!-- Hidden inputs will be generated here dynamically -->
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
                        <span>Stok tersedia: </span>
                        <span id="modalProductStock" class="font-medium text-green-600"></span>
                        <span id="modalProductUnit" class="text-gray-500 ml-1"></span>
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
                    <a href="{{ route('pelanggan.create') }}" target="_blank"
                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="ti ti-plus text-lg mr-2"></i>
                        Tambah Pelanggan Baru
                    </a>
                </div>
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
                        Konfirmasi & Update
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

        /* Stock badge animations and effects */
        .product-card .stock-badge {
            transition: all 0.3s ease;
        }

        .product-card:hover .stock-badge {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Stock level specific animations */
        .stock-low {
            animation: pulse-red 2s infinite;
        }

        .stock-medium {
            animation: pulse-orange 3s infinite;
        }

        .stock-high {
            animation: glow-blue 4s infinite;
        }

        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
            }
        }

        @keyframes pulse-orange {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(245, 101, 101, 0.3);
            }

            50% {
                box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1);
            }
        }

        @keyframes glow-blue {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.2);
            }

            50% {
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            }
        }

        /* Stock badge hover tooltip */
        .stock-badge:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 20;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .stock-badge {
            position: relative;
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
    </style>
@endpush

@push('scripts')
    <script>
        let productIndex = 0;
        let orderItems = [];
        let currentProduct = null;
        let editingItemIndex = null;

        // Initialize existing order items from edit data
        @if ($penjualan->detailPenjualan)
            @foreach ($penjualan->detailPenjualan as $index => $detail)
                orderItems.push({
                    id: {{ $detail->produk_id }},
                    name: '{{ $detail->produk->nama_produk }}',
                    code: '{{ $detail->produk->kode_produk }}',
                    price: {{ $detail->harga }},
                    stock: {{ $detail->produk->stok }},
                    unit: '{{ $detail->produk->satuan->nama ?? '' }}',
                    qty: {{ $detail->qty }},
                    discount: {{ $detail->discount ?? 0 }},
                    index: productIndex++
                });
            @endforeach
        @endif

        // Customer modal functionality
        const customerModal = document.getElementById('customerModal');
        const searchCustomerBtn = document.getElementById('searchCustomerBtn');
        const closeCustomerModal = document.getElementById('closeCustomerModal');
        const customerSearch = document.getElementById('customerSearch');
        const customerDisplay = document.getElementById('customerDisplay');
        const pelangganId = document.getElementById('pelangganId');
        const clearCustomerBtn = document.getElementById('clearCustomerBtn');

        // Set initial customer display
        @if ($penjualan->pelanggan)
            customerDisplay.value = '{{ $penjualan->pelanggan->nama }} ({{ $penjualan->pelanggan->kode_pelanggan }})';
            clearCustomerBtn.classList.remove('hidden');
        @endif

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

                pelangganId.value = customerId;
                customerDisplay.value = `${customerName} (${customerCode})`;
                clearCustomerBtn.classList.remove('hidden');
                customerModal.classList.add('hidden');
                showToast(`Pelanggan ${customerName} dipilih`, 'success');
            });
        });

        // Transaction type functionality
        const jenisTransaksi = document.getElementById('jenisTransaksi');
        const dpContainer = document.getElementById('dpContainer');
        const dpAmountDisplay = document.getElementById('dpAmountDisplay');
        const dpAmount = document.getElementById('dpAmount');

        jenisTransaksi.addEventListener('change', function() {
            if (this.value === 'kredit') {
                dpContainer.classList.remove('hidden');
                dpAmountDisplay.required = true;
            } else {
                dpContainer.classList.add('hidden');
                dpAmountDisplay.required = false;
                dpAmountDisplay.value = '0';
                dpAmount.value = 0;
            }
            updateOrderSummary();
        });

        // Set initial DP visibility
        @if ($penjualan->jenis_transaksi == 'kredit')
            dpContainer.classList.remove('hidden');
            dpAmountDisplay.required = true;
        @endif

        // Update DP when amount changes
        dpAmountDisplay.addEventListener('input', function() {
            dpAmount.value = parseFormattedNumber(this.value);
            updateOrderSummary();
        });

        // Setup number formatting
        setupNumberInput(dpAmountDisplay);
        setupNumberInput(document.getElementById('diskonDisplay'));

        // Category filter functionality
        document.querySelectorAll('.category-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const category = this.dataset.category;
                filterProducts(category);
            });
        });

        // Product search functionality
        document.getElementById('productSearch').addEventListener('input', function(e) {
            searchProducts(e.target.value);
        });

        // Barcode search functionality
        document.getElementById('barcodeSearch').addEventListener('input', function(e) {
            const barcode = e.target.value.trim();
            if (barcode) {
                document.getElementById('productSearch').value = '';
                searchProducts(barcode);
            } else {
                document.querySelectorAll('.product-card').forEach(product => {
                    product.style.display = 'block';
                });
            }
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

        // Search products
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

        // Product click handler
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function() {
                const productData = {
                    id: this.dataset.id,
                    name: this.dataset.name,
                    code: this.dataset.code,
                    price: parseFloat(this.dataset.price),
                    stock: parseInt(this.dataset.stock),
                    unit: this.dataset.unit
                };
                showQuantityModal(productData);
            });
        });

        // Quantity Modal functionality
        const quantityModal = document.getElementById('quantityModal');
        const closeQuantityModal = document.getElementById('closeQuantityModal');
        const cancelQuantity = document.getElementById('cancelQuantity');
        const confirmQuantity = document.getElementById('confirmQuantity');
        const quantityInput = document.getElementById('quantityInput');
        const decreaseQty = document.getElementById('decreaseQty');
        const increaseQty = document.getElementById('increaseQty');

        function showQuantityModal(product, currentQty = 1, currentDiscount = 0, currentPrice = null) {
            currentProduct = product;
            document.getElementById('modalProductName').textContent = product.name;
            document.getElementById('modalProductCode').textContent = product.code;
            document.getElementById('modalProductPrice').textContent = `Rp ${formatNumber(product.price)}`;
            document.getElementById('modalProductStock').textContent = formatNumber(product.stock);
            document.getElementById('modalProductUnit').textContent = product.unit;
            document.getElementById('modalProductUnitInInput').textContent = product.unit;

            const priceInput = document.getElementById('modalPriceInput');
            const defaultPrice = currentPrice || product.price;
            priceInput.value = formatNumberInput(defaultPrice.toString());
            document.getElementById('modalDefaultPrice').textContent = `Rp ${formatNumber(product.price)}`;

            quantityInput.value = formatDecimalInput(currentQty);
            document.getElementById('discountInput').value = formatNumberInput(currentDiscount.toString());

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

            updateModalTotalPrice();
            quantityModal.classList.remove('hidden');
            quantityInput.focus();
            quantityInput.select();
        }

        function closeQuantityModalHandler() {
            quantityModal.classList.add('hidden');
            currentProduct = null;
            editingItemIndex = null;
        }

        function updateModalTotalPrice() {
            if (currentProduct) {
                const qty = parseFormattedDecimal(quantityInput.value) || 0;
                const discount = parseFormattedNumber(document.getElementById('discountInput').value) || 0;
                const currentPrice = parseFormattedNumber(document.getElementById('modalPriceInput').value) ||
                    currentProduct.price;
                const subtotal = currentPrice * qty;
                const total = Math.max(0, subtotal - discount);

                document.getElementById('modalSubtotalPrice').textContent = `Rp ${formatNumber(subtotal)}`;
                const discountRow = document.getElementById('modalDiscountRow');
                const discountPriceElement = document.getElementById('modalDiscountPrice');

                if (discount > 0) {
                    discountRow.style.display = 'flex';
                    discountPriceElement.textContent = `Rp ${formatNumber(discount)}`;
                } else {
                    discountRow.style.display = 'none';
                }

                document.getElementById('modalTotalPrice').textContent = `Rp ${formatNumber(total)}`;

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

        quantityModal.addEventListener('click', (e) => {
            if (e.target === quantityModal) {
                closeQuantityModalHandler();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !quantityModal.classList.contains('hidden')) {
                closeQuantityModalHandler();
            }
        });

        // Setup inputs
        setupDecimalInput(quantityInput);
        setupNumberInput(document.getElementById('discountInput'));
        setupNumberInput(document.getElementById('modalPriceInput'));

        // Input handlers
        document.getElementById('modalPriceInput').addEventListener('input', updateModalTotalPrice);
        quantityInput.addEventListener('input', updateModalTotalPrice);
        document.getElementById('discountInput').addEventListener('input', updateModalTotalPrice);

        // Quantity buttons
        decreaseQty.addEventListener('click', function() {
            let value = parseFormattedDecimal(quantityInput.value) || 1;
            if (value > 0.1) {
                let newValue = value >= 1 ? value - 1 : Math.max(0.1, Math.round((value - 0.1) * 10) / 10);
                quantityInput.value = formatDecimalInput(newValue);
                quantityInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                updateModalTotalPrice();
            }
        });

        increaseQty.addEventListener('click', function() {
            let value = parseFormattedDecimal(quantityInput.value) || 1;
            let newValue = value % 1 === 0 ? value + 1 : Math.round((value + 0.1) * 10) / 10;
            quantityInput.value = formatDecimalInput(newValue);
            quantityInput.dispatchEvent(new Event('input', {
                bubbles: true
            }));
            updateModalTotalPrice();
        });

        // Confirm quantity
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

                const subtotal = currentPrice * qty;
                if (discount > subtotal) {
                    showToast('Potongan tidak boleh melebihi subtotal!', 'error');
                    return;
                }

                if (editingItemIndex !== null) {
                    updateOrderItemQuantity(editingItemIndex, qty, discount, currentPrice);
                } else {
                    addToOrder(currentProduct, qty, discount, currentPrice);
                }

                closeQuantityModalHandler();
            }
        });

        quantityInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                confirmQuantity.click();
            }
        });

        // Add to order function
        function addToOrder(product, quantity = 1, discount = 0, customPrice = null) {
            const existingIndex = orderItems.findIndex(item => item.id === product.id);

            if (existingIndex !== -1) {
                orderItems[existingIndex].qty = quantity;
                orderItems[existingIndex].discount = discount;
                orderItems[existingIndex].price = customPrice || product.price;
                updateOrderItem(existingIndex);
                showToast(`${product.name} diperbarui dalam pesanan`, 'success');
            } else {
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

        // Render order item
        function renderOrderItem(item) {
            const orderItemsContainer = document.getElementById('orderItems');
            const emptyState = document.getElementById('emptyState');

            emptyState.style.display = 'none';

            const subtotal = item.price * item.qty;
            const discount = item.discount || 0;
            const total = subtotal - discount;

            const itemHtml = `
                <div class="order-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200 cursor-pointer" data-index="${item.index}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm">${item.name}</h4>
                            <p class="text-xs text-gray-500 mt-1">${item.code}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="text-center">
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
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Harga Satuan</span>
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
                        
                        <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-200">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="font-bold text-lg ${discount > 0 ? 'text-green-600' : 'text-blue-600'}">Rp ${formatNumber(total)}</span>
                        </div>
                    </div>
                    
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
            addOrderItemListeners(orderItemsContainer.lastElementChild, item.index);
        }

        // Add event listeners to order item
        function addOrderItemListeners(element, index) {
            const removeBtn = element.querySelector('.remove-btn');
            removeBtn.addEventListener('click', () => removeOrderItem(index));
            element.addEventListener('click', () => editOrderItemQuantity(index));
        }

        // Update order item
        function updateOrderItem(itemIndex) {
            const item = orderItems[itemIndex];
            const element = document.querySelector(`[data-index="${item.index}"]`);

            element.querySelector('.qty-input').value = item.qty;
            element.querySelector('.discount-input').value = item.discount || 0;
            element.querySelector('input[name*="[harga]"]').value = item.price;

            const subtotal = item.price * item.qty;
            const discount = item.discount || 0;
            const total = subtotal - discount;

            const qtyElement = element.querySelector('.qty');
            qtyElement.textContent = formatDecimalInput(item.qty);

            const priceBreakdownContainer = element.querySelector('.space-y-2');
            priceBreakdownContainer.innerHTML = `
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Harga Satuan</span>
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
            showQuantityModal(item, item.qty, item.discount || 0, item.price);
        }

        // Update order item quantity
        function updateOrderItemQuantity(itemIndex, newQty, newDiscount = 0, newPrice = null) {
            const item = orderItems[itemIndex];

            if (newQty <= 0) {
                removeOrderItem(item.index);
                showToast(`${item.name} dihapus dari pesanan`, 'info');
                return;
            }

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

        // Remove order item
        function removeOrderItem(index) {
            orderItems = orderItems.filter(item => item.index !== index);
            const element = document.querySelector(`[data-index="${index}"]`);
            element.remove();

            if (orderItems.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
            }

            updateOrderSummary();
        }

        // Update order summary
        function updateOrderSummary() {
            const subtotal = orderItems.reduce((total, item) => {
                const itemSubtotal = item.price * item.qty;
                const itemDiscount = item.discount || 0;
                return total + (itemSubtotal - itemDiscount);
            }, 0);

            const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
            const total = subtotal - discount;

            const jenisTransaksi = document.getElementById('jenisTransaksi').value;
            const dpAmount = parseFormattedNumber(document.getElementById('dpAmountDisplay').value);
            const paymentBreakdown = document.getElementById('paymentBreakdown');

            document.getElementById('orderCount').textContent = `${orderItems.length} item`;
            document.getElementById('subtotalDisplay').textContent = `Rp ${formatNumber(subtotal)}`;
            document.getElementById('discountDisplay').textContent = `Rp ${formatNumber(discount)}`;
            document.getElementById('totalDisplay').textContent = `Rp ${formatNumber(total)}`;

            if (jenisTransaksi === 'kredit' && total > 0) {
                paymentBreakdown.classList.remove('hidden');
                const remaining = Math.max(0, total - dpAmount);
                document.getElementById('dpDisplay').textContent = `Rp ${formatNumber(dpAmount)}`;
                document.getElementById('remainingDisplay').textContent = `Rp ${formatNumber(remaining)}`;
            } else {
                paymentBreakdown.classList.add('hidden');
            }
        }

        // Utility functions
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function formatNumberInput(value) {
            const numericValue = value.toString().replace(/\D/g, '');
            return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseFormattedNumber(value) {
            return parseInt(value.replace(/\./g, '')) || 0;
        }

        function formatDecimalInput(value) {
            if (!value && value !== 0) return '';
            let strValue = value.toString();
            if (strValue.includes(',')) {
                let parts = strValue.split(',');
                let integerPart = parts[0];
                let decimalPart = parts[1] || '';
                integerPart = integerPart.replace(/\./g, '');
                if (integerPart && integerPart !== '0') {
                    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
                if (decimalPart !== '') {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart;
                }
            } else {
                let numValue;
                if (typeof value === 'number') {
                    numValue = value;
                } else {
                    let cleanValue = strValue.replace(',', '.');
                    numValue = parseFloat(cleanValue);
                }
                if (isNaN(numValue)) return '';
                let parts = numValue.toString().split('.');
                let integerPart = parts[0];
                let decimalPart = parts[1] || '';
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                if (decimalPart) {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart;
                }
            }
        }

        function parseFormattedDecimal(value) {
            if (!value) return 0;
            let cleanValue = value.toString()
                .replace(/\./g, '')
                .replace(',', '.');
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
                let rawValue = e.target.value.replace(/[^\d,]/g, '');
                if (!rawValue) return;

                let hasDecimal = rawValue.includes(',');
                let integerPart = '';
                let decimalPart = '';

                if (hasDecimal) {
                    let parts = rawValue.split(',');
                    integerPart = parts[0] || '';
                    decimalPart = parts[1] || '';
                    if (parts.length > 2) {
                        decimalPart = parts.slice(1).join('');
                    }
                } else {
                    integerPart = rawValue;
                }

                let newValue = '';
                if (integerPart) {
                    if (integerPart.length >= 4) {
                        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }
                    newValue = integerPart;
                }

                if (hasDecimal) {
                    newValue += ',' + decimalPart;
                }

                let numericValue = parseFormattedDecimal(newValue);
                if (numericValue > 0) {
                    lastValidValue = newValue;
                }

                if (newValue !== oldValue) {
                    isFormatting = true;
                    e.target.value = newValue;
                    let newCursorPos = cursorPosition;
                    let oldDots = (oldValue.substring(0, cursorPosition).match(/\./g) || []).length;
                    let newDots = (newValue.substring(0, cursorPosition).match(/\./g) || []).length;

                    if (newValue.length > oldValue.length) {
                        let addedDots = (newValue.match(/\./g) || []).length - (oldValue.match(/\./g) || []).length;
                        if (addedDots > 0) {
                            let textBeforeCursor = oldValue.substring(0, cursorPosition);
                            let digitsBeforeCursor = textBeforeCursor.replace(/[^\d]/g, '').length;
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

                    newCursorPos = Math.max(0, Math.min(newCursorPos, newValue.length));
                    e.target.setSelectionRange(newCursorPos, newCursorPos);
                    isFormatting = false;
                }
            });

            input.addEventListener('blur', function(e) {
                let inputValue = e.target.value.trim();
                if (!inputValue) {
                    e.target.value = lastValidValue || '1';
                    return;
                }
                let value = parseFormattedDecimal(inputValue);
                if (value <= 0 || isNaN(value)) {
                    e.target.value = lastValidValue || '1';
                } else {
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

        // Toast notification function
        function showToast(message, type = 'info') {
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

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }

        // Initialize order display
        function initializeOrderDisplay() {
            console.log('initializeOrderDisplay called');
            console.log('orderItems length:', orderItems.length);
            console.log('orderItems:', orderItems);

            if (orderItems.length > 0) {
                document.getElementById('emptyState').style.display = 'none';
                orderItems.forEach((item, index) => {
                    console.log('Rendering item:', item);
                    renderOrderItem(item);
                });
                updateOrderSummary();
            } else {
                console.log('No order items to display');
            }
        }

        // Show order preview
        function showOrderPreview() {
            console.log('showOrderPreview function called');
            console.log('Customer display value:', document.getElementById('customerDisplay').value);
            console.log('Pelanggan ID value:', document.getElementById('pelangganId').value);

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

            // Update hidden inputs with order items data
            const hiddenContainer = document.getElementById('hiddenItemsContainer');
            hiddenContainer.innerHTML = ''; // Clear existing inputs

            if (orderItems.length === 0) {
                console.error('No order items to send!');
                showToast('Tidak ada item untuk dikirim', 'error');
                return;
            }

            orderItems.forEach((item, index) => {
                const itemsForController = {
                    produk_id: item.id,
                    qty: item.qty,
                    harga: item.price,
                    discount: item.discount || 0
                };

                console.log(`Creating hidden inputs for item ${index}:`, itemsForController);

                // Create hidden inputs for each field
                Object.keys(itemsForController).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `items[${index}][${key}]`;
                    input.value = itemsForController[key];
                    hiddenContainer.appendChild(input);

                    console.log(`Created input: ${input.name} = ${input.value}`);
                });
            });

            console.log('Hidden inputs created for items:', orderItems);
            console.log('Hidden inputs HTML:', hiddenContainer.innerHTML);
            console.log('Total hidden inputs created:', hiddenContainer.children.length);

            // Show modal
            document.getElementById('orderPreviewModal').classList.remove('hidden');
        }

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

        // Confirm order save
        confirmOrderSave.addEventListener('click', function() {
            console.log('Confirm order save clicked');
            console.log('Order items:', orderItems);
            console.log('Form data:', {
                pelanggan_id: document.getElementById('pelangganId').value,
                jenis_transaksi: document.getElementById('jenisTransaksi').value,
                dp_amount: document.getElementById('dpAmount').value,
                diskon: document.getElementById('diskon').value
            });

            // Log hidden inputs that will be sent
            const hiddenInputs = document.querySelectorAll('#hiddenItemsContainer input');
            console.log('Hidden inputs to be sent:', Array.from(hiddenInputs).map(input => ({
                name: input.name,
                value: input.value
            })));

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

                            // Ensure hidden inputs are properly set before submit
                            const hiddenContainer = document.getElementById(
                                'hiddenItemsContainer');
                            if (hiddenContainer.children.length === 0) {
                                console.error('No hidden inputs found!');
                                showToast('Terjadi kesalahan: Data items tidak lengkap',
                                    'error');
                                return;
                            }

                            console.log('Submitting form with hidden inputs:',
                                hiddenContainer.innerHTML);

                            // Log form data before submit
                            const formData = new FormData(form);
                            console.log('Form data entries:');
                            for (let [key, value] of formData.entries()) {
                                console.log(`${key}: ${value}`);
                            }

                            form.submit();
                        }, 800);
                    }
                }, state.delay);
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeOrderDisplay();

            // Update discount when changed
            document.getElementById('diskonDisplay').addEventListener('input', function() {
                document.getElementById('diskon').value = parseFormattedNumber(this.value);
                updateOrderSummary();
            });

            // Format initial values
            if (document.getElementById('diskonDisplay').value && document.getElementById('diskonDisplay').value !==
                '0') {
                document.getElementById('diskonDisplay').value = formatNumberInput(document.getElementById(
                    'diskonDisplay').value);
            }

            if (dpAmountDisplay.value && dpAmountDisplay.value !== '0') {
                dpAmountDisplay.value = formatNumberInput(dpAmountDisplay.value);
            }

            // Form submission handler - prevent default and show preview
            const salesForm = document.getElementById('salesForm');

            // Remove any existing event listeners
            const newForm = salesForm.cloneNode(true);
            salesForm.parentNode.replaceChild(newForm, salesForm);

            // Add new event listener
            newForm.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                e.preventDefault();

                // Validate if there are order items
                if (orderItems.length === 0) {
                    showToast('Pilih minimal satu produk terlebih dahulu', 'warning');
                    return;
                }

                // Validate customer selection
                const customerId = document.getElementById('pelangganId').value;
                if (!customerId) {
                    showToast('Pilih pelanggan terlebih dahulu', 'warning');
                    return;
                }

                console.log('Validation passed, showing preview');
                showOrderPreview();
            });
        });
    </script>
@endpush
