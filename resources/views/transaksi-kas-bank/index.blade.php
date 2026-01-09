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
                        <button type="button"
                            class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <span class="sr-only">Dismiss</span>
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
                            class="inline-flex bg-red-50 rounded-lg p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <span class="sr-only">Dismiss</span>
                            <i class="ti ti-x text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Cards (Kept Gradient as requested in flow, but assuming consistency with peminjam index revert) -->
        <!-- User only asked to compact table and fix filter. Stats cards were untouched in plan. -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total IN Card -->
            <div class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 rounded-xl shadow-lg p-6 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative flex items-center">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="ti ti-arrow-down text-2xl text-white"></i>
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

            <!-- Total OUT Card -->
            <div class="relative bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg p-6 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="relative flex items-center">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="ti ti-arrow-up text-2xl text-white"></i>
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

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('transaksi-kas-bank.index') }}"
                class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
                
                <!-- Date From -->
                <div class="lg:w-64">
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <div class="relative">
                        <input type="text" name="tanggal_mulai" id="tanggal_mulai" 
                            value="{{ request('tanggal_mulai') }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Date To -->
                <div class="lg:w-64">
                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <div class="relative">
                        <input type="text" name="tanggal_akhir" id="tanggal_akhir" 
                            value="{{ request('tanggal_akhir') }}"
                            placeholder="Pilih tanggal"
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="ti ti-calendar text-gray-400 text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Kas/Bank Filter -->
                <div class="lg:w-80">
                    <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Kas/Bank</label>
                    <select name="kas_bank_id" id="kas_bank_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="">Semua Kas/Bank</option>
                        @foreach ($kasBankList as $kasBank)
                            <option value="{{ $kasBank->id }}"
                                {{ request('kas_bank_id') == $kasBank->id ? 'selected' : '' }}>
                                {{ $kasBank->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Filter -->
                <div class="lg:w-48">
                    <label for="kategori_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_transaksi" id="kategori_transaksi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="">Semua</option>
                        <option value="PJ" {{ request('kategori_transaksi') == 'PJ' ? 'selected' : '' }}>Penjualan</option>
                        <option value="PB" {{ request('kategori_transaksi') == 'PB' ? 'selected' : '' }}>Pembelian</option>
                        <option value="MN" {{ request('kategori_transaksi') == 'MN' ? 'selected' : '' }}>Manual</option>
                        <option value="TF" {{ request('kategori_transaksi') == 'TF' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                <!-- Jenis Filter -->
                <div class="lg:w-48">
                    <label for="jenis_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                    <select name="jenis_transaksi" id="jenis_transaksi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="">Semua</option>
                        <option value="D" {{ request('jenis_transaksi') == 'D' ? 'selected' : '' }}>IN</option>
                        <option value="K" {{ request('jenis_transaksi') == 'K' ? 'selected' : '' }}>OUT</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                        <i class="ti ti-filter text-lg mr-2"></i>
                        Filter
                    </button>
                    @if (request()->hasAny(['tanggal_mulai', 'tanggal_akhir', 'kas_bank_id', 'kategori_transaksi', 'jenis_transaksi']))
                        <a href="{{ route('transaksi-kas-bank.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider pl-4">No</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center gap-1.5"><i class="ti ti-receipt text-orange-600"></i> Bukti & Tanggal</div>
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center gap-1.5"><i class="ti ti-wallet text-orange-600"></i> Kas/Bank</div>
                            </th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-1.5"><i class="ti ti-category text-orange-600"></i> Kategori</div>
                            </th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-1.5"><i class="ti ti-arrows-exchange text-orange-600"></i> Jenis</div>
                            </th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-end gap-1.5"><i class="ti ti-currency-dollar text-orange-600"></i> Jumlah</div>
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center gap-1.5"><i class="ti ti-message text-orange-600"></i> Ket</div>
                            </th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($transaksi as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-500 pl-4">
                                    {{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">{{ $item->no_bukti }}</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->kasBank->nama }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->kasBank->jenis }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                    @php
                                        $kategoriConfig = [
                                            'PJ' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'label' => 'Penjualan'],
                                            'PB' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'label' => 'Pembelian'],
                                            'MN' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'label' => 'Manual'],
                                            'TF' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'label' => 'Transfer'],
                                        ];
                                        $config = $kategoriConfig[$item->kategori_transaksi] ?? $kategoriConfig['MN'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} border border-transparent">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                    @if($item->jenis_transaksi == 'D')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                                            <i class="ti ti-arrow-down mr-1"></i> IN
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700">
                                            <i class="ti ti-arrow-up mr-1"></i> OUT
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold {{ $item->jenis_transaksi == 'D' ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-500 truncate max-w-xs">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center pr-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('transaksi-kas-bank.show', $item->id) }}"
                                            class="p-1.5 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors"
                                            title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        @if ($item->referensi_tipe == 'MN')
                                            <a href="{{ route('transaksi-kas-bank.edit', $item->id) }}"
                                                class="p-1.5 rounded-lg text-orange-600 bg-orange-50 hover:bg-orange-100 transition-colors"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete('{{ $item->id }}', '{{ $item->no_bukti }}')"
                                                class="p-1.5 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition-colors"
                                                title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @else
                                            <span class="p-1.5 rounded-lg text-gray-400 bg-gray-50 cursor-not-allowed" title="Otomatis">
                                                <i class="ti ti-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="ti ti-database-off text-3xl text-gray-300 mb-2"></i>
                                        <p class="text-sm">Tidak ada transaksi ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transaksi->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    {{ $transaksi->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // -- FLATPICKR CONFIG --
            // Uses altInput to show d/m/Y to user but send Y-m-d to server
            // Removing the need for separate hidden inputs
            
            const commonConfig = {
                locale: "id",
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                allowInput: true
            };

            const dateFrom = flatpickr("#tanggal_mulai", commonConfig);
            const dateTo = flatpickr("#tanggal_akhir", commonConfig);

            // Link date pickers
            dateFrom.config.onChange.push(function(selectedDates) {
                if (selectedDates[0]) {
                    dateTo.set('minDate', selectedDates[0]);
                }
            });
        });

        function confirmDelete(id, noBukti) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: `Yakin ingin menghapus data ${noBukti}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('transaksi-kas-bank.destroy', '') }}/${id}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
