@extends('layouts.pos')

@section('title', 'Detail Uang Muka Supplier')
@section('page-title', 'Detail Uang Muka Supplier')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-orange-50 to-red-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('uang-muka-supplier.index') }}"
                            class="p-2 text-gray-400 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-red-600 rounded-xl transition-all">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $uangMuka->no_uang_muka }}</h1>
                            <p class="text-sm text-gray-500">{{ $uangMuka->tanggal->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if ($uangMuka->status == 'aktif')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="ti ti-circle-check mr-1"></i>
                                Aktif
                            </span>
                        @elseif ($uangMuka->status == 'habis')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <i class="ti ti-check mr-1"></i>
                                Habis
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="ti ti-x mr-1"></i>
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Supplier Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-orange-50 to-red-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-building-store text-orange-600"></i>
                            </div>
                            Informasi Supplier
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center">
                                <i class="ti ti-building-store text-2xl text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $uangMuka->supplier->nama ?? 'N/A' }}</h4>
                                @if ($uangMuka->supplier->kode_supplier)
                                    <p class="text-sm text-gray-500">{{ $uangMuka->supplier->kode_supplier }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Histori Penggunaan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-history text-blue-600"></i>
                            </div>
                            Histori Penggunaan Uang Muka
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($uangMuka->penggunaanPembelian->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Faktur</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah Digunakan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($uangMuka->penggunaanPembelian as $penggunaan)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $penggunaan->tanggal_penggunaan->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <a href="{{ route('pembelian.show', $penggunaan->pembelian->encrypted_id) }}"
                                                        class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                        {{ $penggunaan->pembelian->no_faktur }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                                    <span class="text-sm font-medium text-red-600">
                                                        Rp {{ number_format($penggunaan->jumlah_digunakan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    {{ $penggunaan->keterangan ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <a href="{{ route('pembelian.show', $penggunaan->pembelian->encrypted_id) }}"
                                                        class="text-blue-600 hover:text-blue-800" title="Lihat Faktur">
                                                        <i class="ti ti-eye text-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="ti ti-inbox text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Belum ada penggunaan uang muka</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-currency-dollar text-green-600"></i>
                            </div>
                            Ringkasan
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Jumlah Uang Muka</span>
                            <span class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($uangMuka->jumlah_uang_muka, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Digunakan</span>
                            <span class="text-lg font-bold text-red-600">
                                Rp {{ number_format($uangMuka->penggunaanPembelian->sum('jumlah_digunakan'), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Sisa Uang Muka</span>
                                <span class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($uangMuka->sisa_uang_muka, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-credit-card text-purple-600"></i>
                            </div>
                            Informasi Pembayaran
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <span class="text-xs text-gray-500">Metode Pembayaran</span>
                            <p class="text-sm font-medium text-gray-900 capitalize">{{ $uangMuka->metode_pembayaran }}</p>
                        </div>
                        @if ($uangMuka->kasBank)
                            <div>
                                <span class="text-xs text-gray-500">Kas/Bank</span>
                                <p class="text-sm font-medium text-gray-900">{{ $uangMuka->kasBank->nama }}</p>
                            </div>
                        @endif
                        <div>
                            <span class="text-xs text-gray-500">Dibuat oleh</span>
                            <p class="text-sm font-medium text-gray-900">{{ $uangMuka->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Tanggal Dibuat</span>
                            <p class="text-sm font-medium text-gray-900">{{ $uangMuka->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Keterangan Card -->
                @if ($uangMuka->keterangan)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-slate-50">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="ti ti-note text-gray-600"></i>
                                </div>
                                Keterangan
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-700">{{ $uangMuka->keterangan }}</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                @if ($uangMuka->status == 'aktif' && $uangMuka->penggunaanPembelian->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <form action="{{ route('uang-muka-supplier.cancel', $uangMuka->encrypted_id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan uang muka ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                                <i class="ti ti-x text-lg mr-2"></i>
                                Batalkan Uang Muka
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

