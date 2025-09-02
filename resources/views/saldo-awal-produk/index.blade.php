@extends('layouts.pos')

@section('title', 'Saldo Awal Produk')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Saldo Awal Produk</h1>
                            <p class="text-gray-500 mt-1">Kelola saldo awal stok produk per periode</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('saldo-awal-produk.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                            <i class="ti ti-plus text-lg mr-2"></i>
                            Tambah Saldo Awal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-check-circle text-lg text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
                            <div class="mt-1 text-sm text-green-700">{{ session('success') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-info-circle text-lg text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                            <div class="mt-1 text-sm text-blue-700">{{ session('info') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-circle text-lg text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error</h3>
                            <div class="mt-1 text-sm text-red-700">{{ session('error') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('saldo-awal-produk.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Tahun -->
                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                                Tahun
                            </label>
                            <select name="tahun" id="tahun"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}"
                                        {{ request('tahun', now()->year) == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bulan -->
                        <div>
                            <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">
                                Bulan
                            </label>
                            <select name="bulan" id="bulan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                                <option value="">Semua Bulan</option>
                                @foreach ($bulanList as $key => $bulan)
                                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                                <i class="ti ti-filter text-lg mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Data Saldo Awal Produk</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Menampilkan {{ $saldoAwal->count() }} dari {{ $saldoAwal->total() }} data
                    </p>
                </div>

                @if ($saldoAwal->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-2">
                                            <i class="ti ti-hash text-green-600"></i>
                                            <span>No</span>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class="ti ti-calendar text-green-600"></i>
                                            <span>Periode</span>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class="ti ti-user text-green-600"></i>
                                            <span>Dibuat Oleh</span>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <i class="ti ti-clock text-green-600"></i>
                                            <span>Tanggal Dibuat</span>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-2">
                                            <i class="ti ti-eye text-blue-600"></i>
                                            <span>Detail</span>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center justify-center space-x-2">
                                            <i class="ti ti-trash text-red-600"></i>
                                            <span>Hapus</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($saldoAwal as $index => $saldoHeader)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                            {{ $saldoAwal->firstItem() + $index }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $saldoHeader->bulan_nama }} {{ $saldoHeader->periode_tahun }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $saldoHeader->details->count() }} produk
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $saldoHeader->user->name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $saldoHeader->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $saldoHeader->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <button type="button" onclick="showDetail({{ $saldoHeader->id }})"
                                                class="inline-flex items-center p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                                title="Lihat Detail">
                                                <i class="ti ti-eye text-lg"></i>
                                            </button>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                @if (\App\Models\SaldoAwalProduk::canEdit($saldoHeader->periode_bulan, $saldoHeader->periode_tahun))
                                                    <form action="{{ route('saldo-awal-produk.destroy', $saldoHeader) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus saldo awal periode {{ $saldoHeader->bulan_nama }} {{ $saldoHeader->periode_tahun }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                            title="Hapus">
                                                            <i class="ti ti-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span
                                                        class="inline-flex items-center p-2 text-gray-400 cursor-not-allowed"
                                                        title="Tidak dapat dihapus karena sudah ada saldo awal bulan berikutnya">
                                                        <i class="ti ti-lock text-lg"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                    <i class="ti ti-package text-2xl text-gray-400"></i>
                                                </div>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data</h3>
                                                <p class="text-gray-500 text-center max-w-sm">
                                                    Belum ada saldo awal produk untuk periode yang dipilih. Klik tombol
                                                    "Tambah Saldo Awal" untuk menambahkan data baru.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($saldoAwal->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $saldoAwal->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="ti ti-package text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data</h3>
                            <p class="text-gray-500 text-center max-w-sm mb-6">
                                Belum ada saldo awal produk untuk periode yang dipilih. Klik tombol "Tambah Saldo Awal"
                                untuk menambahkan data baru.
                            </p>
                            <a href="{{ route('saldo-awal-produk.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                                <i class="ti ti-plus text-lg mr-2"></i>
                                Tambah Saldo Awal
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Header Modal -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">
                        Detail Saldo Awal Produk
                    </h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>

                <!-- Content Modal -->
                <div class="mt-4">
                    <div id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button onclick="closeDetailModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetail(saldoAwalId) {
            // Show loading
            document.getElementById('modalContent').innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <i class="ti ti-loader-2 animate-spin text-2xl text-blue-600 mr-2"></i>
                    <span class="text-gray-600">Memuat data...</span>
                </div>
            `;

            // Show modal
            document.getElementById('detailModal').classList.remove('hidden');

            // Fetch data via AJAX
            fetch(`/saldo-awal-produk/${saldoAwalId}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderDetailContent(data.data);
                    } else {
                        document.getElementById('modalContent').innerHTML = `
                            <div class="text-center py-8">
                                <i class="ti ti-alert-circle text-4xl text-red-500 mb-4"></i>
                                <p class="text-red-600">Gagal memuat data detail</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalContent').innerHTML = `
                        <div class="text-center py-8">
                            <i class="ti ti-alert-circle text-4xl text-red-500 mb-4"></i>
                            <p class="text-red-600">Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        function renderDetailContent(data) {
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            modalTitle.textContent = `Detail Saldo Awal - ${data.bulan_nama} ${data.periode_tahun}`;

            let html = `
                <div class="mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Periode</label>
                            <p class="text-sm font-semibold text-gray-900">${data.bulan_nama} ${data.periode_tahun}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Dibuat Oleh</label>
                            <p class="text-sm font-semibold text-gray-900">${data.user.name}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                            <p class="text-sm font-semibold text-gray-900">${data.created_at}</p>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

            data.details.forEach(detail => {
                const foto = detail.produk.foto ?
                    `<img class="h-10 w-10 rounded-lg object-contain border border-gray-200" src="/storage/${detail.produk.foto}" alt="${detail.produk.nama_produk}">` :
                    `<div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <span class="text-white font-medium text-sm">${detail.produk.nama_produk.charAt(0).toUpperCase()}</span>
                    </div>`;

                html += `
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center">
                                ${foto}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${detail.produk.nama_produk}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${detail.produk.kategori.nama}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${detail.produk.satuan.nama}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-green-600">${new Intl.NumberFormat('id-ID').format(detail.saldo_awal)}</div>
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            modalContent.innerHTML = html;
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>
@endsection
