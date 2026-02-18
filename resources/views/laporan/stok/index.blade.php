@extends('layouts.pos')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Laporan Stok</h2>
                <p class="text-xs text-gray-500">Laporan saldo awal dan pergerakan stok produk per periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-filter text-gray-400"></i>
                <h3 class="text-sm font-bold text-gray-800">Filter Laporan</h3>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('laporan.stok.index') }}" id="laporanForm">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                        <!-- Produk Filter -->
                        <div class="md:col-span-3 space-y-1">
                            <label for="produk_id" class="block text-xs font-bold text-gray-700">Produk <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="produk_id" id="produk_id"
                                    class="w-full border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 bg-white cursor-pointer select2"
                                    required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($produkList as $produk)
                                        <option value="{{ $produk->id }}" {{ $selectedProduk == $produk->id ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }} ({{ $produk->kategori->nama ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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
                        <div id="bulanTahunFilter" class="md:col-span-3 grid grid-cols-2 gap-4"
                            style="display: {{ $jenisPeriode == 'bulan' ? 'grid' : 'none' }};">
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
                        <div id="tanggalRangeFilter" class="md:col-span-3 grid grid-cols-2 gap-4"
                            style="display: {{ $jenisPeriode == 'tanggal' ? 'grid' : 'none' }};">
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
                            @if (isset($laporanData) && $laporanData)
                                <button type="button" id="printBtn"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 h-[38px] flex items-center justify-center gap-2">
                                    <i class="ti ti-printer text-base"></i>
                                    <span class="hidden sm:inline">Cetak</span>
                                </button>
                            @endif
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if (isset($laporanData) && $laporanData)
             <!-- Product Info -->
             <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-start gap-4">
                    @if ($laporanData['produk']['foto'])
                        <img src="{{ asset('storage/' . $laporanData['produk']['foto']) }}" alt="{{ $laporanData['produk']['nama_produk'] }}"
                            class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    @else
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 text-gray-400">
                            <i class="ti ti-box text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $laporanData['produk']['nama_produk'] }}</h3>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $laporanData['produk']['kategori'] }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $laporanData['produk']['satuan'] }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            @if ($laporanData['periode']['jenis'] == 'tanggal')
                                Periode: {{ $laporanData['periode']['tanggal_dari'] }} s/d {{ $laporanData['periode']['tanggal_sampai'] }}
                            @else
                                Periode: {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Saldo Awal -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="ti ti-package text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Saldo Awal</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['saldo_awal'], 2, ',', '.') }}</p>
                </div>

                <!-- Total Pembelian -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                            <i class="ti ti-arrow-down text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Pembelian</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['total_pembelian'], 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($laporanData['summary']['total_pembelian_uang'] ?? 0, 0, ',', '.') }}</p>
                </div>

                <!-- Total Penjualan -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                            <i class="ti ti-arrow-up text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Penjualan</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['total_penjualan'], 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($laporanData['summary']['total_penjualan_uang'] ?? 0, 0, ',', '.') }}</p>
                </div>

                <!-- Total Penyesuaian -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                            <i class="ti ti-adjustments text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Penyesuaian</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['total_penyesuaian'], 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($laporanData['summary']['total_penyesuaian_uang'] ?? 0, 0, ',', '.') }}</p>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                            <i class="ti ti-database text-lg"></i>
                        </div>
                        <p class="text-xs text-purple-700 font-bold uppercase">Saldo Akhir</p>
                    </div>
                    <p class="text-lg font-bold text-purple-900">{{ number_format($laporanData['summary']['saldo_akhir'], 2, ',', '.') }}</p>
                    <p class="text-xs text-purple-600 mt-1">Nilai: Rp {{ number_format($laporanData['summary']['nilai_stok'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Detail Transaksi Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i class="ti ti-list text-gray-500"></i> Kartu Stok
                    </h3>
                    @if (isset($laporanData['saldo_awal_terakhir']) && $laporanData['saldo_awal_terakhir'])
                         <div class="text-[10px] text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">
                            <i class="ti ti-info-circle mr-1"></i>
                            Saldo awal dr {{ $laporanData['saldo_awal_terakhir']['periode_saldo_awal'] }}:
                            {{ number_format($laporanData['saldo_awal_terakhir']['saldo'], 2, ',', '.') }}
                        </div>
                    @elseif (isset($laporanData['saldo_awal_bulan']) && $laporanData['saldo_awal_bulan'] > 0)
                        <div class="text-[10px] text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">
                            <i class="ti ti-info-circle mr-1"></i>
                            Saldo Awal Bulan: {{ number_format($laporanData['saldo_awal_bulan'], 2, ',', '.') }}
                        </div>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Keterangan</th>
                                <th class="px-5 py-3 text-center">Jenis</th>
                                <th class="px-5 py-3">No Transaksi</th>
                                <th class="px-5 py-3 text-right text-green-600">In</th>
                                <th class="px-5 py-3 text-right text-green-600">Jumlah (Rp)</th>
                                <th class="px-5 py-3 text-right text-red-600">Out</th>
                                <th class="px-5 py-3 text-right text-red-600">Jumlah (Rp)</th>
                                <th class="px-5 py-3 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php
                                $runningSaldo = $laporanData['saldo_awal'];
                            @endphp
                            
                            <!-- Saldo Awal Row -->
                            <tr class="bg-gray-50/50 italic">
                                <td class="px-5 py-3 text-xs text-gray-500">
                                     @if ($laporanData['periode']['jenis'] == 'tanggal')
                                        {{ $laporanData['periode']['tanggal_dari'] }}
                                    @else
                                        {{ $laporanData['periode']['tanggal_awal'] }}
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500" colspan="3">Saldo Awal Periode</td>
                                <td class="px-5 py-3 text-right text-xs text-gray-500">-</td>
                                <td class="px-5 py-3 text-right text-xs text-gray-500">-</td>
                                <td class="px-5 py-3 text-right text-xs text-gray-500">-</td>
                                <td class="px-5 py-3 text-right text-xs text-gray-500">-</td>
                                <td class="px-5 py-3 text-right font-bold text-gray-800">{{ number_format($runningSaldo, 2, ',', '.') }}</td>
                            </tr>

                            @forelse ($laporanData['transaksi'] as $transaksi)
                                @php
                                    if ($transaksi->jenis == 'pembelian') {
                                        $runningSaldo += $transaksi->jumlah;
                                    } elseif ($transaksi->jenis == 'penjualan') {
                                        $runningSaldo -= $transaksi->jumlah;
                                    } elseif ($transaksi->jenis == 'penyesuaian') {
                                        $runningSaldo += $transaksi->jumlah;
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="text-xs font-medium text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-xs text-gray-700 max-w-[200px] truncate" title="{{ $transaksi->keterangan }}">
                                            {{ $transaksi->keterangan }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                         @if ($transaksi->jenis == 'pembelian')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                                                BELI
                                            </span>
                                        @elseif ($transaksi->jenis == 'penjualan')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                                JUAL
                                            </span>
                                        @elseif ($transaksi->jenis == 'penyesuaian')
                                             <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                                ADJ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500">
                                        {{ $transaksi->no_transaksi }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-medium text-green-700">
                                        @if ($transaksi->jenis == 'pembelian')
                                            {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                        @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah > 0)
                                            {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-medium text-green-700">
                                        @if ($transaksi->jenis == 'pembelian')
                                            {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-medium text-red-700">
                                        @if ($transaksi->jenis == 'penjualan')
                                            {{ number_format($transaksi->jumlah, 2, ',', '.') }}
                                        @elseif ($transaksi->jenis == 'penyesuaian' && $transaksi->jumlah < 0)
                                            {{ number_format(abs($transaksi->jumlah), 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-medium text-red-700">
                                        @if ($transaksi->jenis == 'penjualan')
                                            {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-gray-900">
                                        {{ number_format($runningSaldo, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="ti ti-file-off text-3xl text-gray-300"></i>
                                            <p class="text-sm">Tidak ada transaksi pada periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('#produk_id').select2({
                placeholder: 'Cari Produk...',
                allowClear: true,
                width: '100%'
            });

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

                    const url = "{{ route('laporan.stok.print') }}" + "?" + params.toString();
                    window.open(url, '_blank');
                });
            }
        });
    </script>
    @endpush
@endsection
