@extends('layouts.pos')

@section('title', 'Kas & Bank')
@section('page-title', 'Data Kas & Bank')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Kas & Bank</h2>
                <p class="text-sm text-gray-500">Kelola akun kas dan rekening bank</p>
            </div>
            <a href="{{ route('kas-bank.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                <i class="ti ti-plus text-lg mr-2"></i>
                Tambah Akun
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
            <!-- Total Saldo -->
            <div
                class="relative bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
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
                            <h3 class="text-sm font-medium text-indigo-100">Total Saldo</h3>
                            <p class="text-2xl font-bold text-white">Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}</p>
                            <p class="text-sm text-indigo-200 flex items-center mt-1">
                                <i class="ti ti-chart-pie text-lg mr-1"></i>
                                Akumulasi aset likuid
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Akun Kas -->
            <div
                class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-cash text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-emerald-100">Akun Kas</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalKas ?? 0 }}</p>
                            <p class="text-sm text-emerald-200 flex items-center mt-1">
                                <i class="ti ti-box text-lg mr-1"></i>
                                Akun tunai fisik
                            </p>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 right-0 w-20 h-20 bg-white/5 rounded-tl-full"></div>
            </div>

            <!-- Akun Bank -->
            <div
                class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="ti ti-building-bank text-2xl text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-blue-100">Akun Bank</h3>
                            <p class="text-3xl font-bold text-white">{{ $totalBank ?? 0 }}</p>
                            <p class="text-sm text-blue-200 flex items-center mt-1">
                                <i class="ti ti-credit-card text-lg mr-1"></i>
                                Rekening bank
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
                                placeholder="Cari nama akun, kode, atau nomor rekening...">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="w-full md:w-48">
                         <select name="sort_by" onchange="this.form.submit()"
                            class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama (A-Z)</option>
                            <option value="saldo_terkini" {{ request('sort_by') == 'saldo_terkini' ? 'selected' : '' }}>Saldo Tertinggi</option>
                        </select>
                        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Cari
                        </button>
                        <a href="{{ route('kas-bank.index') }}"
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
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Akun</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Rekening</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Terkini</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">P.Card</th>
                            <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($kasBank as $index => $item)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <!-- Number -->
                                <td class="px-3 py-3 whitespace-nowrap text-xs text-center text-gray-400">
                                    {{ ($kasBank->currentPage() - 1) * $kasBank->perPage() + $index + 1 }}
                                </td>

                                <!-- Nama Info -->
                                <td class="px-3 py-3">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 {{ $item->jenis == 'BANK' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                                            @if($item->image_url)
                                                <img src="{{ $item->image_url }}" alt="" class="h-9 w-9 rounded-lg object-cover">
                                            @else
                                                <i class="ti {{ $item->jenis == 'BANK' ? 'ti-building-bank' : 'ti-cash' }} text-lg"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $item->nama }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 font-mono">{{ $item->kode }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-3 py-3 whitespace-nowrap">
                                    @if ($item->jenis == 'BANK')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">
                                            <i class="ti ti-building-bank mr-1"></i> BANK
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">
                                            <i class="ti ti-cash mr-1"></i> KAS
                                        </span>
                                    @endif
                                </td>

                                <!-- No Rekening -->
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-600 font-mono">
                                    {{ $item->no_rekening ?: '-' }}
                                </td>

                                 <!-- Saldo -->
                                <td class="px-3 py-3 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold text-gray-800">
                                        Rp {{ number_format($item->saldo_terkini, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Card Payment Status -->
                                <td class="px-3 py-3 whitespace-nowrap text-center">
                                    @if ($item->status_card_payment)
                                        <span class="text-blue-500" title="Digunakan untuk Card Payment">
                                            <i class="ti ti-credit-card text-lg"></i>
                                        </span>
                                    @else
                                        <span class="text-gray-200">
                                            <i class="ti ti-minus"></i>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-3 py-3 whitespace-nowrap text-right pl-6">
                                    <div class="flex items-center justify-end gap-2">
                                         <a href="{{ route('kas-bank.show', $item->encrypted_id) }}" 
                                           class="p-1.5 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 transition-all font-medium"
                                           title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('kas-bank.edit', $item->encrypted_id) }}" 
                                           class="p-1.5 rounded-lg text-amber-600 bg-amber-50 hover:bg-amber-100 hover:text-amber-700 transition-all font-medium"
                                           title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $item->encrypted_id }}', '{{ $item->nama }}')"
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
                                            <i class="ti ti-wallet-off text-2xl text-gray-400"></i>
                                        </div>
                                        <p class="text-sm font-medium">Tidak ada data kas & bank</p>
                                        <p class="text-xs text-gray-400 mt-1">Tambahkan akun baru untuk mulai pencatatan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($kasBank->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $kasBank->appends(request()->query())->links() }}
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
                title: 'Hapus Akun?',
                text: `Hapus "${nama}"? Data yang sudah dihapus tidak dapat dikembalikan.`,
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
                    form.action = `{{ route('kas-bank.destroy', '') }}/${id}`;
                    
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
