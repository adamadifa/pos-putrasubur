@extends('layouts.pos')

@section('title', 'Detail Transaksi Kas & Bank')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi Kas & Bank</h1>
                <p class="text-gray-600">Detail informasi transaksi kas dan bank</p>
            </div>
            <div class="flex space-x-3">
                @if ($transaksiKasBankKasBank->referensi_tipe == 'MN')
                    <a href="{{ route('transaksi-kas-bank.edit', $transaksiKasBankKasBank->id) }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 inline mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('transaksi-kas-bank.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 inline mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Transaksi</h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Bukti</label>
                            <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $transaksiKasBank->no_bukti }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaksiKasBank->tanggal)->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kas/Bank</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $transaksiKasBank->kasBank->nama }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori Transaksi</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $transaksiKasBank->kategori_transaksi == 'PJ' ? 'Penjualan' : ($transaksiKasBank->kategori_transaksi == 'PB' ? 'Pembelian' : ($transaksiKasBank->kategori_transaksi == 'MN' ? 'Manual' : 'Transfer')) }}
                            </p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <p class="mt-1">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaksiKasBank->jenis_transaksi == 'D' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaksiKasBank->jenis_transaksi == 'D' ? 'IN' : 'OUT' }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <p class="mt-1 text-lg font-bold text-gray-900">Rp
                                {{ number_format($transaksiKasBank->jumlah, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $transaksiKasBank->keterangan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Referensi</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaksiKasBank->referensi_tipe == 'MN' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($transaksiKasBank->referensi_tipe) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Tambahan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat Pada</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaksiKasBank->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Terakhir Diupdate</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transaksiKasBank->updated_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        @if ($transaksiKasBank->referensi_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Referensi</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaksiKasBank->referensi_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @if ($transaksiKasBank->referensi_tipe == 'MN')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Transaksi ini dapat diedit karena merupakan transaksi manual
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('transaksi-kas-bank.edit', $transaksiKasBank->id) }}"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                    Edit Transaksi
                                </a>
                                <form action="{{ route('transaksi-kas-bank.destroy', $transaksiKasBank->id) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                        Hapus Transaksi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Transaksi Otomatis
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Transaksi ini dibuat secara otomatis oleh sistem dan tidak dapat diedit atau
                                            dihapus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
