@extends('layouts.pos')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan Piutang</h1>
            <p class="text-gray-600">Laporan detail piutang pelanggan berdasarkan periode</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form action="{{ route('laporan.piutang.generate') }}" method="POST" id="filterForm">
                @csrf

                <!-- Periode Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Periode</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="jenis_periode" value="semua"
                                {{ request('jenis_periode') == 'semua' || request('jenis_periode') == null ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Semua Waktu</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="jenis_periode" value="bulan"
                                {{ request('jenis_periode') == 'bulan' ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Per Bulan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="jenis_periode" value="tanggal"
                                {{ request('jenis_periode') == 'tanggal' ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Per Tanggal</span>
                        </label>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <!-- Pelanggan Filter -->
                    <div class="space-y-2">
                        <label for="pelanggan_id" class="block text-sm font-medium text-gray-700">Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Pelanggan</option>
                            @foreach ($pelanggans ?? [] as $pelanggan)
                                <option value="{{ $pelanggan->id }}"
                                    {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum
                                Bayar</option>
                            <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>Down Payment</option>
                            <option value="angsuran" {{ request('status') == 'angsuran' ? 'selected' : '' }}>Angsuran
                            </option>
                        </select>
                    </div>

                    <!-- Bulan/Tahun Filter -->
                    <div id="bulanTahunFilter" class="space-y-2"
                        style="display: {{ request('jenis_periode') == 'bulan' ? 'block' : 'none' }};">
                        <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                        <select name="bulan" id="bulan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @foreach (['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $key => $value)
                                <option value="{{ $key }}"
                                    {{ request('bulan') == $key || (request('bulan') == null && $key == date('m')) ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="tahunFilter" class="space-y-2"
                        style="display: {{ request('jenis_periode') == 'bulan' ? 'block' : 'none' }};">
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                        <select name="tahun" id="tahun"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}"
                                    {{ request('tahun') == $year || (request('tahun') == null && $year == date('Y')) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div id="tanggalFilter" class="space-y-2"
                        style="display: {{ request('jenis_periode') == 'tanggal' ? 'block' : 'none' }};">
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                        <input type="text" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Pilih tanggal">
                    </div>

                    <div id="tanggalSampaiFilter" class="space-y-2"
                        style="display: {{ request('jenis_periode') == 'tanggal' ? 'block' : 'none' }};">
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                        <input type="text" id="tanggal_sampai" name="tanggal_sampai"
                            value="{{ request('tanggal_sampai') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Pilih tanggal">
                    </div>
                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 opacity-0">Action</label>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Generate
                        </button>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 opacity-0">Export</label>
                        <button type="button" id="exportPdfBtn"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            disabled>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </div>
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
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Piutang</h3>
                    @if (isset($laporanData['periode']))
                        <p class="text-sm text-gray-600 mt-1">
                            Periode:
                            @if ($laporanData['periode']['jenis'] == 'semua')
                                {{ $laporanData['periode']['deskripsi'] }}
                            @elseif ($laporanData['periode']['jenis'] == 'bulan')
                                {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
                            @else
                                {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_dari'])->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_sampai'])->format('d M Y') }}
                            @endif
                        </p>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Faktur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Terbayar</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sisa</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($laporanData['piutangs'] ?? [] as $piutang)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $piutang['no_faktur'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($piutang['tanggal'])->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $piutang['pelanggan'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp
                                        {{ number_format($piutang['total'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp
                                        {{ number_format($piutang['terbayar'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp
                                        {{ number_format($piutang['sisa'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($piutang['status'] == 'lunas')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                        @elseif($piutang['status'] == 'dp')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">DP</span>
                                        @elseif($piutang['status'] == 'angsuran')
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Angsuran</span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum
                                                Bayar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data
                                        piutang untuk periode yang dipilih</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (isset($laporanData['piutangs']) && $laporanData['piutangs']->count() > 0)
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-900">
                                        <strong>TOTAL:</strong>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                        <strong>Rp
                                            {{ number_format($laporanData['piutangs']->sum('total'), 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                        <strong>Rp
                                            {{ number_format($laporanData['piutangs']->sum('terbayar'), 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                        <strong>Rp
                                            {{ number_format($laporanData['piutangs']->sum('sisa'), 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $laporanData['piutangs']->count() }} Transaksi
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
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
            } else if (jenisPeriode === 'tanggal') {
                bulanTahunFilter.style.display = 'none';
                tahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'block';
                tanggalSampaiFilter.style.display = 'block';
            } else if (jenisPeriode === 'semua') {
                bulanTahunFilter.style.display = 'none';
                tahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'none';
                tanggalSampaiFilter.style.display = 'none';
            }
        }

        // Export PDF functionality
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            if (!document.getElementById('filterForm').checkValidity()) {
                document.getElementById('filterForm').reportValidity();
                return;
            }

            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            // Show loading
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML =
                '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Exporting...';
            btn.disabled = true;

            fetch('{{ route('laporan.piutang.export-pdf') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    }
                    throw new Error('Export failed');
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'laporan_piutang.pdf';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan dalam export PDF');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        });

        // Enable export button when form is submitted
        document.getElementById('filterForm').addEventListener('submit', function() {
            document.getElementById('exportPdfBtn').disabled = false;
        });

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
        });

        // Initialize on page load
        togglePeriodeType();
    </script>
@endsection
