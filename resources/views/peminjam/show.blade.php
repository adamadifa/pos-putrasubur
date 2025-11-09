@extends('layouts.pos')

@section('title', 'Detail Peminjam')
@section('page-title', 'Detail Peminjam')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('peminjam.index') }}"
                        class="p-2 text-gray-500 hover:text-white hover:bg-primary-600 rounded-xl transition-all">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $peminjam->nama }}</h1>
                        <p class="text-gray-500 mt-1">Detail informasi peminjam</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('peminjam.edit', $peminjam->encrypted_id) }}"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="ti ti-edit mr-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="ti ti-check-circle text-lg text-green-400 mr-3"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Info Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Peminjam</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-500">Kode Peminjam</label>
                        <p class="text-base font-semibold text-gray-900">{{ $peminjam->kode_peminjam }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Nama</label>
                        <p class="text-base font-semibold text-gray-900">{{ $peminjam->nama }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Nomor Telepon</label>
                        <p class="text-base text-gray-900">{{ $peminjam->nomor_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $peminjam->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $peminjam->status ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </p>
                    </div>
                    @if($peminjam->alamat)
                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-500">Alamat</label>
                            <p class="text-base text-gray-900">{{ $peminjam->alamat }}</p>
                        </div>
                    @endif
                    @if($peminjam->keterangan)
                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-500">Keterangan</label>
                            <p class="text-base text-gray-900">{{ $peminjam->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pinjaman History -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Riwayat Pinjaman ({{ $peminjam->pinjaman->count() }})</h2>
            </div>
            <div class="p-6">
                @if($peminjam->pinjaman->count() > 0)
                    <div class="space-y-4">
                        @foreach($peminjam->pinjaman as $pinjaman)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $pinjaman->no_pinjaman }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $pinjaman->tanggal->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $pinjaman->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-800' : ($pinjaman->status_pembayaran == 'sebagian' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $pinjaman->status_pembayaran_display }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('pinjaman.show', $pinjaman->encrypted_id) }}"
                                            class="text-blue-600 hover:text-blue-900 p-2">
                                            <i class="ti ti-eye text-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="ti ti-inbox text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Belum ada pinjaman</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

