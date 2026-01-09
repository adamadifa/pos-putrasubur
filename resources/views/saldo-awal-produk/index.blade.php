@extends('layouts.pos')

@section('title', 'Saldo Awal Produk')
@section('page-title', 'Saldo Awal Produk')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Saldo Awal Produk</h2>
                <p class="text-xs text-gray-500">Kelola stok awal produk per periode pembukuan</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('saldo-awal-produk.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Saldo
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm flex items-center gap-3">
                <i class="ti ti-check-circle text-green-500 text-lg"></i>
                <div>
                    <h3 class="text-sm font-bold text-green-800">Berhasil</h3>
                    <p class="text-xs text-green-700">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.remove()">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm flex items-center gap-3">
                <i class="ti ti-alert-circle text-red-500 text-lg"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Error</h3>
                    <p class="text-xs text-red-700">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('saldo-awal-produk.index') }}" class="flex flex-col md:flex-row gap-4 md:items-end">
                <div class="flex-1 max-w-xs">
                    <label for="tahun" class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" id="tahun" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun', now()->year) == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 max-w-xs">
                    <label for="bulan" class="block text-xs font-bold text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" id="bulan" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                        <option value="">Semua Bulan</option>
                        @foreach ($bulanList as $key => $bulan)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                {{ $bulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['tahun', 'bulan']))
                        <a href="{{ route('saldo-awal-produk.index') }}" class="ml-2 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Compact Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($saldoAwal as $index => $saldoHeader)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-2 text-center text-xs text-gray-500">
                                    {{ $saldoAwal->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $saldoHeader->bulan_nama }} {{ $saldoHeader->periode_tahun }}
                                        </div>
                                        <span class="text-xs text-gray-400">({{ $saldoHeader->details->count() }} items)</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-xs font-medium text-gray-900">{{ $saldoHeader->user->name }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-900">{{ $saldoHeader->created_at->format('d/m/Y') }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $saldoHeader->created_at->format('H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" onclick="showDetail({{ $saldoHeader->id }})"
                                            class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors"
                                            title="Lihat Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        
                                        @if (\App\Models\SaldoAwalProduk::canEdit($saldoHeader->periode_bulan, $saldoHeader->periode_tahun))
                                            <form action="{{ route('saldo-awal-produk.destroy', $saldoHeader) }}" method="POST" 
                                                class="inline-block"
                                                onsubmit="return confirm('Hapus saldo awal periode {{ $saldoHeader->bulan_nama }} {{ $saldoHeader->periode_tahun }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-1.5 text-gray-300 cursor-not-allowed" title="Terkunci (Ada saldo bulan berikutnya)">
                                                <i class="ti ti-lock"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    <i class="ti ti-package text-3xl mb-2 block"></i>
                                    <span class="text-sm">Belum ada data saldo awal produk</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($saldoAwal->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    {{ $saldoAwal->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden z-50 items-center justify-center flex p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50 rounded-t-xl">
                <h3 class="text-sm font-bold text-gray-900" id="modalTitle">Detail Saldo Awal Produk</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ti ti-x text-lg"></i>
                </button>
            </div>

            <!-- Content Modal -->
            <div id="modalContent" class="p-6 overflow-y-auto">
                <!-- Content loaded via JS -->
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-white text-gray-700 text-sm font-medium border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDetail(saldoAwalId) {
            const content = document.getElementById('modalContent');
            content.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <i class="ti ti-loader-2 animate-spin text-3xl text-blue-600 mb-2"></i>
                    <span class="text-sm text-gray-500">Memuat data...</span>
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');

            fetch(`/saldo-awal-produk/${saldoAwalId}/detail`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) renderDetailContent(data.data);
                    else showErrorState();
                })
                .catch(() => showErrorState());
        }

        function showErrorState() {
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="ti ti-alert-triangle text-3xl text-red-500 mb-2 block"></i>
                    <p class="text-sm text-gray-600">Gagal memuat data. Silakan coba lagi.</p>
                </div>
            `;
        }

        function renderDetailContent(data) {
            document.getElementById('modalTitle').textContent = `Detail Saldo: ${data.bulan_nama} ${data.periode_tahun}`;
            
            let html = `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <span class="block text-xs text-blue-600 mb-1">Periode</span>
                        <span class="block text-sm font-bold text-blue-900">${data.bulan_nama} ${data.periode_tahun}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="block text-xs text-gray-500 mb-1">Dibuat Oleh</span>
                        <span class="block text-sm font-medium text-gray-900">${data.user.name}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="block text-xs text-gray-500 mb-1">Waktu Buat</span>
                        <span class="block text-sm font-medium text-gray-900">${data.created_at}</span>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase w-12">No</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Produk</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase w-32">Kategori</th>
                                <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase w-32">Saldo Awal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
            `;

            data.details.forEach((d, i) => {
                const img = d.produk.foto 
                    ? `<img src="/storage/${d.produk.foto}" class="w-8 h-8 rounded object-cover border border-gray-100">`
                    : `<div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">${d.produk.nama_produk.charAt(0)}</div>`;

                html += `
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-2 text-xs text-gray-500 text-center">${i + 1}</td>
                        <td class="px-4 py-2">
                             <div class="flex items-center gap-3">
                                ${img}
                                <div>
                                    <div class="text-sm font-medium text-gray-900">${d.produk.nama_produk}</div>
                                    <div class="text-xs text-gray-500">${d.produk.satuan.nama}</div>
                                </div>
                             </div>
                        </td>
                        <td class="px-4 py-2 text-xs text-gray-600">${d.produk.kategori.nama}</td>
                        <td class="px-4 py-2 text-right">
                            <span class="text-sm font-bold text-blue-600 font-mono">${new Intl.NumberFormat('id-ID').format(d.saldo_awal)}</span>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table></div>`;
            document.getElementById('modalContent').innerHTML = html;
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        document.getElementById('detailModal').addEventListener('click', (e) => {
            if (e.target.id === 'detailModal') closeDetailModal();
        });
    </script>
@endsection
