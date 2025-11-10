@extends('layouts.pos')

@section('title', 'Pinjaman')
@section('page-title', 'Kelola Pinjaman')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Pinjaman</h2>
                <p class="text-sm text-gray-600">Kelola semua pinjaman pelanggan dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('pinjaman.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Pinjaman Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-check-circle text-lg text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button"
                            class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="ti ti-x text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-alert-circle text-lg text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button"
                            class="inline-flex bg-red-50 rounded-lg p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="ti ti-x text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-file-invoice text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Total Pinjaman</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalPinjaman ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-calendar text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-green-100">Pinjaman Hari Ini</h3>
                            <p class="text-3xl font-bold text-white">{{ $pinjamanHariIni ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-currency-dollar text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-purple-100">Total Nilai Pinjaman</h3>
                            <p class="text-2xl font-bold text-white">Rp
                                {{ number_format($totalNilaiPinjaman ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-wallet text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Sisa Pinjaman</h3>
                            <p class="text-2xl font-bold text-white">Rp
                                {{ number_format($totalSisaPinjaman ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('pinjaman.index') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                        placeholder="No. Pinjaman atau Peminjam...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                    <div class="relative">
                        <input type="text" id="tanggal_dari" readonly
                            value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_dari" id="tanggal_dari_hidden"
                        value="{{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') : '' }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                    <div class="relative">
                        <input type="text" id="tanggal_sampai" readonly
                            value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_sampai" id="tanggal_sampai_hidden"
                        value="{{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') : '' }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar
                        </option>
                        <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex space-x-2">
                    <button type="submit"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="ti ti-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('pinjaman.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                                Pinjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Pinjaman</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dibayar</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sisa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pinjaman as $item)
                            @php
                                $totalDibayar = $item->pembayaranPinjaman->sum('jumlah_bayar');
                                $sisaPinjaman = $item->total_pinjaman - $totalDibayar;

                                $statusConfig = [
                                    'lunas' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Lunas'],
                                    'sebagian' => [
                                        'bg' => 'bg-yellow-100',
                                        'text' => 'text-yellow-800',
                                        'label' => 'Sebagian',
                                    ],
                                    'belum_bayar' => [
                                        'bg' => 'bg-red-100',
                                        'text' => 'text-red-800',
                                        'label' => 'Belum Bayar',
                                    ],
                                ];
                                $config = $statusConfig[$item->status_pembayaran] ?? $statusConfig['belum_bayar'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->no_pinjaman }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->peminjam->nama ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $item->peminjam->kode_peminjam ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->tanggal->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($item->total_pinjaman, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-green-600">Rp
                                        {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-red-600">Rp
                                        {{ number_format($sisaPinjaman, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('pinjaman.show', $item->encrypted_id) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3" title="Detail">
                                        <i class="ti ti-eye text-lg"></i>
                                    </a>
                                    <a href="{{ route('pinjaman.edit', $item->encrypted_id) }}"
                                        class="text-orange-600 hover:text-orange-900 mr-3" title="Edit">
                                        <i class="ti ti-edit text-lg"></i>
                                    </a>
                                    @if ($item->pembayaranPinjaman->count() == 0)
                                        <form action="{{ route('pinjaman.destroy', $item->encrypted_id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pinjaman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                title="Hapus">
                                                <i class="ti ti-trash text-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="ti ti-inbox text-4xl mb-2"></i>
                                        <p class="text-sm">Tidak ada data pinjaman</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($pinjaman->hasPages())
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    {{ $pinjaman->links() }}
                </div>
            @endif
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($pinjaman as $item)
                @php
                    $totalDibayar = $item->pembayaranPinjaman->sum('jumlah_bayar');
                    $sisaPinjaman = $item->total_pinjaman - $totalDibayar;

                    $statusConfig = [
                        'lunas' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Lunas'],
                        'sebagian' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Sebagian'],
                        'belum_bayar' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Belum Bayar'],
                    ];
                    $config = $statusConfig[$item->status_pembayaran] ?? $statusConfig['belum_bayar'];
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $item->no_pinjaman }}</h3>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $item->peminjam->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->peminjam->kode_peminjam ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="ti ti-calendar mr-1"></i>{{ $item->tanggal->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2 ml-2">
                            <a href="{{ route('pinjaman.show', $item->encrypted_id) }}"
                                class="text-blue-600 hover:text-blue-900 p-2" title="Detail">
                                <i class="ti ti-eye text-lg"></i>
                            </a>
                            <a href="{{ route('pinjaman.edit', $item->encrypted_id) }}"
                                class="text-orange-600 hover:text-orange-900 p-2" title="Edit">
                                <i class="ti ti-edit text-lg"></i>
                            </a>
                            @if ($item->pembayaranPinjaman->count() == 0)
                                <form action="{{ route('pinjaman.destroy', $item->encrypted_id) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pinjaman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-2" title="Hapus">
                                        <i class="ti ti-trash text-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Jumlah Pinjaman</span>
                            <span class="text-sm font-semibold text-gray-900">Rp
                                {{ number_format($item->total_pinjaman, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Dibayar</span>
                            <span class="text-sm font-medium text-green-600">Rp
                                {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-xs font-medium text-gray-700">Sisa</span>
                            <span class="text-sm font-bold text-red-600">Rp
                                {{ number_format($sisaPinjaman, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="text-gray-500">
                        <i class="ti ti-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Tidak ada data pinjaman</p>
                    </div>
                </div>
            @endforelse

            @if ($pinjaman->hasPages())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    {{ $pinjaman->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom Flatpickr Styling - Tema Orange untuk Pinjaman */
        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
        }

        .flatpickr-months {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 0 !important;
        }

        .flatpickr-month {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month {
            color: #ffffff !important;
        }

        .flatpickr-day.selected {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border: none !important;
        }

        .flatpickr-day.today {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        // Initialize Flatpickr Date Pickers
        document.addEventListener('DOMContentLoaded', function() {
            // Date From Picker
            const dateFromPicker = flatpickr("#tanggal_dari", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                @if(request('tanggal_dari'))
                defaultDate: "{{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') }}",
                @endif
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0]) {
                        // Format tanggal ke Y-m-d tanpa terpengaruh timezone
                        const date = selectedDates[0];
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        const isoDate = `${year}-${month}-${day}`;
                        
                        document.getElementById('tanggal_dari_hidden').value = isoDate;
                    }
                }
            });

            // Date To Picker
            const dateToPicker = flatpickr("#tanggal_sampai", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                @if(request('tanggal_sampai'))
                defaultDate: "{{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') }}",
                @endif
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0]) {
                        // Format tanggal ke Y-m-d tanpa terpengaruh timezone
                        const date = selectedDates[0];
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        const isoDate = `${year}-${month}-${day}`;
                        
                        document.getElementById('tanggal_sampai_hidden').value = isoDate;
                    }
                }
            });

            // Set min date for date_to based on date_from
            dateFromPicker.config.onChange.push(function(selectedDates) {
                if (selectedDates[0]) {
                    dateToPicker.set('minDate', selectedDates[0]);
                }
            });

            // Initialize hidden inputs with ISO format if defaultDate is set
            @if(request('tanggal_dari'))
            const tanggalDariValue = "{{ \Carbon\Carbon::parse(request('tanggal_dari'))->format('Y-m-d') }}";
            document.getElementById('tanggal_dari_hidden').value = tanggalDariValue;
            @endif

            @if(request('tanggal_sampai'))
            const tanggalSampaiValue = "{{ \Carbon\Carbon::parse(request('tanggal_sampai'))->format('Y-m-d') }}";
            document.getElementById('tanggal_sampai_hidden').value = tanggalSampaiValue;
            @endif
        });
    </script>
@endpush
