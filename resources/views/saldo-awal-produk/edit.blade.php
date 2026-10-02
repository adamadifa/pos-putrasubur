@extends('layouts.pos')

@section('title', 'Edit Saldo Awal Produk')
@section('page-title', 'Edit Saldo Awal Produk')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('saldo-awal-produk.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Edit Saldo Awal Produk</h1>
                    <p class="text-xs text-gray-500">Perbarui saldo awal produk periode {{ $saldoAwalProduk->bulan_nama }} {{ $saldoAwalProduk->periode_tahun }}</p>
                </div>
            </div>
            <div class="mt-3 sm:mt-0 flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <i class="ti ti-calendar mr-1.5"></i>
                    {{ $saldoAwalProduk->bulan_nama }} {{ $saldoAwalProduk->periode_tahun }}
                </span>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm flex items-center gap-3">
                <i class="ti ti-check-circle text-green-500 text-lg"></i>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ti ti-alert-circle text-red-500 text-lg mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800">Terdapat kesalahan:</p>
                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <span class="block text-xs text-gray-500 mb-0.5">Periode</span>
                    <span class="text-sm font-bold text-gray-900">{{ $saldoAwalProduk->bulan_nama }} {{ $saldoAwalProduk->periode_tahun }}</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <span class="block text-xs text-gray-500 mb-0.5">Dibuat Oleh</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $saldoAwalProduk->user->name ?? '-' }}</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <span class="block text-xs text-gray-500 mb-0.5">Waktu Buat</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $saldoAwalProduk->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="p-3 bg-blue-50/60 rounded-lg border border-blue-100">
                    <span class="block text-xs text-blue-600 mb-0.5">Total Produk</span>
                    <span class="text-sm font-bold text-blue-900">{{ $produkList->count() }} Produk</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('saldo-awal-produk.update', $saldoAwalProduk) }}" method="POST" id="saldoAwalEditForm">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">

                    <!-- Filter & Search Bar -->
                    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                        <div class="flex-1 relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="ti ti-search text-sm"></i>
                            </span>
                            <input type="text" id="searchInput" 
                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30"
                                placeholder="Cari nama produk atau kategori...">
                        </div>
                        <div class="flex items-center gap-2">
                            <select id="kategoriFilter" 
                                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                                <option value="">Semua Kategori</option>
                                @foreach ($produkList->pluck('kategori.nama')->filter()->unique() as $kategoriNama)
                                    <option value="{{ strtolower($kategoriNama) }}">{{ $kategoriNama }}</option>
                                @endforeach
                            </select>
                            <span id="productCounter" class="text-xs text-gray-500 font-medium px-2.5 py-2 bg-gray-100 rounded-lg whitespace-nowrap">
                                Menampilkan: {{ $produkList->count() }} produk
                            </span>
                        </div>
                    </div>

                    <!-- Product Table Section -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-800">Daftar Produk</h3>
                            <div class="text-xs text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 flex items-center gap-1.5">
                                <i class="ti ti-info-circle"></i>
                                Ubah saldo awal produk sesuai kebutuhan. Nilai 0 diperbolehkan.
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <div class="overflow-x-auto max-h-[500px]">
                                <table class="min-w-full divide-y divide-gray-200" id="produkTable">
                                    <thead class="bg-gray-50 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Foto</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Kategori</th>
                                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-44">Saldo Awal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100" id="produkTableBody">
                                        @foreach ($produkList as $produk)
                                            @php
                                                $hasDetail = isset($detailsMap[$produk->id]);
                                                $saldoVal = $hasDetail ? $detailsMap[$produk->id]->saldo_awal : 0;
                                                $kategoriName = $produk->kategori->nama ?? '-';
                                                $satuanName = $produk->satuan->nama ?? '-';
                                            @endphp
                                            <tr class="product-row hover:bg-gray-50/50 transition-colors {{ $hasDetail ? 'bg-blue-50/10' : '' }}"
                                                data-nama="{{ strtolower($produk->nama_produk) }}"
                                                data-kategori="{{ strtolower($kategoriName) }}">
                                                <td class="px-4 py-2">
                                                    @if ($produk->foto)
                                                        <img src="{{ asset('storage/' . $produk->foto) }}" class="w-8 h-8 rounded border border-gray-200 object-contain">
                                                    @else
                                                        <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                                            {{ strtoupper(substr($produk->nama_produk, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div class="text-sm font-medium text-gray-900">{{ $produk->nama_produk }}</div>
                                                    <div class="mt-0.5">
                                                        @if ($hasDetail)
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 font-medium">
                                                                Tersimpan: {{ number_format($saldoVal, 2, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-medium">
                                                                Item Baru
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div class="text-xs text-gray-600">{{ $kategoriName }}</div>
                                                    <div class="text-[10px] text-gray-400">{{ $satuanName }}</div>
                                                </td>
                                                <td class="px-4 py-2 text-right">
                                                    <input type="text" 
                                                           name="saldo_awal[{{ $produk->id }}]"
                                                           value="{{ number_format($saldoVal, 2, ',', '.') }}"
                                                           class="w-36 px-2 py-1.5 text-sm border border-gray-200 rounded-md text-right focus:ring-1 focus:ring-blue-500 focus:border-blue-500 saldo-input bg-white font-mono"
                                                           placeholder="0">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="field-wrapper">
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 resize-none"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $saldoAwalProduk->keterangan) }}</textarea>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('saldo-awal-produk.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm disabled:bg-gray-300 disabled:cursor-not-allowed">
                        <i class="ti ti-device-floppy mr-1.5 align-middle"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const $rows = $('.product-row');
            const totalCount = $rows.length;

            // Search and Category Filter
            function filterTable() {
                const search = $('#searchInput').val().toLowerCase().trim();
                const kategori = $('#kategoriFilter').val().toLowerCase().trim();
                let visibleCount = 0;

                $rows.each(function() {
                    const rowNama = $(this).data('nama') || '';
                    const rowKategori = $(this).data('kategori') || '';

                    const matchSearch = search === '' || rowNama.includes(search) || rowKategori.includes(search);
                    const matchKategori = kategori === '' || rowKategori === kategori;

                    if (matchSearch && matchKategori) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                $('#productCounter').text(`Menampilkan: ${visibleCount} / ${totalCount} produk`);
            }

            $('#searchInput').on('input', filterTable);
            $('#kategoriFilter').on('change', filterTable);

            // Number formatting on input
            $('.saldo-input').on('input', function() {
                let v = $(this).val().replace(/[^\d,]/g, '');
                const parts = v.split(',');
                if (parts.length > 2) {
                    v = parts[0] + ',' + parts.slice(1).join('');
                }
                
                if (parts[0]) {
                    let formattedInt = new Intl.NumberFormat('id-ID').format(parts[0].replace(/\./g, ''));
                    $(this).val(parts.length > 1 ? formattedInt + ',' + parts[1] : formattedInt);
                } else {
                    $(this).val(v);
                }
            });

            // Submit Handler
            $('#saldoAwalEditForm').on('submit', function(e) {
                // Pastikan semua input unformatted sebelum submit
                $('.saldo-input').each(function() {
                    let val = $(this).val().trim();
                    if (val === '') {
                        val = '0';
                    } else {
                        val = val.replace(/\./g, '').replace(/,/g, '.');
                    }
                    $(this).val(val);
                });

                $('#submitBtn').prop('disabled', true).html('<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...');
            });
        });
    </script>
    @endpush
@endsection
