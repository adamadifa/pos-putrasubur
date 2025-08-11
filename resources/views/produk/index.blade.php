@extends('layouts.pos')

@section('title', 'Produk')
@section('page-title', 'Kelola Produk')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Produk</h2>
                <p class="text-sm text-gray-600">Kelola semua produk dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Import Excel
                </button>
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Produk
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-check-circle text-lg text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Dismiss</span>
                                <i class="ti ti-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                class="inline-flex bg-red-50 rounded-lg p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Dismiss</span>
                                <i class="ti ti-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Produk Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-package text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Total Produk</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalProduk }}</p>
                            <p class="text-sm text-blue-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-lg mr-1"></i>
                                Semua produk
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Produk Tersedia Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-store text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-emerald-100">Produk Tersedia</h3>
                            <p class="text-3xl font-bold text-white">{{ $produkTersedia }}</p>
                            <p class="text-sm text-emerald-200 flex items-center mt-1">
                                <i class="ti ti-trending-up text-lg mr-1"></i>
                                {{ $totalProduk > 0 ? round(($produkTersedia / $totalProduk) * 100) : 0 }}% dari total
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Stok Menipis Card -->
            <div
                class="relative bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-alert-triangle text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-amber-100">Stok Menipis</h3>
                            <p class="text-3xl font-bold text-white">{{ $produkMenipis }}</p>
                            <p class="text-sm text-amber-200 flex items-center mt-1">
                                @if ($produkMenipis > 0)
                                    <i class="ti ti-alert-circle text-lg mr-1"></i>
                                    Perlu restock
                                @else
                                    <i class="ti ti-check-circle text-lg mr-1"></i>
                                    Aman
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Stok Habis Card -->
            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-rose-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-warehouse text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Stok Habis</h3>
                            <p class="text-3xl font-bold text-white">{{ $produkHabis }}</p>
                            <p class="text-sm text-red-200 flex items-center mt-1">
                                @if ($produkHabis > 0)
                                    <i class="ti ti-x text-lg mr-1"></i>
                                    Tidak tersedia
                                @else
                                    <i class="ti ti-check text-lg mr-1"></i>
                                    Semua tersedia
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary-50 rounded-lg">
                            <i class="ti ti-package text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Produk</h3>
                            <p class="text-sm text-gray-500">Menampilkan {{ $produk->count() }} dari
                                {{ $produk->total() }} produk</p>
                        </div>
                    </div>

                    <!-- Table Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- View Options -->
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button class="px-3 py-1.5 bg-white text-gray-900 rounded-md shadow-sm text-sm font-medium">
                                <i class="ti ti-table text-lg inline mr-1"></i>
                                Tabel
                            </button>
                            <button
                                class="px-3 py-1.5 text-gray-600 rounded-md text-sm font-medium hover:bg-white hover:text-gray-900 transition-all">
                                <i class="ti ti-layout-grid text-lg inline mr-1"></i>
                                Grid
                            </button>
                        </div>

                        <!-- Export & Print -->
                        <div class="flex items-center space-x-2">
                            <button
                                class="p-2.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all"
                                title="Export Excel">
                                <i class="ti ti-download text-lg"></i>
                            </button>
                            <button
                                class="p-2.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                title="Print">
                                <i class="ti ti-printer text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" action="{{ route('produk.index') }}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Main Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama produk, kode, atau kategori..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>
                        </div>

                        <!-- Quick Filters -->
                        <div class="flex items-center gap-3">
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button type="button" onclick="setQuickFilter('')"
                                    class="px-3 py-1.5 text-sm font-medium {{ !request('status') ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Semua</button>
                                <button type="button" onclick="setQuickFilter('tersedia')"
                                    class="px-3 py-1.5 text-sm font-medium {{ request('status') == 'tersedia' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Tersedia</button>
                                <button type="button" onclick="setQuickFilter('habis')"
                                    class="px-3 py-1.5 text-sm font-medium {{ request('status') == 'habis' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Habis</button>
                            </div>

                            <button type="submit"
                                class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="ti ti-search text-lg inline mr-1.5"></i>
                                Cari
                            </button>
                        </div>
                    </div>

                    <!-- Hidden input for status filter -->
                    <input type="hidden" name="status" id="status-filter" value="{{ request('status') }}">
                </form>
            </div>

            <div>
                <table class="w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <tr>
                            <th class="px-3 py-4 text-center w-12">
                                <input type="checkbox"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 focus:ring-offset-0">
                            </th>
                            <th class="px-2 py-4 text-center w-16">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">No.</span>
                            </th>
                            <th class="px-3 py-4 text-left w-64">
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Produk</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-3 py-4 text-left w-32">
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-3 py-4 text-right w-28">
                                <div class="flex items-center justify-end space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Harga</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-20">
                                <div class="flex items-center justify-center space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Stok</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-20">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Min. Stok</span>
                            </th>
                            <th class="px-2 py-4 text-center w-24">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-3 py-4 text-center w-32">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($produk as $index => $item)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30 transition-all duration-200 group">
                                <td class="px-3 py-5 whitespace-nowrap w-12">
                                    <input type="checkbox" value="{{ $item->id }}"
                                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 focus:ring-2">
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap w-16">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full">
                                        <span class="text-xs font-bold text-primary-700">
                                            {{ ($produk->currentPage() - 1) * $produk->perPage() + $index + 1 }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap w-64">
                                    <div class="flex items-center">
                                        <div
                                            class="h-14 w-14 flex-shrink-0 relative group-hover:scale-105 transition-transform duration-200">
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}"
                                                    alt="{{ $item->nama_produk }}"
                                                    class="h-14 w-14 rounded-xl object-cover shadow-sm ring-1 ring-gray-200">
                                            @else
                                                <div
                                                    class="h-14 w-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm ring-1 ring-gray-200">
                                                    <i class="ti ti-photo text-2xl text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div
                                                class="text-sm font-semibold text-gray-900 group-hover:text-primary-700 transition-colors">
                                                {{ $item->nama_produk }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 flex items-center space-x-2">
                                                <span
                                                    class="px-2 py-1 bg-gray-100 rounded-md font-mono text-xs">{{ $item->kode_produk }}</span>
                                                <span class="text-gray-300">•</span>
                                                <span class="font-medium">{{ $item->satuan->nama }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap w-32">
                                    @php
                                        $kategoriColors = [
                                            'Rempah-Rempah' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'Hasil Perkebunan' => 'bg-green-100 text-green-800 border-green-200',
                                            'Biji-Bijian' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Umbi-Umbian' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'Buah Kering' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                        $kategoriColor =
                                            $kategoriColors[$item->kategori->nama] ??
                                            'bg-blue-100 text-blue-800 border-blue-200';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $kategoriColor }}">
                                        {{ $item->kategori->nama }}
                                    </span>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap text-right w-28">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->harga_format }}</div>
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap text-end w-20">
                                    <div class="flex flex-col items-end">
                                        <div
                                            class="text-sm font-bold {{ $item->stok <= $item->stok_minimal ? 'text-red-600' : ($item->stok <= $item->stok_minimal * 2 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ formatQuantity($item->stok) }}
                                        </div>
                                        @if ($item->stok <= $item->stok_minimal)
                                            <div class="flex items-center mt-1">
                                                <i class="ti ti-alert-triangle text-xs text-red-500 mr-1"></i>
                                                <span class="text-xs text-red-600 font-medium">Low</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap text-end w-20">
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ formatQuantity($item->stok_minimal) }}
                                    </div>
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap text-center w-24">
                                    @php
                                        $statusConfig = match ($item->status_stok) {
                                            'habis' => [
                                                'class' => 'bg-red-100 text-red-800 border-red-200',
                                                'text' => 'Habis',
                                                'icon' => '<i class="ti ti-x text-xs"></i>',
                                            ],
                                            'menipis' => [
                                                'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'text' => 'Menipis',
                                                'icon' => '<i class="ti ti-alert-triangle text-xs"></i>',
                                            ],
                                            'tersedia' => [
                                                'class' => 'bg-green-100 text-green-800 border-green-200',
                                                'text' => 'Tersedia',
                                                'icon' => '<i class="ti ti-check text-xs"></i>',
                                            ],
                                            default => [
                                                'class' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                'text' => 'Unknown',
                                                'icon' => '',
                                            ],
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold border {{ $statusConfig['class'] }}">
                                        {!! $statusConfig['icon'] !!}
                                        <span class="ml-1">{{ $statusConfig['text'] }}</span>
                                    </span>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap text-center w-32">
                                    <div class="flex items-center justify-center space-x-1">
                                        <!-- View Button -->
                                        <a href="{{ route('produk.show', $item->id) }}"
                                            class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-eye text-xs"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('produk.edit', $item->id) }}"
                                            class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-edit text-xs"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button"
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_produk }}')"
                                            class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="ti ti-package-off text-5xl mx-auto mb-4 text-gray-400"></i>
                                        <p class="text-lg font-medium">Tidak ada produk ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau tambah produk baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($produk->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($produk->previousPageUrl())
                            <a href="{{ $produk->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @endif

                        @if ($produk->nextPageUrl())
                            <a href="{{ $produk->nextPageUrl() }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @else
                            <span
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                                Selanjutnya
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $produk->firstItem() ?? 0 }}</span> sampai
                                <span class="font-medium">{{ $produk->lastItem() ?? 0 }}</span> dari
                                <span class="font-medium">{{ $produk->total() }}</span> produk
                            </p>
                        </div>
                        <div>
                            {{ $produk->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom SweetAlert Styling */
        .swal2-popup {
            border-radius: 16px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-title {
            color: #1f2937 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }

        .swal2-content {
            color: #6b7280 !important;
            font-size: 1rem !important;
        }

        .swal2-confirm-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            transition: all 0.2s ease !important;
        }

        .swal2-confirm-delete:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3) !important;
        }

        .swal2-cancel-delete {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            transition: all 0.2s ease !important;
        }

        .swal2-cancel-delete:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3) !important;
        }

        .swal2-icon {
            border-width: 3px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Quick filter function
        function setQuickFilter(status) {
            document.getElementById('status-filter').value = status;
            // Submit the main search form
            document.querySelector('form[action="{{ route('produk.index') }}"]').submit();
        }

        // Confirm delete function with SweetAlert
        function confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus produk "${productName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm-delete',
                    cancelButton: 'swal2-cancel-delete'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('produk.destroy', '') }}/${productId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            let bgColor, icon;

            switch (type) {
                case 'error':
                    bgColor = 'bg-red-500';
                    icon = `<i class="ti ti-alert-circle text-lg mr-2"></i>`;
                    break;
                case 'success':
                    bgColor = 'bg-green-500';
                    icon = `<i class="ti ti-check text-lg mr-2"></i>`;
                    break;
                case 'info':
                    bgColor = 'bg-blue-500';
                    icon = `<i class="ti ti-loader text-lg mr-2 animate-spin"></i>`;
                    break;
                default:
                    bgColor = 'bg-blue-500';
                    icon = `<i class="ti ti-info-circle text-lg mr-2"></i>`;
            }

            // Create notification element
            const notification = $(`
                <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 notification transform transition-all duration-300 translate-x-full">
                    <div class="flex items-center">
                        ${icon}
                        ${message}
                    </div>
                </div>
            `);

            // Add to body
            $('body').append(notification);

            // Animate in
            setTimeout(() => {
                notification.removeClass('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(function() {
                notification.addClass('translate-x-full');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkbox functionality
            const selectAllCheckbox = document.querySelector('thead input[type="checkbox"]');
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            // Individual checkbox change
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll(
                        'tbody input[type="checkbox"]:checked').length;
                    selectAllCheckbox.checked = checkedCount === rowCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount <
                        rowCheckboxes.length;
                });
            });

            // Show success toast notification after page load
            @if (session('success'))
                setTimeout(function() {
                    showNotification('{{ session('success') }}', 'success');
                }, 500); // Small delay to ensure DOM is ready
            @endif

            // Show error toast notification after page load
            @if (session('error'))
                setTimeout(function() {
                    showNotification('{{ session('error') }}', 'error');
                }, 500); // Small delay to ensure DOM is ready
            @endif
        });
    </script>
@endpush
