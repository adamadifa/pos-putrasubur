@extends('layouts.pos')

@section('title', 'Detail Penjualan')
@section('page-title', 'Detail Transaksi Penjualan')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8">
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
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button"
                                class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                                onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <span class="sr-only">Dismiss</span>
                                <i class="ti ti-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('penjualan.index') }}"
                            class="group p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                                <i class="ti ti-receipt text-2xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">{{ $penjualan->no_faktur }}</h1>
                                <p class="text-slate-200 text-sm">Detail Transaksi Penjualan</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @php
                            $statusConfig = [
                                'lunas' => [
                                    'bg' => 'bg-green-500',
                                    'text' => 'text-white',
                                    'icon' => 'ti-check-circle',
                                    'label' => 'Lunas',
                                ],
                                'dp' => [
                                    'bg' => 'bg-blue-500',
                                    'text' => 'text-white',
                                    'icon' => 'ti-clock',
                                    'label' => 'DP',
                                ],
                                'angsuran' => [
                                    'bg' => 'bg-yellow-500',
                                    'text' => 'text-white',
                                    'icon' => 'ti-clock-hour-4',
                                    'label' => 'Angsuran',
                                ],
                                'belum_bayar' => [
                                    'bg' => 'bg-red-500',
                                    'text' => 'text-white',
                                    'icon' => 'ti-x-circle',
                                    'label' => 'Belum Bayar',
                                ],
                            ];
                            $config = $statusConfig[$penjualan->status_pembayaran] ?? $statusConfig['belum_bayar'];
                        @endphp
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="ti {{ $config['icon'] }} text-lg mr-2"></i>
                            {{ $config['label'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="xl:grid xl:grid-cols-4 xl:gap-8 space-y-8 xl:space-y-0">
            <!-- Main Content Area -->
            <div class="xl:col-span-3 space-y-8">
                <!-- Transaction Info Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-blue-500 rounded-xl">
                                <i class="ti ti-info-circle text-xl text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Informasi Transaksi</h3>
                                <p class="text-gray-600 text-sm">Detail lengkap transaksi penjualan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Invoice Number -->
                                <div
                                    class="p-5 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-blue-100 rounded-xl">
                                            <i class="ti ti-receipt text-xl text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-600 mb-1">Nomor Faktur</p>
                                            <p class="text-xl font-bold text-gray-900">{{ $penjualan->no_faktur }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div
                                    class="p-5 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-green-100 rounded-xl">
                                            <i class="ti ti-calendar text-xl text-green-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-green-600 mb-1">Tanggal Transaksi</p>
                                            <p class="text-xl font-bold text-gray-900">
                                                {{ $penjualan->tanggal->format('d M Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ $penjualan->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div
                                    class="p-5 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-purple-100 rounded-xl">
                                            <i class="ti ti-user text-xl text-purple-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-purple-600 mb-1">Pelanggan</p>
                                            <p class="text-xl font-bold text-gray-900">
                                                {{ $penjualan->pelanggan->nama ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $penjualan->pelanggan->kode_pelanggan ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Cashier -->
                                <div
                                    class="p-5 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-orange-100 rounded-xl">
                                            <i class="ti ti-user-check text-xl text-orange-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-orange-600 mb-1">Kasir</p>
                                            <p class="text-xl font-bold text-gray-900">
                                                {{ $penjualan->kasir->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $penjualan->kasir->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Due Date -->
                                @if ($penjualan->jatuh_tempo)
                                    <div
                                        class="p-5 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-red-100 rounded-xl">
                                                <i class="ti ti-calendar-due text-xl text-red-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-red-600 mb-1">Jatuh Tempo</p>
                                                <p class="text-xl font-bold text-gray-900">
                                                    {{ $penjualan->jatuh_tempo->format('d M Y') }}</p>
                                                @php
                                                    $daysLeft = now()->diffInDays($penjualan->jatuh_tempo, false);
                                                @endphp
                                                @if ($daysLeft < 0)
                                                    <p class="text-sm text-red-500">Terlambat {{ abs($daysLeft) }} hari</p>
                                                @elseif($daysLeft == 0)
                                                    <p class="text-sm text-yellow-500">Hari ini</p>
                                                @else
                                                    <p class="text-sm text-green-500">{{ $daysLeft }} hari lagi</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Items Count -->
                                <div
                                    class="p-5 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-cyan-100 rounded-xl">
                                            <i class="ti ti-shopping-bag text-xl text-cyan-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-cyan-600 mb-1">Total Item</p>
                                            <p class="text-xl font-bold text-gray-900">
                                                {{ $penjualan->detailPenjualan->count() }} produk</p>
                                            <p class="text-sm text-gray-500">{{ $penjualan->detailPenjualan->sum('qty') }}
                                                qty</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Detail Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-8 py-6 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-emerald-500 rounded-xl">
                                <i class="ti ti-package text-xl text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Detail Produk</h3>
                                <p class="text-gray-600 text-sm">Daftar produk yang dibeli</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Produk</th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Qty</th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Harga</th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($penjualan->detailPenjualan as $detail)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                                            <i class="ti ti-package text-white text-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $detail->produk->nama_produk ?? 'N/A' }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $detail->produk->kode_produk ?? 'N/A' }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $detail->produk->kategori->nama ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    {{ $detail->qty }} {{ $detail->produk->satuan->nama ?? '' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <p class="text-sm font-semibold text-gray-900">Rp
                                                    {{ number_format($detail->harga, 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <p class="text-sm font-bold text-gray-900">Rp
                                                    {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment History Card -->
                @if ($penjualan->pembayaranPenjualan->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-8 py-6 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-3 bg-purple-500 rounded-xl">
                                    <i class="ti ti-credit-card text-xl text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Riwayat Pembayaran</h3>
                                    <p class="text-gray-600 text-sm">Detail pembayaran yang telah dilakukan</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="space-y-4">
                                @foreach ($penjualan->pembayaranPenjualan as $pembayaran)
                                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="p-3 bg-green-100 rounded-xl">
                                                    <i class="ti ti-cash text-xl text-green-600"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">
                                                        {{ $pembayaran->no_bukti }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $pembayaran->tanggal->format('d M Y H:i') }}</p>
                                                    <p class="text-xs text-gray-500">{{ $pembayaran->metode_pembayaran }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-900">Rp
                                                    {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $pembayaran->status_bayar_display }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($pembayaran->keterangan)
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <p class="text-sm text-gray-600">{{ $pembayaran->keterangan }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="xl:col-span-1 space-y-8">
                <!-- Financial Summary Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="ti ti-calculator text-xl mr-2 text-indigo-600"></i>
                            Ringkasan Keuangan
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Subtotal -->
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Subtotal</span>
                            <span class="text-sm font-semibold text-gray-900">Rp
                                {{ number_format($penjualan->detailPenjualan->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>

                        <!-- Discount -->
                        @if ($penjualan->diskon > 0)
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">Diskon</span>
                                <span class="text-sm font-semibold text-red-600">-Rp
                                    {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <!-- Total -->
                        <div class="flex justify-between items-center py-3 border-t border-gray-200">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900">Rp
                                {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                        </div>

                        <!-- Payment Info -->
                        <div class="space-y-2 pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Dibayar</span>
                                <span class="text-sm font-semibold text-green-600">Rp
                                    {{ number_format($penjualan->total_dibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Sisa</span>
                                <span
                                    class="text-sm font-semibold {{ $penjualan->sisa_pembayaran > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format($penjualan->sisa_pembayaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="ti ti-settings text-xl mr-2 text-gray-600"></i>
                            Aksi
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Print Button -->
                        <button onclick="window.print()"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors">
                            <i class="ti ti-printer text-lg mr-2"></i>
                            Cetak Invoice
                        </button>

                        <!-- Edit Button -->
                        @if ($penjualan->status_pembayaran !== 'lunas')
                            <a href="{{ route('penjualan.edit', $penjualan->encrypted_id) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="ti ti-edit text-lg mr-2"></i>
                                Edit Transaksi
                            </a>
                        @endif

                        <!-- Payment Button -->
                        @if ($penjualan->sisa_pembayaran > 0)
                            <button type="button"
                                class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                <i class="ti ti-cash text-lg mr-2"></i>
                                Tambah Pembayaran
                            </button>
                        @endif

                        <!-- Delete Button -->
                        @if ($penjualan->pembayaranPenjualan->count() == 0)
                            <button type="button"
                                onclick="confirmDelete('{{ $penjualan->encrypted_id }}', '{{ $penjualan->no_faktur }}')"
                                class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                                <i class="ti ti-trash text-lg mr-2"></i>
                                Hapus Transaksi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .print-section,
            .print-section * {
                visibility: visible;
            }

            .print-section {
                position: absolute;
                left: 0;
                top: 0;
            }
        }

        /* Custom SweetAlert Styling */
        .swal2-popup {
            border-radius: 16px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-title {
            color: #1f2937 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }

        .swal2-content {
            color: #6b7280 !important;
            font-size: 1rem !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            font-size: 0.95rem !important;
        }

        .swal2-cancel {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            font-size: 0.95rem !important;
        }

        .swal2-actions {
            gap: 1rem !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(salesId, invoiceNumber) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus transaksi "${invoiceNumber}"?`,
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
                    form.action = `{{ route('penjualan.destroy', '') }}/${salesId}`;

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

