@extends('layouts.pos')

@section('title', 'Penjualan')
@section('page-title', 'Kelola Penjualan')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Transaksi Penjualan</h2>
                <p class="text-sm text-gray-600">Kelola semua transaksi penjualan dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Export Excel
                </button>
                <a href="{{ route('penjualan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Transaksi Baru
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Transaksi Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-shopping-cart text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-blue-100">Transaksi Hari Ini</h3>
                                <p class="text-3xl font-bold text-white">{{ $penjualanHariIni ?? 0 }}</p>
                                <div class="flex items-center mt-1">
                                    @if ($perubahanPenjualan > 0)
                                        <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                        <span
                                            class="text-xs text-green-300">+{{ number_format($perubahanPenjualan, 1) }}%</span>
                                    @elseif($perubahanPenjualan < 0)
                                        <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                        <span
                                            class="text-xs text-red-300">{{ number_format($perubahanPenjualan, 1) }}%</span>
                                    @else
                                        <i class="ti ti-minus text-blue-200 text-sm mr-1"></i>
                                        <span class="text-xs text-blue-200">0%</span>
                                    @endif
                                    <span class="text-xs text-blue-200 ml-1">vs kemarin</span>
                </div>
                </div>
                </div>
            </div>
            </div>
        </div>

            <!-- Nilai Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                                <h3 class="text-sm font-medium text-emerald-100">Penjualan Hari Ini</h3>
                                <p class="text-2xl font-bold text-white">Rp
                                    {{ number_format($nilaiHariIni ?? 0, 0, ',', '.') }}</p>
                                <div class="flex items-center mt-1">
                                    @if ($perubahanNilai > 0)
                                        <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                        <span
                                            class="text-xs text-green-300">+{{ number_format($perubahanNilai, 1) }}%</span>
                                    @elseif($perubahanNilai < 0)
                                        <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                        <span class="text-xs text-red-300">{{ number_format($perubahanNilai, 1) }}%</span>
                                    @else
                                        <i class="ti ti-minus text-emerald-200 text-sm mr-1"></i>
                                        <span class="text-xs text-emerald-200">0%</span>
                                    @endif
                                    <span class="text-xs text-emerald-200 ml-1">vs kemarin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lunas Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-check-circle text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-purple-100">Lunas Hari Ini</h3>
                            <p class="text-3xl font-bold text-white">{{ $statusCountsHariIni['lunas'] ?? 0 }}</p>
                            <p class="text-sm text-purple-200 flex items-center mt-1">
                                <i class="ti ti-circle-check text-lg mr-1"></i>
                                dari {{ $penjualanHariIni }} transaksi
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belum Bayar Hari Ini Card -->
            <div
                class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-clock text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-orange-100">Belum Bayar</h3>
                            <p class="text-3xl font-bold text-white">{{ $statusCountsHariIni['belum_bayar'] ?? 0 }}</p>
                            <p class="text-sm text-orange-200 flex items-center mt-1">
                                <i class="ti ti-alert-circle text-lg mr-1"></i>
                                perlu tindak lanjut
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Summary Today -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Status Pembayaran Hari Ini</h3>
                    <p class="text-sm text-gray-600">{{ now()->format('d F Y') }}</p>
                </div>
                <div class="text-sm text-gray-500">
                    Total: {{ $penjualanHariIni }} transaksi
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Lunas -->
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-check-circle text-white text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-green-600">{{ $statusCountsHariIni['lunas'] }}</p>
                    <p class="text-sm text-green-600 font-medium">Lunas</p>
                    @if ($penjualanHariIni > 0)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statusCountsHariIni['lunas'] / $penjualanHariIni) * 100, 1) }}%</p>
                    @endif
                </div>

                <!-- DP -->
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-clock text-white text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">{{ $statusCountsHariIni['dp'] }}</p>
                    <p class="text-sm text-blue-600 font-medium">DP</p>
                    @if ($penjualanHariIni > 0)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statusCountsHariIni['dp'] / $penjualanHariIni) * 100, 1) }}%</p>
                    @endif
                </div>

                <!-- Angsuran -->
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-clock-hour-4 text-white text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $statusCountsHariIni['angsuran'] }}</p>
                    <p class="text-sm text-yellow-600 font-medium">Angsuran</p>
                    @if ($penjualanHariIni > 0)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statusCountsHariIni['angsuran'] / $penjualanHariIni) * 100, 1) }}%</p>
                    @endif
                </div>

                <!-- Belum Bayar -->
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ti ti-x-circle text-white text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-red-600">{{ $statusCountsHariIni['belum_bayar'] }}</p>
                    <p class="text-sm text-red-600 font-medium">Belum Bayar</p>
                    @if ($penjualanHariIni > 0)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statusCountsHariIni['belum_bayar'] / $penjualanHariIni) * 100, 1) }}%</p>
                    @endif
                    </div>
                </div>
            </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form method="GET" action="{{ route('penjualan.index') }}"
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
                            placeholder="Cari nomor faktur, nama pelanggan...">
                    </div>
                </div>

                <!-- Date From -->
                <div class="lg:w-48">
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Date To -->
                <div class="lg:w-48">
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Sampai</label>
                    <input type="date" name="tanggal_sampai" id="tanggal_sampai"
                        value="{{ request('tanggal_sampai') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                </div>

                <!-- Status Filter -->
                <div class="lg:w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>DP</option>
                        <option value="angsuran" {{ request('status') == 'angsuran' ? 'selected' : '' }}>Angsuran</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar
                        </option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i class="ti ti-filter text-lg mr-2"></i>
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'tanggal_dari', 'tanggal_sampai', 'status']))
                        <a href="{{ route('penjualan.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="ti ti-receipt text-lg"></i>
                                    <span>Faktur</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="ti ti-calendar text-lg"></i>
                                    <span>Tanggal</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="ti ti-user text-lg"></i>
                                    <span>Pelanggan</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <i class="ti ti-shopping-bag text-lg"></i>
                                    <span>Items</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-1">
                                    <i class="ti ti-currency-dollar text-lg"></i>
                                    <span>Total</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="ti ti-credit-card text-lg"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-1">
                                    <i class="ti ti-settings text-lg"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($penjualan as $item)
                            @php
                                $statusConfig = [
                                    'lunas' => [
                                        'bg' => 'bg-green-100',
                                        'text' => 'text-green-800',
                                        'icon' => 'ti-check-circle',
                                        'label' => 'Lunas',
                                    ],
                                    'dp' => [
                                        'bg' => 'bg-blue-100',
                                        'text' => 'text-blue-800',
                                        'icon' => 'ti-clock',
                                        'label' => 'DP',
                                    ],
                                    'angsuran' => [
                                        'bg' => 'bg-yellow-100',
                                        'text' => 'text-yellow-800',
                                        'icon' => 'ti-clock-hour-4',
                                        'label' => 'Angsuran',
                                    ],
                                    'belum_bayar' => [
                                        'bg' => 'bg-red-100',
                                        'text' => 'text-red-800',
                                        'icon' => 'ti-x-circle',
                                        'label' => 'Belum Bayar',
                                    ],
                                ];
                                $config = $statusConfig[$item->status_pembayaran] ?? $statusConfig['belum_bayar'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->no_faktur }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->kasir->name ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->tanggal->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->pelanggan->nama ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->pelanggan->kode_pelanggan ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $item->detailPenjualan->count() }} item
                                    </span>
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex flex-col items-end">
                                        <div class="text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($item->total, 0, ',', '.') }}</div>
                                        @if ($item->diskon > 0)
                                            <div class="text-xs text-gray-500">Diskon: Rp
                                                {{ number_format($item->diskon, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                        <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                                        {{ $config['label'] }}
                                </span>
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <!-- View Button -->
                                        <a href="{{ route('penjualan.show', $item->encrypted_id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-md hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        @if ($item->status_pembayaran !== 'lunas')
                                            <a href="{{ route('penjualan.edit', $item->encrypted_id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                                <i class="ti ti-edit text-sm"></i>
                                            </a>
                                        @endif

                                        <!-- Delete Button -->
                                        @if ($item->pembayaranPenjualan->count() == 0)
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->no_faktur }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-md hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="ti ti-shopping-cart-off text-5xl mx-auto mb-4 text-gray-400"></i>
                                        <p class="text-lg font-medium">Tidak ada transaksi ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau buat transaksi baru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (isset($penjualan) && $penjualan->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                        @if ($penjualan->previousPageUrl())
                            <a href="{{ $penjualan->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @endif

                        @if ($penjualan->nextPageUrl())
                            <a href="{{ $penjualan->nextPageUrl() }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Selanjutnya
                            </a>
                        @else
                            <span
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Selanjutnya
                            </span>
                        @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $penjualan->firstItem() ?? 0 }}</span> sampai
                                <span class="font-medium">{{ $penjualan->lastItem() ?? 0 }}</span> dari
                                <span class="font-medium">{{ $penjualan->total() }}</span> transaksi
                        </p>
                    </div>
                    <div>
                            {{ $penjualan->links() }}
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

        .swal2-confirm {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            font-size: 0.95rem !important;
        }

        .swal2-cancel {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            font-size: 0.95rem !important;
        }

        .swal2-actions {
            gap: 1rem !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(salesId, invoiceNumber) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus transaksi "${invoiceNumber}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('penjualan.destroy', '') }}/${salesId}`;

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
    </script>
@endpush
