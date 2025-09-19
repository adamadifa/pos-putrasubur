@extends('layouts.pos')

@section('title', 'Edit Transaksi Kas & Bank')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Edit Transaksi Kas & Bank</h1>
                                <p class="text-gray-500 mt-1">Edit transaksi kas atau bank yang sudah ada</p>
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
                            <i class="ti ti-credit-card text-blue-600 text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Edit Transaksi</h2>
                    </div>
                </div>

                <form action="{{ route('transaksi-kas-bank.update', $transaksiKasBank->id) }}" method="POST"
                    class="p-8" id="editTransaksiForm">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Tanggal -->
                        <div class="space-y-2">
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="ti ti-calendar text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                </div>
                                <input type="text" id="tanggal"
                                    value="{{ old('tanggal_display', $transaksiKasBank->tanggal->format('d/m/Y')) }}"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('tanggal_hidden') border-red-500 @enderror"
                                    placeholder="Pilih tanggal" readonly>
                                <input type="hidden" name="tanggal_hidden" id="tanggal_hidden"
                                    value="{{ old('tanggal_hidden', $transaksiKasBank->tanggal->format('Y-m-d')) }}">
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Pilih tanggal transaksi
                            </p>
                            @error('tanggal_hidden')
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
                                    <i
                                        class="ti ti-building-bank text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                </div>
                                <select name="kas_bank_id" id="kas_bank_id"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('kas_bank_id') border-red-500 @enderror">
                                    <option value="">Pilih Kas/Bank</option>
                                    @foreach ($kasBankList as $kasBank)
                                        <option value="{{ $kasBank->id }}"
                                            {{ old('kas_bank_id', $transaksiKasBank->kas_bank_id) == $kasBank->id ? 'selected' : '' }}
                                            data-jenis="{{ $kasBank->jenis }}"
                                            data-saldo="{{ number_format($kasBank->saldo_terkini, 0, ',', '.') }}">
                                            {{ $kasBank->nama }} ({{ $kasBank->kode }})@if ($kasBank->no_rekening)
                                                - {{ $kasBank->no_rekening }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Pilih kas atau bank untuk transaksi
                            </p>
                            @error('kas_bank_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Transaksi (Hidden - Default Manual) -->
                        <input type="hidden" name="kategori_transaksi" value="MN">

                        <!-- Jenis Transaksi -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Jenis Transaksi <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="jenis_transaksi" value="D"
                                        {{ old('jenis_transaksi', $transaksiKasBank->jenis_transaksi) === 'D' ? 'checked' : '' }}
                                        class="mr-3 text-blue-600 focus:ring-blue-500" required>
                                    <div class="flex items-center">
                                        <i class="ti ti-arrow-down text-lg text-green-600 mr-2"></i>
                                        <span class="text-sm font-medium">Masuk</span>
                                    </div>
                                </label>
                                <label
                                    class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="jenis_transaksi" value="K"
                                        {{ old('jenis_transaksi', $transaksiKasBank->jenis_transaksi) === 'K' ? 'checked' : '' }}
                                        class="mr-3 text-blue-600 focus:ring-blue-500" required>
                                    <div class="flex items-center">
                                        <i class="ti ti-arrow-up text-lg text-red-600 mr-2"></i>
                                        <span class="text-sm font-medium">Keluar</span>
                                    </div>
                                </label>
                            </div>
                            @error('jenis_transaksi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Masuk untuk uang masuk, Keluar untuk uang keluar
                            </p>
                        </div>

                        <!-- Jumlah -->
                        <div class="space-y-2">
                            <label for="jumlah" class="block text-sm font-semibold text-gray-700">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i
                                        class="ti ti-currency-rupee text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                </div>
                                <input type="text" name="jumlah" id="jumlah"
                                    value="{{ old('jumlah', number_format($transaksiKasBank->jumlah, 0, ',', '.')) }}"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-right @error('jumlah_raw') border-red-500 @enderror"
                                    placeholder="0" style="text-align: right;">
                                <input type="hidden" name="jumlah_raw" id="jumlah_raw"
                                    value="{{ old('jumlah_raw', $transaksiKasBank->jumlah) }}">
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Masukkan jumlah transaksi (akan diformat otomatis)
                            </p>
                            @error('jumlah_raw')
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
                                    placeholder="Masukkan keterangan transaksi (opsional)" maxlength="255">{{ old('keterangan', $transaksiKasBank->keterangan) }}</textarea>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Keterangan tambahan untuk transaksi (opsional)
                            </p>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Card -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Preview Transaksi</h4>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-4">
                                        <i class="ti ti-exchange text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-base font-medium text-gray-900" id="preview-kas-bank">
                                            Pilih Kas/Bank
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <span id="preview-kategori">Manual</span> •
                                            <span id="preview-jenis">Debet</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900" id="preview-jumlah">
                                        Rp 0
                                    </div>
                                    <div class="text-sm text-gray-500" id="preview-tanggal">
                                        {{ date('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                        <a href="{{ route('transaksi-kas-bank.index') }}"
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
                                Update Transaksi
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
                        <h3 class="text-sm font-medium text-blue-800">Tips Edit Transaksi Kas & Bank</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Pilih kas/bank yang sesuai untuk transaksi</li>
                                <li>Masuk untuk uang masuk, Keluar untuk uang keluar</li>
                                <li>Transaksi ini akan disimpan sebagai transaksi manual</li>
                                <li>Isi keterangan untuk memudahkan pelacakan transaksi</li>
                                <li>Pastikan jumlah transaksi sudah benar sebelum disimpan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        $(document).ready(function() {
            // Frontend Validation Rules and Messages
            const validationRules = {
                kas_bank_id: {
                    required: true
                },
                jenis_transaksi: {
                    required: true
                },
                jumlah_raw: {
                    required: true,
                    min: 1
                },
                keterangan: {
                    maxLength: 255
                }
            };

            const validationMessages = {
                kas_bank_id: {
                    required: 'Kas/Bank wajib dipilih.'
                },
                jenis_transaksi: {
                    required: 'Jenis transaksi wajib dipilih.'
                },
                jumlah_raw: {
                    required: 'Jumlah wajib diisi.',
                    min: 'Jumlah minimal 1.'
                },
                keterangan: {
                    maxLength: 'Keterangan maksimal 255 karakter.'
                }
            };

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

                    // Update hidden field
                    $('#jumlah_raw').val(parseFormattedNumber(newValue));

                    // Trigger validation for jumlah_raw field
                    setTimeout(function() {
                        validateField('jumlah_raw', parseFormattedNumber(newValue));
                    }, 100);
                });

                input.addEventListener('blur', function(e) {
                    if (e.target.value === '' || e.target.value === '0') {
                        e.target.value = '';
                        $('#jumlah_raw').val('');
                        // Trigger validation for empty value
                        setTimeout(function() {
                            validateField('jumlah_raw', '');
                        }, 100);
                    } else {
                        // Trigger validation for non-empty value
                        setTimeout(function() {
                            validateField('jumlah_raw', parseFormattedNumber(e.target.value));
                        }, 100);
                    }
                });
            }

            // Live Preview Function
            function updatePreview() {
                const kasBankSelect = $('#kas_bank_id');
                const selectedOption = kasBankSelect.find('option:selected');
                const kasBankName = selectedOption.text() || 'Pilih Kas/Bank';
                const kategori = 'MN'; // Always Manual
                const jenis = $('input[name="jenis_transaksi"]:checked').val() || 'D';
                const jumlah = $('#jumlah').val() || 'Rp 0';
                const tanggal = $('#tanggal').val();

                // Extract kas/bank name without no rekening
                let displayName = kasBankName;
                if (kasBankName.includes(' - ')) {
                    displayName = kasBankName.split(' - ')[0];
                }

                $('#preview-kas-bank').text(displayName || 'Pilih Kas/Bank');
                $('#preview-kategori').text('Manual');
                $('#preview-jenis').text(jenis === 'D' ? 'Masuk' : 'Keluar');
                $('#preview-jumlah').text(jumlah);

                if (tanggal) {
                    // Parse tanggal dari format d/m/Y ke Date object
                    const dateParts = tanggal.split('/');
                    if (dateParts.length === 3) {
                        const day = parseInt(dateParts[0]);
                        const month = parseInt(dateParts[1]) - 1; // Month is 0-indexed
                        const year = parseInt(dateParts[2]);
                        const date = new Date(year, month, day);

                        if (!isNaN(date.getTime())) {
                            const formattedDate = date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });
                            $('#preview-tanggal').text(formattedDate);
                        } else {
                            $('#preview-tanggal').text('Invalid Date');
                        }
                    } else {
                        $('#preview-tanggal').text('Invalid Date');
                    }
                } else {
                    $('#preview-tanggal').text('Pilih Tanggal');
                }
            }

            // Real-time validation for form fields
            const fieldsToValidate = ['kas_bank_id', 'jumlah_raw', 'keterangan'];

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
                else if (rules.min && value && parseFloat(value) < rules.min) {
                    isValid = false;
                    errorMessage = messages.min;
                }
                // Max length validation
                else if (rules.maxLength && value && value.trim().length > rules.maxLength) {
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

            // Setup number input formatting for jumlah
            const jumlahInput = document.getElementById('jumlah');
            setupNumberInput(jumlahInput);

            // Initialize Flatpickr for tanggal
            flatpickr("#tanggal", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                defaultDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        // Update hidden field with Y-m-d format for backend
                        const backendDate = selectedDates[0].toISOString().split('T')[
                            0]; // Y-m-d format
                        document.getElementById('tanggal_hidden').value = backendDate;
                    }
                }
            });

            // Live preview functionality
            $('#kas_bank_id, #tanggal').on('change', updatePreview);
            $('input[name="jenis_transaksi"]').on('change', updatePreview);
            $('#jumlah').on('input', function() {
                updatePreview();
            });

            // Form submission validation
            $('#editTransaksiForm').on('submit', function(e) {
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

                // Check radio buttons
                if (!$('input[name="jenis_transaksi"]:checked').length) {
                    hasErrors = true;
                    alert('Jenis transaksi wajib dipilih');
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
                    showNotification('Sedang mengupdate data transaksi...', 'info');

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

            // Reset form functionality
            $('#resetBtn').on('click', function(e) {
                e.preventDefault();

                // Show confirmation dialog
                Swal.fire({
                    title: 'Reset Form?',
                    text: 'Apakah Anda yakin ingin mereset form? Semua data yang sudah diisi akan hilang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset form
                        $('#editTransaksiForm')[0].reset();

                        // Clear validation states
                        $('.error-message').remove();
                        $('input, select, textarea').removeClass('border-red-500 border-green-500')
                            .addClass('border-gray-300');

                        // Reset preview
                        updatePreview();

                        // Show success notification
                        showNotification('Form berhasil direset!', 'success');
                    }
                });
            });

            // Initialize preview
            updatePreview();
        });
    </script>
@endsection
