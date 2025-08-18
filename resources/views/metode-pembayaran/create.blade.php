@extends('layouts.pos')

@section('title', 'Tambah Metode Pembayaran')
@section('page-title', 'Tambah Metode Pembayaran Baru')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow border">
            <div class="px-6 py-4 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('metode-pembayaran.index') }}"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Tambah Metode Pembayaran</h1>
                            <p class="text-sm text-gray-500">Buat metode pembayaran baru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow border">
            <form action="{{ route('metode-pembayaran.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Kode -->
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kode') border-red-500 @enderror"
                            placeholder="Contoh: TUNAI, TRANSFER, QRIS" required>
                        @error('kode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="ti ti-info-circle mr-1"></i>
                            Kode unik untuk metode pembayaran (maksimal 20 karakter)
                        </p>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Metode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                            placeholder="Contoh: Tunai, Transfer Bank, QRIS" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="ti ti-info-circle mr-1"></i>
                            Nama yang akan ditampilkan kepada pengguna
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror"
                            placeholder="Deskripsi singkat tentang metode pembayaran ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="ti ti-info-circle mr-1"></i>
                            Deskripsi opsional untuk menjelaskan metode pembayaran
                        </p>
                    </div>

                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Icon
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $icons = [
                                    'ti-cash' => '💵 Tunai',
                                    'ti-credit-card' => '💳 Kartu',
                                    'ti-device-mobile' => '📱 Mobile',
                                    'ti-building-bank' => '🏦 Bank',
                                    'ti-wallet' => '👛 Wallet',
                                    'ti-receipt' => '🧾 Receipt',
                                    'ti-coins' => '🪙 Coins',
                                    'ti-transfer' => '🔄 Transfer',
                                ];
                            @endphp
                            @foreach ($icons as $icon => $label)
                                <label
                                    class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="icon" value="{{ $icon }}"
                                        {{ old('icon') === $icon ? 'checked' : '' }}
                                        class="mr-2 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="ti ti-info-circle mr-1"></i>
                            Pilih icon yang sesuai dengan metode pembayaran
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="ti ti-info-circle mr-1"></i>
                            Metode pembayaran aktif akan tersedia untuk dipilih
                        </p>
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan
                        </label>
                        <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 0) }}" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('urutan') border-red-500 @enderror"
                            placeholder="0">
                        @error('urutan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="ti ti-info-circle mr-1"></i>
                            Urutan tampilan (angka lebih kecil akan ditampilkan terlebih dahulu)
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('metode-pembayaran.index') }}"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="ti ti-arrow-left mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="ti ti-device-floppy mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
