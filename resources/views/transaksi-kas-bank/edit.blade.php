@extends('layouts.pos')

@section('title', 'Edit Transaksi Kas & Bank')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Transaksi Kas & Bank</h1>
                <p class="text-gray-600">Edit transaksi kas dan bank</p>
            </div>
            <a href="{{ route('transaksi-kas-bank.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 inline mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form action="{{ route('transaksi-kas-bank.update', $transaksi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- No Bukti (Read Only) -->
                        <div>
                            <label for="no_bukti" class="block text-sm font-medium text-gray-700 mb-2">
                                No Bukti
                            </label>
                            <input type="text" value="{{ $transaksi->no_bukti }}" readonly
                                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                            <p class="mt-1 text-xs text-gray-500">Nomor bukti tidak dapat diubah</p>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', $transaksi->tanggal) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 @error('tanggal') border-red-500 @enderror"
                                required>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kas/Bank -->
                        <div>
                            <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kas/Bank <span class="text-red-500">*</span>
                            </label>
                            <select name="kas_bank_id" id="kas_bank_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 @error('kas_bank_id') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kas/Bank</option>
                                @foreach ($kasBankList as $kasBank)
                                    <option value="{{ $kasBank->id }}"
                                        {{ old('kas_bank_id', $transaksi->kas_bank_id) == $kasBank->id ? 'selected' : '' }}>
                                        {{ $kasBank->nama }} - Saldo: Rp
                                        {{ number_format($kasBank->saldo_terkini, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kas_bank_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Transaksi -->
                        <div>
                            <label for="kategori_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori Transaksi <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_transaksi" id="kategori_transaksi"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 @error('kategori_transaksi') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                <option value="PJ"
                                    {{ old('kategori_transaksi', $transaksi->kategori_transaksi) == 'PJ' ? 'selected' : '' }}>
                                    Penjualan</option>
                                <option value="PB"
                                    {{ old('kategori_transaksi', $transaksi->kategori_transaksi) == 'PB' ? 'selected' : '' }}>
                                    Pembelian</option>
                                <option value="MN"
                                    {{ old('kategori_transaksi', $transaksi->kategori_transaksi) == 'MN' ? 'selected' : '' }}>
                                    Manual</option>
                                <option value="TF"
                                    {{ old('kategori_transaksi', $transaksi->kategori_transaksi) == 'TF' ? 'selected' : '' }}>
                                    Transfer</option>
                            </select>
                            @error('kategori_transaksi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Transaksi <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_transaksi" id="jenis_transaksi"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 @error('jenis_transaksi') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="D"
                                    {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'D' ? 'selected' : '' }}>IN
                                </option>
                                <option value="K"
                                    {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'K' ? 'selected' : '' }}>OUT
                                </option>
                            </select>
                            @error('jenis_transaksi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    Rp
                                </span>
                                <input type="number" name="jumlah" id="jumlah"
                                    value="{{ old('jumlah', $transaksi->jumlah) }}"
                                    class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 @error('jumlah') border-red-500 @enderror"
                                    placeholder="0" min="0" step="0.01" required>
                            </div>
                            @error('jumlah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="md:col-span-2">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 @error('keterangan') border-red-500 @enderror"
                                placeholder="Masukkan keterangan transaksi (opsional)">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Perhatian
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Mengubah transaksi ini akan mempengaruhi saldo kas/bank. Pastikan perubahan yang
                                        dilakukan sudah benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('transaksi-kas-bank.show', $transaksi->id) }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors">
                            Update Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Format number input
        document.getElementById('jumlah').addEventListener('input', function(e) {
            let value = e.target.value;
            if (value) {
                // Remove non-numeric characters except decimal point
                value = value.replace(/[^\d.]/g, '');
                // Ensure only one decimal point
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                e.target.value = value;
            }
        });
    </script>
@endsection
