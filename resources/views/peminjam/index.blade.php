@extends('layouts.pos')

@section('title', 'Peminjam')
@section('page-title', 'Kelola Peminjam')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Peminjam</h2>
                <p class="text-sm text-gray-600">Kelola semua peminjam dalam sistem</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('peminjam.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Peminjam
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <i class="ti ti-check-circle text-lg text-green-400 mr-3"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <i class="ti ti-alert-circle text-lg text-red-400 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-users text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-blue-100">Total Peminjam</h3>
                        <p class="text-3xl font-bold">{{ $totalPeminjam ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-user-check text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-green-100">Peminjam Aktif</h3>
                        <p class="text-3xl font-bold">{{ $peminjamAktif ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-user-x text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-100">Peminjam Nonaktif</h3>
                        <p class="text-3xl font-bold">{{ $peminjamNonaktif ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <form method="GET" action="{{ route('peminjam.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, kode, atau nomor telepon..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                            <i class="ti ti-search mr-2"></i>Cari
                        </button>
                        <a href="{{ route('peminjam.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjam as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $item->kode_peminjam }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $item->nama }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $item->nomor_telepon ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-500">{{ $item->alamat ? \Illuminate\Support\Str::limit($item->alamat, 50) : '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('peminjam.show', $item->encrypted_id) }}"
                                            class="text-blue-600 hover:text-blue-900 p-2" title="Detail">
                                            <i class="ti ti-eye text-lg"></i>
                                        </a>
                                        <a href="{{ route('peminjam.edit', $item->encrypted_id) }}"
                                            class="text-orange-600 hover:text-orange-900 p-2" title="Edit">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        @if ($item->pinjaman->count() == 0)
                                            <form action="{{ route('peminjam.destroy', $item->encrypted_id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjam ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-2"
                                                    title="Hapus">
                                                    <i class="ti ti-trash text-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="ti ti-inbox text-4xl mb-2"></i>
                                        <p class="text-sm">Tidak ada data peminjam</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($peminjam->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $peminjam->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
