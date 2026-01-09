@extends('layouts.pos')

@section('title', 'Edit Peminjam')
@section('page-title', 'Edit Peminjam')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('peminjam.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Edit Peminjam</h1>
                    <p class="text-xs text-gray-500">Perbarui data peminjam</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex">
                    <i class="ti ti-alert-circle text-red-400 mr-3 mt-0.5"></i>
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
                    <i class="ti ti-info-circle text-blue-400 mt-0.5"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Pembaruan Data</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Pastikan data yang diubah sudah sesuai. Kode peminjam tidak dapat diubah secara manual jika sudah terdaftar.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('peminjam.update', $peminjam->encrypted_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4">
                    <!-- Kode Peminjam (Read Only) -->
                    <div class="field-wrapper">
                        <label for="kode_peminjam" class="block text-xs font-bold text-gray-700 mb-1">
                            Kode Peminjam <span class="text-gray-400 font-normal">(Tidak dapat diubah)</span>
                        </label>
                        <input type="text" name="kode_peminjam" id="kode_peminjam" value="{{ old('kode_peminjam', $peminjam->kode_peminjam) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed @error('kode_peminjam') border-red-500 @enderror"
                            readonly>
                        @error('kode_peminjam') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama -->
                    <div class="field-wrapper">
                        <label for="nama" class="block text-xs font-bold text-gray-700 mb-1">
                            Nama Peminjam <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $peminjam->nama) }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('nama') border-red-500 @enderror"
                            placeholder="Contoh: Budi Susanto" required>
                        @error('nama') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="field-wrapper">
                        <label for="nomor_telepon" class="block text-xs font-bold text-gray-700 mb-1">
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="ti ti-phone text-xs"></i>
                            </span>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $peminjam->nomor_telepon) }}"
                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('nomor_telepon') border-red-500 @enderror"
                                placeholder="Contoh: 08123456789">
                        </div>
                        @error('nomor_telepon') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div class="field-wrapper">
                        <label for="status" class="block text-xs font-bold text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('status') border-red-500 @enderror">
                            <option value="1" {{ old('status', $peminjam->status) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $peminjam->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="field-wrapper">
                        <label for="alamat" class="block text-xs font-bold text-gray-700 mb-1">
                            Alamat Lengkap
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('alamat') border-red-500 @enderror"
                            placeholder="Alamat lengkap peminjam">{{ old('alamat', $peminjam->alamat) }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="field-wrapper">
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 mb-1">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('keterangan') border-red-500 @enderror"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $peminjam->keterangan) }}</textarea>
                        @error('keterangan') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('peminjam.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
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
            // Simple Client-side validation
            const validationRules = {
                nama: { required: true, maxLength: 100 },
                nomor_telepon: { numeric: true, maxLength: 15 },
                alamat: { maxLength: 255 }
            };

            const validationMessages = {
                nama: { 
                    required: 'Nama peminjam wajib diisi.',
                    maxLength: 'Nama maksimal 100 karakter.' 
                },
                nomor_telepon: { 
                    numeric: 'Nomor telepon harus berupa angka.',
                    maxLength: 'Nomor telepon maksimal 15 angka.'
                },
                alamat: { maxLength: 'Alamat maksimal 255 karakter.' }
            };

            const fieldsToValidate = ['nama', 'nomor_telepon', 'alamat'];

            fieldsToValidate.forEach(function(fieldName) {
                const field = $(`#${fieldName}`);
                let validationTimeout;

                field.on('input blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);
                    
                    // Delay validation on input, immediate on blur
                    if (event.type === 'input') {
                        validationTimeout = setTimeout(() => validateField(fieldName, value), 500);
                    } else {
                        validateField(fieldName, value);
                    }
                });
            });

            function validateField(fieldName, value) {
                const field = $(`#${fieldName}`);
                const fieldContainer = field.closest('.field-wrapper');
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Remove existing states
                field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
                fieldContainer.find('.error-message').remove();

                if (!value && !rules.required) return;

                let isValid = true;
                let errorMessage = '';

                if (rules.required && (!value || value.trim() === '')) {
                    isValid = false;
                    errorMessage = messages.required;
                }
                else if (rules.numeric && value && isNaN(value)) {
                    isValid = false;
                    errorMessage = messages.numeric;
                }
                else if (rules.maxLength && value.length > rules.maxLength) {
                    isValid = false;
                    errorMessage = messages.maxLength;
                }

                if (!isValid) {
                    field.removeClass('border-gray-300 border-green-500').addClass('border-red-500');
                    fieldContainer.append(`<p class="mt-1 text-xs text-red-500 error-message">${errorMessage}</p>`);
                } else if (value) {
                    field.removeClass('border-red-500').addClass('border-green-500');
                }
            }

            // Form Submit Loading State
            $('form').on('submit', function() {
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).html('<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...');
            });
        });
    </script>
@endpush
