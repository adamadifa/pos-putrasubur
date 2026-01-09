@extends('layouts.pos')

@section('title', 'Produk')
@section('page-title', 'Kelola Produk')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Daftar Produk</h2>
                <p class="text-sm text-gray-500">Kelola inventaris dan stok produk</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl font-medium text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    <i class="ti ti-download text-lg mr-2"></i>
                    Import
                </button>
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Produk
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                                <i class="ti ti-info-circle text-lg mr-1"></i>
                                Semua produk
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Produk Tersedia Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                            <h3 class="text-sm font-medium text-emerald-100">Produk Tersedia</h3>
                            <p class="text-3xl font-bold text-white">{{ $produkTersedia }}</p>
                            <p class="text-sm text-emerald-200 flex items-center mt-1">
                                <i class="ti ti-trending-up text-lg mr-1"></i>
                                {{ $totalProduk > 0 ? round(($produkTersedia / $totalProduk) * 100) : 0 }}% dari total
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Stok Menipis Card -->
            <div
                class="relative bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-alert-triangle text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-amber-100">Stok Menipis</h3>
                            <p class="text-3xl font-bold text-white">{{ $produkMenipis }}</p>
                            <p class="text-sm text-amber-200 flex items-center mt-1">
                                @if ($produkMenipis > 0)
                                    <i class="ti ti-alert-circle text-lg mr-1"></i>
                                    Perlu restock
                                @else
                                    <i class="ti ti-check-circle text-lg mr-1"></i>
                                    Aman
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Stok Habis Card -->
            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-rose-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-warehouse text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Stok Habis</h3>
                            <p class="text-3xl font-bold text-white">{{ $produkHabis }}</p>
                            <p class="text-sm text-red-200 flex items-center mt-1">
                                @if ($produkHabis > 0)
                                    <i class="ti ti-x text-lg mr-1"></i>
                                    Tidak tersedia
                                @else
                                    <i class="ti ti-check text-lg mr-1"></i>
                                    Semua tersedia
                                @endif
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
                            <i class="ti ti-package text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Produk</h3>
                            <p class="text-sm text-gray-500">Menampilkan {{ $produk->count() }} dari
                                {{ $produk->total() }} produk</p>
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
                <form method="GET" action="{{ route('produk.index') }}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Main Search -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-search text-lg text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama produk, kode, atau kategori..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>
                        </div>

                        <!-- Quick Filters -->
                        <div class="flex items-center gap-3">
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button type="button" onclick="setQuickFilter('')"
                                    class="px-3 py-1.5 text-sm font-medium {{ !request('status') ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Semua</button>
                                <button type="button" onclick="setQuickFilter('tersedia')"
                                    class="px-3 py-1.5 text-sm font-medium {{ request('status') == 'tersedia' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Tersedia</button>
                                <button type="button" onclick="setQuickFilter('habis')"
                                    class="px-3 py-1.5 text-sm font-medium {{ request('status') == 'habis' ? 'bg-white text-primary-600 shadow-sm' : 'text-gray-600 hover:bg-white hover:shadow-sm' }} rounded-md transition-all">Habis</button>
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

            <!-- Compact Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Kategori</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($produk as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                           <!-- Number -->
                            <td class="px-3 py-3 whitespace-nowrap text-xs text-center text-gray-400">
                                {{ ($produk->currentPage() - 1) * $produk->perPage() + $index + 1 }}
                            </td>
                            
                            <!-- Product Info -->
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 flex-shrink-0 relative rounded-lg border border-gray-100 overflow-hidden bg-gray-50">
                                        @if ($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                                alt="{{ $item->nama_produk }}" 
                                                class="h-full w-full object-cover"
                                                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center text-gray-300 {{ $item->foto ? 'hidden' : '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 opacity-70">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                            {{ $item->nama_produk }}
                                        </div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $item->kode_produk }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-3 py-3 whitespace-nowrap hidden md:table-cell">
                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $item->kategori->nama }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-3 py-3 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-gray-900">{{ number_format($item->harga_jual, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-gray-400">Beli: {{ number_format($item->harga_beli, 0, ',', '.') }}</div>
                            </td>

                            <!-- Stock -->
                            <td class="px-3 py-3 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-bold {{ $item->stok <= $item->stok_minimal ? 'text-red-500' : 'text-gray-700' }}">
                                        {{ number_format($item->stok, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">{{ $item->satuan->nama }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-3 py-3 whitespace-nowrap text-center hidden sm:table-cell">
                                @php
                                    $statusClass = match ($item->status_stok) {
                                        'habis' => 'bg-red-50 text-red-700 border-red-100',
                                        'menipis' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'tersedia' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        default => 'bg-gray-50 text-gray-700 border-gray-100',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 inline-flex text-[10px] uppercase font-bold tracking-wide rounded-md border {{ $statusClass }}">
                                    {{ $item->status_stok }}
                                </span>
                            </td>

                                <!-- Actions -->
                                <td class="px-3 py-3 whitespace-nowrap text-right pl-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('produk.show', $item->id) }}" 
                                           class="p-1.5 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 transition-all font-medium"
                                           title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('produk.edit', $item->id) }}" 
                                           class="p-1.5 rounded-lg text-amber-600 bg-amber-50 hover:bg-amber-100 hover:text-amber-700 transition-all font-medium"
                                           title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_produk }}')"
                                                class="p-1.5 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 transition-all font-medium"
                                                title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                        </tr>
                        @empty
                        <tr>
                             <td colspan="7" class="px-6 py-12 text-center text-gray-500 bg-gray-50/50">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-3 bg-gray-100 rounded-full mb-3">
                                        <i class="ti ti-package-off text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm font-medium">Tidak ada produk ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($produk->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                {{ $produk->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Quick filter function
        function setQuickFilter(status) {
            document.getElementById('status-filter').value = status;
            // Submit the main search form
            document.querySelector('form[action="{{ route('produk.index') }}"]').submit();
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: `Hapus "${name}"? Data tidak bisa dikembalikan.`,
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
                    form.action = `{{ route('produk.destroy', '') }}/${id}`;
                    
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
