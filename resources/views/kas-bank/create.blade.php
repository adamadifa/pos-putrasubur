@extends('layouts.pos')

@section('title', 'Tambah Kas & Bank')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('kas-bank.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Akun</h1>
                    <p class="text-xs text-gray-500">Buat akun kas atau bank baru</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                <button type="button" class="ml-auto text-red-500 hover:bg-red-100 p-1.5 rounded-lg" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800">Terdapat kesalahan pada form:</p>
                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Instruction Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Petunjuk Pengisian</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Kode</strong> harus unik (contoh: BCA01, KAS01).</li>
                            <li>Pilih <strong>Jenis</strong> akun (KAS untuk tunai, BANK untuk rekening).</li>
                            <li><strong>Saldo Awal</strong> menentukan saldo saat akun dibuat.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('kas-bank.store') }}" method="POST" enctype="multipart/form-data" id="createKasBankForm">
                @csrf
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kode -->
                        <div class="field-wrapper">
                            <label for="kode" class="block text-xs font-bold text-gray-700 mb-1">
                                Kode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode" id="kode" value="{{ old('kode') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 font-mono uppercase @error('kode') border-red-500 @enderror"
                                   placeholder="Contoh: KAS-BESAR"
                                   onkeyup="this.value = this.value.toUpperCase().replace(/\s/g, '')">
                            @error('kode') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama -->
                        <div class="field-wrapper">
                            <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Akun <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nama') border-red-500 @enderror"
                                   placeholder="Contoh: Kas Besar Toko">
                            @error('nama') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jenis -->
                        <div class="field-wrapper">
                            <label for="jenis" class="block text-xs font-bold text-gray-700 mb-1">
                                Jenis <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis" id="jenis" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30">
                                <option value="KAS" {{ old('jenis') == 'KAS' ? 'selected' : '' }}>KAS (Tunai)</option>
                                <option value="BANK" {{ old('jenis') == 'BANK' ? 'selected' : '' }}>BANK (Rekening)</option>
                            </select>
                        </div>

                         <!-- No Rekening -->
                        <div class="field-wrapper" id="rekening-wrapper" style="display: none;">
                            <label for="no_rekening" class="block text-xs font-bold text-gray-700 mb-1">
                                Nomor Rekening
                            </label>
                            <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('no_rekening') border-red-500 @enderror"
                                   placeholder="Contoh: 1234567890">
                            @error('no_rekening') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Saldo Awal -->
                        <div class="field-wrapper">
                            <label for="saldo_awal" class="block text-xs font-bold text-gray-700 mb-1">
                                Saldo Awal
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="saldo_awal" id="saldo_awal" value="{{ old('saldo_awal', 0) }}"
                                       class="w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('saldo_awal') border-red-500 @enderror"
                                       placeholder="0">
                            </div>
                            @error('saldo_awal') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                         <!-- Saldo Terkini (Hidden/Auto) -->
                         <input type="hidden" name="saldo_terkini" id="saldo_terkini" value="{{ old('saldo_terkini', 0) }}">

                         <!-- Logo / Image -->
                        <div class="field-wrapper">
                            <label for="image" class="block text-xs font-bold text-gray-700 mb-1">
                                Logo Bank / Icon
                            </label>
                             <input type="file" name="image" id="image" accept="image/*"
                                   class="block w-full text-xs text-slate-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-xs file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          file:hover:bg-blue-100
                                          border border-gray-200 rounded-lg cursor-pointer bg-gray-50/30
                                   "/>
                             <p class="mt-1 text-[10px] text-gray-500">Maksimal 2MB (JPG, PNG)</p>
                             @error('image') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Status Card Payment -->
                     <div class="field-wrapper flex items-center mt-2">
                        <input type="checkbox" name="status_card_payment" id="status_card_payment" value="1" {{ old('status_card_payment') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="status_card_payment" class="ml-2 block text-sm text-gray-700">
                            Gunakan sebagai akun utama untuk pembayaran kartu (Debit/Kredit)
                        </label>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Reset
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
             // Handle initial state of No Rekening
            function toggleRekening() {
                if ($('#jenis').val() === 'BANK') {
                    $('#rekening-wrapper').slideDown();
                } else {
                    $('#rekening-wrapper').slideUp();
                }
            }
            toggleRekening();
            $('#jenis').change(toggleRekening);

            // Sync Saldo Awal to Saldo Terkini
            $('#saldo_awal').on('input', function() {
                $('#saldo_terkini').val($(this).val());
            });

            // Validation Rules
            const validationRules = {
                kode: {
                    required: true,
                    maxLength: 20
                },
                nama: {
                    required: true,
                    maxLength: 100
                }
            };

            const validationMessages = {
                kode: {
                    required: 'Kode wajib diisi.',
                    maxLength: 'Kode maksimal 20 karakter.'
                },
                nama: {
                    required: 'Nama akun wajib diisi.',
                    maxLength: 'Nama maksimal 100 karakter.'
                }
            };

            // Real-time validation
            const fieldsToValidate = ['kode', 'nama'];

            fieldsToValidate.forEach(function(fieldName) {
                const field = $(`#${fieldName}`);
                let validationTimeout;

                field.on('input change blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);

                    // Skip validation if empty and not required
                    if (!value && !validationRules[fieldName]?.required) {
                        return;
                    }

                    validationTimeout = setTimeout(function() {
                        validateField(fieldName, value);
                    }, 300);
                });
            });

            function validateField(fieldName, value) {
                const field = $(`#${fieldName}`);
                const fieldContainer = field.closest('.field-wrapper');
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Reset state
                field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
                fieldContainer.find('.error-message').remove();

                if (!rules) return;
                if (!value && !rules.required) return;

                let isValid = true;
                let errorMessage = '';

                if (rules.required && (!value || value.toString().trim() === '')) {
                    isValid = false;
                    errorMessage = messages.required;
                } else if (rules.maxLength && value.length > rules.maxLength) {
                    isValid = false;
                    errorMessage = messages.maxLength;
                }

                if (!isValid) {
                    field.removeClass('border-gray-200 border-green-500 focus:border-blue-500').addClass('border-red-500');
                    
                    const errorHtml = `
                        <p class="mt-1 text-xs text-red-600 flex items-center error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            ${errorMessage}
                        </p>
                    `;
                    fieldContainer.append(errorHtml);
                } else {
                    field.removeClass('border-gray-200 border-red-500').addClass('border-green-500');
                }
            }

            // Form Submit
            $('#createKasBankForm').on('submit', function(e) {
                let hasErrors = false;

                if ($('.error-message').length > 0) hasErrors = true;

                // Validate required fields
                 if (!$('#nama').val()) {
                    hasErrors = true;
                    validateField('nama', '');
                }
                if (!$('#kode').val()) {
                    hasErrors = true;
                    validateField('kode', '');
                }

                if (hasErrors) {
                    e.preventDefault();
                    const firstError = $('.error-message').first().parent().find('input');
                    firstError.focus();
                } else {
                    const submitBtn = $('#submitBtn');
                    submitBtn.prop('disabled', true).html(`
                        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    `);
                }
            });
        });
    </script>
@endpush
