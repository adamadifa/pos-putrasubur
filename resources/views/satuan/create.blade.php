@extends('layouts.pos')

@section('title', 'Tambah Satuan')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('satuan.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Satuan</h1>
                    <p class="text-xs text-gray-500">Input data satuan produk baru</p>
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
                            <li>Isi <strong>Nama Satuan</strong> dengan unit pengukuran yang sesuai (contoh: Pcs, Kg, Liter, Box).</li>
                            <li>Pastikan nama satuan <strong>unik</strong> dan belum pernah terdaftar.</li>
                            <li>Klik tombol <strong>Simpan Satuan</strong> untuk menyimpan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('satuan.store') }}" method="POST" id="createSatuanForm">
                @csrf
                
                <div class="p-6 space-y-4">
                    <!-- Nama Satuan -->
                    <div class="field-wrapper">
                        <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">
                            Nama Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nama') border-red-500 @enderror"
                               placeholder="Contoh: Kg">
                        @error('nama') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        <p class="mt-1.5 text-xs text-gray-400">Nama satuan harus unik.</p>
                    </div>

                    <!-- Preview Box -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 flex items-center gap-4">
                        <div class="h-10 w-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.504 5.504 0 0 1-1.12.28m-5.166-.45l-.433-2.58a1.69 1.69 0 0 1 1.442-1.956c.496-.06.945-.296 1.259-.65l2.899-3.483c.25-.3.402-.676.44-1.077a7.665 7.665 0 0 0-.233-2.776A13.844 13.844 0 0 0 18.75 4.97Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Preview</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5" id="preview-text">Nama Satuan</p>
                        </div>
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
                        Simpan Satuan
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
            // Live Preview
            $('#nama').on('input', function() {
                var val = $(this).val();
                $('#preview-text').text(val ? val : 'Nama Satuan');
            });

            // Validation Rules
            const validationRules = {
                nama: {
                    required: true,
                    minLength: 1, // Satuan can be short like "g", "m"
                    maxLength: 50
                }
            };

            const validationMessages = {
                nama: {
                    required: 'Nama satuan wajib diisi.',
                    minLength: 'Nama satuan minimal 1 karakter.',
                    maxLength: 'Nama satuan maksimal 50 karakter.'
                }
            };

            // Real-time validation
            const fieldsToValidate = ['nama'];

            fieldsToValidate.forEach(function(fieldName) {
                const field = $(`#${fieldName}`);
                let validationTimeout;

                field.on('input change blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);

                    if (!value && $(this)[0].type !== 'blur') {
                        return;
                    }

                    validationTimeout = setTimeout(function() {
                        validateField(fieldName, value);
                    }, 300);
                });

                field.on('blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);
                    validateField(fieldName, value);
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
            $('#createSatuanForm').on('submit', function(e) {
                let hasErrors = false;

                if ($('.error-message').length > 0) hasErrors = true;

                fieldsToValidate.forEach(function(fieldName) {
                    const field = $(`#${fieldName}`);
                    if (!field.val()) {
                        hasErrors = true;
                        validateField(fieldName, field.val());
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    // Shake animation or focus first error
                    const firstError = $('.error-message').first().parent().find('input');
                    firstError.focus();
                } else {
                    const submitBtn = $('#submitBtn');
                    const originalContent = submitBtn.html();
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
