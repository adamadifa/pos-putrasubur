@extends('layouts.pos')

@section('title', 'Detail Produk - ' . $produk->nama_produk)
@section('page-title', 'Detail Produk')

@section('content')
    <div class="space-y-6">
        <!-- Hero Header -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-white to-blue-50 rounded-2xl p-8 border border-primary-100/50">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                                <i class="ti ti-package text-2xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $produk->nama_produk }}</h1>
                                <p class="text-lg text-gray-600">{{ $produk->kode_produk }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 mb-6">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $produk->status_stok === 'tersedia' ? 'bg-green-100 text-green-800 border border-green-200' : ($produk->status_stok === 'menipis' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-red-100 text-red-800 border border-red-200') }}">
                                <i
                                    class="ti {{ $produk->status_stok === 'tersedia' ? 'ti-check' : ($produk->status_stok === 'menipis' ? 'ti-alert-triangle' : 'ti-x') }} text-sm mr-2"></i>
                                {{ ucfirst($produk->status_stok) }}
                            </span>

                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="ti ti-tag text-sm mr-2"></i>
                                {{ $produk->kategori->nama }}
                            </span>

                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                <i class="ti ti-ruler text-sm mr-2"></i>
                                {{ $produk->satuan->nama }}
                            </span>
                        </div>

                        <p class="text-gray-600 max-w-2xl">{{ $produk->deskripsi ?? 'Tidak ada deskripsi produk.' }}</p>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('produk.index') }}"
                            class="inline-flex items-center px-4 py-2 text-gray-700 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl hover:bg-white hover:shadow-lg transition-all duration-200">
                            <i class="ti ti-arrow-left text-lg mr-2"></i>
                            Kembali
                        </a>
                        <a href="{{ route('produk.edit', $produk->id) }}"
                            class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="ti ti-edit text-lg mr-2"></i>
                            Edit Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
            <!-- Main Content - Left Side -->
            <div class="xl:col-span-3 space-y-6">
                <!-- Product Image & Basic Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                        <!-- Product Image -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 flex items-center justify-center">
                            @if ($produk->foto)
                                <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}"
                                    class="w-full h-80 object-cover rounded-xl shadow-lg">
                            @else
                                <div class="text-center">
                                    <div
                                        class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i class="ti ti-photo text-4xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Tidak ada gambar produk</p>
                                </div>
                            @endif
                        </div>

                        <!-- Basic Info -->
                        <div class="p-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Informasi Dasar</h3>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Kode Produk</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $produk->kode_produk }}</span>
                                </div>

                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Kategori</span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $produk->kategori->nama }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Satuan</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $produk->satuan->nama }}</span>
                                </div>

                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Dibuat</span>
                                    <span
                                        class="text-sm text-gray-500">{{ $produk->created_at->format('d M Y H:i') }}</span>
                                </div>

                                <div class="flex items-center justify-between py-3">
                                    <span class="text-sm font-medium text-gray-600">Terakhir Update</span>
                                    <span
                                        class="text-sm text-gray-500">{{ $produk->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Overview -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="ti ti-chart-line text-xl text-primary-600 mr-3"></i>
                        Overview Keuangan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-tag text-2xl text-white"></i>
                            </div>
                            <div class="text-3xl font-bold text-green-600 mb-2">
                                {{ number_format($produk->harga, 0, ',', '.') }}</div>
                            <div class="text-sm font-medium text-green-700">Harga Jual</div>
                        </div>

                        <div
                            class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-info-circle text-2xl text-white"></i>
                            </div>
                            <div class="text-3xl font-bold text-blue-600 mb-2">
                                {{ $produk->status_stok === 'tersedia' ? 'Tersedia' : ($produk->status_stok === 'menipis' ? 'Menipis' : 'Habis') }}
                            </div>
                            <div class="text-sm font-medium text-blue-700">Status Stok</div>
                            <div class="text-xs text-blue-600 mt-1">{{ number_format($produk->stok, 2) }}
                                {{ $produk->satuan->nama }}</div>
                        </div>
                    </div>
                </div>

                <!-- Stock Management -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="ti ti-box text-xl text-primary-600 mr-3"></i>
                        Manajemen Stok
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div
                            class="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-box text-2xl text-white"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($produk->stok, 2) }}</div>
                            <div class="text-sm font-medium text-gray-700">Stok Saat Ini</div>
                        </div>

                        <div
                            class="text-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-alert-triangle text-2xl text-white"></i>
                            </div>
                            <div class="text-3xl font-bold text-yellow-600 mb-2">
                                {{ number_format($produk->stok_minimal, 2) }}</div>
                            <div class="text-sm font-medium text-yellow-700">Stok Minimum</div>
                        </div>

                        <div
                            class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-archive text-2xl text-white"></i>
                            </div>
                            <div class="text-3xl font-bold text-purple-600 mb-2">
                                {{ number_format($produk->stok_awal ?? 0, 2) }}</div>
                            <div class="text-sm font-medium text-purple-700">Stok Awal</div>
                        </div>
                    </div>

                    <!-- Stock Status Alert -->
                    @if ($produk->stok <= $produk->stok_minimal)
                        <div class="p-4 bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="ti ti-alert-circle text-xl text-red-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-semibold text-red-800">Stok Menipis!</div>
                                    <div class="text-sm text-red-600">Stok saat ini ({{ number_format($produk->stok, 2) }})
                                        sudah di bawah batas minimum ({{ number_format($produk->stok_minimal, 2) }}).
                                        Segera tambah stok!</div>
                                </div>
                            </div>
                        </div>
                    @elseif ($produk->stok <= $produk->stok_minimal * 2)
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="ti ti-alert-triangle text-xl text-yellow-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-semibold text-yellow-800">Stok Perlu Diperhatikan</div>
                                    <div class="text-sm text-yellow-600">Stok saat ini
                                        ({{ number_format($produk->stok, 2) }}) mendekati batas minimum.</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl">
                            <div class="flex items-center">
                                <i class="ti ti-check-circle text-xl text-green-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-semibold text-green-800">Stok Aman</div>
                                    <div class="text-sm text-green-600">Stok saat ini
                                        ({{ number_format($produk->stok, 2) }}) masih di atas batas minimum.</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sales Analytics -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="ti ti-chart-bar text-xl text-primary-600 mr-3"></i>
                        Analisis Penjualan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center p-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="ti ti-calendar text-xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">0</div>
                            <div class="text-sm text-gray-600">Terjual Hari Ini</div>
                        </div>

                        <div class="text-center p-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="ti ti-calendar-stats text-xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 mb-1">0</div>
                            <div class="text-sm text-gray-600">Terjual Bulan Ini</div>
                        </div>

                        <div class="text-center p-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="ti ti-currency text-xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-green-600 mb-1">Rp 0</div>
                            <div class="text-sm text-gray-600">Omzet Hari Ini</div>
                        </div>

                        <div class="text-center p-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="ti ti-chart-pie text-xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-green-600 mb-1">Rp 0</div>
                            <div class="text-sm text-gray-600">Omzet Bulan Ini</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="ti ti-receipt text-xl text-primary-600 mr-3"></i>
                            Transaksi Terbaru
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-receipt-off text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-500 mb-2">Belum ada transaksi</p>
                            <p class="text-sm text-gray-400">Produk ini belum pernah dijual</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Right Side -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ti ti-bolt text-lg text-primary-600 mr-2"></i>
                        Aksi Cepat
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('produk.edit', $produk->id) }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="ti ti-edit text-lg mr-2"></i>
                            Edit Produk
                        </a>

                        <button
                            class="w-full flex items-center justify-center px-4 py-3 border-2 border-gray-200 text-gray-700 rounded-xl hover:border-primary-300 hover:bg-primary-50 hover:text-primary-700 transition-all duration-200">
                            <i class="ti ti-plus text-lg mr-2"></i>
                            Tambah Stok
                        </button>

                        <button
                            class="w-full flex items-center justify-center px-4 py-3 border-2 border-gray-200 text-gray-700 rounded-xl hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <i class="ti ti-copy text-lg mr-2"></i>
                            Duplikasi
                        </button>

                        <button onclick="confirmDelete({{ $produk->id }}, '{{ $produk->nama_produk }}')"
                            class="w-full flex items-center justify-center px-4 py-3 border-2 border-red-200 text-red-700 rounded-xl hover:border-red-300 hover:bg-red-50 transition-all duration-200">
                            <i class="ti ti-trash text-lg mr-2"></i>
                            Hapus Produk
                        </button>
                    </div>
                </div>

                <!-- Product Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ti ti-settings text-lg text-primary-600 mr-2"></i>
                        Pengaturan
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                            <div>
                                <div class="text-sm font-medium text-green-800">Status Aktif</div>
                                <div class="text-xs text-green-600">Produk dapat dijual</div>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                <i class="ti ti-check text-xs mr-1"></i>
                                Aktif
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div>
                                <div class="text-sm font-medium text-blue-800">Lacak Stok</div>
                                <div class="text-xs text-blue-600">Otomatis kurangi stok</div>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="ti ti-check text-xs mr-1"></i>
                                Ya
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <div>
                                <div class="text-sm font-medium text-purple-800">Boleh Diskon</div>
                                <div class="text-xs text-purple-600">Dapat diberi diskon</div>
                            </div>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                <i class="ti ti-check text-xs mr-1"></i>
                                Ya
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="ti ti-timeline text-lg text-primary-600 mr-2"></i>
                        Timeline Aktivitas
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">Produk dibuat</div>
                                <div class="text-xs text-gray-500">{{ $produk->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">Terakhir diperbarui</div>
                                <div class="text-xs text-gray-500">{{ $produk->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-gray-400 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">Belum ada transaksi</div>
                                <div class="text-xs text-gray-500">Produk baru</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-grid-pattern {
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Confirm delete function with SweetAlert
        function confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus produk "${productName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal2-confirm-delete',
                    cancelButton: 'swal2-cancel-delete'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('produk.destroy', '') }}/${productId}`;

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
