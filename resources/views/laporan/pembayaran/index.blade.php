@extends('layouts.pos')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@section('content')
    <style>
        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .mobile-button {
                @apply px-3 py-2 text-sm;
            }

            .mobile-card {
                @apply rounded-lg p-4 mb-3 border border-gray-200;
            }

            .mobile-table-card {
                @apply rounded-lg p-3 mb-2 bg-gray-50 border border-gray-200;
            }

            .mobile-summary-card {
                @apply rounded-lg p-4 mb-3 bg-white border border-gray-200;
            }
        }
    </style>

    <div class="space-y-4 md:space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Pembayaran</h2>
                <p class="text-sm text-gray-600">Laporan pembayaran penjualan dan pembelian per periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
            <form method="GET" action="{{ route('laporan.pembayaran.index') }}" id="laporanForm">
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
                <div class="hidden lg:grid grid-cols-8 gap-4 mb-4">
                    <!-- Jenis Transaksi Filter -->
                    <div>
                        <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi
                            <span class="text-red-500">*</span></label>
                        <select name="jenis_transaksi" id="jenis_transaksi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="">Pilih Jenis Transaksi</option>
                            <option value="penjualan" {{ $selectedJenisTransaksi == 'penjualan' ? 'selected' : '' }}>
                                Penjualan</option>
                            <option value="pembelian" {{ $selectedJenisTransaksi == 'pembelian' ? 'selected' : '' }}>
                                Pembelian</option>
                            <option value="semua" {{ $selectedJenisTransaksi == 'semua' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>

                    <!-- Kas/Bank Filter -->
                    <div>
                        <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-1">Kas/Bank</label>
                        <select name="kas_bank_id" id="kas_bank_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Kas/Bank</option>
                            @foreach ($kasBankList as $kasBank)
                                <option value="{{ $kasBank->id }}"
                                    {{ $selectedKasBank == $kasBank->id ? 'selected' : '' }}>
                                    {{ $kasBank->nama }} ({{ $kasBank->jenis }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Metode Pembayaran Filter -->
                    <div>
                        <label for="metode_pembayaran_id" class="block text-sm font-medium text-gray-700 mb-1">Metode
                            Pembayaran</label>
                        <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Metode Pembayaran</option>
                            @foreach ($metodePembayaranList as $metode)
                                <option value="{{ $metode->id }}"
                                    {{ $selectedMetodePembayaran == $metode->id ? 'selected' : '' }}>
                                    {{ $metode->nama }}
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
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Tampilkan
                        </button>
                    </div>

                    @if ($laporanData)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                            <button type="button" id="exportPdfBtn"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Export PDF
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Mobile/Tablet Layout - Responsive grid -->
                <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3 mb-2 md:mb-3">
                    <!-- Jenis Transaksi Filter -->
                    <div class="sm:col-span-2">
                        <label for="jenis_transaksi_mobile" class="block text-sm font-medium text-gray-700 mb-1">Jenis
                            Transaksi
                            <span class="text-red-500">*</span></label>
                        <select name="jenis_transaksi" id="jenis_transaksi_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="">Pilih Jenis Transaksi</option>
                            <option value="penjualan" {{ $selectedJenisTransaksi == 'penjualan' ? 'selected' : '' }}>
                                Penjualan</option>
                            <option value="pembelian" {{ $selectedJenisTransaksi == 'pembelian' ? 'selected' : '' }}>
                                Pembelian</option>
                            <option value="semua" {{ $selectedJenisTransaksi == 'semua' ? 'selected' : '' }}>Semua
                            </option>
                        </select>
                    </div>

                    <!-- Kas/Bank Filter -->
                    <div>
                        <label for="kas_bank_id_mobile"
                            class="block text-sm font-medium text-gray-700 mb-1">Kas/Bank</label>
                        <select name="kas_bank_id" id="kas_bank_id_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Kas/Bank</option>
                            @foreach ($kasBankList as $kasBank)
                                <option value="{{ $kasBank->id }}"
                                    {{ $selectedKasBank == $kasBank->id ? 'selected' : '' }}>
                                    {{ $kasBank->nama }} ({{ $kasBank->jenis }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Metode Pembayaran Filter -->
                    <div>
                        <label for="metode_pembayaran_id_mobile"
                            class="block text-sm font-medium text-gray-700 mb-1">Metode
                            Pembayaran</label>
                        <select name="metode_pembayaran_id" id="metode_pembayaran_id_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Metode Pembayaran</option>
                            @foreach ($metodePembayaranList as $metode)
                                <option value="{{ $metode->id }}"
                                    {{ $selectedMetodePembayaran == $metode->id ? 'selected' : '' }}>
                                    {{ $metode->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan/Tahun Filter (for bulan type) -->
                    <div id="bulanTahunFilterMobile"
                        class="sm:col-span-2 {{ $jenisPeriode == 'tanggal' ? 'hidden' : '' }}"
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
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Tampilkan</span>
                        <span class="sm:hidden">Cari</span>
                        </button>
                        @if ($laporanData)
                            <button type="button" id="exportPdfBtn"
                            class="mobile-button inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <!-- Total Pembayaran -->
                <div
                    class="mobile-summary-card bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-credit-card text-blue-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Pembayaran</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_pembayaran'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div
                    class="mobile-summary-card bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-currency-dollar text-purple-600 text-lg md:text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4">
                            <p class="text-xs md:text-sm font-medium text-gray-500">Total Nilai</p>
                            <p class="text-lg md:text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Header -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Pembayaran</h3>
                        <p class="text-sm text-gray-600">
                            @if ($laporanData['periode']['jenis'] == 'tanggal')
                                {{ $laporanData['periode']['tanggal_dari'] }} s/d
                                {{ $laporanData['periode']['tanggal_sampai'] }}
                            @else
                                {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
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
                    </div>
                    <div class="text-left md:text-right mt-2 md:mt-0">
                        <p class="text-sm text-gray-500">Dicetak pada:</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden">
                    @forelse($laporanData['pembayaran'] as $pembayaran)
                        <div class="mobile-table-card">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-gray-900">{{ $pembayaran->no_faktur }}</div>
                                    <div class="text-xs text-gray-500">{{ $pembayaran->tanggal->format('d/m/Y') }}</div>
                                </div>
                                <div class="text-right">
                                    @if ($pembayaran->jenis == 'Penjualan')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Penjualan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Pembelian
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Pelanggan/Supplier:</span>
                                    <span
                                        class="text-xs font-medium text-gray-900">{{ $pembayaran->nama_pelanggan_supplier }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Metode:</span>
                                    <span
                                        class="text-xs font-medium text-gray-900">{{ $pembayaran->metode_pembayaran }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">Kas Bank:</span>
                                    <span class="text-xs font-medium text-gray-900">{{ $pembayaran->kas_bank }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span class="text-sm font-semibold text-gray-900">Jumlah:</span>
                                    <span class="text-sm font-bold text-gray-900">Rp
                                        {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-sm text-gray-500">Tidak ada data pembayaran untuk periode yang dipilih.</div>
                        </div>
                    @endforelse
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
                                    Jenis
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Faktur
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan/Supplier
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Metode Pembayaran
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kas Bank
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                            <!-- Pembayaran Rows -->
                            @forelse($laporanData['pembayaran'] as $pembayaran)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pembayaran->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        @if ($pembayaran->jenis == 'Penjualan')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Penjualan
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Pembelian
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pembayaran->no_faktur }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        {{ $pembayaran->nama_pelanggan_supplier }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pembayaran->metode_pembayaran }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pembayaran->kas_bank }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Tidak ada data pembayaran untuk periode yang dipilih.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Summary by Metode Pembayaran -->
                @if (isset($laporanData['metode_pembayaran_counts']) && count($laporanData['metode_pembayaran_counts']) > 0)
                    <div class="mt-6 md:mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Rekap Metode Pembayaran</h4>

                        <!-- Mobile Card View -->
                        <div class="block md:hidden space-y-2">
                            @foreach ($laporanData['metode_pembayaran_counts'] as $metode)
                                <div class="mobile-table-card">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="text-sm font-semibold text-gray-900">{{ $metode['nama'] }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Transaksi</div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ number_format($metode['count'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-xs text-gray-500">Total</div>
                                            <div class="text-sm font-bold text-gray-900">Rp
                                                {{ number_format($metode['nilai'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden md:block bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Metode Pembayaran</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah Transaksi</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($laporanData['metode_pembayaran_counts'] as $metode)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $metode['nama'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                                {{ number_format($metode['count'], 0, ',', '.') }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                                Rp {{ number_format($metode['nilai'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Summary by Kas Bank -->
                @if (isset($laporanData['kas_bank_counts']) && count($laporanData['kas_bank_counts']) > 0)
                    <div class="mt-6 md:mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Rekap Kas Bank</h4>

                        <!-- Mobile Card View -->
                        <div class="block md:hidden space-y-2">
                            @foreach ($laporanData['kas_bank_counts'] as $kasBank)
                                <div class="mobile-table-card">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="text-sm font-semibold text-gray-900">{{ $kasBank['nama'] }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Transaksi</div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ number_format($kasBank['count'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-xs text-gray-500">Total</div>
                                            <div class="text-sm font-bold text-gray-900">Rp
                                                {{ number_format($kasBank['nilai'], 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden md:block bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kas Bank</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah Transaksi</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($laporanData['kas_bank_counts'] as $kasBank)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $kasBank['nama'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                                {{ number_format($kasBank['count'], 0, ',', '.') }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                                Rp {{ number_format($kasBank['nilai'], 0, ',', '.') }}</td>
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
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg border border-gray-100 p-8 md:p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="ti ti-file-report text-xl md:text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-medium text-gray-900 mb-2">Pilih Parameter Laporan</h3>
                    <p class="text-gray-500 text-sm mb-6">
                        Pilih kas/bank, bulan, dan tahun untuk menampilkan laporan
                    </p>
                </div>
            </div>
        @endif
    </div>



    <script>
        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePeriodeType();
            initializeFlatpickr();
            setupFormSynchronization();

            // Handle form submission
            const form = document.getElementById('laporanForm');
            form.addEventListener('submit', function(e) {
                // Only submit if jenis_transaksi is selected
                const jenisTransaksi = document.getElementById('jenis_transaksi').value;
                if (!jenisTransaksi) {
                    e.preventDefault();
                    alert('Pilih jenis transaksi terlebih dahulu!');
                    return false;
                }

                // Validate date range if periode is tanggal
                const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
                if (jenisPeriode === 'tanggal') {
                    const tanggalDari = document.getElementById('tanggal_dari').value;
                    const tanggalSampai = document.getElementById('tanggal_sampai').value;

                    if (!tanggalDari || !tanggalSampai) {
                        e.preventDefault();
                        alert('Pilih tanggal dari dan tanggal sampai!');
                        return false;
                    }
                }
            });
        });

        // Synchronize form elements between desktop and mobile
        function setupFormSynchronization() {
            // Sync jenis_transaksi
            const jenisTransaksiDesktop = document.getElementById('jenis_transaksi');
            const jenisTransaksiMobile = document.getElementById('jenis_transaksi_mobile');
            if (jenisTransaksiDesktop && jenisTransaksiMobile) {
                jenisTransaksiDesktop.addEventListener('change', function() {
                    jenisTransaksiMobile.value = this.value;
                });
                jenisTransaksiMobile.addEventListener('change', function() {
                    jenisTransaksiDesktop.value = this.value;
                });
            }

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

            // Sync metode_pembayaran_id
            const metodePembayaranDesktop = document.getElementById('metode_pembayaran_id');
            const metodePembayaranMobile = document.getElementById('metode_pembayaran_id_mobile');
            if (metodePembayaranDesktop && metodePembayaranMobile) {
                metodePembayaranDesktop.addEventListener('change', function() {
                    metodePembayaranMobile.value = this.value;
                });
                metodePembayaranMobile.addEventListener('change', function() {
                    metodePembayaranDesktop.value = this.value;
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

        function initializeFlatpickr() {
            // Initialize flatpickr for tanggal_dari
            flatpickr("#tanggal_dari", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Update tanggal_sampai min date
                    if (selectedDates.length > 0) {
                        const tanggalSampaiInput = document.getElementById('tanggal_sampai');
                        if (tanggalSampaiInput._flatpickr) {
                            tanggalSampaiInput._flatpickr.set('minDate', dateStr);
                        }
                    }
                }
            });

            // Initialize flatpickr for tanggal_sampai
            flatpickr("#tanggal_sampai", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                minDate: document.getElementById('tanggal_dari').value || "today"
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

            // Get form elements for clearing values
            const bulanSelect = document.getElementById('bulan');
            const tahunSelect = document.getElementById('tahun');
            const tanggalDariInput = document.getElementById('tanggal_dari');
            const tanggalSampaiInput = document.getElementById('tanggal_sampai');

            if (jenisPeriode === 'bulan') {
                // Show bulan/tahun filters, hide tanggal filters
                if (bulanFilterDesktop) bulanFilterDesktop.classList.remove('hidden');
                if (tahunFilterDesktop) tahunFilterDesktop.classList.remove('hidden');
                if (tanggalDariFilterDesktop) tanggalDariFilterDesktop.classList.add('hidden');
                if (tanggalSampaiFilterDesktop) tanggalSampaiFilterDesktop.classList.add('hidden');

                if (bulanTahunFilterMobile) bulanTahunFilterMobile.style.display = 'block';
                if (tanggalFilterMobile) tanggalFilterMobile.style.display = 'none';

                // Clear tanggal values
                if (tanggalDariInput) tanggalDariInput.value = '';
                if (tanggalSampaiInput) tanggalSampaiInput.value = '';
            } else {
                // Hide bulan/tahun filters, show tanggal filters
                if (bulanFilterDesktop) bulanFilterDesktop.classList.add('hidden');
                if (tahunFilterDesktop) tahunFilterDesktop.classList.add('hidden');
                if (tanggalDariFilterDesktop) tanggalDariFilterDesktop.classList.remove('hidden');
                if (tanggalSampaiFilterDesktop) tanggalSampaiFilterDesktop.classList.remove('hidden');

                if (bulanTahunFilterMobile) bulanTahunFilterMobile.style.display = 'none';
                if (tanggalFilterMobile) tanggalFilterMobile.style.display = 'block';

                // Clear bulan/tahun values
                if (bulanSelect) bulanSelect.value = '';
                if (tahunSelect) tahunSelect.value = '';

                // Re-initialize flatpickr for date inputs
                setTimeout(() => {
                    initializeFlatpickr();
                }, 100);
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
            formData.append('jenis_transaksi', document.getElementById('jenis_transaksi').value);
            formData.append('kas_bank_id', document.getElementById('kas_bank_id').value);
            formData.append('metode_pembayaran_id', document.getElementById('metode_pembayaran_id').value);
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
            fetch('{{ route('laporan.pembayaran.export-pdf') }}', {
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

        // Add event listener for export PDF button
        document.addEventListener('DOMContentLoaded', function() {
            const exportPdfBtn = document.getElementById('exportPdfBtn');
            if (exportPdfBtn) {
                exportPdfBtn.addEventListener('click', exportToPdf);
            }
        });
    </script>
@endsection
