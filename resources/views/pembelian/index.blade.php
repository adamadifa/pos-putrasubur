@extends('layouts.pos')

@section('title', 'Pembelian')
@section('page-title', 'Kelola Pembelian')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Transaksi Pembelian</h2>
                <p class="text-sm text-gray-600">Kelola semua transaksi pembelian dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Export Excel
                </button>
                <a href="{{ route('pembelian.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Pembelian Baru
                </a>
            </div>
        </div>

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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div
                class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                                <h3 class="text-sm font-medium text-orange-100">Pembelian Hari Ini</h3>
                                <p class="text-3xl font-bold text-white">{{ $pembelianHariIni ?? 0 }}</p>
                                <div class="flex items-center mt-1">
                                    @if (($perubahanPembelian ?? 0) > 0)
                                        <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                        <span
                                            class="text-xs text-green-300">+{{ number_format($perubahanPembelian, 1) }}%</span>
                                    @elseif(($perubahanPembelian ?? 0) < 0)
                                        <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                        <span
                                            class="text-xs text-red-300">{{ number_format($perubahanPembelian, 1) }}%</span>
                                    @else
                                        <i class="ti ti-minus text-orange-200 text-sm mr-1"></i>
                                        <span class="text-xs text-orange-200">0%</span>
                                    @endif
                                    <span class="text-xs text-orange-200 ml-1">vs kemarin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                                <h3 class="text-sm font-medium text-red-100">Pembelian Hari Ini</h3>
                                <p class="text-2xl font-bold text-white">Rp
                                    {{ number_format($nilaiHariIni ?? 0, 0, ',', '.') }}</p>
                                <div class="flex items-center mt-1">
                                    @if (($perubahanNilai ?? 0) > 0)
                                        <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                        <span
                                            class="text-xs text-green-300">+{{ number_format($perubahanNilai, 1) }}%</span>
                                    @elseif(($perubahanNilai ?? 0) < 0)
                                        <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                        <span class="text-xs text-red-300">{{ number_format($perubahanNilai, 1) }}%</span>
                                    @else
                                        <i class="ti ti-minus text-red-200 text-sm mr-1"></i>
                                        <span class="text-xs text-red-200">0%</span>
                                    @endif
                                    <span class="text-xs text-red-200 ml-1">vs kemarin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                            <h3 class="text-sm font-medium text-green-100">Lunas Hari Ini</h3>
                            <p class="text-3xl font-bold text-white">{{ $statusCountsHariIni['lunas'] ?? 0 }}</p>
                            <p class="text-sm text-green-200 flex items-center mt-1">
                                <i class="ti ti-circle-check text-lg mr-1"></i>
                                dari {{ $pembelianHariIni ?? 0 }} transaksi
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-yellow-500 via-yellow-600 to-yellow-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                            <h3 class="text-sm font-medium text-yellow-100">Belum Bayar</h3>
                            <p class="text-3xl font-bold text-white">{{ $statusCountsHariIni['belum_bayar'] ?? 0 }}</p>
                            <p class="text-sm text-yellow-200 flex items-center mt-1">
                                <i class="ti ti-alert-circle text-lg mr-1"></i>
                                perlu tindak lanjut
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-cash text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-purple-100">Jenis Transaksi</h3>
                            <div class="flex items-center space-x-3 mt-2">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-white">
                                        {{ $jenisTransaksiCountsHariIni['tunai'] ?? 0 }}</p>
                                    <p class="text-xs text-purple-200">Tunai</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-bold text-white">
                                        {{ $jenisTransaksiCountsHariIni['kredit'] ?? 0 }}</p>
                                    <p class="text-xs text-purple-200">Kredit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form method="GET" action="{{ route('pembelian.index') }}"
                class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ti ti-search text-lg text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                            placeholder="Cari nomor faktur, nama supplier...">
                    </div>
                </div>

                <div class="lg:w-48">
                    <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <div class="relative">
                        <input type="text" name="tanggal_dari" id="tanggal_dari" readonly
                            value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_sampai_hidden" id="tanggal_sampai_hidden"
                        value="{{ request('tanggal_sampai') }}">
                </div>

                <div class="lg:w-48">
                    <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Sampai</label>
                    <div class="relative">
                        <input type="text" name="tanggal_sampai" id="tanggal_sampai" readonly
                            value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_dari_hidden" id="tanggal_dari_hidden"
                        value="{{ request('tanggal_dari') }}">
                </div>

                <div class="lg:w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>DP</option>
                        <option value="angsuran" {{ request('status') == 'angsuran' ? 'selected' : '' }}>Angsuran</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar
                        </option>
                    </select>
                </div>

                <div class="lg:w-48">
                    <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                        Transaksi</label>
                    <select name="jenis_transaksi" id="jenis_transaksi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Jenis</option>
                        <option value="tunai" {{ request('jenis_transaksi') == 'tunai' ? 'selected' : '' }}>Tunai
                        </option>
                        <option value="kredit" {{ request('jenis_transaksi') == 'kredit' ? 'selected' : '' }}>Kredit
                        </option>
                    </select>
                </div>

                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-orange-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        <i class="ti ti-filter text-lg mr-2"></i>
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'tanggal_dari', 'tanggal_sampai', 'status', 'jenis_transaksi']))
                        <a href="{{ route('pembelian.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-hash text-orange-600"></i>
                                    <span>No</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-receipt text-orange-600"></i>
                                    <span>Faktur & User</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-calendar text-orange-600"></i>
                                    <span>Tanggal & Waktu</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-building-store text-orange-600"></i>
                                    <span>Supplier</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-shopping-bag text-orange-600"></i>
                                    <span>Items</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-currency-dollar text-orange-600"></i>
                                    <span>Total</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-credit-card text-orange-600"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-cash text-orange-600"></i>
                                    <span>Jenis</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-settings text-orange-600"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pembelian as $item)
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
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                            <span
                                                class="text-white text-sm font-semibold">{{ ($pembelian->currentPage() - 1) * $pembelian->perPage() + $loop->iteration }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-receipt text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->no_faktur }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->user->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
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
                                            <i class="ti ti-building-store text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->supplier->nama ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $item->supplier->kode_supplier ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div
                                        class="inline-flex items-center px-3 py-2 rounded-lg bg-orange-50 text-orange-700">
                                        <i class="ti ti-shopping-bag text-sm mr-2"></i>
                                        <span class="text-sm font-semibold">{{ $item->detailPembelian->count() }}</span>
                                        <span class="text-xs ml-1">item</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="text-lg font-bold text-gray-900">Rp
                                        {{ number_format($item->total, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                        <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }} shadow-sm">
                                        <i class="ti {{ $jenisTransaksiConfig['icon'] }} text-xs mr-1"></i>
                                        {{ $jenisTransaksiConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('pembelian.show', $item->encrypted_id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>

                                        {{-- Edit button hidden --}}
                                        {{-- @if (!$isMoreThanOneDay)
                                            <a href="{{ route('pembelian.edit', $item->encrypted_id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200"
                                                title="Edit Pembelian">
                                                <i class="ti ti-edit text-sm"></i>
                                            </a>
                                        @else
                                            <button type="button" disabled
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                                title="Tidak dapat diedit setelah H+1">
                                                <i class="ti ti-edit text-sm"></i>
                                            </button>
                                        @endif --}}

                                        @if (!$isMoreThanOneDay)
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->no_faktur }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200"
                                                title="Hapus Pembelian">
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

        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
                @forelse ($pembelian as $item)
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
                    <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:bg-gray-50 transition-all duration-200">
                        <!-- Header Card -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ti ti-receipt text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $item->no_faktur }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $item->user->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                    <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Supplier & Date Row -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2 flex-1 min-w-0">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ti ti-building-store text-white text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $item->supplier->nama ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->supplier->kode_supplier ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-2">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="ti ti-calendar text-white text-xs"></i>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-medium text-gray-900">{{ $item->tanggal->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Items & Total Row -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-shopping-bag text-white text-xs"></i>
                                </div>
                                <div class="inline-flex items-center px-3 py-1 rounded-lg bg-orange-50 text-orange-700">
                                    <i class="ti ti-shopping-bag text-xs mr-1.5"></i>
                                    <span class="text-xs font-semibold">{{ $item->detailPembelian->count() }}</span>
                                    <span class="text-xs ml-1">item</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-bold text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <!-- Jenis Transaksi & Actions Row -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }} shadow-sm">
                                <i class="ti {{ $jenisTransaksiConfig['icon'] }} text-xs mr-1"></i>
                                {{ $jenisTransaksiConfig['label'] }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('pembelian.show', $item->encrypted_id) }}"
                                    class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200"
                                    title="Lihat Detail">
                                    <i class="ti ti-eye text-sm"></i>
                                </a>

                                {{-- Edit button hidden --}}
                                @if (!$isMoreThanOneDay)
                                    {{-- <a href="{{ route('pembelian.edit', $item->encrypted_id) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200"
                                        title="Edit Pembelian">
                                        <i class="ti ti-edit text-sm"></i>
                                    </a> --}}
                                    <button type="button"
                                        onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->no_faktur }}')"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200"
                                        title="Hapus Pembelian">
                                        <i class="ti ti-trash text-sm"></i>
                                    </button>
                                @else
                                    {{-- <button type="button" disabled
                                        class="inline-flex items-center justify-center w-9 h-9 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                        title="Tidak dapat diedit setelah H+1">
                                        <i class="ti ti-edit text-sm"></i>
                                    </button> --}}
                                    <button type="button" disabled
                                        class="inline-flex items-center justify-center w-9 h-9 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                        title="Tidak dapat dihapus setelah H+1">
                                        <i class="ti ti-trash text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="text-gray-500">
                            <i class="ti ti-shopping-cart-off text-5xl mx-auto mb-4 text-gray-400"></i>
                            <p class="text-lg font-medium">Tidak ada transaksi ditemukan</p>
                            <p class="text-sm">Coba ubah filter pencarian atau buat transaksi baru</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        @if (isset($pembelian) && $pembelian->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if ($pembelian->previousPageUrl())
                        <a href="{{ $pembelian->previousPageUrl() }}"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Sebelumnya
                        </a>
                    @else
                        <span
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                            Sebelumnya
                        </span>
                    @endif

                    @if ($pembelian->nextPageUrl())
                        <a href="{{ $pembelian->nextPageUrl() }}"
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
                            Menampilkan <span class="font-medium">{{ $pembelian->firstItem() ?? 0 }}</span> sampai
                            <span class="font-medium">{{ $pembelian->lastItem() ?? 0 }}</span> dari
                            <span class="font-medium">{{ $pembelian->total() }}</span> transaksi
                        </p>
                    </div>
                    <div>
                        {{ $pembelian->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom Flatpickr Styling - Tema Orange untuk Pembelian */
        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
        }

        .flatpickr-months {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
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
            background: #fed7aa !important;
            color: #ea580c !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.selected {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.3) !important;
        }

        .flatpickr-day.selected:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
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
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
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
                onChange: function(selectedDates, dateStr, instance) {
                    // Update hidden input with ISO date format
                    document.getElementById('tanggal_dari_hidden').value = selectedDates[0]
                        .toISOString().split('T')[0];

                    // Update visible input with formatted date
                    instance.input.value = dateStr;
                }
            });

            // Date To Picker
            const dateToPicker = flatpickr("#tanggal_sampai", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Update hidden input with ISO date format
                    document.getElementById('tanggal_sampai_hidden').value = selectedDates[0]
                        .toISOString().split('T')[0];

                    // Update visible input with formatted date
                    instance.input.value = dateStr;
                }
            });

            // Set min date for date_to based on date_from
            dateFromPicker.config.onChange.push(function(selectedDates) {
                if (selectedDates[0]) {
                    dateToPicker.set('minDate', selectedDates[0]);
                }
            });
        });

        function confirmDelete(purchaseId, invoiceNumber) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus transaksi "${invoiceNumber}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#ea580c',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`{{ route('pembelian.destroy', '') }}/${purchaseId}`, {
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
                                    throw new Error(err.message || 'Terjadi kesalahan saat menghapus pembelian');
                                } catch (e) {
                                    throw new Error(text || response.statusText || 'Terjadi kesalahan saat menghapus pembelian');
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
                                text: data.message || 'Pembelian berhasil dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            // Hanya tampilkan message-nya saja, bukan JSON
                            const errorMessage = data.message || 'Terjadi kesalahan saat menghapus pembelian';
                            throw new Error(errorMessage);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Terjadi kesalahan saat menghapus pembelian',
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
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;
            
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
