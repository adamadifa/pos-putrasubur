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
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
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
                                value="{{ old('pelanggan_id') }}" required>
                        </div>

                        <!-- Transaction Type -->
                        <div class="mb-2">
                            <select name="jenis_transaksi" id="jenisTransaksi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="tunai" {{ old('jenis_transaksi', 'tunai') == 'tunai' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="kredit" {{ old('jenis_transaksi') == 'kredit' ? 'selected' : '' }}>Kredit
                                </option>
                            </select>
                        </div>

                        <!-- DP Amount (shown only for kredit) -->
                        <div class="mb-2 hidden" id="dpContainer">
                            <input type="text" id="dpAmountDisplay"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-right"
                                placeholder="Jumlah DP (Rp)" value="{{ old('dp_amount', 0) }}">
                            <input type="hidden" name="dp_amount" id="dpAmount" value="{{ old('dp_amount', 0) }}">
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
                                Simpan Transaksi
                            </button>
                            <a href="{{ route('penjualan.index') }}"
                                class="w-full py-3 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors text-center block">
                                <i class="ti ti-x text-lg mr-2"></i>
                                Batal
                            </a>
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
                <div class="mb-6">
                    <label for="quantityInput" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantity
                    </label>
                    <div class="flex items-center space-x-3">
                        <button type="button" id="decreaseQty"
                            class="w-10 h-10 bg-gray-200 rounded-lg text-gray-600 hover:bg-gray-300 flex items-center justify-center font-semibold">
                            -
                        </button>
                        <input type="text" id="quantityInput"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-lg font-semibold"
                            value="1" placeholder="0">
                        <button type="button" id="increaseQty"
                            class="w-10 h-10 bg-blue-600 rounded-lg text-white hover:bg-blue-700 flex items-center justify-center font-semibold">
                            +
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan jumlah yang diinginkan (maksimal sesuai stok)</p>
                </div>

                <!-- Total Price Preview -->
                <div class="mb-6 p-3 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Harga:</span>
                        <span id="modalTotalPrice" class="text-lg font-bold text-blue-600">Rp 0</span>
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
        }

        #decreaseQty:hover,
        #increaseQty:hover {
            transform: scale(1.05);
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

        /* Order item hover effects */
        .order-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .order-item {
            transition: all 0.2s ease;
        }

        /* Edit hint */
        .order-item:hover::after {
            content: "Klik untuk edit quantity";
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 10;
        }

        .order-item {
            position: relative;
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

        // Transaction type functionality
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
            }
            updateOrderSummary();
        });

        // Set initial DP visibility based on old value
        @if (old('jenis_transaksi') == 'kredit')
            dpContainer.classList.remove('hidden');
            dpAmountDisplay.required = true;
        @endif

        // Update DP when amount changes
        dpAmountDisplay.addEventListener('input', function() {
            dpAmount.value = parseFormattedNumber(this.value);
            updateOrderSummary();
        });

        // Setup number formatting for input fields
        const diskonDisplay = document.getElementById('diskonDisplay');
        const diskonHidden = document.getElementById('diskon');

        setupNumberInput(diskonDisplay);
        setupNumberInput(dpAmountDisplay);

        // Update hidden fields when display fields change
        diskonDisplay.addEventListener('input', function() {
            diskonHidden.value = parseFormattedNumber(this.value);
            updateOrderSummary();
        });

        // Format initial values
        if (diskonDisplay.value && diskonDisplay.value !== '0') {
            diskonDisplay.value = formatNumberInput(diskonDisplay.value);
        }

        if (dpAmountDisplay.value && dpAmountDisplay.value !== '0') {
            dpAmountDisplay.value = formatNumberInput(dpAmountDisplay.value);
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

                showQuantityModal(productData);
            });
        });

        // Show quantity modal
        function showQuantityModal(product, currentQty = 1) {
            currentProduct = product;

            // Update modal content
            document.getElementById('modalProductName').textContent = product.name;
            document.getElementById('modalProductCode').textContent = product.code;
            document.getElementById('modalProductPrice').textContent = `Rp ${formatNumber(product.price)}`;
            document.getElementById('modalProductStock').textContent = formatNumber(product.stock);
            document.getElementById('modalProductUnit').textContent = product.unit;

            // Set quantity (for edit mode or new item)
            quantityInput.value = formatDecimalInput(currentQty);
            quantityInput.dataset.maxStock = product.stock;

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
            quantityModal.classList.add('hidden');
            currentProduct = null;
            editingItemIndex = null;
        }

        // Update modal total price
        function updateModalTotalPrice() {
            if (currentProduct) {
                const qty = parseFormattedDecimal(quantityInput.value) || 0;
                const total = currentProduct.price * qty;
                document.getElementById('modalTotalPrice').textContent = `Rp ${formatNumber(total)}`;
            }
        }

        // Quantity modal event listeners
        closeQuantityModal.addEventListener('click', closeQuantityModalHandler);
        cancelQuantity.addEventListener('click', closeQuantityModalHandler);

        // Close modal when clicking outside
        quantityModal.addEventListener('click', (e) => {
            if (e.target === quantityModal) {
                closeQuantityModalHandler();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !quantityModal.classList.contains('hidden')) {
                closeQuantityModalHandler();
            }
        });

        // Setup quantity input with decimal formatting
        setupDecimalInput(quantityInput);

        // Quantity input handlers
        quantityInput.addEventListener('input', function() {
            let numericValue = parseFormattedDecimal(this.value);

            if (numericValue < 0) numericValue = 0;

            if (currentProduct && numericValue > currentProduct.stock) {
                numericValue = currentProduct.stock;
                this.value = formatDecimalInput(numericValue);
                showToast(`Maksimal quantity adalah ${formatNumber(currentProduct.stock)} ${currentProduct.unit}`,
                    'warning');
            }

            updateModalTotalPrice();
        });

        // Decrease quantity button
        decreaseQty.addEventListener('click', function() {
            let value = parseFormattedDecimal(quantityInput.value) || 1;
            if (value > 0.1) {
                // Decrease by 1 for values >= 1, by 0.1 for decimal values
                let newValue = value >= 1 ? value - 1 : Math.max(0.1, Math.round((value - 0.1) * 10) / 10);
                quantityInput.value = formatDecimalInput(newValue);
                updateModalTotalPrice();
            }
        });

        // Increase quantity button
        increaseQty.addEventListener('click', function() {
            let value = parseFormattedDecimal(quantityInput.value) || 1;
            if (currentProduct && value < currentProduct.stock) {
                // Increase by 1 for integer values, by 0.1 for decimal values
                let newValue = value % 1 === 0 ? value + 1 : Math.round((value + 0.1) * 10) / 10;
                quantityInput.value = formatDecimalInput(newValue);
                updateModalTotalPrice();
            } else if (currentProduct) {
                showToast(`Maksimal quantity adalah ${formatNumber(currentProduct.stock)} ${currentProduct.unit}`,
                    'warning');
            }
        });

        // Confirm quantity and add to order
        confirmQuantity.addEventListener('click', function() {
            if (currentProduct) {
                const qty = parseFormattedDecimal(quantityInput.value) || 1;

                if (qty <= 0) {
                    showToast('Quantity harus lebih dari 0!', 'error');
                    return;
                }

                if (editingItemIndex !== null) {
                    // Edit existing item
                    updateOrderItemQuantity(editingItemIndex, qty);
                } else {
                    // Add new item
                    addToOrder(currentProduct, qty);
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

        function addToOrder(product, quantity = 1) {
            // Check if product already in order
            const existingIndex = orderItems.findIndex(item => item.id === product.id);

            if (existingIndex !== -1) {
                // Calculate new total quantity
                const newTotalQty = orderItems[existingIndex].qty + quantity;

                if (newTotalQty <= product.stock) {
                    orderItems[existingIndex].qty = newTotalQty;
                    updateOrderItem(existingIndex);
                    showToast(`${product.name} ditambahkan ke pesanan (${quantity} ${product.unit})`, 'success');
                } else {
                    showToast('Stok tidak mencukupi!', 'error');
                }
            } else {
                // Validate quantity against stock
                if (quantity <= product.stock) {
                    // Add new item
                    const newItem = {
                        ...product,
                        qty: quantity,
                        index: productIndex++
                    };
                    orderItems.push(newItem);
                    renderOrderItem(newItem);
                    showToast(`${product.name} ditambahkan ke pesanan (${quantity} ${product.unit})`, 'success');
                } else {
                    showToast('Stok tidak mencukupi!', 'error');
                }
            }

            updateOrderSummary();
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

            const itemHtml = `
                <div class="order-item flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" data-index="${item.index}">
                    <div class="flex-1 edit-item-area">
                        <h4 class="font-medium text-sm text-gray-900">${item.name}</h4>
                        <p class="text-xs text-gray-500">${item.code}</p>
                        <p class="text-sm font-medium text-blue-600">Rp ${formatNumber(item.price)}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-center edit-item-area">
                            <span class="qty text-sm font-medium text-gray-900">${formatDecimalInput(item.qty)}</span>
                            <p class="text-xs text-gray-500">${item.unit}</p>
                        </div>
                        <button type="button" class="remove-btn w-8 h-8 bg-red-500 rounded-lg text-white hover:bg-red-600 flex items-center justify-center" onclick="event.stopPropagation()">
                            <i class="ti ti-trash text-sm"></i>
                        </button>
                    </div>
                    <input type="hidden" name="items[${item.index}][produk_id]" value="${item.id}">
                    <input type="hidden" name="items[${item.index}][qty]" value="${item.qty}" class="qty-input">
                    <input type="hidden" name="items[${item.index}][harga]" value="${item.price}">
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

            element.querySelector('.qty').textContent = formatDecimalInput(item.qty);
            element.querySelector('.qty-input').value = item.qty;
        }

        // Edit order item quantity
        function editOrderItemQuantity(index) {
            const itemIndex = orderItems.findIndex(item => item.index === index);
            if (itemIndex === -1) return;

            const item = orderItems[itemIndex];
            editingItemIndex = itemIndex;

            // Show modal with current quantity
            showQuantityModal(item, item.qty);
        }

        // Update order item quantity
        function updateOrderItemQuantity(itemIndex, newQty) {
            const item = orderItems[itemIndex];

            // Validate new quantity
            if (newQty <= 0) {
                removeOrderItem(item.index);
                showToast(`${item.name} dihapus dari pesanan`, 'info');
                return;
            }

            if (newQty > item.stock) {
                showToast('Quantity melebihi stok yang tersedia!', 'error');
                return;
            }

            // Update quantity
            const oldQty = item.qty;
            item.qty = newQty;
            updateOrderItem(itemIndex);
            updateOrderSummary();

            showToast(`${item.name} quantity diupdate: ${formatNumber(oldQty)} → ${formatNumber(newQty)} ${item.unit}`,
                'success');
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
            const subtotal = orderItems.reduce((total, item) => total + (item.price * item.qty), 0);
            const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
            const total = subtotal - discount;

            // Get transaction type and DP amount
            const jenisTransaksi = document.getElementById('jenisTransaksi').value;
            const dpAmount = parseFormattedNumber(document.getElementById('dpAmountDisplay').value);
            const paymentBreakdown = document.getElementById('paymentBreakdown');

            document.getElementById('orderCount').textContent = `${orderItems.length} item`;
            document.getElementById('subtotalDisplay').textContent = `Rp ${formatNumber(subtotal)}`;
            document.getElementById('discountDisplay').textContent = `Rp ${formatNumber(discount)}`;
            document.getElementById('totalDisplay').textContent = `Rp ${formatNumber(total)}`;

            // Show/hide payment breakdown for kredit
            if (jenisTransaksi === 'kredit' && total > 0) {
                paymentBreakdown.classList.remove('hidden');
                const remaining = Math.max(0, total - dpAmount);
                document.getElementById('dpDisplay').textContent = `Rp ${formatNumber(dpAmount)}`;
                document.getElementById('remainingDisplay').textContent = `Rp ${formatNumber(remaining)}`;
            } else {
                paymentBreakdown.classList.add('hidden');
            }
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
            if (!value) return '';

            // If value already contains comma, handle it carefully
            if (value.toString().includes(',')) {
                let parts = value.toString().split(',');
                let integerPart = parts[0];
                let decimalPart = parts[1] || '';

                // Remove existing dots from integer part and format
                integerPart = integerPart.replace(/\./g, '');

                // Format integer part with thousand separator
                if (integerPart) {
                    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                // Return with decimal part
                if (decimalPart !== '') {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart + ',';
                }
            } else {
                // Convert to string and handle both comma and dot as decimal separator
                let strValue = value.toString().replace(',', '.');

                // Parse as float
                let numValue = parseFloat(strValue);
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

            input.addEventListener('input', function(e) {
                if (isFormatting) return;

                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;

                // Allow digits, comma, and dot
                let inputValue = e.target.value.replace(/[^\d,\.]/g, '');

                // Handle multiple decimal separators
                let commaCount = (inputValue.match(/,/g) || []).length;

                // If multiple commas, keep only the first one
                if (commaCount > 1) {
                    let firstCommaIndex = inputValue.indexOf(',');
                    inputValue = inputValue.substring(0, firstCommaIndex + 1) + inputValue.substring(
                        firstCommaIndex + 1).replace(/,/g, '');
                }

                // If user types dot after comma, ignore it
                if (inputValue.includes(',') && inputValue.lastIndexOf('.') > inputValue.lastIndexOf(',')) {
                    inputValue = inputValue.replace(/\./g, '');
                }

                // Only format if not actively typing after comma
                let shouldFormat = true;
                if (inputValue.includes(',')) {
                    let parts = inputValue.split(',');
                    // Don't format if user is still typing decimal part
                    if (parts[1] !== undefined && cursorPosition > inputValue.indexOf(',')) {
                        shouldFormat = false;
                    }
                }

                let newValue;
                if (shouldFormat && !inputValue.endsWith(',')) {
                    newValue = formatDecimalInput(inputValue);
                } else {
                    // Just clean the input without full formatting
                    newValue = inputValue;
                }

                if (newValue !== oldValue) {
                    isFormatting = true;
                    e.target.value = newValue;

                    // Adjust cursor position more intelligently
                    let newCursorPos = cursorPosition;

                    // If a dot was added for thousand separator, adjust cursor
                    let oldDots = (oldValue.match(/\./g) || []).length;
                    let newDots = (newValue.match(/\./g) || []).length;
                    let dotDiff = newDots - oldDots;

                    if (dotDiff > 0 && cursorPosition > 0) {
                        // Count dots before cursor in old value
                        let dotsBeforeCursor = (oldValue.substring(0, cursorPosition).match(/\./g) || []).length;
                        let newDotsBeforeCursor = (newValue.substring(0, cursorPosition + dotDiff).match(/\./g) ||
                        []).length;
                        newCursorPos = cursorPosition + (newDotsBeforeCursor - dotsBeforeCursor);
                    }

                    e.target.setSelectionRange(newCursorPos, newCursorPos);
                    isFormatting = false;
                }
            });

            input.addEventListener('blur', function(e) {
                let value = parseFormattedDecimal(e.target.value);
                if (value <= 0 || isNaN(value)) {
                    e.target.value = '1';
                } else {
                    e.target.value = formatDecimalInput(value);
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

        // Form validation
        document.getElementById('salesForm').addEventListener('submit', function(e) {
            if (orderItems.length === 0) {
                e.preventDefault();
                showToast('Minimal harus ada 1 produk!', 'error');
                return false;
            }

            if (!pelangganId.value) {
                e.preventDefault();
                showToast('Silakan pilih pelanggan terlebih dahulu!', 'error');
                searchCustomerBtn.focus();
                return false;
            }

            // Validate DP for kredit transactions
            const jenisTransaksi = document.getElementById('jenisTransaksi').value;
            if (jenisTransaksi === 'kredit') {
                const subtotal = orderItems.reduce((total, item) => total + (item.price * item.qty), 0);
                const discount = parseFormattedNumber(document.getElementById('diskonDisplay').value);
                const total = subtotal - discount;
                const dpAmount = parseFormattedNumber(document.getElementById('dpAmountDisplay').value);

                if (dpAmount > total) {
                    e.preventDefault();
                    showToast('DP tidak boleh melebihi total transaksi!', 'error');
                    document.getElementById('dpAmountDisplay').focus();
                    return false;
                }

                if (dpAmount < 0) {
                    e.preventDefault();
                    showToast('DP tidak boleh kurang dari 0!', 'error');
                    document.getElementById('dpAmountDisplay').focus();
                    return false;
                }
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;

            // If validation fails, restore button
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 5000);
        });
    </script>
@endpush
