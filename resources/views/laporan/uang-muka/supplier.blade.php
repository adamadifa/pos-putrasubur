@extends('layouts.pos')

@section('title', 'Laporan Uang Muka Supplier')
@section('page-title', 'Laporan Uang Muka Supplier')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Laporan Uang Muka Supplier</h2>
                <p class="text-xs text-gray-500">Daftar pembayaran dan penggunaan uang muka ke supplier</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                <i class="ti ti-filter text-gray-400"></i>
                <h3 class="text-sm font-bold text-gray-800">Filter Periode</h3>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('laporan.uang-muka-supplier.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 h-[38px] flex items-center justify-center gap-2">
                                <i class="ti ti-search text-base"></i>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-5 py-3 w-16">No</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">No. Uang Muka</th>
                            <th class="px-5 py-3">Supplier</th>
                            <th class="px-5 py-3 text-right">Total Uang Muka</th>
                            <th class="px-5 py-3 text-right text-orange-600">Digunakan</th>
                            <th class="px-5 py-3 text-right text-green-600">Sisa</th>
                            <th class="px-5 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php
                            $totalUM = 0;
                            $totalUsed = 0;
                            $totalSisa = 0;
                        @endphp
                        @forelse ($data as $index => $item)
                            @php
                                $totalUM += $item->jumlah_uang_muka;
                                $totalUsed += $item->total_digunakan;
                                $totalSisa += $item->sisa_uang_muka;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3 text-gray-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-5 py-3">{{ $item->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 font-medium text-blue-600">{{ $item->no_uang_muka }}</td>
                                <td class="px-5 py-3">{{ $item->supplier->nama }}</td>
                                <td class="px-5 py-3 text-right font-semibold">Rp {{ number_format($item->jumlah_uang_muka, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right text-orange-600 font-semibold">Rp {{ number_format($item->total_digunakan, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right text-green-600 font-bold">Rp {{ number_format($item->sisa_uang_muka, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $item->status == 'aktif' ? 'bg-green-50 text-green-700 border border-green-100' : 
                                           ($item->status == 'habis' ? 'bg-gray-50 text-gray-600 border border-gray-200' : 'bg-red-50 text-red-700 border border-red-100') }}">
                                        {{ $item->status_display }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-500">Tidak ada data uang muka pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                        <tr>
                            <td colspan="4" class="px-5 py-3 text-center text-gray-800 uppercase">Total</td>
                            <td class="px-5 py-3 text-right text-gray-900">Rp {{ number_format($totalUM, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-orange-600">Rp {{ number_format($totalUsed, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right text-green-600">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#tanggal_dari", { dateFormat: "d/m/Y", locale: "id" });
            flatpickr("#tanggal_sampai", { dateFormat: "d/m/Y", locale: "id" });
        });
    </script>
    @endpush
@endsection
