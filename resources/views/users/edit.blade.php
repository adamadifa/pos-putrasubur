@extends('layouts.pos')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('users.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Edit User</h1>
                    <p class="text-xs text-gray-500">Perbarui informasi pengguna</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
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
                    <h3 class="text-sm font-medium text-blue-800">Petunjuk Edit User</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Kosongkan password</strong> jika tidak ingin mengubah password.</li>
                            <li>Pastikan email tetap unik.</li>
                            <li>Perubahan role akan langsung berlaku saat user login ulang.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('users.update', $user->encrypted_id) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama -->
                        <div class="field-wrapper">
                            <label for="name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('name') border-red-500 @enderror"
                                   placeholder="Nama Pengguna">
                            @error('name') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="field-wrapper">
                            <label for="email" class="block text-xs font-bold text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('email') border-red-500 @enderror"
                                   placeholder="email@example.com">
                            @error('email') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="field-wrapper">
                        <label for="role" class="block text-xs font-bold text-gray-700 mb-1">
                            Role Akses <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30">
                            <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                         <p class="mt-1 text-[10px] text-gray-500">
                            * Admin: Full Akses. Manager: Laporan & Manajemen. Kasir: Transaksi saja.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4 mt-2">
                        <!-- Password -->
                        <div class="field-wrapper">
                            <label for="password" class="block text-xs font-bold text-gray-700 mb-1">
                                Password Baru (Opsional)
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('password') border-red-500 @enderror"
                                   placeholder="Kosongkan jika tidak diubah">
                            @error('password') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                         <!-- Confirm Password -->
                        <div class="field-wrapper">
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
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
                name: { required: true, minLength: 3 },
                email: { required: true, email: true },
                password: { minLength: 8 }, // Optional, but if filled, must be 8
                password_confirmation: { match: 'password' }
            };

            const validationMessages = {
                name: { required: 'Nama wajib diisi.', minLength: 'Nama minimal 3 karakter.' },
                email: { required: 'Email wajib diisi.', email: 'Format email tidak valid.' },
                password: { minLength: 'Password minimal 8 karakter.' },
                password_confirmation: { match: 'Password tidak cocok.' }
            };

            // Real-time validation
            const fieldsToValidate = ['name', 'email', 'password', 'password_confirmation'];

            fieldsToValidate.forEach(function(fieldName) {
                const field = $(`#${fieldName}`);
                let validationTimeout;

                field.on('input change blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);

                    // For password in edit, it's not required, so only validate if value exists
                    if (fieldName === 'password' && !value) {
                         // clear error
                         const fieldContainer = field.closest('.field-wrapper');
                         field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
                         fieldContainer.find('.error-message').remove();
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
                
                // If optional and empty, skip (unless it's password_confirmation and password is filled)
                if (fieldName === 'password' && !value) return; 

                let isValid = true;
                let errorMessage = '';

                if (rules.required && (!value || value.toString().trim() === '')) {
                    isValid = false;
                    errorMessage = messages.required;
                } else if (rules.email) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        errorMessage = messages.email;
                    }
                } else if (rules.minLength && value.length < rules.minLength) {
                    isValid = false;
                    errorMessage = messages.minLength;
                } else if (rules.match) {
                    const targetValue = $(`#${rules.match}`).val();
                    if (targetValue && value !== targetValue) {
                        isValid = false;
                        errorMessage = messages.match;
                    }
                }

                if (!isValid) {
                    field.removeClass('border-gray-200 border-green-500 focus:border-blue-500').addClass('border-red-500');
                    const errorHtml = `<p class="mt-1 text-xs text-red-600 flex items-center error-message">${errorMessage}</p>`;
                    fieldContainer.append(errorHtml);
                } else if (value)  {
                    field.removeClass('border-gray-200 border-red-500').addClass('border-green-500');
                }
            }

            // Form Submit
            $('#editUserForm').on('submit', function(e) {
                let hasErrors = false;
                 // Validate all required
                if(!$('#name').val()) { validateField('name', ''); hasErrors = true; }
                if(!$('#email').val()) { validateField('email', ''); hasErrors = true; }
                
                // If password filled, check it
                if($('#password').val()) {
                     if($('#password').val().length < 8) { validateField('password', $('#password').val()); hasErrors = true; }
                     if($('#password').val() !== $('#password_confirmation').val()) { validateField('password_confirmation', $('#password_confirmation').val()); hasErrors = true; }
                }

                if ($('.error-message').length > 0) hasErrors = true;

                if (hasErrors) {
                    e.preventDefault();
                    const firstError = $('.error-message').first().parent().find('input');
                    firstError.focus();
                } else {
                    const submitBtn = $('#submitBtn');
                    submitBtn.prop('disabled', true).html('Menyimpan...');
                }
            });
        });
    </script>
@endpush
