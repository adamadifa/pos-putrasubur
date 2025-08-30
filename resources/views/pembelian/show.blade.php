@extends('layouts.pos')

@section('title', 'Detail Pembelian')
@section('page-title', 'Detail Transaksi Pembelian')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="ti ti-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    <button type="button" class="ml-auto text-green-500 hover:text-green-700"
                        onclick="this.parentElement.parentElement.remove()">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-lg shadow border">
            <div class="px-6 py-4 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pembelian.index') }}"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $pembelian->no_faktur }}</h1>
                            <p class="text-sm text-gray-500">{{ $pembelian->tanggal->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @php
                        $statusConfig = [
                            'lunas' => [
                                'bg' => 'bg-green-100',
                                'text' => 'text-green-800',
                                'icon' => 'ti-check-circle',
                                'label' => 'Lunas',
                            ],
                            'dp' => [
                                'bg' => 'bg-blue-100',
                                'text' => 'text-blue-800',
                                'icon' => 'ti-clock',
                                'label' => 'DP',
                            ],
                            'angsuran' => [
                                'bg' => 'bg-yellow-100',
                                'text' => 'text-yellow-800',
                                'icon' => 'ti-clock-hour-4',
                                'label' => 'Angsuran',
                            ],
                            'belum_bayar' => [
                                'bg' => 'bg-red-100',
                                'text' => 'text-red-800',
                                'icon' => 'ti-x-circle',
                                'label' => 'Belum Bayar',
                            ],
                        ];
                        $config = $statusConfig[$pembelian->status_pembayaran] ?? $statusConfig['belum_bayar'];
                    @endphp
                    <div class="flex items-center space-x-3">
                        <!-- Status Pembayaran -->
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="ti {{ $config['icon'] }} mr-1"></i>
                            {{ $config['label'] }}
                        </span>

                        <!-- Jenis Transaksi -->
                        @php
                            $jenisConfig = [
                                'tunai' => [
                                    'bg' => 'bg-green-100',
                                    'text' => 'text-green-800',
                                    'icon' => 'ti-cash',
                                    'label' => 'Tunai',
                                ],
                                'kredit' => [
                                    'bg' => 'bg-purple-100',
                                    'text' => 'text-purple-800',
                                    'icon' => 'ti-credit-card',
                                    'label' => 'Kredit',
                                ],
                            ];
                            $jenisTransaksiConfig = $jenisConfig[$pembelian->jenis_transaksi] ?? $jenisConfig['tunai'];
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }}">
                            <i class="ti {{ $jenisTransaksiConfig['icon'] }} mr-1"></i>
                            {{ $jenisTransaksiConfig['label'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Supplier Info -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-orange-50 to-red-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-building-store text-orange-600"></i>
                            </div>
                            Informasi Supplier
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Supplier Profile Section -->
                        <div class="flex items-start space-x-6 mb-6 pb-6 border-b border-gray-100">
                            <!-- Supplier Photo -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center shadow-md">
                                    <i class="ti ti-building-store text-3xl text-white"></i>
                                </div>
                            </div>

                            <!-- Supplier Basic Info -->
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $pembelian->supplier->nama ?? 'N/A' }}
                                </h4>
                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                    <i class="ti ti-id-badge mr-1"></i>
                                    {{ $pembelian->supplier->kode_supplier ?? 'N/A' }}
                                </div>
                                @if ($pembelian->supplier->telepon)
                                    <div class="flex items-center text-sm text-gray-600 mb-1">
                                        <i class="ti ti-phone text-green-600 mr-2"></i>
                                        <a href="tel:{{ $pembelian->supplier->telepon }}" class="hover:text-orange-600">
                                            {{ $pembelian->supplier->telepon }}
                                        </a>
                                    </div>
                                @endif
                                @if ($pembelian->supplier->email)
                                    <div class="flex items-center text-sm text-gray-600 mb-1">
                                        <i class="ti ti-mail text-blue-600 mr-2"></i>
                                        <a href="mailto:{{ $pembelian->supplier->email }}" class="hover:text-orange-600">
                                            {{ $pembelian->supplier->email }}
                                        </a>
                                    </div>
                                @endif
                                @if ($pembelian->supplier->alamat)
                                    <div class="flex items-start text-sm text-gray-600">
                                        <i class="ti ti-map-pin text-red-500 mr-2 mt-0.5"></i>
                                        <span>{{ $pembelian->supplier->alamat }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-user-check text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Dibuat Oleh</p>
                                    <p class="font-semibold text-gray-900">{{ $pembelian->user->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-calendar-event text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Tanggal Pembelian</p>
                                    <p class="font-semibold text-gray-900">{{ $pembelian->tanggal->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-receipt text-cyan-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">No. Faktur</p>
                                    <p class="font-semibold text-gray-900">{{ $pembelian->no_faktur }}</p>
                                </div>
                            </div>

                            @if ($pembelian->keterangan)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="ti ti-note text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Keterangan</p>
                                        <p class="font-semibold text-gray-900">{{ $pembelian->keterangan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Products Detail -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-shopping-cart text-green-600"></i>
                            </div>
                            Detail Produk ({{ $pembelian->detailPembelian->count() }} item)
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($pembelian->detailPembelian as $detail)
                                <div
                                    class="flex items-center space-x-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
                                    <!-- Product Photo/Icon -->
                                    <div class="flex-shrink-0">
                                        @if ($detail->produk->foto ?? false)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $detail->produk->foto) }}"
                                                    alt="{{ $detail->produk->nama_produk }}"
                                                    class="w-12 h-12 rounded-xl object-cover border-2 border-orange-200 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                                <!-- Overlay untuk hover effect -->
                                                <div
                                                    class="absolute inset-0 bg-black/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                                    <i class="ti ti-eye text-white text-sm"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center shadow-sm hover:scale-105 transition-transform duration-200">
                                                <i class="ti ti-box text-white text-lg"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h5 class="font-semibold text-gray-900">
                                                {{ $detail->produk->nama_produk ?? 'N/A' }}</h5>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="ti ti-tag mr-1"></i>
                                                {{ $detail->produk->kode_produk ?? 'N/A' }}
                                            </span>
                                            @if ($detail->produk->kategori ?? false)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="ti ti-category mr-1"></i>
                                                    {{ $detail->produk->kategori->nama }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <i class="ti ti-package text-orange-500 mr-1"></i>
                                                <span class="font-medium">{{ $detail->qty }}
                                                    {{ $detail->produk->satuan->nama ?? 'pcs' }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="ti ti-currency-dollar text-green-500 mr-1"></i>
                                                <span>@ Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                        @if ($detail->discount > 0)
                                            <div class="flex items-center mt-1">
                                                <i class="ti ti-discount text-red-500 mr-1"></i>
                                                <span class="text-xs text-red-600 font-medium">
                                                    Potongan: Rp {{ number_format($detail->discount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </div>
                                        @if ($detail->discount > 0)
                                            <div class="text-xs text-gray-500 line-through">
                                                Rp {{ number_format($detail->qty * $detail->harga_beli, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                @if ($pembelian->pembayaranPembelian->count() > 0)
                    <div class="bg-white rounded-lg shadow border">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-pink-50">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="ti ti-wallet text-purple-600"></i>
                                </div>
                                Riwayat Pembayaran ({{ $pembelian->pembayaranPembelian->count() }} transaksi)
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($pembelian->pembayaranPembelian as $pembayaran)
                                    <div
                                        class="flex items-center space-x-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:shadow-md transition-all duration-200">
                                        <!-- Payment Icon -->
                                        <div class="flex-shrink-0">
                                            @php
                                                // Get payment method from database
                                                $metodePembayaranData = \App\Models\MetodePembayaran::where(
                                                    'kode',
                                                    $pembayaran->metode_pembayaran,
                                                )->first();

                                                // Default icon and color if not found
                                                $icon = $metodePembayaranData
                                                    ? $metodePembayaranData->icon_display
                                                    : 'ti-credit-card';
                                                $color = 'from-blue-400 to-indigo-500'; // Default color

                                                // Color mapping based on common payment methods
                                                $colorMap = [
                                                    'tunai' => 'from-green-400 to-emerald-500',
                                                    'transfer' => 'from-blue-400 to-indigo-500',
                                                    'qris' => 'from-purple-400 to-pink-500',
                                                    'kartu' => 'from-orange-400 to-red-500',
                                                    'ewallet' => 'from-cyan-400 to-blue-500',
                                                ];

                                                if (
                                                    $metodePembayaranData &&
                                                    isset($colorMap[strtolower($metodePembayaranData->kode)])
                                                ) {
                                                    $color = $colorMap[strtolower($metodePembayaranData->kode)];
                                                }
                                            @endphp
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br {{ $color }} rounded-xl flex items-center justify-center shadow-sm">
                                                <i class="ti {{ $icon }} text-white text-lg"></i>
                                            </div>
                                        </div>

                                        <!-- Payment Info -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h5 class="font-semibold text-gray-900">{{ $pembayaran->no_bukti }}</h5>
                                                @php
                                                    $statusConfig = [
                                                        'D' => [
                                                            'label' => 'DP',
                                                            'bg' => 'bg-blue-100',
                                                            'text' => 'text-blue-800',
                                                            'icon' => 'ti-clock',
                                                        ],
                                                        'A' => [
                                                            'label' => 'Angsuran',
                                                            'bg' => 'bg-orange-100',
                                                            'text' => 'text-orange-800',
                                                            'icon' => 'ti-coins',
                                                        ],
                                                        'P' => [
                                                            'label' => 'Pelunasan',
                                                            'bg' => 'bg-green-100',
                                                            'text' => 'text-green-800',
                                                            'icon' => 'ti-check',
                                                        ],
                                                    ];
                                                    $status =
                                                        $statusConfig[$pembayaran->status_bayar] ?? $statusConfig['D'];
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }}">
                                                    <i class="ti {{ $status['icon'] }} mr-1"></i>
                                                    {{ $status['label'] }}
                                                </span>

                                                <!-- Delete Status Badge -->
                                                @php
                                                    $today = \Carbon\Carbon::today();
                                                    $paymentDate = \Carbon\Carbon::parse(
                                                        $pembayaran->created_at,
                                                    )->startOfDay();

                                                    // Check if this is the latest payment
                                                    $latestPayment = $pembelian->pembayaranPembelian
                                                        ->sortByDesc('created_at')
                                                        ->first();
                                                    $isLatestPayment =
                                                        $latestPayment && $latestPayment->id === $pembayaran->id;

                                                    // Payment can only be deleted if:
                                                    // 1. It's created today AND
// 2. It's the latest payment (no newer payments exist)
                                                    $canDelete = $today->equalTo($paymentDate) && $isLatestPayment;
                                                @endphp

                                                <!-- User Badge -->
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    <i class="ti ti-user mr-1"></i>
                                                    {{ $pembayaran->user->name ?? 'Unknown' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <div class="flex items-center">
                                                    <i class="ti ti-calendar text-blue-500 mr-1"></i>
                                                    <span>{{ $pembayaran->tanggal->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="ti ti-credit-card text-purple-500 mr-1"></i>
                                                    <span class="capitalize">
                                                        @php
                                                            $metodePembayaranData = \App\Models\MetodePembayaran::where(
                                                                'kode',
                                                                $pembayaran->metode_pembayaran,
                                                            )->first();
                                                        @endphp
                                                        {{ $metodePembayaranData ? $metodePembayaranData->nama : $pembayaran->metode_pembayaran }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="ti ti-clock text-gray-500 mr-1"></i>
                                                    <span>Dibuat:
                                                        {{ $pembayaran->created_at->format('d M Y, H:i') }}</span>
                                                </div>
                                            </div>

                                            @if ($pembayaran->keterangan)
                                                <div class="flex items-center mt-1">
                                                    <i class="ti ti-note text-gray-400 mr-1"></i>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $pembayaran->keterangan }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Payment Amount -->
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">
                                                Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if ($canDelete)
                                                <button
                                                    onclick="confirmDeletePayment('{{ $pembayaran->encrypted_id }}', '{{ $pembayaran->no_bukti }}')"
                                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                    title="Hapus Pembayaran">
                                                    <i class="ti ti-trash text-sm"></i>
                                                </button>
                                            @else
                                                @php
                                                    $tooltipMessage = '';
                                                    if (!$today->equalTo($paymentDate)) {
                                                        $tooltipMessage =
                                                            'Pembayaran hanya bisa dihapus pada hari yang sama dengan pembuatan.';
                                                    } else {
                                                        $tooltipMessage =
                                                            'Pembayaran tidak dapat dihapus karena sudah ada pembayaran baru.';
                                                    }
                                                @endphp
                                                <button class="p-2 text-gray-400 cursor-not-allowed"
                                                    title="{{ $tooltipMessage }}">
                                                    <i class="ti ti-trash text-sm"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Financial Summary -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-blue-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-calculator text-indigo-600"></i>
                            </div>
                            Ringkasan Keuangan
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Subtotal -->
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <i class="ti ti-receipt text-gray-400 mr-2"></i>
                                <span class="text-gray-600">Subtotal</span>
                            </div>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($pembelian->detailPembelian->sum('subtotal'), 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Jenis Transaksi -->
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                @if ($pembelian->jenis_transaksi == 'tunai')
                                    <i class="ti ti-cash text-green-400 mr-2"></i>
                                @else
                                    <i class="ti ti-credit-card text-purple-400 mr-2"></i>
                                @endif
                                <span class="text-gray-600">Jenis Transaksi</span>
                            </div>
                            <span
                                class="font-semibold {{ $pembelian->jenis_transaksi == 'tunai' ? 'text-green-600' : 'text-purple-600' }}">
                                {{ ucfirst($pembelian->jenis_transaksi) }}
                            </span>
                        </div>

                        @if ($pembelian->diskon > 0)
                            <!-- Discount -->
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <i class="ti ti-discount text-red-400 mr-2"></i>
                                    <span class="text-gray-600">Diskon</span>
                                </div>
                                <span class="font-semibold text-red-600">
                                    -Rp {{ number_format($pembelian->diskon, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <!-- Total -->
                        <div
                            class="flex items-center justify-between py-3 border-t border-gray-200 bg-gradient-to-r from-orange-50 to-red-50 -mx-6 px-6 rounded-lg">
                            <div class="flex items-center">
                                <i class="ti ti-currency-dollar text-orange-600 mr-2"></i>
                                <span class="font-bold text-gray-900">Total</span>
                            </div>
                            <span class="font-bold text-xl text-orange-600">
                                Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Payment Status -->
                        <div class="pt-3 border-t space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="ti ti-check-circle text-green-500 mr-2"></i>
                                    <span class="text-gray-600">Dibayar</span>
                                </div>
                                <span class="font-semibold text-green-600">
                                    Rp
                                    {{ number_format($pembelian->pembayaranPembelian->sum('jumlah_bayar'), 0, ',', '.') }}
                                </span>
                            </div>

                            @php
                                $totalDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
                                $sisaPembayaran = $pembelian->total - $totalDibayar;
                            @endphp

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if ($sisaPembayaran > 0)
                                        <i class="ti ti-clock text-red-500 mr-2"></i>
                                    @else
                                        <i class="ti ti-check text-green-500 mr-2"></i>
                                    @endif
                                    <span class="text-gray-600">Sisa</span>
                                </div>
                                <span class="font-semibold {{ $sisaPembayaran > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-slate-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-settings text-gray-600"></i>
                            </div>
                            Aksi Transaksi
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Print Buttons -->
                        <div class="space-y-2">
                            <!-- QZ Tray Print Button -->
                            <button onclick="printInvoiceWithQZTray(event)"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-600 to-slate-600 text-white rounded-lg hover:from-gray-700 hover:to-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-3">
                                    <i class="ti ti-printer text-sm"></i>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <span id="printButtonText" class="font-medium">Cetak Invoice (QZ Tray)</span>
                                    <div id="printerInfo" class="text-xs text-white/80 font-normal">
                                        @php
                                            $defaultPrinter = \App\Models\PrinterSetting::getDefault();
                                        @endphp
                                        @if ($defaultPrinter)
                                            Printer: {{ $defaultPrinter->printer_name }}
                                        @else
                                            Printer: Auto-detect
                                        @endif
                                    </div>
                                </div>
                            </button>

                            <!-- Browser Print Button -->
                            <button onclick="printInvoiceRaw()"
                                class="w-full flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <div class="w-4 h-4 bg-white/20 rounded flex items-center justify-center mr-2">
                                    <i class="ti ti-device-floppy text-xs"></i>
                                </div>
                                Cetak Raw (Browser)
                            </button>
                        </div>

                        @if ($pembelian->status_pembayaran !== 'lunas')
                            <!-- Edit Button -->
                            <a href="{{ route('pembelian.edit', $pembelian->encrypted_id) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-3">
                                    <i class="ti ti-edit text-sm"></i>
                                </div>
                                Edit Transaksi
                            </a>
                        @endif

                        @if ($sisaPembayaran > 0)
                            <!-- Payment Button -->
                            <button type="button" onclick="openPaymentModal()"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-3">
                                    <i class="ti ti-cash text-sm"></i>
                                </div>
                                Tambah Pembayaran
                            </button>
                        @endif

                        @if ($pembelian->pembayaranPembelian->count() == 0)
                            <!-- Delete Button -->
                            <button type="button"
                                onclick="confirmDelete('{{ $pembelian->encrypted_id }}', '{{ $pembelian->no_faktur }}')"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-lg hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-3">
                                    <i class="ti ti-trash text-sm"></i>
                                </div>
                                Hapus Transaksi
                            </button>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-auto">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            Tambah Pembayaran
                        </h3>
                        <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Payment Summary - Compact -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-3 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600 text-xs">Total:</span>
                                <div class="font-semibold text-gray-800">Rp {{ number_format($pembelian->total, 0) }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-xs">Sudah Bayar:</span>
                                <div class="font-semibold text-green-600">Rp
                                    {{ number_format($pembelian->pembayaranPembelian->sum('jumlah_bayar'), 0) }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600 text-xs">Sisa:</span>
                                <div class="font-semibold text-red-600">Rp
                                    {{ number_format($sisaPembayaran, 0) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form id="paymentForm" onsubmit="submitPayment(event)">
                        @csrf
                        <input type="hidden" name="pembelian_id" value="{{ $pembelian->id }}">

                        <div class="space-y-4">
                            <!-- Row 1: Tanggal dan Nominal bersebelahan -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Payment Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                        required>
                                </div>

                                <!-- Payment Amount -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Jumlah Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm font-medium">Rp</span>
                                        </div>
                                        <input type="text" id="paymentAmount" name="jumlah"
                                            value="{{ number_format($sisaPembayaran, 0, ',', '.') }}"
                                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 text-right text-lg font-semibold"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Keterangan full width -->
                            <div>
                                <textarea name="keterangan" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    placeholder="Keterangan (opsional)..."></textarea>
                            </div>

                            <!-- Row 3: Metode Pembayaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach ($metodePembayaran as $metode)
                                        <label class="relative cursor-pointer payment-method-option">
                                                <input type="radio" name="metode_pembayaran"
                                                    value="{{ $metode->kode }}"
                                                {{ old('metode_pembayaran') == $metode->kode ? 'checked' : '' }}
                                                class="sr-only payment-method-radio">
                                            <div
                                                class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 payment-method-card">
                                                <div class="flex flex-col items-center text-center">
                                                    <div
                                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                                                        <i
                                                            class="ti {{ $metode->icon_display }} text-blue-600 text-lg"></i>
                                                    </div>
                                                    <span
                                                        class="text-sm font-medium text-gray-900">{{ $metode->nama }}</span>
                                                    <span class="text-xs text-gray-500">{{ $metode->kode }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                                <!-- Row 4: Kas/Bank Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kas/Bank <span class="text-red-500">*</span>
                                    </label>

                                    <!-- Message when no payment method selected -->
                                    <div id="kasBankMessage" class="text-center py-4 text-gray-500">
                                        <i class="ti ti-arrow-up text-xl mb-2"></i>
                                        <p class="text-sm">Pilih metode pembayaran terlebih dahulu untuk melihat pilihan
                                            kas/bank</p>
                                    </div>

                                    <div class="grid gap-3 hidden" id="kasBankContainer">
                                        @foreach ($kasBank as $kas)
                                            <label class="relative cursor-pointer kas-bank-option">
                                                <input type="radio" name="kas_bank_id" value="{{ $kas->id }}"
                                                    data-saldo="{{ $kas->saldo_terkini }}"
                                                    data-jenis="{{ $kas->jenis }}"
                                                    data-image="{{ $kas->image_url ?? '' }}"
                                                    class="sr-only kas-bank-radio">
                                                <div
                                                    class="p-4 border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 kas-bank-card hidden flex items-center justify-between shadow-sm hover:shadow-md">
                                                    <div class="flex items-center flex-1">
                                                        <div
                                                            class="w-16 h-16 rounded-xl flex items-center justify-center mr-4 overflow-hidden shadow-sm flex-shrink-0">
                                                            @if ($kas->jenis === 'KAS')
                                                                <div
                                                                    class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                                                    <i class="ti ti-cash text-green-600 text-xl"></i>
                                                                </div>
                                                            @else
                                                                @if ($kas->image)
                                                                    <img src="{{ asset('storage/' . $kas->image) }}"
                                                                        alt="Logo {{ $kas->nama }}"
                                                                        class="w-full h-full object-contain">
                                                                @else
                                                                    <div
                                                                        class="w-full h-full bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                                                                        <i
                                                                            class="ti ti-building-bank text-purple-600 text-xl"></i>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 flex flex-col justify-center">
                                                            <div class="text-base font-bold text-gray-900 leading-tight">
                                                                {{ $kas->nama }}
                                                            </div>
                                                            @if ($kas->no_rekening)
                                                                <div class="text-sm text-gray-500 font-medium">
                                                                    {{ $kas->no_rekening }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-right ml-4 flex flex-col justify-center flex-shrink-0">
                                                        <div class="text-sm text-gray-500 font-medium">Saldo</div>
                                                        <div class="text-base font-bold text-green-600">
                                                            Rp {{ number_format($kas->saldo_terkini, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                        </div>

                        <!-- Payment Preview - Compact -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-medium text-blue-800 mb-2 text-sm">Preview Pembayaran:</h4>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700 text-xs">Jumlah Bayar:</span>
                                    <div class="font-medium" id="previewAmount">Rp
                                        {{ number_format($sisaPembayaran, 0) }}</div>
                                </div>
                                <div>
                                    <span class="text-blue-700 text-xs">Sisa Setelah Bayar:</span>
                                    <div class="font-medium" id="previewRemaining">Rp 0</div>
                                </div>
                                <div>
                                    <span class="text-blue-700 text-xs">Status:</span>
                                    <div class="font-medium text-green-600" id="previewStatus">
                                        @php
                                                $totalDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
                                                $sisaPembayaran = $pembelian->total - $totalDibayar;
                                        @endphp
                                            @if ($sisaPembayaran <= 0)
                                                Lunas
                                            @elseif ($totalDibayar > 0)
                                                DP
                                            @else
                                                Belum Bayar
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Submit Button -->
                            <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="closePaymentModal()"
                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                Batal
                            </button>
                            <button type="submit" id="submitPaymentBtn"
                                    class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <span id="submitBtnText">Simpan Pembayaran</span>
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('styles')
                    <style>
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

        /* Toast animation */
        .toast {
            min-width: 250px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className =
                `toast fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;

            if (type === 'success') {
                toast.className += ' bg-green-500 text-white';
                toast.innerHTML = `<i class="ti ti-check-circle mr-2"></i>${message}`;
            } else if (type === 'error') {
                toast.className += ' bg-red-500 text-white';
                toast.innerHTML = `<i class="ti ti-alert-circle mr-2"></i>${message}`;
            } else if (type === 'warning') {
                toast.className += ' bg-orange-500 text-white';
                toast.innerHTML = `<i class="ti ti-alert-triangle mr-2"></i>${message}`;
                } else {
                toast.className += ' bg-blue-500 text-white';
                toast.innerHTML = `<i class="ti ti-info-circle mr-2"></i>${message}`;
            }

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }

        // Payment Modal Functions
        function openPaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset form and preview
            document.getElementById('paymentForm').reset();
            document.getElementById('paymentAmount').value =
                {{ $pembelian->total - $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};
            updatePaymentInfo();
        }



        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetValidationStyles();
        }

        function resetValidationStyles() {
            // Reset payment method validation
            const paymentMethodCards = document.querySelectorAll('.payment-method-card');
            paymentMethodCards.forEach(card => {
                card.style.border = '';
                card.style.backgroundColor = '';
            });

            // Reset kas/bank validation
            const kasBankCards = document.querySelectorAll('.kas-bank-card');
            kasBankCards.forEach(card => {
                card.style.border = '';
                card.style.backgroundColor = '';
            });

            // Reset payment amount validation
            const paymentAmountInput = document.getElementById('paymentAmount');
            if (paymentAmountInput) {
                paymentAmountInput.style.border = '';
                paymentAmountInput.style.backgroundColor = '';
            }
        }

        // Format number input with thousand separator
        function formatNumberInput(value) {
            // Remove all non-digit characters
            const numericValue = value.toString().replace(/\D/g, '');
            // Format with thousand separator
            return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Parse formatted number back to numeric value
        function parseFormattedNumber(value) {
            if (!value || typeof value !== 'string') return 0;

            // Remove all non-digit characters (dots and commas)
            const cleanValue = value.toString().replace(/[^\d]/g, '');

            const result = parseInt(cleanValue) || 0;
            return result;
        }

        // Setup number input formatting
        function setupNumberInput(input) {
            input.addEventListener('input', function(e) {
                const cursorPosition = e.target.selectionStart;
                const oldValue = e.target.value;
                const newValue = formatNumberInput(e.target.value);

                e.target.value = newValue;

                // Adjust cursor position
                const diff = newValue.length - oldValue.length;
                e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
            });
        }

        function updatePaymentInfo() {
            const paymentAmount = parseFormattedNumber(document.getElementById('paymentAmount').value) || 0;
            const sisaPembayaran = {{ $pembelian->total - $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};
            const remainingAmount = sisaPembayaran - paymentAmount;
            const sudahDibayar = {{ $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};

            // Update preview
            document.getElementById('previewAmount').textContent = `Rp ${paymentAmount.toLocaleString('id-ID')}`;
            document.getElementById('previewRemaining').textContent = `Rp ${remainingAmount.toLocaleString('id-ID')}`;

            // Update status berdasarkan rule
            const statusElement = document.getElementById('previewStatus');

            if (sudahDibayar == 0) {
                // Pembayaran pertama
                if (paymentAmount >= sisaPembayaran) {
                    statusElement.textContent = 'P (Pelunasan)';
                    statusElement.className = 'font-medium text-green-600';
                } else {
                    statusElement.textContent = 'D (DP)';
                    statusElement.className = 'font-medium text-blue-600';
                }
            } else {
                // Pembayaran selanjutnya
                if (paymentAmount >= sisaPembayaran) {
                    statusElement.textContent = 'P (Pelunasan)';
                    statusElement.className = 'font-medium text-green-600';
                } else {
                    statusElement.textContent = 'A (Angsuran)';
                    statusElement.className = 'font-medium text-orange-600';
                }
            }
        }

        // Form submission
        function submitPayment(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            // Reset validation styles
            resetValidationStyles();

            // Validation
            const paymentAmount = parseFormattedNumber(formData.get('jumlah'));
            const metodePembayaran = formData.get('metode_pembayaran');
            const kasBankId = formData.get('kas_bank_id');
            let isValid = true;

            // Validate payment method first
            if (!metodePembayaran) {
                const paymentMethodCards = document.querySelectorAll('.payment-method-card');
                paymentMethodCards.forEach(card => {
                    card.style.border = '2px solid #ef4444';
                    card.style.backgroundColor = '#fef2f2';
                });
                showToast('Pilih metode pembayaran terlebih dahulu!', 'error');
                isValid = false;
            }

            // Only validate kas/bank if payment method is selected
            if (metodePembayaran && !kasBankId) {
                const kasBankCards = document.querySelectorAll('.kas-bank-card');
                kasBankCards.forEach(card => {
                    card.style.border = '2px solid #ef4444';
                    card.style.backgroundColor = '#fef2f2';
                });
                showToast('Pilih kas/bank terlebih dahulu!', 'error');
                isValid = false;
            }

            // Only validate payment amount if payment method and kas/bank are selected
            if (metodePembayaran && kasBankId && paymentAmount <= 0) {
            const paymentAmountInput = document.getElementById('paymentAmount');
            if (paymentAmountInput) {
                    paymentAmountInput.style.border = '2px solid #ef4444';
                    paymentAmountInput.style.backgroundColor = '#fef2f2';
                }
                showToast('Jumlah pembayaran harus lebih dari 0!', 'error');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Disable button and show loading
            const submitBtn = document.getElementById('submitPaymentBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            submitBtn.disabled = true;
            submitBtnText.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...';

            // Debug: Log form data
            console.log('Form Data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            // Submit form
            fetch('{{ route('pembayaran-pembelian.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    showToast(error.message || 'Terjadi kesalahan saat menyimpan pembayaran', 'error');
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtnText.textContent = 'Simpan Pembayaran';
                });
        }

        // Delete payment confirmation
        function confirmDeletePayment(paymentId, paymentNumber) {
            Swal.fire({
                title: 'Hapus Pembayaran?',
                text: `Apakah Anda yakin ingin menghapus pembayaran "${paymentNumber}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deletePayment(paymentId);
                }
            });
        }

        function deletePayment(paymentId) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            // Show loading state
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`{{ route('pembayaran-pembelian.destroy', '') }}/${paymentId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.redirected) {
                        // If redirected, follow the redirect
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pembayaran berhasil dihapus',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload page to show updated data
                            window.location.reload();
                        });
                    } else if (data) {
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus pembayaran');
                    }
                })
                .catch(error => {
                    console.error('Delete payment error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }

        // Delete transaction confirmation
        function confirmDelete(purchaseId, invoiceNumber) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus transaksi "${invoiceNumber}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('pembelian.destroy', '') }}/${purchaseId}`;

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

        // Print functions
        function printInvoiceWithQZTray(event) {
            event.preventDefault();
            // Implementation for QZ Tray printing
            console.log('QZ Tray printing not implemented yet');
        }

        function printInvoiceRaw() {
            window.print();
        }

        // Payment Method Option Buttons Styling
        document.addEventListener('DOMContentLoaded', function() {
            const paymentRadios = document.querySelectorAll('.payment-method-radio');
            const paymentCards = document.querySelectorAll('.payment-method-card');

            // Function to update payment method card styling
            function updatePaymentMethodCards() {
                paymentCards.forEach((card, index) => {
                    const radio = paymentRadios[index];
                    if (radio.checked) {
                        card.classList.remove('border-gray-200', 'bg-white', 'border-red-500', 'bg-red-50',
                            'animate-pulse');
                        card.classList.add('border-blue-500', 'bg-blue-50');
                    } else {
                        card.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-500',
                            'bg-red-50', 'animate-pulse');
                        card.classList.add('border-gray-200', 'bg-white');
                    }
                });
            }

            // Add event listeners to radio buttons
            paymentRadios.forEach((radio, index) => {
                radio.addEventListener('change', function() {
                    updatePaymentMethodCards();
                });

                // Add click event to card for better UX
                const card = paymentCards[index];
                card.addEventListener('click', function() {
                    radio.checked = true;
                    updatePaymentMethodCards();
                });
            });

            // Initialize card states
            updatePaymentMethodCards();

            // Setup number input formatting
            const paymentAmountInput = document.getElementById('paymentAmount');
            if (paymentAmountInput) {
                setupNumberInput(paymentAmountInput);

                // Add event listener for real-time preview update
                paymentAmountInput.addEventListener('input', function() {
                    updatePaymentInfo();
                    // Clear validation error when user types
                    this.style.border = '';
                    this.style.backgroundColor = '';
                });

                // Initialize preview on page load
                updatePaymentInfo();
            }

            // Kas/Bank filtering functionality
            const kasBankRadios = document.querySelectorAll('.kas-bank-radio');
            const kasBankCards = document.querySelectorAll('.kas-bank-card');
            const kasBankContainer = document.getElementById('kasBankContainer');
            const kasBankMessage = document.getElementById('kasBankMessage');

            // Function to update kas/bank card styling
            function updateKasBankCards() {
                kasBankCards.forEach((card, index) => {
                    const radio = kasBankRadios[index];
                    if (radio.checked) {
                        card.classList.remove('border-gray-200', 'bg-white', 'border-red-500', 'bg-red-50',
                            'animate-pulse');
                        card.classList.add('border-blue-500', 'bg-blue-50');
                    } else {
                        card.classList.remove('border-blue-500', 'bg-blue-50', 'border-red-500',
                            'bg-red-50', 'animate-pulse');
                        card.classList.add('border-gray-200', 'bg-white');
                    }
                });
            }

            // Function to filter kas/bank based on payment method
            function filterKasBankByPaymentMethod() {
                const selectedPaymentMethod = document.querySelector('.payment-method-radio:checked');

                if (!selectedPaymentMethod) {
                    // If no payment method selected, hide all kas/bank and show message
                    kasBankCards.forEach((card, index) => {
                        card.classList.add('hidden');
                    });
                    kasBankMessage.classList.remove('hidden');
                    return;
                }

                // Hide message when payment method is selected
                kasBankMessage.classList.add('hidden');

                const paymentMethodCode = selectedPaymentMethod.value;
                const isTransfer = paymentMethodCode.toLowerCase().includes('transfer') ||
                    paymentMethodCode.toLowerCase().includes('bank') ||
                    paymentMethodCode.toLowerCase().includes('bca') ||
                    paymentMethodCode.toLowerCase().includes('mandiri') ||
                    paymentMethodCode.toLowerCase().includes('bni') ||
                    paymentMethodCode.toLowerCase().includes('bri');
                const isCash = paymentMethodCode.toLowerCase().includes('cash') ||
                    paymentMethodCode.toLowerCase().includes('tunai') ||
                    paymentMethodCode.toLowerCase().includes('kas');

                let visibleCount = 0;

                kasBankCards.forEach((card, index) => {
                    const radio = kasBankRadios[index];
                    const kasBankJenis = radio.getAttribute('data-jenis');

                    if (isTransfer && kasBankJenis === 'BANK') {
                        // Show only BANK for transfer methods
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else if (isCash && kasBankJenis === 'KAS') {
                        // Show only KAS for cash methods
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else if (!isTransfer && !isCash) {
                        // If payment method is not clearly transfer or cash, show all
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        // Hide the card
                        card.classList.add('hidden');
                    }
                });

                // Update grid columns based on visible count
                if (visibleCount === 1) {
                    kasBankContainer.className = 'grid gap-3 grid-cols-1';
                } else if (visibleCount === 2) {
                    kasBankContainer.className = 'grid gap-3 grid-cols-2';
                } else if (visibleCount === 3) {
                    kasBankContainer.className = 'grid gap-3 grid-cols-3';
                } else if (visibleCount >= 4) {
                    kasBankContainer.className = 'grid gap-3 grid-cols-4';
                }

                // Uncheck any hidden kas/bank selections
                kasBankRadios.forEach((radio, index) => {
                    const card = kasBankCards[index];
                    if (card.classList.contains('hidden') && radio.checked) {
                        radio.checked = false;
                        updateKasBankCards();
                    }
                });
            }

            // Add event listeners to payment method radio buttons
            paymentRadios.forEach((radio, index) => {
                radio.addEventListener('change', function() {
                    updatePaymentMethodCards();
                    filterKasBankByPaymentMethod();
                    // Clear validation error when payment method is selected
                    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
                    paymentMethodCards.forEach(card => {
                        card.style.border = '';
                        card.style.backgroundColor = '';
                    });
                    // Show info toast
                    showToast(`Metode pembayaran "${this.value}" telah dipilih`, 'info');
                });

                // Add click event to card for better UX
                const card = paymentCards[index];
                card.addEventListener('click', function() {
                    radio.checked = true;
                    updatePaymentMethodCards();
                    filterKasBankByPaymentMethod();
                    // Clear validation error when payment method is selected
                    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
                    paymentMethodCards.forEach(card => {
                        card.style.border = '';
                        card.style.backgroundColor = '';
                    });
                    // Show info toast
                    showToast(`Metode pembayaran "${radio.value}" telah dipilih`, 'info');
                });
            });

            // Add event listeners to kas/bank radio buttons
            kasBankRadios.forEach((radio, index) => {
                radio.addEventListener('change', function() {
                    updateKasBankCards();
                    // Clear validation error when kas/bank is selected
                    const kasBankCards = document.querySelectorAll('.kas-bank-card');
                    kasBankCards.forEach(card => {
                        card.style.border = '';
                        card.style.backgroundColor = '';
                    });
                    // Show info toast
                    const kasBankName = this.closest('.kas-bank-option').querySelector('.text-base')
                        .textContent.trim();
                    showToast(`Kas/Bank "${kasBankName}" telah dipilih`, 'info');
                });

                // Add click event to card for better UX
                const card = kasBankCards[index];
                card.addEventListener('click', function() {
                    radio.checked = true;
                    updateKasBankCards();
                    // Clear validation error when kas/bank is selected
                    const kasBankCards = document.querySelectorAll('.kas-bank-card');
                    kasBankCards.forEach(card => {
                        card.style.border = '';
                        card.style.backgroundColor = '';
                    });
                    // Show info toast
                    const kasBankName = this.closest('.kas-bank-option').querySelector('.text-base')
                        .textContent.trim();
                    showToast(`Kas/Bank "${kasBankName}" telah dipilih`, 'info');
                });
            });

            // Initialize kas/bank filter
            filterKasBankByPaymentMethod();
        });
    </script>
@endpush
