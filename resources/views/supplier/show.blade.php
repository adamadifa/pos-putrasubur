@extends('layouts.pos')

@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-check text-xl text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto">
                        <button type="button" class="text-green-600 hover:text-green-800"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="ti ti-x text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="xl:col-span-3 space-y-8">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
                            <!-- Avatar Section -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-32 h-32 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center shadow-lg">
                                    <i class="ti ti-building-store text-5xl text-blue-600"></i>
                                </div>
                            </div>

                            <!-- Basic Info -->
                            <div class="flex-1 space-y-6">
                                <!-- Name and Status -->
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $supplier->nama }}</h2>

                                    <!-- Status Badge -->
                                    <div class="flex items-center space-x-3 mb-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $supplier->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="ti ti-{{ $supplier->status ? 'check' : 'x' }} text-sm mr-1"></i>
                                            {{ $supplier->status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>

                                    <!-- Contact Info -->
                                    <div class="space-y-4">
                                        @if ($supplier->telepon)
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-blue-100 rounded-lg">
                                                    <i class="ti ti-phone text-blue-600"></i>
                                                </div>
                                                <span class="text-gray-700">{{ $supplier->telepon }}</span>
                                            </div>
                                        @endif

                                        @if ($supplier->email)
                                            <div class="flex items-center space-x-3">
                                                <div class="p-2 bg-green-100 rounded-lg">
                                                    <i class="ti ti-mail text-green-600"></i>
                                                </div>
                                                <span class="text-gray-700">{{ $supplier->email }}</span>
                                            </div>
                                        @endif

                                        @if ($supplier->alamat)
                                            <div class="flex items-start space-x-3">
                                                <div class="p-2 bg-amber-100 rounded-lg mt-0.5">
                                                    <i class="ti ti-map-pin text-amber-600"></i>
                                                </div>
                                                <span class="text-gray-700">{{ $supplier->alamat }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complete Details Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                                <i class="ti ti-building-store text-2xl text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Informasi Lengkap</h3>
                                <p class="text-slate-200 text-sm">Detail lengkap data supplier</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-5">
                                <!-- ID Supplier -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-blue-50 transition-colors">
                                            <i class="ti ti-id text-lg text-slate-600 group-hover:text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">ID Supplier</p>
                                            <p class="text-xl font-semibold text-slate-900">#{{ $supplier->id }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kode Supplier -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-purple-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-purple-50 transition-colors">
                                            <i class="ti ti-code text-lg text-slate-600 group-hover:text-purple-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Kode Supplier</p>
                                            <p class="text-xl font-semibold text-slate-900">
                                                {{ $supplier->kode_supplier }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama Supplier -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-green-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-green-50 transition-colors">
                                            <i class="ti ti-building text-lg text-slate-600 group-hover:text-green-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Nama Supplier</p>
                                            <p class="text-xl font-semibold text-slate-900">{{ $supplier->nama }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-{{ $supplier->status ? 'green' : 'red' }}-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-{{ $supplier->status ? 'green' : 'red' }}-50 transition-colors">
                                            <i
                                                class="ti ti-{{ $supplier->status ? 'check' : 'x' }} text-lg text-slate-600 group-hover:text-{{ $supplier->status ? 'green' : 'red' }}-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Status</p>
                                            <p
                                                class="text-xl font-semibold text-{{ $supplier->status ? 'green' : 'red' }}-600">
                                                {{ $supplier->status ? 'Aktif' : 'Nonaktif' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-5">
                                <!-- Nomor Telepon -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-cyan-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-cyan-50 transition-colors">
                                            <i class="ti ti-phone text-lg text-slate-600 group-hover:text-cyan-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Nomor Telepon</p>
                                            <p class="text-xl font-semibold text-slate-900">
                                                {{ $supplier->telepon ?: 'Tidak tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-emerald-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-emerald-50 transition-colors">
                                            <i class="ti ti-mail text-lg text-slate-600 group-hover:text-emerald-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Email</p>
                                            <p class="text-xl font-semibold text-slate-900">
                                                {{ $supplier->email ?: 'Tidak tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-amber-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start space-x-4">
                                        <div
                                            class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-amber-50 transition-colors mt-1">
                                            <i class="ti ti-map-pin text-lg text-slate-600 group-hover:text-amber-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Alamat</p>
                                            <p class="text-lg font-semibold text-slate-900 leading-relaxed">
                                                {{ $supplier->alamat ?: 'Tidak tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanggal Registrasi -->
                                <div
                                    class="group p-5 bg-white border border-gray-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="p-2.5 bg-slate-100 rounded-lg group-hover:bg-indigo-50 transition-colors">
                                            <i
                                                class="ti ti-calendar-plus text-lg text-slate-600 group-hover:text-indigo-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-600 mb-1">Tanggal Registrasi</p>
                                            <p class="text-xl font-semibold text-slate-900">
                                                {{ $supplier->created_at ? $supplier->created_at->format('d M Y') : 'Tidak diketahui' }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ $supplier->created_at ? $supplier->created_at->format('H:i') : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info Bar -->
                        <div class="mt-6 p-5 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-slate-200 rounded-lg">
                                        <i class="ti ti-info-circle text-slate-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-600">Supplier terdaftar sejak</p>
                                        <p class="text-lg font-semibold text-slate-900">
                                            {{ $supplier->created_at ? $supplier->created_at->diffForHumans() : 'Tidak diketahui' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-slate-600">Status terakhir</p>
                                    <p
                                        class="text-lg font-semibold {{ $supplier->status ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $supplier->status ? 'Aktif' : 'Nonaktif' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase History Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800">Riwayat Pembelian</h3>
                            <span class="text-sm text-gray-500">{{ $supplier->pembelian->count() }} pembelian</span>
                        </div>
                    </div>
                    <div class="p-8">
                        @if ($supplier->pembelian->count() > 0)
                            <div class="space-y-6">
                                @foreach ($supplier->pembelian()->orderBy('tanggal', 'desc')->get() as $pembelian)
                                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                                        <div
                                            class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                            <!-- Purchase Info -->
                                            <div class="flex-1 space-y-3">
                                                <div class="flex items-center space-x-3">
                                                    <div class="p-2 bg-orange-100 rounded-lg">
                                                        <i class="ti ti-shopping-cart text-orange-600"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">
                                                            {{ $pembelian->no_faktur }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500">
                                                            {{ $pembelian->tanggal->format('d M Y') }} •
                                                            {{ $pembelian->user->name ?? 'User tidak diketahui' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Product Summary -->
                                                @if ($pembelian->detailPembelian->count() > 0)
                                                    <div class="ml-12">
                                                        <p class="text-sm text-gray-600">
                                                            {{ $pembelian->detailPembelian->count() }} produk •
                                                            Total: Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Status and Amount -->
                                            <div class="flex flex-col items-end space-y-2">
                                                <span class="text-2xl font-bold text-gray-900">
                                                    Rp {{ number_format($pembelian->total_setelah_diskon, 0, ',', '.') }}
                                                </span>

                                                @if ($pembelian->diskon > 0)
                                                    <span class="text-sm text-gray-500 line-through">
                                                        Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                                                    </span>
                                                @endif

                                                <div class="flex items-center space-x-2">
                                                    @php
                                                        $statusColors = [
                                                            'lunas' => 'bg-green-100 text-green-800',
                                                            'dp' => 'bg-blue-100 text-blue-800',
                                                            'angsuran' => 'bg-yellow-100 text-yellow-800',
                                                            'belum_bayar' => 'bg-red-100 text-red-800',
                                                        ];
                                                        $statusColor =
                                                            $statusColors[$pembelian->status_pembayaran] ??
                                                            'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                        {{ $pembelian->status_pembayaran_display }}
                                                    </span>
                                                </div>

                                                @if ($pembelian->status_pembayaran !== 'lunas')
                                                    <div class="text-right">
                                                        <p class="text-xs text-gray-500">Dibayar</p>
                                                        <p class="text-sm font-semibold text-green-600">
                                                            Rp {{ number_format($pembelian->total_dibayar, 0, ',', '.') }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">Sisa</p>
                                                        <p class="text-sm font-semibold text-red-600">
                                                            Rp
                                                            {{ number_format($pembelian->sisa_pembayaran, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Payment Details -->
                                        @if ($pembelian->pembayaranPembelian->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-gray-100">
                                                <p class="text-sm font-medium text-gray-700 mb-2">Detail Pembayaran:</p>
                                                <div class="space-y-2">
                                                    @foreach ($pembelian->pembayaranPembelian as $pembayaran)
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-600">
                                                                {{ $pembayaran->tanggal->format('d/m/Y H:i') }} •
                                                                {{ $pembayaran->metode_pembayaran }} •
                                                                {{ $pembayaran->status_bayar_display }}
                                                            </span>
                                                            <span class="font-medium text-gray-900">
                                                                Rp
                                                                {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ti ti-shopping-cart text-2xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pembelian</h4>
                                <p class="text-gray-500">Supplier ini belum memiliki riwayat pembelian.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('supplier.edit', $supplier->encrypted_id) }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="ti ti-edit text-lg mr-2"></i>
                            Edit Supplier
                        </a>

                        <button type="button"
                            onclick="confirmDelete('{{ $supplier->encrypted_id }}', '{{ $supplier->nama }}')"
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                            <i class="ti ti-trash text-lg mr-2"></i>
                            Hapus Supplier
                        </button>

                        <a href="{{ route('supplier.index') }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors">
                            <i class="ti ti-arrow-left text-lg mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Purchase Summary -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-orange-800 mb-4">Ringkasan Pembelian</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-orange-700">Total Pembelian</span>
                            <span class="text-lg font-bold text-orange-800">{{ $supplier->total_pembelian }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-orange-700">Total Nilai</span>
                            <span class="text-lg font-bold text-orange-800">
                                Rp {{ number_format($supplier->total_nilai_pembelian, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-orange-700">Rata-rata</span>
                            <span class="text-lg font-bold text-orange-800">
                                Rp {{ number_format($supplier->rata_rata_pembelian, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-orange-700">Status</span>
                            <span class="text-lg font-bold {{ $supplier->status ? 'text-green-600' : 'text-red-600' }}">
                                {{ $supplier->status ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-orange-700">Terdaftar</span>
                            <span class="text-sm font-bold text-orange-800">
                                {{ $supplier->created_at ? $supplier->created_at->diffForHumans() : 'Tidak diketahui' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Debt -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-red-800 mb-4">Sisa Hutang</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-red-700">Total Hutang</span>
                            <span class="text-lg font-bold text-red-800">
                                Rp {{ number_format($supplier->total_hutang, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-red-700">Sudah Dibayar</span>
                            <span class="text-lg font-bold text-green-600">
                                Rp {{ number_format($supplier->total_dibayar, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-red-700">Sisa Hutang</span>
                            <span class="text-lg font-bold text-red-600">
                                Rp {{ number_format($supplier->sisa_hutang, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-red-700">Transaksi Belum Lunas</span>
                            <span class="text-lg font-bold text-red-800">
                                {{ $supplier->pembelian_belum_lunas }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom SweetAlert Styling */
        .swal2-popup {
            border-radius: 16px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-title {
            color: #1f2937 !important;
            font-weight: 700 !important;
        }

        .swal2-confirm {
            background: #dc2626 !important;
            border-radius: 8px !important;
        }

        .swal2-cancel {
            background: #3b82f6 !important;
            border-radius: 8px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(supplierId, supplierName) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus supplier "${supplierName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('supplier.destroy', '') }}/${supplierId}`;

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
