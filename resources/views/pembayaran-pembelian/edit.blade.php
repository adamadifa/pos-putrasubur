@extends('layouts.pos')

@section('title', 'Edit Pembayaran Pembelian')
@section('page-title', 'Edit Pembayaran Pembelian')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Edit Pembayaran Pembelian</h2>
                <p class="text-sm text-gray-600">Perbarui informasi pembayaran pembelian</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('pembayaran-pembelian.show', $pembayaranPembelian->encrypted_id) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <i class="ti ti-arrow-left text-lg mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-alert-circle text-lg text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form action="{{ route('pembayaran-pembelian.update', $pembayaranPembelian->encrypted_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Purchase Selection -->
                        <div>
                            <label for="pembelian_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Transaksi Pembelian <span class="text-red-500">*</span>
                            </label>
                            <select name="pembelian_id" id="pembelian_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                <option value="">Pilih Transaksi Pembelian</option>
                                @foreach ($pembelian as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $pembayaranPembelian->pembelian_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->no_faktur }} - {{ $p->supplier->nama }} (Rp {{ number_format($p->total) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pembelian_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Amount -->
                        <div>
                            <label for="jumlah_raw" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Bayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                    Rp
                                </span>
                                <input type="text" name="jumlah_raw" id="jumlah_raw"
                                    value="{{ old('jumlah_raw', number_format($pembayaranPembelian->jumlah_bayar, 0, ',', '.')) }}"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                    placeholder="0" required>
                            </div>
                            @error('jumlah_raw')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $metodePembayaran = [
                                        'tunai' => ['icon' => 'ti ti-cash', 'label' => 'Tunai'],
                                        'transfer' => ['icon' => 'ti ti-transfer', 'label' => 'Transfer'],
                                        'qris' => ['icon' => 'ti ti-qrcode', 'label' => 'QRIS'],
                                        'edc' => ['icon' => 'ti ti-credit-card', 'label' => 'EDC'],
                                    ];
                                @endphp
                                @foreach ($metodePembayaran as $kode => $metode)
                                    <label class="relative">
                                        <input type="radio" name="metode_pembayaran" value="{{ $kode }}"
                                            {{ $pembayaranPembelian->metode_pembayaran == $kode ? 'checked' : '' }}
                                            class="sr-only" required>
                                        <div
                                            class="payment-method-card cursor-pointer border-2 border-gray-200 rounded-lg p-4 text-center transition-all duration-200 hover:border-primary-300">
                                            <i class="{{ $metode['icon'] }} text-2xl text-gray-400 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-700">{{ $metode['label'] }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('metode_pembayaran')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Payment Date -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', $pembayaranPembelian->tanggal->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                required>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="status_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="status_bayar" id="status_bayar" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                <option value="P" {{ $pembayaranPembelian->status_bayar == 'P' ? 'selected' : '' }}>
                                    Pelunasan</option>
                                <option value="D" {{ $pembayaranPembelian->status_bayar == 'D' ? 'selected' : '' }}>DP
                                </option>
                                <option value="A" {{ $pembayaranPembelian->status_bayar == 'A' ? 'selected' : '' }}>
                                    Angsuran</option>
                                <option value="B" {{ $pembayaranPembelian->status_bayar == 'B' ? 'selected' : '' }}>
                                    Batal</option>
                            </select>
                            @error('status_bayar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Tambahkan keterangan pembayaran (opsional)">{{ old('keterangan', $pembayaranPembelian->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Payment Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Pembayaran Saat Ini</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Bukti:</span>
                                    <span class="font-medium">{{ $pembayaranPembelian->no_bukti }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dibuat Oleh:</span>
                                    <span class="font-medium">{{ $pembayaranPembelian->user->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dibuat Pada:</span>
                                    <span
                                        class="font-medium">{{ $pembayaranPembelian->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('pembayaran-pembelian.show', $pembayaranPembelian->encrypted_id) }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i class="ti ti-device-floppy text-lg mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .payment-method-card input[type="radio"]:checked+div {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .payment-method-card input[type="radio"]:checked+div i {
            color: #3b82f6;
        }
    </style>

    <script>
        // Number formatting for jumlah input
        document.getElementById('jumlah_raw').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            e.target.value = value;
        });

        // Payment method card selection
        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-method-card').forEach(function(card) {
                    card.classList.remove('border-primary-500', 'bg-primary-50');
                    card.classList.add('border-gray-200');
                });

                if (this.checked) {
                    const card = this.nextElementSibling;
                    card.classList.remove('border-gray-200');
                    card.classList.add('border-primary-500', 'bg-primary-50');
                }
            });
        });

        // Initialize payment method selection
        document.addEventListener('DOMContentLoaded', function() {
            const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
            if (selectedMethod) {
                const card = selectedMethod.nextElementSibling;
                card.classList.remove('border-gray-200');
                card.classList.add('border-primary-500', 'bg-primary-50');
            }
        });
    </script>
@endsection

