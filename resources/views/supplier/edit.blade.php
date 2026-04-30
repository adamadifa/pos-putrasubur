@extends('layouts.pos')

@section('title', 'Edit Supplier')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('supplier.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Edit Supplier</h1>
                    <p class="text-xs text-gray-500">Perbarui data pemasok</p>
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
                    <h3 class="text-sm font-medium text-blue-800">Petunjuk Edit Supplier</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Kode Supplier</strong> tidak dapat diubah (Read-only).</li>
                            <li>Perbarui informasi kontak jika ada perubahan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('supplier.update', $supplier->encrypted_id) }}" method="POST" id="editSupplierForm">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kode Supplier (Readonly) -->
                        <div class="field-wrapper">
                            <label for="kode_supplier" class="block text-xs font-bold text-gray-700 mb-1">
                                Kode Supplier
                            </label>
                            <input type="text" name="kode_supplier" id="kode_supplier" value="{{ $supplier->kode_supplier }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                   readonly>
                        </div>

                        <!-- Nama Supplier -->
                        <div class="field-wrapper">
                            <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Supplier <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $supplier->nama) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nama') border-red-500 @enderror"
                                   placeholder="Contoh: PT. Sinar Jaya Abadi">
                            @error('nama') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nomor Telepon -->
                        <div class="field-wrapper">
                            <label for="telepon" class="block text-xs font-bold text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $supplier->telepon) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('telepon') border-red-500 @enderror"
                                   placeholder="Contoh: 021-5551234">
                            @error('telepon') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="field-wrapper">
                            <label for="email" class="block text-xs font-bold text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('email') border-red-500 @enderror"
                                   placeholder="Contoh: info@sinarjaya.com">
                            @error('email') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <!-- Status -->
                         <div class="field-wrapper">
                            <label for="status" class="block text-xs font-bold text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30">
                                <option value="1" {{ old('status', $supplier->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', $supplier->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="field-wrapper">
                        <label for="alamat" class="block text-xs font-bold text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea name="alamat" id="alamat" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('alamat') border-red-500 @enderror"
                                  placeholder="Contoh: Jl. Industri Raya No. 99, Jakarta Barat">{{ old('alamat', $supplier->alamat) }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="field-wrapper">
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 mb-1">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('keterangan') border-red-500 @enderror"
                                  placeholder="Tambahan catatan (opsional)">{{ old('keterangan', $supplier->keterangan) }}</textarea>
                        @error('keterangan') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Link ke Pelanggan -->
                    <div class="field-wrapper">
                        <label for="pelanggan_id" class="block text-xs font-bold text-gray-700 mb-1">
                            Hubungkan ke Pelanggan
                        </label>
                        <select name="pelanggan_id" id="pelanggan_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('pelanggan_id') border-red-500 @enderror">
                            <option value="">-- Tidak dihubungkan --</option>
                            @foreach ($pelangganList as $pelanggan)
                                <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id', $supplier->pelanggan_id) == $pelanggan->id ? 'selected' : '' }}>
                                    {{ $pelanggan->kode_pelanggan }} - {{ $pelanggan->nama }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            <svg class="w-3 h-3 inline mr-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            Opsional. Hubungkan jika supplier ini juga membeli barang dari Anda (untuk fitur potongan penjualan).
                        </p>
                        @error('pelanggan_id') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('supplier.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Simpan Perubahan
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
            // Validation Rules
            const validationRules = {
                nama: {
                    required: true,
                    minLength: 2,
                    maxLength: 100
                },
                telepon: {
                    maxLength: 20
                },
                email: {
                    email: true,
                    maxLength: 100
                }
            };

            const validationMessages = {
                nama: {
                    required: 'Nama supplier wajib diisi.',
                    minLength: 'Nama minimal 2 karakter.',
                    maxLength: 'Nama maksimal 100 karakter.'
                },
                telepon: {
                    maxLength: 'Nomor telepon maksimal 20 karakter.'
                },
                email: {
                    email: 'Format email tidak valid.',
                    maxLength: 'Email maksimal 100 karakter.'
                }
            };

            // Real-time validation
            const fieldsToValidate = ['nama', 'telepon', 'email'];

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
                } else if (rules.email) {
                     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                     if (!emailRegex.test(value)) {
                         isValid = false;
                         errorMessage = messages.email;
                     }
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
            $('#editSupplierForm').on('submit', function(e) {
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
                        Mengupdate...
                    `);
                }
            });
        });
    </script>
@endpush
