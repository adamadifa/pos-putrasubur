@extends('layouts.pos')

@section('title', 'Daftar Penjualan')
@section('page-title', 'Transaksi Penjualan')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Riwayat Transaksi</h2>
                <p class="text-sm text-gray-500">Pantau penjualan dan status pembayaran</p>
            </div>
            <a href="{{ route('penjualan.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                <i class="ti ti-shopping-cart-plus text-lg mr-2"></i>
                Transaksi Baru
            </a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg text-green-600">
                        <i class="ti ti-check text-lg"></i>
                    </div>
                    <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif

        <!-- Stats Cards (Gradient Style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Penjualan Card -->
            <div class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-shopping-cart text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-blue-100">Total Transaksi</h3>
                                <p class="text-3xl font-bold text-white">{{ number_format($totalPenjualan) }}</p>
                                <div class="flex items-center mt-1">
                                    @if ($perubahanPenjualan >= 0)
                                        <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                        <span class="text-xs text-green-300">+{{ abs(round($perubahanPenjualan, 1)) }}%</span>
                                    @else
                                        <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                        <span class="text-xs text-red-300">-{{ abs(round($perubahanPenjualan, 1)) }}%</span>
                                    @endif
                                    <span class="text-xs text-blue-200 ml-1">dari kemarin</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nilai Penjualan Card -->
            <div class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i class="ti ti-cash text-2xl text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-emerald-100">Nilai Penjualan</h3>
                                <p class="text-2xl font-bold text-white">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
                                <div class="flex items-center mt-1">
                                    @if ($perubahanNilai >= 0)
                                        <i class="ti ti-trending-up text-green-300 text-sm mr-1"></i>
                                        <span class="text-xs text-green-300">+{{ abs(round($perubahanNilai, 1)) }}%</span>
                                    @else
                                        <i class="ti ti-trending-down text-red-300 text-sm mr-1"></i>
                                        <span class="text-xs text-red-300">-{{ abs(round($perubahanNilai, 1)) }}%</span>
                                    @endif
                                    <span class="text-xs text-emerald-200 ml-1">growth</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Hari Ini Card -->
            <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-calendar-event text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-purple-100">Hari Ini</h3>
                            <p class="text-3xl font-bold text-white">{{ $penjualanHariIni }} <span class="text-sm font-normal text-purple-200">Trx</span></p>
                             <p class="text-xs text-purple-200 mt-1">
                                Rp {{ number_format($nilaiHariIni, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Pembayaran Card -->
            <div class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-chart-pie text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 w-full">
                            <h3 class="text-sm font-medium text-orange-100 mb-2">Status Hari Ini</h3>
                            <div class="flex items-center justify-between text-xs">
                                <div class="text-center">
                                    <div class="text-white font-bold text-lg">{{ $statusCountsHariIni['lunas'] }}</div>
                                    <div class="text-orange-200">Lunas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-white font-bold text-lg">{{ $statusCountsHariIni['dp'] + $statusCountsHariIni['angsuran'] }}</div>
                                    <div class="text-orange-200">Kredit</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-white font-bold text-lg">{{ $statusCountsHariIni['belum_bayar'] }}</div>
                                    <div class="text-orange-200">Unpaid</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
             <!-- Filters -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date Range -->
                    <div class="col-span-1 md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                        <div class="relative">
                            <input type="text" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                   placeholder="Pilih tanggal">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                     <div class="col-span-1 md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                         <div class="relative">
                            <input type="text" name="tanggal_sampai" id="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                                   class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                   placeholder="Pilih tanggal">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="col-span-1 md:col-span-2 flex items-end gap-2">
                         <div class="flex-1">
                             <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Cari Transaksi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="No Faktur / Nama Pelanggan...">
                            </div>
                        </div>
                        <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="ti ti-filter"></i>
                        </button>
                         <a href="{{ route('penjualan.index') }}" class="px-3 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-receipt text-blue-600"></i>
                                    <span>Faktur & Tanggal</span>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-user text-blue-600"></i>
                                    <span>Pelanggan</span>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-cash text-blue-600"></i>
                                    <span>Jenis</span>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-info-circle text-blue-600"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-currency-dollar text-blue-600"></i>
                                    <span>Total</span>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-settings text-blue-600"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($penjualan as $item)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <!-- Faktur & Tanggal -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-receipt text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors flex items-center gap-1.5">
                                                {{ $item->no_faktur }}
                                                @if ($item->kompensasi)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700" title="Dari kompensasi pembelian">
                                                        <i class="ti ti-link text-[10px] mr-0.5"></i>KOMP
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                                <i class="ti ti-calendar mr-1"></i>
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pelanggan -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                             <div class="text-sm font-medium text-gray-700">
                                                {{ $item->pelanggan->nama }}
                                            </div>
                                             <div class="text-[10px] text-gray-400 font-mono">
                                                {{ $item->pelanggan->kode_pelanggan }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jenis Transaksi -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($item->jenis_transaksi == 'tunai')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-500 to-green-600 text-white shadow-sm">
                                            <i class="ti ti-cash mr-1"></i> Tunai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-sm">
                                            <i class="ti ti-credit-card mr-1"></i> Kredit
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Pembayaran -->
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = match($item->status_pembayaran) {
                                            'lunas' => 'bg-gradient-to-r from-green-500 to-green-600 text-white',
                                            'dp' => 'bg-gradient-to-r from-blue-500 to-blue-600 text-white',
                                            'angsuran' => 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white',
                                            'belum_bayar' => 'bg-gradient-to-r from-red-500 to-red-600 text-white',
                                            default => 'bg-gradient-to-r from-gray-500 to-gray-600 text-white'
                                        };
                                        $statusLabel = match($item->status_pembayaran) {
                                            'lunas' => 'LUNAS',
                                            'dp' => 'DP',
                                            'angsuran' => 'CICILAN',
                                            'belum_bayar' => 'BELUM BAYAR',
                                            default => $item->status_pembayaran
                                        };
                                        $statusIcon = match($item->status_pembayaran) {
                                            'lunas' => 'ti-check',
                                            'dp' => 'ti-wallet',
                                            'angsuran' => 'ti-calendar-time',
                                            'belum_bayar' => 'ti-alert-circle',
                                            default => 'ti-help'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full {{ $statusClass }} shadow-sm">
                                        <i class="ti {{ $statusIcon }} mr-1"></i> {{ $statusLabel }}
                                    </span>
                                    @if($item->jatuh_tempo && $item->status_pembayaran != 'lunas')
                                        <div class="text-[10px] text-red-500 mt-1 flex items-center justify-center font-medium" title="Jatuh Tempo">
                                            <i class="ti ti-alert-triangle mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Total -->
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($item->total_setelah_diskon, 0, ',', '.') }}
                                    </div>
                                     @if($item->diskon > 0)
                                        <div class="text-[10px] text-red-500 line-through">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 whitespace-nowrap text-right pl-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('penjualan.show', $item->encrypted_id) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200"
                                           title="Lihat Detail">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->no_faktur }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200"
                                                title="Hapus Transaksi">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 border-t border-gray-100">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                            <i class="ti ti-receipt-off text-2xl"></i>
                                        </div>
                                        <p class="font-medium">Belum ada transaksi</p>
                                        <p class="text-sm text-gray-400 mt-1">Silakan tambah transaksi baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
             @if ($penjualan->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 bg-white">
                    {{ $penjualan->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom SweetAlert Styling */
        .swal2-popup {
            border-radius: 16px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-title {
            color: #1f2937 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }

        .swal2-content {
            color: #6b7280 !important;
            font-size: 1rem !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            font-size: 0.95rem !important;
        }

        .swal2-cancel {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            font-size: 0.95rem !important;
        }

        .swal2-actions {
            gap: 1rem !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flatpickr configuration
            const flatpickrConfig = {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,
                altInput: true,
                altFormat: "j F Y",
            };

            // Initialize Flatpickr
            flatpickr("#tanggal_dari", flatpickrConfig);
            flatpickr("#tanggal_sampai", flatpickrConfig);
        });

        function confirmDelete(saleId, invoiceNumber) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus transaksi "${invoiceNumber}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#ea580c',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`{{ route('penjualan.destroy', '') }}/${saleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Try to parse as JSON, if fails, use status text
                            return response.text().then(text => {
                                try {
                                    const err = JSON.parse(text);
                                    throw new Error(err.message || 'Terjadi kesalahan saat menghapus penjualan');
                                } catch (e) {
                                    throw new Error(text || response.statusText || 'Terjadi kesalahan saat menghapus penjualan');
                                }
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Penjualan berhasil dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            // Hanya tampilkan message-nya saja, bukan JSON
                            const errorMessage = data.message || 'Terjadi kesalahan saat menghapus penjualan';
                            throw new Error(errorMessage);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Terjadi kesalahan saat menghapus penjualan',
                            confirmButtonText: 'OK'
                        });
                        throw error;
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
    </script>
@endpush
