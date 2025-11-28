@extends('layouts.pos')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
    <style>
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .mobile-card {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
                margin-bottom: 0.75rem;
            }

            .mobile-card:last-child {
                margin-bottom: 0;
            }

            .mobile-text-sm {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }

            .mobile-text-xs {
                font-size: 0.75rem;
                line-height: 1rem;
            }

            /* Ensure proper spacing on mobile */
            .space-y-3>*+* {
                margin-top: 0.75rem;
            }

            /* Make buttons more touch-friendly */
            .mobile-button {
                min-height: 44px;
                padding: 0.75rem 1rem;
            }

            /* Button grid spacing */
            .grid.grid-cols-2.gap-2 {
                gap: 0.5rem;
            }

            /* Ensure buttons are properly sized on mobile */
            .grid.grid-cols-2 .mobile-button {
                font-size: 0.875rem;
                padding: 0.75rem 0.5rem;
            }

            /* Reduce spacing between form elements on mobile */
            .space-y-1>*+* {
                margin-top: 0.25rem;
            }

            /* Make form more compact on mobile */
            .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4.xl\\:grid-cols-6 {
                gap: 0.5rem;
            }

            /* Reduce margin bottom on mobile */
            .mb-2.md\\:mb-3 {
                margin-bottom: 0.5rem;
            }

            /* Mobile card improvements */
            .mobile-card {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
                margin-bottom: 0.75rem;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                transition: box-shadow 0.15s ease-in-out;
            }

            .mobile-card:hover {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            /* Ensure proper text truncation */
            .truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* Right align currency values */
            .text-right {
                text-align: right;
            }

            /* Ensure currency values are properly aligned */
            .mobile-card .text-right {
                text-align: right !important;
            }

        }
    </style>

    <div class="space-y-4 md:space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg md:text-xl font-semibold text-gray-900">Laporan Penjualan</h2>
                <p class="text-xs md:text-sm text-gray-600">Laporan penjualan per periode dengan analisis produk dan
                    pelanggan</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
            <form method="GET" action="{{ route('laporan.penjualan.index') }}" id="laporanForm">
                <!-- Periode Type Selection -->
                <div class="mb-4 md:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Periode</label>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="jenis_periode" value="bulan"
                                {{ $jenisPeriode == 'bulan' ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Per Bulan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="jenis_periode" value="tanggal"
                                {{ $jenisPeriode == 'tanggal' ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Per Tanggal</span>
                        </label>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-2 md:gap-3 mb-2 md:mb-3">
                    <!-- Bulan/Tahun Filter -->
                    <div id="bulanTahunFilter" class="space-y-1"
                        style="display: {{ $jenisPeriode == 'bulan' ? 'block' : 'none' }};">
                        <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            id="bulan" name="bulan">
                            @foreach ($bulanList as $key => $bulan)
                                <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>
                                    {{ $bulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="tahunFilter" class="space-y-1"
                        style="display: {{ $jenisPeriode == 'bulan' ? 'block' : 'none' }};">
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            id="tahun" name="tahun">
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $selectedTahun == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div id="tanggalFilter" class="space-y-1"
                        style="display: {{ $jenisPeriode == 'tanggal' ? 'block' : 'none' }};">
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                        <input type="text" id="tanggal_dari" name="tanggal_dari" value="{{ $tanggalDari }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Pilih tanggal">
                    </div>

                    <div id="tanggalSampaiFilter" class="space-y-1"
                        style="display: {{ $jenisPeriode == 'tanggal' ? 'block' : 'none' }};">
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                        <input type="text" id="tanggal_sampai" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Pilih tanggal">
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-1 sm:col-span-2 lg:col-span-2 xl:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 opacity-0">Actions</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors text-sm mobile-button">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="hidden sm:inline">Generate</span>
                                <span class="sm:hidden">Cari</span>
                            </button>
                            <button type="button" id="printBtn"
                                class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-700 border border-transparent rounded-lg font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600 transition-colors text-sm mobile-button">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 9V2h12v7M6 18h12v4H6zM6 14h12a2 2 0 002-2V9H4v3a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Cetak</span>
                                <span class="sm:hidden">Cetak</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if (isset($laporanData))
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Total Penjualan</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">
                                {{ number_format($laporanData['summary']['total_penjualan'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Total Nilai</p>
                            <p class="text-lg md:text-2xl font-semibold text-gray-900">Rp
                                {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Status & Jenis Transaksi Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Pembayaran -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">LUNAS</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $laporanData['status_counts']['lunas'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">BELUM LUNAS</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ ($laporanData['status_counts']['dp'] ?? 0) + ($laporanData['status_counts']['angsuran'] ?? 0) + ($laporanData['status_counts']['belum_bayar'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Jenis Transaksi -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Jenis Transaksi</h3>
                    <div class="space-y-3">
                        @foreach ($laporanData['jenis_transaksi_counts'] as $jenis => $count)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 capitalize">{{ $jenis }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 text-primary-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Top 5 Produk
                    </h3>
                    <span
                        class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                        {{ count($laporanData['top_products']) }} produk
                    </span>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden space-y-3">
                    @foreach ($laporanData['top_products'] as $index => $product)
                        <div
                            class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Header dengan ranking dan nama produk -->
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-sm mr-3 flex-shrink-0">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $product->nama_produk }}
                                    </h4>
                                    <p class="text-xs text-gray-500">Produk Terlaris</p>
                                </div>
                            </div>

                            <!-- Data metrics dalam layout vertikal -->
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Qty Terjual</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ number_format($product->total_qty, 2, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">{{ $product->nama_satuan ?? 'unit' }}</div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Total Nilai</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900 text-right">Rp
                                        {{ number_format($product->total_nilai, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Produk
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2">
                                            </path>
                                        </svg>
                                        Qty Terjual
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Total Nilai
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($laporanData['top_products'] as $index => $product)
                                <tr
                                    class="hover:bg-gray-50 transition-colors duration-150 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $product->nama_produk }}</div>
                                                <div class="text-xs text-gray-500">Produk Terlaris</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ number_format($product->total_qty, 2, ',', '.') }}</span>
                                            <span
                                                class="ml-2 text-xs text-gray-500">{{ $product->nama_satuan ?? 'unit' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($product->total_nilai, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Customers -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Top 5 Pelanggan
                    </h3>
                    <span
                        class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ count($laporanData['top_customers']) }} pelanggan
                    </span>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden space-y-3">
                    @foreach ($laporanData['top_customers'] as $index => $customer)
                        <div
                            class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Header dengan avatar dan nama pelanggan -->
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3 flex-shrink-0">
                                    {{ strtoupper(substr($customer->nama, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $customer->nama }}</h4>
                                    <p class="text-xs text-gray-500">Pelanggan Setia</p>
                                </div>
                            </div>

                            <!-- Data metrics dalam layout vertikal -->
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Total Transaksi</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900">{{ $customer->total_transaksi }}</div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Total Nilai</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900 text-right">Rp
                                        {{ number_format($customer->total_nilai, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-green-50 to-green-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Pelanggan
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        Total Transaksi
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Total Nilai
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($laporanData['top_customers'] as $index => $customer)
                                <tr
                                    class="hover:bg-gray-50 transition-colors duration-150 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($customer->nama, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $customer->nama }}
                                                </div>
                                                <div class="text-xs text-gray-500">Pelanggan Setia</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $customer->total_transaksi }}</span>
                                            <span class="ml-2 text-xs text-gray-500">transaksi</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($customer->total_nilai, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Transaksi -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Detail Transaksi
                    </h3>
                    <span
                        class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ count($laporanData['penjualan']) }} transaksi
                    </span>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden space-y-3">
                    @foreach ($laporanData['penjualan'] as $index => $transaksi)
                        <div
                            class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Header dengan no faktur dan total dalam layout vertikal -->
                            <div class="mb-3">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm mr-3 flex-shrink-0">
                                        {{ substr($transaksi->no_faktur, -3) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <button onclick="showDetailPenjualan({{ $transaksi->id }})"
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline cursor-pointer transition-colors truncate">
                                            {{ $transaksi->no_faktur }}
                                        </button>
                                        <p class="text-xs text-gray-500">Klik untuk detail</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Total Penjualan</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900 text-right">Rp
                                        {{ number_format($transaksi->total_setelah_diskon, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <!-- Data transaksi dalam layout vertikal -->
                            <div class="space-y-3 mb-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Tanggal</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }}</div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Pelanggan</span>
                                    </div>
                                    <div class="text-lg font-bold text-gray-900 truncate">
                                        {{ $transaksi->pelanggan->nama ?? 'Umum' }}</div>
                                </div>
                            </div>

                            <!-- Status badges -->
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $transaksi->status_pembayaran == 'lunas'
                                        ? 'bg-green-100 text-green-800 border border-green-200'
                                        : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $transaksi->status_pembayaran == 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $transaksi->jenis_transaksi == 'tunai' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z">
                                        </path>
                                    </svg>
                                    {{ strtoupper($transaksi->jenis_transaksi) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        No Faktur
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Tanggal
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Pelanggan
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Jenis
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Total
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($laporanData['penjualan'] as $index => $transaksi)
                                <tr
                                    class="hover:bg-gray-50 transition-colors duration-150 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                                {{ substr($transaksi->no_faktur, -3) }}
                                            </div>
                                            <div class="ml-4">
                                                <button onclick="showDetailPenjualan({{ $transaksi->id }})"
                                                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline cursor-pointer transition-colors">
                                                    {{ $transaksi->no_faktur }}
                                                </button>
                                                <div class="text-xs text-gray-500">Klik untuk detail</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 text-xs font-medium">
                                                {{ strtoupper(substr($transaksi->pelanggan->nama ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $transaksi->pelanggan->nama ?? 'Umum' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $transaksi->status_pembayaran == 'lunas'
                                                ? 'bg-green-100 text-green-800 border border-green-200'
                                                : 'bg-red-100 text-red-800 border border-red-200' }}">
                                            {{ $transaksi->status_pembayaran == 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $transaksi->jenis_transaksi == 'tunai' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                            {{ strtoupper($transaksi->jenis_transaksi) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($transaksi->total_setelah_diskon, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500">Total Bayar</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <script>
        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
            const bulanTahunFilter = document.getElementById('bulanTahunFilter');
            const tahunFilter = document.getElementById('tahunFilter');
            const tanggalFilter = document.getElementById('tanggalFilter');
            const tanggalSampaiFilter = document.getElementById('tanggalSampaiFilter');

            if (jenisPeriode === 'bulan') {
                bulanTahunFilter.style.display = 'block';
                tahunFilter.style.display = 'block';
                tanggalFilter.style.display = 'none';
                tanggalSampaiFilter.style.display = 'none';
            } else {
                bulanTahunFilter.style.display = 'none';
                tahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'block';
                tanggalSampaiFilter.style.display = 'block';
            }
        }

        // Initialize Flatpickr for date inputs
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tanggal_dari", {
                dateFormat: "d/m/Y",
                locale: "id"
            });

            flatpickr("#tanggal_sampai", {
                dateFormat: "d/m/Y",
                locale: "id"
            });

            // Tombol cetak ke halaman khusus (format sama dengan PDF, tapi di browser)
            const printBtn = document.getElementById('printBtn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    const form = document.getElementById('laporanForm');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);

                    const url = '{{ route('laporan.penjualan.print') }}' + '?' + params.toString();
                    window.open(url, '_blank');
                });
            }
        });

        // Modal Detail Penjualan
        function showDetailPenjualan(penjualanId) {
            // Show loading
            Swal.fire({
                title: 'Memuat Detail Penjualan...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Fetch detail penjualan
            fetch(`/penjualan/${penjualanId}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showDetailModal(data.penjualan);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Gagal memuat detail penjualan'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan dalam memuat detail penjualan'
                    });
                });
        }

        function showDetailModal(penjualan) {
            const isMobile = window.innerWidth < 768;
            const detailHtml = `
                <div class="relative max-w-md mx-auto bg-white">
                    <!-- Close Button -->
                    <button onclick="Swal.close()" class="absolute top-2 right-2 w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors z-10">
                        <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Struk Header -->
                    <div class="text-center py-4 border-b border-gray-300">
                        <div class="text-lg font-bold text-gray-800">TOKO KITA</div>
                        <div class="text-xs text-gray-600 mt-1">Jl. Contoh No. 123, Kota</div>
                        <div class="text-xs text-gray-600">Telp: (021) 123-4567</div>
                    </div>

                    <!-- Struk Info -->
                    <div class="py-3 px-4 text-xs">
                        <div class="flex justify-between mb-1">
                            <span>No. Faktur:</span>
                            <span class="font-semibold">${penjualan.no_faktur}</span>
                                </div>
                        <div class="flex justify-between mb-1">
                            <span>Tanggal:</span>
                            <span>${penjualan.tanggal} ${penjualan.jam}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Kasir:</span>
                            <span>${penjualan.kasir?.name || 'Admin'}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Pelanggan:</span>
                            <span>${penjualan.pelanggan?.nama || 'Umum'}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Status:</span>
                            <span class="font-semibold ${penjualan.status_pembayaran === 'lunas' ? 'text-green-600' : 'text-red-600'}">
                                        ${penjualan.status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS'}
                                    </span>
                                </div>
                        <div class="flex justify-between mb-2">
                            <span>Jenis:</span>
                            <span class="font-semibold">${penjualan.jenis_transaksi.toUpperCase()}</span>
                                </div>
                            </div>

                    <!-- Garis Pemisah -->
                    <div class="border-t border-dashed border-gray-400"></div>

                    <!-- Detail Produk -->
                    <div class="py-3 px-4">
                        <div class="text-xs font-semibold mb-2 text-center">DETAIL PRODUK</div>
                        ${penjualan.detail_penjualan.map(detail => `
                                    <div class="mb-3 pb-2 border-b border-gray-200 last:border-b-0">
                                        <div class="text-xs font-semibold text-gray-900 mb-1">${detail.produk?.nama_produk || 'N/A'}</div>
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div class="flex justify-between">
                                                <span>Qty:</span>
                                                <span>${formatNumber(detail.qty, 2)}</span>
                            </div>
                                            <div class="flex justify-between">
                                                <span>Harga:</span>
                                                <span>Rp ${formatNumber(detail.harga)}</span>
                                    </div>
                                            <div class="flex justify-between">
                                                <span>Diskon:</span>
                                                <span>Rp ${formatNumber(detail.diskon || 0)}</span>
                                    </div>
                                            <div class="flex justify-between font-semibold">
                                                <span>Subtotal:</span>
                                                <span>Rp ${formatNumber(detail.subtotal)}</span>
                                    </div>
                                </div>
                            </div>
                                `).join('')}
                    </div>
                    
                    <!-- Garis Pemisah -->
                    <div class="border-t border-dashed border-gray-400"></div>

                    <!-- Total -->
                    <div class="py-3 px-4 text-xs">
                        <div class="flex justify-between mb-1">
                            <span>Subtotal:</span>
                            <span>Rp ${formatNumber(penjualan.total)}</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>Diskon:</span>
                            <span>Rp ${formatNumber(penjualan.diskon)}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold border-t border-gray-300 pt-2 mt-2">
                            <span>TOTAL:</span>
                            <span>Rp ${formatNumber(penjualan.total_setelah_diskon)}</span>
                        </div>
                    </div>

                    <!-- Garis Pemisah -->
                    <div class="border-t border-dashed border-gray-400"></div>

                    <!-- Footer -->
                    <div class="py-4 px-4 text-center text-xs text-gray-600">
                        <div class="mb-2">Terima kasih atas kunjungan Anda</div>
                        <div>Barang yang sudah dibeli tidak dapat dikembalikan</div>
                        <div class="mt-2 text-xs text-gray-500">${new Date().toLocaleString('id-ID')}</div>
                    </div>
                </div>
            `;

            Swal.fire({
                html: detailHtml,
                width: window.innerWidth < 768 ? '95%' : '900px',
                showConfirmButton: false,
                customClass: {
                    popup: 'text-left rounded-lg shadow-2xl'
                },
                showCloseButton: false,
                allowOutsideClick: true
            });
        }

        function getStatusClass(status) {
            switch (status) {
                case 'lunas':
                    return 'bg-green-100 text-green-800';
                case 'dp':
                    return 'bg-yellow-100 text-yellow-800';
                case 'angsuran':
                    return 'bg-blue-100 text-blue-800';
                default:
                    return 'bg-red-100 text-red-800';
            }
        }

        function formatNumber(num, decimals = 0) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(num);
        }
    </script>
@endsection
