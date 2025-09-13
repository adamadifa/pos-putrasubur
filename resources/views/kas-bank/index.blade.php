@extends('layouts.pos')

@section('title', 'Kas & Bank')
@section('page-title', 'Kelola Kas & Bank')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Kas & Bank</h2>
                <p class="text-sm text-gray-600">Kelola semua data kas dan bank dalam sistem POS Anda</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('kas-bank.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Kas & Bank
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-check text-lg text-green-400"></i>
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
            <!-- Total Kas & Bank Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-credit-card text-xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Total Kas & Bank</h3>
                            <p class="text-3xl font-bold text-white">{{ $kasBank->total() }}</p>
                            <p class="text-sm text-blue-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-sm mr-1"></i>
                                Semua data
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kas Card -->
            <div
                class="relative bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-cash text-xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-green-100">Kas</h3>
                            <p class="text-3xl font-bold text-white">{{ $kasBank->where('kode', 'like', 'KAS%')->count() }}
                            </p>
                            <p class="text-sm text-green-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-sm mr-1"></i>
                                Kas tunai
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Card -->
            <div
                class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-bank text-xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-purple-100">Bank</h3>
                            <p class="text-3xl font-bold text-white">{{ $kasBank->where('kode', 'like', 'BANK%')->count() }}
                            </p>
                            <p class="text-sm text-purple-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-sm mr-1"></i>
                                Rekening bank
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kas & Bank Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary-50 rounded-lg">
                            <i class="ti ti-credit-card text-lg text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Kas & Bank</h3>
                            <p class="text-sm text-gray-500">Menampilkan {{ $kasBank->count() }} dari
                                {{ $kasBank->total() }} data</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" action="{{ route('kas-bank.index') }}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Main Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari kode, nama, atau no rekening..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="flex items-center gap-3">
                            <select name="sort_by"
                                class="px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    Tanggal Dibuat</option>
                                <option value="kode" {{ request('sort_by') == 'kode' ? 'selected' : '' }}>Kode</option>
                                <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                                <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>
                                    Tanggal Update</option>
                            </select>
                            <select name="sort_order"
                                class="px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru
                                </option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama
                                </option>
                            </select>
                            <button type="submit"
                                class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="ti ti-search text-sm mr-1.5"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if ($kasBank->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode
                                </th>
                                <th scope="col"
                                    class="w-3/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col"
                                    class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Rekening
                                </th>
                                <th scope="col"
                                    class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis
                                </th>
                                {{-- <th scope="col"
                                    class="w-2/12 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo Terkini
                                </th> --}}
                                <th scope="col"
                                    class="w-3/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Logo
                                </th>
                                <th scope="col"
                                    class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($kasBank as $index => $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $kasBank->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-3">
                                                <i class="ti ti-credit-card text-xl text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->kode_formatted }}
                                                </div>
                                                <div class="text-xs text-gray-500">ID: {{ $item->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_formatted }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $item->no_rekening ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->jenis === 'KAS')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="ti ti-cash text-sm mr-1"></i>
                                                KAS
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="ti ti-building-bank text-sm mr-1"></i>
                                                BANK
                                            </span>
                                        @endif
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($item->saldo_terkini, 0, ',', '.') }}
                                        </div>
                                    </td> --}}
                                    <td class="px-6 py-4">
                                        @if ($item->image)
                                            <div class="w-full h-12 flex items-center justify-center">
                                                <img src="{{ $item->image_url }}" alt="Logo {{ $item->nama }}"
                                                    class="max-w-full max-h-full object-contain rounded-lg border shadow-sm">
                                            </div>
                                        @else
                                            <div
                                                class="w-full h-12 rounded-lg bg-gray-100 flex items-center justify-center border">
                                                <i class="ti ti-image text-gray-400 text-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-1">
                                            <!-- View Button -->
                                            <a href="{{ route('kas-bank.show', $item) }}"
                                                class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                                <i class="ti ti-eye text-sm"></i>
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ route('kas-bank.edit', $item) }}"
                                                class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                                <i class="ti ti-edit text-sm"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <button onclick="confirmDelete({{ $item->id }}, '{{ $item->nama }}')"
                                                class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-md shadow-md hover:shadow-lg hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200 ease-out">
                                                <i class="ti ti-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($kasBank->hasPages())
                    <div class="bg-white px-6 py-3 border-t border-gray-200">
                        {{ $kasBank->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-credit-card text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data Kas & Bank</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan data Kas & Bank pertama Anda</p>
                    <a href="{{ route('kas-bank.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i class="ti ti-plus text-sm mr-2"></i>
                        Tambah Kas & Bank Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            // Show notification function
            function showNotification(message, type = 'info') {
                let bgColor, icon;

                switch (type) {
                    case 'error':
                        bgColor = 'bg-red-500';
                        icon = `<i class="ti ti-alert-circle w-5 h-5 mr-2"></i>`;
                        break;
                    case 'success':
                        bgColor = 'bg-green-500';
                        icon = `<i class="ti ti-check w-5 h-5 mr-2"></i>`;
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        icon = `<i class="ti ti-loader-2 w-5 h-5 mr-2 animate-spin"></i>`;
                        break;
                    default:
                        bgColor = 'bg-blue-500';
                        icon = `<i class="ti ti-info-circle w-5 h-5 mr-2"></i>`;
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

            function confirmDelete(id, nama) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus "${nama}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/kas-bank/${id}`;

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        const tokenField = document.createElement('input');
                        tokenField.type = 'hidden';
                        tokenField.name = '_token';
                        tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        form.appendChild(methodField);
                        form.appendChild(tokenField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            $(document).ready(function() {
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
@endsection
