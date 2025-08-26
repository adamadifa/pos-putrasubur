@extends('layouts.pos')

@section('title', 'Pembayaran Pembelian')
@section('page-title', 'Kelola Pembayaran Pembelian')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Pembayaran Pembelian</h2>
                <p class="text-sm text-gray-600">Kelola semua pembayaran pembelian dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Export Excel
                </button>
                <a href="{{ route('pembayaran-pembelian.create') }}"
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
                                    {{ $pembayaranPembelian->where('tanggal', date('Y-m-d'))->count() }}</p>
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
                                    {{ number_format($pembayaranPembelian->where('tanggal', date('Y-m-d'))->sum('jumlah_bayar'), 0, ',', '.') }}
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
                                    {{ $pembayaranPembelian->where('tanggal', date('Y-m-d'))->where('metode_pembayaran', 'tunai')->count() }}
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
                                    {{ $pembayaranPembelian->where('tanggal', date('Y-m-d'))->where('metode_pembayaran', 'transfer')->count() }}
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
                                            {{ $pembayaranPembelian->where('tanggal', date('Y-m-d'))->where('status_bayar', 'P')->count() }}
                                        </p>
                                        <p class="text-xs text-orange-200">Pelunasan</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-white">
                                            {{ $pembayaranPembelian->where('tanggal', date('Y-m-d'))->where('status_bayar', 'D')->count() }}
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
            <form method="GET" action="{{ route('pembayaran-pembelian.index') }}"
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
                            placeholder="Cari no. bukti, no. faktur, nama supplier...">
                    </div>
                </div>

                <!-- Date From -->
                <div class="lg:w-48">
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <div class="relative">
                        <input type="text" id="tanggal_dari" readonly
                            value="{{ request('tanggal_dari_hidden') ? \Carbon\Carbon::parse(request('tanggal_dari_hidden'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_dari_hidden" id="tanggal_dari_hidden"
                        value="{{ request('tanggal_dari_hidden') }}">
                </div>

                <!-- Date To -->
                <div class="lg:w-48">
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Sampai</label>
                    <div class="relative">
                        <input type="text" id="tanggal_sampai" readonly
                            value="{{ request('tanggal_sampai_hidden') ? \Carbon\Carbon::parse(request('tanggal_sampai_hidden'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_sampai_hidden" id="tanggal_sampai_hidden"
                        value="{{ request('tanggal_sampai_hidden') }}">
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
                    @if (request()->hasAny(['search', 'tanggal_dari_hidden', 'tanggal_sampai_hidden', 'status', 'metode_pembayaran']))
                        <a href="{{ route('pembayaran-pembelian.index') }}"
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
                                    <i class="ti ti-building text-blue-600"></i>
                                    <span>Supplier</span>
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
                        @forelse ($pembayaranPembelian as $index => $pembayaran)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-sm font-semibold">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-receipt text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $pembayaran->no_bukti }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pembayaran->tanggal->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('pembelian.show', $pembayaran->pembelian->encrypted_id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                                        <i class="ti ti-file-invoice text-xs mr-1"></i>
                                        {{ $pembayaran->pembelian->no_faktur }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-building text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pembayaran->pembelian->supplier->nama }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($pembayaran->metode_pembayaran === 'tunai') bg-green-100 text-green-800
                                        @elseif($pembayaran->metode_pembayaran === 'transfer') bg-blue-100 text-blue-800
                                        @elseif($pembayaran->metode_pembayaran === 'qris') bg-purple-100 text-purple-800
                                        @elseif($pembayaran->metode_pembayaran === 'edc') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="ti ti-credit-card text-xs mr-1"></i>
                                        {{ ucfirst($pembayaran->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($pembayaran->status_bayar === 'P') bg-green-100 text-green-800
                                        @elseif($pembayaran->status_bayar === 'D') bg-blue-100 text-blue-800
                                        @elseif($pembayaran->status_bayar === 'A') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="ti ti-status-change text-xs mr-1"></i>
                                        @if ($pembayaran->status_bayar === 'P')
                                            Pelunasan
                                        @elseif($pembayaran->status_bayar === 'D')
                                            DP
                                        @elseif($pembayaran->status_bayar === 'A')
                                            Angsuran
                                        @elseif($pembayaran->status_bayar === 'B')
                                            Bayar Sebagian
                                        @else
                                            {{ $pembayaran->status_bayar }}
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
                                            <div class="text-sm font-medium text-gray-900">{{ $pembayaran->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button type="button"
                                            class="view-detail-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200"
                                            title="Lihat Detail" data-pembayaran-id="{{ $pembayaran->encrypted_id }}">
                                            <i class="ti ti-eye text-sm"></i>
                                        </button>

                                        <!-- Delete Button - Disabled if payment date is not today -->
                                        <form
                                            action="{{ route('pembayaran-pembelian.destroy', $pembayaran->encrypted_id) }}"
                                            method="POST" class="inline delete-form"
                                            data-pembayaran-id="{{ $pembayaran->encrypted_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg shadow-sm transition-all duration-200 {{ $pembayaran->tanggal->format('Y-m-d') === date('Y-m-d') ? 'bg-gradient-to-r from-red-500 to-red-600 text-white hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                                                title="{{ $pembayaran->tanggal->format('Y-m-d') === date('Y-m-d') ? 'Hapus Pembayaran' : 'Hanya bisa dihapus pada hari yang sama' }}"
                                                {{ $pembayaran->tanggal->format('Y-m-d') !== date('Y-m-d') ? 'disabled' : '' }}>
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
                                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan pembayaran pembelian pertama
                                            Anda.</p>
                                        <a href="{{ route('pembayaran-pembelian.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                            <i class="ti ti-plus text-lg mr-2"></i>
                                            Tambah Pembayaran
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                                <h3 class="text-lg font-semibold text-gray-800">Detail Pembayaran Pembelian</h3>
                                <p class="text-sm text-gray-600">Informasi lengkap pembayaran pembelian</p>
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
                            <button type="button" id="closeModal"
                                class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Date picker initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date pickers
            const tanggalDariInput = document.getElementById('tanggal_dari');
            const tanggalSampaiInput = document.getElementById('tanggal_sampai');
            const tanggalDariHidden = document.getElementById('tanggal_dari_hidden');
            const tanggalSampaiHidden = document.getElementById('tanggal_sampai_hidden');

            // Simple date picker functionality
            if (tanggalDariInput) {
                tanggalDariInput.addEventListener('click', function() {
                    const input = document.createElement('input');
                    input.type = 'date';
                    input.style.position = 'absolute';
                    input.style.left = '-9999px';
                    document.body.appendChild(input);

                    input.addEventListener('change', function() {
                        const date = new Date(this.value);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                        tanggalDariInput.value = formattedDate;
                        tanggalDariHidden.value = this.value;
                        document.body.removeChild(input);
                    });

                    input.click();
                });
            }

            if (tanggalSampaiInput) {
                tanggalSampaiInput.addEventListener('click', function() {
                    const input = document.createElement('input');
                    input.type = 'date';
                    input.style.position = 'absolute';
                    input.style.left = '-9999px';
                    document.body.appendChild(input);

                    input.addEventListener('change', function() {
                        const date = new Date(this.value);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                        tanggalSampaiInput.value = formattedDate;
                        tanggalSampaiHidden.value = this.value;
                        document.body.removeChild(input);
                    });

                    input.click();
                });
            }
        });

        // View detail button functionality
        document.querySelectorAll('.view-detail-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const pembayaranId = this.getAttribute('data-pembayaran-id');
                showDetailModal(pembayaranId);
            });
        });

        // Modal functionality
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        const closeModal = document.getElementById('closeModal');
        const printBtn = document.getElementById('printBtn');

        function showDetailModal(pembayaranId) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Set pembayaran ID to print button
            printBtn.setAttribute('data-current-pembayaran-id', pembayaranId);

            // Show loading
            modalContent.innerHTML = `
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Memuat data...</span>
                </div>
            `;

            // Fetch payment details
            fetch(`/pembayaran-pembelian/${pembayaranId}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalContent.innerHTML = generateDetailHTML(data.data);
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
                    console.error('Error:', error);
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
                                <p class="text-sm text-gray-600">Tanggal: ${new Date(pembayaran.tanggal).toLocaleDateString('id-ID')}</p>
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
                                    <span class="font-semibold">Rp ${Number(pembayaran.jumlah_bayar).toLocaleString('id-ID')}</span>
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
                                    <span class="font-semibold">${pembayaran.user.name}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-800 mb-3">Informasi Pembelian</h5>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Faktur:</span>
                                    <span class="font-semibold">${pembayaran.pembelian.no_faktur}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Supplier:</span>
                                    <span class="font-semibold">${pembayaran.pembelian.supplier.nama}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Pembelian:</span>
                                    <span class="font-semibold">Rp ${Number(pembayaran.pembelian.total).toLocaleString('id-ID')}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Pembelian:</span>
                                    <span class="font-semibold">${new Date(pembayaran.pembelian.tanggal).toLocaleDateString('id-ID')}</span>
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

        // Close modal
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Print functionality
        printBtn.addEventListener('click', async function() {
            const pembayaranId = this.getAttribute('data-current-pembayaran-id');
            if (!pembayaranId) {
                alert('Tidak ada data pembayaran yang dipilih');
                return;
            }

            // Disable button and show loading
            printBtn.disabled = true;
            printBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mencetak...
            `;

            try {
                const response = await fetch(`/pembayaran-pembelian/${pembayaranId}/print`);
                const data = await response.json();

                if (data.success) {
                    // Use QZ Tray for printing
                    if (typeof qz !== 'undefined') {
                        await printReceipt(data.data);
                    } else {
                        // Fallback to browser print
                        window.print();
                    }
                } else {
                    throw new Error(data.message || 'Gagal mendapatkan data untuk cetak');
                }
            } catch (error) {
                console.error('Print error:', error);
                alert('Gagal mencetak: ' + error.message);
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
        });

        async function printReceipt(printData) {
            try {
                // Get printer settings
                const printerResponse = await fetch('/printer/get-settings');
                const printerData = await printerResponse.json();

                if (!printerData.success) {
                    throw new Error('Printer tidak ditemukan');
                }

                const printer = printerData.data.printer_name || 'Default Printer';
                const content = generateReceiptContent(printData);

                const config = qz.configs.create(printer, {
                    scaleContent: true,
                    rasterize: false
                });

                await qz.print(config, [content]);
            } catch (error) {
                console.error('QZ Tray error:', error);
                // Fallback to browser print
                window.print();
            }
        }

        function generateReceiptContent(data) {
            const {
                pembayaran,
                pembelian,
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
            content += 'BUKTI PEMBAYARAN PEMBELIAN\n';
            content += '='.repeat(32) + '\n';

            // Payment info
            content += `No. Bukti  : ${pembayaran.no_bukti}\n`;
            content += `Tanggal    : ${pembayaran.tanggal}\n`;
            content += `Kasir      : ${pembayaran.user_name}\n`;
            content += '-'.repeat(32) + '\n';

            // Transaction info
            content += `No. Faktur : ${pembelian.no_faktur}\n`;
            content += `Supplier   : ${pembelian.supplier}\n`;
            content += `Total Trans: Rp ${pembelian.total}\n`;
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

        // Delete form confirmation
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const pembayaranId = this.getAttribute('data-pembayaran-id');
                const button = this.querySelector('button[type="submit"]');

                if (button.disabled) {
                    alert('Pembayaran hanya bisa dihapus pada hari yang sama dengan pembuatan.');
                    return;
                }

                if (confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')) {
                    this.submit();
                }
            });
        });
    </script>
@endsection
