@extends('layouts.pos')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Laporan Penjualan</h2>
                <p class="text-xs text-gray-500">Analisis penjualan, produk terlaris, ve pelanggan top</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-filter text-gray-400"></i>
                <h3 class="text-sm font-bold text-gray-800">Filter Laporan</h3>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('laporan.penjualan.index') }}" id="laporanForm">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <!-- Periode Type -->
                        <div class="md:col-span-3 space-y-1">
                            <label class="block text-xs font-bold text-gray-700">Jenis Periode</label>
                            <div class="flex rounded-lg bg-gray-50/50 p-1 border border-gray-200">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis_periode" value="bulan"
                                        {{ $jenisPeriode == 'bulan' ? 'checked' : '' }}
                                        class="peer sr-only" onchange="togglePeriodeType()">
                                    <div class="text-center py-1.5 px-3 text-xs font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all">
                                        Bulanan
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis_periode" value="tanggal"
                                        {{ $jenisPeriode == 'tanggal' ? 'checked' : '' }}
                                        class="peer sr-only" onchange="togglePeriodeType()">
                                    <div class="text-center py-1.5 px-3 text-xs font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all">
                                        Harian
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Filters for Bulanan -->
                        <div id="bulanTahunFilter" class="md:col-span-6 grid grid-cols-2 gap-4" style="display: {{ $jenisPeriode == 'bulan' ? 'grid' : 'none' }};">
                            <div class="space-y-1">
                                <label for="bulan" class="block text-xs font-bold text-gray-700">Bulan</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar-month text-sm"></i></span>
                                    <select id="bulan" name="bulan"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer appearance-none">
                                        @foreach ($bulanList as $key => $bulan)
                                            <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>
                                                {{ $bulan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="tahun" class="block text-xs font-bold text-gray-700">Tahun</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                    <select id="tahun" name="tahun"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer appearance-none">
                                        @for ($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ $selectedTahun == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Filters for Harian -->
                        <div id="tanggalRangeFilter" class="md:col-span-6 grid grid-cols-2 gap-4" style="display: {{ $jenisPeriode == 'tanggal' ? 'grid' : 'none' }};">
                            <div class="space-y-1">
                                <label for="tanggal_dari" class="block text-xs font-bold text-gray-700">Dari</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                    <input type="text" id="tanggal_dari" name="tanggal_dari" value="{{ $tanggalDari }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                                        placeholder="Pilih tanggal">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="tanggal_sampai" class="block text-xs font-bold text-gray-700">Sampai</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                    <input type="text" id="tanggal_sampai" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                                        placeholder="Pilih tanggal">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all h-[38px] flex items-center justify-center gap-2">
                                <i class="ti ti-search text-base"></i>
                                <span>Tampilkan</span>
                            </button>
                            <button type="button" id="printBtn"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 h-[38px] flex items-center justify-center gap-2">
                                <i class="ti ti-printer text-base"></i>
                                <span class="hidden sm:inline">Cetak</span>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if (isset($laporanData))
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Total Penjualan -->
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                            <i class="ti ti-shopping-cart text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Transaksi</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">{{ number_format($laporanData['summary']['total_penjualan'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 border border-green-100">
                            <i class="ti ti-currency-dollar text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Pendapatan</p>
                            <p class="text-xl font-bold text-gray-900 mt-0.5">Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats: Status & Jenis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Status Pembayaran -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                        <i class="ti ti-wallet"></i> Status Pembayaran
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-2 rounded-lg bg-green-50/50 border border-green-100">
                            <span class="text-sm font-medium text-green-700">Lunas</span>
                            <span class="text-sm font-bold text-gray-900">{{ $laporanData['status_counts']['lunas'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center p-2 rounded-lg bg-red-50/50 border border-red-100">
                            <span class="text-sm font-medium text-red-700">Belum Lunas</span>
                            <span class="text-sm font-bold text-gray-900">{{ ($laporanData['status_counts']['dp'] ?? 0) + ($laporanData['status_counts']['angsuran'] ?? 0) + ($laporanData['status_counts']['belum_bayar'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Jenis Transaksi -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                        <i class="ti ti-credit-card"></i> Jenis Transaksi
                    </h4>
                    <div class="space-y-3">
                        @foreach ($laporanData['jenis_transaksi_counts'] as $jenis => $count)
                            <div class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-colors">
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $jenis }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
                    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <i class="ti ti-trophy text-amber-500"></i>
                            Rekap Produk
                        </h3>
                        <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-amber-100">Terlaris</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="px-3 py-2 w-12 text-center">#</th>
                                    <th class="px-3 py-2">Produk</th>
                                    <th class="px-3 py-2 text-right">Qty</th>
                                    <th class="px-3 py-2 text-right">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($laporanData['top_products'] as $index => $product)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-3 py-2 text-center">
                                            @if($index < 3)
                                                <i class="ti ti-medal text-{{ $index === 0 ? 'yellow-500' : ($index === 1 ? 'gray-400' : 'amber-700') }}"></i>
                                            @else
                                                <span class="text-gray-400 text-xs">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="font-medium text-gray-900 truncate max-w-[150px]" title="{{ $product->nama_produk }}">
                                                {{ strtoupper($product->nama_produk) }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            <div class="font-medium text-gray-900">{{ number_format($product->total_qty, 0, ',', '.') }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $product->nama_satuan ?? 'unit' }}</div>
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-gray-900">
                                            {{ number_format($product->total_nilai, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-5 py-4 text-center text-gray-500 text-xs">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="2" class="px-3 py-2 text-right font-bold text-gray-900">Total</td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900">
                                        {{ number_format($laporanData['top_products']->sum('total_qty'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900">
                                        Rp {{ number_format($laporanData['top_products']->sum('total_nilai'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
                    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <i class="ti ti-users text-blue-500"></i>
                            Rekap Pelanggan
                        </h3>
                        <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-100">Setia</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                                <tr>
                                    <th class="px-3 py-2 w-12 text-center">#</th>
                                    <th class="px-3 py-2">Pelanggan</th>
                                    <th class="px-3 py-2 text-right">Trx</th>
                                    <th class="px-3 py-2 text-right">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($laporanData['top_customers'] as $index => $customer)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-3 py-2 text-center">
                                            <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold mx-auto">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="font-medium text-gray-900 truncate max-w-[150px]" title="{{ $customer->nama }}">
                                                {{ strtoupper($customer->nama) }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-gray-600">
                                            {{ $customer->total_transaksi }}
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-gray-900">
                                            {{ number_format($customer->total_nilai, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-5 py-4 text-center text-gray-500 text-xs">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="2" class="px-3 py-2 text-right font-bold text-gray-900">Total</td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900">
                                        {{ number_format($laporanData['top_customers']->sum('total_transaksi'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900">
                                        Rp {{ number_format($laporanData['top_customers']->sum('total_nilai'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detail Transaksi Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="ti ti-list text-gray-500"></i> Detail Transaksi
                    </h3>
                    <span class="text-xs text-gray-500 font-medium">{{ count($laporanData['penjualan']) }} Transaksi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-3 py-2">No Faktur</th>
                                <th class="px-3 py-2">Tanggal</th>
                                <th class="px-3 py-2">Pelanggan</th>
                                <th class="px-3 py-2 text-center">Status</th>
                                <th class="px-3 py-2 text-center">Jenis</th>
                                <th class="px-3 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($laporanData['penjualan'] as $transaksi)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-3 py-2">
                                        <button onclick="showDetailPenjualan({{ $transaksi->id }})" 
                                            class="flex items-center gap-2 group text-left">
                                            <div class="w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 group-hover:text-blue-700 transition">
                                                <i class="ti ti-receipt-2"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-blue-600 group-hover:underline text-xs">{{ $transaksi->no_faktur }}</div>
                                                <div class="text-[10px] text-gray-500 hidden sm:block">Lihat Detail</div>
                                            </div>
                                        </button>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="text-xs font-medium text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-500 font-bold">
                                                {{ strtoupper(substr($transaksi->pelanggan->nama ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-gray-700 font-medium">{{ strtoupper($transaksi->pelanggan->nama ?? 'UMUM') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        @if($transaksi->status_pembayaran == 'lunas')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                                                LUNAS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                                BELUM
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                         @if($transaksi->jenis_transaksi == 'tunai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                TUNAI
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                KREDIT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        <div class="font-bold text-gray-900 text-sm">Rp {{ number_format($transaksi->total_setelah_diskon, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="ti ti-file-off text-3xl text-gray-300"></i>
                                            <p class="text-sm">Tidak ada transaksi pada periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-100">
                            <tr>
                                <td colspan="5" class="px-3 py-2 text-right font-bold text-gray-900">Total</td>
                                <td class="px-3 py-2 text-right font-bold text-gray-900">
                                    Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
            const bulanTahunFilter = document.getElementById('bulanTahunFilter');
            const tanggalFilter = document.getElementById('tanggalRangeFilter');
            
            if (jenisPeriode === 'bulan') {
                bulanTahunFilter.style.display = 'grid';
                tanggalFilter.style.display = 'none';
            } else {
                bulanTahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'grid';
            }
        }

        // Initialize Flatpickr for date inputs
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tanggal_dari", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: true
            });

            flatpickr("#tanggal_sampai", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: true
            });

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
            Swal.fire({
                title: 'Memuat Detail...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch(`/penjualan/${penjualanId}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showDetailModal(data.penjualan);
                    } else {
                        Swal.fire('Error', data.message || 'Gagal memuat detail', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                });
        }

        function showDetailModal(penjualan) {
            // Helper to safe access default values
            const kasir = (penjualan.kasir ? penjualan.kasir.name : 'Admin').toUpperCase();
            const pelanggan = (penjualan.pelanggan ? penjualan.pelanggan.nama : 'Umum').toUpperCase();
            const statusClass = penjualan.status_pembayaran === 'lunas' ? 'text-green-600' : 'text-red-600';
            const statusText = penjualan.status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS';
            
            const detailRows = penjualan.detail_penjualan.map(detail => `
                <div class="border-b border-dashed border-gray-200 py-3 last:border-0">
                     <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-gray-800 text-sm">${(detail.produk ? detail.produk.nama_produk : 'PRODUK DIHAPUS').toUpperCase()}</span>
                        <span class="font-bold text-gray-900 text-sm">Rp ${formatNumber(detail.subtotal)}</span>
                     </div>
                     <div class="flex justify-between text-xs text-gray-500">
                        <span>${formatNumber(detail.qty, 2)} x ${formatNumber(detail.harga)}</span>
                        <span>Diskon: Rp ${formatNumber(detail.diskon || 0)}</span>
                     </div>
                </div>
            `).join('');

            const htmlContent = `
                <div class="text-left font-mono">
                    <!-- Header -->
                    <div class="text-center mb-6 pb-4 border-b-2 border-dashed border-gray-300">
                        <h2 class="text-xl font-bold text-gray-900 mb-1">TOKO PUTRA SUBUR</h2>
                        <p class="text-xs text-gray-500">Jl. Contoh No. 123, Kota</p>
                        <p class="text-xs text-gray-500">Telp: (021) 123-4567</p>
                    </div>

                    <!-- Info -->
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs mb-6 text-gray-600">
                        <div>No. Faktur</div>
                        <div class="text-right font-bold text-gray-900">${penjualan.no_faktur}</div>
                        
                        <div>Tanggal</div>
                        <div class="text-right text-gray-900">${penjualan.tanggal} ${penjualan.jam}</div>
                        
                        <div>Pelanggan</div>
                        <div class="text-right text-gray-900 font-medium">${pelanggan}</div>
                        
                        <div>Kasir</div>
                        <div class="text-right text-gray-900">${kasir}</div>

                        <div>Status</div>
                        <div class="text-right font-bold ${statusClass}">${statusText}</div>
                    </div>

                    <!-- Items -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        ${detailRows}
                    </div>

                    <!-- Totals -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp ${formatNumber(penjualan.total)}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Diskon</span>
                            <span>Rp ${formatNumber(penjualan.diskon)}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-gray-900 pt-3 border-t-2 border-dashed border-gray-300">
                            <span>TOTAL</span>
                            <span>Rp ${formatNumber(penjualan.total_setelah_diskon)}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-4 border-t-2 border-dashed border-gray-300 text-center text-[10px] text-gray-400">
                        <p>Simpan bukti pembayaran ini sebagai bukti transaksi yang sah.</p>
                        <p class="mt-1">${new Date().toLocaleString('id-ID')}</p>
                    </div>
                </div>
            `;

            Swal.fire({
                html: htmlContent,
                width: '400px',
                showConfirmButton: true,
                confirmButtonText: '<i class="ti ti-printer"></i> Cetak Struk',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                customClass: {
                    container: 'font-sans',
                    popup: 'rounded-xl shadow-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Logic print sruk (optional implementation)
                    window.open(`/penjualan/${penjualan.id}/print-struk`, '_blank');
                }
            });
        }

        function formatNumber(num, decimals = 0) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(num);
        }
    </script>
    @endpush
@endsection
