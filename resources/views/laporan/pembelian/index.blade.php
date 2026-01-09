@extends('layouts.pos')

@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')

@section('content')
    <div class="space-y-4 md:space-y-6">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 relative overflow-hidden">
            <!-- Decorative Circle -->
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 opacity-50 blur-xl pointer-events-none"></div>

            <form method="GET" action="{{ route('laporan.pembelian.index') }}" id="laporanForm">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <!-- Period Toggle -->
                    <div class="md:col-span-4 lg:col-span-3 space-y-3">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide flex items-center gap-2">
                            <i class="ti ti-calendar-time"></i> Periode Laporan
                        </label>
                        <div class="flex bg-gray-50 p-1.5 rounded-xl border border-gray-100">
                            <label class="relative flex-1 cursor-pointer group">
                                <input type="radio" name="jenis_periode" value="bulan" class="peer sr-only"
                                    {{ $jenisPeriode == 'bulan' ? 'checked' : '' }} onchange="togglePeriodeType()">
                                <div class="px-3 py-2 text-xs font-bold text-center rounded-lg transition-all duration-200 
                                    text-gray-500 group-hover:text-gray-700
                                    peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-gray-200">
                                    <i class="ti ti-calendar-month text-sm mb-0.5 block sm:inline sm:mb-0 sm:mr-1"></i>
                                    BULANAN
                                </div>
                            </label>
                            <label class="relative flex-1 cursor-pointer group">
                                <input type="radio" name="jenis_periode" value="tanggal" class="peer sr-only"
                                    {{ $jenisPeriode == 'tanggal' ? 'checked' : '' }} onchange="togglePeriodeType()">
                                <div class="px-3 py-2 text-xs font-bold text-center rounded-lg transition-all duration-200 
                                    text-gray-500 group-hover:text-gray-700
                                    peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-gray-200">
                                    <i class="ti ti-calendar text-sm mb-0.5 block sm:inline sm:mb-0 sm:mr-1"></i>
                                    HARIAN
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Filter Inputs -->
                    <div class="md:col-span-5 lg:col-span-6">
                        <!-- Bulan/Tahun Inputs -->
                        <div id="bulanTahunFilter" class="grid grid-cols-2 gap-4" style="display: {{ $jenisPeriode == 'bulan' ? 'grid' : 'none' }};">
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Pilih Bulan</label>
                                <div class="relative">
                                    <select name="bulan" class="w-full pl-3 pr-8 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all appearance-none cursor-pointer">
                                        @foreach ($bulanList as $key => $bulan)
                                            <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>{{ $bulan }}</option>
                                        @endforeach
                                    </select>
                                    <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Pilih Tahun</label>
                                <div class="relative">
                                    <select name="tahun" class="w-full pl-3 pr-8 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all appearance-none cursor-pointer">
                                        @for ($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ $selectedTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Inputs -->
                        <div id="tanggalFilter" class="grid grid-cols-2 gap-4" style="display: {{ $jenisPeriode == 'tanggal' ? 'grid' : 'none' }};">
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Dari Tanggal</label>
                                <div class="relative group">
                                    <input type="text" id="tanggal_dari" name="tanggal_dari" value="{{ $tanggalDari }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer group-hover:bg-white"
                                        placeholder="DD/MM/YYYY">
                                    <i class="ti ti-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-medium text-gray-700">Sampai Tanggal</label>
                                <div class="relative group">
                                    <input type="text" id="tanggal_sampai" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all cursor-pointer group-hover:bg-white"
                                        placeholder="DD/MM/YYYY">
                                    <i class="ti ti-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                </div>
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

        @if (isset($laporanData))
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Total Pembelian -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 relative z-10">
                        <i class="ti ti-shopping-cart text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($laporanData['summary']['total_pembelian']) }}</h3>
                    </div>
                </div>

                <!-- Total Nilai -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-600 relative z-10">
                        <i class="ti ti-cash text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Pengeluaran</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Status Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:col-span-2 lg:col-span-1">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Status Pembayaran</h4>
                    <div class="space-y-2">
                        @foreach ($laporanData['status'] as $status => $count)
                            <div class="flex items-center justify-between text-sm p-2 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $status == 'lunas' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    <span class="capitalize text-gray-700 font-medium">{{ $status }}</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top 5 Suppliers -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full lg:col-span-1">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                            <i class="ti ti-trophy text-yellow-500"></i>
                            Rekap Supplier
                        </h3>
                    </div>
                    <div class="flex-1 overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-3 py-2 font-medium text-center w-12">#</th>
                                    <th class="px-3 py-2 font-medium">Supplier</th>
                                    <th class="px-3 py-2 font-medium text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($laporanData['top_suppliers'] as $index => $supplier)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-3 py-2 text-center">
                                            @if($index < 3)
                                                <i class="ti ti-medal text-{{ $index === 0 ? 'yellow-500' : ($index === 1 ? 'gray-400' : 'amber-700') }}"></i>
                                            @else
                                                <span class="text-gray-400 text-xs">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="font-medium text-gray-900 truncate max-w-[120px]" title="{{ $supplier['supplier']->nama }}">
                                                {{ strtoupper($supplier['supplier']->nama) }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 mt-0.5">{{ $supplier['total_transaksi'] }} Transaksi</div>
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-gray-900">
                                            Rp {{ number_format($supplier['total_nilai'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500 italic text-xs">
                                            Belum ada data supplier
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="2" class="px-3 py-2 text-right font-bold text-gray-900">Total</td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900">
                                        Rp {{ number_format($laporanData['top_suppliers']->sum('total_nilai'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Rekap Produk -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                            <i class="ti ti-package text-blue-500"></i>
                            Rekap Produk
                        </h3>
                    </div>
                    <div class="flex-1 overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-3 py-2 font-medium text-center w-12">#</th>
                                    <th class="px-3 py-2 font-medium">Produk</th>
                                    <th class="px-3 py-2 font-medium text-center">Qty</th>
                                    <th class="px-3 py-2 font-medium text-right">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($laporanData['top_produks'] as $index => $produk)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-3 py-2 text-center">
                                            @if($index < 3)
                                                <i class="ti ti-medal text-{{ $index === 0 ? 'yellow-500' : ($index === 1 ? 'gray-400' : 'amber-700') }}"></i>
                                            @else
                                                <span class="text-gray-400 text-xs">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="font-medium text-gray-900 truncate max-w-[150px]" title="{{ $produk['produk']->nama_produk }}">
                                                {{ strtoupper($produk['produk']->nama_produk) }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <div class="font-medium text-gray-900">{{ number_format($produk['total_qty'], 0, ',', '.') }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $produk['produk']->satuan->nama ?? 'Unit' }}</div>
                                        </td>
                                        <td class="px-3 py-2 text-right font-medium text-gray-900">
                                            Rp {{ number_format($produk['total_nilai'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic text-xs">
                                            Belum ada data produk
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="2" class="px-3 py-2 text-right font-bold text-gray-900">Total</td>
                                     <td class="px-3 py-2 text-center font-bold text-gray-900">
                                        {{ number_format($laporanData['top_produks']->sum('total_qty'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-900">
                                        Rp {{ number_format($laporanData['top_produks']->sum('total_nilai'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Transaction List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col w-full mt-6">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                            <i class="ti ti-list-details text-primary-500"></i>
                            Detail Transaksi
                        </h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-gray-200 text-gray-600 rounded-full border border-gray-300">
                            {{ count($laporanData['pembelians']) }} Data
                        </span>
                    </div>
                    <div class="flex-1 overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-3 py-2 font-medium">Tanggal</th>
                                    <th class="px-3 py-2 font-medium">No. Faktur</th>
                                    <th class="px-4 py-3 font-medium">Supplier</th>
                                    <th class="px-4 py-3 font-medium text-center">Status</th>
                                    <th class="px-4 py-3 font-medium text-right">Total</th>
                                    <th class="px-4 py-3 font-medium text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($laporanData['pembelians'] as $transaksi)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($transaksi['tanggal'])->format('d/m/Y') }}</span>
                                                <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($transaksi['created_at'])->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-primary-600 font-mono text-xs">
                                            {{ $transaksi['no_faktur'] }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 truncate max-w-[150px]" title="{{ $transaksi['supplier'] }}">
                                                {{ strtoupper($transaksi['supplier']) }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                                {{ $transaksi['status_pembayaran'] == 'lunas' 
                                                    ? 'bg-green-100 text-green-700 border border-green-200' 
                                                    : 'bg-red-100 text-red-700 border border-red-200' }}">
                                                {{ $transaksi['status_pembayaran'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                                            Rp {{ number_format($transaksi['total'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button onclick="showDetailPembelian({{ $transaksi['id'] }})" 
                                                class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-all shadow-sm">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                                    <i class="ti ti-folder-off text-xl"></i>
                                                </div>
                                                <p class="text-sm font-medium text-gray-600">Tidak ada transaksi ditemukan</p>
                                                <p class="text-xs text-gray-400 mt-1">Coba ubah filter periode laporan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-100">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right font-bold text-gray-900">Total</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                                        Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
            const bulanTahunFilter = document.getElementById('bulanTahunFilter');
            const tanggalFilter = document.getElementById('tanggalFilter');

            if (jenisPeriode === 'bulan') {
                bulanTahunFilter.style.display = 'grid';
                tanggalFilter.style.display = 'none';
            } else {
                bulanTahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'grid';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tanggal_dari", { dateFormat: "d/m/Y", locale: "id" });
            flatpickr("#tanggal_sampai", { dateFormat: "d/m/Y", locale: "id" });

            const printBtn = document.getElementById('printBtn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    const form = document.getElementById('laporanForm');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const url = '{{ route('laporan.pembelian.print') }}' + '?' + params.toString();
                    window.open(url, '_blank');
                });
            }
        });

        // Removed exportPdfBtn event listener as it is no longer used in the simple view

        function showDetailPembelian(pembelianId) {
            Swal.fire({
                title: 'Memuat...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => Swal.showLoading()
            });

            fetch(`/pembelian/${pembelianId}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showDetailModal(data.pembelian);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    }
                })
                .catch(error => Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan' }));
        }

        function showDetailModal(pembelian) {
            const supplier = (pembelian.supplier ? pembelian.supplier.nama : 'Supplier N/A').toUpperCase();
            const status = pembelian.status_pembayaran.toUpperCase();
            const statusColor = pembelian.status_pembayaran === 'lunas' ? 'text-green-600' : 'text-red-600';

            const detailHtml = `
                <div class="text-left">
                    <div class="text-center pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide">Purchase Order</h3>
                        <p class="text-sm text-gray-500 mt-1">${pembelian.no_faktur}</p>
                    </div>

                    <div class="py-4 space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="font-medium text-gray-900">${pembelian.tanggal} ${pembelian.jam}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Supplier</span>
                            <span class="font-medium text-gray-900">${supplier}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-bold ${statusColor}">${status}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-2"></div>

                    <div class="py-2">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Detail Item</h4>
                        <div class="space-y-3">
                            ${pembelian.detail_pembelian.map(detail => `
                                <div class="flex justify-between items-start text-sm">
                                    <div class="flex-1 pr-4">
                                        <div class="font-medium text-gray-900">${(detail.produk ? detail.produk.nama_produk : 'PRODUK DIHAPUS').toUpperCase()}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            ${formatNumber(detail.qty, 2)} x Rp ${formatNumber(detail.harga_beli)} (Disc: ${formatNumber(detail.diskon || 0)})
                                        </div>
                                    </div>
                                    <div class="font-medium text-gray-900 whitespace-nowrap">
                                        Rp ${formatNumber(detail.subtotal)}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-4"></div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp ${formatNumber(pembelian.subtotal)}</span>
                        </div>
                         <div class="flex justify-between text-gray-600">
                            <span>Diskon Akhir</span>
                            <span>Rp ${formatNumber(pembelian.diskon || 0)}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100 mt-2">
                            <span>TOTAL</span>
                            <span>Rp ${formatNumber(pembelian.total)}</span>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                html: detailHtml,
                width: '400px',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#6b7280',
                showCloseButton: true,
                customClass: {
                    container: 'font-sans',
                    popup: 'rounded-xl shadow-xl'
                }
            });
        }

        function formatNumber(num, decimals = 0) {
            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }).format(num);
        }
    </script>
    @endpush
@endsection
