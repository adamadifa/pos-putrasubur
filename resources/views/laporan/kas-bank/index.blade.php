@extends('layouts.pos')

@section('title', 'Laporan Kas & Bank')
@section('page-title', 'Laporan Kas & Bank')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Kas & Bank</h2>
                <p class="text-sm text-gray-600">Laporan saldo awal dan transaksi kas/bank per periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
            <form method="GET" action="{{ route('laporan.kas-bank.index') }}" id="laporanForm">
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
                            <span class="ml-2 text-sm text-gray-700">Periode Tanggal</span>
                        </label>
                    </div>
                </div>

                <!-- Desktop Layout - All elements aligned with equal width -->
                <div class="hidden lg:grid grid-cols-6 gap-4 mb-4">
                    <!-- Kas/Bank Filter -->
                    <div>
                        <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-1">Kas/Bank
                            <span class="text-red-500">*</span></label>
                        <select name="kas_bank_id" id="kas_bank_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="">Pilih Kas/Bank</option>
                            @foreach ($kasBankList as $kasBank)
                                <option value="{{ $kasBank->id }}"
                                    {{ $selectedKasBank == $kasBank->id ? 'selected' : '' }}>
                                    {{ $kasBank->nama }} ({{ $kasBank->jenis }})
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
                            <input type="text" name="tanggal_dari" id="tanggal_dari" value="{{ $tanggalDari }}"
                                class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Pilih tanggal dari" readonly>
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Sampai (for tanggal type) -->
                    <div id="tanggalSampaiFilterDesktop" class="{{ $jenisPeriode == 'bulan' ? 'hidden' : '' }}">
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Sampai</label>
                        <div class="relative">
                            <input type="text" name="tanggal_sampai" id="tanggal_sampai" value="{{ $tanggalSampai }}"
                                class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Pilih tanggal sampai" readonly>
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <i class="ti ti-search text-lg mr-2"></i>
                            Tampilkan
                        </button>
                    </div>

                    @if ($laporanData)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                            <button type="button" onclick="exportToPdf()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="ti ti-file-download text-lg mr-2"></i>
                                Export PDF
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Mobile/Tablet Layout - Responsive grid -->
                <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3 mb-2 md:mb-3">
                    <!-- Kas/Bank Filter -->
                    <div class="sm:col-span-2">
                        <label for="kas_bank_id_mobile" class="block text-sm font-medium text-gray-700 mb-1">Kas/Bank
                            <span class="text-red-500">*</span></label>
                        <select name="kas_bank_id" id="kas_bank_id_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="">Pilih Kas/Bank</option>
                            @foreach ($kasBankList as $kasBank)
                                <option value="{{ $kasBank->id }}"
                                    {{ $selectedKasBank == $kasBank->id ? 'selected' : '' }}>
                                    {{ $kasBank->nama }} ({{ $kasBank->jenis }})
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
                                <label for="bulan_mobile"
                                    class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                <select name="bulan" id="bulan_mobile"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                    @foreach ($bulanList as $key => $bulan)
                                        <option value="{{ $key }}"
                                            {{ $selectedBulan == $key ? 'selected' : '' }}>
                                            {{ $bulan }}
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
                                            {{ $selectedTahun == $tahun ? 'selected' : '' }}>
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
                            <label for="tanggal_dari_mobile" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Dari</label>
                            <div class="relative">
                                <input type="text" name="tanggal_dari" id="tanggal_dari_mobile"
                                    value="{{ $tanggalDari }}"
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
                                class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Sampai</label>
                            <div class="relative">
                                <input type="text" name="tanggal_sampai" id="tanggal_sampai_mobile"
                                    value="{{ $tanggalSampai }}"
                                    class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Pilih tanggal sampai" readonly>
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <i class="ti ti-calendar text-gray-400 text-sm"></i>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Mobile Action Buttons -->
                <div class="lg:hidden grid grid-cols-2 gap-2">
                        <button type="submit"
                        class="mobile-button inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <i class="ti ti-search text-lg mr-2"></i>
                        <span class="hidden sm:inline">Tampilkan</span>
                        <span class="sm:hidden">Cari</span>
                        </button>
                        @if ($laporanData)
                            <button type="button" onclick="exportToPdf()"
                            class="mobile-button inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="ti ti-file-download text-lg mr-2"></i>
                            <span class="hidden sm:inline">Export PDF</span>
                            <span class="sm:hidden">PDF</span>
                            </button>
                        @endif
                </div>
            </form>
        </div>

        <!-- Laporan Data -->
        @if ($laporanData)
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Saldo Awal -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-currency-dollar text-blue-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Saldo Awal</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Debet -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-trending-up text-green-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Debet</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['total_debet'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Kredit -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-trending-down text-red-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Kredit</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['total_kredit'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-wallet text-purple-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Saldo Akhir</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['saldo_akhir'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Header -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
                    <div class="mb-3 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Kas & Bank</h3>
                        <p class="text-sm text-gray-600">
                            {{ $laporanData['kas_bank']->nama }}
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
                                @if (isset($laporanData['statistics']['jumlah_hari']))
                                    ({{ $laporanData['statistics']['jumlah_hari'] }} hari)
                                @endif
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
                                    Rp
                                    {{ number_format($laporanData['saldo_awal_terakhir']['saldo'], 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="ti ti-calendar mr-1"></i>
                                    Dihitung dari {{ $laporanData['saldo_awal_terakhir']['tanggal_mulai_hitung'] }} s/d
                                    {{ $laporanData['periode']['tanggal_dari'] }}
                                </p>
                            @elseif (isset($laporanData['saldo_awal_bulan']) && $laporanData['saldo_awal_bulan'] > 0)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal bulan {{ $laporanData['periode']['bulan_nama'] }}: Rp
                                    {{ number_format($laporanData['saldo_awal_bulan'], 0, ',', '.') }}
                                </p>
                            @endif
                        @else
                            @if (isset($laporanData['saldo_awal_terakhir']) && $laporanData['saldo_awal_terakhir'])
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal terakhir ({{ $laporanData['saldo_awal_terakhir']['periode_saldo_awal'] }}):
                                    Rp
                                    {{ number_format($laporanData['saldo_awal_terakhir']['saldo'], 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="ti ti-calendar mr-1"></i>
                                    Dihitung dari {{ $laporanData['saldo_awal_terakhir']['tanggal_mulai_hitung'] }} s/d
                                    {{ $laporanData['periode']['tanggal_awal'] }}
                                </p>
                            @elseif (isset($laporanData['saldo_awal_bulan']) && $laporanData['saldo_awal_bulan'] > 0)
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="ti ti-info-circle mr-1"></i>
                                    Saldo awal bulan {{ $laporanData['periode']['bulan_nama'] }}: Rp
                                    {{ number_format($laporanData['saldo_awal_bulan'], 0, ',', '.') }}
                                </p>
                            @endif
                        @endif
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm text-gray-500">Dicetak pada:</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4 mb-4 md:mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <p class="text-xs md:text-sm text-gray-500">Jumlah Transaksi</p>
                        <p class="text-lg md:text-xl font-bold text-gray-900">
                            {{ $laporanData['statistics']['jumlah_transaksi'] }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <p class="text-xs md:text-sm text-gray-500">Transaksi Debet</p>
                        <p class="text-lg md:text-xl font-bold text-gray-900">
                            {{ $laporanData['statistics']['transaksi_debet'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <p class="text-xs md:text-sm text-gray-500">Transaksi Kredit</p>
                        <p class="text-lg md:text-xl font-bold text-gray-900">
                            {{ $laporanData['statistics']['transaksi_kredit'] }}
                        </p>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden space-y-3">
                    <!-- Saldo Awal Card -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <div class="text-xs font-medium text-blue-800 mb-1">
                                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                                        Saldo Awal Periode
                                    @else
                                        Saldo Awal
                                    @endif
                                </div>
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
                                <span class="text-xs text-gray-600">Debet:</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Kredit:</span>
                                <span class="text-sm text-gray-500">-</span>
                            </div>
                            <div class="flex justify-between border-t border-blue-200 pt-2">
                                <span class="text-xs font-semibold text-blue-800">Saldo:</span>
                                <span class="text-sm font-bold text-blue-900">
                                    Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Transaksi Cards -->
                    @forelse($laporanData['transaksi'] as $transaksi)
                        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 mb-1">
                                        {{ $transaksi->tanggal->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $transaksi->keterangan_detail ?? $transaksi->keterangan ?: '-' }}
                                    </div>
                                </div>
                                <div>
                                    @if ($transaksi->jenis_transaksi == 'D')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Debet
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Kredit
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Debet:</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        @if ($transaksi->jenis_transaksi == 'D')
                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Kredit:</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        @if ($transaksi->jenis_transaksi == 'K')
                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span class="text-xs font-semibold text-gray-800">Saldo:</span>
                                    <span class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($transaksi->saldo_akhir, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ti ti-file-report text-xl text-gray-400"></i>
                            </div>
                            <p class="text-sm text-gray-500">Tidak ada transaksi pada periode ini</p>
                        </div>
                    @endforelse

                    <!-- Saldo Akhir Card -->
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <div class="text-xs font-medium text-purple-800 mb-1">
                                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                                        Saldo Akhir Periode
                                    @else
                                        Saldo Akhir
                                    @endif
                                </div>
                                <div class="text-xs text-purple-600">
                                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                                        {{ $laporanData['periode']['tanggal_sampai'] }}
                                    @else
                                        {{ $laporanData['periode']['tanggal_akhir'] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Total Debet:</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($laporanData['summary']['total_debet'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-600">Total Kredit:</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($laporanData['summary']['total_kredit'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between border-t border-purple-200 pt-2">
                                <span class="text-xs font-semibold text-purple-800">Saldo Akhir:</span>
                                <span class="text-sm font-bold text-purple-900">
                                    Rp {{ number_format($laporanData['summary']['saldo_akhir'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
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
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Debet
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kredit
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Saldo Awal Row -->
                            <tr class="bg-blue-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                                        {{ $laporanData['periode']['tanggal_dari'] }}
                                    @else
                                        {{ $laporanData['periode']['tanggal_awal'] }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        @if ($laporanData['periode']['jenis'] == 'tanggal')
                                            Saldo Awal Periode
                                        @else
                                            Saldo Awal
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-500">-</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                    -
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}
                                </td>
                            </tr>

                            <!-- Transaksi Rows -->
                            @forelse($laporanData['transaksi'] as $transaksi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaksi->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $transaksi->keterangan_detail ?? $transaksi->keterangan ?: '-' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if ($transaksi->jenis_transaksi == 'D')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Debet
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Kredit
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                        @if ($transaksi->jenis_transaksi == 'D')
                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                        @if ($transaksi->jenis_transaksi == 'K')
                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                        Rp {{ number_format($transaksi->saldo_akhir, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Tidak ada transaksi pada periode ini
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Saldo Akhir Row -->
                            <tr class="bg-purple-50 border-t-2 border-purple-200">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    @if ($laporanData['periode']['jenis'] == 'tanggal')
                                        {{ $laporanData['periode']['tanggal_sampai'] }}
                                    @else
                                        {{ $laporanData['periode']['tanggal_akhir'] }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm font-bold text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        @if ($laporanData['periode']['jenis'] == 'tanggal')
                                            Saldo Akhir Periode
                                        @else
                                            Saldo Akhir
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-500">-</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($laporanData['summary']['total_debet'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($laporanData['summary']['total_kredit'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-purple-600">
                                    Rp {{ number_format($laporanData['summary']['saldo_akhir'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="ti ti-file-report text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Parameter Laporan</h3>
                    <p class="text-gray-500 text-sm mb-6">
                        Pilih kas/bank, bulan, dan tahun untuk menampilkan laporan
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
    </style>

    <script>
        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePeriodeType();
            initializeFlatpickr();
            setupFormSynchronization();
        });

        function initializeFlatpickr() {
            // Initialize flatpickr for desktop tanggal_dari
            flatpickr("#tanggal_dari", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: false,
                clickOpens: true
            });

            // Initialize flatpickr for desktop tanggal_sampai
            flatpickr("#tanggal_sampai", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: false,
                clickOpens: true
            });

            // Initialize flatpickr for mobile tanggal_dari
            flatpickr("#tanggal_dari_mobile", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: false,
                clickOpens: true
            });

            // Initialize flatpickr for mobile tanggal_sampai
            flatpickr("#tanggal_sampai_mobile", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: false,
                clickOpens: true
            });
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
            const bulanSelectMobile = document.getElementById('bulan_mobile');
            const tahunSelect = document.getElementById('tahun');
            const tahunSelectMobile = document.getElementById('tahun_mobile');
            const tanggalDariInput = document.getElementById('tanggal_dari');
            const tanggalDariInputMobile = document.getElementById('tanggal_dari_mobile');
            const tanggalSampaiInput = document.getElementById('tanggal_sampai');
            const tanggalSampaiInputMobile = document.getElementById('tanggal_sampai_mobile');

            if (jenisPeriode === 'bulan') {
                // Show bulan/tahun filter, hide tanggal filter
                if (bulanFilterDesktop) {
                    bulanFilterDesktop.style.display = 'block';
                    tahunFilterDesktop.style.display = 'block';
                    tanggalDariFilterDesktop.style.display = 'none';
                    tanggalSampaiFilterDesktop.style.display = 'none';
                }

                if (bulanTahunFilterMobile) {
                    bulanTahunFilterMobile.style.display = 'block';
                    tanggalFilterMobile.style.display = 'none';
                }

                // Clear tanggal values
                if (tanggalDariInput) tanggalDariInput.value = '';
                if (tanggalDariInputMobile) tanggalDariInputMobile.value = '';
                if (tanggalSampaiInput) tanggalSampaiInput.value = '';
                if (tanggalSampaiInputMobile) tanggalSampaiInputMobile.value = '';
            } else {
                // Hide bulan/tahun filter, show tanggal filter
                if (bulanFilterDesktop) {
                    bulanFilterDesktop.style.display = 'none';
                    tahunFilterDesktop.style.display = 'none';
                    tanggalDariFilterDesktop.style.display = 'block';
                    tanggalSampaiFilterDesktop.style.display = 'block';
                }

                if (bulanTahunFilterMobile) {
                    bulanTahunFilterMobile.style.display = 'none';
                    tanggalFilterMobile.style.display = 'block';
                }

                // Clear bulan/tahun values
                if (bulanSelect) bulanSelect.value = '';
                if (bulanSelectMobile) bulanSelectMobile.value = '';
                if (tahunSelect) tahunSelect.value = '';
                if (tahunSelectMobile) tahunSelectMobile.value = '';

                // Re-initialize flatpickr for date inputs
                setTimeout(() => {
                    initializeFlatpickr();
                }, 100);
            }
        }

        function setupFormSynchronization() {
            // Sync kas_bank_id
            const kasBankDesktop = document.getElementById('kas_bank_id');
            const kasBankMobile = document.getElementById('kas_bank_id_mobile');
            if (kasBankDesktop && kasBankMobile) {
                kasBankDesktop.addEventListener('change', function() {
                    kasBankMobile.value = this.value;
                });
                kasBankMobile.addEventListener('change', function() {
                    kasBankDesktop.value = this.value;
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

        function exportToPdf() {
            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i>Mengekspor...';
            button.disabled = true;

            // Get form data
            const formData = new FormData();
            formData.append('kas_bank_id', document.getElementById('kas_bank_id').value);
            formData.append('jenis_periode', document.querySelector('input[name="jenis_periode"]:checked').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Add periode-specific data
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
            if (jenisPeriode === 'bulan') {
                formData.append('bulan', document.getElementById('bulan').value);
                formData.append('tahun', document.getElementById('tahun').value);
            } else {
                formData.append('tanggal_dari', document.getElementById('tanggal_dari').value);
                formData.append('tanggal_sampai', document.getElementById('tanggal_sampai').value);
            }

            // Make request
            fetch('{{ route('laporan.kas-bank.export-pdf') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Check if response is PDF
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/pdf')) {
                            // Handle PDF response
                            return response.blob().then(blob => {
                                // Create object URL and open in new tab
                                const url = window.URL.createObjectURL(blob);
                                window.open(url, '_blank');
                                showNotification('PDF berhasil dibuka di tab baru!', 'success');
                            });
                        } else {
                            // Handle JSON response (error case)
                            return response.json().then(data => {
                                if (data.success) {
                                    showNotification('PDF berhasil diekspor!', 'success');
                                } else {
                                    showNotification('Gagal mengekspor PDF: ' + data.message, 'error');
                                }
                            });
                        }
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .catch(error => {
                    console.error('Export PDF error:', error);
                    showNotification('Terjadi kesalahan saat mengekspor PDF', 'error');
                })
                .finally(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
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
