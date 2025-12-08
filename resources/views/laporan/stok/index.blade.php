@extends('layouts.pos')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Stok</h2>
                <p class="text-sm text-gray-600">Laporan saldo awal dan pergerakan stok produk per periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6 no-print">
            <form method="GET" action="{{ route('laporan.stok.index') }}" id="laporanForm">
                <!-- Header: Periode + Actions (compact) -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 md:gap-4 mb-4 border-b border-gray-100 pb-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Periode Laporan</p>
                        <div class="mt-2 flex flex-wrap gap-3 text-xs md:text-sm text-gray-700">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_periode" value="bulan" {{ $jenisPeriode == 'bulan' ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300" onchange="togglePeriodeType()">
                                <span>Per Bulan</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_periode" value="tanggal" {{ $jenisPeriode == 'tanggal' ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300" onchange="togglePeriodeType()">
                                <span>Periode Tanggal</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full lg:w-auto">
                        <button type="submit"
                            class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 border border-transparent rounded-lg font-medium text-white text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Tampilkan</span>
                            <span class="sm:hidden">Cari</span>
                        </button>

                        @if (isset($laporanData) && $laporanData)
                            <button type="button" id="printBtn"
                                class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gray-800 border border-transparent rounded-lg font-medium text-white text-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 9V2h12v7M6 18h12v4H6zM6 14h12a2 2 0 002-2V9H4v3a2 2 0 002 2z" />
                                </svg>
                                <span class="hidden sm:inline">Cetak</span>
                                <span class="sm:hidden">Cetak</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Desktop Layout - Filters (full-width, dibagi rata per field) -->
                <div class="hidden lg:grid grid-cols-3 gap-4 mb-4">
                    <!-- Produk Filter -->
                    <div>
                        <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-1">Produk
                            <span class="text-red-500">*</span></label>
                        <select name="produk_id" id="produk_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="">Pilih Produk</option>
                            @foreach ($produkList as $produk)
                                <option value="{{ $produk->id }}" {{ $selectedProduk == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }} ({{ $produk->kategori->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan Filter (for bulan type) -->
                    <div id="bulanFilterDesktop" class="{{ $jenisPeriode == 'tanggal' ? 'hidden' : '' }}">
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" id="bulan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            @foreach ($bulanList as $key => $bulan)
                                <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>
                                    {{ $bulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun Filter (for bulan type) -->
                    <div id="tahunFilterDesktop" class="{{ $jenisPeriode == 'tanggal' ? 'hidden' : '' }}">
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Dari (for tanggal type) -->
                    <div id="tanggalDariFilterDesktop" class="{{ $jenisPeriode == 'bulan' ? 'hidden' : '' }}">
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                        <div class="relative">
                            <input type="text" id="tanggal_dari" readonly
                                value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                                placeholder="Pilih tanggal"
                                class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                            <input type="hidden" name="tanggal_dari" id="tanggal_dari_hidden"
                                value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') : '' }}">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Sampai (for tanggal type) -->
                    <div id="tanggalSampaiFilterDesktop" class="{{ $jenisPeriode == 'bulan' ? 'hidden' : '' }}">
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                        <div class="relative">
                            <input type="text" id="tanggal_sampai" readonly
                                value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                                placeholder="Pilih tanggal"
                                class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                            <input type="hidden" name="tanggal_sampai" id="tanggal_sampai_hidden"
                                value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') : '' }}">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile/Tablet Layout - Responsive grid -->
                <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3 mb-2 md:mb-3">
                    <!-- Produk Filter -->
                    <div class="sm:col-span-2">
                        <label for="produk_id_mobile" class="block text-sm font-medium text-gray-700 mb-1">Produk
                            <span class="text-red-500">*</span></label>
                        <select id="produk_id_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="">Pilih Produk</option>
                            @foreach ($produkList as $produk)
                                <option value="{{ $produk->id }}" {{ $selectedProduk == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }} ({{ $produk->kategori->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan/Tahun Filter (for bulan type) -->
                    <div id="bulanTahunFilterMobile" class="sm:col-span-2 {{ $jenisPeriode == 'tanggal' ? 'hidden' : '' }}"
                        style="display: {{ $jenisPeriode == 'tanggal' ? 'none' : 'block' }};">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bulan Filter -->
                            <div>
                                <label for="bulan_mobile" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                <select id="bulan_mobile"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                    @foreach ($bulanList as $key => $bulan)
                                        <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>
                                            {{ $bulan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tahun Filter -->
                            <div>
                                <label for="tahun_mobile" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                <select id="tahun_mobile"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                    @foreach ($tahunList as $tahun)
                                        <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Filter (for tanggal type) -->
                    <div id="tanggalFilterMobile" class="sm:col-span-2 {{ $jenisPeriode == 'bulan' ? 'hidden' : '' }}"
                        style="display: {{ $jenisPeriode == 'bulan' ? 'none' : 'block' }};">
                        <!-- Tanggal Dari -->
                        <div class="mb-2">
                            <label for="tanggal_dari_mobile" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                            <div class="relative">
                                <input type="text" id="tanggal_dari_mobile" readonly
                                    value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                                    placeholder="Pilih tanggal"
                                    class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                                <input type="hidden" id="tanggal_dari_mobile_hidden"
                                    value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') : '' }}">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <i class="ti ti-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Sampai -->
                        <div>
                            <label for="tanggal_sampai_mobile" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                            <div class="relative">
                                <input type="text" id="tanggal_sampai_mobile" readonly
                                    value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                                    placeholder="Pilih tanggal"
                                    class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                                <input type="hidden" id="tanggal_sampai_mobile_hidden"
                                    value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') : '' }}">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <i class="ti ti-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Action Buttons diheader (lihat header di atas) -->
            </form>
        </div>

        <!-- Laporan Data -->
        @if (isset($laporanData) && $laporanData)
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                <!-- Saldo Awal -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-package text-blue-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Saldo Awal</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Pembelian -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-trending-up text-green-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Pembelian</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_pembelian'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Penjualan -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-trending-down text-red-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Penjualan</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_penjualan'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Penyesuaian -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-adjustments text-yellow-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Penyesuaian</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_penyesuaian'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-currency-dollar text-purple-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Saldo Akhir</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards Row 2 (Rp) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-4">
                <div class="bg-red-50 border border-red-200 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-cash text-red-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-red-800">Total Pembelian (Rp)</p>
                            <p class="text-lg md:text-2xl font-bold text-red-900">
                                {{ number_format($laporanData['summary']['total_pembelian_uang'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-currency-dollar text-green-700 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-green-800">Total Penjualan (Rp)</p>
                            <p class="text-lg md:text-2xl font-bold text-green-900">
                                {{ number_format($laporanData['summary']['total_penjualan_uang'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Header -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
                    <div class="mb-3 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Stok</h3>
                        <p class="text-sm text-gray-600">
                            {{ $laporanData['produk']['nama_produk'] }}
                            @if ($laporanData['periode']['jenis'] == 'tanggal')
                                - {{ $laporanData['periode']['tanggal_dari'] }} s/d
                                {{ $laporanData['periode']['tanggal_sampai'] }}
                            @else
                                - {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">
                            @if ($laporanData['periode']['jenis'] == 'tanggal')
                                Periode Tanggal: {{ $laporanData['periode']['tanggal_dari'] }} s/d
                                {{ $laporanData['periode']['tanggal_sampai'] }}
                            @else
                                Periode: {{ $laporanData['periode']['tanggal_awal'] }} s/d
                                {{ $laporanData['periode']['tanggal_akhir'] }}
                            @endif
                        </p>
                        @if ($laporanData['periode']['jenis'] == 'tanggal')
                            @if (isset($laporanData['saldo_awal_terakhir']) && $laporanData['saldo_awal_terakhir'])
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal terakhir ({{ $laporanData['saldo_awal_terakhir']['periode_saldo_awal'] }}):
                                    {{ number_format($laporanData['saldo_awal_terakhir']['saldo'], 2, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="ti ti-calendar mr-1"></i>
                                    Dihitung dari {{ $laporanData['saldo_awal_terakhir']['tanggal_mulai_hitung'] }} s/d
                                    {{ $laporanData['periode']['tanggal_dari'] }}
                                </p>
                            @elseif (isset($laporanData['saldo_awal_bulan']) && $laporanData['saldo_awal_bulan'] > 0)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal bulan {{ $laporanData['periode']['bulan_nama'] }}:
                                    {{ number_format($laporanData['saldo_awal_bulan'], 2, ',', '.') }}
                                </p>
                            @endif
                        @else
                            @if (isset($laporanData['saldo_awal_terakhir']) && $laporanData['saldo_awal_terakhir'])
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal terakhir ({{ $laporanData['saldo_awal_terakhir']['periode_saldo_awal'] }}):
                                    {{ number_format($laporanData['saldo_awal_terakhir']['saldo'], 2, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="ti ti-calendar mr-1"></i>
                                    Dihitung dari {{ $laporanData['saldo_awal_terakhir']['tanggal_mulai_hitung'] }} s/d
                                    {{ $laporanData['periode']['tanggal_awal'] }}
                                </p>
                            @elseif (isset($laporanData['saldo_awal_bulan']) && $laporanData['saldo_awal_bulan'] > 0)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal bulan {{ $laporanData['periode']['bulan_nama'] }}:
                                    {{ number_format($laporanData['saldo_awal_bulan'], 2, ',', '.') }}
                                </p>
                            @endif
                        @endif
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm text-gray-500">Dicetak pada:</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <p class="text-xs md:text-sm text-gray-500">Kategori</p>
                        <p class="text-lg md:text-lg font-bold text-gray-900">{{ $laporanData['produk']['kategori'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <p class="text-xs md:text-sm text-gray-500">Satuan</p>
                        <p class="text-lg md:text-lg font-bold text-gray-900">{{ $laporanData['produk']['satuan'] }}</p>
                    </div>
                </div>

                <!-- Product Photo -->
                <div class="flex justify-center mb-4 md:mb-6">
                    @if ($laporanData['produk']['foto'])
                        <img src="{{ asset('storage/' . $laporanData['produk']['foto']) }}" alt="{{ $laporanData['produk']['nama_produk'] }}"
                            class="w-24 h-24 md:w-32 md:h-32 rounded-lg object-cover border-4 border-gray-200">
                    @else
                        <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 rounded-lg flex items-center justify-center border-4 border-gray-200">
                            <i class="ti ti-photo text-gray-400 text-3xl md:text-4xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Mobile Summary Cards -->
                <div class="block md:hidden space-y-3 mb-6">
                    <!-- Saldo Awal Card -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium text-blue-800">Saldo Awal</div>
                            <div class="text-lg font-bold text-blue-900">
                                {{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Total Pembelian Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-700">Total Pembelian</div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_pembelian'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Total Pembelian (Rp) Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-700">Total Pembelian (Rp)</div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_pembelian_uang'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Total Penjualan Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-700">Total Penjualan</div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_penjualan'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Total Penjualan (Rp) Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-700">Total Penjualan (Rp)</div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_penjualan_uang'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Total Penyesuaian Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-700">Total Penyesuaian</div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_penyesuaian'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Saldo Akhir Card -->
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-bold text-purple-800">Saldo Akhir</div>
                            <div class="text-lg font-bold text-purple-900">
                                {{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Summary Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Qty
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Harga
                                </th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="bg-blue-50">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    Saldo Awal
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-bold text-gray-900">
                                    {{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-500">
                                    -
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    Total Pembelian
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($laporanData['summary']['total_pembelian'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($laporanData['summary']['total_pembelian_uang'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    Total Penjualan
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($laporanData['summary']['total_penjualan'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($laporanData['summary']['total_penjualan_uang'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    Total Penyesuaian
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($laporanData['summary']['total_penyesuaian'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($laporanData['summary']['total_penyesuaian_uang'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="bg-purple-50 border-t-2 border-purple-200">
                                <td class="px-4 py-4 text-sm font-bold text-gray-900">
                                    Saldo Akhir (Qty)
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-bold text-gray-900">
                                    {{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-bold text-gray-900">
                                    {{ number_format($laporanData['summary']['nilai_stok'] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Detail Transaksi -->
                @if ($laporanData['transaksi']->count() > 0)
                    <div class="mt-6 md:mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Transaksi</h4>

                        <!-- Mobile Card View -->
                        <div class="block md:hidden space-y-3">
                            @php
                                $runningSaldo = $laporanData['saldo_awal'];
                            @endphp

                            <!-- Saldo Awal Card -->
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-sm font-medium text-blue-800 mb-1">Saldo Awal</div>
                                        <div class="text-xs text-blue-600">
                                            @if ($laporanData['periode']['jenis'] == 'tanggal')
                                                {{ $laporanData['periode']['tanggal_dari'] }}
                                            @else
                                                {{ $laporanData['periode']['tanggal_awal'] }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">In:</span>
                                        <span class="text-sm font-semibold text-gray-900">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">Out:</span>
                                        <span class="text-sm font-semibold text-gray-900">-</span>
                                    </div>
                                    <div class="flex justify-between border-t border-blue-200 pt-2">
                                        <span class="text-xs font-semibold text-blue-800">Saldo:</span>
                                        <span class="text-sm font-bold text-blue-900">
                                            {{ number_format($runningSaldo, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Transaksi Cards -->
                            @foreach ($laporanData['transaksi'] as $transaksi)
                                @php
                                    if ($transaksi->jenis == 'pembelian') {
                                        $runningSaldo += $transaksi->jumlah;
                                    } elseif ($transaksi->jenis == 'penjualan') {
                                        $runningSaldo -= $transaksi->jumlah;
                                    } elseif ($transaksi->jenis == 'penyesuaian') {
                                        $runningSaldo += $transaksi->jumlah;
                                    }
                                @endphp
                                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 mb-1">
                                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-600 mb-1">
                                                {{ $transaksi->keterangan }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                No: {{ $transaksi->no_transaksi }}
                                            </div>
                                        </div>
                                        <div>
                                            @if ($transaksi->jenis == 'pembelian')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Pembelian
                                                </span>
                                            @elseif ($transaksi->jenis == 'penjualan')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Penjualan
                                                </span>
                                            @elseif ($transaksi->jenis == 'penyesuaian')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Penyesuaian
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-xs text-gray-600">In:</span>
                                            <span class="text-sm font-semibold text-gray-900">
                                                @if ($transaksi->jenis == 'pembelian')
                                                    {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                                @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah > 0)
                                                    {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-xs text-gray-600">Out:</span>
                                            <span class="text-sm font-semibold text-gray-900">
                                                @if ($transaksi->jenis == 'penjualan')
                                                    {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                                @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah < 0)
                                                    {{ number_format(abs($transaksi->jumlah), 2, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-xs text-gray-600">Total Harga:</span>
                                            <span class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 pt-2">
                                            <span class="text-xs font-semibold text-gray-800">Saldo:</span>
                                            <span class="text-sm font-bold text-gray-900">
                                                {{ number_format($runningSaldo, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kategori
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No. Transaksi
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            In
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Out
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Harga
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Saldo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $runningSaldo = $laporanData['saldo_awal'];
                                    @endphp

                                    <!-- Baris Saldo Awal -->
                                    <tr class="bg-blue-50 font-medium">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if ($laporanData['periode']['jenis'] == 'tanggal')
                                                {{ $laporanData['periode']['tanggal_dari'] }}
                                            @else
                                                {{ $laporanData['periode']['tanggal_awal'] }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            Saldo Awal
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Saldo Awal
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            -
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                            -
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                            -
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                            -
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm font-bold text-blue-600">
                                            {{ number_format($runningSaldo, 2, ',', '.') }}
                                        </td>
                                    </tr>

                                    @foreach ($laporanData['transaksi'] as $transaksi)
                                        @php
                                            if ($transaksi->jenis == 'pembelian') {
                                                $runningSaldo += $transaksi->jumlah;
                                            } elseif ($transaksi->jenis == 'penjualan') {
                                                $runningSaldo -= $transaksi->jumlah;
                                            } elseif ($transaksi->jenis == 'penyesuaian') {
                                                $runningSaldo += $transaksi->jumlah;
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                {{ $transaksi->keterangan }}
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                @if ($transaksi->jenis == 'pembelian')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Pembelian
                                                    </span>
                                                @elseif ($transaksi->jenis == 'penjualan')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Penjualan
                                                    </span>
                                                @elseif ($transaksi->jenis == 'penyesuaian')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Penyesuaian
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-900">
                                                {{ $transaksi->no_transaksi }}
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                                @if ($transaksi->jenis == 'pembelian')
                                                    {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                                @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah > 0)
                                                    {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                                @if ($transaksi->jenis == 'penjualan')
                                                    {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                                @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah < 0)
                                                    {{ number_format(abs($transaksi->jumlah), 2, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                                {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm font-bold text-gray-900">
                                                {{ number_format($runningSaldo, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="ti ti-package text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Produk untuk Melihat Laporan Stok</h3>
                    <p class="text-gray-500 text-sm mb-6">
                        Pilih produk terlebih dahulu, kemudian pilih periode untuk menampilkan laporan stok
                    </p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .mobile-button {
            @apply text-sm;
        }

        @media (max-width: 640px) {
            .mobile-button {
                @apply text-xs px-3 py-2;
            }
        }

        /* Select2 Custom Styling for Clear Button */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 25px;
            font-size: 20px;
            font-weight: bold;
            line-height: 1;
            padding: 4px 6px;
            cursor: pointer;
            color: #999;
            transition: all 0.2s;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            position: relative;
            z-index: 10;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #dc2626;
            background-color: #fee2e2;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:active {
            background-color: #fecaca;
            transform: scale(0.95);
        }

        /* Print styles */
        @media print {
            body {
                background: #ffffff !important;
            }

            /* Sembunyikan elemen yang tidak perlu saat cetak */
            header,
            footer,
            nav,
            .no-print,
            #printBtn {
                display: none !important;
            }

            /* Full width untuk konten laporan */
            .print-container {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            /* Hindari pemotongan aneh di dalam tabel */
            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>

    <script>
        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePeriodeType();
            // Initialize Flatpickr dan Select2
            initializeFlatpickr();
            initializeSelect2();
            setupFormSynchronization();


            // Add event listener for print button
            const printBtn = document.getElementById('printBtn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    const form = document.getElementById('laporanForm');
                    const formData = new FormData(form);

                    // Get produk_id from Select2 (jika menggunakan Select2)
                    const produkId = typeof jQuery !== 'undefined' && $('#produk_id').length ?
                        $('#produk_id').val() : document.getElementById('produk_id').value;
                    if (produkId) {
                        formData.set('produk_id', produkId);
                    }

                    // Use hidden inputs for date format if jenis_periode is tanggal
                    const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked')
                        .value;
                    if (jenisPeriode === 'tanggal') {
                        const tanggalDariHidden = document.getElementById('tanggal_dari_hidden');
                        const tanggalSampaiHidden = document.getElementById('tanggal_sampai_hidden');

                        if (tanggalDariHidden && tanggalDariHidden.value) {
                            formData.set('tanggal_dari', tanggalDariHidden.value);
                        }
                        if (tanggalSampaiHidden && tanggalSampaiHidden.value) {
                            formData.set('tanggal_sampai', tanggalSampaiHidden.value);
                        }
                    }

                    const params = new URLSearchParams(formData);
                    const url = '{{ route('laporan.stok.print') }}' + '?' + params.toString();
                    window.open(url, '_blank');
                });
            }

            // Handle form submission - sync mobile to desktop sebelum submit
            const form = document.getElementById('laporanForm');
            form.addEventListener('submit', function(e) {
                // Sync mobile values to desktop (karena mobile tidak punya name attribute)
                const produkIdMobile = document.getElementById('produk_id_mobile');
                const produkIdDesktop = document.getElementById('produk_id');
                if (produkIdMobile && produkIdDesktop && produkIdMobile.value) {
                    produkIdDesktop.value = produkIdMobile.value;
                }

                const bulanMobile = document.getElementById('bulan_mobile');
                const bulanDesktop = document.getElementById('bulan');
                if (bulanMobile && bulanDesktop && bulanMobile.value) {
                    bulanDesktop.value = bulanMobile.value;
                }

                const tahunMobile = document.getElementById('tahun_mobile');
                const tahunDesktop = document.getElementById('tahun');
                if (tahunMobile && tahunDesktop && tahunMobile.value) {
                    tahunDesktop.value = tahunMobile.value;
                }

                // Sync tanggal mobile hidden ke desktop hidden (desktop yang punya name attribute untuk submit)
                const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
                if (jenisPeriode === 'tanggal') {
                    const tanggalDariMobileHidden = document.getElementById('tanggal_dari_mobile_hidden');
                    const tanggalSampaiMobileHidden = document.getElementById('tanggal_sampai_mobile_hidden');
                    const tanggalDariHidden = document.getElementById('tanggal_dari_hidden');
                    const tanggalSampaiHidden = document.getElementById('tanggal_sampai_hidden');

                    // Sync mobile ke desktop (prioritaskan desktop jika sudah ada nilai)
                    if (tanggalDariHidden) {
                        // Jika desktop kosong tapi mobile ada nilai, ambil dari mobile
                        if (!tanggalDariHidden.value && tanggalDariMobileHidden && tanggalDariMobileHidden.value) {
                            tanggalDariHidden.value = tanggalDariMobileHidden.value;
                        }
                        // Jika desktop ada nilai, tetap gunakan desktop (tidak di-overwrite)
                    }

                    if (tanggalSampaiHidden) {
                        // Jika desktop kosong tapi mobile ada nilai, ambil dari mobile
                        if (!tanggalSampaiHidden.value && tanggalSampaiMobileHidden && tanggalSampaiMobileHidden.value) {
                            tanggalSampaiHidden.value = tanggalSampaiMobileHidden.value;
                        }
                        // Jika desktop ada nilai, tetap gunakan desktop (tidak di-overwrite)
                    }
                }
            });
        });

        function initializeFlatpickr() {
            // Date From Picker (Desktop)
            const tanggalDariInput = document.getElementById('tanggal_dari');
            if (tanggalDariInput) {
                const dateFromPicker = flatpickr(tanggalDariInput, {
                    locale: "id",
                    dateFormat: "d/m/Y",
                    allowInput: false,
                    clickOpens: true,
                    @if (request('tanggal_dari'))
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
                            instance.input.value = dateStr;

                            // Update tanggal_sampai min date
                            const tanggalSampaiInput = document.getElementById('tanggal_sampai');
                            if (tanggalSampaiInput && tanggalSampaiInput._flatpickr) {
                                tanggalSampaiInput._flatpickr.set('minDate', date);
                            }
                        }
                    }
                });
            }

            // Date To Picker (Desktop)
            const tanggalSampaiInput = document.getElementById('tanggal_sampai');
            if (tanggalSampaiInput) {
                const dateToPicker = flatpickr(tanggalSampaiInput, {
                    locale: "id",
                    dateFormat: "d/m/Y",
                    allowInput: false,
                    clickOpens: true,
                    @if (request('tanggal_sampai'))
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
                            instance.input.value = dateStr;
                        }
                    }
                });

                // Set min date for date_to based on date_from
                if (tanggalDariInput && tanggalDariInput._flatpickr) {
                    tanggalDariInput._flatpickr.config.onChange.push(function(selectedDates) {
                        if (selectedDates[0]) {
                            dateToPicker.set('minDate', selectedDates[0]);
                        }
                    });
                }
            }

            // Date From Picker (Mobile)
            const tanggalDariMobileInput = document.getElementById('tanggal_dari_mobile');
            if (tanggalDariMobileInput) {
                const dateFromPickerMobile = flatpickr(tanggalDariMobileInput, {
                    locale: "id",
                    dateFormat: "d/m/Y",
                    allowInput: false,
                    clickOpens: true,
                    @if (request('tanggal_dari'))
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

                            const tanggalDariMobileHidden = document.getElementById('tanggal_dari_mobile_hidden');
                            const tanggalDariHidden = document.getElementById('tanggal_dari_hidden');
                            if (tanggalDariMobileHidden) {
                                tanggalDariMobileHidden.value = isoDate;
                            }
                            // Sync dengan desktop hidden (yang punya name attribute)
                            if (tanggalDariHidden) {
                                tanggalDariHidden.value = isoDate;
                            }
                            instance.input.value = dateStr;

                            // Update tanggal_sampai min date
                            const tanggalSampaiMobileInput = document.getElementById('tanggal_sampai_mobile');
                            if (tanggalSampaiMobileInput && tanggalSampaiMobileInput._flatpickr) {
                                tanggalSampaiMobileInput._flatpickr.set('minDate', date);
                            }
                        }
                    }
                });
            }

            // Date To Picker (Mobile)
            const tanggalSampaiMobileInput = document.getElementById('tanggal_sampai_mobile');
            if (tanggalSampaiMobileInput) {
                const dateToPickerMobile = flatpickr(tanggalSampaiMobileInput, {
                    locale: "id",
                    dateFormat: "d/m/Y",
                    allowInput: false,
                    clickOpens: true,
                    @if (request('tanggal_sampai'))
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

                            const tanggalSampaiMobileHidden = document.getElementById('tanggal_sampai_mobile_hidden');
                            const tanggalSampaiHidden = document.getElementById('tanggal_sampai_hidden');
                            if (tanggalSampaiMobileHidden) {
                                tanggalSampaiMobileHidden.value = isoDate;
                            }
                            // Sync dengan desktop hidden (yang punya name attribute)
                            if (tanggalSampaiHidden) {
                                tanggalSampaiHidden.value = isoDate;
                            }
                            instance.input.value = dateStr;
                        }
                    }
                });

                // Set min date for date_to based on date_from
                if (tanggalDariMobileInput && tanggalDariMobileInput._flatpickr) {
                    tanggalDariMobileInput._flatpickr.config.onChange.push(function(selectedDates) {
                        if (selectedDates[0]) {
                            dateToPickerMobile.set('minDate', selectedDates[0]);
                        }
                    });
                }
            }

            // Initialize hidden inputs with ISO format if defaultDate is set
            @if (request('tanggal_dari'))
                const tanggalDariValue = "{{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') }}";
                document.getElementById('tanggal_dari_hidden').value = tanggalDariValue;
                const tanggalDariMobileHidden = document.getElementById('tanggal_dari_mobile_hidden');
                if (tanggalDariMobileHidden) {
                    tanggalDariMobileHidden.value = tanggalDariValue;
                }
            @endif

            @if (request('tanggal_sampai'))
                const tanggalSampaiValue = "{{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') }}";
                document.getElementById('tanggal_sampai_hidden').value = tanggalSampaiValue;
                const tanggalSampaiMobileHidden = document.getElementById('tanggal_sampai_mobile_hidden');
                if (tanggalSampaiMobileHidden) {
                    tanggalSampaiMobileHidden.value = tanggalSampaiValue;
                }
            @endif
        }

        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;

            // Desktop elements
            const bulanFilterDesktop = document.getElementById('bulanFilterDesktop');
            const tahunFilterDesktop = document.getElementById('tahunFilterDesktop');
            const tanggalDariFilterDesktop = document.getElementById('tanggalDariFilterDesktop');
            const tanggalSampaiFilterDesktop = document.getElementById('tanggalSampaiFilterDesktop');

            // Mobile elements
            const bulanTahunFilterMobile = document.getElementById('bulanTahunFilterMobile');
            const tanggalFilterMobile = document.getElementById('tanggalFilterMobile');

            // Get form elements
            const bulanSelect = document.getElementById('bulan');
            const bulanMobileSelect = document.getElementById('bulan_mobile');
            const tahunSelect = document.getElementById('tahun');
            const tahunMobileSelect = document.getElementById('tahun_mobile');
            const tanggalDariInput = document.getElementById('tanggal_dari');
            const tanggalDariMobileInput = document.getElementById('tanggal_dari_mobile');
            const tanggalSampaiInput = document.getElementById('tanggal_sampai');
            const tanggalSampaiMobileInput = document.getElementById('tanggal_sampai_mobile');

            if (jenisPeriode === 'bulan') {
                // Show bulan/tahun filters, hide tanggal filters
                if (bulanFilterDesktop) bulanFilterDesktop.classList.remove('hidden');
                if (tahunFilterDesktop) tahunFilterDesktop.classList.remove('hidden');
                if (tanggalDariFilterDesktop) tanggalDariFilterDesktop.classList.add('hidden');
                if (tanggalSampaiFilterDesktop) tanggalSampaiFilterDesktop.classList.add('hidden');

                if (bulanTahunFilterMobile) bulanTahunFilterMobile.style.display = 'block';
                if (tanggalFilterMobile) tanggalFilterMobile.style.display = 'none';

                // Clear tanggal values (both display and hidden)
                if (tanggalDariInput) {
                    tanggalDariInput.value = '';
                    if (tanggalDariInput._flatpickr) {
                        tanggalDariInput._flatpickr.clear();
                    }
                }
                const tanggalDariHidden = document.getElementById('tanggal_dari_hidden');
                if (tanggalDariHidden) {
                    tanggalDariHidden.value = '';
                }
                if (tanggalDariMobileInput) {
                    tanggalDariMobileInput.value = '';
                    if (tanggalDariMobileInput._flatpickr) {
                        tanggalDariMobileInput._flatpickr.clear();
                    }
                }
                const tanggalDariMobileHidden = document.getElementById('tanggal_dari_mobile_hidden');
                if (tanggalDariMobileHidden) {
                    tanggalDariMobileHidden.value = '';
                }
                if (tanggalSampaiInput) {
                    tanggalSampaiInput.value = '';
                    if (tanggalSampaiInput._flatpickr) {
                        tanggalSampaiInput._flatpickr.clear();
                    }
                }
                const tanggalSampaiHidden = document.getElementById('tanggal_sampai_hidden');
                if (tanggalSampaiHidden) {
                    tanggalSampaiHidden.value = '';
                }
                if (tanggalSampaiMobileInput) {
                    tanggalSampaiMobileInput.value = '';
                    if (tanggalSampaiMobileInput._flatpickr) {
                        tanggalSampaiMobileInput._flatpickr.clear();
                    }
                }
                const tanggalSampaiMobileHidden = document.getElementById('tanggal_sampai_mobile_hidden');
                if (tanggalSampaiMobileHidden) {
                    tanggalSampaiMobileHidden.value = '';
                }

                // Set default values for bulan and tahun if they are empty
                const currentMonth = new Date().getMonth() + 1; // getMonth() returns 0-11
                const currentYear = new Date().getFullYear();

                if (bulanSelect && !bulanSelect.value) {
                    bulanSelect.value = currentMonth;
                }
                if (bulanMobileSelect && !bulanMobileSelect.value) {
                    bulanMobileSelect.value = currentMonth;
                }
                if (tahunSelect && !tahunSelect.value) {
                    tahunSelect.value = currentYear;
                }
                if (tahunMobileSelect && !tahunMobileSelect.value) {
                    tahunMobileSelect.value = currentYear;
                }
            } else {
                // Hide bulan/tahun filters, show tanggal filters
                if (bulanFilterDesktop) bulanFilterDesktop.classList.add('hidden');
                if (tahunFilterDesktop) tahunFilterDesktop.classList.add('hidden');
                if (tanggalDariFilterDesktop) tanggalDariFilterDesktop.classList.remove('hidden');
                if (tanggalSampaiFilterDesktop) tanggalSampaiFilterDesktop.classList.remove('hidden');

                if (bulanTahunFilterMobile) bulanTahunFilterMobile.style.display = 'none';
                if (tanggalFilterMobile) tanggalFilterMobile.style.display = 'block';

                // Don't clear bulan/tahun values, just hide them
                // This way when user switches back, the values are preserved

                // Re-initialize flatpickr for date inputs
                setTimeout(() => {
                    initializeFlatpickr();
                }, 100);
            }
        }

        function initializeSelect2() {
            // Wait for jQuery to be available
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is required for Select2');
                return;
            }

            // Initialize Select2 for desktop produk_id
            const produkDesktop = $('#produk_id');
            if (produkDesktop.length) {
                produkDesktop.select2({
                    placeholder: 'Pilih Produk',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true,
                    language: {
                        noResults: function() {
                            return "Produk tidak ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                }).on('select2:select', function() {
                    // Close dropdown after selection
                    $(this).trigger('blur');
                });
            }

            // Initialize Select2 for mobile produk_id_mobile
            const produkMobile = $('#produk_id_mobile');
            if (produkMobile.length) {
                produkMobile.select2({
                    placeholder: 'Pilih Produk',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true,
                    language: {
                        noResults: function() {
                            return "Produk tidak ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                }).on('select2:select', function() {
                    // Close dropdown after selection
                    $(this).trigger('blur');
                });
            }
        }

        function setupFormSynchronization() {
            // Sync produk_id dengan Select2
            const produkDesktop = $('#produk_id');
            const produkMobile = $('#produk_id_mobile');

            if (produkDesktop.length && produkMobile.length) {
                let isSyncing = false;

                produkDesktop.on('select2:select select2:clear', function() {
                    if (isSyncing) return;
                    isSyncing = true;
                    const value = $(this).val();
                    if (produkMobile.val() !== value) {
                        produkMobile.val(value).trigger('change');
                    }
                    setTimeout(() => {
                        isSyncing = false;
                    }, 200);
                });

                produkMobile.on('select2:select select2:clear', function() {
                    if (isSyncing) return;
                    isSyncing = true;
                    const value = $(this).val();
                    if (produkDesktop.val() !== value) {
                        produkDesktop.val(value).trigger('change');
                    }
                    setTimeout(() => {
                        isSyncing = false;
                    }, 200);
                });
            }

            // Sync bulan
            const bulanDesktop = document.getElementById('bulan');
            const bulanMobile = document.getElementById('bulan_mobile');
            if (bulanDesktop && bulanMobile) {
                bulanDesktop.addEventListener('change', function() {
                    bulanMobile.value = this.value;
                });
                bulanMobile.addEventListener('change', function() {
                    bulanDesktop.value = this.value;
                });
            }

            // Sync tahun
            const tahunDesktop = document.getElementById('tahun');
            const tahunMobile = document.getElementById('tahun_mobile');
            if (tahunDesktop && tahunMobile) {
                tahunDesktop.addEventListener('change', function() {
                    tahunMobile.value = this.value;
                });
                tahunMobile.addEventListener('change', function() {
                    tahunDesktop.value = this.value;
                });
            }

            // Tanggal sync sudah ditangani oleh Flatpickr onChange handler
        }

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
                    icon = `<i class="ti ti-info-circle text-lg mr-2"></i>`;
                    break;
                default:
                    bgColor = 'bg-blue-500';
                    icon = `<i class="ti ti-info-circle text-lg mr-2"></i>`;
            }

            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 notification transform transition-all duration-300 translate-x-full`;
            notification.innerHTML = `
                <div class="flex items-center">
                    ${icon}
                    ${message}
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(function() {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(function() {
                notification.classList.add('translate-x-full');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 4000);
        }
    </script>
@endsection
