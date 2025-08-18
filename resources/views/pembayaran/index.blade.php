@extends('layouts.pos')

@section('title', 'Pembayaran')
@section('page-title', 'Kelola Pembayaran')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Pembayaran Penjualan</h2>
                <p class="text-sm text-gray-600">Kelola semua pembayaran penjualan dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Export Excel
                </button>
                <a href="{{ route('pembayaran.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Pembayaran Baru
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

        <!-- Stats Cards - Hari Ini -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Pembayaran Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-receipt text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-blue-100">Total Pembayaran</h3>
                                <p class="text-3xl font-bold text-white">
                                    {{ $pembayaran->where('tanggal', date('Y-m-d'))->count() }}</p>
                                <div class="flex items-center mt-1">
                                    <i class="ti ti-calendar text-blue-300 text-sm mr-1"></i>
                                    <span class="text-xs text-blue-200">Hari ini</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Nilai Pembayaran Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-currency-dollar text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-green-100">Total Nilai</h3>
                                <p class="text-3xl font-bold text-white">
                                    Rp
                                    {{ number_format($pembayaran->where('tanggal', date('Y-m-d'))->sum('jumlah_bayar'), 0, ',', '.') }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                    <span class="text-xs text-green-200">Hari ini</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Tunai Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-cash text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-emerald-100">Pembayaran Tunai</h3>
                                <p class="text-3xl font-bold text-white">
                                    {{ $pembayaran->where('tanggal', date('Y-m-d'))->where('metode_pembayaran', 'tunai')->count() }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i class="ti ti-credit-card text-emerald-300 text-sm mr-1"></i>
                                    <span class="text-xs text-emerald-200">Hari ini</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Transfer Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-transfer text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-purple-100">Pembayaran Transfer</h3>
                                <p class="text-3xl font-bold text-white">
                                    {{ $pembayaran->where('tanggal', date('Y-m-d'))->where('metode_pembayaran', 'transfer')->count() }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i class="ti ti-bank text-purple-300 text-sm mr-1"></i>
                                    <span class="text-xs text-purple-200">Hari ini</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Pembayaran Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-credit-card text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-orange-100">Status Pembayaran</h3>
                                <div class="flex items-center space-x-3 mt-2">
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-white">
                                            {{ $pembayaran->where('tanggal', date('Y-m-d'))->where('status_bayar', 'P')->count() }}
                                        </p>
                                        <p class="text-xs text-orange-200">Pelunasan</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-white">
                                            {{ $pembayaran->where('tanggal', date('Y-m-d'))->where('status_bayar', 'D')->count() }}
                                        </p>
                                        <p class="text-xs text-orange-200">DP</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form method="GET" action="{{ route('pembayaran.index') }}"
                class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-search text-lg text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            placeholder="Cari no. bukti, no. faktur, nama pelanggan...">
                    </div>
                </div>

                <!-- Date From -->
                <div class="lg:w-48">
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <div class="relative">
                        <input type="text" name="tanggal_dari" id="tanggal_dari" readonly
                            value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_dari_hidden" id="tanggal_dari_hidden"
                        value="{{ request('tanggal_dari') }}">
                </div>

                <!-- Date To -->
                <div class="lg:w-48">
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Sampai</label>
                    <div class="relative">
                        <input type="text" name="tanggal_sampai" id="tanggal_sampai" readonly
                            value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_sampai_hidden" id="tanggal_sampai_hidden"
                        value="{{ request('tanggal_sampai') }}">
                </div>

                <!-- Status Filter -->
                <div class="lg:w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="P" {{ request('status') == 'P' ? 'selected' : '' }}>Pelunasan</option>
                        <option value="D" {{ request('status') == 'D' ? 'selected' : '' }}>DP</option>
                        <option value="A" {{ request('status') == 'A' ? 'selected' : '' }}>Angsuran</option>
                    </select>
                </div>

                <!-- Metode Pembayaran Filter -->
                <div class="lg:w-48">
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Metode
                        Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Metode</option>
                        <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai
                        </option>
                        <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>
                            Transfer</option>
                        <option value="qris" {{ request('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS
                        </option>
                        <option value="edc" {{ request('metode_pembayaran') == 'edc' ? 'selected' : '' }}>EDC</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i class="ti ti-filter text-lg mr-2"></i>
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'tanggal_dari', 'tanggal_sampai', 'status', 'metode_pembayaran']))
                        <a href="{{ route('pembayaran.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Modern Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-hash text-blue-600"></i>
                                    <span>No</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-receipt text-blue-600"></i>
                                    <span>No. Bukti</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-calendar text-blue-600"></i>
                                    <span>Tanggal</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-file-invoice text-blue-600"></i>
                                    <span>No. Faktur</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-user text-blue-600"></i>
                                    <span>Pelanggan</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-cash text-blue-600"></i>
                                    <span>Jumlah</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-credit-card text-blue-600"></i>
                                    <span>Metode</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-status-change text-blue-600"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-user-check text-blue-600"></i>
                                    <span>Kasir</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-settings text-blue-600"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pembayaran as $index => $p)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                            <span
                                                class="text-white text-sm font-semibold">{{ ($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-receipt text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $p->no_bukti }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('penjualan.show', $p->penjualan->encrypted_id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                        <i class="ti ti-file-invoice text-xs mr-1"></i>
                                        {{ $p->penjualan->no_faktur }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-user text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $p->penjualan->pelanggan->nama }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($p->metode_pembayaran === 'tunai') bg-green-100 text-green-800
                                        @elseif($p->metode_pembayaran === 'transfer') bg-blue-100 text-blue-800
                                        @elseif($p->metode_pembayaran === 'qris') bg-purple-100 text-purple-800
                                        @elseif($p->metode_pembayaran === 'edc') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="ti ti-credit-card text-xs mr-1"></i>
                                        {{ ucfirst($p->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($p->status_bayar === 'P') bg-green-100 text-green-800
                                        @elseif($p->status_bayar === 'D') bg-blue-100 text-blue-800
                                        @elseif($p->status_bayar === 'A') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="ti ti-status-change text-xs mr-1"></i>
                                        @if ($p->status_bayar === 'P')
                                            Pelunasan
                                        @elseif($p->status_bayar === 'D')
                                            DP
                                        @elseif($p->status_bayar === 'A')
                                            Angsuran
                                        @elseif($p->status_bayar === 'B')
                                            Bayar Sebagian
                                        @else
                                            {{ $p->status_bayar }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-user-check text-indigo-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $p->user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button type="button"
                                            class="view-detail-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200"
                                            title="Lihat Detail" data-pembayaran-id="{{ $p->id }}">
                                            <i class="ti ti-eye text-sm"></i>
                                        </button>

                                        <!-- Delete Button - Disabled if payment date is not today -->
                                        <form action="{{ route('pembayaran.destroy', $p->id) }}" method="POST"
                                            class="inline delete-form" data-pembayaran-id="{{ $p->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm transition-all duration-200 {{ $p->tanggal->format('Y-m-d') === date('Y-m-d') ? 'bg-gradient-to-r from-red-500 to-red-600 text-white hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                                                title="{{ $p->tanggal->format('Y-m-d') === date('Y-m-d') ? 'Hapus Pembayaran' : 'Hanya bisa dihapus pada hari yang sama' }}"
                                                {{ $p->tanggal->format('Y-m-d') !== date('Y-m-d') ? 'disabled' : '' }}>
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-receipt text-2xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pembayaran</h3>
                                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan pembayaran pertama Anda.</p>
                                        <a href="{{ route('pembayaran.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <i class="ti ti-plus mr-2"></i>
                                            Tambah Pembayaran
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($pembayaran->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $pembayaran->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Pembayaran Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Detail Pembayaran</h3>
                                <p class="text-sm text-gray-600">Informasi lengkap pembayaran</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="printBtn"
                                class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229.243A2.25 2.25 0 0021 20.25V19.5a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 19.5v.75a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 20.25V19.5a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 19.5v.75a2.25 2.25 0 002.25 2.25h13.5z" />
                                </svg>
                                Cetak
                            </button>
                            <button type="button" id="closeDetailModal"
                                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6" id="modalContent">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- QZ Tray -->
    <script src="{{ asset('js/qz/qz-tray.js') }}"></script>
    <script src="{{ asset('js/qz/qz-config.js') }}"></script>

    <script>
        // Initialize Flatpickr for date inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Date From
            flatpickr("#tanggal_dari", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                todayHighlight: true,
                maxDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    const hiddenInput = document.getElementById('tanggal_dari_hidden');
                    if (selectedDates[0]) {
                        const year = selectedDates[0].getFullYear();
                        const month = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                        const day = String(selectedDates[0].getDate()).padStart(2, '0');
                        hiddenInput.value = `${year}-${month}-${day}`;
                    }
                }
            });

            // Date To
            flatpickr("#tanggal_sampai", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                todayHighlight: true,
                maxDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    const hiddenInput = document.getElementById('tanggal_sampai_hidden');
                    if (selectedDates[0]) {
                        const year = selectedDates[0].getFullYear();
                        const month = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                        const day = String(selectedDates[0].getDate()).padStart(2, '0');
                        hiddenInput.value = `${year}-${month}-${day}`;
                    }
                }
            });

            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const pembayaranId = this.getAttribute('data-pembayaran-id');

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus pembayaran ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Detail Modal Functionality
            const detailModal = document.getElementById('detailModal');
            const closeDetailModal = document.getElementById('closeDetailModal');
            const modalContent = document.getElementById('modalContent');
            const printBtn = document.getElementById('printBtn');

            // Open modal when view button is clicked
            document.querySelectorAll('.view-detail-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const pembayaranId = this.getAttribute('data-pembayaran-id');
                    openDetailModal(pembayaranId);
                });
            });

            // Close modal
            closeDetailModal.addEventListener('click', function() {
                detailModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            detailModal.addEventListener('click', function(e) {
                if (e.target === detailModal) {
                    detailModal.classList.add('hidden');
                }
            });

            // Print functionality with QZ Tray
            printBtn.addEventListener('click', function() {
                // Get pembayaran ID from the current modal data
                const currentPembayaranId = this.getAttribute('data-current-pembayaran-id');
                if (currentPembayaranId) {
                    printPembayaran(currentPembayaranId);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'ID pembayaran tidak ditemukan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            async function printPembayaran(pembayaranId) {
                try {
                    // Show loading
                    printBtn.disabled = true;
                    printBtn.innerHTML = `
                        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mencetak...
                    `;

                    // Fetch print data
                    const response = await fetch(`/pembayaran/${pembayaranId}/print`);
                    const result = await response.json();

                    if (!result.success) {
                        throw new Error(result.message);
                    }

                    const data = result.data;

                    // Check if QZ Tray is available
                    if (typeof qz === 'undefined') {
                        Swal.fire({
                            title: 'QZ Tray Tidak Tersedia',
                            text: 'Pastikan QZ Tray sudah terinstall dan berjalan di komputer Anda.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Connect to QZ Tray
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect();
                    }

                    // Find printer
                    const printers = await qz.printers.get();
                    const printerName = data.printer.name;

                    if (!printers.includes(printerName)) {
                        Swal.fire({
                            title: 'Printer Tidak Ditemukan',
                            text: `Printer "${printerName}" tidak ditemukan. Printer yang tersedia: ${printers.join(', ')}`,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    // Generate receipt content
                    const receiptContent = generateReceiptContent(data);

                    // Configure print job
                    const config = qz.configs.create(printerName, {
                        scaleContent: true,
                        rasterize: false,
                        orientation: data.printer.orientation,
                        paperThickness: 0,
                        margins: {
                            top: data.printer.margin_top,
                            bottom: data.printer.margin_bottom,
                            left: data.printer.margin_left,
                            right: data.printer.margin_right
                        }
                    });

                    // Print
                    await qz.print(config, [receiptContent]);

                    // Success message
                    Swal.fire({
                        title: 'Berhasil Dicetak',
                        text: 'Struk pembayaran berhasil dicetak!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                } catch (error) {
                    console.error('Print error:', error);
                    Swal.fire({
                        title: 'Gagal Mencetak',
                        text: error.message || 'Terjadi kesalahan saat mencetak',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } finally {
                    // Reset button
                    printBtn.disabled = false;
                    printBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229.243A2.25 2.25 0 0021 20.25V19.5a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 19.5v.75a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 20.25V19.5a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 19.5v.75a2.25 2.25 0 002.25 2.25h13.5z" />
                        </svg>
                        Cetak
                    `;
                }
            }

            function generateReceiptContent(data) {
                const {
                    pembayaran,
                    penjualan,
                    company
                } = data;

                let content = '';

                // Header
                content += `${company.name}\n`;
                content += `${company.address}\n`;
                content += `Telp: ${company.phone}\n`;
                if (company.website) {
                    content += `${company.website}\n`;
                }
                content += '='.repeat(32) + '\n';

                // Receipt title
                content += 'BUKTI PEMBAYARAN\n';
                content += '='.repeat(32) + '\n';

                // Payment info
                content += `No. Bukti  : ${pembayaran.no_bukti}\n`;
                content += `Tanggal    : ${pembayaran.tanggal}\n`;
                content += `Kasir      : ${pembayaran.user_name}\n`;
                content += '-'.repeat(32) + '\n';

                // Transaction info
                content += `No. Faktur : ${penjualan.no_faktur}\n`;
                content += `Pelanggan  : ${penjualan.pelanggan}\n`;
                content += `Total Trans: Rp ${penjualan.total}\n`;
                content += '-'.repeat(32) + '\n';

                // Payment details
                content += `Jumlah Bayar: Rp ${pembayaran.jumlah_bayar}\n`;
                content += `Metode      : ${pembayaran.metode_pembayaran}\n`;
                content += `Status      : ${pembayaran.status_bayar}\n`;

                if (pembayaran.keterangan) {
                    content += `Keterangan  : ${pembayaran.keterangan}\n`;
                }

                content += '='.repeat(32) + '\n';
                content += `Terima kasih atas pembayaran Anda\n`;
                content += `Semoga hari Anda menyenangkan!\n`;
                content += '='.repeat(32) + '\n';
                content += `\n\n\n\n\n`; // Feed paper

                return content;
            }

            function openDetailModal(pembayaranId) {
                detailModal.classList.remove('hidden');

                // Set pembayaran ID to print button
                printBtn.setAttribute('data-current-pembayaran-id', pembayaranId);

                // Show loading
                modalContent.innerHTML = `
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600">Memuat data...</span>
                    </div>
                `;

                // Fetch pembayaran detail
                fetch(`/pembayaran/${pembayaranId}/detail`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalContent.innerHTML = generateDetailHTML(data.pembayaran);
                        } else {
                            modalContent.innerHTML = `
                                <div class="text-center py-8">
                                    <div class="text-red-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-600">Gagal memuat data pembayaran</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        modalContent.innerHTML = `
                            <div class="text-center py-8">
                                <div class="text-red-500 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <p class="text-gray-600">Terjadi kesalahan saat memuat data</p>
                            </div>
                        `;
                    });
            }

            function generateDetailHTML(pembayaran) {
                return `
                    <div class="space-y-6">
                        <!-- Header Info -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">No. Bukti: ${pembayaran.no_bukti}</h4>
                                    <p class="text-sm text-gray-600">Tanggal: ${pembayaran.tanggal_formatted}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        ${pembayaran.status_bayar === 'P' ? 'bg-green-100 text-green-800' : 
                                          pembayaran.status_bayar === 'D' ? 'bg-blue-100 text-blue-800' : 
                                          'bg-orange-100 text-orange-800'}">
                                        ${pembayaran.status_bayar_display}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-800 mb-3">Informasi Pembayaran</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Jumlah Bayar:</span>
                                        <span class="font-semibold">Rp ${pembayaran.jumlah_bayar_formatted}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Metode Pembayaran:</span>
                                        <span class="font-semibold">${pembayaran.metode_pembayaran_display}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="font-semibold">${pembayaran.status_bayar_display}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Dibuat Oleh:</span>
                                        <span class="font-semibold">${pembayaran.user_name}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-800 mb-3">Informasi Transaksi</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">No. Faktur:</span>
                                        <span class="font-semibold">${pembayaran.penjualan.no_faktur}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Pelanggan:</span>
                                        <span class="font-semibold">${pembayaran.penjualan.pelanggan.nama}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Transaksi:</span>
                                        <span class="font-semibold">Rp ${pembayaran.penjualan.total_formatted}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Transaksi:</span>
                                        <span class="font-semibold">${pembayaran.penjualan.tanggal_formatted}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        ${pembayaran.keterangan ? `
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-800 mb-2">Keterangan</h5>
                                        <p class="text-gray-700">${pembayaran.keterangan}</p>
                                    </div>
                                    ` : ''}
                    </div>
                `;
            }
        });
    </script>
@endpush
