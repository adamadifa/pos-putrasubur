@extends('layouts.pos')

@section('title', 'Penyesuaian Stok')
@section('page-title', 'Penyesuaian Stok')

@section('content')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Penyesuaian Stok</h2>
                <p class="text-xs text-gray-500">Kelola koreksi stok produk</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('penyesuaian-stok.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Penyesuaian
                </a>
            </div>
        </div>

        <!-- Alerts handled via JS Toast but keeping session blocks for fallback -->
        @if (session('success'))
            <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));</script>
        @endif
        @if (session('error'))
            <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));</script>
        @endif

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('penyesuaian-stok.index') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 items-end">
                <!-- Search -->
                <div class="md:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-xs font-bold text-gray-700 mb-1">Cari</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-search text-xs"></i></span>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30"
                            placeholder="Kode, produk...">
                    </div>
                </div>

                <!-- Date From -->
                <div>
                    <label for="tanggal_dari" class="block text-xs font-bold text-gray-700 mb-1">Dari Tanggal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-xs"></i></span>
                        <input type="text" name="tanggal_dari" id="tanggal_dari"
                            value="{{ request('tanggal_dari') }}"
                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                            placeholder="Pilih tanggal">
                    </div>
                </div>

                <!-- Date To -->
                <div>
                    <label for="tanggal_sampai" class="block text-xs font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-xs"></i></span>
                        <input type="text" name="tanggal_sampai" id="tanggal_sampai"
                            value="{{ request('tanggal_sampai') }}"
                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                            placeholder="Pilih tanggal">
                    </div>
                </div>

                <!-- Jenis -->
                <div>
                    <label for="jenis_penyesuaian" class="block text-xs font-bold text-gray-700 mb-1">Jenis</label>
                    <select name="jenis_penyesuaian" id="jenis_penyesuaian"
                        class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                        <option value="">Semua</option>
                        <option value="penambahan" {{ request('jenis_penyesuaian') == 'penambahan' ? 'selected' : '' }}>Penambahan (+)</option>
                        <option value="pengurangan" {{ request('jenis_penyesuaian') == 'pengurangan' ? 'selected' : '' }}>Pengurangan (-)</option>
                        <option value="netral" {{ request('jenis_penyesuaian') == 'netral' ? 'selected' : '' }}>Netral (0)</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('penyesuaian-stok.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors" title="Reset">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Compact Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-clipboard-list text-lg"></i> Info Transaksi</span>
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-box text-lg"></i> Produk</span>
                            </th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center justify-end gap-1"><i class="ti ti-history text-lg"></i> Awal</span>
                            </th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center justify-center gap-1"><i class="ti ti-adjustments text-lg"></i> Adj</span>
                            </th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center justify-end gap-1"><i class="ti ti-building-warehouse text-lg"></i> Akhir</span>
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-user text-lg"></i> User</span>
                            </th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24">
                                <span class="flex items-center justify-center gap-1"><i class="ti ti-settings text-lg"></i> Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($penyesuaianStok as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded bg-blue-50 flex items-center justify-center border border-blue-100 text-blue-600">
                                            <i class="ti ti-adjustments text-sm"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-900">{{ $item->kode_penyesuaian }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $item->tanggal_penyesuaian->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded bg-gray-50 flex items-center justify-center border border-gray-200 overflow-hidden">
                                            @if ($item->produk->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->produk->foto))
                                                <img src="{{ asset('storage/' . $item->produk->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="ti ti-photo text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]">{{ $item->produk->nama_produk }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $item->produk->satuan->nama }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <span class="text-sm text-gray-600 font-mono">{{ number_format($item->stok_sebelum, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if ($item->jumlah_penyesuaian > 0)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                                            <i class="ti ti-arrow-up text-[8px]"></i> {{ number_format($item->jumlah_penyesuaian, 0, ',', '.') }}
                                        </span>
                                    @elseif($item->jumlah_penyesuaian < 0)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                            <i class="ti ti-arrow-down text-[8px]"></i> {{ number_format(abs($item->jumlah_penyesuaian), 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                            0
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <span class="text-sm font-bold text-gray-900 font-mono">{{ number_format($item->stok_sesudah, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-500">
                                            <i class="ti ti-user text-xs"></i>
                                        </div>
                                        <span class="text-xs text-gray-600 truncate max-w-[80px]" title="{{ $item->user->name }}">{{ $item->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('penyesuaian-stok.show', $item) }}"
                                            class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('penyesuaian-stok.edit', $item) }}"
                                            class="p-1.5 text-orange-600 hover:text-orange-800 hover:bg-orange-50 rounded transition-colors" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('penyesuaian-stok.destroy', $item) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus penyesuaian ini? Stok akan dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                    <i class="ti ti-adjustments-off text-3xl mb-2 block"></i>
                                    <span class="text-sm">Tidak ada data penyesuaian stok</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($penyesuaianStok->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    {{ $penyesuaianStok->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".flatpickr-input", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,
                theme: "light"
            });

            window.showToast = function(message, type = 'info') {
                const container = document.getElementById('toast-container');
                const id = 'toast-' + Date.now();
                const color = type === 'error' ? 'bg-red-500' : (type === 'success' ? 'bg-green-500' : 'bg-blue-500');
                const icon = type === 'error' ? 'ti-alert-circle' : (type === 'success' ? 'ti-check-circle' : 'ti-info-circle');
                
                const el = document.createElement('div');
                el.id = id;
                el.className = `${color} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 transform translate-x-full transition-transform duration-300 text-sm max-w-sm`;
                el.innerHTML = `
                    <i class="ti ${icon} text-lg"></i>
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white"><i class="ti ti-x"></i></button>
                `;
                
                container.appendChild(el);
                requestAnimationFrame(() => el.classList.remove('translate-x-full'));
                setTimeout(() => {
                    el.classList.add('translate-x-full');
                    setTimeout(() => el.remove(), 300);
                }, 4000);
            };
        });
    </script>
@endsection
