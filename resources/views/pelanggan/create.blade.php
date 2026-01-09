@extends('layouts.pos')

@section('title', 'Tambah Pelanggan')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('pelanggan.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Pelanggan</h1>
                    <p class="text-xs text-gray-500">Input data pelanggan baru</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-green-500 hover:bg-green-100 p-1.5 rounded-lg" onclick="this.parentElement.remove()">
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
                            <li><strong>Kode Pelanggan</strong> dibuat otomatis oleh sistem dan tidak dapat diubah.</li>
                            <li>Isi data <strong>Nama</strong> dan <strong>Nomor Telepon</strong> dengan benar.</li>
                            <li>Foto pelanggan bersifat <strong>opsional</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('pelanggan.store') }}" method="POST" id="createPelangganForm" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kode Pelanggan (Readonly) -->
                        <div class="field-wrapper">
                            <label for="kode_pelanggan" class="block text-xs font-bold text-gray-700 mb-1">
                                Kode Pelanggan
                            </label>
                            <input type="text" name="kode_pelanggan" id="kode_pelanggan" value="{{ $kodePelanggan }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                   readonly>
                        </div>

                        <!-- Nama Pelanggan -->
                        <div class="field-wrapper">
                            <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Pelanggan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nama') border-red-500 @enderror"
                                   placeholder="Contoh: Budi Santoso">
                            @error('nama') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nomor Telepon -->
                        <div class="field-wrapper">
                            <label for="nomor_telepon" class="block text-xs font-bold text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nomor_telepon') border-red-500 @enderror"
                                   placeholder="Contoh: 08123456789">
                            @error('nomor_telepon') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div class="field-wrapper">
                            <label for="status" class="block text-xs font-bold text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="field-wrapper">
                        <label for="alamat" class="block text-xs font-bold text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('alamat') border-red-500 @enderror"
                                  placeholder="Contoh: Jl. Sudirman No. 123">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Foto Pelanggan -->
                    <div class="field-wrapper">
                        <label for="foto" class="block text-xs font-bold text-gray-700 mb-1">
                            Foto Pelanggan
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <div id="foto-preview" class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="foto" id="foto" accept="image/*"
                                       class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-gray-200 rounded-lg cursor-pointer">
                                <p class="mt-1 text-[10px] text-gray-400">Format: JPG, PNG. Maks: 2MB</p>
                            </div>
                        </div>
                        @error('foto') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Reset Form
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Simpan Pelanggan
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
            // Foto Preview
            $('#foto').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#foto-preview').html(`
                            <img src="${e.target.result}" class="h-full w-full object-cover">
                        `);
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#foto-preview').html(`
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    `);
                }
            });

            // Validation Rules
            const validationRules = {
                nama: {
                    required: true,
                    minLength: 2,
                    maxLength: 100
                },
                nomor_telepon: {
                     maxLength: 20
                }
            };

            const validationMessages = {
                nama: {
                    required: 'Nama pelanggan wajib diisi.',
                    minLength: 'Nama minimal 2 karakter.',
                    maxLength: 'Nama maksimal 100 karakter.'
                },
                nomor_telepon: {
                    maxLength: 'Nomor telepon maksimal 20 karakter.'
                }
            };

            // Real-time validation for specific fields
            const fieldsToValidate = ['nama', 'nomor_telepon'];

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
                } else if (rules.minLength && value.trim().length < rules.minLength) {
                    isValid = false;
                    errorMessage = messages.minLength;
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
            $('#createPelangganForm').on('submit', function(e) {
                let hasErrors = false;

                if ($('.error-message').length > 0) hasErrors = true;

                // Validate required fields
                 if (!$('#nama').val()) {
                    hasErrors = true;
                    validateField('nama', '');
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
