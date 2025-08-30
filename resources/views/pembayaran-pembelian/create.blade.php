@extends('layouts.pos')

@section('title', 'Buat Pembayaran Pembelian')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pembayaran-pembelian.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-red-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Buat Pembayaran Pembelian Baru
                                </h1>
                                <p class="text-gray-500 mt-1">Input pembayaran untuk transaksi pembelian yang belum lunas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-green-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-red-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-orange-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Pembayaran Pembelian</h2>
                    </div>
                </div>

                <form action="{{ route('pembayaran-pembelian.store') }}" method="POST" class="p-8">
                    @csrf
                    <div class="space-y-8">
                        <!-- Pilihan Transaksi -->
                        <div class="space-y-2">
                            <label for="pembelian_id" class="block text-sm font-semibold text-gray-700">
                                Pilih Transaksi Pembelian <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <input type="text" id="pembelian_display" readonly
                                    class="w-full pl-11 pr-20 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('pembelian_id') border-red-500 @enderror"
                                    placeholder="Klik tombol cari untuk memilih transaksi pembelian...">
                                <input type="hidden" id="pembelian_id" name="pembelian_id" value="">
                                <button type="button" id="search_transaction_btn"
                                    class="absolute inset-y-0 right-0 px-4 flex items-center bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-r-lg hover:from-orange-600 hover:to-red-700 transition-all duration-200 group-hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </button>
                            </div>
                            @error('pembelian_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Detail Transaksi (akan muncul setelah transaksi dipilih) -->
                        <div id="transaction-details"
                            class="hidden bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-100">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-orange-100 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-orange-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Detail Transaksi Pembelian</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white rounded-lg p-4 border border-orange-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-orange-100 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-orange-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Total Transaksi</p>
                                            <p id="total-transaksi" class="text-lg font-bold text-gray-900"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-green-100 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-green-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Sudah Dibayar</p>
                                            <p id="sudah-dibayar" class="text-lg font-bold text-gray-900"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-4 border border-red-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-red-100 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Sisa Bayar</p>
                                            <p id="sisa-bayar" class="text-lg font-bold text-red-600"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Pembayaran -->
                        <div id="payment-form" class="hidden space-y-6">
                            <!-- Jumlah dan Tanggal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tanggal Pembayaran -->
                                <div class="space-y-2">
                                    <label for="tanggal" class="block text-sm font-semibold text-gray-700">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 @error('tanggal') border-red-500 @enderror"
                                        required>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jumlah Pembayaran -->
                                <div class="space-y-2">
                                    <label for="jumlah" class="block text-sm font-semibold text-gray-700">
                                        Jumlah Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm font-medium">Rp</span>
                                        </div>
                                        <input type="text" id="jumlah" name="jumlah"
                                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-right text-lg font-semibold @error('jumlah') border-red-500 @enderror"
                                            placeholder="0" required>
                                    </div>
                                    @error('jumlah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="space-y-2">
                                <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                                    Keterangan
                                </label>
                                <textarea id="keterangan" name="keterangan" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none @error('keterangan') border-red-500 @enderror"
                                    placeholder="Keterangan pembayaran (opsional)..."></textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($metodePembayaran as $metode)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="metode_pembayaran" value="{{ $metode->kode }}"
                                                class="sr-only payment-method-radio">
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 payment-method-card">
                                                <div class="flex flex-col items-center text-center">
                                                    <div
                                                        class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mb-2">
                                                        <i
                                                            class="ti {{ $metode->icon_display }} text-orange-600 text-lg"></i>
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-gray-900">{{ $metode->nama }}</span>
                                                    <span class="text-xs text-gray-500">{{ $metode->kode }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('metode_pembayaran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kas/Bank Selection -->
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Kas/Bank <span class="text-red-500">*</span>
                                </label>

                                <!-- Message when no payment method selected -->
                                <div id="kasBankMessage" class="text-center py-8 text-gray-500">
                                    <i class="ti ti-arrow-up text-2xl mb-2"></i>
                                    <p class="text-sm">Pilih metode pembayaran terlebih dahulu untuk melihat pilihan
                                        kas/bank</p>
                                </div>

                                <div class="grid gap-4 hidden" id="kasBankContainer">
                                    @foreach ($kasBank as $kas)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="kas_bank_id" value="{{ $kas->id }}"
                                                data-saldo="{{ $kas->saldo_terkini }}" data-jenis="{{ $kas->jenis }}"
                                                data-image="{{ $kas->image_url ?? '' }}" class="sr-only kas-bank-radio">
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-xl hover:border-orange-400 hover:bg-gradient-to-br hover:from-orange-50 hover:to-red-50 transition-all duration-300 kas-bank-card hidden flex items-center justify-between shadow-sm hover:shadow-md">
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
                                                                    <i
                                                                        class="ti ti-building-bank text-purple-600 text-xl"></i>
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
                                @error('kas_bank_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('pembayaran-pembelian.index') }}"
                                    class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    Batal
                                </a>
                                <button type="submit" id="submit-btn"
                                    class="px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="submit-text">Simpan Pembayaran</span>
                                    <span id="submit-loading" class="hidden">
                                        <i class="ti ti-loader animate-spin mr-2"></i>
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pencarian Transaksi -->
    <div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-auto">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b bg-gradient-to-r from-orange-50 to-red-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="ti ti-search text-orange-600 mr-2"></i>
                            Pilih Transaksi Pembelian
                        </h3>
                        <button onclick="closeSearchModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ti ti-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Search Input -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput"
                                placeholder="Cari berdasarkan no faktur, supplier, atau total..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <!-- Transaction List -->
                    <div id="transactionList" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Transactions will be loaded here -->
                    </div>

                    <!-- Loading State -->
                    <div id="loadingState" class="hidden text-center py-8">
                        <i class="ti ti-loader animate-spin text-2xl text-orange-500 mb-2"></i>
                        <p class="text-gray-500">Memuat data transaksi...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden text-center py-8">
                        <i class="ti ti-inbox text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Tidak ada transaksi yang ditemukan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .payment-method-card.selected {
            border-color: #f97316;
            background-color: #fff7ed;
        }

        .kas-bank-card.selected {
            border-color: #f97316;
            background-color: #fff7ed;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global variables
        let searchTimeout = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            setupNumberFormatting();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search transaction button
            document.getElementById('search_transaction_btn').addEventListener('click', openSearchModal);

            // Search input
            document.getElementById('searchInput').addEventListener('input', handleSearch);

            // Payment method selection
            document.querySelectorAll('.payment-method-radio').forEach(radio => {
                radio.addEventListener('change', handlePaymentMethodChange);
            });

            // Kas/Bank selection
            document.querySelectorAll('.kas-bank-radio').forEach(radio => {
                radio.addEventListener('change', handleKasBankChange);
            });

            // Form submission
            document.querySelector('form').addEventListener('submit', handleFormSubmit);
        }

        // Setup number formatting
        function setupNumberFormatting() {
            const jumlahInput = document.getElementById('jumlah');

            jumlahInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/\D/g, '');
                const formattedValue = new Intl.NumberFormat('id-ID').format(value);
                e.target.value = formattedValue;
            });
        }

        // Modal functions
        function openSearchModal() {
            document.getElementById('searchModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadTransactions();
        }

        function closeSearchModal() {
            document.getElementById('searchModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('searchInput').value = '';
        }

        // Load transactions
        function loadTransactions(search = '') {
            showLoading();

            fetch(`{{ route('pembayaran-pembelian.get-transactions') }}?search=${search}`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        displayTransactions(data.transactions);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('Terjadi kesalahan saat memuat data');
                });
        }

        // Display transactions
        function displayTransactions(transactions) {
            const container = document.getElementById('transactionList');

            if (transactions.length === 0) {
                showEmpty();
                return;
            }

            hideEmpty();

            container.innerHTML = transactions.map(transaction => `
                <div class="transaction-item p-4 border border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 cursor-pointer transition-all duration-200"
                     data-id="${transaction.id}" data-encrypted-id="${transaction.encrypted_id}">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="font-semibold text-gray-900">${transaction.no_faktur}</h4>
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(transaction.status_pembayaran)}">
                                    ${getStatusText(transaction.status_pembayaran)}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <div class="flex items-center space-x-4">
                                    <span><i class="ti ti-building-store mr-1"></i>${transaction.supplier.nama}</span>
                                    <span><i class="ti ti-calendar mr-1"></i>${transaction.tanggal}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">Rp ${formatNumber(transaction.total)}</div>
                            <div class="text-sm text-gray-500">
                                Sisa: Rp ${formatNumber(transaction.sisa_pembayaran)}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

            // Add click listeners
            document.querySelectorAll('.transaction-item').forEach(item => {
                item.addEventListener('click', () => selectTransaction(item.dataset));
            });
        }

        // Select transaction
        function selectTransaction(data) {
            // Update display
            document.getElementById('pembelian_display').value = `Faktur: ${data.id}`;
            document.getElementById('pembelian_id').value = data.encryptedId;

            // Load transaction details
            loadTransactionDetails(data.encryptedId);

            // Close modal
            closeSearchModal();

            // Show payment form
            document.getElementById('payment-form').classList.remove('hidden');
        }

        // Load transaction details
        function loadTransactionDetails(encryptedId) {
            fetch(`{{ route('pembayaran-pembelian.get-transaction-details') }}?id=${encryptedId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayTransactionDetails(data.transaction);
                    }
                })
                .catch(error => {
                    console.error('Error loading transaction details:', error);
                });
        }

        // Display transaction details
        function displayTransactionDetails(transaction) {
            document.getElementById('total-transaksi').textContent = `Rp ${formatNumber(transaction.total)}`;
            document.getElementById('sudah-dibayar').textContent = `Rp ${formatNumber(transaction.total_dibayar)}`;
            document.getElementById('sisa-bayar').textContent = `Rp ${formatNumber(transaction.sisa_pembayaran)}`;

            // Set default payment amount
            document.getElementById('jumlah').value = formatNumber(transaction.sisa_pembayaran);

            // Show transaction details
            document.getElementById('transaction-details').classList.remove('hidden');
        }

        // Handle search
        function handleSearch(e) {
            const search = e.target.value;

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadTransactions(search);
            }, 300);
        }

        // Utility functions
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function getStatusClass(status) {
            const classes = {
                'lunas': 'bg-green-100 text-green-800',
                'dp': 'bg-blue-100 text-blue-800',
                'belum_bayar': 'bg-red-100 text-red-800'
            };
            return classes[status] || classes['belum_bayar'];
        }

        function getStatusText(status) {
            const texts = {
                'lunas': 'Lunas',
                'dp': 'DP',
                'belum_bayar': 'Belum Bayar'
            };
            return texts[status] || 'Belum Bayar';
        }

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('transactionList').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('transactionList').classList.remove('hidden');
        }

        function showEmpty() {
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('transactionList').classList.add('hidden');
        }

        function hideEmpty() {
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('transactionList').classList.remove('hidden');
        }

        // Handle payment method change
        function handlePaymentMethodChange(e) {
            updatePaymentMethodCards();
            filterKasBankByPaymentMethod();
        }

        // Update payment method cards
        function updatePaymentMethodCards() {
            document.querySelectorAll('.payment-method-card').forEach((card, index) => {
                const radio = document.querySelectorAll('.payment-method-radio')[index];
                if (radio.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
        }

        // Filter kas/bank by payment method
        function filterKasBankByPaymentMethod() {
            const selectedMethod = document.querySelector('.payment-method-radio:checked');
            const kasBankContainer = document.getElementById('kasBankContainer');
            const kasBankMessage = document.getElementById('kasBankMessage');
            const kasBankCards = document.querySelectorAll('.kas-bank-card');

            if (!selectedMethod) {
                kasBankContainer.classList.add('hidden');
                kasBankMessage.classList.remove('hidden');
                return;
            }

            kasBankMessage.classList.add('hidden');
            kasBankContainer.classList.remove('hidden');

            const methodCode = selectedMethod.value.toLowerCase();
            const isTransfer = methodCode.includes('transfer') || methodCode.includes('bank');
            const isCash = methodCode.includes('cash') || methodCode.includes('tunai');

            kasBankCards.forEach((card, index) => {
                const radio = document.querySelectorAll('.kas-bank-radio')[index];
                const jenis = radio.dataset.jenis;

                if (isTransfer && jenis === 'BANK') {
                    card.classList.remove('hidden');
                } else if (isCash && jenis === 'KAS') {
                    card.classList.remove('hidden');
                } else if (!isTransfer && !isCash) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        // Handle kas/bank change
        function handleKasBankChange(e) {
            updateKasBankCards();
        }

        // Update kas/bank cards
        function updateKasBankCards() {
            document.querySelectorAll('.kas-bank-card').forEach((card, index) => {
                const radio = document.querySelectorAll('.kas-bank-radio')[index];
                if (radio.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
        }

        // Handle form submission
        function handleFormSubmit(e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            showLoadingState();

            const formData = new FormData(e.target);

            fetch(e.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoadingState();
                    if (data.success) {
                        showSuccess(data.message);
                        setTimeout(() => {
                            window.location.href = '{{ route('pembayaran-pembelian.index') }}';
                        }, 1500);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    hideLoadingState();
                    showError('Terjadi kesalahan saat menyimpan pembayaran');
                });
        }

        // Validate form
        function validateForm() {
            const pembelianId = document.getElementById('pembelian_id').value;
            const jumlah = document.getElementById('jumlah').value.replace(/\D/g, '');
            const metodePembayaran = document.querySelector('.payment-method-radio:checked');
            const kasBankId = document.querySelector('.kas-bank-radio:checked');

            if (!pembelianId) {
                showError('Pilih transaksi pembelian terlebih dahulu');
                return false;
            }

            if (!jumlah || parseInt(jumlah) <= 0) {
                showError('Jumlah pembayaran harus lebih dari 0');
                return false;
            }

            if (!metodePembayaran) {
                showError('Pilih metode pembayaran terlebih dahulu');
                return false;
            }

            if (!kasBankId) {
                showError('Pilih kas/bank terlebih dahulu');
                return false;
            }

            return true;
        }

        function showLoadingState() {
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-text').classList.add('hidden');
            document.getElementById('submit-loading').classList.remove('hidden');
        }

        function hideLoadingState() {
            document.getElementById('submit-btn').disabled = false;
            document.getElementById('submit-text').classList.remove('hidden');
            document.getElementById('submit-loading').classList.add('hidden');
        }

        function showSuccess(message) {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }

        function showError(message) {
            Swal.fire({
                title: 'Error!',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    </script>
@endpush
