@extends('layouts.pos')

@section('title', 'Edit Penyesuaian Stok')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Penyesuaian Stok</h1>
            <a href="{{ route('penyesuaian-stok.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penyesuaian-stok.update', $penyesuaianStok) }}" method="POST" id="penyesuaianForm">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penyesuaian Stok</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="kode_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Penyesuaian
                        </label>
                        <input type="text" value="{{ $penyesuaianStok->kode_penyesuaian }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>

                    <div>
                        <label for="tanggal_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Penyesuaian <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                            <input type="text" name="tanggal_penyesuaian" id="tanggal_penyesuaian"
                                value="{{ old('tanggal_penyesuaian', $penyesuaianStok->tanggal_penyesuaian->format('Y-m-d')) }}"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent flatpickr-input"
                                placeholder="Pilih tanggal penyesuaian..." required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Produk <span class="text-red-500">*</span>
                        </label>
                        <select name="produk_id" id="produk_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            onchange="updateStokSistem()" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}" data-stok="{{ $produk->stok }}"
                                    data-satuan="{{ $produk->satuan->nama }}"
                                    {{ old('produk_id', $penyesuaianStok->produk_id) == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }} ({{ $produk->satuan->nama }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stok Saat Ini</label>
                        <input type="number" id="stok_saat_ini"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="jumlah_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Penyesuaian <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_penyesuaian" id="jumlah_penyesuaian"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Masukkan jumlah penyesuaian (+/-)"
                            value="{{ old('jumlah_penyesuaian', $penyesuaianStok->jumlah_penyesuaian) }}"
                            onchange="calculateStokSesudah()" required>
                        <p class="text-xs text-gray-500 mt-1">Gunakan + untuk menambah, - untuk mengurangi</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stok Setelah Penyesuaian</label>
                        <input type="number" id="stok_sesudah"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Masukkan keterangan penyesuaian stok">{{ old('keterangan', $penyesuaianStok->keterangan) }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('penyesuaian-stok.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Update Penyesuaian Stok
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for date input
            flatpickr("#tanggal_penyesuaian", {
                dateFormat: "Y-m-d",
                locale: "id",
                placeholder: "Pilih tanggal penyesuaian...",
                allowInput: true,
                clickOpens: true,
                theme: "light",
                defaultDate: "{{ old('tanggal_penyesuaian', $penyesuaianStok->tanggal_penyesuaian->format('Y-m-d')) }}"
            });

            function updateStokSistem() {
                const produkSelect = document.getElementById('produk_id');
                const stokSaatIniInput = document.getElementById('stok_saat_ini');
                const selectedOption = produkSelect.options[produkSelect.selectedIndex];

                if (selectedOption && selectedOption.value) {
                    stokSaatIniInput.value = selectedOption.dataset.stok || 0;
                    calculateStokSesudah();
                } else {
                    stokSaatIniInput.value = '';
                    document.getElementById('stok_sesudah').value = '';
                }
            }

            function calculateStokSesudah() {
                const stokSaatIni = parseInt(document.getElementById('stok_saat_ini').value) || 0;
                const jumlahPenyesuaian = parseInt(document.getElementById('jumlah_penyesuaian').value) || 0;
                const stokSesudah = stokSaatIni + jumlahPenyesuaian;

                const stokSesudahInput = document.getElementById('stok_sesudah');
                stokSesudahInput.value = stokSesudah;

                // Add color coding
                if (stokSesudah < 0) {
                    stokSesudahInput.className = stokSesudahInput.className.replace(
                        /bg-(gray|green|red|yellow)-100/, 'bg-red-100');
                } else if (jumlahPenyesuaian > 0) {
                    stokSesudahInput.className = stokSesudahInput.className.replace(
                        /bg-(gray|green|red|yellow)-100/, 'bg-green-100');
                } else if (jumlahPenyesuaian < 0) {
                    stokSesudahInput.className = stokSesudahInput.className.replace(
                        /bg-(gray|green|red|yellow)-100/, 'bg-yellow-100');
                } else {
                    stokSesudahInput.className = stokSesudahInput.className.replace(
                        /bg-(gray|green|red|yellow)-100/, 'bg-gray-100');
                }
            }

            // Initialize on page load
            updateStokSistem();

            // Make functions global
            window.updateStokSistem = updateStokSistem;
            window.calculateStokSesudah = calculateStokSesudah;
        });
    </script>
@endsection
