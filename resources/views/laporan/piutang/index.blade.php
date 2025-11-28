@extends('layouts.pos')

@section('title', 'Laporan Piutang')
@section('page-title', 'Laporan Piutang')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Piutang</h2>
                <p class="text-sm text-gray-600">Laporan detail piutang pelanggan berdasarkan periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6 no-print">
            <form action="{{ route('laporan.piutang.index') }}" method="GET" id="filterForm">
                <!-- Header: Periode + Actions (compact) -->
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 md:gap-4 mb-4 border-b border-gray-100 pb-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">Periode Laporan</p>
                        <div class="mt-2 flex flex-wrap gap-3 text-xs md:text-sm text-gray-700">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_periode" value="semua"
                                    {{ request('jenis_periode') == 'semua' || request('jenis_periode') == '' || request('jenis_periode') == null ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                    onchange="togglePeriodeType()">
                                <span>Semua Waktu</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_periode" value="bulan"
                                    {{ request('jenis_periode') == 'bulan' ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                    onchange="togglePeriodeType()">
                                <span>Per Bulan</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_periode" value="tanggal"
                                    {{ request('jenis_periode') == 'tanggal' ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                    onchange="togglePeriodeType()">
                                <span>Per Tanggal</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full lg:w-auto">
                        <button type="submit"
                            class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 border border-transparent rounded-lg font-medium text-white text-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Tampilkan</span>
                            <span class="sm:hidden">Cari</span>
                        </button>

                        @if (isset($laporanData))
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
                <div class="hidden lg:grid grid-cols-4 gap-4 mb-4">
                    <!-- Pelanggan Filter -->
                    <div>
                        <label for="pelanggan_id" class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Pelanggan</option>
                            @foreach ($pelangganList ?? [] as $pelanggan)
                                <option value="{{ $pelanggan->id }}"
                                    {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum
                                Bayar</option>
                            <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>Down Payment</option>
                            <option value="angsuran" {{ request('status') == 'angsuran' ? 'selected' : '' }}>Angsuran
                            </option>
                        </select>
                    </div>

                    <!-- Bulan Filter (for bulan type) -->
                    <div id="bulanFilterDesktop"
                        class="{{ request('jenis_periode') == 'tanggal' || request('jenis_periode') == 'semua' ? 'hidden' : '' }}">
                        <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="bulan" id="bulan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            @foreach ($bulanList as $key => $value)
                                <option value="{{ $key }}"
                                    {{ request('bulan') == $key || (request('bulan') == null && $key == date('n')) ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun Filter (for bulan type) -->
                    <div id="tahunFilterDesktop"
                        class="{{ request('jenis_periode') == 'tanggal' || request('jenis_periode') == 'semua' ? 'hidden' : '' }}">
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}"
                                    {{ request('tahun') == $tahun || (request('tahun') == null && $tahun == date('Y')) ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Dari (for tanggal type) -->
                    <div id="tanggalDariFilterDesktop"
                        class="{{ request('jenis_periode') == 'bulan' || request('jenis_periode') == 'semua' ? 'hidden' : '' }}">
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                        <div class="relative">
                            <input type="text" id="tanggal_dari" name="tanggal_dari"
                                value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                                class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Pilih tanggal dari" readonly>
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Sampai (for tanggal type) -->
                    <div id="tanggalSampaiFilterDesktop"
                        class="{{ request('jenis_periode') == 'bulan' || request('jenis_periode') == 'semua' ? 'hidden' : '' }}">
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Sampai</label>
                        <div class="relative">
                            <input type="text" id="tanggal_sampai" name="tanggal_sampai"
                                value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                                class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Pilih tanggal sampai" readonly>
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile/Tablet Layout - Responsive grid -->
                <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3 mb-2 md:mb-3">
                    <!-- Pelanggan Filter -->
                    <div class="sm:col-span-2">
                        <label for="pelanggan_id_mobile"
                            class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Pelanggan</option>
                            @foreach ($pelangganList ?? [] as $pelanggan)
                                <option value="{{ $pelanggan->id }}"
                                    {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                    {{ $pelanggan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="sm:col-span-2">
                        <label for="status_mobile" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum
                                Bayar</option>
                            <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>Down Payment</option>
                            <option value="angsuran" {{ request('status') == 'angsuran' ? 'selected' : '' }}>Angsuran
                            </option>
                        </select>
                    </div>

                    <!-- Bulan/Tahun Filter (for bulan type) -->
                    <div id="bulanTahunFilterMobile"
                        class="sm:col-span-2 {{ request('jenis_periode') == 'tanggal' || request('jenis_periode') == 'semua' ? 'hidden' : '' }}"
                        style="display: {{ request('jenis_periode') == 'bulan' ? 'block' : 'none' }};">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bulan Filter -->
                            <div>
                                <label for="bulan_mobile"
                                    class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                <select name="bulan" id="bulan_mobile"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                    @foreach ($bulanList as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ request('bulan') == $key || (request('bulan') == null && $key == date('n')) ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tahun Filter -->
                            <div>
                                <label for="tahun_mobile"
                                    class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                <select name="tahun" id="tahun_mobile"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                    @foreach ($tahunList as $tahun)
                                        <option value="{{ $tahun }}"
                                            {{ request('tahun') == $tahun || (request('tahun') == null && $tahun == date('Y')) ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Filter (for tanggal type) -->
                    <div id="tanggalFilterMobile"
                        class="sm:col-span-2 {{ request('jenis_periode') == 'bulan' || request('jenis_periode') == 'semua' ? 'hidden' : '' }}"
                        style="display: {{ request('jenis_periode') == 'tanggal' ? 'block' : 'none' }};">
                        <!-- Tanggal Dari -->
                        <div class="mb-2">
                            <label for="tanggal_dari_mobile" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Dari</label>
                            <div class="relative">
                                <input type="text" id="tanggal_dari_mobile" name="tanggal_dari"
                                    value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                                    class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Pilih tanggal dari" readonly>
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <i class="ti ti-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Sampai -->
                        <div>
                            <label for="tanggal_sampai_mobile"
                                class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                            <div class="relative">
                                <input type="text" id="tanggal_sampai_mobile" name="tanggal_sampai"
                                    value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                                    class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Pilih tanggal sampai" readonly>
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


        <!-- Summary Cards -->
        @if (isset($laporanData))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 mt-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Piutang</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($laporanData['summary']['total_piutang'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($laporanData['summary']['total_transaksi'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Belum Bayar</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($laporanData['summary']['belum_bayar'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Down Payment</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($laporanData['summary']['dp'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Detail Piutang
                            </h3>
                            @if (isset($laporanData['periode']))
                                <p class="text-sm text-gray-600 mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Periode:
                                    @if ($laporanData['periode']['jenis'] == 'semua')
                                        {{ $laporanData['periode']['deskripsi'] }}
                                    @elseif ($laporanData['periode']['jenis'] == 'bulan')
                                        {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
                                    @else
                                        {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_dari'])->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_sampai'])->format('d M Y') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ isset($laporanData['piutangs']) ? $laporanData['piutangs']->count() : 0 }} Transaksi
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden p-4 space-y-3">
                    @forelse($laporanData['piutangs'] ?? [] as $piutang)
                        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 mb-1">{{ $piutang['no_faktur'] }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($piutang['tanggal'])->format('d M Y') }}</div>
                                </div>
                                <div>
                                    @if ($piutang['status'] == 'lunas')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Lunas
                                        </span>
                                    @elseif($piutang['status'] == 'dp')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            DP
                                        </span>
                                    @elseif($piutang['status'] == 'angsuran')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Angsuran
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Belum Bayar
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Pelanggan:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $piutang['pelanggan'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Total:</span>
                                    <span class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($piutang['total'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Terbayar:</span>
                                    <span class="text-sm font-medium text-green-600">Rp
                                        {{ number_format($piutang['terbayar'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span class="text-xs font-semibold text-gray-800">Sisa:</span>
                                    <span class="text-sm font-bold text-red-600">Rp
                                        {{ number_format($piutang['sisa'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ti ti-file-report text-xl text-gray-400"></i>
                            </div>
                            <p class="text-sm text-gray-500">Tidak ada data piutang pada periode ini</p>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        No. Faktur
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Tanggal
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Pelanggan
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center justify-end">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Total
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center justify-end">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Terbayar
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center justify-end">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        Sisa
                                    </div>
                                </th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($laporanData['piutangs'] ?? [] as $piutang)
                                <tr class="hover:bg-blue-50 transition-colors duration-200 group">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $piutang['no_faktur'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">
                                            {{ \Carbon\Carbon::parse($piutang['tanggal'])->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $piutang['pelanggan'] }}</div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($piutang['total'], 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-green-600">
                                            Rp {{ number_format($piutang['terbayar'], 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <div class="text-sm font-bold text-red-600">
                                            Rp {{ number_format($piutang['sisa'], 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        @if ($piutang['status'] == 'lunas')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Lunas
                                            </span>
                                        @elseif($piutang['status'] == 'dp')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                DP
                                            </span>
                                        @elseif($piutang['status'] == 'angsuran')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Angsuran
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data piutang</h3>
                                            <p class="text-sm text-gray-500">Tidak ada data piutang untuk periode yang
                                                dipilih</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (isset($laporanData['piutangs']) && $laporanData['piutangs']->count() > 0)
                            <tfoot class="bg-gradient-to-r from-gray-50 to-blue-50 border-t-2 border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-900">
                                        <div class="flex items-center justify-end">
                                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                </path>
                                            </svg>
                                            <span class="text-lg">TOTAL:</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            Rp {{ number_format($laporanData['piutangs']->sum('total'), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-lg font-bold text-green-600">
                                            Rp {{ number_format($laporanData['piutangs']->sum('terbayar'), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-lg font-bold text-red-600">
                                            Rp {{ number_format($laporanData['piutangs']->sum('sisa'), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-bold bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $laporanData['piutangs']->count() }} Transaksi
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Spacing between tables -->
            <div class="mt-8"></div>

            <!-- Tabel Rekap Pelanggan -->
            @if (isset($laporanData['rekap_pelanggan']) && $laporanData['rekap_pelanggan']->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Rekap Berdasarkan Pelanggan</h3>
                        </div>
                    </div>
                    <!-- Mobile Card View -->
                    <div class="block md:hidden p-4 space-y-3">
                        @foreach ($laporanData['rekap_pelanggan'] as $rekap)
                            <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center mr-3">
                                            <span class="text-white font-semibold text-sm">
                                                {{ substr($rekap['pelanggan'], 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $rekap['pelanggan'] }}
                                            </div>
                                            <div class="text-xs text-gray-600">{{ $rekap['total_transaksi'] }} Transaksi
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @if ($rekap['sisa_piutang'] <= 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Lunas
                                            </span>
                                        @elseif ($rekap['total_terbayar'] > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Angsuran
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">Total Piutang:</span>
                                        <span class="text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($rekap['total_piutang'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">Terbayar:</span>
                                        <span class="text-sm font-medium text-green-600">Rp
                                            {{ number_format($rekap['total_terbayar'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 pt-2">
                                        <span class="text-xs font-semibold text-gray-800">Sisa:</span>
                                        <span class="text-sm font-bold text-red-600">Rp
                                            {{ number_format($rekap['sisa_piutang'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            Pelanggan
                                        </div>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                </path>
                                            </svg>
                                            Transaksi
                                        </div>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                </path>
                                            </svg>
                                            Total Piutang
                                        </div>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Terbayar
                                        </div>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Sisa
                                        </div>
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Status
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($laporanData['rekap_pelanggan'] as $rekap)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                                        <span class="text-white font-semibold text-sm">
                                                            {{ substr($rekap['pelanggan'], 0, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $rekap['pelanggan'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $rekap['total_transaksi'] }} Transaksi
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($rekap['total_piutang'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="text-sm font-medium text-green-600">
                                                Rp {{ number_format($rekap['total_terbayar'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="text-sm font-bold text-red-600">
                                                Rp {{ number_format($rekap['sisa_piutang'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($rekap['sisa_piutang'] <= 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Lunas
                                                </span>
                                            @elseif ($rekap['total_terbayar'] > 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Angsuran
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gradient-to-r from-gray-50 to-blue-50 border-t-2 border-gray-200">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-900">
                                        <div class="flex items-center justify-end">
                                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                </path>
                                            </svg>
                                            TOTAL:
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            Rp
                                            {{ number_format($laporanData['rekap_pelanggan']->sum('total_piutang'), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-lg font-bold text-green-600">
                                            Rp
                                            {{ number_format($laporanData['rekap_pelanggan']->sum('total_terbayar'), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-lg font-bold text-red-600">
                                            Rp
                                            {{ number_format($laporanData['rekap_pelanggan']->sum('sisa_piutang'), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-bold bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $laporanData['rekap_pelanggan']->count() }} Pelanggan
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <style>
        @media print {
            /* Hide non-essential elements */
            .no-print,
            nav,
            aside,
            header:not(.print-header),
            footer,
            button,
            .btn,
            a:not(.print-link) {
                display: none !important;
            }

            /* Show only content */
            body {
                margin: 0;
                padding: 10px;
                font-size: 12px;
            }

            /* Optimize table for printing */
            table {
                width: 100%;
                border-collapse: collapse;
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            /* Ensure proper spacing */
            .bg-white {
                background: white !important;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            /* Print-friendly colors */
            * {
                color: #000 !important;
                background: transparent !important;
                box-shadow: none !important;
            }

            /* Keep borders for tables */
            table,
            th,
            td {
                border: 1px solid #000 !important;
            }
        }
    </style>

    <script>
        // Initialize flatpickr for date inputs
        function initializeFlatpickr() {
            // Initialize flatpickr for desktop tanggal_dari
            if (document.getElementById('tanggal_dari')) {
                flatpickr("#tanggal_dari", {
                    dateFormat: "d/m/Y",
                    locale: "id",
                    allowInput: false,
                    clickOpens: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        // Update tanggal_sampai min date
                        if (selectedDates.length > 0) {
                            const tanggalSampaiInput = document.getElementById('tanggal_sampai');
                            if (tanggalSampaiInput && tanggalSampaiInput._flatpickr) {
                                tanggalSampaiInput._flatpickr.set('minDate', dateStr);
                            }
                        }
                    }
                });
            }

            // Initialize flatpickr for desktop tanggal_sampai
            if (document.getElementById('tanggal_sampai')) {
                flatpickr("#tanggal_sampai", {
                    dateFormat: "d/m/Y",
                    locale: "id",
                    allowInput: false,
                    clickOpens: true,
                    minDate: document.getElementById('tanggal_dari')?.value || "today"
                });
            }

            // Initialize flatpickr for mobile tanggal_dari
            if (document.getElementById('tanggal_dari_mobile')) {
                flatpickr("#tanggal_dari_mobile", {
                    dateFormat: "d/m/Y",
                    locale: "id",
                    allowInput: false,
                    clickOpens: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        // Update tanggal_sampai min date
                        if (selectedDates.length > 0) {
                            const tanggalSampaiInput = document.getElementById('tanggal_sampai_mobile');
                            if (tanggalSampaiInput && tanggalSampaiInput._flatpickr) {
                                tanggalSampaiInput._flatpickr.set('minDate', dateStr);
                            }
                        }
                    }
                });
            }

            // Initialize flatpickr for mobile tanggal_sampai
            if (document.getElementById('tanggal_sampai_mobile')) {
                flatpickr("#tanggal_sampai_mobile", {
                    dateFormat: "d/m/Y",
                    locale: "id",
                    allowInput: false,
                    clickOpens: true,
                    minDate: document.getElementById('tanggal_dari_mobile')?.value || "today"
                });
            }
        }

        // Initialize flatpickr when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeFlatpickr();
        });

        // Helper function to convert date from d/m/Y to Y-m-d
        function convertDateFormat(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }

        // Re-initialize flatpickr when periode type changes
        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;

            // Desktop filters
            const bulanFilterDesktop = document.getElementById('bulanFilterDesktop');
            const tahunFilterDesktop = document.getElementById('tahunFilterDesktop');
            const tanggalDariFilterDesktop = document.getElementById('tanggalDariFilterDesktop');
            const tanggalSampaiFilterDesktop = document.getElementById('tanggalSampaiFilterDesktop');

            // Mobile filters
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

                // Clear tanggal values
                if (tanggalDariInput) {
                    tanggalDariInput.value = '';
                    if (tanggalDariInput._flatpickr) {
                        tanggalDariInput._flatpickr.clear();
                    }
                }
                if (tanggalDariMobileInput) {
                    tanggalDariMobileInput.value = '';
                    if (tanggalDariMobileInput._flatpickr) {
                        tanggalDariMobileInput._flatpickr.clear();
                    }
                }
                if (tanggalSampaiInput) {
                    tanggalSampaiInput.value = '';
                    if (tanggalSampaiInput._flatpickr) {
                        tanggalSampaiInput._flatpickr.clear();
                    }
                }
                if (tanggalSampaiMobileInput) {
                    tanggalSampaiMobileInput.value = '';
                    if (tanggalSampaiMobileInput._flatpickr) {
                        tanggalSampaiMobileInput._flatpickr.clear();
                    }
                }

                // Set default values for bulan and tahun if they are empty
                const currentMonth = new Date().getMonth() + 1;
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
            } else if (jenisPeriode === 'tanggal') {
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
            } else if (jenisPeriode === 'semua') {
                // Hide all filters
                if (bulanFilterDesktop) bulanFilterDesktop.classList.add('hidden');
                if (tahunFilterDesktop) tahunFilterDesktop.classList.add('hidden');
                if (tanggalDariFilterDesktop) tanggalDariFilterDesktop.classList.add('hidden');
                if (tanggalSampaiFilterDesktop) tanggalSampaiFilterDesktop.classList.add('hidden');

                if (bulanTahunFilterMobile) bulanTahunFilterMobile.style.display = 'none';
                if (tanggalFilterMobile) tanggalFilterMobile.style.display = 'none';

                // Don't clear values, just hide them
                // This way when user switches back, the values are preserved
            }

            // Re-initialize Select2 for pelanggan
            setTimeout(() => {
                $('#pelanggan_id, #pelanggan_id_mobile').select2({
                    placeholder: 'Semua Pelanggan',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true,
                    language: {
                        noResults: function() {
                            return "Tidak ada pelanggan ditemukan";
                        },
                        searching: function() {
                            return "Mencari pelanggan...";
                        }
                    }
                }).on('select2:select', function (e) {
                    $(this).blur();
                });
            }, 100);
        }

        // Setup form synchronization between desktop and mobile
        function setupFormSynchronization() {
            // Sync pelanggan
            const pelangganDesktop = document.getElementById('pelanggan_id');
            const pelangganMobile = document.getElementById('pelanggan_id_mobile');
            if (pelangganDesktop && pelangganMobile) {
                pelangganDesktop.addEventListener('change', function() {
                    pelangganMobile.value = this.value;
                    pelangganMobile.dispatchEvent(new Event('change'));
                });
                pelangganMobile.addEventListener('change', function() {
                    pelangganDesktop.value = this.value;
                    pelangganDesktop.dispatchEvent(new Event('change'));
                });
            }

            // Sync status
            const statusDesktop = document.getElementById('status');
            const statusMobile = document.getElementById('status_mobile');
            if (statusDesktop && statusMobile) {
                statusDesktop.addEventListener('change', function() {
                    statusMobile.value = this.value;
                });
                statusMobile.addEventListener('change', function() {
                    statusDesktop.value = this.value;
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

            // Sync tanggal_dari
            const tanggalDariDesktop = document.getElementById('tanggal_dari');
            const tanggalDariMobile = document.getElementById('tanggal_dari_mobile');
            if (tanggalDariDesktop && tanggalDariMobile) {
                tanggalDariDesktop.addEventListener('change', function() {
                    tanggalDariMobile.value = this.value;
                });
                tanggalDariMobile.addEventListener('change', function() {
                    tanggalDariDesktop.value = this.value;
                });
            }

            // Sync tanggal_sampai
            const tanggalSampaiDesktop = document.getElementById('tanggal_sampai');
            const tanggalSampaiMobile = document.getElementById('tanggal_sampai_mobile');
            if (tanggalSampaiDesktop && tanggalSampaiMobile) {
                tanggalSampaiDesktop.addEventListener('change', function() {
                    tanggalSampaiMobile.value = this.value;
                });
                tanggalSampaiMobile.addEventListener('change', function() {
                    tanggalSampaiDesktop.value = this.value;
                });
            }
        }

        // Handle form submission - convert date format before submit
        function setupFormSubmission() {
            const form = document.getElementById('filterForm');
            form.addEventListener('submit', function(e) {
                const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
                
                if (jenisPeriode === 'tanggal') {
                    const tanggalDari = document.getElementById('tanggal_dari').value || document.getElementById('tanggal_dari_mobile').value;
                    const tanggalSampai = document.getElementById('tanggal_sampai').value || document.getElementById('tanggal_sampai_mobile').value;
                    
                    // Create hidden inputs with converted dates
                    if (tanggalDari) {
                        const hiddenDari = document.createElement('input');
                        hiddenDari.type = 'hidden';
                        hiddenDari.name = 'tanggal_dari';
                        hiddenDari.value = convertDateFormat(tanggalDari);
                        form.appendChild(hiddenDari);
                        
                        // Remove original inputs temporarily
                        const originalDari = document.getElementById('tanggal_dari');
                        const originalDariMobile = document.getElementById('tanggal_dari_mobile');
                        if (originalDari) originalDari.disabled = true;
                        if (originalDariMobile) originalDariMobile.disabled = true;
                    }
                    
                    if (tanggalSampai) {
                        const hiddenSampai = document.createElement('input');
                        hiddenSampai.type = 'hidden';
                        hiddenSampai.name = 'tanggal_sampai';
                        hiddenSampai.value = convertDateFormat(tanggalSampai);
                        form.appendChild(hiddenSampai);
                        
                        // Remove original inputs temporarily
                        const originalSampai = document.getElementById('tanggal_sampai');
                        const originalSampaiMobile = document.getElementById('tanggal_sampai_mobile');
                        if (originalSampai) originalSampai.disabled = true;
                        if (originalSampaiMobile) originalSampaiMobile.disabled = true;
                    }
                }
            });
        }

        // Print functionality
        function setupPrintButton() {
            const printBtn = document.getElementById('printBtn');
            if (!printBtn) return;

            printBtn.addEventListener('click', function() {
                const form = document.getElementById('filterForm');
                const formData = new FormData(form);
                const params = new URLSearchParams();

                // Get jenis_periode
                const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
                params.append('jenis_periode', jenisPeriode);

                // Get pelanggan_id
                const pelangganId = document.getElementById('pelanggan_id').value || document.getElementById('pelanggan_id_mobile').value;
                if (pelangganId) {
                    params.append('pelanggan_id', pelangganId);
                }

                // Get status
                const status = document.getElementById('status').value || document.getElementById('status_mobile').value;
                if (status) {
                    params.append('status', status);
                }

                // Handle dates based on jenis_periode
                if (jenisPeriode === 'bulan') {
                    const bulan = document.getElementById('bulan').value || document.getElementById('bulan_mobile').value;
                    const tahun = document.getElementById('tahun').value || document.getElementById('tahun_mobile').value;
                    if (bulan) params.append('bulan', bulan);
                    if (tahun) params.append('tahun', tahun);
                } else if (jenisPeriode === 'tanggal') {
                    const tanggalDari = document.getElementById('tanggal_dari').value || document.getElementById('tanggal_dari_mobile').value;
                    const tanggalSampai = document.getElementById('tanggal_sampai').value || document.getElementById('tanggal_sampai_mobile').value;
                    if (tanggalDari) {
                        params.append('tanggal_dari', convertDateFormat(tanggalDari));
                    }
                    if (tanggalSampai) {
                        params.append('tanggal_sampai', convertDateFormat(tanggalSampai));
                    }
                }

                // Open print page in new tab
                const url = '{{ route('laporan.piutang.print') }}?' + params.toString();
                window.open(url, '_blank');
            });
        }

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for pelanggan selects
            $('#pelanggan_id, #pelanggan_id_mobile').select2({
                placeholder: 'Semua Pelanggan',
                allowClear: true,
                width: '100%',
                closeOnSelect: true,
                language: {
                    noResults: function() {
                        return "Tidak ada pelanggan ditemukan";
                    },
                    searching: function() {
                        return "Mencari pelanggan...";
                    }
                }
            }).on('select2:select', function (e) {
                $(this).blur();
            });

            // Initialize flatpickr
            initializeFlatpickr();

            // Setup form synchronization
            setupFormSynchronization();

            // Setup form submission
            setupFormSubmission();

            // Setup print button
            setupPrintButton();

            // Initialize periode type
            togglePeriodeType();

            // Enable export button when form is submitted
            document.getElementById('filterForm').addEventListener('submit', function() {
                setTimeout(() => {
                    toggleExportButton();
                }, 1000);
            });
        });
    </script>
@endsection
