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
                                Pilih Transaksi <span class="text-red-500">*</span>
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
                                    placeholder="Klik tombol cari untuk memilih transaksi...">
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
                                <h4 class="text-lg font-semibold text-gray-800">Detail Transaksi</h4>
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
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Payment Amount -->
                                <div class="space-y-2">
                                    <label for="jumlah" class="block text-sm font-semibold text-gray-700">
                                        Jumlah Bayar <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span
                                                class="text-gray-500 font-medium group-hover:text-orange-500 transition-colors">Rp</span>
                                        </div>
                                        <input type="text" id="jumlah" name="jumlah"
                                            class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('jumlah') border-red-500 @enderror text-right text-lg font-semibold"
                                            placeholder="0">
                                        <input type="hidden" id="jumlah_raw" name="jumlah_raw" value="">
                                    </div>
                                    @error('jumlah')
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
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors">
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
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach ($metodePembayaran as $metode)
                                        <label class="relative cursor-pointer payment-method-option">
                                            <input type="radio" name="metode_pembayaran"
                                                id="metode_pembayaran_{{ $metode->kode }}" value="{{ $metode->kode }}"
                                                {{ old('metode_pembayaran') == $metode->kode ? 'checked' : '' }}
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

                            <!-- Kas & Bank Selection -->
                            <div id="kas-bank-selection" class="space-y-4 hidden">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Pilih Kas/Bank <span class="text-red-500">*</span>
                                    </label>
                                    <div id="kas-bank-container" class="grid gap-4"
                                        style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                                        @foreach ($kasBank as $kas)
                                            @php
                                                $jenis = $kas->jenis ?: 'KAS';
                                                $image = $kas->image ? asset('storage/' . $kas->image) : null;
                                            @endphp
                                            <label class="relative cursor-pointer kas-bank-option">
                                                <input type="radio" name="kas_bank_id"
                                                    id="kas_bank_{{ $kas->id }}" value="{{ $kas->id }}"
                                                    data-jenis="{{ $jenis }}" data-image="{{ $image }}"
                                                    class="sr-only kas-bank-radio">
                                                <div
                                                    class="kas-bank-card p-4 border-2 border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-all duration-200">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            @if ($kas->image)
                                                                <img src="{{ asset('storage/' . $kas->image) }}"
                                                                    alt="{{ $kas->nama }}"
                                                                    class="w-12 h-12 rounded-lg object-contain border border-gray-200 bg-white">
                                                            @else
                                                                <div
                                                                    class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" class="w-6 h-6 text-white">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $kas->nama }}</p>
                                                            @if ($kas->no_rekening)
                                                                <p class="text-xs text-gray-500">No. Rek:
                                                                    {{ $kas->no_rekening }}</p>
                                                            @endif
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
                                            class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors">
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
                        <a href="{{ route('pembayaran-pembelian.index') }}"
                            class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 group-hover:text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>

                        <div class="flex items-center space-x-4">
                            <button type="reset" id="resetBtn"
                                class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 mr-3 group-hover:text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Reset Form
                            </button>
                            <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold text-base rounded-xl shadow-lg hover:from-orange-700 hover:to-red-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                                <svg id="submitIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                <span id="submitBtnText">Simpan Pembayaran</span>
                            </button>
                        </div>
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
                <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <i class="ti ti-search text-orange-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Pilih Transaksi Pembelian</h3>
                                <p class="text-sm text-gray-600">Pilih transaksi yang belum lunas</p>
                            </div>
                        </div>
                        <button type="button" id="closeModal"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ti ti-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchTransaction"
                                placeholder="Cari berdasarkan no faktur atau nama supplier..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Transaction List -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach ($pembelian as $p)
                            @php
                                $sudahDibayar = $p->pembayaranPembelian->sum('jumlah_bayar');
                                $sisaBayar = $p->total - $sudahDibayar;
                            @endphp
                            <div class="transaction-item border border-gray-200 rounded-lg p-4 hover:border-orange-300 hover:bg-orange-50 cursor-pointer transition-all duration-200"
                                data-id="{{ $p->encrypted_id }}" data-total="{{ $p->total }}"
                                data-sudah-dibayar="{{ $sudahDibayar }}" data-sisa="{{ $sisaBayar }}"
                                data-faktur="{{ $p->no_faktur }}"
                                data-supplier="{{ $p->supplier->nama ?? 'Supplier Umum' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-orange-100 rounded-lg">
                                                <i class="ti ti-file-invoice text-orange-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $p->no_faktur }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    {{ $p->supplier->nama ?? 'Supplier Umum' }}</p>
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
                            <i class="ti ti-search text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500">Tidak ada transaksi yang ditemukan</p>
                        </div>
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
        // Modal functionality
        const modal = document.getElementById('transactionModal');
        const searchBtn = document.getElementById('search_transaction_btn');
        const closeModal = document.getElementById('closeModal');
        const searchInput = document.getElementById('searchTransaction');
        const transactionItems = document.querySelectorAll('.transaction-item');
        const noResults = document.getElementById('noResults');
        const pembelianDisplay = document.getElementById('pembelian_display');
        const pembelianIdInput = document.getElementById('pembelian_id');
        const transactionDetails = document.getElementById('transaction-details');
        const paymentForm = document.getElementById('payment-form');
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

        // Close modal button
        closeModal.addEventListener('click', closeModalFunction);

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
                const supplier = item.dataset.supplier.toLowerCase();

                if (faktur.includes(searchTerm) || supplier.includes(searchTerm)) {
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
                const supplier = this.dataset.supplier;

                // Update form fields
                pembelianIdInput.value = id;
                pembelianDisplay.value = `${faktur} - ${supplier}`;

                // Update transaction details
                totalTransaksi.textContent = 'Rp ' + total.toLocaleString('id-ID');
                sudahDibayar.textContent = 'Rp ' + sudahDibayarValue.toLocaleString('id-ID');
                sisaBayar.textContent = 'Rp ' + sisa.toLocaleString('id-ID');

                // Set max value for payment amount
                jumlahInput.setAttribute('data-max', sisa);
                jumlahInput.placeholder = 'Maksimal: Rp ' + sisa.toLocaleString('id-ID');

                // Set default payment amount to remaining balance
                jumlahInput.value = sisa.toLocaleString('id-ID');
                if (document.getElementById('jumlah_raw')) {
                    document.getElementById('jumlah_raw').value = sisa;
                }

                // Show transaction details and payment form
                transactionDetails.classList.remove('hidden');
                paymentForm.classList.remove('hidden');

                // Close modal
                closeModalFunction();
            });
        });

        // Setup number formatting
        function setupNumberFormatting() {
            const jumlahInput = document.getElementById('jumlah');
            const jumlahRawInput = document.getElementById('jumlah_raw');

            jumlahInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/\D/g, '');
                const formattedValue = new Intl.NumberFormat('id-ID').format(value);
                e.target.value = formattedValue;

                // Update hidden raw value
                if (jumlahRawInput) {
                    jumlahRawInput.value = value;
                }
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            setupNumberFormatting();
            setupPaymentMethodHandlers();
            setupKasBankHandlers();
            setupFormHandlers();
            initializeKasBankGrid();
        });

        // Initialize kas/bank grid layout
        function initializeKasBankGrid() {
            const kasBankContainer = document.getElementById('kas-bank-container');
            const kasBankCards = document.querySelectorAll('.kas-bank-card');

            if (kasBankContainer && kasBankCards.length > 0) {
                const visibleCount = kasBankCards.length;

                if (visibleCount <= 2) {
                    kasBankContainer.style.gridTemplateColumns = `repeat(${visibleCount}, 1fr)`;
                } else {
                    kasBankContainer.style.gridTemplateColumns = 'repeat(auto-fit, minmax(250px, 1fr))';
                }
            }
        }

        // Setup payment method handlers
        function setupPaymentMethodHandlers() {
            document.querySelectorAll('.payment-method-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    updatePaymentMethodCards();
                    filterKasBankByPaymentMethod();
                });
            });
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
            const kasBankSelection = document.getElementById('kas-bank-selection');
            const kasBankContainer = document.getElementById('kas-bank-container');
            const kasBankCards = document.querySelectorAll('.kas-bank-card');

            if (!selectedMethod) {
                kasBankSelection.classList.add('hidden');
                return;
            }

            kasBankSelection.classList.remove('hidden');

            const methodCode = selectedMethod.value.toLowerCase();
            const isTransfer = methodCode.includes('transfer') || methodCode.includes('bank');
            const isCash = methodCode.includes('cash') || methodCode.includes('tunai');

            let visibleCount = 0;

            kasBankCards.forEach((card, index) => {
                const radio = document.querySelectorAll('.kas-bank-radio')[index];
                const jenis = radio.dataset.jenis;

                if (isTransfer && jenis === 'BANK') {
                    card.parentElement.style.display = 'block';
                    visibleCount++;
                } else if (isCash && jenis === 'KAS') {
                    card.parentElement.style.display = 'block';
                    visibleCount++;
                } else if (!isTransfer && !isCash) {
                    card.parentElement.style.display = 'block';
                    visibleCount++;
                } else {
                    card.parentElement.style.display = 'none';
                }
            });

            // Update grid columns based on visible count
            if (visibleCount <= 2) {
                kasBankContainer.style.gridTemplateColumns = `repeat(${visibleCount}, 1fr)`;
            } else {
                kasBankContainer.style.gridTemplateColumns = 'repeat(auto-fit, minmax(200px, 1fr))';
            }
        }

        // Setup kas/bank handlers
        function setupKasBankHandlers() {
            document.querySelectorAll('.kas-bank-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateKasBankCards();
                });
            });
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

        // Setup form handlers
        function setupFormHandlers() {
            const form = document.querySelector('form');
            const resetBtn = document.getElementById('resetBtn');

            form.addEventListener('submit', handleFormSubmit);
            resetBtn.addEventListener('click', handleFormReset);
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

        // Handle form reset
        function handleFormReset() {
            document.getElementById('pembelian_id').value = '';
            document.getElementById('pembelian_display').value = '';
            document.getElementById('jumlah').value = '';
            document.getElementById('keterangan').value = '';
            document.getElementById('transaction-details').classList.add('hidden');
            document.getElementById('payment-form').classList.add('hidden');
            document.getElementById('kas-bank-selection').classList.add('hidden');

            // Reset radio buttons
            document.querySelectorAll('.payment-method-radio').forEach(radio => radio.checked = false);
            document.querySelectorAll('.kas-bank-radio').forEach(radio => radio.checked = false);

            // Reset card styles
            document.querySelectorAll('.payment-method-card').forEach(card => card.classList.remove('selected'));
            document.querySelectorAll('.kas-bank-card').forEach(card => card.classList.remove('selected'));

            // Reset kas/bank display and grid layout
            const kasBankCards = document.querySelectorAll('.kas-bank-card');
            const kasBankContainer = document.getElementById('kas-bank-container');

            kasBankCards.forEach(card => {
                card.parentElement.style.display = 'block';
            });

            if (kasBankContainer) {
                kasBankContainer.style.gridTemplateColumns = 'repeat(auto-fit, minmax(250px, 1fr))';
            }
        }

        // Show loading state
        function showLoadingState() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtnText').textContent = 'Menyimpan...';
            document.getElementById('submitIcon').innerHTML = '<i class="ti ti-loader animate-spin"></i>';
        }

        // Hide loading state
        function hideLoadingState() {
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtnText').textContent = 'Simpan Pembayaran';
            document.getElementById('submitIcon').innerHTML =
                '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />';
        }

        // Show success message
        function showSuccess(message) {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Show error message
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
