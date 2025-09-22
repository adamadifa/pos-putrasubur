@extends('layouts.pos')

@section('title', 'Pengaturan Umum')
@section('page-title', 'Pengaturan Umum')

@section('content')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="min-h-screen py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="ti ti-settings text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">Pengaturan Umum</h1>
                            <p class="text-sm text-gray-500">Kelola informasi dasar toko Anda</p>
                        </div>
                    </div>
                    <div>
                        @if ($pengaturan)
                            <a href="{{ route('pengaturan-umum.edit', $pengaturan) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="ti ti-edit mr-2"></i>
                                Edit
                            </a>
                        @else
                            <a href="{{ route('pengaturan-umum.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="ti ti-plus mr-2"></i>
                                Buat Pengaturan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if ($pengaturan)
                <!-- Current Settings Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="ti ti-settings text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Aktif</h3>
                                    <p class="text-gray-600 text-sm">Konfigurasi yang sedang digunakan</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Aktif</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Logo Section -->
                            <div>
                                <div class="space-y-4">
                                    <h4 class="text-lg font-medium text-gray-900 flex items-center">
                                        <i class="ti ti-photo text-gray-500 mr-2"></i>
                                        Logo Toko
                                    </h4>
                                    @if ($pengaturan->logo)
                                        <div class="w-32 h-32 mx-auto rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ $pengaturan->logo_url }}" alt="Logo Toko" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div
                                            class="w-32 h-32 mx-auto border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center">
                                            <div class="text-center">
                                                <i class="ti ti-photo text-gray-400 text-2xl mb-2"></i>
                                                <p class="text-xs text-gray-500">Belum ada logo</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Foto Toko Section -->
                            <div class="bg-orange-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <i class="ti ti-camera text-orange-500 mr-2"></i>
                                    Foto Toko
                                </h4>
                                @if ($pengaturan->foto_toko)
                                    <div class="w-full h-48 mx-auto rounded-lg overflow-hidden border border-gray-200">
                                        <img src="{{ $pengaturan->foto_toko_url }}" alt="Foto Toko" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="w-full h-48 mx-auto border-2 border-dashed border-orange-300 rounded-lg bg-orange-50 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="ti ti-camera text-orange-400 text-3xl mb-2"></i>
                                            <p class="text-sm text-orange-600">Belum ada foto toko</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Section -->
                            <div>
                                <div class="space-y-4">
                                    <!-- Nama Toko -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                            <i class="ti ti-building-store text-gray-500 mr-2"></i>
                                            Nama Toko
                                        </h5>
                                        <p class="text-gray-700">{{ $pengaturan->nama_toko }}</p>
                                    </div>

                                    <!-- Contact Info -->
                                    <div class="grid grid-cols-1 gap-3">
                                        @if ($pengaturan->no_telepon)
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <h6 class="font-medium text-gray-900 text-sm mb-1 flex items-center">
                                                    <i class="ti ti-phone text-gray-500 mr-2"></i>
                                                    Telepon
                                                </h6>
                                                <p class="text-gray-700 text-sm">{{ $pengaturan->no_telepon }}</p>
                                            </div>
                                        @endif

                                        @if ($pengaturan->email)
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <h6 class="font-medium text-gray-900 text-sm mb-1 flex items-center">
                                                    <i class="ti ti-mail text-gray-500 mr-2"></i>
                                                    Email
                                                </h6>
                                                <p class="text-gray-700 text-sm">{{ $pengaturan->email }}</p>
                                            </div>
                                        @endif

                                        @if ($pengaturan->no_rekening_koperasi)
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <h6 class="font-medium text-gray-900 text-sm mb-1 flex items-center">
                                                    <i class="ti ti-credit-card text-gray-500 mr-2"></i>
                                                    No. Rekening Koperasi
                                                </h6>
                                                <p class="text-gray-700 text-sm">{{ $pengaturan->no_rekening_koperasi }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Alamat -->
                                    @if ($pengaturan->alamat)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                                <i class="ti ti-map-pin text-gray-500 mr-2"></i>
                                                Alamat
                                            </h5>
                                            <p class="text-gray-700 text-sm">{{ $pengaturan->alamat }}</p>
                                        </div>
                                    @endif

                                    <!-- Deskripsi -->
                                    @if ($pengaturan->deskripsi)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                                <i class="ti ti-note text-gray-500 mr-2"></i>
                                                Deskripsi
                                            </h5>
                                            <p class="text-gray-700 text-sm">{{ $pengaturan->deskripsi }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>Terakhir diperbarui: {{ $pengaturan->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                                <a href="{{ route('pengaturan-umum.edit', $pengaturan) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="ti ti-edit mr-2"></i>
                                    Edit Pengaturan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <i class="ti ti-settings text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Belum Ada Pengaturan</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Mulai dengan mengatur informasi dasar toko Anda seperti nama, alamat, dan logo.
                        </p>
                        <a href="{{ route('pengaturan-umum.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="ti ti-plus mr-2"></i>
                            Buat Pengaturan Pertama
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple toast notification
            function showToast(message, type = 'error') {
                const toastContainer = document.getElementById('toast-container');
                const toastId = 'toast-' + Date.now();

                const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                const icon = type === 'error' ? 'ti-alert-circle' : 'ti-check-circle';

                const toast = document.createElement('div');
                toast.id = toastId;
                toast.className =
                    `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2`;
                toast.innerHTML = `
                    <i class="ti ${icon}"></i>
                    <span>${message}</span>
                    <button onclick="hideToast('${toastId}')" class="ml-2 text-white hover:text-gray-200">
                        <i class="ti ti-x"></i>
                    </button>
                `;

                toastContainer.appendChild(toast);

                // Auto hide after 3 seconds
                setTimeout(() => {
                    hideToast(toastId);
                }, 3000);
            }

            function hideToast(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.remove();
                }
            }

            // Show flash messages
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            window.hideToast = hideToast;
        });
    </script>
@endsection
