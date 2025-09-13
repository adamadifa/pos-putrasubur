@extends('layouts.pos')

@section('title', 'Pengaturan Umum')
@section('page-title', 'Pengaturan Umum')

@section('content')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Pengaturan Umum
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Kelola informasi dasar toko Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if ($pengaturan)
                            <a href="{{ route('pengaturan-umum.edit', $pengaturan) }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                                <i class="ti ti-edit text-lg mr-2"></i>
                                Edit Pengaturan
                            </a>
                        @else
                            <a href="{{ route('pengaturan-umum.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                                <i class="ti ti-plus text-lg mr-2"></i>
                                Buat Pengaturan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if ($pengaturan)
                <!-- Current Settings Card -->
                <div
                    class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
                    <!-- Header -->
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <i class="ti ti-settings text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Pengaturan Aktif</h3>
                                    <p class="text-blue-100 text-sm">Konfigurasi yang sedang digunakan</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-white text-sm font-medium">Aktif</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Logo Section -->
                            <div class="lg:col-span-1">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-2 mb-4">
                                        <div class="p-2 bg-purple-100 rounded-lg">
                                            <i class="ti ti-photo text-purple-600 text-lg"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Logo Toko</h4>
                                    </div>
                                    @if ($pengaturan->logo)
                                        <div class="relative group">
                                            <div
                                                class="w-40 h-40 mx-auto rounded-xl overflow-hidden border-4 border-gray-100 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                                <img src="{{ $pengaturan->logo_url }}" alt="Logo Toko"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            </div>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl flex items-end justify-center pb-4">
                                                <span
                                                    class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded-full">Logo
                                                    Aktif</span>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="w-40 h-40 mx-auto border-2 border-dashed border-gray-300 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center group hover:border-blue-300 hover:bg-blue-50 transition-all duration-300">
                                            <div class="text-center">
                                                <div
                                                    class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors duration-300">
                                                    <i
                                                        class="ti ti-photo text-gray-400 text-2xl group-hover:text-blue-500"></i>
                                                </div>
                                                <p class="text-sm text-gray-500 group-hover:text-blue-600">Belum ada logo
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Info Section -->
                            <div class="lg:col-span-2">
                                <div class="space-y-6">
                                    <!-- Nama Toko -->
                                    <div
                                        class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                        <div class="flex items-center space-x-3 mb-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <i class="ti ti-building-store text-blue-600 text-lg"></i>
                                            </div>
                                            <h5 class="font-semibold text-gray-900">Nama Toko</h5>
                                        </div>
                                        <p class="text-lg font-medium text-gray-900">{{ $pengaturan->nama_toko }}</p>
                                    </div>

                                    <!-- Contact Info Grid -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if ($pengaturan->no_telepon)
                                            <div
                                                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <div class="p-1.5 bg-green-100 rounded-lg">
                                                        <i class="ti ti-phone text-green-600 text-sm"></i>
                                                    </div>
                                                    <h6 class="font-medium text-gray-900 text-sm">Telepon</h6>
                                                </div>
                                                <p class="text-gray-700 font-medium">{{ $pengaturan->no_telepon }}</p>
                                            </div>
                                        @endif

                                        @if ($pengaturan->email)
                                            <div
                                                class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl p-4 border border-purple-100">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <div class="p-1.5 bg-purple-100 rounded-lg">
                                                        <i class="ti ti-mail text-purple-600 text-sm"></i>
                                                    </div>
                                                    <h6 class="font-medium text-gray-900 text-sm">Email</h6>
                                                </div>
                                                <p class="text-gray-700 font-medium">{{ $pengaturan->email }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Alamat -->
                                    @if ($pengaturan->alamat)
                                        <div
                                            class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-6 border border-orange-100">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <div class="p-2 bg-orange-100 rounded-lg">
                                                    <i class="ti ti-map-pin text-orange-600 text-lg"></i>
                                                </div>
                                                <h5 class="font-semibold text-gray-900">Alamat</h5>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">{{ $pengaturan->alamat }}</p>
                                        </div>
                                    @endif

                                    <!-- Deskripsi -->
                                    @if ($pengaturan->deskripsi)
                                        <div
                                            class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-100">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <div class="p-2 bg-gray-100 rounded-lg">
                                                    <i class="ti ti-note text-gray-600 text-lg"></i>
                                                </div>
                                                <h5 class="font-semibold text-gray-900">Deskripsi</h5>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">{{ $pengaturan->deskripsi }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-900">Status: Aktif</span>
                                    </div>
                                    <div class="h-4 w-px bg-gray-300"></div>
                                    <span class="text-xs text-gray-500">
                                        <i class="ti ti-clock text-gray-400 mr-1"></i>
                                        Terakhir diperbarui: {{ $pengaturan->updated_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('pengaturan-umum.edit', $pengaturan) }}"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                                        <i class="ti ti-edit text-lg mr-2"></i>
                                        Edit Pengaturan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-8 py-12 text-center">
                        <div
                            class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-8 relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full animate-pulse">
                            </div>
                            <i class="ti ti-settings text-gray-400 text-4xl relative z-10"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Pengaturan</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                            Mulai dengan mengatur informasi dasar toko Anda seperti nama, alamat, dan logo untuk
                            personalisasi yang lebih baik.
                        </p>
                        <a href="{{ route('pengaturan-umum.create') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-base text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                            <i class="ti ti-plus text-xl mr-3"></i>
                            Buat Pengaturan Pertama
                        </a>
                    </div>
                </div>
            @endif
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
