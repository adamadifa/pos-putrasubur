@extends('layouts.pos')

@section('title', 'Supplier')
@section('page-title', 'Kelola Supplier')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Supplier</h2>
                <p class="text-sm text-gray-600">Kelola semua supplier dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Import Excel
                </button>
                <a href="{{ route('supplier.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Supplier
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Supplier Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-store text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Total Supplier</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalSupplier ?? 0 }}</p>
                            <p class="text-sm text-blue-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-lg mr-1"></i>
                                Semua supplier
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Supplier Aktif Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-check text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-emerald-100">Supplier Aktif</h3>
                            <p class="text-3xl font-bold text-white">{{ $supplierAktif ?? 0 }}</p>
                            <p class="text-sm text-emerald-200 flex items-center mt-1">
                                <i class="ti ti-trending-up text-lg mr-1"></i>
                                {{ $totalSupplier > 0 ? round((($supplierAktif ?? 0) / $totalSupplier) * 100) : 0 }}%
                                dari total
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Supplier Nonaktif Card -->
            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-x text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Supplier Nonaktif</h3>
                            <p class="text-3xl font-bold text-white">{{ $supplierNonaktif ?? 0 }}</p>
                            <p class="text-sm text-red-200 flex items-center mt-1">
                                <i class="ti ti-trending-down text-lg mr-1"></i>
                                {{ $totalSupplier > 0 ? round((($supplierNonaktif ?? 0) / $totalSupplier) * 100) : 0 }}%
                                dari total
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary-50 rounded-lg">
                            <i class="ti ti-building-store text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Supplier</h3>
                            <p class="text-sm text-gray-500">Menampilkan {{ $suppliers->count() ?? 0 }} dari
                                {{ $suppliers->total() ?? 0 }} supplier</p>
                        </div>
                    </div>

                    <!-- Table Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- View Options -->
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button class="px-3 py-1.5 bg-white text-gray-900 rounded-md shadow-sm text-sm font-medium">
                                <i class="ti ti-table text-lg inline mr-1"></i>
                                Tabel
                            </button>
                            <button
                                class="px-3 py-1.5 text-gray-600 rounded-md text-sm font-medium hover:bg-white hover:text-gray-900 transition-all">
                                <i class="ti ti-layout-grid text-lg inline mr-1"></i>
                                Grid
                            </button>
                        </div>

                        <!-- Export & Print -->
                        <div class="flex items-center space-x-2">
                            <button
                                class="p-2.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all"
                                title="Export Excel">
                                <i class="ti ti-download text-lg"></i>
                            </button>
                            <button
                                class="p-2.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                title="Print">
                                <i class="ti ti-printer text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" action="{{ route('supplier.index') }}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Main Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama supplier, kode supplier, atau nomor telepon..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>
                        </div>

                        <!-- Quick Filters -->
                        <div class="flex items-center gap-3">
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button type="button" onclick="setQuickFilter('')"
                                    class="px-3 py-1.5 text-sm font-medium {{ !request('status') ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Semua</button>
                                <button type="button" onclick="setQuickFilter('aktif')"
                                    class="px-3 py-1.5 text-sm font-medium {{ request('status') == 'aktif' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Aktif</button>
                                <button type="button" onclick="setQuickFilter('nonaktif')"
                                    class="px-3 py-1.5 text-sm font-medium {{ request('status') == 'nonaktif' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Nonaktif</button>
                            </div>

                            <button type="submit"
                                class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="ti ti-search text-lg inline mr-1.5"></i>
                                Cari
                            </button>
                        </div>
                    </div>

                    <!-- Hidden input for status filter -->
                    <input type="hidden" name="status" id="status-filter" value="{{ request('status') }}">
                </form>
            </div>

            <div>
                <table class="w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <tr>
                            <th class="px-3 py-4 text-center w-12">
                                <input type="checkbox"
                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 focus:ring-offset-0">
                            </th>
                            <th class="px-2 py-4 text-center w-16">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">No.</span>
                            </th>
                            <th class="px-3 py-4 text-left w-64">
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Supplier</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-3 py-4 text-left w-48">
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-3 py-4 text-left w-48">
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat</span>
                                    <i
                                        class="ti ti-arrows-sort text-lg text-gray-400 cursor-pointer hover:text-primary-600 transition-colors"></i>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-24">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-3 py-4 text-center w-32">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($suppliers ?? [] as $index => $item)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/30 transition-all duration-200 group">
                                <td class="px-3 py-5 whitespace-nowrap w-12">
                                    <input type="checkbox" value="{{ $item->id }}"
                                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 focus:ring-2">
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap w-16">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full">
                                        <span class="text-xs font-bold text-primary-700">
                                            {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $index + 1 }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap w-64">
                                    <div class="flex items-center">
                                        <div
                                            class="h-14 w-14 flex-shrink-0 relative group-hover:scale-105 transition-transform duration-200">
                                            <div
                                                class="h-14 w-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm ring-1 ring-gray-200">
                                                <i class="ti ti-building-store text-2xl text-gray-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div
                                                class="text-sm font-semibold text-gray-900 group-hover:text-primary-700 transition-colors">
                                                {{ $item->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 flex items-center space-x-2">
                                                <span
                                                    class="px-2 py-1 bg-gray-100 rounded-md font-mono text-xs">{{ $item->kode_supplier }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap w-48">
                                    <div class="space-y-1">
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <i class="ti ti-phone text-xs mr-1"></i>
                                            {{ $item->telepon ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <i class="ti ti-mail text-xs mr-1"></i>
                                            {{ $item->email ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap w-48">
                                    <div class="text-sm text-gray-700">
                                        {{ Str::limit($item->alamat ?? 'Alamat tidak tersedia', 50) }}
                                    </div>
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap text-center w-24">
                                    @php
                                        $statusConfig = $item->status
                                            ? [
                                                'class' => 'bg-green-100 text-green-800 border-green-200',
                                                'text' => 'Aktif',
                                                'icon' => '<i class="ti ti-check text-xs"></i>',
                                            ]
                                            : [
                                                'class' => 'bg-red-100 text-red-800 border-red-200',
                                                'text' => 'Nonaktif',
                                                'icon' => '<i class="ti ti-x text-xs"></i>',
                                            ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold border {{ $statusConfig['class'] }}">
                                        {!! $statusConfig['icon'] !!}
                                        <span class="ml-1">{{ $statusConfig['text'] }}</span>
                                    </span>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap text-center w-32">
                                    <div class="flex items-center justify-center space-x-1">
                                        <!-- View Button -->
                                        <a href="{{ route('supplier.show', $item->encrypted_id) }}"
                                            class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-eye text-xs"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('supplier.edit', $item->encrypted_id) }}"
                                            class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-edit text-xs"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button"
                                            onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->nama }}')"
                                            class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                            <i class="ti ti-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="ti ti-building-store-off text-5xl mx-auto mb-4 text-gray-400"></i>
                                        <p class="text-lg font-medium">Tidak ada supplier ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau tambah supplier baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (isset($suppliers) && $suppliers->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($suppliers->previousPageUrl())
                            <a href="{{ $suppliers->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Sebelumnya
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @endif

                        @if ($suppliers->nextPageUrl())
                            <a href="{{ $suppliers->nextPageUrl() }}"
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
                                Menampilkan <span class="font-medium">{{ $suppliers->firstItem() ?? 0 }}</span> sampai
                                <span class="font-medium">{{ $suppliers->lastItem() ?? 0 }}</span> dari
                                <span class="font-medium">{{ $suppliers->total() }}</span> supplier
                            </p>
                        </div>
                        <div>
                            {{ $suppliers->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
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

        .swal2-confirm-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            transition: all 0.2s ease !important;
        }

        .swal2-confirm-delete:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3) !important;
        }

        .swal2-cancel-delete {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            transition: all 0.2s ease !important;
        }

        .swal2-cancel-delete:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3) !important;
        }

        .swal2-icon {
            border-width: 3px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Quick filter function
        function setQuickFilter(status) {
            document.getElementById('status-filter').value = status;
            // Submit the main search form
            document.querySelector('form[action="{{ route('supplier.index') }}"]').submit();
        }

        // Confirm delete function with SweetAlert
        function confirmDelete(supplierId, supplierName) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus supplier "${supplierName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm-delete',
                    cancelButton: 'swal2-cancel-delete'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('supplier.destroy', '') }}/${supplierId}`;

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

        // Show notification function
        function showNotification(message, type = 'info') {
            let bgColor, icon;

            switch (type) {
                case 'error':
                    bgColor = 'bg-red-500';
                    icon = `<i class="ti ti-alert-circle text-lg mr-2"></i>`;
                    break;
                case 'success':
                    bgColor = 'bg-green-500';
                    icon = `<i class="ti ti-check text-lg mr-2"></i>`;
                    break;
                case 'info':
                    bgColor = 'bg-blue-500';
                    icon = `<i class="ti ti-loader text-lg mr-2 animate-spin"></i>`;
                    break;
                default:
                    bgColor = 'bg-blue-500';
                    icon = `<i class="ti ti-info-circle text-lg mr-2"></i>`;
            }

            // Create notification element
            const notification = $(`
                <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 notification transform transition-all duration-300 translate-x-full">
                    <div class="flex items-center">
                        ${icon}
                        ${message}
                    </div>
                </div>
            `);

            // Add to body
            $('body').append(notification);

            // Animate in
            setTimeout(() => {
                notification.removeClass('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(function() {
                notification.addClass('translate-x-full');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkbox functionality
            const selectAllCheckbox = document.querySelector('thead input[type="checkbox"]');
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            // Individual checkbox change
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll(
                        'tbody input[type="checkbox"]:checked').length;
                    selectAllCheckbox.checked = checkedCount === rowCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount <
                        rowCheckboxes.length;
                });
            });

            // Show success toast notification after page load
            @if (session('success'))
                setTimeout(function() {
                    showNotification('{{ session('success') }}', 'success');
                }, 500); // Small delay to ensure DOM is ready
            @endif

            // Show error toast notification after page load
            @if (session('error'))
                setTimeout(function() {
                    showNotification('{{ session('error') }}', 'error');
                }, 500); // Small delay to ensure DOM is ready
            @endif
        });
    </script>
@endpush
