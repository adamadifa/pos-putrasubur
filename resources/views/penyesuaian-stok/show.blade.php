@extends('layouts.pos')

@section('title', 'Detail Penyesuaian Stok')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detail Penyesuaian Stok</h1>
            <div class="flex space-x-2">
                <a href="{{ route('penyesuaian-stok.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>

                <a href="{{ route('penyesuaian-stok.edit', $penyesuaianStok) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <!-- Informasi Penyesuaian -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penyesuaian</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kode Penyesuaian</label>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $penyesuaianStok->kode_penyesuaian }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Penyesuaian</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $penyesuaianStok->tanggal_penyesuaian->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Dibuat Oleh</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $penyesuaianStok->user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $penyesuaianStok->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if ($penyesuaianStok->keterangan)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Keterangan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $penyesuaianStok->keterangan }}</p>
                </div>
            @endif
        </div>

        <!-- Detail Produk -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Detail Produk</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Produk</label>
                        <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $penyesuaianStok->produk->nama_produk }}</p>
                        <p class="text-sm text-gray-500">{{ $penyesuaianStok->produk->satuan->nama }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Stok Sebelum Penyesuaian</label>
                        <p class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ number_format($penyesuaianStok->stok_sebelum) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Penyesuaian</label>
                        <p class="mt-1 text-sm font-semibold">
                            @if ($penyesuaianStok->jumlah_penyesuaian > 0)
                                <span
                                    class="text-green-600">+{{ number_format($penyesuaianStok->jumlah_penyesuaian) }}</span>
                            @elseif($penyesuaianStok->jumlah_penyesuaian < 0)
                                <span class="text-red-600">{{ number_format($penyesuaianStok->jumlah_penyesuaian) }}</span>
                            @else
                                <span
                                    class="text-gray-600">{{ number_format($penyesuaianStok->jumlah_penyesuaian) }}</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Stok Setelah Penyesuaian</label>
                        <p class="mt-1 text-sm text-gray-900 font-semibold">
                            {{ number_format($penyesuaianStok->stok_sesudah) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
