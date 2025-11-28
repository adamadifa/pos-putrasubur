@extends('layouts.pos')

@section('title', 'Uang Muka Supplier')
@section('page-title', 'Kelola Uang Muka Supplier')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Uang Muka Supplier</h2>
                <p class="text-sm text-gray-600">Kelola semua uang muka yang diberikan kepada supplier</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button type="button" id="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Uang Muka Baru
                </button>
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
                        <button type="button"
                            class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="ti ti-x text-lg"></i>
                        </button>
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
            <!-- Total Uang Muka Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                            <h3 class="text-sm font-medium text-blue-100">Total Uang Muka</h3>
                            <p class="text-2xl font-bold text-white">Rp
                                {{ number_format($totalUangMuka ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Digunakan Card -->
            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-shopping-cart text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Total Digunakan</h3>
                            <p class="text-2xl font-bold text-white">Rp
                                {{ number_format($totalDigunakan ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Sisa Card -->
            <div
                class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                            <h3 class="text-sm font-medium text-green-100">Total Sisa</h3>
                            <p class="text-2xl font-bold text-white">Rp {{ number_format($totalSisa ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Aktif Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-circle-check text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-emerald-100">Uang Muka Aktif</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalAktif ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('uang-muka-supplier.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                        placeholder="No. Uang Muka atau Supplier...">
                </div>

                <!-- Supplier Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Supplier</option>
                        @foreach ($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="ti ti-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('uang-muka-supplier.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($uangMuka as $um)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $um->no_uang_muka }}</h3>
                                @if ($um->status == 'aktif')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @elseif ($um->status == 'habis')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Habis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $um->supplier->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="ti ti-calendar mr-1"></i>{{ $um->tanggal->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2 ml-2">
                            <a href="{{ route('uang-muka-supplier.show', $um->encrypted_id) }}" class="text-blue-600 hover:text-blue-900 p-2"
                                title="Detail">
                                <i class="ti ti-eye text-lg"></i>
                            </a>
                            @if ($um->status == 'aktif' && (!$um->penggunaan_pembelian_sum_jumlah_digunakan || $um->penggunaan_pembelian_sum_jumlah_digunakan == 0))
                                <form action="{{ route('uang-muka-supplier.cancel', $um->encrypted_id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan uang muka ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-orange-600 hover:text-orange-900 p-2" title="Batalkan">
                                        <i class="ti ti-x text-lg"></i>
                                    </button>
                                </form>
                            @endif
                            @if (!$um->penggunaan_pembelian_sum_jumlah_digunakan || $um->penggunaan_pembelian_sum_jumlah_digunakan == 0)
                                <form action="{{ route('uang-muka-supplier.destroy', $um->encrypted_id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen uang muka ini? Data yang dihapus tidak dapat dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-2" title="Hapus">
                                        <i class="ti ti-trash text-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Jumlah</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($um->jumlah_uang_muka, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Digunakan</span>
                            <span class="text-sm font-medium text-red-600 text-right">Rp
                                {{ number_format($um->penggunaan_pembelian_sum_jumlah_digunakan ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-xs font-medium text-gray-700">Sisa</span>
                            <span class="text-sm font-bold text-green-600 text-right">Rp {{ number_format($um->sisa_uang_muka, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="text-gray-500">
                        <i class="ti ti-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Tidak ada data uang muka supplier</p>
                    </div>
                </div>
            @endforelse

            <!-- Pagination Mobile -->
            @if ($uangMuka->hasPages())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    {{ $uangMuka->links() }}
                </div>
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-orange-50 to-red-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-receipt text-orange-600"></i>
                                    <span>No. Uang Muka</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-building-store text-orange-600"></i>
                                    <span>Supplier</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-calendar text-orange-600"></i>
                                    <span>Tanggal</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-currency-dollar text-orange-600"></i>
                                    <span>Jumlah</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-arrow-down text-red-500"></i>
                                    <span>Digunakan</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end space-x-2">
                                    <i class="ti ti-arrow-up text-green-500"></i>
                                    <span>Sisa</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="ti ti-info-circle text-orange-600"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <i class="ti ti-settings text-orange-600"></i>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($uangMuka as $um)
                            <tr class="hover:bg-gradient-to-r hover:from-orange-50/50 hover:to-red-50/50 transition-all duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 rounded-full bg-orange-500 group-hover:bg-orange-600 transition-colors"></div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $um->no_uang_muka }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-orange-100 to-red-100 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-building-store text-orange-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $um->supplier->nama ?? '-' }}</div>
                                            @if($um->supplier && $um->supplier->kode_supplier)
                                                <div class="text-xs text-gray-500">{{ $um->supplier->kode_supplier }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <i class="ti ti-calendar text-gray-400 text-sm"></i>
                                        <div class="text-sm text-gray-700">{{ $um->tanggal->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($um->jumlah_uang_muka, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center space-x-1 px-2 py-1 rounded-md bg-red-50">
                                        <i class="ti ti-arrow-down text-red-500 text-xs"></i>
                                        <span class="text-sm font-medium text-red-700">Rp
                                            {{ number_format($um->penggunaan_pembelian_sum_jumlah_digunakan ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center space-x-1 px-2 py-1 rounded-md bg-green-50">
                                        <i class="ti ti-arrow-up text-green-500 text-xs"></i>
                                        <span class="text-sm font-semibold text-green-700">Rp
                                            {{ number_format($um->sisa_uang_muka, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($um->status == 'aktif')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @elseif ($um->status == 'habis')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200">
                                            <i class="ti ti-check text-xs mr-1.5"></i>
                                            Habis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200">
                                            <i class="ti ti-x text-xs mr-1.5"></i>
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('uang-muka-supplier.show', $um->encrypted_id) }}" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-all duration-200"
                                            title="Detail">
                                            <i class="ti ti-eye text-base"></i>
                                        </a>
                                        @if ($um->status == 'aktif' && (!$um->penggunaan_pembelian_sum_jumlah_digunakan || $um->penggunaan_pembelian_sum_jumlah_digunakan == 0))
                                            <form action="{{ route('uang-muka-supplier.cancel', $um->encrypted_id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan uang muka ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 hover:text-orange-700 transition-all duration-200"
                                                    title="Batalkan">
                                                    <i class="ti ti-x text-base"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if (!$um->penggunaan_pembelian_sum_jumlah_digunakan || $um->penggunaan_pembelian_sum_jumlah_digunakan == 0)
                                            <form action="{{ route('uang-muka-supplier.destroy', $um->encrypted_id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen uang muka ini? Data yang dihapus tidak dapat dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all duration-200"
                                                    title="Hapus">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-inbox text-3xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Tidak ada data uang muka supplier</p>
                                        <p class="text-xs text-gray-400 mt-1">Klik tombol "Uang Muka Baru" untuk menambahkan data</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($uangMuka->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $uangMuka->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-xl bg-white max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="ti ti-currency-dollar text-xl text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Tambah Uang Muka Supplier</h3>
                        <p class="text-sm text-gray-500 mt-1">Input uang muka yang diberikan kepada supplier</p>
                    </div>
                </div>
                <button type="button" id="closeCreateModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto px-1">
                <div id="createModalContent">
                    <!-- Form will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .date-input-wrapper {
                position: relative;
            }

            .date-input-wrapper .ti-calendar {
                position: absolute;
                left: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                color: #6b7280;
                font-size: 18px;
                pointer-events: none;
                z-index: 10;
            }

            .date-input-wrapper .flatpickr-input {
                padding-left: 2.5rem !important;
            }

            /* Error message styling - remove browser default tooltip */
            .date-input-wrapper .flatpickr-input:invalid,
            select:invalid,
            input:invalid {
                box-shadow: none;
            }

            /* Ensure error messages are displayed properly */
            .text-red-600 {
                display: flex;
                align-items: center;
                margin-top: 0.25rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const createModal = document.getElementById('createModal');
            const createModalContent = document.getElementById('createModalContent');
            const openCreateModalBtn = document.getElementById('openCreateModal');
            const closeCreateModalBtn = document.getElementById('closeCreateModal');

            // Open modal
            openCreateModalBtn.addEventListener('click', function() {
                createModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Show loading
                createModalContent.innerHTML = `
                    <div class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <i class="ti ti-loader-2 animate-spin text-4xl text-orange-600 mb-4"></i>
                            <p class="text-gray-600">Memuat form...</p>
                        </div>
                    </div>
                `;

                // Load form via AJAX (using partial view)
                fetch('{{ route('uang-muka-supplier.create') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Directly use the HTML response (it's already just the form)
                        createModalContent.innerHTML = html;

                        // Add cancel button handler
                        const cancelBtn = createModalContent.querySelector('#cancelFormBtn');
                        if (cancelBtn) {
                            cancelBtn.addEventListener('click', function() {
                                createModal.classList.add('hidden');
                                document.body.style.overflow = 'auto';
                                createModalContent.innerHTML = '';
                            });
                        }

                        // Reinitialize scripts (flatpickr, number formatting, payment method filtering, etc)
                        setTimeout(() => {
                            initModalScripts();
                        }, 100);
                    })
                    .catch(error => {
                        console.error('Error loading form:', error);
                        createModalContent.innerHTML = `
                            <div class="text-center py-12">
                                <i class="ti ti-alert-circle text-4xl text-red-500 mb-4"></i>
                                <p class="text-red-600">Terjadi kesalahan saat memuat form</p>
                                <p class="text-sm text-gray-500 mt-2">${error.message}</p>
                            </div>
                        `;

                        // Re-enable body scroll on error
                        document.body.style.overflow = 'auto';
                    });
            });

            // Close modal
            closeCreateModalBtn.addEventListener('click', function() {
                createModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                createModalContent.innerHTML = '';
            });

            // Don't close modal when clicking outside - only close via close button

            // Initialize scripts for form in modal
            function initModalScripts() {
                // Reinitialize flatpickr if exists
                if (typeof flatpickr !== 'undefined' && document.querySelector('#tanggal')) {
                    flatpickr("#tanggal", {
                        dateFormat: "d/m/Y",
                        defaultDate: new Date(),
                        onChange: function(selectedDates, dateStr, instance) {
                            const date = selectedDates[0];
                            if (date) {
                                const formattedDate = date.getFullYear() + '-' +
                                    String(date.getMonth() + 1).padStart(2, '0') + '-' +
                                    String(date.getDate()).padStart(2, '0');
                                const hiddenInput = document.getElementById('tanggal_hidden');
                                if (hiddenInput) {
                                    hiddenInput.value = formattedDate;
                                }
                            }
                        }
                    });
                }

                // Reinitialize number formatting
                const jumlahInput = document.getElementById('jumlah_uang_muka');
                if (jumlahInput) {
                    setupNumberInput(jumlahInput);
                }

                // Initialize payment method and kas/bank filtering
                initializePaymentMethodFilter();

                // Handle form submission - only attach once to prevent multiple listeners
                const form = createModalContent.querySelector('form');
                if (form && !form.hasAttribute('data-submit-handled')) {
                    form.setAttribute('data-submit-handled', 'true');

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Prevent multiple submissions
                        if (form.hasAttribute('data-submitting')) {
                            return false;
                        }

                        // Client-side validation
                        let isValid = true;
                        const errors = [];

                        // Check supplier
                        const supplierSelect = form.querySelector('#supplier_id');
                        if (!supplierSelect || !supplierSelect.value) {
                            isValid = false;
                            errors.push('Pilih supplier terlebih dahulu.');
                            if (supplierSelect) {
                                supplierSelect.classList.add('border-red-500');
                                supplierSelect.classList.remove('border-gray-300');
                            }
                        }

                        // Check tanggal
                        const tanggalHidden = form.querySelector('#tanggal_hidden');
                        if (!tanggalHidden || !tanggalHidden.value) {
                            isValid = false;
                            errors.push('Pilih tanggal terlebih dahulu.');
                            const tanggalInput = form.querySelector('#tanggal');
                            if (tanggalInput) {
                                tanggalInput.classList.add('border-red-500');
                                tanggalInput.classList.remove('border-gray-300');
                            }
                        }

                        // Check jumlah uang muka
                        const jumlahInput = form.querySelector('#jumlah_uang_muka');
                        if (!jumlahInput || !jumlahInput.value) {
                            isValid = false;
                            errors.push('Masukkan jumlah uang muka.');
                            if (jumlahInput) {
                                jumlahInput.classList.add('border-red-500');
                                jumlahInput.classList.remove('border-gray-300');
                            }
                        } else {
                            const numericValue = jumlahInput.value.replace(/[^\d]/g, '');
                            if (!numericValue || parseInt(numericValue) < 1) {
                                isValid = false;
                                errors.push('Jumlah uang muka minimal 1.');
                                jumlahInput.classList.add('border-red-500');
                                jumlahInput.classList.remove('border-gray-300');
                            }
                        }

                        // Check metode pembayaran
                        const metodeRadio = form.querySelector('.payment-method-radio:checked');
                        const metodeHidden = form.querySelector('#metode_pembayaran_hidden');
                        if (!metodeRadio || !metodeRadio.value) {
                            isValid = false;
                            errors.push('Pilih metode pembayaran terlebih dahulu.');
                            // Add border red to container
                            const paymentContainer = form.querySelector('#paymentMethodContainer');
                            if (paymentContainer) {
                                paymentContainer.classList.add('border-red-500', 'border-2', 'rounded-lg', 'p-2');
                            }
                        } else {
                            // Update hidden input
                            if (metodeHidden) {
                                metodeHidden.value = metodeRadio.value;
                            }
                        }

                        // Check kas/bank
                        const kasBankRadio = form.querySelector('.kas-bank-radio:checked');
                        const kasBankHidden = form.querySelector('#kas_bank_id_hidden');
                        if (!kasBankRadio || !kasBankRadio.value) {
                            isValid = false;
                            errors.push('Pilih kas/bank terlebih dahulu.');
                            // Add border red to container
                            const kasBankContainer = form.querySelector('#kasBankContainer');
                            if (kasBankContainer && kasBankContainer.style.display !== 'none') {
                                kasBankContainer.classList.add('border-red-500', 'border-2', 'rounded-lg', 'p-2');
                            }
                        } else {
                            // Update hidden input
                            if (kasBankHidden) {
                                kasBankHidden.value = kasBankRadio.value;
                            }
                        }

                        // If validation fails, show errors and return
                        if (!isValid) {
                            // Clear previous error messages
                            form.querySelectorAll('.error-message').forEach(el => el.remove());

                            // Add error messages below each field
                            if (!supplierSelect || !supplierSelect.value) {
                                const supplierContainer = supplierSelect?.closest('div');
                                if (supplierContainer && !supplierContainer.querySelector('.error-message')) {
                                    const errorMsg = document.createElement('p');
                                    errorMsg.className = 'mt-1 text-sm text-red-600 flex items-center error-message';
                                    errorMsg.innerHTML =
                                        '<i class="ti ti-alert-circle mr-1"></i> Pilih supplier terlebih dahulu.';
                                    supplierContainer.appendChild(errorMsg);
                                }
                            }

                            if (!tanggalHidden || !tanggalHidden.value) {
                                const tanggalContainer = tanggalInput?.closest('div');
                                if (tanggalContainer && !tanggalContainer.querySelector('.error-message')) {
                                    const errorMsg = document.createElement('p');
                                    errorMsg.className = 'mt-1 text-sm text-red-600 flex items-center error-message';
                                    errorMsg.innerHTML =
                                        '<i class="ti ti-alert-circle mr-1"></i> Pilih tanggal terlebih dahulu.';
                                    tanggalContainer.appendChild(errorMsg);
                                }
                            }

                            if (!jumlahInput || !jumlahInput.value) {
                                const jumlahContainer = jumlahInput?.closest('div');
                                if (jumlahContainer && !jumlahContainer.querySelector('.error-message')) {
                                    const errorMsg = document.createElement('p');
                                    errorMsg.className = 'mt-1 text-sm text-red-600 flex items-center error-message';
                                    errorMsg.innerHTML =
                                        '<i class="ti ti-alert-circle mr-1"></i> Masukkan jumlah uang muka.';
                                    jumlahContainer.appendChild(errorMsg);
                                }
                            } else if (jumlahInput && jumlahInput.value) {
                                const numericValue = jumlahInput.value.replace(/[^\d]/g, '');
                                if (!numericValue || parseInt(numericValue) < 1) {
                                    const jumlahContainer = jumlahInput.closest('div');
                                    if (jumlahContainer && !jumlahContainer.querySelector('.error-message')) {
                                        const errorMsg = document.createElement('p');
                                        errorMsg.className =
                                            'mt-1 text-sm text-red-600 flex items-center error-message';
                                        errorMsg.innerHTML =
                                            '<i class="ti ti-alert-circle mr-1"></i> Jumlah uang muka minimal 1.';
                                        jumlahContainer.appendChild(errorMsg);
                                    }
                                }
                            }

                            const metodeRadio = form.querySelector('.payment-method-radio:checked');
                            if (!metodeRadio || !metodeRadio.value) {
                                const metodeContainer = form.querySelector('#paymentMethodContainer');
                                const metodeParent = metodeContainer?.parentElement;
                                if (metodeParent && !metodeParent.querySelector('.error-message')) {
                                    const errorMsg = document.createElement('p');
                                    errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center error-message';
                                    errorMsg.innerHTML =
                                        '<i class="ti ti-alert-circle mr-1"></i> Pilih metode pembayaran terlebih dahulu.';
                                    metodeParent.appendChild(errorMsg);
                                }
                            }

                            const kasBankRadio = form.querySelector('.kas-bank-radio:checked');
                            if (!kasBankRadio || !kasBankRadio.value) {
                                const kasBankContainerEl = form.querySelector('#kasBankContainer');
                                const kasBankParent = kasBankContainerEl?.parentElement || form.querySelector(
                                    '#kasBankMessage')?.parentElement;
                                if (kasBankParent && !kasBankParent.querySelector('.error-message')) {
                                    const errorMsg = document.createElement('p');
                                    errorMsg.className = 'mt-2 text-sm text-red-600 flex items-center error-message';
                                    errorMsg.innerHTML =
                                        '<i class="ti ti-alert-circle mr-1"></i> Pilih kas/bank terlebih dahulu.';
                                    kasBankParent.appendChild(errorMsg);
                                }
                            }

                            // Scroll to first error
                            const firstError = form.querySelector('.error-message');
                            if (firstError) {
                                firstError.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'nearest'
                                });
                            }

                            return false;
                        }

                        // Clear error borders and messages if validation passes
                        form.querySelectorAll('.border-red-500').forEach(el => {
                            el.classList.remove('border-red-500');
                            el.classList.add('border-gray-300');
                        });
                        form.querySelectorAll('.error-message').forEach(el => el.remove());

                        form.setAttribute('data-submitting', 'true');

                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<i class="ti ti-loader-2 animate-spin text-lg mr-2"></i> Menyimpan...';
                        }

                        const formData = new FormData(form);

                        // Clean jumlah_uang_muka
                        if (jumlahInput && jumlahInput.value) {
                            const numericValue = jumlahInput.value.replace(/[^\d]/g, '');
                            if (numericValue) {
                                formData.set('jumlah_uang_muka', numericValue);
                            }
                        }

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'text/html'
                                }
                            })
                            .then(response => {
                                form.removeAttribute('data-submitting');

                                if (response.redirected || response.ok) {
                                    // Success - reload page to show success message
                                    window.location.href = response.url ||
                                        '{{ route('uang-muka-supplier.index') }}';
                                } else {
                                    return response.text().then(html => {
                                        // Parse error response
                                        const parser = new DOMParser();
                                        const doc = parser.parseFromString(html, 'text/html');

                                        // Check if there are validation errors
                                        const errorElements = doc.querySelectorAll(
                                            '.text-red-600, .alert-danger, ul.error-list');
                                        const hasErrors = errorElements.length > 0 ||
                                            html.includes('Pilih') ||
                                            html.includes('Masukkan') ||
                                            html.includes('tidak valid') ||
                                            html.includes('minimal') ||
                                            html.includes('maksimal');

                                        if (hasErrors) {
                                            // Extract only the form content from response
                                            const formFromResponse = doc.querySelector('form');
                                            if (formFromResponse) {
                                                createModalContent.innerHTML = formFromResponse.outerHTML;
                                            } else {
                                                createModalContent.innerHTML = html;
                                            }

                                            // Re-initialize scripts (flag will be checked inside initModalScripts)
                                            setTimeout(() => {
                                                // Remove any existing flags before reinit
                                                const newForm = createModalContent.querySelector(
                                                    'form');
                                                if (newForm) {
                                                    newForm.removeAttribute('data-submit-handled');
                                                    newForm.removeAttribute('data-submitting');
                                                }
                                                initModalScripts();
                                            }, 100);
                                        } else {
                                            // Unknown error
                                            alert('Terjadi kesalahan saat menyimpan data');
                                        }

                                        // Re-enable submit button
                                        if (submitBtn) {
                                            submitBtn.disabled = false;
                                            submitBtn.innerHTML = originalBtnHtml;
                                        }
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                form.removeAttribute('data-submitting');
                                alert('Terjadi kesalahan saat menyimpan data');

                                // Re-enable submit button
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalBtnHtml;
                                }
                            });

                        return false;
                    });
                }
            }

            // Initialize payment method and kas/bank filtering
            function initializePaymentMethodFilter() {
                const paymentMethodRadios = createModalContent.querySelectorAll('.payment-method-radio');
                const kasBankRadios = createModalContent.querySelectorAll('.kas-bank-radio');
                const kasBankCards = createModalContent.querySelectorAll('.kas-bank-card');
                const kasBankContainer = createModalContent.querySelector('#kasBankContainer');
                const kasBankMessage = createModalContent.querySelector('#kasBankMessage');

                if (!paymentMethodRadios.length || !kasBankRadios.length) return;

                // Update payment method cards
                function updatePaymentMethodCards() {
                    const paymentMethodCards = createModalContent.querySelectorAll('.payment-method-card');
                    paymentMethodCards.forEach(card => {
                        card.classList.remove('border-orange-500', 'bg-orange-50');
                        card.classList.add('border-gray-200');
                    });

                    const selectedRadio = createModalContent.querySelector('.payment-method-radio:checked');
                    if (selectedRadio) {
                        const selectedCard = selectedRadio.parentElement.querySelector('.payment-method-card');
                        if (selectedCard) {
                            selectedCard.classList.remove('border-gray-200');
                            selectedCard.classList.add('border-orange-500', 'bg-orange-50');
                        }
                        // Update hidden input
                        const metodeHidden = createModalContent.querySelector('#metode_pembayaran_hidden');
                        if (metodeHidden) {
                            metodeHidden.value = selectedRadio.value;
                        }
                    }
                }

                // Update kas/bank cards
                function updateKasBankCards() {
                    kasBankCards.forEach(card => {
                        card.classList.remove('border-orange-500', 'bg-orange-50');
                        card.classList.add('border-gray-200');
                        const checkIcon = card.querySelector('.kas-bank-check');
                        if (checkIcon) {
                            checkIcon.classList.remove('bg-orange-500', 'border-orange-500');
                            checkIcon.classList.add('border-gray-300');
                            const icon = checkIcon.querySelector('i');
                            if (icon) icon.classList.add('opacity-0');
                        }
                    });

                    const selectedRadio = createModalContent.querySelector('.kas-bank-radio:checked');
                    if (selectedRadio) {
                        const selectedCard = selectedRadio.parentElement.querySelector('.kas-bank-card');
                        if (selectedCard) {
                            selectedCard.classList.remove('border-gray-200');
                            selectedCard.classList.add('border-orange-500', 'bg-orange-50');
                            const checkIcon = selectedCard.querySelector('.kas-bank-check');
                            if (checkIcon) {
                                checkIcon.classList.remove('border-gray-300');
                                checkIcon.classList.add('bg-orange-500', 'border-orange-500');
                                const icon = checkIcon.querySelector('i');
                                if (icon) icon.classList.remove('opacity-0');
                            }
                        }
                        // Update hidden input
                        const kasBankHidden = createModalContent.querySelector('#kas_bank_id_hidden');
                        if (kasBankHidden) {
                            kasBankHidden.value = selectedRadio.value;
                        }
                    }
                }

                // Filter kas/bank by payment method
                function filterKasBankByPaymentMethod() {
                    const selectedPaymentMethod = createModalContent.querySelector('.payment-method-radio:checked');

                    if (!selectedPaymentMethod) {
                        // Hide all kas/bank and show message
                        kasBankCards.forEach(card => card.style.display = 'none');
                        if (kasBankContainer) {
                            kasBankContainer.style.display = 'none';
                        }
                        if (kasBankMessage) {
                            kasBankMessage.style.display = 'flex';
                        }
                        return;
                    }

                    // Hide message and show container
                    if (kasBankMessage) {
                        kasBankMessage.style.display = 'none';
                    }
                    if (kasBankContainer) {
                        kasBankContainer.style.display = 'flex';
                    }

                    const paymentMethodCode = selectedPaymentMethod.value.toLowerCase();
                    const isTransfer = paymentMethodCode.includes('transfer') ||
                        paymentMethodCode.includes('bank') ||
                        paymentMethodCode.includes('bca') ||
                        paymentMethodCode.includes('mandiri') ||
                        paymentMethodCode.includes('bni') ||
                        paymentMethodCode.includes('bri');
                    const isCash = paymentMethodCode.includes('cash') ||
                        paymentMethodCode.includes('tunai') ||
                        paymentMethodCode.includes('kas');

                    kasBankCards.forEach((card, index) => {
                        const radio = kasBankRadios[index];
                        const kasBankJenis = radio.getAttribute('data-jenis');

                        if (isTransfer && kasBankJenis === 'BANK') {
                            card.style.display = 'flex';
                        } else if (isCash && kasBankJenis === 'KAS') {
                            card.style.display = 'flex';
                        } else if (!isTransfer && !isCash) {
                            // For QRIS or other methods, show all
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                            // Uncheck hidden selections
                            if (radio.checked) {
                                radio.checked = false;
                            }
                        }

                        // Update parent label display
                        const label = card.closest('.kas-bank-option');
                        if (label) {
                            label.style.display = card.style.display === 'flex' ? 'block' : 'none';
                        }
                    });

                    updateKasBankCards();
                }


                // Payment method change handler
                paymentMethodRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        updatePaymentMethodCards();
                        filterKasBankByPaymentMethod();
                    });
                });

                // Kas/bank change handler
                kasBankRadios.forEach(radio => {
                    radio.addEventListener('change', updateKasBankCards);
                });

                // Click handler for kas/bank cards
                kasBankCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const radio = this.closest('.kas-bank-option').querySelector('.kas-bank-radio');
                        radio.checked = true;
                        updateKasBankCards();
                    });
                });

                // Initialize on load
                const selectedPaymentMethod = createModalContent.querySelector('.payment-method-radio:checked');
                if (selectedPaymentMethod) {
                    updatePaymentMethodCards();
                    filterKasBankByPaymentMethod();
                } else {
                    updatePaymentMethodCards();
                }
                updateKasBankCards();
            }

            // Setup number formatting function
            function setupNumberInput(input) {
                if (!input) return;

                input.addEventListener('input', function(e) {
                    let cursorPos = e.target.selectionStart;
                    let oldValue = e.target.value;
                    let cleanValue = oldValue.replace(/[^\d]/g, '');

                    if (cleanValue) {
                        let formattedValue = new Intl.NumberFormat('id-ID').format(parseInt(cleanValue));
                        e.target.value = formattedValue;
                        let dotsBeforeCursor = (oldValue.substring(0, cursorPos).match(/\./g) || []).length;
                        let dotsAfterFormat = (formattedValue.match(/\./g) || []).length;
                        let newCursorPos = cursorPos + (dotsAfterFormat - dotsBeforeCursor);
                        e.target.setSelectionRange(Math.min(newCursorPos, formattedValue.length), Math.min(newCursorPos,
                            formattedValue.length));
                    } else {
                        e.target.value = '';
                    }
                });
            }
        </script>
    @endpush
@endsection
