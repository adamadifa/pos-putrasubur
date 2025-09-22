@extends('layouts.pos')

@section('title', 'Mutasi Kas & Bank')
@section('page-title', 'Kelola Mutasi Kas & Bank')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Transaksi Kas & Bank</h2>
                <p class="text-sm text-gray-600">Kelola semua transaksi kas dan bank dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Export Excel
                </button>
                <a href="{{ route('transaksi-kas-bank.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Transaksi Baru
                </a>
            </div>
        </div>

        <!-- Success Alert -->
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
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Dismiss</span>
                                <i class="ti ti-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Alert -->
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
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                class="inline-flex bg-red-50 rounded-lg p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Dismiss</span>
                                <i class="ti ti-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total IN Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-arrow-down text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-emerald-100">Total IN</h3>
                            <p class="text-2xl font-bold text-white">Rp {{ number_format($totalDebet, 0, ',', '.') }}</p>
                            <p class="text-sm text-emerald-200 flex items-center mt-1">
                                <i class="ti ti-plus text-lg mr-1"></i>
                                Hari ini: {{ now()->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total OUT Card -->
            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-arrow-up text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Total OUT</h3>
                            <p class="text-2xl font-bold text-white">Rp {{ number_format($totalKredit, 0, ',', '.') }}</p>
                            <p class="text-sm text-red-200 flex items-center mt-1">
                                <i class="ti ti-minus text-lg mr-1"></i>
                                Hari ini: {{ now()->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form method="GET" action="{{ route('transaksi-kas-bank.index') }}"
                class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
                <!-- Date From -->
                <div class="lg:w-64">
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <div class="relative">
                        <input type="text" name="tanggal_mulai" id="tanggal_mulai" readonly
                            value="{{ request('tanggal_mulai') ? \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_mulai_hidden" id="tanggal_mulai_hidden"
                        value="{{ request('tanggal_mulai') }}">
                </div>

                <!-- Date To -->
                <div class="lg:w-64">
                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <div class="relative">
                        <input type="text" name="tanggal_akhir" id="tanggal_akhir" readonly
                            value="{{ request('tanggal_akhir') ? \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') : '' }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 cursor-pointer bg-white hover:bg-gray-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_akhir_hidden" id="tanggal_akhir_hidden"
                        value="{{ request('tanggal_akhir') }}">
                </div>

                <!-- Kas/Bank Filter -->
                <div class="lg:w-80">
                    <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Kas/Bank</label>
                    <select name="kas_bank_id" id="kas_bank_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Kas/Bank</option>
                        @foreach ($kasBankList as $kasBank)
                            <option value="{{ $kasBank->id }}"
                                {{ request('kasBank->id') == $kasBank->id ? 'selected' : '' }}>
                                {{ $kasBank->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Transaksi Filter -->
                <div class="lg:w-48">
                    <label for="kategori_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Kategori
                        Transaksi</label>
                    <select name="kategori_transaksi" id="kategori_transaksi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Kategori</option>
                        <option value="PJ" {{ request('kategori_transaksi') == 'PJ' ? 'selected' : '' }}>Penjualan
                        </option>
                        <option value="PB" {{ request('kategori_transaksi') == 'PB' ? 'selected' : '' }}>Pembelian
                        </option>
                        <option value="MN" {{ request('kategori_transaksi') == 'MN' ? 'selected' : '' }}>Manual
                        </option>
                        <option value="TF" {{ request('kategori_transaksi') == 'TF' ? 'selected' : '' }}>Transfer
                        </option>
                    </select>
                </div>

                <!-- Jenis Transaksi Filter -->
                <div class="lg:w-48">
                    <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                        Transaksi</label>
                    <select name="jenis_transaksi" id="jenis_transaksi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Jenis</option>
                        <option value="D" {{ request('jenis_transaksi') == 'D' ? 'selected' : '' }}>IN</option>
                        <option value="K" {{ request('jenis_transaksi') == 'K' ? 'selected' : '' }}>OUT</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i class="ti ti-filter text-lg mr-2"></i>
                        Filter
                    </button>
                    @if (request()->hasAny(['tanggal_mulai', 'tanggal_akhir', 'kas_bank_id', 'kategori_transaksi', 'jenis_transaksi']))
                        <a href="{{ route('transaksi-kas-bank.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Modern Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-hash text-blue-600"></i>
                                    <span>No</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-receipt text-blue-600"></i>
                                    <span>No Bukti & Tanggal</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-wallet text-blue-600"></i>
                                    <span>Kas/Bank</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-category text-blue-600"></i>
                                    <span>Kategori</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-arrows-exchange text-blue-600"></i>
                                    <span>Jenis</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-currency-dollar text-blue-600"></i>
                                    <span>Jumlah</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-message text-blue-600"></i>
                                    <span>Keterangan</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-settings text-blue-600"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transaksi as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                            <span
                                                class="text-white text-sm font-semibold">{{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-receipt text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->no_bukti }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-wallet text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->kasBank->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $item->kasBank->jenis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @php
                                        $kategoriConfig = [
                                            'PJ' => [
                                                'bg' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-shopping-cart',
                                                'label' => 'Penjualan',
                                            ],
                                            'PB' => [
                                                'bg' => 'bg-gradient-to-r from-orange-500 to-orange-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-truck',
                                                'label' => 'Pembelian',
                                            ],
                                            'MN' => [
                                                'bg' => 'bg-gradient-to-r from-purple-500 to-purple-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-edit',
                                                'label' => 'Manual',
                                            ],
                                            'TF' => [
                                                'bg' => 'bg-gradient-to-r from-indigo-500 to-indigo-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-arrows-exchange',
                                                'label' => 'Transfer',
                                            ],
                                        ];
                                        $config = $kategoriConfig[$item->kategori_transaksi] ?? $kategoriConfig['MN'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                        <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    @php
                                        $jenisConfig = [
                                            'D' => [
                                                'bg' => 'bg-gradient-to-r from-emerald-500 to-emerald-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-arrow-down',
                                                'label' => 'IN',
                                            ],
                                            'K' => [
                                                'bg' => 'bg-gradient-to-r from-red-500 to-red-600',
                                                'text' => 'text-white',
                                                'icon' => 'ti-arrow-up',
                                                'label' => 'OUT',
                                            ],
                                        ];
                                        $jenisTransaksiConfig =
                                            $jenisConfig[$item->jenis_transaksi] ?? $jenisConfig['D'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-xs font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }} shadow-sm">
                                        <i class="ti {{ $jenisTransaksiConfig['icon'] }} text-xs mr-1"></i>
                                        {{ $jenisTransaksiConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="text-lg font-bold text-gray-900">Rp
                                        {{ number_format($item->jumlah, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route('transaksi-kas-bank.show', $item->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200">
                                            <i class="ti ti-eye text-sm"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        @if ($item->referensi_tipe == 'MN')
                                            <a href="{{ route('transaksi-kas-bank.edit', $item->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200"
                                                title="Edit Transaksi">
                                                <i class="ti ti-edit text-sm"></i>
                                            </a>
                                        @else
                                            <button type="button" disabled
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                                title="Transaksi otomatis tidak dapat diedit">
                                                <i class="ti ti-edit text-sm"></i>
                                            </button>
                                        @endif

                                        <!-- Delete Button -->
                                        @if ($item->referensi_tipe == 'MN')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->id }}', '{{ $item->no_bukti }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-sm hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200"
                                                title="Hapus Transaksi">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        @else
                                            <button type="button" disabled
                                                class="inline-flex items-center justify-center w-8 h-8 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                                title="Transaksi otomatis tidak dapat dihapus">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="ti ti-wallet-off text-5xl mx-auto mb-4 text-gray-400"></i>
                                        <p class="text-lg font-medium">Tidak ada transaksi ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau buat transaksi baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($transaksi->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if ($transaksi->previousPageUrl())
                        <a href="{{ $transaksi->previousPageUrl() }}"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Sebelumnya
                        </a>
                    @else
                        <span
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                            Sebelumnya
                        </span>
                    @endif

                    @if ($transaksi->nextPageUrl())
                        <a href="{{ $transaksi->nextPageUrl() }}"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Selanjutnya
                        </a>
                    @else
                        <span
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Selanjutnya
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $transaksi->firstItem() ?? 0 }}</span> sampai
                            <span class="font-medium">{{ $transaksi->lastItem() ?? 0 }}</span> dari
                            <span class="font-medium">{{ $transaksi->total() }}</span> transaksi
                        </p>
                    </div>
                    <div>
                        {{ $transaksi->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* Custom Flatpickr Styling - Sesuai Tema Aplikasi */
        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
        }

        .flatpickr-months {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
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

        .flatpickr-monthDropdown-months {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month .numInputWrapper {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-weekdays {
            background: #f8fafc !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .flatpickr-weekday {
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .flatpickr-day {
            color: #374151 !important;
            border-radius: 8px !important;
            margin: 2px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-day:hover {
            background: #dbeafe !important;
            color: #1e40af !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.selected {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3) !important;
        }

        .flatpickr-day.selected:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.today {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3) !important;
        }

        .flatpickr-day.today:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #9ca3af !important;
            opacity: 0.6 !important;
        }

        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: #ffffff !important;
            fill: #ffffff !important;
            padding: 8px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            transform: scale(1.1) !important;
        }

        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 16px !important;
            height: 16px !important;
        }

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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
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
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize Flatpickr Date Pickers
        document.addEventListener('DOMContentLoaded', function() {
            // Date From Picker
            const dateFromPicker = flatpickr("#tanggal_mulai", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Update hidden input with ISO date format
                    document.getElementById('tanggal_mulai_hidden').value = selectedDates[0]
                        .toISOString().split('T')[0];

                    // Update visible input with formatted date
                    instance.input.value = dateStr;
                }
            });

            // Date To Picker
            const dateToPicker = flatpickr("#tanggal_akhir", {
                locale: "id",
                dateFormat: "d/m/Y",
                allowInput: false,
                clickOpens: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Update hidden input with ISO date format
                    document.getElementById('tanggal_akhir_hidden').value = selectedDates[0]
                        .toISOString().split('T')[0];

                    // Update visible input with formatted date
                    instance.input.value = dateStr;
                }
            });

            // Set min date for date_to based on date_from
            dateFromPicker.config.onChange.push(function(selectedDates) {
                if (selectedDates[0]) {
                    dateToPicker.set('minDate', selectedDates[0]);
                }
            });
        });

        function confirmDelete(transactionId, receiptNumber) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus transaksi "${receiptNumber}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('transaksi-kas-bank.destroy', '') }}/${transactionId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
