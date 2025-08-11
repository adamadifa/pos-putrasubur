@extends('layouts.pos')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Data Pelanggan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pelanggan.index') }}"
                        class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <i class="ti ti-users text-xl text-white"></i>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Edit Pelanggan</h1>
                            <p class="text-gray-500 mt-1">Edit data pelanggan {{ $pelanggan->nama }}</p>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                    <i class="ti ti-info-circle text-lg"></i>
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
                        <i class="ti ti-users text-lg text-blue-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Form Edit Pelanggan</h2>
                </div>
            </div>

            <form action="{{ route('pelanggan.update', $pelanggan->encrypted_id) }}" method="POST" class="p-8"
                id="editPelangganForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    <!-- Nama Pelanggan -->
                    <div class="space-y-2">
                        <label for="nama" class="block text-sm font-semibold text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-user text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $pelanggan->nama) }}"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('nama') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap pelanggan" maxlength="100" required>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Nama lengkap akan ditampilkan di sistem dan laporan
                        </p>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pelanggan (Opsional) -->
                    <div class="space-y-2">
                        <label for="kode_pelanggan" class="block text-sm font-semibold text-gray-700">
                            Kode Pelanggan (opsional)
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-barcode text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="kode_pelanggan" id="kode_pelanggan"
                                value="{{ old('kode_pelanggan', $pelanggan->kode_pelanggan) }}"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('kode_pelanggan') border-red-500 @enderror"
                                placeholder="Contoh: P-0001" maxlength="50">
                        </div>
                        <p class="text-xs text-gray-500">Biarkan kosong untuk tetap menggunakan kode saat ini</p>
                        @error('kode_pelanggan')
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
                                <option value="1"
                                    {{ old('status', $pelanggan->status ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="0"
                                    {{ old('status', $pelanggan->status ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="space-y-2">
                        <label for="nomor_telepon" class="block text-sm font-semibold text-gray-700">
                            Nomor Telepon
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i
                                    class="ti ti-phone text-lg text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="tel" name="nomor_telepon" id="nomor_telepon"
                                value="{{ old('nomor_telepon', $pelanggan->nomor_telepon) }}"
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('nomor_telepon') border-red-500 @enderror"
                                placeholder="08123456789" maxlength="20">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Nomor telepon untuk kontak dan verifikasi
                        </p>
                        @error('nomor_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat (Opsional) -->
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
                                placeholder="Masukkan alamat lengkap pelanggan" maxlength="255">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="ti ti-sparkles text-xs mr-1"></i>
                            Alamat untuk pengiriman dan billing
                        </p>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto (Opsional) -->
                    <div class="space-y-2">
                        <label for="foto" class="block text-sm font-semibold text-gray-700">
                            Foto (opsional)
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*"
                            class="w-full text-sm border border-gray-300 rounded-lg px-4 py-3.5 bg-gray-50 focus:bg-white">
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($pelanggan->foto)
                            <div class="mt-2 text-xs text-gray-500">Foto saat ini:</div>
                            <img src="{{ asset('storage/' . $pelanggan->foto) }}" alt="{{ $pelanggan->nama }}"
                                class="h-16 w-16 rounded-lg object-cover border">
                        @endif
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Preview Pelanggan</h4>
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-4">
                                <i class="ti ti-user text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-base font-medium text-gray-900" id="preview-nama">{{ $pelanggan->nama }}
                                </div>
                                <div class="text-sm text-gray-500" id="preview-kode">Kode:
                                    {{ $pelanggan->kode_pelanggan ?? 'otomatis' }}</div>
                                <div class="text-sm text-gray-500" id="preview-phone">{{ $pelanggan->nomor_telepon }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                    <a href="{{ route('pelanggan.index') }}"
                        class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                        <i class="ti ti-x text-xl mr-3 group-hover:text-gray-600"></i>
                        Batal
                    </a>

                    <div class="flex items-center space-x-4">
                        <button type="reset"
                            class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                            <i class="ti ti-refresh text-xl mr-3 group-hover:text-gray-600"></i>
                            Reset Form
                        </button>
                        <button type="submit" id="submitBtn"
                            class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-base rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                            <i class="ti ti-device-floppy text-xl mr-3"></i>
                            Update Pelanggan
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
                    <h3 class="text-sm font-medium text-blue-800">Tips Edit Pelanggan</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Pastikan data yang diubah tetap akurat dan lengkap</li>
                            <li>Kode pelanggan bersifat opsional</li>
                            <li>Status aktif/nonaktif mempengaruhi akses pelanggan</li>
                            <li>Anda dapat mengunggah foto baru untuk mengganti foto lama</li>
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
            // Update preview in real-time
            $('#nama').on('input', function() {
                const nama = $(this).val() || 'Nama Pelanggan';
                $('#preview-nama').text(nama);
            });

            $('#kode_pelanggan').on('input', function() {
                const kode = $(this).val() || 'otomatis';
                $('#preview-kode').text(`Kode: ${kode}`);
            });

            $('#nomor_telepon').on('input', function() {
                const phone = $(this).val() || '08123456789';
                $('#preview-phone').text(phone);
            });

            // Form submission handler
            $('#editPelangganForm').on('submit', function(e) {
                // Show loading state
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true);
                submitBtn.html(
                    '<i class="ti ti-loader animate-spin text-xl mr-3"></i>Mengupdate...'
                );
            });
        });
    </script>
@endpush
