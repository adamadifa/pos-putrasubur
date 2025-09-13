@extends('layouts.pos')

@section('title', 'Edit Pengaturan Umum')
@section('page-title', 'Edit Pengaturan Umum')

@section('content')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pengaturan-umum.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Edit Pengaturan Umum
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">Perbarui informasi toko Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <form action="{{ route('pengaturan-umum.update', $pengaturanUmumModel) }}" method="POST"
                    enctype="multipart/form-data" id="pengaturanForm">
                    @csrf
                    @method('PUT')
                    <div class="p-8 space-y-8">
                        <!-- Informasi Dasar -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-100">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-3 bg-blue-100 rounded-xl">
                                    <i class="ti ti-building-store text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Informasi Dasar</h2>
                                    <p class="text-sm text-gray-600">Update informasi utama toko Anda</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Nama Toko -->
                                <div class="space-y-3">
                                    <label for="nama_toko" class="block text-sm font-semibold text-gray-700">
                                        <i class="ti ti-building-store text-blue-600 mr-1"></i>
                                        Nama Toko <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i
                                                class="ti ti-building-store text-gray-400 group-hover:text-blue-500 transition-colors text-lg"></i>
                                        </div>
                                        <input type="text" name="nama_toko" id="nama_toko"
                                            class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-200 bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 @error('nama_toko') border-red-500 ring-4 ring-red-100 @enderror"
                                            placeholder="Masukkan nama toko"
                                            value="{{ old('nama_toko', $pengaturanUmumModel->nama_toko) }}">
                                    </div>
                                    @error('nama_toko')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="ti ti-alert-circle text-red-500 mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- No Telepon -->
                                <div class="space-y-3">
                                    <label for="no_telepon" class="block text-sm font-semibold text-gray-700">
                                        <i class="ti ti-phone text-green-600 mr-1"></i>
                                        Nomor Telepon
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i
                                                class="ti ti-phone text-gray-400 group-hover:text-green-500 transition-colors text-lg"></i>
                                        </div>
                                        <input type="text" name="no_telepon" id="no_telepon"
                                            class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-200 bg-white focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-100 @error('no_telepon') border-red-500 ring-4 ring-red-100 @enderror"
                                            placeholder="Contoh: 081234567890"
                                            value="{{ old('no_telepon', $pengaturanUmumModel->no_telepon) }}">
                                    </div>
                                    @error('no_telepon')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="ti ti-alert-circle text-red-500 mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="space-y-3">
                                    <label for="email" class="block text-sm font-semibold text-gray-700">
                                        <i class="ti ti-mail text-purple-600 mr-1"></i>
                                        Email
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i
                                                class="ti ti-mail text-gray-400 group-hover:text-purple-500 transition-colors text-lg"></i>
                                        </div>
                                        <input type="email" name="email" id="email"
                                            class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-200 bg-white focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 @error('email') border-red-500 ring-4 ring-red-100 @enderror"
                                            placeholder="Contoh: info@tokosaya.com"
                                            value="{{ old('email', $pengaturanUmumModel->email) }}">
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="ti ti-alert-circle text-red-500 mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="mt-6 space-y-3">
                                <label for="alamat" class="block text-sm font-semibold text-gray-700">
                                    <i class="ti ti-map-pin text-orange-600 mr-1"></i>
                                    Alamat Lengkap
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 pt-4 flex items-start pointer-events-none">
                                        <i
                                            class="ti ti-map-pin text-gray-400 group-hover:text-orange-500 transition-colors text-lg"></i>
                                    </div>
                                    <textarea name="alamat" id="alamat" rows="3"
                                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-200 bg-white focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 resize-none @error('alamat') border-red-500 ring-4 ring-red-100 @enderror"
                                        placeholder="Masukkan alamat lengkap toko">{{ old('alamat', $pengaturanUmumModel->alamat) }}</textarea>
                                </div>
                                @error('alamat')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="ti ti-alert-circle text-red-500 mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="mt-6 space-y-3">
                                <label for="deskripsi" class="block text-sm font-semibold text-gray-700">
                                    <i class="ti ti-note text-gray-600 mr-1"></i>
                                    Deskripsi Toko
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 pt-4 flex items-start pointer-events-none">
                                        <i
                                            class="ti ti-note text-gray-400 group-hover:text-gray-500 transition-colors text-lg"></i>
                                    </div>
                                    <textarea name="deskripsi" id="deskripsi" rows="3"
                                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-200 bg-white focus:bg-white focus:border-gray-500 focus:ring-4 focus:ring-gray-100 resize-none @error('deskripsi') border-red-500 ring-4 ring-red-100 @enderror"
                                        placeholder="Masukkan deskripsi singkat tentang toko">{{ old('deskripsi', $pengaturanUmumModel->deskripsi) }}</textarea>
                                </div>
                                @error('deskripsi')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="ti ti-alert-circle text-red-500 mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Logo Toko -->
                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-8 border border-purple-100">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-3 bg-purple-100 rounded-xl">
                                    <i class="ti ti-photo text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Logo Toko</h2>
                                    <p class="text-sm text-gray-600">Update logo untuk identitas toko Anda</p>
                                </div>
                            </div>

                            <!-- Current Logo -->
                            @if ($pengaturanUmumModel->logo)
                                <div class="mb-6">
                                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="relative group">
                                                <img src="{{ $pengaturanUmumModel->logo_url }}" alt="Logo Saat Ini"
                                                    class="w-24 h-24 object-cover rounded-xl border-2 border-gray-100 shadow-sm group-hover:shadow-md transition-all duration-300">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl flex items-end justify-center pb-2">
                                                    <span
                                                        class="text-white text-xs font-medium bg-black/50 px-2 py-1 rounded-full">Logo
                                                        Aktif</span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Logo Saat Ini</h4>
                                                <p class="text-sm text-gray-600">Logo yang sedang digunakan dalam sistem
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Upload logo baru untuk menggantinya
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <!-- Upload Area -->
                                <div
                                    class="relative border-2 border-dashed border-purple-300 rounded-xl p-8 text-center hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 group">
                                    <div
                                        class="mx-auto w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors duration-300">
                                        <i class="ti ti-upload text-purple-600 text-2xl group-hover:text-purple-700"></i>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ $pengaturanUmumModel->logo ? 'Ganti Logo Toko' : 'Upload Logo Toko' }}</p>
                                        <p class="text-sm text-gray-600">Drag & drop atau klik untuk memilih file</p>
                                        <div class="text-xs text-gray-500 space-y-1">
                                            <p><i class="ti ti-check text-green-500 mr-1"></i>Format: JPEG, PNG, JPG, GIF
                                            </p>
                                            <p><i class="ti ti-check text-green-500 mr-1"></i>Maksimal ukuran: 2MB</p>
                                            <p><i class="ti ti-check text-green-500 mr-1"></i>Rekomendasi: 400x400px</p>
                                        </div>
                                    </div>
                                    <input type="file" name="logo" id="logo" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        onchange="previewLogo(this)">
                                </div>

                                @error('logo')
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <p class="text-sm text-red-600 flex items-center">
                                            <i class="ti ti-alert-circle text-red-500 mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    </div>
                                @enderror

                                <!-- Logo Preview -->
                                <div id="logoPreview" class="hidden">
                                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="relative group">
                                                <img id="previewImage" src="" alt="Logo Preview"
                                                    class="w-24 h-24 object-cover rounded-xl border-2 border-gray-100 shadow-sm group-hover:shadow-md transition-all duration-300">
                                                <button type="button" onclick="removeLogo()"
                                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                                    <i class="ti ti-x text-xs"></i>
                                                </button>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Preview Logo Baru</h4>
                                                <p class="text-sm text-gray-600">Logo baru akan menggantikan logo saat ini
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-slate-50 border-t border-gray-200 rounded-b-xl">
                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i class="ti ti-info-circle text-blue-500"></i>
                                <span>Pastikan semua perubahan sudah benar sebelum menyimpan</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('pengaturan-umum.index') }}"
                                    class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                    <i class="ti ti-x text-lg mr-2"></i>
                                    Batal
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    <i class="ti ti-check text-lg mr-2"></i>
                                    Perbarui Pengaturan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast notification functions
            function showToast(message, type = 'error') {
                const toastContainer = document.getElementById('toast-container');
                const toastId = 'toast-' + Date.now();

                const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                const icon = type === 'error' ? 'ti-alert-circle' : 'ti-check-circle';

                const toast = document.createElement('div');
                toast.id = toastId;
                toast.className =
                    `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
                toast.innerHTML = `
                    <i class="ti ${icon} text-lg"></i>
                    <span class="flex-1">${message}</span>
                    <button onclick="hideToast('${toastId}')" class="text-white hover:text-gray-200 transition-colors">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                `;

                toastContainer.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideToast(toastId);
                }, 5000);
            }

            function hideToast(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }

            // Logo preview function
            window.previewLogo = function(input) {
                const preview = document.getElementById('logoPreview');
                const previewImage = document.getElementById('previewImage');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            };

            // Remove logo function
            window.removeLogo = function() {
                const logoInput = document.getElementById('logo');
                const preview = document.getElementById('logoPreview');

                logoInput.value = '';
                preview.classList.add('hidden');
            };

            // Form submission with loading state
            document.getElementById('pengaturanForm').addEventListener('submit', function() {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>Memperbarui...';
            });

            // Show flash messages as toast
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            // Make functions global
            window.hideToast = hideToast;
        });
    </script>
@endsection
