@extends('layouts.pos')

@section('title', 'Tambah Pinjaman')
@section('page-title', 'Tambah Pinjaman Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pinjaman.index') }}"
                        class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-red-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <i class="ti ti-file-invoice text-xl text-white"></i>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Tambah Pinjaman
                            </h1>
                            <p class="text-gray-500 mt-1">Input pinjaman baru untuk pelanggan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="ti ti-file-invoice text-lg text-blue-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Form Pinjaman</h2>
                </div>
            </div>

            <form action="{{ route('pinjaman.store') }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="no_pinjaman" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Pinjaman <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_pinjaman" id="no_pinjaman"
                            value="{{ old('no_pinjaman', $noPinjaman) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_pinjaman') border-red-500 @enderror"
                            required>
                        @error('no_pinjaman')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="peminjam_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Peminjam <span class="text-red-500">*</span>
                        </label>
                        <select name="peminjam_id" id="peminjam_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('peminjam_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Peminjam</option>
                            @foreach ($peminjam as $pmj)
                                <option value="{{ $pmj->id }}" {{ old('peminjam_id') == $pmj->id ? 'selected' : '' }}>
                                    {{ $pmj->kode_peminjam }} - {{ $pmj->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('peminjam_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pinjaman <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror"
                            required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_pinjaman" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pinjaman <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jumlah_pinjaman" id="jumlah_pinjaman"
                            value="{{ old('jumlah_pinjaman') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_pinjaman') border-red-500 @enderror"
                            placeholder="0" required>
                        @error('jumlah_pinjaman')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('pinjaman.index') }}"
                        class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Pinjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Format currency input
            document.getElementById('jumlah_pinjaman').addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    e.target.value = new Intl.NumberFormat('id-ID').format(value);
                }
            });

            // Format before submit - keep the formatted value for display, but the controller will clean it
            document.querySelector('form').addEventListener('submit', function(e) {
                // Let the controller handle the cleaning
            });
        </script>
    @endpush
@endsection
