@extends('layouts.pos')

@section('title', 'Penyesuaian Stok')
@section('page-title', 'Kelola Penyesuaian Stok')

@section('content')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Penyesuaian Stok</h2>
                <p class="text-sm text-gray-600">Kelola penyesuaian stok produk dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('penyesuaian-stok.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Penyesuaian Stok
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


        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('penyesuaian-stok.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Kode, produk, atau user...">
                        </div>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                            <input type="text" name="tanggal_dari" id="tanggal_dari"
                                value="{{ request('tanggal_dari') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr-input"
                                placeholder="Pilih tanggal dari...">
                        </div>
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                            Sampai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-calendar text-gray-400"></i>
                            </div>
                            <input type="text" name="tanggal_sampai" id="tanggal_sampai"
                                value="{{ request('tanggal_sampai') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr-input"
                                placeholder="Pilih tanggal sampai...">
                        </div>
                    </div>

                    <!-- Jenis Penyesuaian -->
                    <div>
                        <label for="jenis_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                            Penyesuaian</label>
                        <select name="jenis_penyesuaian" id="jenis_penyesuaian"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua</option>
                            <option value="penambahan" {{ request('jenis_penyesuaian') == 'penambahan' ? 'selected' : '' }}>
                                Penambahan (+)</option>
                            <option value="pengurangan"
                                {{ request('jenis_penyesuaian') == 'pengurangan' ? 'selected' : '' }}>Pengurangan (-)
                            </option>
                            <option value="netral" {{ request('jenis_penyesuaian') == 'netral' ? 'selected' : '' }}>Netral
                                (0)</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="ti ti-search text-lg mr-2"></i>
                            Filter
                        </button>
                    </div>

                    <!-- Reset Button -->
                    <div>
                        <a href="{{ route('penyesuaian-stok.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="ti ti-refresh text-lg mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Penyesuaian Stok Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Riwayat Penyesuaian Stok</h3>
                <p class="text-sm text-gray-500">Daftar semua penyesuaian stok yang telah dilakukan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode Penyesuaian
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok Sebelum
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Penyesuaian
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok Sesudah
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($penyesuaianStok as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-adjustments text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->kode_penyesuaian }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->tanggal_penyesuaian->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 rounded-lg border border-gray-200 bg-white flex items-center justify-center p-1 mr-3">
                                            @if ($item->produk->foto)
                                                <img class="max-h-full max-w-full object-contain"
                                                    src="{{ asset('storage/' . $item->produk->foto) }}"
                                                    alt="{{ $item->produk->nama_produk }}">
                                            @else
                                                <div
                                                    class="h-8 w-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                                    <span
                                                        class="text-white font-medium text-sm">{{ substr($item->produk->nama_produk, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->produk->nama_produk }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->produk->satuan->nama }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($item->stok_sebelum, 2, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($item->jumlah_penyesuaian > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="ti ti-trending-up mr-1"></i>
                                            +{{ number_format($item->jumlah_penyesuaian, 2, ',', '.') }}
                                        </span>
                                    @elseif($item->jumlah_penyesuaian < 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="ti ti-trending-down mr-1"></i>
                                            {{ number_format($item->jumlah_penyesuaian, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="ti ti-minus mr-1"></i>
                                            {{ number_format($item->jumlah_penyesuaian, 2, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($item->stok_sesudah, 2, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center mr-3">
                                            <span
                                                class="text-white font-medium text-xs">{{ substr($item->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="text-sm text-gray-900">{{ $item->user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('penyesuaian-stok.show', $item) }}"
                                            class="inline-flex items-center p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                            title="Lihat Detail">
                                            <i class="ti ti-eye text-lg"></i>
                                        </a>
                                        <a href="{{ route('penyesuaian-stok.edit', $item) }}"
                                            class="inline-flex items-center p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                            title="Edit">
                                            <i class="ti ti-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('penyesuaian-stok.destroy', $item) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                                title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus penyesuaian stok ini? Stok akan dikembalikan ke kondisi sebelumnya.')">
                                                <i class="ti ti-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-adjustments text-2xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada penyesuaian stok</h3>
                                        <p class="text-sm text-gray-500 mb-4">Belum ada penyesuaian stok yang dilakukan</p>
                                        <a href="{{ route('penyesuaian-stok.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <i class="ti ti-plus text-lg mr-2"></i>
                                            Tambah Penyesuaian Stok
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($penyesuaianStok->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $penyesuaianStok->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for date inputs
            flatpickr("#tanggal_dari", {
                dateFormat: "Y-m-d",
                locale: "id",
                placeholder: "Pilih tanggal dari...",
                allowInput: true,
                clickOpens: true,
                theme: "light"
            });

            flatpickr("#tanggal_sampai", {
                dateFormat: "Y-m-d",
                locale: "id",
                placeholder: "Pilih tanggal sampai...",
                allowInput: true,
                clickOpens: true,
                theme: "light"
            });

            // Toast notification functions
            function showToast(message, type = 'error') {
                const toastContainer = document.getElementById('toast-container');
                const toastId = 'toast-' + Date.now();

                const bgColor = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                const icon = type === 'error' ? 'ti-alert-circle' : 'ti-check-circle';

                const toast = document.createElement('div');
                toast.id = toastId;
                toast.className =
                    `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
                toast.innerHTML = `
                    <i class="ti ${icon} text-lg"></i>
                    <span class="flex-1">${message}</span>
                    <button onclick="hideToast('${toastId}')" class="text-white hover:text-gray-200 transition-colors">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                `;

                toastContainer.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Auto hide after 5 seconds
                setTimeout(() => {
                    hideToast(toastId);
                }, 5000);
            }

            function hideToast(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }

            // Show flash messages as toast
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            // Make functions global
            window.hideToast = hideToast;
        });
    </script>
@endsection
