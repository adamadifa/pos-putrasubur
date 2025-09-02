@extends('layouts.pos')

@section('title', 'Tambah Saldo Awal Bulanan')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('saldo-awal-bulanan.index') }}"
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
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Tambah Saldo Awal Bulanan</h1>
                                <p class="text-gray-500 mt-1">Set saldo awal kas atau bank untuk periode tertentu</p>
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
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm">
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
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button"
                                    class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                    onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                    <span class="sr-only">Dismiss</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-red-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
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
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button"
                                    class="inline-flex bg-red-50 rounded-lg p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                                    onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                    <span class="sr-only">Dismiss</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="ti ti-currency-dollar text-blue-600 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Saldo Awal Bulanan</h2>
                    </div>
                </div>

                <form action="{{ route('saldo-awal-bulanan.store') }}" method="POST" class="p-8"
                    id="createSaldoAwalForm">
                    @csrf

                    <div class="space-y-8">
                        <!-- Kas/Bank -->
                        <div class="space-y-2">
                            <label for="kas_bank_id" class="block text-sm font-semibold text-gray-700">
                                Kas/Bank <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="ti ti-building-bank text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                </div>
                                <select name="kas_bank_id" id="kas_bank_id"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('kas_bank_id') border-red-500 @enderror"
                                    required>
                                    <option value="">Pilih Kas/Bank</option>
                                    @foreach ($kasBankList as $kasBank)
                                        <option value="{{ $kasBank->id }}"
                                            {{ old('kas_bank_id') == $kasBank->id ? 'selected' : '' }}>
                                            {{ $kasBank->nama }} ({{ $kasBank->jenis }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Pilih kas atau bank yang akan diset saldo awalnya
                            </p>
                            @error('kas_bank_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Periode Bulan dan Tahun -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Periode Bulan -->
                            <div class="space-y-2">
                                <label for="periode_bulan" class="block text-sm font-semibold text-gray-700">
                                    Bulan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="ti ti-calendar text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                    </div>
                                    <select name="periode_bulan" id="periode_bulan"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('periode_bulan') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Bulan</option>
                                        @foreach ($bulanList as $key => $bulan)
                                            <option value="{{ $key }}"
                                                {{ old('periode_bulan') == $key ? 'selected' : '' }}>
                                                {{ $bulan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                    Pilih bulan untuk periode saldo awal
                                </p>
                                @error('periode_bulan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Periode Tahun -->
                            <div class="space-y-2">
                                <label for="periode_tahun" class="block text-sm font-semibold text-gray-700">
                                    Tahun <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="ti ti-calendar-event text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                    </div>
                                    <select name="periode_tahun" id="periode_tahun"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('periode_tahun') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($tahunList as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ old('periode_tahun', now()->year) == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                    Pilih tahun untuk periode saldo awal
                                </p>
                                @error('periode_tahun')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Saldo Awal -->
                        <div class="space-y-2">
                            <label for="saldo_awal" class="block text-sm font-semibold text-gray-700">
                                Saldo Awal <span class="text-red-500">*</span>
                            </label>

                            <!-- Input Group dengan Button Get Saldo -->
                            <div class="flex space-x-2">
                                <div class="relative group flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="ti ti-currency-dollar text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                    </div>
                                    <input type="text" name="saldo_awal_display" id="saldo_awal"
                                        value="{{ old('saldo_awal') ? number_format(old('saldo_awal'), 0, ',', '.') : '' }}"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-right @error('saldo_awal') border-red-500 @enderror"
                                        placeholder="0" required>
                                    <input type="hidden" name="saldo_awal_raw" id="saldo_awal_raw"
                                        value="{{ old('saldo_awal') }}">
                                </div>

                                <!-- Button Get Saldo -->
                                <button type="button" id="getSaldoBtn"
                                    class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold text-sm rounded-lg shadow-lg hover:from-green-700 hover:to-emerald-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    disabled>
                                    <i class="ti ti-calculator text-lg mr-2"></i>
                                    Get Saldo
                                </button>
                            </div>

                            <!-- Info Text -->
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                <span id="saldoInfoText">Masukkan saldo awal untuk periode yang dipilih</span>
                            </p>

                            <!-- Loading State -->
                            <div id="saldoLoading" class="hidden">
                                <div class="flex items-center space-x-2 text-sm text-blue-600">
                                    <i class="ti ti-loader-2 animate-spin"></i>
                                    <span>Menghitung saldo akhir bulan sebelumnya...</span>
                                </div>
                            </div>

                            <!-- Result Info -->
                            <div id="saldoResultInfo" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-start">
                                        <i class="ti ti-info-circle text-blue-500 mt-0.5 mr-2"></i>
                                        <div class="text-sm">
                                            <div class="font-medium text-blue-800 mb-1" id="saldoResultTitle"></div>
                                            <div class="text-blue-700 space-y-1" id="saldoResultDetails"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('saldo_awal')
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
                                    <i
                                        class="ti ti-note text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                </div>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('keterangan') border-red-500 @enderror"
                                    placeholder="Keterangan saldo awal (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Tambahkan keterangan untuk saldo awal ini (opsional)
                            </p>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Card -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Preview Saldo Awal</h4>
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-4">
                                    <i class="ti ti-currency-dollar text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-base font-medium text-gray-900" id="preview-kas-bank">
                                        {{ old('kas_bank_id') ? $kasBankList->firstWhere('id', old('kas_bank_id'))?->nama : 'Pilih Kas/Bank' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span id="preview-periode">
                                            {{ old('periode_bulan') && old('periode_tahun') ? $bulanList[old('periode_bulan')] . ' ' . old('periode_tahun') : 'Pilih Periode' }}
                                        </span>
                                        @if (old('saldo_awal'))
                                            • <span id="preview-saldo">Rp
                                                {{ number_format(old('saldo_awal'), 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                        <a href="{{ route('saldo-awal-bulanan.index') }}"
                            class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                            <i class="ti ti-x text-lg mr-3 group-hover:text-gray-600"></i>
                            Batal
                        </a>

                        <div class="flex items-center space-x-4">
                            <button type="reset"
                                class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                                <i class="ti ti-refresh text-lg mr-3 group-hover:text-gray-600"></i>
                                Reset Form
                            </button>
                            <button type="submit" id="submitBtn"
                                class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-base rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                                <i class="ti ti-device-floppy text-lg mr-3"></i>
                                Simpan Saldo Awal
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
                        <h3 class="text-sm font-medium text-blue-800">Tips Mengatur Saldo Awal</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Fitur "Get Saldo":</strong> Klik tombol "Get Saldo" untuk otomatis menghitung
                                    saldo akhir bulan sebelumnya sebagai saldo awal bulan yang dipilih</li>
                                <li>Saldo awal digunakan sebagai referensi untuk perhitungan saldo terkini</li>
                                <li>Setiap kas/bank hanya bisa memiliki satu saldo awal per bulan</li>
                                <li>Saldo awal tidak dapat diedit jika sudah ada saldo awal bulan berikutnya</li>
                                <li>Gunakan saldo awal untuk menyesuaikan dengan saldo bank statement</li>
                                <li>Pastikan saldo awal sesuai dengan opname kas/bank di akhir bulan sebelumnya</li>
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
            // CSRF Token untuk AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Frontend Validation Rules and Messages
            const validationRules = {
                kas_bank_id: {
                    required: true
                },
                periode_bulan: {
                    required: true
                },
                periode_tahun: {
                    required: true
                },
                saldo_awal_raw: {
                    required: true,
                    min: 0
                },
                keterangan: {
                    required: false,
                    maxLength: 500
                }
            };

            const validationMessages = {
                kas_bank_id: {
                    required: 'Kas/Bank wajib dipilih.'
                },
                periode_bulan: {
                    required: 'Bulan wajib dipilih.'
                },
                periode_tahun: {
                    required: 'Tahun wajib dipilih.'
                },
                saldo_awal_raw: {
                    required: 'Saldo awal wajib diisi.',
                    min: 'Saldo awal tidak boleh negatif.'
                },
                keterangan: {
                    maxLength: 'Keterangan maksimal 500 karakter.'
                }
            };

            // Real-time validation for form fields
            const fieldsToValidate = ['kas_bank_id', 'periode_bulan', 'periode_tahun', 'saldo_awal_raw',
                'keterangan'
            ];

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
                // Min validation for numbers
                else if (rules.min !== undefined && value && parseFloat(value) < rules.min) {
                    isValid = false;
                    errorMessage = messages.min;
                }
                // Max length validation
                else if (rules.maxLength && value && value.length > rules.maxLength) {
                    isValid = false;
                    errorMessage = messages.maxLength;
                }

                if (!isValid) {
                    // Add error styling
                    field.removeClass('border-gray-300 border-green-500').addClass('border-red-500');

                    // Add error message
                    const errorHtml = `
                        <p class="mt-2 text-sm text-red-600 flex items-center error-message">
                            <i class="ti ti-alert-circle text-red-500 mr-2"></i>
                            ${errorMessage}
                        </p>
                    `;
                    fieldContainer.append(errorHtml);
                } else {
                    // Add success styling (green border only)
                    field.removeClass('border-gray-300 border-red-500').addClass('border-green-500');
                }
            }

            // Live preview functionality
            $('#kas_bank_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const previewKasBank = $('#preview-kas-bank');
                previewKasBank.text(selectedOption.text() || 'Pilih Kas/Bank');
            });

            $('#periode_bulan, #periode_tahun').on('change', function() {
                updatePreviewPeriode();
            });

            function updatePreviewPeriode() {
                const bulan = $('#periode_bulan option:selected').text();
                const tahun = $('#periode_tahun').val();
                const previewPeriode = $('#preview-periode');

                if (bulan && tahun && bulan !== 'Pilih Bulan') {
                    previewPeriode.text(bulan + ' ' + tahun);
                } else {
                    previewPeriode.text('Pilih Periode');
                }
            }

            $('#saldo_awal').on('input', function() {
                const rawValue = parseFormattedNumber(this.value);
                const previewSaldo = $('#preview-saldo');

                if (rawValue > 0) {
                    if ($('#preview-saldo').length === 0) {
                        $('#preview-periode').after(' • <span id="preview-saldo">Rp ' + rawValue
                            .toLocaleString('id-ID') + '</span>');
                    } else {
                        previewSaldo.text('Rp ' + rawValue.toLocaleString('id-ID'));
                    }
                } else {
                    $('#preview-saldo').remove();
                }
            });

            // Form submission validation
            $('#createSaldoAwalForm').on('submit', function(e) {
                let hasErrors = false;

                // Check for any visible error messages
                if ($('.error-message').length > 0) {
                    hasErrors = true;
                }

                // Check for empty required fields
                fieldsToValidate.forEach(function(fieldName) {
                    const field = $(`#${fieldName}`);
                    const rules = validationRules[fieldName];
                    if (rules.required && !field.val()) {
                        hasErrors = true;
                        validateField(fieldName, field.val());
                    }
                });

                // Special validation for saldo_awal_raw
                const saldoAwalRawValue = parseFormattedNumber($('#saldo_awal').val());
                if (!saldoAwalRawValue || saldoAwalRawValue <= 0) {
                    hasErrors = true;
                    validateField('saldo_awal_raw', saldoAwalRawValue);
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

                    // Show notification
                    showNotification('Harap perbaiki kesalahan pada form sebelum melanjutkan.', 'error');
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
                    showNotification('Sedang menyimpan saldo awal...', 'info');

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

            // Show notification function
            function showNotification(message, type = 'info') {
                let bgColor, icon;

                switch (type) {
                    case 'error':
                        bgColor = 'bg-red-500';
                        icon = `<i class="ti ti-alert-circle text-lg mr-2"></i>`;
                        break;
                    case 'success':
                        bgColor = 'bg-green-500';
                        icon = `<i class="ti ti-check text-lg mr-2"></i>`;
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        icon = `<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>`;
                        break;
                    default:
                        bgColor = 'bg-blue-500';
                        icon = `<i class="ti ti-info-circle text-lg mr-2"></i>`;
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

            // Show success toast notification after page load
            @if (session('success'))
                setTimeout(function() {
                    showNotification('{{ session('success') }}', 'success');
                }, 500);
            @endif

            // ===== GET SALDO FUNCTIONALITY =====

            // Elements
            const getSaldoBtn = $('#getSaldoBtn');
            const saldoAwalInput = $('#saldo_awal');
            const saldoAwalRaw = $('#saldo_awal_raw');
            const kasBankSelect = $('#kas_bank_id');
            const periodeBulanSelect = $('#periode_bulan');
            const periodeTahunSelect = $('#periode_tahun');
            const saldoInfoText = $('#saldoInfoText');
            const saldoLoading = $('#saldoLoading');
            const saldoResultInfo = $('#saldoResultInfo');
            const saldoResultTitle = $('#saldoResultTitle');
            const saldoResultDetails = $('#saldoResultDetails');

            // Check if form is ready for Get Saldo
            function checkGetSaldoReady() {
                const kasBankId = kasBankSelect.val();
                const periodeBulan = periodeBulanSelect.val();
                const periodeTahun = periodeTahunSelect.val();

                if (kasBankId && periodeBulan && periodeTahun) {
                    getSaldoBtn.prop('disabled', false);
                    saldoInfoText.text(
                        'Pilih kas/bank dan periode, lalu klik "Get Saldo" untuk menghitung saldo akhir bulan sebelumnya'
                    );
                } else {
                    getSaldoBtn.prop('disabled', true);
                    saldoInfoText.text('Masukkan saldo awal untuk periode yang dipilih');
                }
            }

            // Listen for changes in form fields
            kasBankSelect.on('change', checkGetSaldoReady);
            periodeBulanSelect.on('change', checkGetSaldoReady);
            periodeTahunSelect.on('change', checkGetSaldoReady);

            // Initial check
            checkGetSaldoReady();

            // Get Saldo button click handler
            getSaldoBtn.on('click', function() {
                const kasBankId = kasBankSelect.val();
                const periodeBulan = periodeBulanSelect.val();
                const periodeTahun = periodeTahunSelect.val();

                if (!kasBankId || !periodeBulan || !periodeTahun) {
                    showNotification('Pilih kas/bank dan periode terlebih dahulu', 'error');
                    return;
                }

                // Show loading state
                getSaldoBtn.prop('disabled', true);
                saldoLoading.removeClass('hidden');
                saldoResultInfo.addClass('hidden');

                // Make AJAX request
                $.ajax({
                    url: '{{ route('saldo-awal-bulanan.get-saldo-akhir') }}',
                    method: 'POST',
                    data: {
                        kas_bank_id: kasBankId,
                        periode_bulan: periodeBulan,
                        periode_tahun: periodeTahun
                    },
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;

                            // Update input value with formatted number
                            const formattedValue = formatNumber(data
                                .saldo_akhir_bulan_sebelumnya);
                            saldoAwalInput.val(formattedValue);
                            saldoAwalRaw.val(data.saldo_akhir_bulan_sebelumnya);

                            // Show result info
                            saldoResultTitle.text(
                                `Saldo akhir ${data.bulan_sebelumnya} sebagai saldo awal ${data.periode_dipilih}`
                            );

                            let detailsHtml = `
                                <div>• Saldo awal ${data.bulan_sebelumnya}: Rp ${formatNumber(data.saldo_awal_bulan_sebelumnya)}</div>
                                <div>• Total transaksi ${data.bulan_sebelumnya}: Rp ${formatNumber(data.total_transaksi_bulan_sebelumnya)}</div>
                                <div>• Saldo akhir ${data.bulan_sebelumnya}: <strong>Rp ${formatNumber(data.saldo_akhir_bulan_sebelumnya)}</strong></div>
                            `;

                            if (data.sudah_ada_saldo_awal) {
                                detailsHtml +=
                                    `<div class="text-orange-600 font-medium">⚠️ Sudah ada saldo awal terdaftar: Rp ${formatNumber(data.saldo_awal_terdaftar)}</div>`;
                            }

                            saldoResultDetails.html(detailsHtml);
                            saldoResultInfo.removeClass('hidden');

                            // Update info text
                            saldoInfoText.text(
                                `Saldo akhir ${data.bulan_sebelumnya} telah diambil sebagai saldo awal ${data.periode_dipilih}`
                            );

                            // Show success notification
                            showNotification(
                                `Saldo akhir ${data.bulan_sebelumnya} berhasil dihitung`,
                                'success');

                            // Trigger validation
                            validateField('saldo_awal', data.saldo_akhir_bulan_sebelumnya);
                        } else {
                            showNotification('Gagal menghitung saldo: ' + response.message,
                                'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghitung saldo';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        // Hide loading state
                        saldoLoading.addClass('hidden');
                        getSaldoBtn.prop('disabled', false);
                    }
                });
            });

            // Helper function untuk format number
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Helper function untuk parse formatted number
            function parseFormattedNumber(value) {
                if (!value) return 0;
                // Remove thousand separators and convert comma to dot
                let cleanValue = value.toString()
                    .replace(/\./g, '') // Remove thousand separators
                    .replace(',', '.'); // Convert comma to dot for decimal
                let numValue = parseFloat(cleanValue);
                return isNaN(numValue) ? 0 : numValue;
            }

            // Setup number input formatting untuk saldo awal
            function setupSaldoAwalInput() {
                const saldoAwalInput = $('#saldo_awal');

                saldoAwalInput.on('input', function(e) {
                    const cursorPosition = e.target.selectionStart;
                    const oldValue = e.target.value;
                    const newValue = formatNumberInput(e.target.value);

                    e.target.value = newValue;

                    // Update hidden field with raw value
                    const rawValue = parseFormattedNumber(newValue);
                    saldoAwalRaw.val(rawValue);

                    // Adjust cursor position
                    const diff = newValue.length - oldValue.length;
                    e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
                });

                saldoAwalInput.on('blur', function(e) {
                    if (e.target.value === '' || e.target.value === '0') {
                        e.target.value = '0';
                        saldoAwalRaw.val(0);
                    }
                });

                saldoAwalInput.on('focus', function(e) {
                    if (e.target.value === '0') {
                        e.target.select();
                    }
                });
            }

            // Format number input function
            function formatNumberInput(value) {
                if (!value) return '';

                // Remove all non-digit characters except comma and dot
                let cleanValue = value.toString().replace(/[^\d,.-]/g, '');

                // Handle negative numbers
                let isNegative = false;
                if (cleanValue.startsWith('-')) {
                    isNegative = true;
                    cleanValue = cleanValue.substring(1);
                }

                // Convert to number - prioritize thousand separators over decimal
                let numValue;

                // Check if it's likely a decimal number (has comma at the end or in specific position)
                if (cleanValue.includes(',') && (cleanValue.endsWith(',') || cleanValue.split(',').length === 2)) {
                    // Handle comma as decimal separator (Indonesian format: 1234,56)
                    numValue = parseFloat(cleanValue.replace(',', '.'));
                } else if (cleanValue.includes(',') && cleanValue.includes('.')) {
                    // Handle both comma and dot (e.g., 1.234,56)
                    let parts = cleanValue.split(',');
                    let decimalPart = parts.pop();
                    let integerPart = parts.join('').replace(/\./g, '');
                    numValue = parseFloat(integerPart + '.' + decimalPart);
                } else if (cleanValue.includes(',')) {
                    // Multiple commas - treat as thousand separators
                    numValue = parseFloat(cleanValue.replace(/,/g, ''));
                } else if (cleanValue.includes('.')) {
                    // Single dot - check if it's decimal or thousand separator
                    let parts = cleanValue.split('.');
                    if (parts.length === 2 && parts[1].length <= 2) {
                        // Likely decimal separator
                        numValue = parseFloat(cleanValue);
                    } else {
                        // Likely thousand separator
                        numValue = parseFloat(cleanValue.replace(/\./g, ''));
                    }
                } else {
                    // No separators, just digits
                    numValue = parseFloat(cleanValue);
                }

                if (isNaN(numValue)) return '';

                // Apply negative sign
                if (isNegative) {
                    numValue = -numValue;
                }

                // Format with thousand separators (no decimal places for currency)
                return Math.floor(numValue).toLocaleString('id-ID');
            }

            // Initialize number formatting
            setupSaldoAwalInput();
        });
    </script>
@endsection
