@extends('layouts.pos')

@section('title', 'Edit Metode Pembayaran')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('metode-pembayaran.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Edit Metode</h1>
                    <p class="text-xs text-gray-500">Perbarui metode pembayaran</p>
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
                    <h3 class="text-sm font-medium text-blue-800">Petunjuk Edit Metode</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Kode</strong> dapat diubah jika belum digunakan dalam transaksi.</li>
                            <li>Pastikan status <strong>Aktif</strong> agar muncul di halaman kasir.</li>
                            <li>Gunakan <strong>Urutan</strong> untuk mengatur posisi tampilan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('metode-pembayaran.update', $metodePembayaran->encrypted_id) }}" method="POST" id="editMetodeForm">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kode -->
                        <div class="field-wrapper">
                            <label for="kode" class="block text-xs font-bold text-gray-700 mb-1">
                                Kode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode" id="kode" value="{{ old('kode', $metodePembayaran->kode) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 font-mono uppercase @error('kode') border-red-500 @enderror"
                                   placeholder="Contoh: QRIS"
                                   onkeyup="this.value = this.value.toUpperCase().replace(/\s/g, '')">
                            @error('kode') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama -->
                        <div class="field-wrapper">
                            <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Metode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $metodePembayaran->nama) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nama') border-red-500 @enderror"
                                   placeholder="Contoh: QRIS BCA">
                            @error('nama') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                         <!-- Icon -->
                         <div class="field-wrapper">
                            <label for="icon" class="block text-xs font-bold text-gray-700 mb-1">
                                Icon Class (Tabler)
                            </label>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <input type="text" name="icon" id="icon" value="{{ old('icon', $metodePembayaran->icon) }}"
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('icon') border-red-500 @enderror"
                                        placeholder="Contoh: ti-qrcode">
                                </div>
                                <div class="flex items-center justify-center w-10 h-9 bg-gray-100 rounded-lg border border-gray-200 text-gray-500" id="icon-preview">
                                    <i class="ti {{ $metodePembayaran->icon ?? 'ti-help' }}"></i>
                                </div>
                            </div>
                            @error('icon') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Urutan -->
                        <div class="field-wrapper">
                            <label for="urutan" class="block text-xs font-bold text-gray-700 mb-1">
                                Urutan
                            </label>
                            <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $metodePembayaran->urutan) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('urutan') border-red-500 @enderror"
                                   min="0">
                        </div>

                        <!-- Status -->
                        <div class="field-wrapper">
                            <label for="status" class="block text-xs font-bold text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30">
                                <option value="1" {{ old('status', $metodePembayaran->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', $metodePembayaran->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="field-wrapper">
                        <label for="deskripsi" class="block text-xs font-bold text-gray-700 mb-1">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="2"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Keterangan singkat tentang metode pembayaran ini">{{ old('deskripsi', $metodePembayaran->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('metode-pembayaran.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
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
            // Icon Preview
            $('#icon').on('input', function() {
                const iconClass = $(this).val();
                if (iconClass && iconClass.startsWith('ti-')) {
                    $('#icon-preview').html(`<i class="ti ${iconClass} text-lg text-blue-600"></i>`);
                } else {
                    $('#icon-preview').html('<i class="ti ti-help text-lg"></i>');
                }
            });
            
             // Trigger check on load
             if ($('#icon').val() && $('#icon').val().startsWith('ti-')) {
                 const iconClass = $('#icon').val();
                 $('#icon-preview').html(`<i class="ti ${iconClass} text-lg text-blue-600"></i>`);
             }

            // Validation Rules
            const validationRules = {
                kode: {
                    required: true,
                    maxLength: 20
                },
                nama: {
                    required: true,
                    maxLength: 100
                },
                deskripsi: {
                    maxLength: 500
                }
            };

            const validationMessages = {
                kode: {
                    required: 'Kode wajib diisi.',
                    maxLength: 'Kode maksimal 20 karakter.'
                },
                nama: {
                    required: 'Nama metode wajib diisi.',
                    maxLength: 'Nama maksimal 100 karakter.'
                },
                deskripsi: {
                    maxLength: 'Deskripsi maksimal 500 karakter.'
                }
            };

            // Real-time validation
            const fieldsToValidate = ['kode', 'nama', 'deskripsi'];

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
            $('#editMetodeForm').on('submit', function(e) {
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
                        Mengupdate...
                    `);
                }
            });
        });
    </script>
@endpush
