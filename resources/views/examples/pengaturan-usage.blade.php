{{-- 
    CONTOH PENGGUNAAN DATA PENGATURAN UMUM DI VIEW
    File ini menunjukkan berbagai cara menggunakan data pengaturan umum
--}}

@extends('layouts.pos')

@section('title', 'Contoh Penggunaan Pengaturan Umum')
@section('page-title', 'Contoh Penggunaan')

@section('content')
    <div class="space-y-8">
        <!-- Header dengan informasi toko -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Contoh Penggunaan Data Pengaturan Umum</h2>

            <!-- Method 1: Menggunakan variabel $pengaturanUmum (dari Service Provider) -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">1. Menggunakan Variabel $pengaturanUmum</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-900">Nama Toko</h4>
                        <p class="text-blue-700">{{ $pengaturanUmum->nama_toko }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-green-900">Nomor Telepon</h4>
                        <p class="text-green-700">{{ $pengaturanUmum->no_telepon ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-purple-900">Email</h4>
                        <p class="text-purple-700">{{ $pengaturanUmum->email ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-orange-900">Alamat</h4>
                        <p class="text-orange-700">{{ $pengaturanUmum->alamat ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Method 2: Menggunakan Helper Functions -->
            <div class="space-y-4 mt-8">
                <h3 class="text-lg font-semibold text-gray-900">2. Menggunakan Helper Functions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-900">nama_toko()</h4>
                        <p class="text-blue-700">{{ nama_toko() }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-green-900">no_telepon_toko()</h4>
                        <p class="text-green-700">{{ no_telepon_toko() ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-purple-900">email_toko()</h4>
                        <p class="text-purple-700">{{ email_toko() ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-orange-900">alamat_toko()</h4>
                        <p class="text-orange-700">{{ alamat_toko() ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Method 3: Logo Display -->
            <div class="space-y-4 mt-8">
                <h3 class="text-lg font-semibold text-gray-900">3. Menampilkan Logo</h3>
                <div class="flex items-center space-x-6">
                    @if ($pengaturanUmum->logo_url)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Logo Toko</h4>
                            <img src="{{ $pengaturanUmum->logo_url }}" alt="{{ $pengaturanUmum->nama_toko }}"
                                class="w-32 h-32 object-contain border border-gray-200 rounded-lg">
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Logo Toko</h4>
                            <div
                                class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">
                                    {{ strtoupper(substr($pengaturanUmum->nama_toko, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">Helper Function</h4>
                        @if (has_logo_toko())
                            <img src="{{ logo_toko() }}" alt="{{ nama_toko() }}"
                                class="w-32 h-32 object-contain border border-gray-200 rounded-lg">
                        @else
                            <div
                                class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">
                                    {{ strtoupper(substr(nama_toko(), 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Method 4: Conditional Display -->
            <div class="space-y-4 mt-8">
                <h3 class="text-lg font-semibold text-gray-900">4. Conditional Display</h3>
                <div class="space-y-2">
                    @if ($pengaturanUmum->no_telepon)
                        <div class="flex items-center space-x-2 text-green-600">
                            <i class="ti ti-phone"></i>
                            <span>Telepon: {{ $pengaturanUmum->no_telepon }}</span>
                        </div>
                    @endif

                    @if ($pengaturanUmum->email)
                        <div class="flex items-center space-x-2 text-blue-600">
                            <i class="ti ti-mail"></i>
                            <span>Email: {{ $pengaturanUmum->email }}</span>
                        </div>
                    @endif

                    @if ($pengaturanUmum->alamat)
                        <div class="flex items-center space-x-2 text-orange-600">
                            <i class="ti ti-map-pin"></i>
                            <span>Alamat: {{ $pengaturanUmum->alamat }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Method 5: Dalam JavaScript -->
            <div class="space-y-4 mt-8">
                <h3 class="text-lg font-semibold text-gray-900">5. Data dalam JavaScript</h3>
                <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm">
                    <div>// Data pengaturan tersedia di JavaScript</div>
                    <div>const namaToko = '{{ $pengaturanUmum->nama_toko }}';</div>
                    <div>const emailToko = '{{ $pengaturanUmum->email ?? 'null' }}';</div>
                    <div>const logoUrl = '{{ $pengaturanUmum->logo_url ?? 'null' }}';</div>
                </div>
            </div>
        </div>

        <!-- Footer dengan informasi kontak -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Footer dengan Informasi Toko</h3>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-900">{{ $pengaturanUmum->nama_toko }}</h4>
                    @if ($pengaturanUmum->deskripsi)
                        <p class="text-sm text-gray-600">{{ $pengaturanUmum->deskripsi }}</p>
                    @endif
                </div>
                <div class="space-y-1 text-sm text-gray-600">
                    @if ($pengaturanUmum->alamat)
                        <div>{{ $pengaturanUmum->alamat }}</div>
                    @endif
                    @if ($pengaturanUmum->no_telepon)
                        <div>Tel: {{ $pengaturanUmum->no_telepon }}</div>
                    @endif
                    @if ($pengaturanUmum->email)
                        <div>Email: {{ $pengaturanUmum->email }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Contoh penggunaan data pengaturan di JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Data tersedia dari PHP
            const namaToko = '{{ $pengaturanUmum->nama_toko }}';
            const emailToko = '{{ $pengaturanUmum->email ?? 'null' }}';
            const logoUrl = '{{ $pengaturanUmum->logo_url ?? 'null' }}';

            console.log('Nama Toko:', namaToko);
            console.log('Email Toko:', emailToko);
            console.log('Logo URL:', logoUrl);

            // Contoh penggunaan untuk update dinamis
            // document.title = namaToko + ' - Dashboard';
        });
    </script>
@endsection
