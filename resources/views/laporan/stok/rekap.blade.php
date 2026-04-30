@extends('layouts.pos')

@section('title', 'Rekap Laporan Stok')
@section('page-title', 'Rekap Laporan Stok')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Rekap Laporan Stok</h2>
                <p class="text-xs text-gray-500">Ringkasan saldo awal dan mutasi stok semua produk</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('laporan.stok.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2 w-fit">
                    <i class="ti ti-arrow-left"></i> Kembali ke Kartu Stok
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-filter text-gray-400"></i>
                <h3 class="text-sm font-bold text-gray-800">Filter Rekap</h3>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('laporan.stok.rekap') }}" id="rekapForm">
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
                                        Harian/Range
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Filters for Bulanan -->
                        <div id="bulanTahunFilter" class="md:col-span-4 grid grid-cols-2 gap-4"
                            style="display: {{ $jenisPeriode == 'bulan' ? 'grid' : 'none' }};">
                            <div class="space-y-1">
                                <label for="bulan" class="block text-xs font-bold text-gray-700">Bulan</label>
                                <select id="bulan" name="bulan" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer">
                                    @foreach ($bulanList as $key => $bulan)
                                        <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label for="tahun" class="block text-xs font-bold text-gray-700">Tahun</label>
                                <select id="tahun" name="tahun" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer">
                                    @foreach($tahunList as $year)
                                        <option value="{{ $year }}" {{ $selectedTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Filters for Harian -->
                        <div id="tanggalRangeFilter" class="md:col-span-4 grid grid-cols-2 gap-4"
                            style="display: {{ $jenisPeriode == 'tanggal' ? 'grid' : 'none' }};">
                            <div class="space-y-1">
                                <label for="tanggal_dari" class="block text-xs font-bold text-gray-700">Dari</label>
                                <input type="text" id="tanggal_dari" name="tanggal_dari" value="{{ $tanggalDari }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30"
                                    placeholder="d/m/Y">
                            </div>
                            <div class="space-y-1">
                                <label for="tanggal_sampai" class="block text-xs font-bold text-gray-700">Sampai</label>
                                <input type="text" id="tanggal_sampai" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30"
                                    placeholder="d/m/Y">
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="md:col-span-5 flex gap-2">
                            <button type="submit"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 h-[38px] flex items-center justify-center gap-2">
                                <i class="ti ti-search text-base"></i>
                                <span>Tampilkan</span>
                            </button>
                            @if (isset($rekapData) && count($rekapData['results']) > 0)
                                <button type="button" id="printBtn"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 h-[38px] flex items-center justify-center gap-2">
                                    <i class="ti ti-printer text-base"></i>
                                    <span>Cetak Rekap</span>
                                </button>
                            @endif
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if (isset($rekapData))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800">
                        Hasil Rekap Stok 
                        <span class="text-blue-600 ml-2">
                            @if ($rekapData['periode']['jenis'] == 'bulan')
                                {{ $rekapData['periode']['bulan_nama'] }} {{ $rekapData['periode']['tahun'] }}
                            @else
                                {{ $rekapData['periode']['tanggal_dari'] }} - {{ $rekapData['periode']['tanggal_sampai'] }}
                            @endif
                        </span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-5 py-3 w-16">No</th>
                                <th class="px-5 py-3">Nama Produk</th>
                                <th class="px-5 py-3">Kategori</th>
                                <th class="px-5 py-3 text-right">Saldo Awal</th>
                                <th class="px-5 py-3 text-right text-green-600">Masuk (+)</th>
                                <th class="px-5 py-3 text-right text-green-600">Nominal Masuk</th>
                                <th class="px-5 py-3 text-right text-red-600">Keluar (-)</th>
                                <th class="px-5 py-3 text-right text-red-600">Nominal Keluar</th>
                                <th class="px-5 py-3 text-right">Saldo Akhir</th>
                                <th class="px-5 py-3 text-center">Satuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($rekapData['results'] as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-3 text-gray-500 text-center">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-900">{{ $item['produk']->nama_produk }}</td>
                                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $item['produk']->kategori->nama ?? '-' }}</td>
                                    <td class="px-5 py-3 text-right font-semibold">{{ number_format($item['saldo_awal'], 2, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-green-600 font-semibold">{{ number_format($item['masuk'], 2, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-green-600 text-xs">Rp {{ number_format($item['masuk_nominal'], 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-red-600 font-semibold">{{ number_format($item['keluar'], 2, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right text-red-600 text-xs">Rp {{ number_format($item['keluar_nominal'], 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-right font-bold text-blue-600">{{ number_format($item['saldo_akhir'], 2, ',', '.') }}</td>
                                    <td class="px-5 py-3 text-center text-xs text-gray-500">{{ $item['produk']->satuan->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-8 text-center text-gray-500">Tidak ada data produk.</td>
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
            flatpickr("#tanggal_dari", { dateFormat: "d/m/Y", locale: "id" });
            flatpickr("#tanggal_sampai", { dateFormat: "d/m/Y", locale: "id" });

            const printBtn = document.getElementById('printBtn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    const form = document.getElementById('rekapForm');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const url = "{{ route('laporan.stok.rekap-print') }}" + "?" + params.toString();
                    window.open(url, '_blank');
                });
            }
        });
    </script>
    @endpush
@endsection
