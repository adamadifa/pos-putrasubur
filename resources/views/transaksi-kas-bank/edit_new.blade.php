@extends('layouts.pos')

@section('title', 'Edit Transaksi Kas & Bank')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('transaksi-kas-bank.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Edit Transaksi Kas & Bank
                                </h1>
                                <p class="text-gray-500 mt-1">Edit data transaksi kas dan bank</p>
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
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Edit Transaksi Kas & Bank</h2>
                    </div>
                </div>

                <form action="{{ route('transaksi-kas-bank.update', $transaksi->id) }}" method="POST" class="p-8"
                    id="editTransaksiForm">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- No Bukti (Read Only) -->
                        <div class="space-y-2">
                            <label for="no_bukti" class="block text-sm font-semibold text-gray-700">
                                No Bukti
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="ti ti-receipt text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                </div>
                                <input type="text" value="{{ $transaksi->no_bukti }}" readonly
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Nomor bukti tidak dapat diubah
                            </p>
                        </div>

                        <!-- Tanggal dan Kas/Bank -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Tanggal -->
                            <div class="space-y-2">
                                <label for="tanggal" class="block text-sm font-semibold text-gray-700">
                                    Tanggal <span class="text-red-500">*</span>
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
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ old('tanggal', $transaksi->tanggal) }}"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('tanggal') border-red-500 @enderror"
                                        required>
                                </div>
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kas/Bank -->
                            <div class="space-y-2">
                                <label for="kas_bank_id" class="block text-sm font-semibold text-gray-700">
                                    Kas/Bank <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                        </svg>
                                    </div>
                                    <select name="kas_bank_id" id="kas_bank_id"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('kas_bank_id') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Kas/Bank</option>
                                        @foreach ($kasBankList as $kasBank)
                                            <option value="{{ $kasBank->id }}"
                                                {{ old('kas_bank_id', $transaksi->kas_bank_id) == $kasBank->id ? 'selected' : '' }}>
                                                {{ $kasBank->nama }} - No. Rek: {{ $kasBank->no_rekening ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kas_bank_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Jenis Transaksi -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Jenis Transaksi <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer transaction-type-option">
                                    <input type="radio" name="jenis_transaksi" id="jenis_transaksi_masuk"
                                        value="D"
                                        {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'D' ? 'checked' : '' }}
                                        class="sr-only transaction-type-radio">
                                    <div
                                        class="p-6 border-2 border-gray-200 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200 transaction-type-card">
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-green-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </div>
                                            <span class="text-lg font-semibold text-gray-900">Masuk</span>
                                            <span class="text-sm text-gray-500">Transaksi masuk ke kas/bank</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer transaction-type-option">
                                    <input type="radio" name="jenis_transaksi" id="jenis_transaksi_keluar"
                                        value="K"
                                        {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'K' ? 'checked' : '' }}
                                        class="sr-only transaction-type-radio">
                                    <div
                                        class="p-6 border-2 border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 transition-all duration-200 transaction-type-card">
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-red-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25" />
                                                </svg>
                                            </div>
                                            <span class="text-lg font-semibold text-gray-900">Keluar</span>
                                            <span class="text-sm text-gray-500">Transaksi keluar dari kas/bank</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('jenis_transaksi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="space-y-2">
                            <label for="jumlah" class="block text-sm font-semibold text-gray-700">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span
                                        class="text-gray-500 font-medium group-hover:text-green-500 transition-colors">Rp</span>
                                </div>
                                <input type="text" id="jumlah" name="jumlah"
                                    value="{{ old('jumlah', number_format($transaksi->jumlah, 0, ',', '.')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('jumlah') border-red-500 @enderror text-right text-lg font-semibold"
                                    placeholder="0" required>
                                <input type="hidden" name="jumlah_raw" id="jumlah_raw"
                                    value="{{ old('jumlah_raw', $transaksi->jumlah) }}">
                            </div>
                            @error('jumlah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
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
                                    placeholder="Masukkan keterangan transaksi (opsional)">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                </svg>
                                Catatan tambahan untuk transaksi ini
                            </p>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden input for kategori_transaksi -->
                        <input type="hidden" name="kategori_transaksi" value="MN">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                        <a href="{{ route('transaksi-kas-bank.index') }}"
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
                                class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-base rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                                <svg id="submitIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                <span id="submitBtnText">Update Transaksi</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tips Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="ti ti-info-circle text-blue-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Tips Edit Transaksi</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Pastikan data yang diubah sudah benar sebelum menyimpan</li>
                                <li>Perubahan jumlah akan mempengaruhi saldo kas/bank</li>
                                <li>Nomor bukti tidak dapat diubah setelah transaksi dibuat</li>
                                <li>Gunakan keterangan untuk mencatat alasan perubahan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Document ready, initializing transaction form...');

            const jumlahInput = document.getElementById('jumlah');

            // Number formatting for amount
            let isFormatting = false;
            let lastValidValue = '0';

            jumlahInput.addEventListener('input', function(e) {
                if (isFormatting) return;

                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;

                // Get the raw numeric value
                let rawValue = e.target.value.replace(/[^\d,]/g, '');

                if (!rawValue) {
                    document.getElementById('jumlah_raw').value = '';
                    return;
                }

                // Handle decimal separator
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

                // Format the value
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

                // Store last valid value
                let numericValue = parseFormattedDecimal(newValue);
                if (numericValue > 0) {
                    lastValidValue = newValue;
                }

                // Store raw value
                document.getElementById('jumlah_raw').value = numericValue;

                if (newValue !== oldValue) {
                    isFormatting = true;
                    e.target.value = newValue;
                    isFormatting = false;
                }
            });

            // Helper functions for decimal formatting
            function formatDecimalInput(value) {
                if (!value && value !== 0) return '';

                let strValue = value.toString();
                if (strValue.includes(',')) {
                    let parts = strValue.split(',');
                    let integerPart = parts[0].replace(/\./g, '');
                    let decimalPart = parts[1] || '';

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

                // Handle Indonesian number format (1.000.000,50)
                let cleanValue = value.toString()
                    .replace(/\./g, '') // Remove thousand separators
                    .replace(',', '.'); // Replace decimal separator

                let numValue = parseFloat(cleanValue);
                return isNaN(numValue) ? 0 : numValue;
            }

            // Handle focus and blur for amount
            jumlahInput.addEventListener('focus', function() {
                const rawValue = document.getElementById('jumlah_raw').value;
                if (rawValue && rawValue !== '0') {
                    this.value = rawValue;
                    this.select();
                }
            });

            jumlahInput.addEventListener('blur', function() {
                let inputValue = this.value.trim();

                if (!inputValue) {
                    this.value = lastValidValue || '0';
                    return;
                }

                let value = parseFormattedDecimal(inputValue);
                if (value <= 0 || isNaN(value)) {
                    this.value = lastValidValue || '0';
                } else {
                    let formattedValue = formatDecimalInput(value);
                    this.value = formattedValue;
                    lastValidValue = formattedValue;
                }
            });

            // Frontend Validation Rules and Messages
            const validationRules = {
                tanggal: {
                    required: true
                },
                kas_bank_id: {
                    required: true
                },
                jumlah: {
                    required: true
                }
            };

            const validationMessages = {
                tanggal: {
                    required: 'Tanggal wajib diisi.'
                },
                kas_bank_id: {
                    required: 'Kas/Bank wajib dipilih.'
                },
                jumlah: {
                    required: 'Jumlah wajib diisi.'
                }
            };

            // Real-time validation for form fields
            const fieldsToValidate = ['tanggal', 'kas_bank_id', 'jumlah'];

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

            // Clear error states for transaction type cards
            $('.transaction-type-radio').on('change', function() {
                $('.transaction-type-card').removeClass('border-red-500');
                $('.transaction-type-card').siblings('.error-message').remove();
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

                if (!isValid) {
                    // Add error styling
                    field.removeClass('border-gray-300 border-green-500').addClass('border-red-500');

                    // Add error message
                    const errorHtml = `
                        <p class="mt-1 text-sm text-red-600 error-message">
                            ${errorMessage}
                        </p>
                    `;
                    fieldContainer.append(errorHtml);
                } else {
                    // Add success styling (green border only)
                    field.removeClass('border-gray-300 border-red-500').addClass('border-green-500');
                }
            }

            // Form submission validation
            $('form').on('submit', function(e) {
                let hasErrors = false;
                const errorMessages = [];

                // Reset all error states
                $('.border-red-500').removeClass('border-red-500').addClass('border-gray-300');
                $('.error-message').remove();

                // Ensure jumlah_raw has the correct value before submission
                const jumlahDisplay = $('#jumlah').val();
                const jumlahRaw = parseFormattedDecimal(jumlahDisplay);
                $('#jumlah_raw').val(jumlahRaw);

                // Check for empty required fields
                fieldsToValidate.forEach(function(fieldName) {
                    const field = $(`#${fieldName}`);
                    const rules = validationRules[fieldName];
                    if (rules.required && !field.val()) {
                        hasErrors = true;
                        validateField(fieldName, field.val());
                        errorMessages.push(
                            `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} wajib diisi`
                        );
                    }
                });

                // Validate transaction type (radio button)
                const transactionType = $('input[name="jenis_transaksi"]:checked').val();
                if (!transactionType) {
                    hasErrors = true;
                    $('.transaction-type-card').addClass('border-red-500');
                    $('.transaction-type-card').first().after(
                        '<p class="mt-1 text-sm text-red-600 error-message">Pilih jenis transaksi</p>'
                    );
                    errorMessages.push('Jenis transaksi wajib dipilih');
                }

                // Validate amount
                if (jumlahRaw <= 0) {
                    hasErrors = true;
                    $('#jumlah').removeClass('border-gray-300').addClass('border-red-500');
                    $('#jumlah').after(
                        '<p class="mt-1 text-sm text-red-600 error-message">Jumlah harus lebih dari 0</p>'
                    );
                    errorMessages.push('Jumlah harus lebih dari 0');
                }

                // Check for any visible error messages
                if ($('.error-message').length > 0) {
                    hasErrors = true;
                    $('.error-message').each(function() {
                        errorMessages.push($(this).text());
                    });
                }

                if (hasErrors) {
                    e.preventDefault();

                    // Scroll to first error
                    const firstError = $('.error-message').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }

                    // Show notification with all errors
                    showNotification(errorMessages.join(', '), 'error');
                } else {
                    // Disable submit button and show loading state
                    const submitButton = $('#submitBtn');
                    const originalText = submitButton.html();

                    submitButton.prop('disabled', true);
                    submitButton.removeClass(
                        'hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-[1.02]');
                    submitButton.addClass('opacity-75 cursor-not-allowed');

                    // Change button content to loading state
                    submitButton.html(`
                        <i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>
                        Menyimpan...
                    `);

                    // Show notification
                    showNotification('Sedang menyimpan data transaksi...', 'info');

                    // Re-enable button if form submission fails (fallback)
                    setTimeout(function() {
                        if (submitButton.prop('disabled')) {
                            submitButton.prop('disabled', false);
                            submitButton.removeClass('opacity-75 cursor-not-allowed');
                            submitButton.addClass(
                                'hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-[1.02]'
                            );
                            submitButton.html(originalText);
                        }
                    }, 10000); // 10 seconds fallback
                }
            });

            // Initialize transaction type cards
            function initializeTransactionTypeCards() {
                $('.transaction-type-card').removeClass('border-green-500 bg-green-50 border-red-500 bg-red-50')
                    .addClass('border-gray-200');
                $('.transaction-type-radio:checked').each(function() {
                    $(this).closest('.transaction-type-option').find('.transaction-type-card')
                        .removeClass('border-gray-200').addClass(this.value === 'D' ?
                            'border-green-500 bg-green-50' : 'border-red-500 bg-red-50');
                });
            }

            // Initialize on page load
            initializeTransactionTypeCards();

            // Transaction type card selection
            $('.transaction-type-radio').on('change', function() {
                $('.transaction-type-card').removeClass(
                    'border-green-500 bg-green-50 border-red-500 bg-red-50').addClass('border-gray-200');
                if (this.checked) {
                    $(this).closest('.transaction-type-option').find('.transaction-type-card')
                        .removeClass('border-gray-200').addClass(this.value === 'D' ?
                            'border-green-500 bg-green-50' : 'border-red-500 bg-red-50');
                }
            });


            // Clear transaction type error states
            $('.transaction-type-radio').on('change', function() {
                $('.transaction-type-card').removeClass('border-red-500');
                $('.transaction-type-card').siblings('.error-message').remove();
            });

            // Show notification function
            function showNotification(message, type = 'info') {
                let icon;

                switch (type) {
                    case 'success':
                        icon = `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>`;
                        break;
                    case 'error':
                        icon = `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>`;
                        break;
                    default:
                        icon = `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>`;
                }

                const notification = $(`
                    <div class="toast-notification ${type}">
                        ${icon}
                        <div class="toast-message">${message}</div>
                        <button class="toast-close" type="button">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                `);

                $('body').append(notification);

                // Show notification with animation
                setTimeout(() => {
                    notification.addClass('show');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.removeClass('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);

                // Click to close
                notification.find('.toast-close').on('click', function() {
                    notification.removeClass('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                });
            }
        });
    </script>

    <style>
        /* Error state styling */
        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444;
        }

        .error-message {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        /* Disabled button styles */
        .opacity-50 {
            opacity: 0.5;
        }

        .cursor-not-allowed {
            cursor: not-allowed;
        }

        /* Transaction type card styling */
        .transaction-type-card {
            transition: all 0.2s ease-in-out;
        }

        .transaction-type-card:hover {
            transform: translateY(-2px);
        }

        .transaction-type-radio:checked+.transaction-type-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .transaction-type-radio:checked+.transaction-type-card.border-green-500 {
            border-color: #10b981;
            background-color: #ecfdf5;
        }

        .transaction-type-radio:checked+.transaction-type-card.border-red-500 {
            border-color: #ef4444;
            background-color: #fef2f2;
        }

        .transaction-type-card.border-red-500 {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
        }

        /* Error state styling */
        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444;
        }

        .error-message {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Toast notification styles */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toast-notification.show {
            transform: translateX(0);
        }

        .toast-notification.error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            color: #dc2626;
        }

        .toast-notification.success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border: 1px solid #86efac;
            color: #16a34a;
        }

        .toast-notification.info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 1px solid #93c5fd;
            color: #2563eb;
        }

        .toast-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
        }

        .toast-message {
            flex: 1;
            font-weight: 500;
            line-height: 1.4;
        }

        .toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .toast-close:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
