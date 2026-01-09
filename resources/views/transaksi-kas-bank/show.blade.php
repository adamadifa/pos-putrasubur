@extends('layouts.pos')

@section('title', 'Detail Transaksi Kas & Bank')
@section('page-title', 'Detail Transaksi Kas & Bank')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('transaksi-kas-bank.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Detail Transaksi</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-gray-500 font-mono">{{ $transaksiKasBank->no_bukti }}</span>
                        <span class="text-xs text-gray-300">•</span>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaksiKasBank->tanggal)->format('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                @if ($transaksiKasBank->referensi_tipe == 'MN')
                    <a href="{{ route('transaksi-kas-bank.edit', $transaksiKasBank->id) }}"
                        class="px-3 py-1.5 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg text-sm font-medium transition-colors border border-yellow-200">
                        <i class="ti ti-edit mr-1.5"></i>Edit
                    </a>
                    
                    <button type="button" onclick="confirmDelete('{{ $transaksiKasBank->id }}')"
                        class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors border border-red-200">
                        <i class="ti ti-trash mr-1.5"></i>Hapus
                    </button>

                    <form id="delete-form-{{ $transaksiKasBank->id }}" 
                        action="{{ route('transaksi-kas-bank.destroy', $transaksiKasBank->id) }}" 
                        method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Transaction Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Informasi Transaksi</h3>
                        
                        <div class="space-y-4">
                            <!-- Large Amount Display -->
                            <div class="text-center py-4 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="block text-xs text-gray-500 mb-1">Total Nominal</span>
                                <span class="text-3xl font-bold {{ $transaksiKasBank->jenis_transaksi == 'D' ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($transaksiKasBank->jumlah, 0, ',', '.') }}
                                </span>
                                <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaksiKasBank->jenis_transaksi == 'D' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($transaksiKasBank->jenis_transaksi == 'D')
                                        <i class="ti ti-arrow-down mr-1"></i>Pemasukan (Debit)
                                    @else
                                        <i class="ti ti-arrow-up mr-1"></i>Pengeluaran (Kredit)
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-3 bg-white border border-gray-200 rounded-lg">
                                    <label class="block text-xs text-gray-500 mb-1">Kas/Bank</label>
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-blue-50 rounded-md">
                                            <i class="ti ti-building-bank text-blue-600"></i>
                                        </div>
                                        <div class="font-medium text-gray-900">{{ $transaksiKasBank->kasBank->nama }}</div>
                                    </div>
                                </div>

                                <div class="p-3 bg-white border border-gray-200 rounded-lg">
                                    <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-purple-50 rounded-md">
                                            <i class="ti ti-category text-purple-600"></i>
                                        </div>
                                        <div class="font-medium text-gray-900">
                                            @php
                                                $cats = ['PJ' => 'Penjualan', 'PB' => 'Pembelian', 'MN' => 'Manual', 'TF' => 'Transfer'];
                                            @endphp
                                            {{ $cats[$transaksiKasBank->kategori_transaksi] ?? 'Lainnya' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan</label>
                                <p class="text-sm text-gray-600 italic">
                                    "{{ $transaksiKasBank->keterangan ?? 'Tidak ada keterangan' }}"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Meta Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4">Metadata</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-xs text-gray-500">Tipe Input</span>
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $transaksiKasBank->referensi_tipe == 'MN' ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-orange-700' }}">
                                    {{ $transaksiKasBank->referensi_tipe == 'MN' ? 'Manual' : 'Otomatis' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-xs text-gray-500">Dibuat</span>
                                <span class="text-xs font-medium text-gray-700">{{ $transaksiKasBank->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                <span class="text-xs text-gray-500">Update Terakhir</span>
                                <span class="text-xs font-medium text-gray-700">{{ $transaksiKasBank->updated_at->format('d/m/Y H:i') }}</span>
                            </div>

                            @if($transaksiKasBank->referensi_id)
                                <div class="pt-2">
                                    <span class="block text-xs text-gray-500 mb-1">ID Referensi</span>
                                    <code class="block w-full px-2 py-1 bg-gray-100 rounded text-xs text-gray-600 font-mono break-all">
                                        {{ $transaksiKasBank->referensi_id }}
                                    </code>
                                </div>
                            @endif
                        </div>

                        @if ($transaksiKasBank->referensi_tipe != 'MN')
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-2">
                                <i class="ti ti-lock text-yellow-600 mt-0.5"></i>
                                <p class="text-xs text-yellow-800 leading-relaxed">
                                    Transaksi ini dibuat otomatis oleh sistem. Edit/Hapus hanya bisa dilakukan melalui modul asalnya.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
