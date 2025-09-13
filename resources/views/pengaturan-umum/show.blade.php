@extends('layouts.pos')

@section('title', 'Detail Pengaturan Umum')
@section('page-title', 'Detail Pengaturan Umum')

@section('content')
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
                            <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Detail Pengaturan Umum
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">Lihat informasi lengkap pengaturan toko</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('pengaturan-umum.edit', $pengaturanUmumModel) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Informasi Dasar</h2>
                        </div>

                        <div class="space-y-6">
                            <!-- Nama Toko -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-building-store text-blue-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Nama Toko</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $pengaturanUmumModel->nama_toko }}</p>
                                </div>
                            </div>

                            <!-- Nomor Telepon -->
                            @if ($pengaturanUmumModel->no_telepon)
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                        <i class="ti ti-phone text-green-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</h3>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ $pengaturanUmumModel->no_telepon }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Email -->
                            @if ($pengaturanUmumModel->email)
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                        <i class="ti ti-mail text-purple-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                                        <p class="text-lg font-semibold text-gray-900">{{ $pengaturanUmumModel->email }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Alamat -->
                            @if ($pengaturanUmumModel->alamat)
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                                        <i class="ti ti-map-pin text-orange-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Alamat</h3>
                                        <p class="text-lg font-semibold text-gray-900">{{ $pengaturanUmumModel->alamat }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Deskripsi -->
                            @if ($pengaturanUmumModel->deskripsi)
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center">
                                        <i class="ti ti-note text-gray-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h3>
                                        <p class="text-lg font-semibold text-gray-900">{{ $pengaturanUmumModel->deskripsi }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Logo Preview -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-purple-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Logo Toko</h2>
                        </div>

                        @if ($pengaturanUmumModel->logo)
                            <div class="flex justify-center">
                                <img src="{{ $pengaturanUmumModel->logo_url }}" alt="Logo Toko"
                                    class="w-32 h-32 object-cover rounded-xl border-2 border-gray-100 shadow-lg">
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600">Logo saat ini</p>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8">
                                <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                                    <i class="ti ti-photo text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-500 text-center">Belum ada logo</p>
                            </div>
                        @endif
                    </div>

                    <!-- Status Information -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Status</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Status Aktif</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="ti ti-check-circle mr-1"></i>
                                    Aktif
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Dibuat</span>
                                <span
                                    class="text-sm text-gray-900">{{ $pengaturanUmumModel->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-600">Terakhir Diupdate</span>
                                <span
                                    class="text-sm text-gray-900">{{ $pengaturanUmumModel->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi</h2>
                        <div class="space-y-3">
                            <a href="{{ route('pengaturan-umum.edit', $pengaturanUmumModel) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Edit Pengaturan
                            </a>

                            <a href="{{ route('pengaturan-umum.index') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
