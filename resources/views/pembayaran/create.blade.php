@extends('layouts.pos')

@section('title', 'Buat Pembayaran')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pembayaran.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Buat Pembayaran Baru</h1>
                                <p class="text-gray-500 mt-1">Input pembayaran untuk transaksi yang belum lunas</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Fields bertanda <span class="text-red-500 font-medium">*</span> wajib diisi</span>
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
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Pembayaran</h2>
                    </div>
                </div>

                <form action="{{ route('pembayaran.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="space-y-8">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Transaksi Selection -->
                                <div class="space-y-2">
                                    <label for="penjualan_id" class="block text-sm font-semibold text-gray-700">
                                        Pilih Transaksi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="penjualan_display" readonly
                                            class="w-full pl-11 pr-20 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('penjualan_id') border-red-500 @enderror"
                                            placeholder="Klik tombol cari untuk memilih transaksi...">
                                        <input type="hidden" id="penjualan_id" name="penjualan_id" value="">
                                        <button type="button" id="search_transaction_btn"
                                            class="absolute inset-y-0 right-0 px-4 flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-r-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 group-hover:shadow-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('penjualan_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Date -->
                                <div class="space-y-2">
                                    <label for="tanggal" class="block text-sm font-semibold text-gray-700">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                        </div>
                                        <input type="datetime-local" id="tanggal" name="tanggal"
                                            value="{{ now()->format('Y-m-d\TH:i') }}"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('tanggal') border-red-500 @enderror">
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Amount -->
                                <div class="space-y-2">
                                    <label for="jumlah" class="block text-sm font-semibold text-gray-700">
                                        Jumlah Bayar <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span
                                                class="text-gray-500 font-medium group-hover:text-green-500 transition-colors">Rp</span>
                                        </div>
                                        <input type="text" id="jumlah" name="jumlah"
                                            class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('jumlah') border-red-500 @enderror text-right"
                                            placeholder="0">
                                        <input type="hidden" id="jumlah_raw" name="jumlah_raw" value="">
                                    </div>
                                    @error('jumlah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="space-y-2">
                                    <label for="metode_pembayaran" class="block text-sm font-semibold text-gray-700">
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                            </svg>
                                        </div>
                                        <select id="metode_pembayaran" name="metode_pembayaran"
                                            class="w-full pl-11 pr-10 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('metode_pembayaran') border-red-500 @enderror appearance-none">
                                            <option value="">Pilih metode pembayaran...</option>
                                            <option value="tunai">💵 Tunai</option>
                                            <option value="transfer">🏦 Transfer Bank</option>
                                            <option value="qris">📱 QRIS</option>
                                            <option value="kartu">💳 Kartu Debit/Credit</option>
                                            <option value="ewallet">📱 E-Wallet</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('metode_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Transaction Details (will be shown when transaction is selected) -->
                            <div id="transaction-details"
                                class="hidden bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800">Detail Transaksi</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 text-blue-600">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 text-green-600">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 text-red-600">
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

                            <!-- Notes -->
                            <div class="space-y-2">
                                <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                                    Keterangan
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5 text-gray-400 group-hover:text-purple-500 transition-colors">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                        </svg>
                                    </div>
                                    <textarea id="keterangan" name="keterangan" rows="3"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('keterangan') border-red-500 @enderror"
                                        placeholder="Catatan tambahan (opsional)"></textarea>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                    </svg>
                                    Catatan tambahan untuk pembayaran ini
                                </p>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                        <a href="{{ route('pembayaran.index') }}"
                            class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 group-hover:text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>

                        <div class="flex items-center space-x-4">
                            <button type="reset"
                                class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 mr-3 group-hover:text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Reset Form
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold text-base rounded-xl shadow-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                Simpan Pembayaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transaction Selection Modal -->
    <div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Pilih Transaksi</h3>
                                <p class="text-sm text-gray-600">Pilih transaksi yang belum lunas</p>
                            </div>
                        </div>
                        <button type="button" id="closeModal"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                            <input type="text" id="searchTransaction"
                                placeholder="Cari berdasarkan no faktur atau nama pelanggan..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Transaction List -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach ($penjualan as $p)
                            @php
                                $sudahDibayar = $p->pembayaranPenjualan->sum('jumlah_bayar');
                                $sisaBayar = $p->total - $sudahDibayar;
                            @endphp
                            <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all duration-200"
                                data-id="{{ $p->id }}" data-total="{{ $p->total }}"
                                data-sudah-dibayar="{{ $sudahDibayar }}" data-sisa="{{ $sisaBayar }}"
                                data-faktur="{{ $p->no_faktur }}"
                                data-pelanggan="{{ $p->pelanggan->nama ?? 'Pelanggan Umum' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 text-blue-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $p->no_faktur }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    {{ $p->pelanggan->nama ?? 'Pelanggan Umum' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-600">Total: <span
                                                class="font-semibold text-gray-900">Rp
                                                {{ number_format($p->total, 0, ',', '.') }}</span></div>
                                        <div class="text-sm text-gray-600">Sisa: <span
                                                class="font-semibold text-red-600">Rp
                                                {{ number_format($sisaBayar, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="hidden text-center py-8">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400 mx-auto mb-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <p class="text-gray-500">Tidak ada transaksi yang ditemukan</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelSelection"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Frontend Validation Rules and Messages
            const validationRules = {
                penjualan_id: {
                    required: true
                },
                metode_pembayaran: {
                    required: true
                },
                jumlah: {
                    required: true,
                    numeric: true,
                    min: 0.01
                },
                tanggal: {
                    required: true
                },
                keterangan: {
                    required: false,
                    maxLength: 255
                }
            };

            const validationMessages = {
                penjualan_id: {
                    required: 'Transaksi wajib dipilih.'
                },
                metode_pembayaran: {
                    required: 'Metode pembayaran wajib dipilih.'
                },
                jumlah: {
                    required: 'Jumlah bayar wajib diisi.',
                    numeric: 'Jumlah bayar harus berupa angka.',
                    min: 'Jumlah bayar minimal 0.01.'
                },
                tanggal: {
                    required: 'Tanggal pembayaran wajib diisi.'
                },
                keterangan: {
                    maxLength: 'Keterangan maksimal 255 karakter.'
                }
            };

            // Real-time validation for form fields
            const fieldsToValidate = ['penjualan_id', 'metode_pembayaran', 'jumlah', 'tanggal', 'keterangan'];

            fieldsToValidate.forEach(function(fieldName) {
                const field = $(`#${fieldName}`);
                let validationTimeout;

                field.on('input change blur', function() {
                    const value = $(this).val();

                    // Clear previous timeout
                    clearTimeout(validationTimeout);

                    // Don't validate empty fields on input (only on blur)
                    if (!value && $(this)[0].type !== 'blur') {
                        return;
                    }

                    // Set timeout to avoid too many validations
                    validationTimeout = setTimeout(function() {
                        validateField(fieldName, value);
                    }, 300);
                });

                // Immediate validation on blur for required fields
                field.on('blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);
                    validateField(fieldName, value);
                });
            });

            // Frontend Validate field function
            function validateField(fieldName, value) {
                const field = $(`#${fieldName}`);
                const fieldContainer = field.closest('.space-y-2');
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Remove existing error and success states
                field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
                fieldContainer.find('.error-message').remove();

                // Skip validation for empty optional fields
                if (!value && !rules.required) {
                    return;
                }

                let isValid = true;
                let errorMessage = '';

                // Required validation
                if (rules.required && (!value || value.toString().trim() === '')) {
                    isValid = false;
                    errorMessage = messages.required;
                }
                // Max length validation
                else if (rules.maxLength && value && value.length > rules.maxLength) {
                    isValid = false;
                    errorMessage = messages.maxLength;
                }
                // Numeric validation
                else if (rules.numeric && value) {
                    // For jumlah field, get the raw value from hidden input
                    let numValue;
                    if (fieldName === 'jumlah') {
                        const rawValue = document.getElementById('jumlah_raw').value;
                        numValue = parseFloat(rawValue) || 0;
                    } else {
                        numValue = parseFloat(value) || 0;
                    }

                    if (isNaN(numValue)) {
                        isValid = false;
                        errorMessage = messages.numeric;
                    } else if (numValue < rules.min) {
                        isValid = false;
                        errorMessage = messages.min;
                    }
                }

                if (!isValid) {
                    // Add error styling
                    field.removeClass('border-gray-300 border-green-500').addClass('border-red-500');

                    // Add error message
                    const errorHtml = `
                <p class="mt-2 text-sm text-red-600 flex items-center error-message">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    ${errorMessage}
                </p>
            `;
                    fieldContainer.append(errorHtml);
                } else {
                    // Add success styling (green border only)
                    field.removeClass('border-gray-300 border-red-500').addClass('border-green-500');
                }
            }

            // Modal functionality
            const modal = document.getElementById('transactionModal');
            const searchBtn = document.getElementById('search_transaction_btn');
            const closeModal = document.getElementById('closeModal');
            const cancelSelection = document.getElementById('cancelSelection');
            const searchInput = document.getElementById('searchTransaction');
            const transactionItems = document.querySelectorAll('.transaction-item');
            const noResults = document.getElementById('noResults');
            const penjualanDisplay = document.getElementById('penjualan_display');
            const penjualanIdInput = document.getElementById('penjualan_id');
            const transactionDetails = document.getElementById('transaction-details');
            const totalTransaksi = document.getElementById('total-transaksi');
            const sudahDibayar = document.getElementById('sudah-dibayar');
            const sisaBayar = document.getElementById('sisa-bayar');
            const jumlahInput = document.getElementById('jumlah');

            // Open modal
            searchBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
                searchInput.focus();
            });

            // Close modal functions
            function closeModalFunction() {
                modal.classList.add('hidden');
                searchInput.value = '';
                // Reset search results
                transactionItems.forEach(item => item.style.display = 'block');
                noResults.classList.add('hidden');
            }

            closeModal.addEventListener('click', closeModalFunction);
            cancelSelection.addEventListener('click', closeModalFunction);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalFunction();
                }
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;

                transactionItems.forEach(item => {
                    const faktur = item.dataset.faktur.toLowerCase();
                    const pelanggan = item.dataset.pelanggan.toLowerCase();

                    if (faktur.includes(searchTerm) || pelanggan.includes(searchTerm)) {
                        item.style.display = 'block';
                        hasResults = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (hasResults) {
                    noResults.classList.add('hidden');
                } else {
                    noResults.classList.remove('hidden');
                }
            });

            // Transaction selection
            transactionItems.forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const total = parseFloat(this.dataset.total);
                    const sudahDibayarValue = parseFloat(this.dataset.sudahDibayar);
                    const sisa = parseFloat(this.dataset.sisa);
                    const faktur = this.dataset.faktur;
                    const pelanggan = this.dataset.pelanggan;

                    // Update form fields
                    penjualanIdInput.value = id;
                    penjualanDisplay.value = `${faktur} - ${pelanggan}`;

                    // Update transaction details
                    totalTransaksi.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    sudahDibayar.textContent = 'Rp ' + sudahDibayarValue.toLocaleString('id-ID');
                    sisaBayar.textContent = 'Rp ' + sisa.toLocaleString('id-ID');

                    // Set max value for payment amount
                    jumlahInput.setAttribute('data-max', sisa);
                    jumlahInput.placeholder = 'Maksimal: Rp ' + sisa.toLocaleString('id-ID');

                    transactionDetails.classList.remove('hidden');

                    // Close modal
                    closeModalFunction();
                });
            });

            // Format number input with automatic formatting (using the same logic as penjualan/create)
            let isFormatting = false;
            let lastValidValue = '0';

            jumlahInput.addEventListener('input', function(e) {
                if (isFormatting) return;

                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;

                // Get the raw numeric value by removing all formatting
                let rawValue = e.target.value.replace(/[^\d,]/g, ''); // Keep digits and comma only

                // If input is empty, don't format yet
                if (!rawValue) {
                    document.getElementById('jumlah_raw').value = '';
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

                // Check maximum value
                const max = parseFloat(this.getAttribute('data-max')) || Infinity;
                if (numericValue > max) {
                    numericValue = max;
                    newValue = formatDecimalInput(max);
                }

                // Store raw value in hidden input for form submission
                document.getElementById('jumlah_raw').value = numericValue;

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
                        let addedDots = (newValue.match(/\./g) || []).length - (oldValue.match(/\./g) || [])
                            .length;
                        if (addedDots > 0) {
                            // Find where the dot was added relative to cursor
                            let textBeforeCursor = oldValue.substring(0, cursorPosition);
                            let digitsBeforeCursor = textBeforeCursor.replace(/[^\d]/g, '').length;

                            // Count how many dots should be before this many digits in the new value
                            let newTextUpToDigits = newValue.replace(/[^\d.]/g, '');
                            let dotsBeforeDigits = 0;
                            let digitCount = 0;

                            for (let i = 0; i < newTextUpToDigits.length && digitCount <
                                digitsBeforeCursor; i++) {
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

            // Helper functions for decimal formatting
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

            function parseFormattedDecimal(value) {
                if (!value) return 0;

                // Remove thousand separators and convert comma to dot
                let cleanValue = value.toString()
                    .replace(/\./g, '') // Remove thousand separators
                    .replace(',', '.'); // Convert comma to dot for decimal

                let numValue = parseFloat(cleanValue);
                return isNaN(numValue) ? 0 : numValue;
            }

            // Handle focus to show raw number for editing
            jumlahInput.addEventListener('focus', function() {
                const rawValue = document.getElementById('jumlah_raw').value;
                if (rawValue && rawValue !== '0') {
                    this.value = rawValue;
                    // Select all text for easy editing
                    this.select();
                }
            });

            // Handle blur to format the number
            jumlahInput.addEventListener('blur', function() {
                let inputValue = this.value.trim();

                // If empty, set to last valid value or 0
                if (!inputValue) {
                    this.value = lastValidValue || '0';
                    return;
                }

                let value = parseFormattedDecimal(inputValue);
                if (value <= 0 || isNaN(value)) {
                    // Use last valid value instead of defaulting to 0
                    this.value = lastValidValue || '0';
                } else {
                    // Format the value properly
                    let formattedValue = formatDecimalInput(value);
                    this.value = formattedValue;
                    lastValidValue = formattedValue;
                }
            });

            // Form submission validation
            $('form').on('submit', function(e) {
                let hasErrors = false;

                // Check for any visible error messages
                if ($('.error-message').length > 0) {
                    hasErrors = true;
                }

                // Check for empty required fields
                fieldsToValidate.forEach(function(fieldName) {
                    const field = $(`#${fieldName}`);
                    let fieldValue = field.val();

                    // For jumlah field, check the raw value
                    if (fieldName === 'jumlah') {
                        fieldValue = document.getElementById('jumlah_raw').value;
                    }

                    if (!fieldValue && fieldName !== 'keterangan') {
                        hasErrors = true;
                        validateField(fieldName, fieldValue);
                    }
                });

                if (hasErrors) {
                    e.preventDefault();

                    // Scroll to first error
                    const firstError = $('.error-message').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }

                    // Show notification
                    showNotification('Harap perbaiki kesalahan pada form sebelum melanjutkan.', 'error');
                } else {
                    // Disable submit button and show loading state
                    const submitButton = $('button[type="submit"]');
                    const originalText = submitButton.html();

                    submitButton.prop('disabled', true);
                    submitButton.removeClass(
                        'hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-[1.02]');
                    submitButton.addClass('opacity-75 cursor-not-allowed');

                    // Change button content to loading state
                    submitButton.html(`
                        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    `);

                    // Show notification
                    showNotification('Sedang menyimpan pembayaran...', 'info');

                    // Re-enable button if form submission fails (fallback)
                    setTimeout(function() {
                        if (submitButton.prop('disabled')) {
                            submitButton.prop('disabled', false);
                            submitButton.removeClass('opacity-75 cursor-not-allowed');
                            submitButton.addClass(
                                'hover:from-green-700 hover:to-emerald-700 hover:shadow-xl hover:scale-[1.02]'
                            );
                            submitButton.html(originalText);
                        }
                    }, 10000); // 10 seconds fallback
                }
            });

            // Show notification function
            function showNotification(message, type = 'info') {
                let bgColor, icon;

                switch (type) {
                    case 'error':
                        bgColor = 'bg-red-500';
                        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>`;
                        break;
                    case 'success':
                        bgColor = 'bg-green-500';
                        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>`;
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        icon = `<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>`;
                        break;
                    default:
                        bgColor = 'bg-blue-500';
                        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.20a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>`;
                }

                const notification = $(`
            <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 notification transform transition-all duration-300 translate-x-full">
                <div class="flex items-center">
                    ${icon}
                    ${message}
                </div>
            </div>
        `);

                $('body').append(notification);

                // Animate in
                setTimeout(function() {
                    notification.removeClass('translate-x-full');
                }, 100);

                // Animate out and remove
                setTimeout(function() {
                    notification.addClass('translate-x-full');
                    setTimeout(function() {
                        notification.remove();
                    }, 300);
                }, 4000);
            }
        });
    </script>
@endsection
