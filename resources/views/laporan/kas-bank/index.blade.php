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
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form method="GET" action="{{ route('laporan.kas-bank.index') }}" id="laporanForm">
                <!-- Periode Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Periode</label>
                    <div class="flex space-x-4">
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

                <div class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
                    <!-- Kas/Bank Filter -->
                    <div class="flex-1">
                        <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Kas/Bank</label>
                        <select name="kas_bank_id" id="kas_bank_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
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
                    <div id="bulanTahunFilter" class="lg:flex lg:space-x-4 {{ $jenisPeriode == 'tanggal' ? 'hidden' : '' }}"
                        style="display: {{ $jenisPeriode == 'tanggal' ? 'none' : 'flex' }};">
                        <!-- Bulan Filter -->
                        <div class="lg:w-48">
                            <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" id="bulan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                @foreach ($bulanList as $key => $bulan)
                                    <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tahun Filter -->
                        <div class="lg:w-32">
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" id="tahun"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal Filter (for tanggal type) -->
                    <div id="tanggalFilter" class="lg:flex lg:space-x-4 {{ $jenisPeriode == 'bulan' ? 'hidden' : '' }}"
                        style="display: {{ $jenisPeriode == 'bulan' ? 'none' : 'flex' }};">
                        <!-- Tanggal Dari -->
                        <div class="lg:w-48">
                            <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Dari</label>
                            <div class="relative">
                                <input type="text" name="tanggal_dari" id="tanggal_dari" value="{{ $tanggalDari }}"
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Pilih tanggal dari" readonly>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-calendar text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Sampai -->
                        <div class="lg:w-48">
                            <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Sampai</label>
                            <div class="relative">
                                <input type="text" name="tanggal_sampai" id="tanggal_sampai"
                                    value="{{ $tanggalSampai }}"
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Pilih tanggal sampai" readonly>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-calendar text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 lg:flex-none">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <i class="ti ti-search text-lg mr-2"></i>
                            Tampilkan Laporan
                        </button>
                        @if ($laporanData)
                            <button type="button" onclick="exportToPdf()"
                                class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="ti ti-file-download text-lg mr-2"></i>
                                Export PDF
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Laporan Data -->
        @if ($laporanData)
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Saldo Awal -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-currency-dollar text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Saldo Awal</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['saldo_awal'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Debet -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-trending-up text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Debet</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['total_debet'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Kredit -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-trending-down text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Kredit</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['total_kredit'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-wallet text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Saldo Akhir</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['saldo_akhir'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
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
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Dicetak pada:</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Jumlah Transaksi</p>
                        <p class="text-xl font-bold text-gray-900">{{ $laporanData['statistics']['jumlah_transaksi'] }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Transaksi Debet</p>
                        <p class="text-xl font-bold text-gray-900">{{ $laporanData['statistics']['transaksi_debet'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Transaksi Kredit</p>
                        <p class="text-xl font-bold text-gray-900">{{ $laporanData['statistics']['transaksi_kredit'] }}
                        </p>
                    </div>
                </div>

                <!-- Transaksi Table -->
                <div class="overflow-x-auto">
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
                                        {{ $transaksi->keterangan ?: '-' }}
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



    <script>
        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePeriodeType();
            initializeFlatpickr();
        });

        function initializeFlatpickr() {
            // Initialize flatpickr for tanggal_dari
            flatpickr("#tanggal_dari", {
                dateFormat: "Y-m-d",
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
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                minDate: document.getElementById('tanggal_dari').value || "today"
            });
        }

        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
            const bulanTahunFilter = document.getElementById('bulanTahunFilter');
            const tanggalFilter = document.getElementById('tanggalFilter');

            // Get form elements
            const bulanSelect = document.getElementById('bulan');
            const tahunSelect = document.getElementById('tahun');
            const tanggalDariInput = document.getElementById('tanggal_dari');
            const tanggalSampaiInput = document.getElementById('tanggal_sampai');

            if (jenisPeriode === 'bulan') {
                // Show bulan/tahun filter, hide tanggal filter
                bulanTahunFilter.style.display = 'flex';
                tanggalFilter.style.display = 'none';
                bulanTahunFilter.classList.remove('hidden');
                tanggalFilter.classList.add('hidden');

                // Clear tanggal values
                tanggalDariInput.value = '';
                tanggalSampaiInput.value = '';
            } else {
                // Hide bulan/tahun filter, show tanggal filter
                bulanTahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'flex';
                bulanTahunFilter.classList.add('hidden');
                tanggalFilter.classList.remove('hidden');

                // Clear bulan/tahun values
                bulanSelect.value = '';
                tahunSelect.value = '';

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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('PDF berhasil diekspor!', 'success');
                    } else {
                        showNotification('Gagal mengekspor PDF: ' + data.message, 'error');
                    }
                })
                .catch(error => {
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
