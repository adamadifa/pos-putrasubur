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
                                                $paymentIcons = [
                                                    'tunai' => [
                                                        'icon' => 'ti-cash',
                                                        'color' => 'from-green-400 to-emerald-500',
                                                    ],
                                                    'transfer' => [
                                                        'icon' => 'ti-credit-card',
                                                        'color' => 'from-blue-400 to-indigo-500',
                                                    ],
                                                    'kartu_kredit' => [
                                                        'icon' => 'ti-credit-card',
                                                        'color' => 'from-purple-400 to-pink-500',
                                                    ],
                                                    'kartu_debit' => [
                                                        'icon' => 'ti-credit-card',
                                                        'color' => 'from-orange-400 to-red-500',
                                                    ],
                                                    'e_wallet' => [
                                                        'icon' => 'ti-device-mobile',
                                                        'color' => 'from-cyan-400 to-blue-500',
                                                    ],
                                                    'dp' => [
                                                        'icon' => 'ti-credit-card',
                                                        'color' => 'from-blue-400 to-indigo-500',
                                                    ],
                                                ];
                                                $paymentConfig =
                                                    $paymentIcons[strtolower($pembayaran->metode_pembayaran)] ??
                                                    $paymentIcons['tunai'];
                                            @endphp
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br {{ $paymentConfig['color'] }} rounded-xl flex items-center justify-center shadow-sm">
                                                <i class="ti {{ $paymentConfig['icon'] }} text-white text-lg"></i>
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
                                                    <span>{{ $pembayaran->tanggal->format('d M Y, H:i') }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="ti ti-credit-card text-purple-500 mr-1"></i>
                                                    <span class="capitalize">{{ $pembayaran->metode_pembayaran }}</span>
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
                                            <div class="text-xs text-gray-500">
                                                {{ $pembayaran->tanggal->format('H:i') }}
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if ($canDelete)
                                                <button
                                                    onclick="confirmDeletePayment({{ $pembayaran->id }}, '{{ $pembayaran->no_bukti }}')"
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
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
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
                                            <input type="radio" name="metode_pembayaran" value="{{ $metode->kode }}"
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
                                            $sudahDibayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
                                            $totalTransaksi = $pembelian->total;
                                            $sisaPembayaran = $pembelian->sisa_pembayaran;

                                            if ($sudahDibayar == 0) {
                                                // Pembayaran pertama
                                                if ($sisaPembayaran == 0) {
                                                    echo 'P (Pelunasan)';
                                                } else {
                                                    echo 'D (DP)';
                                                }
                                            } else {
                                                // Pembayaran selanjutnya
                                                if ($sisaPembayaran == 0) {
                                                    echo 'P (Pelunasan)';
                                                } else {
                                                    echo 'A (Angsuran)';
                                                }
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 mt-4">
                            <button type="button" onclick="closePaymentModal()"
                                class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                Batal
                            </button>
                            <button type="submit" id="submitPaymentBtn"
                                class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-colors duration-200 font-medium">
                                <span id="submitBtnText">Simpan Pembayaran</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- QZ Tray -->
    <script src="{{ asset('js/qz/qz-tray.js') }}"></script>
    <script src="{{ asset('js/qz/qz-config.js') }}"></script>

    <script>
        // Confirm delete function
        function confirmDelete(id, faktur) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus pembelian "${faktur}"?`,
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
                    form.action = `/pembelian/${id}`;

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

        // Print Invoice with QZ Tray functionality
        function printInvoiceWithQZTray(event) {
            const button = event.target.closest('button');
            const buttonText = document.getElementById('printButtonText');

            // Disable button and show loading
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            buttonText.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Mencetak...';

            // Check if QZ Tray is available
            if (typeof qz === 'undefined') {
                console.log('QZ Tray not loaded, loading script...');
                loadQZTrayAndPrintInvoice();
                return;
            }

            // Setup QZ security (unsigned mode)
            qz.security.setCertificatePromise(function(resolve, reject) {
                resolve(); // Allow unsigned requests
            });

            qz.security.setSignaturePromise(function(toSign) {
                return function(resolve, reject) {
                    resolve(toSign); // Allow unsigned requests
                };
            });

            // Connect and print
            qz.websocket.connect({
                retries: 2,
                delay: 1
            }).then(function() {
                console.log('QZ Tray connected for invoice printing');

                // Get default printer from settings or find any printer
                const defaultPrinter = @json(session('printer_settings.default_printer', ''));

                if (defaultPrinter) {
                    printInvoice(defaultPrinter);
                } else {
                    // Find any available printer
                    qz.printers.find().then(function(printers) {
                        if (printers.length > 0) {
                            printInvoice(printers[0]);
                        } else {
                            console.log('No printers found for invoice printing');
                            showPrintError('Tidak ada printer yang tersedia');
                        }
                    });
                }
            }).catch(function(err) {
                console.log('QZ Tray connection failed for invoice printing:', err);
                showPrintError('Koneksi QZ Tray gagal: ' + err.message);
            });
        }

        function printInvoice(printerName) {
            // Generate invoice content for thermal printer
            const invoiceData = generateInvoiceData();

            const config = qz.configs.create(printerName);

            qz.print(config, invoiceData).then(function() {
                console.log(`Invoice printed to ${printerName}`);

                // Update printer info
                updatePrinterInfo(printerName);

                // Show success notification
                showNotification('✅ Invoice berhasil dicetak ke ' + printerName, 'success');

                // Reset button
                resetPrintButton();
            }).catch(function(err) {
                console.log('Invoice printing failed:', err);
                showNotification('⚠️ Cetak invoice gagal: ' + err.message, 'warning');

                // Reset button
                resetPrintButton();
            });
        }

        function generateInvoiceData() {
            const invoiceLines = [];

            // Header
            invoiceLines.push("\x1B\x40"); // Initialize printer
            invoiceLines.push("\x1B\x61\x01"); // Center align
            invoiceLines.push("PUTRA SUBUR\n");
            invoiceLines.push("Toko Kelontong\n");
            invoiceLines.push("Jl. Raya No. 123\n");
            invoiceLines.push("Telp: 021-1234567\n");
            invoiceLines.push("================================\n");

            // Invoice info
            invoiceLines.push("\x1B\x61\x00"); // Left align
            invoiceLines.push("PURCHASE ORDER\n");
            invoiceLines.push("No. Faktur: {{ $pembelian->no_faktur }}\n");
            invoiceLines.push("Tanggal: {{ $pembelian->tanggal->format('d/m/Y H:i') }}\n");
            invoiceLines.push("Supplier: {{ $pembelian->supplier->nama }}\n");
            invoiceLines.push("Dibuat: {{ $pembelian->user->name }}\n");
            invoiceLines.push("================================\n");

            // Items
            @foreach ($pembelian->detailPembelian as $detail)
                invoiceLines.push("{{ substr($detail->produk->nama_produk, 0, 20) }}\n");
                invoiceLines.push(
                    "  {{ number_format($detail->qty, 0) }} {{ $detail->produk->satuan->nama ?? 'pcs' }} x {{ number_format($detail->harga_beli, 0) }} = {{ number_format($detail->subtotal, 0) }}\n"
                );
                @if ($detail->discount > 0)
                    invoiceLines.push("  Diskon: -{{ number_format($detail->discount, 0) }}\n");
                @endif
            @endforeach

            invoiceLines.push("--------------------------------\n");

            // Totals
            @php
                $subtotalSebelumDiskon = $pembelian->total + $pembelian->diskon;
            @endphp
            invoiceLines.push("Subtotal: Rp {{ number_format($subtotalSebelumDiskon, 0) }}\n");

            @if ($pembelian->diskon > 0)
                invoiceLines.push("Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n");
            @endif

            invoiceLines.push("TOTAL: Rp {{ number_format($pembelian->total, 0) }}\n");

            // Payment info
            @php
                $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            @endphp
            @if ($totalBayar > 0)
                invoiceLines.push("Bayar: Rp {{ number_format($totalBayar, 0) }}\n");

                @if ($totalBayar >= $pembelian->total)
                    @php
                        $lunas = $totalBayar - $pembelian->total;
                    @endphp
                    invoiceLines.push("Lunas: Rp {{ number_format($lunas, 0) }}\n");
                @else
                    @php
                        $sisa = $pembelian->total - $totalBayar;
                    @endphp
                    invoiceLines.push("Sisa: Rp {{ number_format($sisa, 0) }}\n");
                @endif
            @endif

            invoiceLines.push("================================\n");
            invoiceLines.push("\x1B\x61\x01"); // Center align
            invoiceLines.push("Terima kasih atas kerjasamanya\n");
            invoiceLines.push("Barang yang sudah dibeli\n");
            invoiceLines.push("tidak dapat dikembalikan\n");
            invoiceLines.push("\n\n\n");
            invoiceLines.push("\x1D\x56\x42\x00"); // Cut paper

            return invoiceLines;
        }

        function loadQZTrayAndPrintInvoice() {
            const script = document.createElement('script');
            script.src = '{{ asset('js/qz/qz-tray.js') }}';
            script.onload = function() {
                // Create a dummy event object for the delayed call
                const dummyEvent = {
                    target: document.querySelector('button[onclick="printInvoiceWithQZTray(event)"]')
                };
                setTimeout(() => printInvoiceWithQZTray(dummyEvent), 1000);
            };
            script.onerror = function() {
                showPrintError('Gagal memuat QZ Tray library');
                resetPrintButton();
            };
            document.head.appendChild(script);
        }

        function showPrintError(message) {
            showNotification('⚠️ ' + message, 'warning');
        }

        function resetPrintButton() {
            const button = document.querySelector('button[onclick="printInvoiceWithQZTray(event)"]');
            const buttonText = document.getElementById('printButtonText');

            if (button && buttonText) {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                buttonText.textContent = 'Cetak Invoice (QZ Tray)';
            }
        }

        // Update printer info when QZ Tray connects
        function updatePrinterInfo(printerName) {
            const printerInfo = document.getElementById('printerInfo');
            if (printerInfo) {
                printerInfo.textContent = `Printer: ${printerName}`;
            }
        }

        // Print Invoice Raw via Browser
        function printInvoiceRaw() {
            // Generate raw invoice content
            const rawContent = generateRawInvoiceContent();

            // Create new window for printing
            const printWindow = window.open('', '_blank', 'width=800,height=600');

            // Write raw content with monospace font
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Purchase Order {{ $pembelian->no_faktur }}</title>
                    <style>
                        body {
                            font-family: 'Courier New', monospace;
                            font-size: 12px;
                            line-height: 1.2;
                            margin: 20px;
                            background: white;
                            color: black;
                        }
                        .invoice-content {
                            white-space: pre;
                            font-size: 11px;
                            line-height: 1.1;
                        }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="invoice-content">${rawContent}</div>
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            🖨️ Print Invoice
                        </button>
                        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                            ❌ Close
                        </button>
                    </div>
                </body>
                </html>
            `);

            printWindow.document.close();

            // Focus on the new window
            printWindow.focus();
        }

        function generateRawInvoiceContent() {
            let content = '';

            // Header
            content += '================================\n';
            content += '        PUTRA SUBUR\n';
            content += '       Toko Kelontong\n';
            content += '      Jl. Raya No. 123\n';
            content += '      Telp: 021-1234567\n';
            content += '================================\n\n';

            // Invoice info
            content += 'PURCHASE ORDER\n';
            content += 'No. Faktur: {{ $pembelian->no_faktur }}\n';
            content += 'Tanggal: {{ $pembelian->tanggal->format('d/m/Y H:i') }}\n';
            content += 'Supplier: {{ $pembelian->supplier->nama }}\n';
            content += 'Dibuat: {{ $pembelian->user->name }}\n';
            content += '================================\n\n';

            // Items
            @foreach ($pembelian->detailPembelian as $detail)
                content += '{{ substr($detail->produk->nama_produk, 0, 30) }}\n';
                content +=
                    '  {{ number_format($detail->qty, 0) }} {{ $detail->produk->satuan->nama ?? 'pcs' }} x {{ number_format($detail->harga_beli, 0) }} = {{ number_format($detail->subtotal, 0) }}\n';
                @if ($detail->discount > 0)
                    content += '  Diskon: -{{ number_format($detail->discount, 0) }}\n';
                @endif
                content += '\n';
            @endforeach

            content += '--------------------------------\n';

            // Totals
            @php
                $subtotalSebelumDiskon = $pembelian->total + $pembelian->diskon;
            @endphp
            content += 'Subtotal: Rp {{ number_format($subtotalSebelumDiskon, 0) }}\n';

            @if ($pembelian->diskon > 0)
                content += 'Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n';
            @endif

            content += 'TOTAL: Rp {{ number_format($pembelian->total, 0) }}\n\n';

            // Payment info
            @php
                $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            @endphp
            @if ($totalBayar > 0)
                content += 'Bayar: Rp {{ number_format($totalBayar, 0) }}\n';

                @if ($totalBayar >= $pembelian->total)
                    @php
                        $lunas = $totalBayar - $pembelian->total;
                    @endphp
                    content += 'Lunas: Rp {{ number_format($lunas, 0) }}\n';
                @else
                    @php
                        $sisa = $pembelian->total - $totalBayar;
                    @endphp
                    content += 'Sisa: Rp {{ number_format($sisa, 0) }}\n';
                @endif
                content += '\n';
            @endif

            content += '================================\n';
            content += '   Terima kasih atas kerjasamanya\n';
            content += '     Barang yang sudah dibeli\n';
            content += '     tidak dapat dikembalikan\n';
            content += '================================\n';

            return content;
        }

        // Notification function
        function showNotification(message, type = 'info') {
            Swal.fire({
                title: type === 'success' ? 'Berhasil!' : type === 'warning' ? 'Peringatan!' : 'Info',
                text: message,
                icon: type,
                timer: type === 'success' ? 2000 : null,
                showConfirmButton: type !== 'success'
            });
        }

        // Payment Modal Functions
        function openPaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset form and preview
            document.getElementById('paymentForm').reset();
            document.getElementById('paymentAmount').value = {{ $sisaPembayaran }};
            updatePaymentInfo();
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function updatePaymentInfo() {
            const paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
            const remainingAmount = {{ $sisaPembayaran }} - paymentAmount;
            const totalTransaksi = {{ $pembelian->total }};
            const sudahDibayar = {{ $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};

            // Update preview
            document.getElementById('previewAmount').textContent = `Rp ${paymentAmount.toLocaleString('id-ID')}`;
            document.getElementById('previewRemaining').textContent = `Rp ${remainingAmount.toLocaleString('id-ID')}`;

            // Update status berdasarkan logika yang sama dengan backend
            const statusElement = document.getElementById('previewStatus');
            let statusText = 'DP';
            let statusClass = 'font-medium text-blue-600';

            if (sudahDibayar == 0) {
                // First payment
                if (paymentAmount >= totalTransaksi) {
                    statusText = 'Lunas';
                    statusClass = 'font-medium text-green-600';
                } else {
                    statusText = 'DP';
                    statusClass = 'font-medium text-blue-600';
                }
            } else {
                // Subsequent payments
                const totalAfterPayment = sudahDibayar + paymentAmount;
                if (totalAfterPayment >= totalTransaksi) {
                    statusText = 'Lunas';
                    statusClass = 'font-medium text-green-600';
                } else {
                    statusText = 'Angsuran';
                    statusClass = 'font-medium text-orange-600';
                }
            }

            statusElement.textContent = statusText;
            statusElement.className = statusClass;
        }

        function submitPayment(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitPaymentBtn');
            const submitBtnText = document.getElementById('submitBtnText');

            // Validate form data before submission
            const jumlahRaw = formData.get('jumlah');
            const jumlah = parseFormattedNumber(jumlahRaw);
            const metode_pembayaran = formData.get('metode_pembayaran');
            const tanggal = formData.get('tanggal');

            // Validate jumlah pembayaran
            if (!jumlahRaw || jumlahRaw.trim() === '') {
                showPaymentError('Jumlah pembayaran wajib diisi!');
                document.getElementById('paymentAmount').focus();
                return;
            }

            if (!jumlah || jumlah <= 0) {
                showPaymentError('Jumlah pembayaran harus lebih dari 0!');
                document.getElementById('paymentAmount').focus();
                return;
            }

            // Validate maksimum pembayaran
            const maxPayment = {{ $sisaPembayaran }};
            if (jumlah > maxPayment) {
                showPaymentError(
                    `Jumlah pembayaran tidak boleh melebihi sisa pembayaran (Rp ${maxPayment.toLocaleString('id-ID')})!`
                );
                document.getElementById('paymentAmount').focus();
                return;
            }

            if (!metode_pembayaran) {
                showPaymentError('Metode pembayaran wajib dipilih!');

                // Add error highlight to all payment method cards
                const paymentCards = document.querySelectorAll('.payment-method-card');
                paymentCards.forEach(card => {
                    card.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                    // Remove error highlight after 3 seconds
                    setTimeout(() => {
                        card.classList.remove('border-red-500', 'bg-red-50', 'animate-pulse');
                    }, 3000);
                });

                // Focus on the first payment method card
                const firstPaymentCard = document.querySelector('.payment-method-card');
                if (firstPaymentCard) {
                    firstPaymentCard.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                return;
            }

            if (!tanggal) {
                showPaymentError('Tanggal pembayaran wajib diisi!');
                document.querySelector('input[name="tanggal"]').focus();
                return;
            }

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtnText.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...';

            // Update form data with parsed values
            formData.set('jumlah', jumlah);

            // Log form data for debugging
            console.log('Submitting payment:', {
                jumlah: jumlah,
                metode_pembayaran: metode_pembayaran,
                tanggal: tanggal,
                pembelian_id: formData.get('pembelian_id'),
                keterangan: formData.get('keterangan')
            });

            // Submit payment
            fetch('{{ route('pembayaran-pembelian.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Terjadi kesalahan pada server');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showPaymentSuccess(data.message);

                        // Close modal
                        closePaymentModal();

                        // Reload page after short delay to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menyimpan pembayaran');
                    }
                })
                .catch(error => {
                    console.error('Payment submission error:', error);
                    showPaymentError(error.message);

                    // Reset button
                    submitBtn.disabled = false;
                    submitBtnText.textContent = 'Simpan Pembayaran';
                });
        }

        function showPaymentSuccess(message) {
            // Create success notification
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 z-50 p-4 bg-green-500 text-white rounded-lg shadow-lg transform transition-all duration-300 translate-x-full';
            notification.innerHTML = `
                 <div class="flex items-center">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                     </svg>
                     ${message}
                 </div>
             `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        function showPaymentError(message) {
            // Create error notification
            const notification = document.createElement('div');
            notification.className =
                'fixed top-4 right-4 z-50 p-4 bg-red-500 text-white rounded-lg shadow-lg transform transition-all duration-300 translate-x-full';
            notification.innerHTML = `
                 <div class="flex items-center">
                     <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                     </svg>
                     ${message}
                 </div>
             `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('paymentModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePaymentModal();
            }
        });
    </script>

    <style>
        .payment-method-card {
            transition: all 0.2s ease-in-out;
        }

        .payment-method-card:hover {
            transform: translateY(-2px);
        }

        .payment-method-radio:checked+.payment-method-card {
            border-color: #3b82f6;
            background-color: #eff6ff;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
        }

        .payment-method-radio:checked+.payment-method-card .text-blue-600 {
            color: #2563eb;
        }
    </style>

    <script>
        // Payment Modal Functions
        function openPaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset form and preview
            document.getElementById('paymentForm').reset();
            document.getElementById('paymentAmount').value = {{ $sisaPembayaran }};
            updatePaymentInfo();
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
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
            const remainingAmount = {{ $sisaPembayaran }} - paymentAmount;
            const totalTransaksi = {{ $pembelian->total }};
            const sudahDibayar = {{ $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};

            // Update preview
            document.getElementById('previewAmount').textContent = `Rp ${paymentAmount.toLocaleString('id-ID')}`;
            document.getElementById('previewRemaining').textContent = `Rp ${remainingAmount.toLocaleString('id-ID')}`;

            // Update status berdasarkan rule
            const statusElement = document.getElementById('previewStatus');

            if (sudahDibayar == 0) {
                // Pembayaran pertama
                if (paymentAmount >= totalTransaksi) {
                    statusElement.textContent = 'P (Pelunasan)';
                    statusElement.className = 'font-medium text-green-600';
                } else {
                    statusElement.textContent = 'D (DP)';
                    statusElement.className = 'font-medium text-blue-600';
                }
            } else {
                // Pembayaran selanjutnya
                const totalAfterPayment = sudahDibayar + paymentAmount;
                if (totalAfterPayment >= totalTransaksi) {
                    statusElement.textContent = 'P (Pelunasan)';
                    statusElement.className = 'font-medium text-green-600';
                } else {
                    statusElement.textContent = 'A (Angsuran)';
                    statusElement.className = 'font-medium text-orange-600';
                }
            }
        }

        // Initialize payment method selection
        function initializePaymentMethod() {
            const paymentMethodRadios = document.querySelectorAll('.payment-method-radio');

            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected class from all cards
                    document.querySelectorAll('.payment-method-card').forEach(card => {
                        card.classList.remove('selected');
                    });

                    // Add selected class to checked card
                    if (this.checked) {
                        this.closest('.payment-method-option').querySelector('.payment-method-card')
                            .classList.add('selected');
                    }
                });
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Setup number input formatting
            const paymentAmountInput = document.getElementById('paymentAmount');
            if (paymentAmountInput) {
                setupNumberInput(paymentAmountInput);
                paymentAmountInput.addEventListener('input', updatePaymentInfo);
            }

            // Initialize payment method selection
            initializePaymentMethod();
        });

        // Delete Payment Functions
        function confirmDeletePayment(paymentId, noBukti) {
            Swal.fire({
                title: 'Hapus Pembayaran?',
                text: `Apakah Anda yakin ingin menghapus pembayaran "${noBukti}"?`,
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

            fetch(`/pembayaran-pembelian/${paymentId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
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
                    } else {
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
    </script>
@endsection
