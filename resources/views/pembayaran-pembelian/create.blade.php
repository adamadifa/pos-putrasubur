@extends('layouts.pos')

@section('title', 'Buat Pembayaran Pembelian Baru')

@section('content')
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pembayaran-pembelian.index') }}"
                            class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Buat Pembayaran Pembelian Baru</h1>
                            <p class="text-gray-600">Input pembayaran untuk transaksi pembelian yang belum lunas</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <span>Fields bertanda * wajib diisi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-emerald-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-emerald-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Pembayaran</h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('pembayaran-pembelian.store') }}" class="p-6 space-y-6">
                    @csrf

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Transaksi Selection -->
                            <div class="space-y-2">
                                <label for="pembelian_id" class="block text-sm font-semibold text-gray-700">
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
                                    <input type="text" id="pembelian_display" readonly
                                        class="w-full pl-11 pr-20 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('pembelian_id') border-red-500 @enderror"
                                        placeholder="Klik tombol cari untuk memilih transaksi...">
                                    <input type="hidden" id="pembelian_id" name="pembelian_id" value="">
                                    <button type="button" id="search_transaction_btn"
                                        class="absolute inset-y-0 right-0 px-4 flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-r-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 group-hover:shadow-md">
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
                        </div>

                        <!-- Payment Amount - Full Width -->
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
                                    class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('jumlah') border-red-500 @enderror text-right text-lg font-semibold"
                                    placeholder="0">
                                <input type="hidden" id="jumlah_raw" name="jumlah_raw" value="">
                            </div>
                            @error('jumlah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                            class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 payment-method-card">
                                            <div class="flex flex-col items-center text-center">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                                                    <i class="ti {{ $metode->icon_display }} text-blue-600 text-lg"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $metode->nama }}</span>
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

                        <!-- Transaction Details -->
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600">
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
            <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Pilih Transaksi Pembelian</h3>
                        <button id="closeModal" class="text-white hover:text-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="searchTransaction"
                                placeholder="Cari berdasarkan faktur atau supplier..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach ($pembelian as $p)
                            @php
                                $sudahDibayar = $p->pembayaranPembelian->sum('jumlah_bayar');
                                $sisaBayar = $p->total - $sudahDibayar;
                            @endphp
                            <div class="transaction-item p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                                data-id="{{ $p->id }}" data-faktur="{{ $p->no_faktur }}"
                                data-supplier="{{ $p->supplier->nama }}" data-total="{{ $p->total }}"
                                data-sudah-dibayar="{{ $sudahDibayar }}" data-sisa="{{ $sisaBayar }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $p->no_faktur }}</h4>
                                        <p class="text-sm text-gray-600">{{ $p->supplier->nama }}</p>
                                        <p class="text-xs text-gray-500">{{ $p->tanggal->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($p->total) }}</p>
                                        <p class="text-sm text-gray-600">Sisa: Rp {{ number_format($sisaBayar) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="noResults" class="hidden text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto mb-4 text-gray-300">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <p>Tidak ada transaksi yang ditemukan</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button id="cancelSelection"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .payment-method-card {
            transition: all 0.2s ease-in-out;
        }

        .payment-method-card:hover {
            transform: translateY(-2px);
        }

        .payment-method-radio:checked+.payment-method-card {
            border-color: #3b82f6;
            background-color: #eff6ff;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
        }

        .payment-method-radio:checked+.payment-method-card .text-blue-600 {
            color: #2563eb;
        }
    </style>

    <script>
        // Validation rules and messages
        const validationRules = {
            pembelian_id: {
                required: true
            },
            metode_pembayaran: {
                required: true
            },
            jumlah: {
                required: true,
                numeric: true,
                min: 1000
            },
            tanggal: {
                required: true
            },
            keterangan: {
                maxLength: 255
            }
        };

        const validationMessages = {
            pembelian_id: {
                required: 'Transaksi wajib dipilih.'
            },
            metode_pembayaran: {
                required: 'Metode pembayaran wajib dipilih.'
            },
            jumlah: {
                required: 'Jumlah bayar wajib diisi.',
                numeric: 'Jumlah bayar harus berupa angka.',
                min: 'Jumlah bayar minimal Rp 1.000.'
            },
            tanggal: {
                required: 'Tanggal pembayaran wajib diisi.'
            },
            keterangan: {
                maxLength: 'Keterangan maksimal 255 karakter.'
            }
        };

        // Real-time validation for form fields
        const fieldsToValidate = ['pembelian_id', 'metode_pembayaran', 'jumlah', 'tanggal', 'keterangan'];

        fieldsToValidate.forEach(function(fieldName) {
            let validationTimeout;

            // Special handling for radio buttons
            if (fieldName === 'metode_pembayaran') {
                $('input[name="metode_pembayaran"]').on('change', function() {
                    const value = $('input[name="metode_pembayaran"]:checked').val();
                    clearTimeout(validationTimeout);
                    validationTimeout = setTimeout(function() {
                        validateField(fieldName, value);
                    }, 300);
                });
            } else {
                const field = $(`#${fieldName}`);

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
            }
        });

        // Frontend Validate field function
        function validateField(fieldName, value) {
            let field, fieldContainer;

            // Special handling for radio buttons
            if (fieldName === 'metode_pembayaran') {
                field = $('input[name="metode_pembayaran"]:checked');
                fieldContainer = $('.payment-method-option').first().closest('.space-y-2');
            } else {
                field = $(`#${fieldName}`);
                fieldContainer = field.closest('.space-y-2');
            }

            const rules = validationRules[fieldName];
            const messages = validationMessages[fieldName];

            // Remove existing error and success states
            if (fieldName === 'metode_pembayaran') {
                $('.payment-method-card').removeClass('border-red-500 border-green-500').addClass('border-gray-200');
            } else {
                field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
            }
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
                if (fieldName === 'metode_pembayaran') {
                    $('.payment-method-card').removeClass('border-gray-200 border-green-500').addClass('border-red-500');
                } else {
                    field.removeClass('border-gray-300 border-green-500').addClass('border-red-500');
                }

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
                if (fieldName === 'metode_pembayaran') {
                    $('.payment-method-card').removeClass('border-gray-200 border-red-500').addClass('border-green-500');
                } else {
                    field.removeClass('border-gray-300 border-red-500').addClass('border-green-500');
                }
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
        const pembelianDisplay = document.getElementById('pembelian_display');
        const pembelianIdInput = document.getElementById('pembelian_id');
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

                transactionDetails.classList.remove('hidden');

                // Close modal
                closeModalFunction();

                // Trigger validation
                validateField('pembelian_id', id);
            });
        });

        // Number formatting for jumlah input
        let lastValidValue = '0';
        let isFormatting = false;

        jumlahInput.addEventListener('input', function(e) {
            if (isFormatting) return;

            isFormatting = true;
            let inputValue = this.value.replace(/[^\d]/g, '');

            if (inputValue === '') {
                this.value = '0';
                document.getElementById('jumlah_raw').value = '0';
                lastValidValue = '0';
            } else {
                let numValue = parseInt(inputValue);
                if (isNaN(numValue)) {
                    numValue = 0;
                }

                // Format with thousand separators
                const formattedValue = numValue.toLocaleString('id-ID');
                this.value = formattedValue;
                document.getElementById('jumlah_raw').value = numValue.toString();
                lastValidValue = formattedValue;
            }
            isFormatting = false;
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
                let fieldValue;

                // Special handling for radio buttons
                if (fieldName === 'metode_pembayaran') {
                    fieldValue = $('input[name="metode_pembayaran"]:checked').val();
                } else {
                    const field = $(`#${fieldName}`);
                    fieldValue = field.val();

                    // For jumlah field, check the raw value
                    if (fieldName === 'jumlah') {
                        fieldValue = document.getElementById('jumlah_raw').value;
                    }
                }

                if (!fieldValue && fieldName !== 'keterangan') {
                    hasErrors = true;
                    validateField(fieldName, fieldValue);
                }
            });

            if (hasErrors) {
                e.preventDefault();
                showNotification('Harap perbaiki kesalahan pada form sebelum melanjutkan.', 'error');
            }
        });

        // Initialize payment method selection
        function initializePaymentMethod() {
            const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');

            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected class from all cards
                    document.querySelectorAll('.payment-method-card').forEach(card => {
                        card.classList.remove('selected');
                    });

                    // Add selected class to checked card
                    if (this.checked) {
                        this.closest('.payment-method-option').querySelector('.payment-method-card')
                            .classList.add('selected');
                    }

                    // Trigger validation
                    const value = this.value;
                    validateField('metode_pembayaran', value);
                });
            });
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            // You can implement your own notification system here
            // For now, we'll use a simple alert
            alert(message);
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePaymentMethod();
        });
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
