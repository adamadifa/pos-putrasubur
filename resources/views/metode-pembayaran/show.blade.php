@extends('layouts.pos')

@section('title', 'Detail Metode Pembayaran')
@section('page-title', 'Detail Metode Pembayaran')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow border">
            <div class="px-6 py-4 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('metode-pembayaran.index') }}"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $metodePembayaran->nama }}</h1>
                            <p class="text-sm text-gray-500">Detail metode pembayaran</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('metode-pembayaran.edit', $metodePembayaran->encrypted_id) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="ti ti-edit mr-2"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Informasi Dasar</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Kode</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $metodePembayaran->kode }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $metodePembayaran->nama }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                <div class="mt-1">
                                    @if ($metodePembayaran->status)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="ti ti-check mr-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="ti ti-x mr-1"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Urutan</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $metodePembayaran->urutan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if ($metodePembayaran->deskripsi)
                    <div class="bg-white rounded-lg shadow border">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="font-semibold text-gray-900">Deskripsi</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 leading-relaxed">{{ $metodePembayaran->deskripsi }}</p>
                        </div>
                    </div>
                @endif

                <!-- Usage Statistics -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Statistik Penggunaan</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ \App\Models\PembayaranPenjualan::where('metode_pembayaran', $metodePembayaran->kode)->count() }}
                                </div>
                                <div class="text-sm text-gray-600">Total Penggunaan</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">
                                    Rp
                                    {{ number_format(\App\Models\PembayaranPenjualan::where('metode_pembayaran', $metodePembayaran->kode)->sum('jumlah_bayar'), 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-600">Total Nilai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Icon Preview -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Icon</h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-20 h-20 mx-auto bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <i class="ti {{ $metodePembayaran->icon_display }} text-blue-600 text-3xl"></i>
                        </div>
                        <p class="text-sm text-gray-600">{{ $metodePembayaran->icon ?: 'ti-credit-card' }}</p>
                    </div>
                </div>



                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('metode-pembayaran.edit', $metodePembayaran->encrypted_id) }}"
                            class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="ti ti-edit mr-2"></i>
                            Edit Metode
                        </a>
                        <button
                            onclick="confirmDelete('{{ $metodePembayaran->encrypted_id }}', '{{ $metodePembayaran->nama }}')"
                            class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="ti ti-trash mr-2"></i>
                            Hapus Metode
                        </button>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Informasi Sistem</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Dibuat</label>
                            <p class="text-sm text-gray-900">{{ $metodePembayaran->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
                            <p class="text-sm text-gray-900">{{ $metodePembayaran->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus metode pembayaran "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/metode-pembayaran/${id}`;

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
@endsection
