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
                                    <td class="px-5 py-3 font-medium text-gray-900">
                                        <button type="button" 
                                            onclick="showDetailMutasi('{{ $item['produk']->id }}', '{{ $item['produk']->nama_produk }}')"
                                            class="text-left hover:text-blue-600 hover:underline transition-colors">
                                            {{ $item['produk']->nama_produk }}
                                        </button>
                                    </td>
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

    <!-- Modal Detail Mutasi -->
    <div id="detailMutasiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modalTitle">
                                Rincian Mutasi Stok
                            </h3>
                            <p class="text-sm text-gray-500" id="modalSubtitle"></p>
                        </div>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <i class="ti ti-x text-2xl"></i>
                        </button>
                    </div>
                    
                    <div class="mt-2 overflow-x-auto max-h-[60vh]">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-[10px] tracking-wider sticky top-0">
                                <tr>
                                    <th class="px-4 py-2">Tanggal</th>
                                    <th class="px-4 py-2">Jenis</th>
                                    <th class="px-4 py-2">No. Transaksi</th>
                                    <th class="px-4 py-2 text-right">Masuk</th>
                                    <th class="px-4 py-2 text-right">Keluar</th>
                                    <th class="px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="detailMutasiTableBody" class="divide-y divide-gray-50">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
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

        function showDetailMutasi(produkId, namaProduk) {
            const modal = document.getElementById('detailMutasiModal');
            const tableBody = document.getElementById('detailMutasiTableBody');
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');
            
            const tanggalDari = '{{ $tanggalDari ?? "" }}';
            const tanggalSampai = '{{ $tanggalSampai ?? "" }}';
            const bulan = '{{ $selectedBulan ?? "" }}';
            const tahun = '{{ $selectedTahun ?? "" }}';
            const jenisPeriode = '{{ $jenisPeriode ?? "bulan" }}';

            let tDari = tanggalDari;
            let tSampai = tanggalSampai;

            if (jenisPeriode === 'bulan') {
                const lastDay = new Date(tahun, bulan, 0).getDate();
                tDari = `01/${bulan.padStart(2, '0')}/${tahun}`;
                tSampai = `${lastDay}/${bulan.padStart(2, '0')}/${tahun}`;
            }

            modalTitle.innerText = `Rincian Mutasi: ${namaProduk}`;
            modalSubtitle.innerText = `Periode: ${tDari} - ${tSampai}`;
            tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500"><i class="ti ti-loader animate-spin mr-2"></i>Memuat data...</td></tr>';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            fetch(`{{ route('laporan.stok.detail-mutasi') }}?produk_id=${produkId}&tanggal_dari=${tDari}&tanggal_sampai=${tSampai}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        if (result.data.length === 0) {
                            tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada rincian mutasi pada periode ini.</td></tr>';
                            return;
                        }

                        let html = '';
                        result.data.forEach(item => {
                            const dateStr = new Date(item.tanggal).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });

                            let badgeClass = '';
                            let jenisLabel = '';
                            if (item.jenis === 'pembelian') {
                                badgeClass = 'bg-green-100 text-green-700';
                                jenisLabel = 'Pembelian';
                            } else if (item.jenis === 'penjualan') {
                                badgeClass = 'bg-blue-100 text-blue-700';
                                jenisLabel = 'Penjualan';
                            } else {
                                badgeClass = 'bg-gray-100 text-gray-700';
                                jenisLabel = 'Penyesuaian';
                            }

                            const qtyIn = item.jenis === 'pembelian' || (item.jenis === 'penyesuaian' && item.jumlah > 0) ? 
                                parseFloat(item.jumlah).toLocaleString('id-ID', {minimumFractionDigits: 2}) : '-';
                            const qtyOut = item.jenis === 'penjualan' || (item.jenis === 'penyesuaian' && item.jumlah < 0) ? 
                                Math.abs(parseFloat(item.jumlah)).toLocaleString('id-ID', {minimumFractionDigits: 2}) : '-';

                            html += `
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-2 text-xs text-gray-600">${dateStr}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${badgeClass}">${jenisLabel}</span>
                                    </td>
                                    <td class="px-4 py-2 text-xs font-medium text-gray-700">${item.no_transaksi}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-green-600 text-xs">${qtyIn}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-red-600 text-xs">${qtyOut}</td>
                                    <td class="px-4 py-2 text-xs text-gray-500">${item.keterangan || '-'}</td>
                                </tr>
                            `;
                        });
                        tableBody.innerHTML = html;
                    } else {
                        tableBody.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-red-500">${result.message}</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-red-500">Gagal memuat data.</td></tr>';
                });
        }

        function closeModal() {
            const modal = document.getElementById('detailMutasiModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
@endsection
