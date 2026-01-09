@extends('layouts.pos')

@section('title', 'Metode Pembayaran')
@section('page-title', 'Data Metode Pembayaran')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Metode Pembayaran</h2>
                <p class="text-sm text-gray-500">Kelola opsi pembayaran untuk transaksi</p>
            </div>
            <a href="{{ route('metode-pembayaran.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                <i class="ti ti-plus text-lg mr-2"></i>
                Tambah Metode
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Card -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-credit-card text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Total Metode</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalMetode ?? 0 }}</p>
                            <p class="text-sm text-blue-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-lg mr-1"></i>
                                Semua opsi tersedia
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Aktif Card -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-check-circle text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-emerald-100">Metode Aktif</h3>
                            <p class="text-3xl font-bold text-white">{{ $aktifCount ?? 0 }}</p>
                            <p class="text-sm text-emerald-200 flex items-center mt-1">
                                <i class="ti ti-trending-up text-lg mr-1"></i>
                                Dapat digunakan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Nonaktif Card -->
            <div
                class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-x-circle text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-red-100">Nonaktif</h3>
                            <p class="text-3xl font-bold text-white">{{ $nonaktifCount ?? 0 }}</p>
                            <p class="text-sm text-red-200 flex items-center mt-1">
                                <i class="ti ti-info-circle text-lg mr-1"></i>
                                Tidak tersedia
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table Header & Search -->
            <div class="px-6 py-4 border-b border-gray-100">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cari nama, kode, atau deskripsi...">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full md:w-48">
                        <select name="status"
                            class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Cari
                        </button>
                        <a href="{{ route('metode-pembayaran.index') }}"
                            class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Compact Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($metodePembayaran as $index => $metode)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <!-- Number -->
                                <td class="px-3 py-3 whitespace-nowrap text-xs text-center text-gray-400">
                                    {{ ($metodePembayaran->currentPage() - 1) * $metodePembayaran->perPage() + $index + 1 }}
                                </td>

                                <!-- Metode Info -->
                                <td class="px-3 py-3">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-lg bg-blue-50 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="ti {{ $metode->icon ?? 'ti-credit-card' }} text-blue-500 text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $metode->nama }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 font-mono">{{ $metode->kode }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Deskripsi -->
                                <td class="px-3 py-3">
                                    <p class="text-xs text-gray-500 line-clamp-2 max-w-xs">{{ $metode->deskripsi ?: '-' }}</p>
                                </td>

                                <!-- Status -->
                                <td class="px-3 py-3 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = $metode->status
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                            : 'bg-red-50 text-red-700 border-red-100';
                                        $statusText = $metode->status ? 'AKTIF' : 'NONAKTIF';
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-[10px] uppercase font-bold tracking-wide rounded-md border {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                 <!-- Urutan -->
                                 <td class="px-3 py-3 whitespace-nowrap text-center text-sm font-medium text-gray-600">
                                    {{ $metode->urutan ?? '-' }}
                                </td>

                                <!-- Actions -->
                                <td class="px-3 py-3 whitespace-nowrap text-right pl-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('metode-pembayaran.edit', $metode->encrypted_id) }}" 
                                           class="p-1.5 rounded-lg text-amber-600 bg-amber-50 hover:bg-amber-100 hover:text-amber-700 transition-all font-medium"
                                           title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $metode->encrypted_id }}', '{{ $metode->nama }}')"
                                                class="p-1.5 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 transition-all font-medium"
                                                title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-3 bg-gray-100 rounded-full mb-3">
                                            <i class="ti ti-credit-card-off text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-sm font-medium">Tidak ada metode pembayaran</p>
                                        <p class="text-xs text-gray-400 mt-1">Tambahkan metode baru untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($metodePembayaran->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $metodePembayaran->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Metode?',
                text: `Hapus "${nama}"? Metode ini tidak dapat dipulihkan.`,
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
                    form.action = `{{ route('metode-pembayaran.destroy', '') }}/${id}`;
                    
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
