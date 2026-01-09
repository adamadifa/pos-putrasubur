@extends('layouts.pos')

@section('title', 'Laporan Piutang')
@section('page-title', 'Laporan Piutang')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Laporan Piutang</h2>
                <p class="text-xs text-gray-500">Laporan detail piutang pelanggan berdasarkan periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-filter text-gray-400"></i>
                <h3 class="text-sm font-bold text-gray-800">Filter Laporan</h3>
            </div>
            <div class="p-4">
                <form action="{{ route('laporan.piutang.index') }}" method="GET" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <!-- Pelanggan Filter -->
                        <div class="md:col-span-3 space-y-1">
                            <label for="pelanggan_id" class="block text-xs font-bold text-gray-700">Pelanggan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-users text-sm"></i></span>
                                <select name="pelanggan_id" id="pelanggan_id"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer appearance-none">
                                    <option value="">Semua Pelanggan</option>
                                    @foreach ($pelangganList ?? [] as $pelanggan)
                                        <option value="{{ $pelanggan->id }}"
                                            {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                         <!-- Status Filter -->
                        <div class="md:col-span-3 space-y-1">
                            <label for="status" class="block text-xs font-bold text-gray-700">Status</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-check text-sm"></i></span>
                                <select name="status" id="status"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer appearance-none">
                                    <option value="">Semua Status</option>
                                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="dp" {{ request('status') == 'dp' ? 'selected' : '' }}>Down Payment</option>
                                    <option value="angsuran" {{ request('status') == 'angsuran' ? 'selected' : '' }}>Angsuran</option>
                                </select>
                            </div>
                        </div>

                        <!-- Periode Type -->
                        <div class="md:col-span-3 space-y-1">
                            <label class="block text-xs font-bold text-gray-700">Jenis Periode</label>
                            <div class="flex rounded-lg bg-gray-50/50 p-1 border border-gray-200">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis_periode" value="semua"
                                        {{ request('jenis_periode') == 'semua' || request('jenis_periode') == '' || request('jenis_periode') == null ? 'checked' : '' }}
                                        class="peer sr-only" onchange="togglePeriodeType()">
                                    <div class="text-center py-1.5 px-3 text-xs font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all">
                                        Semua
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis_periode" value="bulan"
                                        {{ request('jenis_periode') == 'bulan' ? 'checked' : '' }}
                                        class="peer sr-only" onchange="togglePeriodeType()">
                                    <div class="text-center py-1.5 px-3 text-xs font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all">
                                        Bulanan
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="jenis_periode" value="tanggal"
                                        {{ request('jenis_periode') == 'tanggal' ? 'checked' : '' }}
                                        class="peer sr-only" onchange="togglePeriodeType()">
                                    <div class="text-center py-1.5 px-3 text-xs font-medium text-gray-500 rounded-md peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all">
                                        Harian
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                         <!-- Spacer col for alignment in large screens if needed, simplified here -->
                         <div class="md:col-span-3"></div>


                        <!-- Filters for Bulanan -->
                        <div id="bulanTahunFilter" class="md:col-span-6 grid grid-cols-2 gap-4"
                             style="display: {{ request('jenis_periode') == 'bulan' ? 'grid' : 'none' }};">
                            <div class="space-y-1">
                                <label for="bulan" class="block text-xs font-bold text-gray-700">Bulan</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar-month text-sm"></i></span>
                                    <select name="bulan" id="bulan"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer appearance-none">
                                        @foreach ($bulanList as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ request('bulan') == $key || (request('bulan') == null && $key == date('n')) ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="tahun" class="block text-xs font-bold text-gray-700">Tahun</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                    <select name="tahun" id="tahun"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 cursor-pointer appearance-none">
                                        @foreach ($tahunList as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ request('tahun') == $tahun || (request('tahun') == null && $tahun == date('Y')) ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                         <!-- Filters for Harian -->
                        <div id="tanggalRangeFilter" class="md:col-span-6 grid grid-cols-2 gap-4"
                             style="display: {{ request('jenis_periode') == 'tanggal' ? 'grid' : 'none' }};">
                            <div class="space-y-1">
                                <label for="tanggal_dari" class="block text-xs font-bold text-gray-700">Dari</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                    <input type="text" id="tanggal_dari" name="tanggal_dari"
                                        value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                                        placeholder="Pilih tanggal">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label for="tanggal_sampai" class="block text-xs font-bold text-gray-700">Sampai</label>
                                <div class="relative">
                                   <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                    <input type="text" id="tanggal_sampai" name="tanggal_sampai"
                                        value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                                        placeholder="Pilih tanggal">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="md:col-span-12 flex gap-2 justify-end mt-2">
                             <button type="submit"
                                class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all h-[38px] flex items-center justify-center gap-2">
                                <i class="ti ti-search text-base"></i>
                                <span>Tampilkan</span>
                            </button>
                             @if (isset($laporanData))
                                <button type="button" id="printBtn"
                                    class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 h-[38px] flex items-center justify-center gap-2">
                                    <i class="ti ti-printer text-base"></i>
                                    <span>Cetak</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($laporanData))
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Piutang -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                             <i class="ti ti-receipt-2 text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Total Piutang</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['total_piutang'], 0, ',', '.') }}</p>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                            <i class="ti ti-check text-lg"></i>
                        </div>
                         <p class="text-xs text-gray-500 font-bold uppercase">Total Transaksi</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['total_transaksi'], 0, ',', '.') }}</p>
                </div>

                <!-- Belum Bayar -->
                <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                            <i class="ti ti-alert-circle text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Belum Bayar</p>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($laporanData['summary']['belum_bayar'], 0, ',', '.') }}</p>
                </div>

                <!-- Down Payment -->
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                             <i class="ti ti-clock-dollar text-lg"></i>
                        </div>
                        <p class="text-xs text-purple-700 font-bold uppercase">Down Payment</p>
                    </div>
                     <p class="text-lg font-bold text-purple-900">{{ number_format($laporanData['summary']['dp'], 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Detail Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                         <i class="ti ti-list text-gray-500"></i> Detail Piutang
                    </h3>
                    @if (isset($laporanData['periode']))
                        <div class="text-[10px] text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">
                            <i class="ti ti-calendar mr-1"></i>
                            Periode:
                            @if ($laporanData['periode']['jenis'] == 'semua')
                                {{ $laporanData['periode']['deskripsi'] }}
                            @elseif ($laporanData['periode']['jenis'] == 'bulan')
                                {{ $laporanData['periode']['bulan_nama'] }} {{ $laporanData['periode']['tahun'] }}
                            @else
                                {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_dari'])->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($laporanData['periode']['tanggal_sampai'])->format('d M Y') }}
                            @endif
                        </div>
                    @endif
                </div>

                 <!-- Mobile Card View -->
                <div class="block md:hidden p-4 space-y-3">
                    @forelse($laporanData['piutangs'] ?? [] as $piutang)
                        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 mb-1">{{ $piutang['no_faktur'] }}</div>
                                    <div class="text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($piutang['tanggal'])->format('d M Y') }}</div>
                                </div>
                                <div>
                                    @if ($piutang['status'] == 'lunas')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">LUNAS</span>
                                    @elseif($piutang['status'] == 'dp')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">DP</span>
                                    @elseif($piutang['status'] == 'angsuran')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">ANGSURAN</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">BELUM BAYAR</span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Pelanggan:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $piutang['pelanggan'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Total:</span>
                                    <span class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($piutang['total'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-600">Terbayar:</span>
                                    <span class="text-sm font-medium text-green-600">Rp
                                        {{ number_format($piutang['terbayar'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-2">
                                    <span class="text-xs font-semibold text-gray-800">Sisa:</span>
                                    <span class="text-sm font-bold text-red-600">Rp
                                        {{ number_format($piutang['sisa'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="ti ti-file-off text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm">Tidak ada data piutang pada periode ini</p>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-5 py-3">No. Faktur</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3 text-right">Total</th>
                                <th class="px-5 py-3 text-right text-green-600">Terbayar</th>
                                <th class="px-5 py-3 text-right text-red-600">Sisa</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($laporanData['piutangs'] ?? [] as $piutang)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="text-sm font-bold text-gray-900">{{ $piutang['no_faktur'] }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($piutang['tanggal'])->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $piutang['pelanggan'] }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($piutang['total'], 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <div class="text-sm font-medium text-green-600">Rp {{ number_format($piutang['terbayar'], 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <div class="text-sm font-bold text-red-600">Rp {{ number_format($piutang['sisa'], 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                         @if ($piutang['status'] == 'lunas')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">LUNAS</span>
                                        @elseif($piutang['status'] == 'dp')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">DP</span>
                                        @elseif($piutang['status'] == 'angsuran')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">ANGSURAN</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">BELUM BAYAR</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                         <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="ti ti-file-off text-3xl text-gray-300"></i>
                                            <p class="text-sm">Tidak ada data piutang pada periode ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (isset($laporanData['piutangs']) && $laporanData['piutangs']->count() > 0)
                            <tfoot class="bg-gray-50 border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-5 py-3 text-right font-bold text-gray-900 text-xs uppercase tracking-wider">
                                        Total
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-gray-900">
                                        Rp {{ number_format($laporanData['piutangs']->sum('total'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-green-600">
                                        Rp {{ number_format($laporanData['piutangs']->sum('terbayar'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-red-600">
                                        Rp {{ number_format($laporanData['piutangs']->sum('sisa'), 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Tabel Rekap Pelanggan -->
            @if (isset($laporanData['rekap_pelanggan']) && $laporanData['rekap_pelanggan']->count() > 0)
                 <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                         <i class="ti ti-chart-pie text-gray-400"></i>
                        <h3 class="text-sm font-bold text-gray-800">Rekap Berdasarkan Pelanggan</h3>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="block md:hidden p-4 space-y-3">
                         @foreach ($laporanData['rekap_pelanggan'] as $rekap)
                             <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                         <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-3">
                                             {{ substr($rekap['pelanggan'], 0, 2) }}
                                         </div>
                                        <div class="text-sm font-medium text-gray-900">{{ $rekap['pelanggan'] }}</div>
                                    </div>
                                    <div>
                                         @if ($rekap['sisa_piutang'] <= 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">LUNAS</span>
                                        @elseif ($rekap['total_terbayar'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">ANGSURAN</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">BELUM BAYAR</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">Transaksi:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $rekap['total_transaksi'] }} Transaksi</span>
                                    </div>
                                     <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">Total Piutang:</span>
                                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($rekap['total_piutang'], 0, ',', '.') }}</span>
                                    </div>
                                     <div class="flex justify-between">
                                        <span class="text-xs text-gray-600">Terbayar:</span>
                                        <span class="text-sm font-medium text-green-600">Rp {{ number_format($rekap['total_terbayar'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 pt-2">
                                        <span class="text-xs font-semibold text-gray-800">Sisa:</span>
                                        <span class="text-sm font-bold text-red-600">Rp {{ number_format($rekap['sisa_piutang'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                             </div>
                         @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-5 py-3">Pelanggan</th>
                                    <th class="px-5 py-3 text-center">Jml Transaksi</th>
                                    <th class="px-5 py-3 text-right">Total Piutang</th>
                                    <th class="px-5 py-3 text-right text-green-600">Terbayar</th>
                                    <th class="px-5 py-3 text-right text-red-600">Sisa Piutang</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($laporanData['rekap_pelanggan'] as $rekap)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-3">
                                                    {{ substr($rekap['pelanggan'], 0, 2) }}
                                                </div>
                                                <div class="font-medium text-gray-900">{{ $rekap['pelanggan'] }}</div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $rekap['total_transaksi'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-right font-medium text-gray-900">
                                            Rp {{ number_format($rekap['total_piutang'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3 text-right font-medium text-green-600">
                                            Rp {{ number_format($rekap['total_terbayar'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3 text-right font-bold text-red-600">
                                            Rp {{ number_format($rekap['sisa_piutang'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                             @if ($rekap['sisa_piutang'] <= 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">LUNAS</span>
                                            @elseif ($rekap['total_terbayar'] > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">ANGSURAN</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">BELUM BAYAR</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

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
            } else if (jenisPeriode === 'tanggal') {
                bulanTahunFilter.style.display = 'none';
                tanggalFilter.style.display = 'grid';
            } else {
                 bulanTahunFilter.style.display = 'none';
                 tanggalFilter.style.display = 'none';
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
                    const form = document.getElementById('filterForm');
                    // clone form to submit to different route
                    const printForm = document.createElement('form');
                    printForm.action = '{{ route('laporan.piutang.print') }}'; 
                    printForm.method = 'GET';
                    printForm.target = '_blank';
                    
                    // Copy all inputs
                    Array.from(form.elements).forEach(element => {
                         if (element.name) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = element.name;
                            input.value = element.value;
                            if(element.type === 'radio' && element.checked) {
                                printForm.appendChild(input);
                            } else if(element.type !== 'radio') {
                                printForm.appendChild(input);
                            }
                        }
                    });
                    
                    document.body.appendChild(printForm);
                    printForm.submit();
                    document.body.removeChild(printForm);
                });
            }
        });
    </script>
    @endpush
@endsection
