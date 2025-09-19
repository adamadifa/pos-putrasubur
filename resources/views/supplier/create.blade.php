@extends('layouts.pos')

@section('title', 'Tambah Supplier')
@section('page-title', 'Tambah Supplier Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('supplier.index') }}"
                        class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <i class="ti ti-building-store text-xl text-white"></i>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Tambah Supplier</h1>
                            <p class="text-gray-500 mt-1">Buat supplier baru untuk sistem POS Anda</p>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                    <i class="ti ti-info-circle text-sm"></i>
                    <span>Fields bertanda <span class="text-red-500 font-medium">*</span> wajib diisi</span>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-check text-lg text-green-400"></i>
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
                                <i class="ti ti-x text-lg"></i>
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
                        <i class="ti ti-alert-circle text-lg text-red-400"></i>
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
                                <i class="ti ti-x text-lg"></i>
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
                        <i class="ti ti-building-store text-lg text-blue-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Form Tambah Supplier</h2>
                </div>
            </div>

            <form action="{{ route('supplier.store') }}" method="POST" class="p-8" id="createSupplierForm"
                enctype="multipart/form-data">
                @csrf

                <div class="space-y-8">

                    <!-- Nama Supplier -->
                    <div class="space-y-2">
                        <label for="nama" class="block text-sm font-semibold text-gray-700">
                            Nama Supplier <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-building text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('nama') border-red-500 @enderror"
                                placeholder="Masukkan nama supplier" maxlength="100">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Nama supplier akan ditampilkan di sistem dan laporan
                        </p>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-semibold text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-toggle-right text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <select name="status" id="status"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('status') border-red-500 @enderror">
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="space-y-2">
                        <label for="telepon" class="block text-sm font-semibold text-gray-700">
                            Nomor Telepon
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-phone text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="tel" name="telepon" id="telepon" value="{{ old('telepon') }}"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('telepon') border-red-500 @enderror"
                                placeholder="08123456789" maxlength="20">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Nomor telepon untuk kontak supplier
                        </p>
                        @error('telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-mail text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('email') border-red-500 @enderror"
                                placeholder="supplier@example.com" maxlength="100">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Email untuk komunikasi dan dokumen
                        </p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="space-y-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700">
                            Alamat Lengkap
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-map-pin text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('alamat') border-red-500 @enderror"
                                placeholder="Masukkan alamat lengkap supplier" maxlength="255">{{ old('alamat') }}</textarea>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Alamat untuk pengiriman dan billing
                        </p>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="space-y-2">
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                            Keterangan
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-note text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('keterangan') border-red-500 @enderror"
                                placeholder="Tambahkan keterangan tambahan tentang supplier" maxlength="255">{{ old('keterangan') }}</textarea>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Informasi tambahan tentang supplier
                        </p>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Preview Supplier</h4>
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-4">
                                <i class="ti ti-building-store text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-base font-medium text-gray-900" id="preview-nama">Nama Supplier</div>
                                <div class="text-sm text-gray-500" id="preview-phone">08123456789</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('supplier.index') }}"
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
                            <i class="ti ti-plus text-lg mr-3"></i>
                            Simpan Supplier
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tips Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="ti ti-info-circle text-lg text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Tips Menambah Supplier</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Pastikan data yang diisi lengkap dan akurat</li>
                            <li>Kode supplier harus unik dan tidak boleh kosong</li>
                            <li>Nomor telepon opsional (maksimal 20 karakter)</li>
                            <li>Email opsional untuk komunikasi</li>
                            <li>Alamat dan keterangan opsional</li>
                            <li>Set status aktif/nonaktif sesuai kebutuhan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Frontend Validation Rules and Messages
            const validationRules = {
                nama: {
                    required: true,
                    maxLength: 100,
                    minLength: 2
                },
                telepon: {
                    required: false,
                    maxLength: 20,
                    minLength: 8
                },
                email: {
                    required: false,
                    maxLength: 100,
                    email: true
                },
                alamat: {
                    required: false,
                    maxLength: 255
                },
                keterangan: {
                    required: false,
                    maxLength: 255
                }
            };

            const validationMessages = {
                nama: {
                    required: 'Nama supplier wajib diisi.',
                    maxLength: 'Nama supplier maksimal 100 karakter.',
                    minLength: 'Nama supplier minimal 2 karakter.'
                },
                telepon: {
                    maxLength: 'Nomor telepon maksimal 20 karakter.',
                    minLength: 'Nomor telepon minimal 8 karakter.'
                },
                email: {
                    maxLength: 'Email maksimal 100 karakter.',
                    email: 'Format email tidak valid.'
                },
                alamat: {
                    maxLength: 'Alamat maksimal 255 karakter.'
                },
                keterangan: {
                    maxLength: 'Keterangan maksimal 255 karakter.'
                }
            };

            // Real-time validation for form fields
            const fieldsToValidate = ['nama', 'telepon', 'email', 'alamat', 'keterangan'];

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
                    validateField(fieldName, value);
                });
            });

            // Update preview in real-time
            $('#nama').on('input', function() {
                const nama = $(this).val() || 'Nama Supplier';
                $('#preview-nama').text(nama);
            });

            // Kode supplier is auto-generated, no need for input handler

            $('#telepon').on('input', function() {
                const phone = $(this).val() || '08123456789';
                $('#preview-phone').text(phone);
            });

            // Form submission handler
            $('#createSupplierForm').on('submit', function(e) {
                e.preventDefault();

                // Validate all fields before submission
                let isValid = true;
                fieldsToValidate.forEach(function(fieldName) {
                    const field = $(`#${fieldName}`);
                    const value = field.val();
                    if (!validateField(fieldName, value)) {
                        isValid = false;
                    }
                });

                if (isValid) {
                    // Show loading state
                    const submitBtn = $('#submitBtn');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true);
                    submitBtn.html(
                        '<i class="ti ti-loader animate-spin text-xl mr-3"></i>Menyimpan...'
                    );

                    // Submit form
                    this.submit();
                }
            });

            // Validation function
            function validateField(fieldName, value) {
                const field = $(`#${fieldName}`);
                const errorDiv = $(`#${fieldName}-error`);
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Clear previous errors
                field.removeClass('border-red-500').addClass('border-gray-300');
                errorDiv.addClass('hidden');

                // Required validation
                if (rules.required && !value.trim()) {
                    showFieldError(fieldName, messages.required);
                    return false;
                }

                // Email validation
                if (rules.email && value && !isValidEmail(value)) {
                    showFieldError(fieldName, messages.email);
                    return false;
                }

                // Min length validation
                if (rules.minLength && value && value.length < rules.minLength) {
                    showFieldError(fieldName, messages.minLength);
                    return false;
                }

                // Max length validation
                if (rules.maxLength && value && value.length > rules.maxLength) {
                    showFieldError(fieldName, messages.maxLength);
                    return false;
                }

                return true;
            }

            // Email validation helper
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Show field error
            function showFieldError(fieldName, message) {
                const field = $(`#${fieldName}`);
                const fieldContainer = field.closest('.space-y-2');

                field.removeClass('border-gray-300').addClass('border-red-500');

                // Remove existing error message
                fieldContainer.find('.error-message').remove();

                // Add error message
                const errorHtml = `
                    <p class="mt-2 text-sm text-red-600 flex items-center error-message">
                        <i class="ti ti-alert-circle text-sm mr-2 flex-shrink-0"></i>
                        ${message}
                    </p>
                `;
                fieldContainer.append(errorHtml);
            }
        });
    </script>
@endpush
