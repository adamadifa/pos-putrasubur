@extends('layouts.pos')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Laporan Pembayaran</h2>
                <p class="text-sm text-gray-500 mt-1">Laporan pembayaran penjualan dan pembelian per periode</p>
            </div>
            
            <!-- Summary Cards -->
            @if(isset($laporanData))
            <div class="flex gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="ti ti-credit-card text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Transaksi</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['total_pembayaran']) }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <i class="ti ti-cash text-emerald-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Nilai</p>
                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-5 border-b border-gray-100">
                <form method="GET" action="{{ route('laporan.pembayaran.index') }}" id="laporanForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                        
                        <!-- Periode Toggle -->
                        <div class="lg:col-span-6 mb-2">
                             <div class="flex items-center space-x-4">
                                <label class="text-sm font-medium text-gray-700">Periode:</label>
                                <div class="flex bg-gray-100 p-1 rounded-lg">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_periode" value="bulan" class="peer sr-only" {{ $jenisPeriode == 'bulan' ? 'checked' : '' }} onchange="togglePeriodeType()">
                                        <span class="block px-3 py-1.5 text-xs font-medium rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-gray-500 hover:text-gray-700">Per Bulan</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_periode" value="tanggal" class="peer sr-only" {{ $jenisPeriode == 'tanggal' ? 'checked' : '' }} onchange="togglePeriodeType()">
                                        <span class="block px-3 py-1.5 text-xs font-medium rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm text-gray-500 hover:text-gray-700">Per Tanggal</span>
                                    </label>
                                </div>
                             </div>
                        </div>

                        <!-- Filter Fields -->
                        
                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Transaksi <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="ti ti-exchange absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <select name="jenis_transaksi" id="jenis_transaksi" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="penjualan" {{ $selectedJenisTransaksi == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                                    <option value="pembelian" {{ $selectedJenisTransaksi == 'pembelian' ? 'selected' : '' }}>Pembelian</option>
                                    <option value="semua" {{ $selectedJenisTransaksi == 'semua' ? 'selected' : '' }}>Semua</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kas/Bank -->
                        <div>
                             <label for="kas_bank_id" class="block text-xs font-medium text-gray-700 mb-1">Kas/Bank</label>
                             <div class="relative">
                                 <i class="ti ti-building-bank absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                 <select name="kas_bank_id" id="kas_bank_id" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                     <option value="">Semua</option>
                                     @foreach ($kasBankList as $kasBank)
                                         <option value="{{ $kasBank->id }}" {{ $selectedKasBank == $kasBank->id ? 'selected' : '' }}>
                                             {{ strtoupper($kasBank->nama) }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                        </div>
                        
                        <!-- Metode Pembayaran -->
                         <div>
                             <label for="metode_pembayaran_id" class="block text-xs font-medium text-gray-700 mb-1">Metode Bayar</label>
                             <div class="relative">
                                 <i class="ti ti-wallet absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                 <select name="metode_pembayaran_id" id="metode_pembayaran_id" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                     <option value="">Semua</option>
                                     @foreach ($metodePembayaranList as $metode)
                                         <option value="{{ $metode->id }}" {{ $selectedMetodePembayaran == $metode->id ? 'selected' : '' }}>
                                             {{ strtoupper($metode->nama) }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                        </div>

                        <!-- Bulan & Tahun (Show if Bulan) -->
                        <div id="bulanFilter" class="{{ $jenisPeriode == 'tanggal' ? 'hidden' : 'block' }}">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                            <div class="relative">
                                <i class="ti ti-calendar-event absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <select name="bulan" id="bulan" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    @foreach ($bulanList as $key => $bulan)
                                        <option value="{{ $key }}" {{ $selectedBulan == $key ? 'selected' : '' }}>{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="tahunFilter" class="{{ $jenisPeriode == 'tanggal' ? 'hidden' : 'block' }}">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                            <div class="relative">
                                <i class="ti ti-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <select name="tahun" id="tahun" class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    @foreach ($tahunList as $tahun)
                                        <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Range (Show if Tanggal) -->
                        <div id="tanggalDariFilter" class="{{ $jenisPeriode == 'bulan' ? 'hidden' : 'block' }}">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <div class="relative">
                                <i class="ti ti-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="tanggal_dari" id="tanggal_dari" value="{{ $tanggalDari }}" 
                                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" 
                                    placeholder="Pilih Tanggal">
                            </div>
                        </div>

                        <div id="tanggalSampaiFilter" class="{{ $jenisPeriode == 'bulan' ? 'hidden' : 'block' }}">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <div class="relative">
                                <i class="ti ti-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="tanggal_sampai" id="tanggal_sampai" value="{{ $tanggalSampai }}" 
                                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" 
                                    placeholder="Pilih Tanggal">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="md:col-span-2 lg:col-span-1 flex gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                                <i class="ti ti-filter"></i>
                                Tampilkan
                            </button>
                            
                            @if($laporanData)
                            <button type="button" id="printBtn" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                                <i class="ti ti-printer"></i>
                                Cetak
                            </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Content -->
            @if ($laporanData)
                <div class="p-5">
                    <!-- Report Header Info -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                        <div>
                             <h3 class="text-base font-semibold text-gray-900">
                                Detail Pembayaran: 
                                <span class="text-blue-600">
                                    {{ $laporanData['periode']['label'] ?? ($laporanData['periode']['jenis'] == 'bulan' ? $laporanData['periode']['bulan_nama'].' '.$laporanData['periode']['tahun'] : $laporanData['periode']['tanggal_dari'].' - '.$laporanData['periode']['tanggal_sampai']) }}
                                </span>
                             </h3>
                        </div>
                         <div class="text-xs text-gray-500">
                             Dicetak: {{ now()->format('d/m/Y H:i') }}
                         </div>
                    </div>

                    <!-- Payment Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Faktur</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">PIHAK TERKAIT</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kas/Bank</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($laporanData['pembayaran'] as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        @if ($item->jenis == 'Penjualan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                                                PENJUALAN
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/10">
                                                PEMBELIAN
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->no_faktur }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ strtoupper($item->nama_pelanggan_supplier) }}
                                    </td>
                                     <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ strtoupper($item->metode_pembayaran) }}
                                    </td>
                                     <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ strtoupper($item->kas_bank) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-bold text-right">
                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500 italic">
                                        Tidak ada data pembayaran untuk periode ini
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-right text-sm font-bold text-gray-900">TOTAL</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-900 border-t-2 border-gray-300">
                                         Rp {{ number_format($laporanData['summary']['total_nilai'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Additional Summaries -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- By Method -->
                         @if (isset($laporanData['metode_pembayaran_counts']) && count($laporanData['metode_pembayaran_counts']) > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase">Rekap Metode Pembayaran</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($laporanData['metode_pembayaran_counts'] as $metode)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ strtoupper($metode['nama']) }}</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-500">{{ $metode['count'] }} Trx</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">Rp {{ number_format($metode['nilai'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- By Kas/Bank -->
                         @if (isset($laporanData['kas_bank_counts']) && count($laporanData['kas_bank_counts']) > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                             <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 uppercase">Rekap Kas/Bank</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($laporanData['kas_bank_counts'] as $kb)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ strtoupper($kb['nama']) }}</td>
                                         <td class="px-4 py-3 text-sm text-center text-gray-500">{{ $kb['count'] }} Trx</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">Rp {{ number_format($kb['nilai'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                </div>
            @else
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="ti ti-file-search text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada data laporan</h3>
                    <p class="mt-2 text-sm text-gray-500">Silakan pilih periode dan parameter laporan untuk menampilkan data.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Init Flatpickr
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

            // Initial toggle
            togglePeriodeType();

            // Print Button Listener
            const printBtn = document.getElementById('printBtn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    const form = document.getElementById('laporanForm');
                    const params = new URLSearchParams(new FormData(form));
                    const url = "{{ route('laporan.pembayaran.print') }}?" + params.toString();
                    window.open(url, '_blank');
                });
            }
        });

        function togglePeriodeType() {
            const jenisPeriode = document.querySelector('input[name="jenis_periode"]:checked').value;
            const bulanFilter = document.getElementById('bulanFilter');
            const tahunFilter = document.getElementById('tahunFilter');
            const tanggalDariFilter = document.getElementById('tanggalDariFilter');
            const tanggalSampaiFilter = document.getElementById('tanggalSampaiFilter');

            if (jenisPeriode === 'bulan') {
                bulanFilter.classList.remove('hidden');
                tahunFilter.classList.remove('hidden');
                tanggalDariFilter.classList.add('hidden');
                tanggalSampaiFilter.classList.add('hidden');
                
                // Adjust grid
                bulanFilter.parentElement.classList.remove('md:grid-cols-2');
            } else {
                bulanFilter.classList.add('hidden');
                tahunFilter.classList.add('hidden');
                tanggalDariFilter.classList.remove('hidden');
                tanggalSampaiFilter.classList.remove('hidden');
            }
        }
    </script>
@endsection
