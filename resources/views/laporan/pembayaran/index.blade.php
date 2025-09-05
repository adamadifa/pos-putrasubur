@extends('layouts.pos')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Pembayaran</h2>
                <p class="text-sm text-gray-600">Laporan pembayaran penjualan dan pembelian per periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form method="POST" action="{{ route('laporan.pembayaran.generate') }}" id="laporanForm">
                @csrf
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
                    <!-- Jenis Transaksi Filter -->
                    <div class="flex-1">
                        <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi
                            <span class="text-red-500">*</span></label>
                        <select name="jenis_transaksi" id="jenis_transaksi"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
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
                    <div class="flex-1">
                        <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Kas/Bank</label>
                        <select name="kas_bank_id" id="kas_bank_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
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
                    <div class="flex-1">
                        <label for="metode_pembayaran_id" class="block text-sm font-medium text-gray-700 mb-2">Metode
                            Pembayaran</label>
                        <select name="metode_pembayaran_id" id="metode_pembayaran_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
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
                    <div id="bulanTahunFilter"
                        class="lg:flex lg:space-x-4 {{ $jenisPeriode == 'tanggal' ? 'hidden' : '' }}"
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
                            <button type="button" id="exportPdfBtn"
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Pembayaran -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-credit-card text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ number_format($laporanData['summary']['total_pembayaran'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="ti ti-currency-dollar text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Nilai</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
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
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Dicetak pada:</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
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
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Rekap Metode Pembayaran</h4>
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
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
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Rekap Kas Bank</h4>
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
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
