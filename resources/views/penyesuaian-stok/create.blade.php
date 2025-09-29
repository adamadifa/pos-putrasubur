@extends('layouts.pos')

@section('title', 'Tambah Penyesuaian Stok')
@section('page-title', 'Tambah Penyesuaian Stok')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('penyesuaian-stok.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m0 0v12m0-12V3.75M7.5 6h-.75m.75 0h.75m6 0h.75m-.75 0h-.75M7.5 18v-12m0 12h-.75m.75 0h.75m6 0h.75m-.75 0h-.75M7.5 18v-12m0 12h-.75m.75 0h.75m6 0h.75m-.75 0h-.75M7.5 18v-12m0 12h-.75m.75 0h.75m6 0h.75m-.75 0h-.75" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Tambah Penyesuaian Stok</h1>
                                <p class="text-gray-500 mt-1">Atur penyesuaian stok produk untuk memperbaiki selisih
                                    inventori</p>
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

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-circle text-lg text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Terdapat kesalahan dalam form:
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

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                <form action="{{ route('penyesuaian-stok.store') }}" method="POST" id="penyesuaianForm">
                    @csrf

                    <div class="space-y-8">
                        <!-- Informasi Dasar -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="ti ti-info-circle text-blue-600 text-lg"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tanggal Penyesuaian -->
                                <div class="space-y-2">
                                    <label for="tanggal_penyesuaian" class="block text-sm font-semibold text-gray-700">
                                        Tanggal Penyesuaian <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ti ti-calendar text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                        </div>
                                        <input type="text" name="tanggal_penyesuaian" id="tanggal_penyesuaian"
                                            value="{{ old('tanggal_penyesuaian', date('Y-m-d')) }}"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('tanggal_penyesuaian') border-red-500 @enderror flatpickr-input"
                                            placeholder="Pilih tanggal penyesuaian...">
                                    </div>
                                    @error('tanggal_penyesuaian')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Produk -->
                                <div class="space-y-2">
                                    <label for="produk_id" class="block text-sm font-semibold text-gray-700">
                                        Produk <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ti ti-package text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                        </div>
                                        <select name="produk_id" id="produk_id"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('produk_id') border-red-500 @enderror"
                                            onchange="updateStokSistem()">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($produks as $produk)
                                                <option value="{{ $produk->id }}"
                                                    data-stok="{{ number_format($produk->stok, 2, '.', '') }}"
                                                    data-satuan="{{ $produk->satuan->nama }}"
                                                    {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                                    {{ $produk->nama_produk }} ({{ $produk->satuan->nama }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('produk_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Perhitungan Stok -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i class="ti ti-calculator text-green-600 text-lg"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Perhitungan Stok</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Stok Saat Ini -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Stok Saat Ini
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ti ti-database text-gray-400 text-lg"></i>
                                        </div>
                                        <input type="number" id="stok_saat_ini"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"
                                            readonly>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                        Stok sistem saat ini
                                    </p>
                                </div>

                                <!-- Jumlah Penyesuaian -->
                                <div class="space-y-2">
                                    <label for="jumlah_penyesuaian" class="block text-sm font-semibold text-gray-700">
                                        Jumlah Penyesuaian <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i
                                                class="ti ti-adjustments text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                        </div>
                                        <input type="text" name="jumlah_penyesuaian" id="jumlah_penyesuaian"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('jumlah_penyesuaian') border-red-500 @enderror"
                                            placeholder="Masukkan jumlah penyesuaian (+/-), contoh: 1.500,50"
                                            value="{{ old('jumlah_penyesuaian') }}"
                                            oninput="formatNumberInput(this); calculateStokSesudah()">
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                        Gunakan + untuk menambah, - untuk mengurangi. Contoh: 1.500,50 atau -250,75
                                    </p>
                                    @error('jumlah_penyesuaian')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Stok Setelah Penyesuaian -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        Stok Setelah Penyesuaian
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ti ti-check-circle text-gray-400 text-lg"></i>
                                        </div>
                                        <input type="text" id="stok_sesudah"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"
                                            readonly>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                        Hasil perhitungan otomatis
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i class="ti ti-note text-purple-600 text-lg"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Keterangan</h2>
                            </div>

                            <div class="space-y-2">
                                <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                                    Keterangan Penyesuaian
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                        <i
                                            class="ti ti-note text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                    </div>
                                    <textarea name="keterangan" id="keterangan" rows="4"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white resize-none @error('keterangan') border-red-500 @enderror"
                                        placeholder="Masukkan keterangan penyesuaian stok (minimal 10 karakter)">{{ old('keterangan') }}</textarea>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                    Keterangan tambahan untuk penyesuaian stok (minimal 10 karakter)
                                </p>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('penyesuaian-stok.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <i class="ti ti-check text-lg mr-2"></i>
                            Simpan Penyesuaian Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for date input
            flatpickr("#tanggal_penyesuaian", {
                dateFormat: "Y-m-d",
                locale: "id",
                placeholder: "Pilih tanggal penyesuaian...",
                allowInput: true,
                clickOpens: true,
                theme: "light",
                defaultDate: "{{ old('tanggal_penyesuaian', date('Y-m-d')) }}"
            });

            function updateStokSistem() {
                const produkSelect = document.getElementById('produk_id');
                const stokSaatIniInput = document.getElementById('stok_saat_ini');
                const selectedOption = produkSelect.options[produkSelect.selectedIndex];

                if (selectedOption && selectedOption.value) {
                    const stok = parseFloat(selectedOption.dataset.stok) || 0;
                    // Use simple format for current stock display
                    stokSaatIniInput.value = stok.toFixed(2);
                    calculateStokSesudah();
                } else {
                    stokSaatIniInput.value = '';
                    document.getElementById('stok_sesudah').value = '';
                }
            }

            function formatNumberInput(input) {
                // Get cursor position
                let cursorPos = input.selectionStart;
                let oldValue = input.value;

                // Remove all non-numeric characters except +, -, dots, and commas
                let cleanValue = oldValue.replace(/[^\d\+\-\.\,]/g, '');

                // Handle multiple minus signs - keep only the first one
                let minusCount = (cleanValue.match(/-/g) || []).length;
                if (minusCount > 1) {
                    cleanValue = '-' + cleanValue.replace(/-/g, '');
                }

                // Handle multiple plus signs - remove all plus signs
                cleanValue = cleanValue.replace(/\+/g, '');

                // Determine if negative
                let isNegative = cleanValue.startsWith('-');
                if (isNegative) {
                    cleanValue = cleanValue.substring(1);
                }

                // Remove any remaining non-numeric characters except dots and commas
                cleanValue = cleanValue.replace(/[^\d\.\,]/g, '');

                // Indonesian format: dots as thousand separators, comma as decimal separator
                // Smart detection: if there's a comma, treat everything after as decimal
                let commaIndex = cleanValue.lastIndexOf(',');
                let hasDecimal = false;
                let integerPart = '';
                let decimalPart = '';

                if (commaIndex !== -1) {
                    // Has comma - treat as decimal separator
                    hasDecimal = true;
                    integerPart = cleanValue.substring(0, commaIndex).replace(/\./g,
                        ''); // Remove dots from integer part
                    decimalPart = cleanValue.substring(commaIndex + 1);

                    // Limit decimal places to 2
                    if (decimalPart.length > 2) {
                        decimalPart = decimalPart.substring(0, 2);
                    }
                } else {
                    // No comma - check if last dot might be decimal
                    let parts = cleanValue.split('.');
                    if (parts.length > 1) {
                        let lastPart = parts[parts.length - 1];
                        // If last part has 1-2 digits, treat as decimal
                        if (lastPart.length <= 2 && lastPart.length > 0) {
                            hasDecimal = true;
                            integerPart = parts.slice(0, -1).join('');
                            decimalPart = lastPart;
                        } else {
                            // If last part has more than 2 digits, treat as thousand separator
                            integerPart = cleanValue.replace(/\./g, '');
                        }
                    } else {
                        integerPart = cleanValue.replace(/\./g, '');
                    }
                }

                // Format with Indonesian format
                if (cleanValue !== '' && cleanValue !== '.' && cleanValue !== ',') {

                    if (hasDecimal) {
                        // Format integer part with thousand separators, keep decimal with comma
                        if (integerPart !== '') {
                            let formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            input.value = (isNegative ? '-' : '') + formatted + ',' + decimalPart;
                        } else {
                            input.value = (isNegative ? '-' : '') + ',' + decimalPart;
                        }
                    } else {
                        // No decimal, format as integer with thousand separators
                        if (integerPart !== '') {
                            let formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            input.value = (isNegative ? '-' : '') + formatted;
                        } else {
                            input.value = isNegative ? '-' : '';
                        }
                    }
                } else {
                    input.value = isNegative ? '-' : '';
                }

                // Adjust cursor position
                let newLength = input.value.length;
                let oldLength = oldValue.length;
                let lengthDiff = newLength - oldLength;
                input.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);
            }

            function calculateStokSesudah() {
                try {
                    // Parse stok saat ini - simple format (no thousand separators)
                    const stokSaatIniRaw = document.getElementById('stok_saat_ini').value;
                    const stokSaatIni = parseFloat(stokSaatIniRaw) || 0;

                    // Parse jumlah penyesuaian - convert from Indonesian format to standard format
                    const jumlahPenyesuaianRaw = document.getElementById('jumlah_penyesuaian').value;
                    const jumlahPenyesuaian = parseIndonesianNumber(jumlahPenyesuaianRaw);

                    const stokSesudah = stokSaatIni + jumlahPenyesuaian;

                    const stokSesudahInput = document.getElementById('stok_sesudah');
                    if (!stokSesudahInput) {
                        console.error('Element stok_sesudah not found!');
                        return;
                    }

                    // Format with decimal places
                    const formattedResult = formatNumberWithDecimals(stokSesudah);
                    stokSesudahInput.value = formattedResult;

                    // Add color coding
                    if (stokSesudah < 0) {
                        stokSesudahInput.className = stokSesudahInput.className.replace(
                            /bg-(gray|green|red|yellow)-100/, 'bg-red-100');
                    } else if (jumlahPenyesuaian > 0) {
                        stokSesudahInput.className = stokSesudahInput.className.replace(
                            /bg-(gray|green|red|yellow)-100/, 'bg-green-100');
                    } else if (jumlahPenyesuaian < 0) {
                        stokSesudahInput.className = stokSesudahInput.className.replace(
                            /bg-(gray|green|red|yellow)-100/, 'bg-yellow-100');
                    } else {
                        stokSesudahInput.className = stokSesudahInput.className.replace(
                            /bg-(gray|green|red|yellow)-100/, 'bg-gray-100');
                    }

                    // Debug log untuk membantu troubleshooting
                    console.log('=== DEBUG PERHITUNGAN ===');
                    console.log('Stok Saat Ini Raw:', stokSaatIniRaw);
                    console.log('Stok Saat Ini Parsed:', stokSaatIni);
                    console.log('Jumlah Penyesuaian Raw:', jumlahPenyesuaianRaw);
                    console.log('Jumlah Penyesuaian Parsed:', jumlahPenyesuaian);
                    console.log('Hasil (stokSesudah):', stokSesudah);
                    console.log('Formatted Result:', formattedResult);
                    console.log('Element Found:', !!stokSesudahInput);
                    console.log('========================');

                } catch (error) {
                    console.error('Error in calculateStokSesudah:', error);
                }
            }

            function parseIndonesianNumber(value) {
                if (!value || value === '') return 0;

                // Remove all non-numeric characters except minus, dots, and commas
                let clean = value.replace(/[^\d\-\.\,]/g, '');

                // Handle multiple minus signs - keep only the first one
                let minusCount = (clean.match(/-/g) || []).length;
                if (minusCount > 1) {
                    clean = '-' + clean.replace(/-/g, '');
                }

                // Handle negative numbers
                let isNegative = clean.startsWith('-');
                if (isNegative) {
                    clean = clean.substring(1);
                }

                // Convert Indonesian format to standard format
                // Indonesian: 1.000,50 (thousand separator: dot, decimal separator: comma)
                // Standard: 1000.50 (decimal separator: dot)

                // Split by comma to separate integer and decimal parts
                let parts = clean.split(',');

                if (parts.length === 2) {
                    // Has decimal part
                    let integerPart = parts[0].replace(/\./g, ''); // Remove thousand separators
                    let decimalPart = parts[1];
                    clean = integerPart + '.' + decimalPart;
                } else if (parts.length === 1) {
                    // No decimal part, just remove thousand separators
                    clean = clean.replace(/\./g, '');
                } else {
                    // Multiple commas, invalid
                    return 0;
                }

                let result = parseFloat(clean) || 0;
                return isNegative ? -result : result;
            }

            function formatNumberWithDecimals(number) {
                // Format number with thousand separators and decimal places
                if (isNaN(number)) return '0';

                // Convert to string and split integer and decimal parts
                let parts = number.toString().split('.');
                let integerPart = parts[0];
                let decimalPart = parts.length > 1 ? parts[1] : '';

                // Add thousand separators to integer part
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Limit decimal places to 2 and pad if necessary
                if (decimalPart.length > 2) {
                    decimalPart = decimalPart.substring(0, 2);
                } else if (decimalPart.length === 1) {
                    decimalPart = decimalPart + '0';
                }

                // Return formatted number
                if (decimalPart) {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart;
                }
            }

            // Handle form submission to remove formatting
            document.getElementById('penyesuaianForm').addEventListener('submit', function(e) {
                const jumlahInput = document.getElementById('jumlah_penyesuaian');
                const originalValue = jumlahInput.value;

                // Convert Indonesian format to standard format for submission
                const cleanValue = parseIndonesianNumber(originalValue).toString();
                jumlahInput.value = cleanValue;

                // Re-apply formatting after a short delay (in case form doesn't submit)
                setTimeout(() => {
                    if (jumlahInput.value === cleanValue) {
                        jumlahInput.value = originalValue;
                    }
                }, 100);
            });

            // Toast notification functions
            function showToast(message, type = 'error') {
                const toastContainer = document.getElementById('toast-container');
                const toastId = 'toast-' + Date.now();

                const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                const icon = type === 'error' ? 'ti-alert-circle' : 'ti-check-circle';

                const toast = document.createElement('div');
                toast.id = toastId;
                toast.className =
                    `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
                toast.innerHTML = `
                    <i class="ti ${icon} text-lg"></i>
                    <span class="flex-1">${message}</span>
                    <button onclick="hideToast('${toastId}')" class="text-white hover:text-gray-200 transition-colors">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                `;

                toastContainer.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideToast(toastId);
                }, 5000);
            }

            function hideToast(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }

            // Form validation functions
            function validateForm() {
                let isValid = true;

                // Clear previous error styles
                clearAllErrors();

                // Validate tanggal_penyesuaian
                const tanggalInput = document.getElementById('tanggal_penyesuaian');
                if (!tanggalInput.value.trim()) {
                    showFieldError(tanggalInput, 'Tanggal penyesuaian harus diisi');
                    isValid = false;
                }

                // Validate produk_id
                const produkSelect = document.getElementById('produk_id');
                if (!produkSelect.value) {
                    showFieldError(produkSelect, 'Produk harus dipilih');
                    isValid = false;
                }

                // Validate jumlah_penyesuaian
                const jumlahInput = document.getElementById('jumlah_penyesuaian');
                const jumlahValue = jumlahInput.value.trim();
                if (!jumlahValue) {
                    showFieldError(jumlahInput, 'Jumlah penyesuaian harus diisi');
                    isValid = false;
                } else {
                    const parsedValue = parseIndonesianNumber(jumlahValue);
                    if (isNaN(parsedValue) || parsedValue === 0) {
                        showFieldError(jumlahInput,
                            'Jumlah penyesuaian harus berupa angka yang valid dan tidak boleh nol');
                        isValid = false;
                    }
                }

                // Validate keterangan
                const keteranganInput = document.getElementById('keterangan');
                if (!keteranganInput.value.trim()) {
                    showFieldError(keteranganInput, 'Keterangan harus diisi');
                    isValid = false;
                } else if (keteranganInput.value.trim().length < 10) {
                    showFieldError(keteranganInput, 'Keterangan minimal 10 karakter');
                    isValid = false;
                }

                // Show error toast if validation fails
                if (!isValid) {
                    showToast('Form tidak valid. Silakan periksa kembali data yang diisi.', 'error');
                }

                return isValid;
            }

            function showFieldError(field, message) {
                // Add error styling to field
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                // Find the parent div that contains the field
                let container = field.closest('.space-y-2');
                if (!container) {
                    container = field.parentNode.parentNode;
                }

                // Remove existing error message
                const existingError = container.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }

                // Add new error message
                const errorElement = document.createElement('p');
                errorElement.className = 'field-error mt-1 text-sm text-red-600 flex items-center';
                errorElement.innerHTML = `<i class="ti ti-alert-circle text-red-500 mr-1"></i>${message}`;
                container.appendChild(errorElement);
            }

            function clearAllErrors() {
                // Clear all error styles and messages
                const fields = document.querySelectorAll('input, select, textarea');
                fields.forEach(field => {
                    // Reset field styling
                    field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    field.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
                });

                // Remove all error messages
                const errorElements = document.querySelectorAll('.field-error');
                errorElements.forEach(error => error.remove());
            }

            // Simple real-time validation - clear errors when user starts typing
            document.getElementById('tanggal_penyesuaian').addEventListener('input', function() {
                clearFieldError(this);
            });

            document.getElementById('produk_id').addEventListener('change', function() {
                clearFieldError(this);
            });

            document.getElementById('jumlah_penyesuaian').addEventListener('input', function() {
                clearFieldError(this);
            });

            document.getElementById('keterangan').addEventListener('input', function() {
                clearFieldError(this);
            });

            function clearFieldError(field) {
                // Reset field styling
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                // Find container and remove error message
                let container = field.closest('.space-y-2');
                if (!container) {
                    container = field.parentNode.parentNode;
                }

                const errorElement = container.querySelector('.field-error');
                if (errorElement) {
                    errorElement.remove();
                }
            }

            // Form submission with validation
            document.getElementById('penyesuaianForm').addEventListener('submit', function(e) {
                // Run validation first
                if (!validateForm()) {
                    e.preventDefault(); // Prevent submission if validation fails
                    return false;
                }

                // If validation passes, show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>Menyimpan...';

                // Form will submit naturally
            });

            // Show success toast if redirected from successful submission
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            // Make functions global
            window.updateStokSistem = updateStokSistem;
            window.calculateStokSesudah = calculateStokSesudah;
            window.formatNumberInput = formatNumberInput;
            window.hideToast = hideToast;
        });
    </script>
@endsection
