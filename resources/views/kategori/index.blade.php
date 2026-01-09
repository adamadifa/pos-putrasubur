@extends('layouts.pos')

@section('title', 'Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Daftar Kategori</h2>
                <p class="text-sm text-gray-500">Kelola kategori untuk pengelompokan produk</p>
            </div>
            <div>
                <a href="{{ route('kategori.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>

        <!-- Success/Error Alerts -->
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
        
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-100 rounded-lg text-red-600">
                        <i class="ti ti-alert-circle text-lg"></i>
                    </div>
                    <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total Kategori Card -->
            <div
                class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-category text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-purple-100">Total Kategori</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalKategori }}</p>
                            <p class="text-sm text-purple-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-lg mr-1"></i>
                                Semua kategori aktif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Total Produk Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-package text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Total Produk</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalProduk }}</p>
                            <p class="text-sm text-blue-200 flex items-center mt-1">
                                <i class="ti ti-chart-pie text-lg mr-1"></i>
                                Terdistribusi di kategori
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary-50 rounded-lg">
                            <i class="ti ti-category text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Kategori</h3>
                            <p class="text-sm text-gray-500">Menampilkan {{ $kategoris->count() }} dari
                                {{ $totalKategori }} kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" action="{{ route('kategori.index') }}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Main Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama kategori..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="ti ti-search text-lg inline mr-1.5"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Compact Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($kategoris as $index => $kategori)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                           <!-- Number -->
                            <td class="px-3 py-3 whitespace-nowrap text-xs text-center text-gray-400">
                                {{ $index + 1 }}
                            </td>
                            
                            <!-- Category Name -->
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 flex-shrink-0 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                        <i class="ti ti-category text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ $kategori->nama }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Product Count -->
                            <td class="px-3 py-3 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $kategori->produk_count }} Produk
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-3 py-3 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 inline-flex text-[10px] uppercase font-bold tracking-wide rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Aktif
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-3 py-3 whitespace-nowrap text-right pl-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" 
                                       class="p-1.5 rounded-lg text-amber-600 bg-amber-50 hover:bg-amber-100 hover:text-amber-700 transition-all font-medium"
                                       title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="confirmDelete({{ $kategori->id }}, '{{ $kategori->nama }}')"
                                            class="p-1.5 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 transition-all font-medium"
                                            title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                             <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-gray-50/50">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-3 bg-gray-100 rounded-full mb-3">
                                        <i class="ti ti-category-off text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada kategori</p>
                                    <p class="text-xs text-gray-400 mt-1">Tambahkan kategori baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Hapus "${name}"? Produk dalam kategori ini mungkin akan terpengaruh.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-4 py-2 rounded-lg',
                    cancelButton: 'px-4 py-2 rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('kategori.destroy', '') }}/${id}`;
                    
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
