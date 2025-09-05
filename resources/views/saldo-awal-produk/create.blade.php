@extends('layouts.pos')

@section('title', 'Tambah Saldo Awal Produk')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('saldo-awal-produk.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Tambah Saldo Awal Produk</h1>
                                <p class="text-gray-500 mt-1">Set saldo awal stok produk untuk periode tertentu</p>
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
                            <i class="ti ti-check-circle text-lg text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
                            <div class="mt-1 text-sm text-green-700">{{ session('success') }}</div>
                        </div>
                    </div>
                </div>
            @endif

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
                <form action="{{ route('saldo-awal-produk.store') }}" method="POST" id="saldoAwalForm">
                    @csrf

                    <div class="space-y-8">
                        <!-- Periode dan Generate Button -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <!-- Periode Bulan -->
                            <div class="space-y-2">
                                <label for="periode_bulan" class="block text-sm font-semibold text-gray-700">
                                    Bulan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="ti ti-calendar text-gray-400 group-hover:text-green-500 transition-colors text-lg"></i>
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
                                            class="ti ti-calendar text-gray-400 group-hover:text-green-500 transition-colors text-lg"></i>
                                    </div>
                                    <select name="periode_tahun" id="periode_tahun"
                                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('periode_tahun') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($tahunList as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ old('periode_tahun') == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('periode_tahun')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Generate Button -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    &nbsp;
                                </label>
                                <button type="button" id="generateBtn"
                                    class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    disabled>
                                    <i class="ti ti-refresh text-lg mr-2"></i>
                                    Generate
                                </button>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div id="loadingState" class="hidden">
                            <div class="flex items-center justify-center space-x-2 text-sm text-blue-600">
                                <i class="ti ti-loader-2 animate-spin"></i>
                                <span>Memuat daftar produk...</span>
                            </div>
                        </div>

                        <!-- Produk List -->
                        <div id="produkListContainer" class="hidden">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    Saldo Awal Produk <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                    Masukkan saldo awal untuk setiap produk (kosongkan jika tidak ada stok)
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Foto
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Produk
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Kategori
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Satuan
                                                </th>
                                                <th
                                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Saldo Awal
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="produkListBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Produk list will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                                Keterangan
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                    <i
                                        class="ti ti-note text-gray-400 group-hover:text-green-500 transition-colors text-lg"></i>
                                </div>
                                <textarea name="keterangan" id="keterangan" rows="4"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white resize-none @error('keterangan') border-red-500 @enderror"
                                    placeholder="Keterangan saldo awal produk (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class="ti ti-info-circle text-gray-400 mr-1"></i>
                                Keterangan tambahan untuk saldo awal produk (opsional)
                            </p>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('saldo-awal-produk.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled>
                            <i class="ti ti-check text-lg mr-2"></i>
                            Simpan Saldo Awal
                        </button>
                    </div>
                </form>
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

            // Elements
            const periodeBulanSelect = $('#periode_bulan');
            const periodeTahunSelect = $('#periode_tahun');
            const generateBtn = $('#generateBtn');
            const loadingState = $('#loadingState');
            const produkListContainer = $('#produkListContainer');
            const produkListBody = $('#produkListBody');
            const submitBtn = $('#submitBtn');

            // Check if form is ready for generate
            function checkGenerateReady() {
                const periodeBulan = periodeBulanSelect.val();
                const periodeTahun = periodeTahunSelect.val();

                if (periodeBulan && periodeTahun) {
                    generateBtn.prop('disabled', false);
                } else {
                    generateBtn.prop('disabled', true);
                }
            }

            // Listen for changes in form fields
            periodeBulanSelect.on('change', checkGenerateReady);
            periodeTahunSelect.on('change', checkGenerateReady);

            // Initial check
            checkGenerateReady();

            // Generate button click handler
            generateBtn.on('click', function() {
                const periodeBulan = periodeBulanSelect.val();
                const periodeTahun = periodeTahunSelect.val();

                if (!periodeBulan || !periodeTahun) {
                    showNotification('Pilih periode terlebih dahulu', 'error');
                    return;
                }

                // Show loading state
                generateBtn.prop('disabled', true);
                loadingState.removeClass('hidden');
                produkListContainer.addClass('hidden');
                submitBtn.prop('disabled', true);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('saldo-awal-produk.get-all-produk') }}',
                    method: 'POST',
                    data: {
                        periode_bulan: periodeBulan,
                        periode_tahun: periodeTahun
                    },
                    success: function(response) {
                        if (response.success) {
                            populateProdukList(response.data);
                            produkListContainer.removeClass('hidden');
                            // Notifikasi success akan ditampilkan di dalam populateProdukList jika tidak ada masalah
                        } else {
                            showNotification('Gagal memuat daftar produk: ' + response.message,
                                'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat memuat daftar produk';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        // Hide loading state
                        loadingState.addClass('hidden');
                        generateBtn.prop('disabled', false);
                    }
                });
            });

            // Populate produk list
            function populateProdukList(produkData) {
                // Cek apakah ada produk yang saldo sebelumnya belum di-set
                let hasSaldoSebelumnyaBelumDiSet = false;
                produkData.forEach(function(produk) {
                    if (produk.saldo_sebelumnya_belum_diset) {
                        hasSaldoSebelumnyaBelumDiSet = true;
                    }
                });

                // Jika ada produk yang saldo sebelumnya belum di-set, tampilkan notifikasi dan kosongkan list
                if (hasSaldoSebelumnyaBelumDiSet) {
                    produkListBody.html('');
                    showNotification(
                        '⚠️ Tidak dapat menampilkan list produk karena saldo awal bulan sebelumnya belum di-set. Silakan set saldo awal bulan sebelumnya terlebih dahulu.',
                        'warning');

                    // Disable submit button
                    submitBtn.prop('disabled', true);
                    return; // Keluar dari function, tidak tampilkan list produk
                }

                // Jika tidak ada masalah, tampilkan list produk seperti biasa
                let html = '';
                produkData.forEach(function(produk) {
                    const existingClass = produk.has_existing ? 'bg-yellow-50 border-yellow-200' :
                        'bg-white';

                    let statusText = '';
                    if (produk.has_existing) {
                        statusText =
                            `<span class="text-xs text-yellow-600 font-medium">Sudah ada: ${formatNumber(produk.existing_saldo)}</span>`;
                    } else if (produk.calculated_saldo && produk.calculated_saldo > 0) {
                        statusText =
                            `<span class="text-xs text-blue-600 font-medium">Otomatis: ${formatNumber(produk.calculated_saldo)}</span>`;
                    } else {
                        statusText = `<span class="text-xs text-gray-500 font-medium">Input manual</span>`;
                    }

                    // Gunakan saldo yang sudah ada atau saldo yang dihitung otomatis
                    const defaultSaldo = produk.has_existing ? produk.existing_saldo : (produk
                        .calculated_saldo || '');

                    html += `
                          <tr class="${existingClass}">
                              <td class="px-4 py-4 whitespace-nowrap">
                                  <div class="h-12 w-12 rounded-lg border border-gray-200 bg-white flex items-center justify-center p-1">
                                      ${produk.foto ? 
                                          `<img class="max-h-full max-w-full object-contain" src="${produk.foto}" alt="${produk.nama}">` :
                                          `<div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                                                          <span class="text-white font-medium text-lg">${produk.nama.charAt(0).toUpperCase()}</span>
                                                                      </div>`
                                      }
                                  </div>
                              </td>
                              <td class="px-4 py-4 whitespace-nowrap">
                                  <div class="text-sm font-medium text-gray-900">${produk.nama}</div>
                                  ${statusText}
                              </td>
                              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                  ${produk.kategori}
                              </td>
                              <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                  ${produk.satuan}
                              </td>
                              <td class="px-4 py-4 whitespace-nowrap">
                                  <div class="flex items-center justify-end">
                                      <input type="text" 
                                             name="saldo_awal[${produk.id}]" 
                                             value="${formatDecimalInput(defaultSaldo)}"
                                             class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-right saldo-input"
                                             placeholder="0"
                                             data-produk-id="${produk.id}">
                                  </div>
                              </td>
                          </tr>
                      `;
                });
                produkListBody.html(html);

                // Setup decimal input formatting for all saldo inputs
                $('.saldo-input').each(function() {
                    setupDecimalInput(this);
                });

                // Enable submit button jika tidak ada masalah
                submitBtn.prop('disabled', false);

                // Tampilkan notifikasi success jika list produk berhasil dimuat
                showNotification('Daftar produk berhasil dimuat', 'success');
            }

            // Helper function untuk format number
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Format decimal input with thousand separator
            function formatDecimalInput(value) {
                if (!value || value === '0' || value === 0) return '';

                // Convert to string and handle decimal
                let strValue = value.toString();

                // Handle decimal separator (comma)
                let hasDecimal = strValue.includes(',') || strValue.includes('.');
                let integerPart = '';
                let decimalPart = '';

                if (hasDecimal) {
                    let parts = strValue.replace(',', '.').split('.');
                    integerPart = parts[0] || '';
                    decimalPart = parts[1] || '';
                } else {
                    integerPart = strValue;
                }

                // Format integer part with thousand separators
                if (integerPart && integerPart.length >= 4) {
                    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                // Combine parts
                let result = integerPart;
                if (hasDecimal && decimalPart) {
                    result += ',' + decimalPart;
                }

                return result;
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

            // Setup decimal input with formatting
            function setupDecimalInput(input) {
                let isFormatting = false;
                let lastValidValue = '';

                input.addEventListener('input', function(e) {
                    if (isFormatting) return;

                    const cursorPosition = e.target.selectionStart;
                    const oldValue = e.target.value;

                    // Get the raw numeric value by removing all formatting
                    let rawValue = e.target.value.replace(/[^\d,]/g, ''); // Keep digits and comma only

                    // If input is empty, don't format yet
                    if (!rawValue) {
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
                            let addedDots = (newValue.match(/\./g) || []).length - (oldValue.match(/\./g) ||
                                []).length;
                            if (addedDots > 0) {
                                // Find where the dot was added relative to cursor
                                let textBeforeCursor = oldValue.substring(0, cursorPosition);
                                let digitsBeforeCursor = textBeforeCursor.replace(/[^\d]/g, '').length;

                                // Count how many dots should be before this many digits in the new value
                                let newTextUpToDigits = newValue.replace(/[^\d.]/g, '');
                                let dotsBeforeDigits = 0;
                                let digitCount = 0;
                                for (let i = 0; i < newTextUpToDigits.length; i++) {
                                    if (newTextUpToDigits[i] === '.') {
                                        dotsBeforeDigits++;
                                    } else {
                                        digitCount++;
                                        if (digitCount === digitsBeforeCursor) {
                                            break;
                                        }
                                    }
                                }

                                newCursorPos = cursorPosition + (dotsBeforeDigits - oldDots);
                            }
                        }

                        // Ensure cursor position is within bounds
                        newCursorPos = Math.min(newCursorPos, newValue.length);
                        newCursorPos = Math.max(0, newCursorPos);

                        e.target.setSelectionRange(newCursorPos, newCursorPos);
                        isFormatting = false;
                    }
                });

                input.addEventListener('blur', function(e) {
                    if (e.target.value === '' || e.target.value === '0') {
                        e.target.value = '';
                        return;
                    }

                    // Ensure we have a valid decimal format
                    let numericValue = parseFormattedDecimal(e.target.value);
                    if (numericValue > 0) {
                        e.target.value = formatDecimalInput(numericValue.toString());
                    } else {
                        e.target.value = '';
                    }
                });

                input.addEventListener('focus', function(e) {
                    // Select all text when focused for easy editing
                    e.target.select();
                });
            }

            // Form submission validation
            $('#saldoAwalForm').on('submit', function(e) {
                const periodeBulan = periodeBulanSelect.val();
                const periodeTahun = periodeTahunSelect.val();

                if (!periodeBulan || !periodeTahun) {
                    e.preventDefault();
                    showNotification('Pilih periode terlebih dahulu', 'error');
                    return false;
                }

                // Check if there are any products in the list (if empty, means previous saldo not set)
                if (produkListBody.find('tr').length === 0) {
                    e.preventDefault();
                    showNotification(
                        'Tidak dapat menyimpan karena saldo awal bulan sebelumnya belum di-set. Silakan set saldo awal bulan sebelumnya terlebih dahulu.',
                        'error');
                    return false;
                }

                // Check if at least one saldo awal is filled
                let hasSaldoAwal = false;
                $('input[name^="saldo_awal["]').each(function() {
                    const value = parseFormattedDecimal($(this).val());
                    if (value > 0) {
                        hasSaldoAwal = true;
                        return false; // break loop
                    }
                });

                if (!hasSaldoAwal) {
                    e.preventDefault();
                    showNotification('Minimal satu produk harus memiliki saldo awal > 0', 'error');
                    return false;
                }

                // Convert formatted values to numeric values before submission
                $('input[name^="saldo_awal["]').each(function() {
                    const formattedValue = $(this).val();
                    const numericValue = parseFormattedDecimal(formattedValue);
                    $(this).val(numericValue);
                });

                // Disable submit button to prevent double submission
                submitBtn.prop('disabled', true).html(
                    '<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>Memproses...');
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
                    case 'warning':
                        bgColor = 'bg-yellow-500';
                        icon = `<i class="ti ti-alert-triangle text-lg mr-2"></i>`;
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        icon = `<i class="ti ti-info-circle text-lg mr-2"></i>`;
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
        });
    </script>
@endsection
