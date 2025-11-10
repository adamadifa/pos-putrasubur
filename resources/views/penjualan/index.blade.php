@extends('layouts.pos')

@section('title', 'Penjualan')
@section('page-title', 'Kelola Penjualan')

@section('content')
    <div class="space-y-4 md:space-y-6 px-2 md:px-0">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Transaksi Penjualan</h2>
                <p class="text-sm text-gray-600">Kelola semua transaksi penjualan dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 sm:space-x-0">
                <button
                    class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-xs md:text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-base md:text-lg mr-2"></i>
                    <span class="hidden sm:inline">Export Excel</span>
                    <span class="sm:hidden">Export</span>
                </button>
                <a href="{{ route('penjualan.create') }}"
                    class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-xs md:text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-base md:text-lg mr-2"></i>
                    <span class="hidden sm:inline">Transaksi Baru</span>
                    <span class="sm:hidden">Baru</span>
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
        <div class="overflow-x-auto md:overflow-visible -mx-2 md:mx-0 px-2 md:px-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-6 min-w-max md:min-w-0">
                <!-- Transaksi Hari Ini Card -->
                <div
                    class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative p-4 md:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="ti ti-shopping-cart text-xl md:text-2xl text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-3 md:ml-4">
                                    <h3 class="text-xs md:text-sm font-medium text-blue-100">Transaksi Hari Ini</h3>
                                    <p class="text-2xl md:text-3xl font-bold text-white">{{ $penjualanHariIni ?? 0 }}</p>
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
                    <div class="relative p-4 md:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="ti ti-currency-dollar text-xl md:text-2xl text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-3 md:ml-4">
                                    <h3 class="text-xs md:text-sm font-medium text-emerald-100">Penjualan Hari Ini</h3>
                                    <p class="text-lg md:text-2xl font-bold text-white">Rp
                                        {{ number_format($nilaiHariIni ?? 0, 0, ',', '.') }}</p>
                                    <div class="flex items-center mt-1">
                                        @if ($perubahanNilai > 0)
                                            <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                            <span
                                                class="text-xs text-green-300">+{{ number_format($perubahanNilai, 1) }}%</span>
                                        @elseif($perubahanNilai < 0)
                                            <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                            <span
                                                class="text-xs text-red-300">{{ number_format($perubahanNilai, 1) }}%</span>
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
                    <div class="relative p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-check-circle text-xl md:text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-3 md:ml-4">
                                <h3 class="text-xs md:text-sm font-medium text-purple-100">Lunas Hari Ini</h3>
                                <p class="text-2xl md:text-3xl font-bold text-white">
                                    {{ $statusCountsHariIni['lunas'] ?? 0 }}</p>
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
                    <div class="relative p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-clock text-xl md:text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-3 md:ml-4">
                                <h3 class="text-xs md:text-sm font-medium text-orange-100">Belum Bayar</h3>
                                <p class="text-2xl md:text-3xl font-bold text-white">
                                    {{ $statusCountsHariIni['belum_bayar'] ?? 0 }}</p>
                                <p class="text-sm text-orange-200 flex items-center mt-1">
                                    <i class="ti ti-alert-circle text-lg mr-1"></i>
                                    perlu tindak lanjut
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jenis Transaksi Card -->
                <div
                    class="relative bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-cash text-xl md:text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-3 md:ml-4">
                                <h3 class="text-xs md:text-sm font-medium text-indigo-100">Jenis Transaksi</h3>
                                <div class="flex items-center space-x-3 mt-2">
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-white">
                                            {{ $jenisTransaksiCountsHariIni['tunai'] ?? 0 }}</p>
                                        <p class="text-xs text-indigo-200">Tunai</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-white">
                                            {{ $jenisTransaksiCountsHariIni['kredit'] ?? 0 }}</p>
                                        <p class="text-xs text-indigo-200">Kredit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-3 md:p-6">
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
                    <div class="relative">
                        <input type="text" id="tanggal_dari" readonly
                            value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_dari" id="tanggal_dari_hidden"
                        value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') : '' }}">
                </div>

                <!-- Date To -->
                <div class="lg:w-48">
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Sampai</label>
                    <div class="relative">
                        <input type="text" id="tanggal_sampai" readonly
                            value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_sampai" id="tanggal_sampai_hidden"
                        value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') : '' }}">
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

                <!-- Jenis Transaksi Filter -->
                <div class="lg:w-48">
                    <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                        Transaksi</label>
                    <select name="jenis_transaksi" id="jenis_transaksi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Jenis</option>
                        <option value="tunai" {{ request('jenis_transaksi') == 'tunai' ? 'selected' : '' }}>Tunai
                        </option>
                        <option value="kredit" {{ request('jenis_transaksi') == 'kredit' ? 'selected' : '' }}>Kredit
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
                    @if (request()->hasAny(['search', 'tanggal_dari', 'tanggal_sampai', 'status', 'jenis_transaksi']))
                        <a href="{{ route('penjualan.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Modern Table -->
        <!-- Mobile: Card View -->
        <div class="block md:hidden space-y-3">
            @forelse ($penjualan as $item)
                @php
                    $statusConfig = [
                        'lunas' => [
                            'bg' => 'bg-gradient-to-r from-green-500 to-green-600',
                            'text' => 'text-white',
                            'icon' => 'ti-check-circle',
                            'label' => 'Lunas',
                        ],
                        'dp' => [
                            'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                            'text' => 'text-white',
                            'icon' => 'ti-x-circle',
                            'label' => 'Belum Lunas',
                        ],
                        'angsuran' => [
                            'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                            'text' => 'text-white',
                            'icon' => 'ti-x-circle',
                            'label' => 'Belum Lunas',
                        ],
                        'belum_bayar' => [
                            'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                            'text' => 'text-white',
                            'icon' => 'ti-x-circle',
                            'label' => 'Belum Lunas',
                        ],
                    ];
                    $config = $statusConfig[$item->status_pembayaran] ?? $statusConfig['belum_bayar'];
                    $jenisConfig = [
                        'tunai' => [
                            'bg' => 'bg-gradient-to-r from-green-500 to-green-600',
                            'text' => 'text-white',
                            'icon' => 'ti-cash',
                            'label' => 'Tunai',
                        ],
                        'kredit' => [
                            'bg' => 'bg-gradient-to-r from-purple-500 to-purple-600',
                            'text' => 'text-white',
                            'icon' => 'ti-credit-card',
                            'label' => 'Kredit',
                        ],
                    ];
                    $jenisTransaksiConfig = $jenisConfig[$item->jenis_transaksi] ?? $jenisConfig['tunai'];
                    $today = \Carbon\Carbon::today();
                    $transactionDate = \Carbon\Carbon::parse($item->created_at)->startOfDay();
                    $isMoreThanOneDay = $today->diffInDays($transactionDate) > 1;
                @endphp
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-1">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ti ti-receipt text-white text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $item->no_faktur }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->kasir->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            @if (!$isMoreThanOneDay)
                                <a href="{{ route('penjualan.edit', $item->encrypted_id) }}"
                                    class="hidden items-center justify-center w-7 h-7 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-sm">
                                    <i class="ti ti-edit text-xs"></i>
                                </a>
                                <button type="button"
                                    onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->no_faktur }}')"
                                    class="inline-flex items-center justify-center w-7 h-7 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm">
                                    <i class="ti ti-trash text-xs"></i>
                                </button>
                            @endif
                            <a href="{{ route('penjualan.show', $item->encrypted_id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm">
                                <i class="ti ti-eye text-xs"></i>
                            </a>
                        </div>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Tanggal:</span>
                            <span class="font-medium text-gray-900">{{ $item->tanggal->format('d M Y') }}
                                {{ $item->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Pelanggan:</span>
                            <span
                                class="font-medium text-gray-900 truncate ml-2">{{ $item->pelanggan->nama ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Items:</span>
                            <span class="font-medium text-gray-900">{{ $item->detailPenjualan->count() }} item</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total:</span>
                            <span class="font-bold text-lg text-gray-900">Rp
                                {{ number_format($item->total_setelah_diskon, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                            {{ $config['label'] }}
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }}">
                            <i class="ti {{ $jenisTransaksiConfig['icon'] }} text-xs mr-1"></i>
                            {{ $jenisTransaksiConfig['label'] }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-500">
                        <i class="ti ti-shopping-cart-off text-5xl mx-auto mb-4 text-gray-400"></i>
                        <p class="text-lg font-medium">Tidak ada transaksi ditemukan</p>
                        <p class="text-sm">Coba ubah filter pencarian atau buat transaksi baru</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Desktop: Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
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
                                    <span>Faktur & Kasir</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-calendar text-blue-600"></i>
                                    <span>Tanggal & Waktu</span>
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
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-shopping-bag text-blue-600"></i>
                                    <span>Items</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-currency-dollar text-blue-600"></i>
                                    <span>Total Netto</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-credit-card text-blue-600"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-cash text-blue-600"></i>
                                    <span>Jenis</span>
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
                        @forelse ($penjualan as $item)
                            @php
                                $statusConfig = [
                                    'lunas' => [
                                        'bg' => 'bg-gradient-to-r from-green-500 to-green-600',
                                        'text' => 'text-white',
                                        'icon' => 'ti-check-circle',
                                        'label' => 'Lunas',
                                    ],
                                    'dp' => [
                                        'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                                        'text' => 'text-white',
                                        'icon' => 'ti-x-circle',
                                        'label' => 'Belum Lunas',
                                    ],
                                    'angsuran' => [
                                        'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                                        'text' => 'text-white',
                                        'icon' => 'ti-x-circle',
                                        'label' => 'Belum Lunas',
                                    ],
                                    'belum_bayar' => [
                                        'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                                        'text' => 'text-white',
                                        'icon' => 'ti-x-circle',
                                        'label' => 'Belum Lunas',
                                    ],
                                ];
                                $config = $statusConfig[$item->status_pembayaran] ?? $statusConfig['belum_bayar'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                            <span
                                                class="text-white text-sm font-semibold">{{ ($penjualan->currentPage() - 1) * $penjualan->perPage() + $loop->iteration }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-receipt text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->no_faktur }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->kasir->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-calendar text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->tanggal->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->pelanggan->nama ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $item->pelanggan->kode_pelanggan ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-50 text-blue-700">
                                        <i class="ti ti-shopping-bag text-sm mr-2"></i>
                                        <span class="text-sm font-semibold">{{ $item->detailPenjualan->count() }}</span>
                                        <span class="text-xs ml-1">item</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="text-lg font-bold text-gray-900">Rp
                                        {{ number_format($item->total_setelah_diskon, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                        <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @php
                                        $jenisConfig = [
                                            'tunai' => [
                                                'bg' => 'bg-gradient-to-r from-green-500 to-green-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-cash',
                                                'label' => 'Tunai',
                                            ],
                                            'kredit' => [
                                                'bg' => 'bg-gradient-to-r from-purple-500 to-purple-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-credit-card',
                                                'label' => 'Kredit',
                                            ],
                                        ];
                                        $jenisTransaksiConfig =
                                            $jenisConfig[$item->jenis_transaksi] ?? $jenisConfig['tunai'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }} shadow-sm">
                                        <i class="ti {{ $jenisTransaksiConfig['icon'] }} text-xs mr-1"></i>
                                        {{ $jenisTransaksiConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route('penjualan.show', $item->encrypted_id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $transactionDate = \Carbon\Carbon::parse($item->created_at)->startOfDay();
                                            $isMoreThanOneDay = $today->diffInDays($transactionDate) > 1; // Lebih dari 1 hari
                                            $hasPayment = $item->pembayaranPenjualan->count() > 0;
                                        @endphp

                                        @if (!$isMoreThanOneDay)
                                            <a href="{{ route('penjualan.edit', $item->encrypted_id) }}"
                                                class="hidden items-center justify-center w-8 h-8 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200"
                                                title="Edit Penjualan">
                                                <i class="ti ti-edit text-sm"></i>
                                            </a>
                                        @else
                                            <button type="button" disabled
                                                class="hidden items-center justify-center w-8 h-8 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                                title="Tidak dapat diedit setelah H+1">
                                                <i class="ti ti-edit text-sm"></i>
                                            </button>
                                        @endif

                                        <!-- Delete Button -->
                                        @if (!$isMoreThanOneDay)
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->no_faktur }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200"
                                                title="Hapus Penjualan">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        @else
                                            <button type="button" disabled
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                                title="Tidak dapat dihapus setelah H+1">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
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
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* Custom Flatpickr Styling - Sesuai Tema Aplikasi */
        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
        }

        .flatpickr-months {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 0 !important;
        }

        .flatpickr-month {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month {
            color: #ffffff !important;
        }

        .flatpickr-monthDropdown-months {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month .numInputWrapper {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-weekdays {
            background: #f8fafc !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .flatpickr-weekday {
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .flatpickr-day {
            color: #374151 !important;
            border-radius: 8px !important;
            margin: 2px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-day:hover {
            background: #dbeafe !important;
            color: #1e40af !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.selected {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3) !important;
        }

        .flatpickr-day.selected:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.today {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3) !important;
        }

        .flatpickr-day.today:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #9ca3af !important;
            opacity: 0.6 !important;
        }

        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: #ffffff !important;
            fill: #ffffff !important;
            padding: 8px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            transform: scale(1.1) !important;
        }

        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 16px !important;
            height: 16px !important;
        }

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
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize Flatpickr Date Pickers
        document.addEventListener('DOMContentLoaded', function() {
            // Date From Picker
            const dateFromPicker = flatpickr("#tanggal_dari", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                @if(request('tanggal_dari'))
                defaultDate: "{{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') }}",
                @endif
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0]) {
                        // Format tanggal ke Y-m-d tanpa terpengaruh timezone
                        const date = selectedDates[0];
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        const isoDate = `${year}-${month}-${day}`;
                        
                        document.getElementById('tanggal_dari_hidden').value = isoDate;

                        // Update visible input with formatted date
                        instance.input.value = dateStr;
                    }
                }
            });

            // Date To Picker
            const dateToPicker = flatpickr("#tanggal_sampai", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                @if(request('tanggal_sampai'))
                defaultDate: "{{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') }}",
                @endif
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0]) {
                        // Format tanggal ke Y-m-d tanpa terpengaruh timezone
                        const date = selectedDates[0];
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        const isoDate = `${year}-${month}-${day}`;
                        
                        document.getElementById('tanggal_sampai_hidden').value = isoDate;

                        // Update visible input with formatted date
                        instance.input.value = dateStr;
                    }
                }
            });

            // Set min date for date_to based on date_from
            dateFromPicker.config.onChange.push(function(selectedDates) {
                if (selectedDates[0]) {
                    dateToPicker.set('minDate', selectedDates[0]);
                }
            });

            // Initialize hidden inputs with ISO format if defaultDate is set
            @if(request('tanggal_dari'))
            const tanggalDariValue = "{{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') }}";
            document.getElementById('tanggal_dari_hidden').value = tanggalDariValue;
            @endif

            @if(request('tanggal_sampai'))
            const tanggalSampaiValue = "{{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') }}";
            document.getElementById('tanggal_sampai_hidden').value = tanggalSampaiValue;
            @endif
        });

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
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`{{ route('penjualan.destroy', '') }}/${salesId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) {
                                // Try to parse as JSON, if fails, use status text
                                return response.text().then(text => {
                                    try {
                                        const err = JSON.parse(text);
                                        throw new Error(err.message ||
                                            'Terjadi kesalahan saat menghapus penjualan');
                                    } catch (e) {
                                        throw new Error(text || response.statusText ||
                                            'Terjadi kesalahan saat menghapus penjualan');
                                    }
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Penjualan berhasil dihapus.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                // Hanya tampilkan message-nya saja, bukan JSON
                                const errorMessage = data.message ||
                                    'Terjadi kesalahan saat menghapus penjualan';
                                throw new Error(errorMessage);
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: error.message || 'Terjadi kesalahan saat menghapus penjualan',
                                confirmButtonText: 'OK'
                            });
                            throw error;
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }

        // Helper function untuk show toast (jika belum ada)
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;

            if (type === 'success') {
                toast.classList.add('bg-green-500');
            } else if (type === 'error') {
                toast.classList.add('bg-red-500');
            } else if (type === 'warning') {
                toast.classList.add('bg-yellow-500');
            } else {
                toast.classList.add('bg-blue-500');
            }

            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">
                        ${type === 'success' ? '<i class="ti ti-check-circle"></i>' : 
                          type === 'error' ? '<i class="ti ti-x-circle"></i>' : 
                          type === 'warning' ? '<i class="ti ti-alert-circle"></i>' : 
                          '<i class="ti ti-info-circle"></i>'}
                    </span>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }
    </script>
@endpush
