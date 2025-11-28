@extends('layouts.pos')

@section('title', 'Detail Pembelian')
@section('page-title', 'Detail Pembelian')

@section('content')
    <div class="mx-4 xl:mx-6 2xl:mx-8 space-y-6">
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
            <div class="px-4 py-3 md:px-6 md:py-4 border-b bg-gray-50">
                <!-- Desktop Header -->
                <div class="hidden md:flex items-center justify-between">
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

                <!-- Mobile Header -->
                <div class="block md:hidden">
                    <div class="flex items-center space-x-3 mb-3">
                        <a href="{{ route('pembelian.index') }}"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-lg font-bold text-gray-900 truncate">{{ $pembelian->no_faktur }}</h1>
                            <p class="text-xs text-gray-500">{{ $pembelian->tanggal->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                            {{ $config['label'] }}
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $jenisTransaksiConfig['bg'] }} {{ $jenisTransaksiConfig['text'] }}">
                            <i class="ti {{ $jenisTransaksiConfig['icon'] }} text-xs mr-1"></i>
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
                    <div class="p-4 md:p-6">
                        <!-- Supplier Profile Section -->
                        <div
                            class="flex flex-col md:flex-row md:items-start md:space-x-6 space-y-4 md:space-y-0 mb-4 md:mb-6 pb-4 md:pb-6 border-b border-gray-100">
                            <!-- Supplier Photo -->
                            <div class="flex-shrink-0 flex justify-center md:justify-start">
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center shadow-md">
                                    <i class="ti ti-building-store text-2xl md:text-3xl text-white"></i>
                                </div>
                            </div>

                            <!-- Supplier Basic Info -->
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="text-lg md:text-xl font-bold text-gray-900 mb-1">
                                    {{ $pembelian->supplier->nama ?? 'N/A' }}
                                </h4>
                                <div class="flex items-center justify-center md:justify-start text-sm text-gray-500 mb-2">
                                    <i class="ti ti-id-badge mr-1"></i>
                                    {{ $pembelian->supplier->kode_supplier ?? 'N/A' }}
                                </div>
                                @if ($pembelian->supplier->telepon)
                                    <div
                                        class="flex items-center justify-center md:justify-start text-sm text-gray-600 mb-1">
                                        <i class="ti ti-phone text-green-600 mr-2"></i>
                                        <a href="tel:{{ $pembelian->supplier->telepon }}" class="hover:text-orange-600">
                                            {{ $pembelian->supplier->telepon }}
                                        </a>
                                    </div>
                                @endif
                                @if ($pembelian->supplier->email)
                                    <div
                                        class="flex items-center justify-center md:justify-start text-sm text-gray-600 mb-1">
                                        <i class="ti ti-mail text-blue-600 mr-2"></i>
                                        <a href="mailto:{{ $pembelian->supplier->email }}"
                                            class="hover:text-orange-600 break-all">
                                            {{ $pembelian->supplier->email }}
                                        </a>
                                    </div>
                                @endif
                                @if ($pembelian->supplier->alamat)
                                    <div class="flex items-start justify-center md:justify-start text-sm text-gray-600">
                                        <i class="ti ti-map-pin text-red-500 mr-2 mt-0.5"></i>
                                        <span class="text-center md:text-left">{{ $pembelian->supplier->alamat }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
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
                    <div class="px-4 md:px-6 py-3 md:py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center text-sm md:text-base">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-shopping-cart text-green-600"></i>
                            </div>
                            Detail Produk ({{ $pembelian->detailPembelian->count() }} item)
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="space-y-3 md:space-y-4">
                            @foreach ($pembelian->detailPembelian as $detail)
                                <div
                                    class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-4 p-3 md:p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
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
                                    <div class="flex-1 w-full md:w-auto">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <h5 class="font-semibold text-gray-900 text-sm md:text-base w-full md:w-auto">
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

                                        <div
                                            class="flex flex-wrap items-center gap-2 md:gap-4 text-xs md:text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <i class="ti ti-package text-orange-500 mr-1"></i>
                                                <span class="font-medium">{{ number_format($detail->qty, 2, ',', '.') }}
                                                    {{ $detail->produk->satuan->nama ?? 'pcs' }}</span>
                                            </div>
                                            @if ($detail->qty_discount > 0)
                                                <div class="flex items-center">
                                                    <i class="ti ti-minus text-blue-500 mr-1"></i>
                                                    <span
                                                        class="text-blue-600 font-medium">-{{ number_format($detail->qty_discount, 2, ',', '.') }}
                                                        {{ $detail->produk->satuan->nama ?? 'pcs' }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="ti ti-equals text-green-500 mr-1"></i>
                                                    <span
                                                        class="text-green-600 font-semibold">{{ number_format($detail->qty - $detail->qty_discount, 2, ',', '.') }}
                                                        {{ $detail->produk->satuan->nama ?? 'pcs' }}</span>
                                                </div>
                                            @endif
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

                                        @if ($detail->keterangan)
                                            <div class="flex items-start mt-2">
                                                <i class="ti ti-note text-gray-500 mr-1 mt-0.5"></i>
                                                <span class="text-xs text-gray-600 italic">
                                                    "{{ $detail->keterangan }}"
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="text-left md:text-right w-full md:w-auto">
                                        <div class="text-base md:text-lg font-bold text-gray-900">
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
                @if (count($riwayatPembayaran) > 0)
                    <div class="bg-white rounded-lg shadow border">
                        <div class="px-4 md:px-6 py-3 md:py-4 border-b bg-gradient-to-r from-purple-50 to-pink-50">
                            <h3 class="font-semibold text-gray-900 flex items-center text-sm md:text-base">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="ti ti-wallet text-purple-600"></i>
                                </div>
                                Riwayat Pembayaran ({{ count($riwayatPembayaran) }} transaksi)
                            </h3>
                        </div>
                        <div class="p-4 md:p-6">
                            @php
                                // Tentukan payment terakhir di luar loop (lebih efisien)
                                // Karena data sudah diorder by id asc dari controller, payment terakhir adalah yang ID-nya terbesar
                                $latestPaymentId = $riwayatPembayaran->max('id');
                                $today = \Carbon\Carbon::today();
                            @endphp
                            <div class="space-y-3 md:space-y-4">
                                @foreach ($riwayatPembayaran as $pembayaran)
                                    @php
                                        // Check if this is the latest payment (by ID)
                                        $isLatestPayment = $pembayaran->id == $latestPaymentId;
                                        $paymentDate = \Carbon\Carbon::parse($pembayaran->created_at)->startOfDay();

                                        // Payment can only be deleted if:
                                        // 1. It's created today AND
// 2. It's the latest payment (highest ID)
                                        $canDelete = $today->equalTo($paymentDate) && $isLatestPayment;
                                    @endphp
                                    <div
                                        class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-4 p-3 md:p-4 {{ ($pembayaran->status_uang_muka ?? 0) == 1 ? 'bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200' : 'bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200' }} hover:shadow-md transition-all duration-200">
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

                                                // Jika menggunakan uang muka, gunakan warna ungu
                                                if (($pembayaran->status_uang_muka ?? 0) == 1) {
                                                    $color = 'from-purple-400 to-pink-500';
                                                    $icon = 'ti-wallet';
                                                } else {
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
                                                }
                                            @endphp
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br {{ $color }} rounded-xl flex items-center justify-center shadow-sm">
                                                <i class="ti {{ $icon }} text-white text-lg"></i>
                                            </div>
                                        </div>

                                        <!-- Payment Info -->
                                        <div class="flex-1 w-full md:w-auto">
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <h5
                                                    class="font-semibold text-gray-900 text-sm md:text-base w-full md:w-auto">
                                                    {{ $pembayaran->no_bukti }}</h5>
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

                                                @if (($pembayaran->status_uang_muka ?? 0) == 1)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        <i class="ti ti-wallet mr-1"></i>
                                                        Uang Muka
                                                    </span>
                                                @endif

                                                <!-- User Badge -->
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    <i class="ti ti-user mr-1"></i>
                                                    <span
                                                        class="truncate max-w-[100px]">{{ $pembayaran->user->name ?? 'Unknown' }}</span>
                                                </span>
                                            </div>

                                            <div
                                                class="flex flex-wrap items-center gap-2 md:gap-4 text-xs md:text-sm text-gray-600 mb-2">
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
                                                    <span>{{ $pembayaran->created_at->format('H:i') }}</span>
                                                </div>
                                            </div>

                                            @if ($pembayaran->keterangan)
                                                <div class="flex items-center mt-1">
                                                    <i class="ti ti-note text-gray-400 mr-1"></i>
                                                    <span
                                                        class="text-xs text-gray-500 line-clamp-2">{{ $pembayaran->keterangan }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Payment Amount & Actions -->
                                        <div
                                            class="flex items-center justify-between md:block md:text-right w-full md:w-auto">
                                            <div class="text-base md:text-lg font-bold text-gray-900">
                                                Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                            </div>
                                            <!-- Action Buttons -->
                                            <div class="flex items-center space-x-2 md:ml-4 md:mt-2 md:justify-end">
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
                    <div class="px-4 md:px-6 py-3 md:py-4 border-b bg-gradient-to-r from-indigo-50 to-blue-50">
                        <h3 class="font-semibold text-gray-900 flex items-center text-sm md:text-base">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-calculator text-indigo-600"></i>
                            </div>
                            Ringkasan Keuangan
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 space-y-3 md:space-y-4">
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
                    <div class="px-4 md:px-6 py-3 md:py-4 border-b bg-gradient-to-r from-gray-50 to-slate-50">
                        <h3 class="font-semibold text-gray-900 flex items-center text-sm md:text-base">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-settings text-gray-600"></i>
                            </div>
                            Aksi Transaksi
                        </h3>
                    </div>
                    <div class="p-4 md:p-6 space-y-2 md:space-y-3">
                        <!-- Print Buttons -->
                        <div class="space-y-2">
                            <!-- QZ Tray Print Button -->
                            <button onclick="printInvoiceWithQZTray(event)"
                                class="w-full flex items-center justify-center px-3 md:px-4 py-2.5 md:py-3 bg-gradient-to-r from-gray-600 to-slate-600 text-white rounded-lg hover:from-gray-700 hover:to-slate-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-2 md:mr-3">
                                    <i class="ti ti-printer text-sm"></i>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <span id="printButtonText" class="font-medium text-sm md:text-base">Cetak Invoice (QZ
                                        Tray)</span>
                                    <div id="printerInfo" class="text-xs text-white/80 font-normal hidden md:block">
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

                            <!-- RAW BT Print Button (Mobile) -->
                            <a href="{{ route('pembelian.cetak-rawbt', $pembelian->encrypted_id) }}" target="_blank"
                                class="w-full md:hidden flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <div class="w-4 h-4 bg-white/20 rounded flex items-center justify-center mr-2">
                                    <i class="ti ti-bluetooth text-xs"></i>
                                </div>
                                <span>Cetak RAW BT</span>
                            </a>

                            <!-- Browser Print Button -->
                            <button onclick="printInvoiceRaw()"
                                class="hidden w-full px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <div class="w-4 h-4 bg-white/20 rounded flex items-center justify-center mr-2">
                                    <i class="ti ti-device-floppy text-xs"></i>
                                </div>
                                Cetak Raw (Browser)
                            </button>

                            <!-- Export PDF Button -->
                            <button onclick="exportToPDF()"
                                class="w-full flex items-center justify-center px-3 py-2.5 md:py-2 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg hover:from-red-600 hover:to-rose-600 transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <div class="w-4 h-4 bg-white/20 rounded flex items-center justify-center mr-2">
                                    <i class="ti ti-file-download text-xs"></i>
                                </div>
                                Export PDF
                            </button>
                        </div>

                        @if ($pembelian->status_pembayaran !== 'lunas')
                            <!-- Edit Button -->
                            <a href="{{ route('pembelian.edit', $pembelian->encrypted_id) }}"
                                class="w-full flex items-center justify-center px-3 md:px-4 py-2.5 md:py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-sm hover:shadow-md text-sm md:text-base">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-2 md:mr-3">
                                    <i class="ti ti-edit text-sm"></i>
                                </div>
                                Edit Transaksi
                            </a>
                        @endif

                        @if ($sisaPembayaran > 0)
                            <!-- Payment Button -->
                            <button type="button" onclick="openPaymentModal()"
                                class="w-full flex items-center justify-center px-3 md:px-4 py-2.5 md:py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md text-sm md:text-base">
                                <div class="w-5 h-5 bg-white/20 rounded flex items-center justify-center mr-2 md:mr-3">
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
        <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto">
            <div class="flex items-start justify-center min-h-full p-2 sm:p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-auto my-4 sm:my-8 max-h-full flex flex-col">
                    <!-- Modal Header -->
                    <div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50 flex-shrink-0">
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
                    <div class="p-4 sm:p-6 flex-1 overflow-y-auto min-h-0">
                        <!-- Payment Summary - Compact -->
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
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

                            <div class="space-y-3 sm:space-y-4">
                                <!-- Row 1: Tanggal dan Nominal bersebelahan -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <!-- Payment Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tanggal Pembayaran <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="tanggal" id="paymentDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 bg-gray-50"
                                            value="{{ date('d/m/Y') }}" required>
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
                                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 text-right text-lg font-semibold {{ $pembelian->jenis_transaksi == 'tunai' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                                placeholder="0" @if ($pembelian->jenis_transaksi == 'tunai') readonly @endif>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: Keterangan full width -->
                                <div>
                                    <textarea name="keterangan" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                        placeholder="Keterangan (opsional)..."></textarea>
                                </div>

                                <!-- Uang Muka Supplier (jika ada) -->
                                @if ($pembelian->supplier_id)
                                    <div id="paymentUangMukaContainer" class="hidden">
                                        <div class="mb-3">
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" id="useUangMukaCheckbox"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">
                                                    <i class="ti ti-coin mr-1"></i>
                                                    Gunakan Uang Muka
                                                </span>
                                            </label>
                                        </div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="ti ti-info-circle mr-1"></i>
                                            Informasi Uang Muka Tersedia
                                        </label>
                                        <div id="paymentUangMukaList"
                                            class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                            <p class="text-sm text-gray-500 text-center py-4"
                                                id="paymentUangMukaEmptyMessage">
                                                Memuat uang muka yang tersedia...
                                            </p>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <i class="ti ti-info-circle mr-1"></i>
                                            Centang "Gunakan Uang Muka" untuk menggunakan sisa uang muka sebagai jumlah
                                            pembayaran
                                        </div>
                                    </div>

                                    <!-- Hidden input untuk default value ketika menggunakan uang muka -->
                                    <input type="hidden" id="hiddenMetodePembayaran" value="TUNAI">
                                    <input type="hidden" id="hiddenKasBankId" value="1">
                                @endif

                                <!-- Row 3: Metode Pembayaran -->
                                <div id="paymentMethodSection">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid gap-3" id="paymentMethodContainer"
                                        style="grid-template-columns: repeat({{ count($metodePembayaran) }}, 1fr);">
                                        @foreach ($metodePembayaran as $metode)
                                            <label class="relative cursor-pointer payment-method-option">
                                                <input type="radio" name="metode_pembayaran"
                                                    value="{{ $metode->kode }}"
                                                    {{ old('metode_pembayaran') == $metode->kode ? 'checked' : '' }}
                                                    class="sr-only payment-method-radio">
                                                <div
                                                    class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 payment-method-card">
                                                    <div class="flex items-center space-x-3">
                                                        <div
                                                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <i
                                                                class="ti {{ $metode->icon_display }} text-blue-600 text-lg"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <span
                                                                class="text-sm font-medium text-gray-900 block">{{ $metode->nama }}</span>
                                                            <span class="text-xs text-gray-500">{{ $metode->kode }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Row 4: Kas/Bank Selection -->
                                <div id="kasBankSection">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kas/Bank <span class="text-red-500">*</span>
                                    </label>

                                    <!-- Message when no payment method selected -->
                                    <div id="kasBankMessage" class="text-center py-4 text-gray-500">
                                        <i class="ti ti-arrow-up text-xl mb-2"></i>
                                        <p class="text-sm">Pilih metode pembayaran terlebih dahulu untuk melihat pilihan
                                            kas/bank</p>
                                    </div>

                                    <div class="hidden flex flex-col gap-3" id="kasBankContainer">
                                        @foreach ($kasBank as $kas)
                                            <label class="relative cursor-pointer kas-bank-option">
                                                <input type="radio" name="kas_bank_id" value="{{ $kas->id }}"
                                                    data-jenis="{{ $kas->jenis }}"
                                                    data-image="{{ $kas->image_url ?? '' }}"
                                                    class="sr-only kas-bank-radio">
                                                <div
                                                    class="hidden p-4 border-2 border-gray-200 rounded-xl hover:border-blue-400 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 kas-bank-card flex items-center justify-between shadow-sm hover:shadow-md w-full">
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
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-sm">
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
                            <div class="mt-4 flex space-x-2 sm:space-x-3">
                                <button type="button" onclick="closePaymentModal()"
                                    class="flex-1 px-3 sm:px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 text-sm sm:text-base">
                                    Batal
                                </button>
                                <button type="submit" id="submitPaymentBtn"
                                    class="flex-1 px-3 sm:px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-colors duration-200 font-medium text-sm sm:text-base">
                                    <span id="submitBtnText">Simpan Pembayaran</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Section (Hidden) -->
    <div class="print-section" style="display: none;">
        <div
            style="font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.4; max-width: 300px; margin: 0 auto;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 20px;">
                @if ($pengaturanUmum->logo_url)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ $pengaturanUmum->logo_url }}" alt="{{ $pengaturanUmum->nama_toko }}"
                            style="max-width: 150px; max-height: 60px; object-fit: contain;">
                    </div>
                @endif
                <h1 style="font-size: 18px; font-weight: bold; margin: 0;">{{ $pengaturanUmum->nama_toko }}</h1>
                @if ($pengaturanUmum->deskripsi)
                    <p style="margin: 5px 0; font-size: 12px;">{{ $pengaturanUmum->deskripsi }}</p>
                @endif
                @if ($pengaturanUmum->alamat)
                    <p style="margin: 5px 0; font-size: 11px;">{{ $pengaturanUmum->alamat }}</p>
                @endif
                @if ($pengaturanUmum->no_telepon)
                    <p style="margin: 5px 0; font-size: 11px;">Telp: {{ $pengaturanUmum->no_telepon }}</p>
                @endif
                @if ($pengaturanUmum->email)
                    <p style="margin: 5px 0; font-size: 11px;">Email: {{ $pengaturanUmum->email }}</p>
                @endif
                <hr style="border: none; border-top: 1px solid #000; margin: 10px 0;">
            </div>

            <!-- Invoice Info -->
            <div style="margin-bottom: 15px;">
                <h2 style="font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">PEMBELIAN</h2>
                <p style="margin: 2px 0;"><strong>No. Faktur:</strong> {{ $pembelian->no_faktur }}</p>
                <p style="margin: 2px 0;"><strong>Tanggal:</strong> {{ $pembelian->tanggal->format('d/m/Y H:i') }}</p>
                <p style="margin: 2px 0;"><strong>Supplier:</strong> {{ $pembelian->supplier->nama ?? 'N/A' }}</p>
                <p style="margin: 2px 0;"><strong>Kasir:</strong> {{ $pembelian->user->name ?? 'N/A' }}</p>
                <hr style="border: none; border-top: 1px solid #000; margin: 10px 0;">
            </div>

            <!-- Items -->
            <div style="margin-bottom: 15px;">
                @foreach ($pembelian->detailPembelian as $detail)
                    <div style="margin-bottom: 8px;">
                        <p style="margin: 2px 0; font-weight: bold;">{{ $detail->produk->nama_produk }}</p>
                        <p style="margin: 2px 0; font-size: 11px;">
                            {{ number_format($detail->qty, 2, ',', '.') }} {{ $detail->produk->satuan->nama ?? 'pcs' }}
                            @if ($detail->qty_discount > 0)
                                - {{ number_format($detail->qty_discount, 2, ',', '.') }}
                                {{ $detail->produk->satuan->nama ?? 'pcs' }}
                                = {{ number_format($detail->qty - $detail->qty_discount, 2, ',', '.') }}
                                {{ $detail->produk->satuan->nama ?? 'pcs' }}
                            @endif
                            x {{ number_format($detail->harga_beli, 0, ',', '.') }} =
                            {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </p>
                        @if ($detail->keterangan)
                            <p style="margin: 2px 0; font-size: 10px; font-style: italic; color: #666;">
                                Note: {{ $detail->keterangan }}
                            </p>
                        @endif
                    </div>
                @endforeach
                <hr style="border: none; border-top: 1px solid #000; margin: 10px 0;">
            </div>

            <!-- Totals -->
            <div style="text-align: right; margin-bottom: 15px;">
                <p style="margin: 2px 0; font-size: 11px;">Subtotal: {{ number_format($pembelian->total, 0) }}</p>
                @if ($pembelian->diskon > 0)
                    <p style="margin: 2px 0; font-size: 11px;">Diskon: -{{ number_format($pembelian->diskon, 0) }}</p>
                @endif
                @if ($pembelian->ppn > 0)
                    <p style="margin: 2px 0; font-size: 11px;">PPN: {{ number_format($pembelian->ppn, 0) }}</p>
                @endif
                <p style="margin: 5px 0; font-weight: bold; font-size: 13px;">TOTAL:
                    {{ number_format($pembelian->grand_total, 0) }}</p>
            </div>

            <!-- Payment Info -->
            @if ($pembelian->pembayaranPembelian->count() > 0)
                <div style="margin-bottom: 15px;">
                    <h3 style="font-size: 12px; font-weight: bold; margin: 0 0 8px 0;">PEMBAYARAN:</h3>
                    @foreach ($pembelian->pembayaranPembelian as $pembayaran)
                        <p style="margin: 2px 0; font-size: 11px;">{{ $pembayaran->metode_pembayaran }}:
                            {{ number_format($pembayaran->jumlah_bayar, 0) }}</p>
                    @endforeach
                    <hr style="border: none; border-top: 1px solid #000; margin: 8px 0;">
                    <div style="text-align: right;">
                        <p style="margin: 2px 0; font-size: 11px; font-weight: bold;">Total Bayar:
                            {{ number_format($pembelian->pembayaranPembelian->sum('jumlah_bayar'), 0) }}</p>
                        @if ($pembelian->pembayaranPembelian->sum('jumlah_bayar') < $pembelian->grand_total)
                            <p style="margin: 2px 0; font-size: 11px; font-weight: bold;">Sisa:
                                {{ number_format($pembelian->grand_total - $pembelian->pembayaranPembelian->sum('jumlah_bayar'), 0) }}
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <div style="margin-bottom: 15px; text-align: center;">
                    <p style="margin: 2px 0; font-size: 11px; font-weight: bold;">BELUM ADA PEMBAYARAN</p>
                </div>
            @endif

            <!-- Footer -->
            <div style="text-align: center; margin-top: 20px;">
                <hr style="border: none; border-top: 1px solid #000; margin: 10px 0;">
                <p style="margin: 5px 0; font-size: 11px;">Terima kasih atas pembelian Anda!</p>
                <p style="margin: 5px 0; font-size: 10px;">{{ date('d/m/Y H:i:s') }}</p>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            const paymentAmountInput = document.getElementById('paymentAmount');
            const sisaPembayaran = {{ $pembelian->total - $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};

            // Set payment amount value
            paymentAmountInput.value = sisaPembayaran.toLocaleString('id-ID');

            // Set readonly for tunai transactions
            @if ($pembelian->jenis_transaksi == 'tunai')
                paymentAmountInput.setAttribute('readonly', 'readonly');
                paymentAmountInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            @else
                paymentAmountInput.removeAttribute('readonly');
                paymentAmountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            @endif

            updatePaymentInfo();

            // Initialize Flatpickr for payment date
            flatpickr("#paymentDate", {
                dateFormat: "d/m/Y",
                defaultDate: "today",
                allowInput: true,
                clickOpens: true,
                locale: "id"
            });

            // Load uang muka jika ada supplier
            @if ($pembelian->supplier_id)
                loadPaymentUangMukaSupplier({{ $pembelian->supplier_id }});
            @endif
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

        // Function to load uang muka supplier for payment form
        function loadPaymentUangMukaSupplier(supplierId) {
            const container = document.getElementById('paymentUangMukaList');
            const emptyMessage = document.getElementById('paymentUangMukaEmptyMessage');
            const uangMukaContainer = document.getElementById('paymentUangMukaContainer');

            // Clear previous data
            if (container) {
                const existingItems = container.querySelectorAll('.bg-white.border');
                existingItems.forEach(item => item.remove());
            }
            if (uangMukaContainer) {
                uangMukaContainer.classList.add('hidden');
            }
            if (emptyMessage && emptyMessage.parentNode) {
                emptyMessage.style.display = 'block';
                emptyMessage.textContent = 'Memuat uang muka yang tersedia...';
            }

            fetch(`/uang-muka-supplier/get-available?supplier_id=${supplierId}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success || data.data.length === 0) {
                        if (emptyMessage) {
                            emptyMessage.textContent = 'Tidak ada uang muka yang tersedia untuk supplier ini';
                        }
                        if (uangMukaContainer) {
                            uangMukaContainer.classList.add('hidden');
                        }
                    } else {
                        // Hide empty message
                        if (emptyMessage) {
                            emptyMessage.style.display = 'none';
                        }

                        // Render uang muka items (hanya informasi, tanpa checkbox dan input)
                        container.innerHTML = data.data.map(um => `
                            <div class="bg-white border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-sm font-medium text-gray-900">${um.no_uang_muka}</span>
                                            <span class="text-xs text-gray-500">${um.tanggal}</span>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            <span>Jumlah Awal: <strong class="text-gray-700">Rp ${formatNumber(um.jumlah_uang_muka)}</strong></span>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <div class="text-xs text-gray-500 mb-1">Sisa</div>
                                        <div class="text-sm font-bold text-green-600">Rp ${formatNumber(um.sisa_uang_muka)}</div>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        // Setup checkbox handler untuk menggunakan uang muka
                        const useUangMukaCheckbox = document.getElementById('useUangMukaCheckbox');
                        if (useUangMukaCheckbox) {
                            // Simpan data uang muka untuk digunakan
                            window.paymentUangMukaData = data.data;

                            // Hapus event listener lama dengan clone element
                            const oldCheckbox = useUangMukaCheckbox;
                            const newCheckbox = oldCheckbox.cloneNode(true);
                            oldCheckbox.parentNode.replaceChild(newCheckbox, oldCheckbox);

                            // Tambahkan event listener baru
                            document.getElementById('useUangMukaCheckbox').addEventListener('change', function() {
                                const paymentAmountInput = document.getElementById('paymentAmount');
                                const paymentMethodSection = document.getElementById('paymentMethodSection');
                                const kasBankSection = document.getElementById('kasBankSection');
                                const hiddenMetodePembayaran = document.getElementById(
                                    'hiddenMetodePembayaran');
                                const hiddenKasBankId = document.getElementById('hiddenKasBankId');

                                if (this.checked && data.data.length > 0) {
                                    // Isi field jumlah pembayaran dengan sisa pembayaran (bukan sisa uang muka)
                                    const sisaPembayaran =
                                        {{ $pembelian->total - $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};
                                    if (paymentAmountInput) {
                                        paymentAmountInput.value = formatNumberInput(sisaPembayaran.toString());
                                        updatePaymentInfo();
                                    }

                                    // Hide metode pembayaran dan kas/bank section
                                    if (paymentMethodSection) {
                                        paymentMethodSection.style.display = 'none';
                                    }
                                    if (kasBankSection) {
                                        kasBankSection.style.display = 'none';
                                    }

                                    // Set default value untuk hidden input
                                    if (hiddenMetodePembayaran) {
                                        hiddenMetodePembayaran.value = 'TUNAI';
                                    }
                                    if (hiddenKasBankId) {
                                        hiddenKasBankId.value = '1';
                                    }

                                    // Uncheck semua radio button yang ada
                                    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(
                                        radio => {
                                            radio.checked = false;
                                        });
                                    document.querySelectorAll('input[name="kas_bank_id"]').forEach(radio => {
                                        radio.checked = false;
                                    });
                                } else {
                                    // Reset ke sisa pembayaran jika uncheck
                                    const sisaPembayaran =
                                        {{ $pembelian->total - $pembelian->pembayaranPembelian->sum('jumlah_bayar') }};
                                    if (paymentAmountInput) {
                                        paymentAmountInput.value = formatNumberInput(sisaPembayaran.toString());
                                        updatePaymentInfo();
                                    }

                                    // Show metode pembayaran dan kas/bank section
                                    if (paymentMethodSection) {
                                        paymentMethodSection.style.display = 'block';
                                    }
                                    if (kasBankSection) {
                                        kasBankSection.style.display = 'block';
                                    }

                                    // Clear hidden input value
                                    if (hiddenMetodePembayaran) {
                                        hiddenMetodePembayaran.value = '';
                                    }
                                    if (hiddenKasBankId) {
                                        hiddenKasBankId.value = '';
                                    }
                                }
                            });
                        }

                        // Show container
                        if (uangMukaContainer) {
                            uangMukaContainer.classList.remove('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading uang muka:', error);
                    if (emptyMessage) {
                        emptyMessage.textContent = 'Error memuat uang muka';
                    }
                    if (uangMukaContainer) {
                        uangMukaContainer.classList.add('hidden');
                    }
                });
        }

        // Format number helper
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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
            let metodePembayaran = formData.get('metode_pembayaran');
            let kasBankId = formData.get('kas_bank_id');

            // Check if using uang muka
            const useUangMukaCheckbox = document.getElementById('useUangMukaCheckbox');
            if (useUangMukaCheckbox && useUangMukaCheckbox.checked) {
                // Jika checkbox dicentang, ambil dari hidden input
                const hiddenMetodePembayaran = document.getElementById('hiddenMetodePembayaran');
                const hiddenKasBankId = document.getElementById('hiddenKasBankId');
                if (hiddenMetodePembayaran) {
                    metodePembayaran = hiddenMetodePembayaran.value;
                    formData.set('metode_pembayaran', metodePembayaran);
                }
                if (hiddenKasBankId) {
                    kasBankId = hiddenKasBankId.value;
                    formData.set('kas_bank_id', kasBankId);
                }
                // Tambahkan flag use_uang_muka
                formData.append('use_uang_muka', '1');
            }

            let isValid = true;

            // Validate payment method first (skip jika menggunakan uang muka)
            if (!useUangMukaCheckbox || !useUangMukaCheckbox.checked) {
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
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            // Handle validation errors safely
                            let errorMessage = 'Terjadi kesalahan pada server';
                            if (err.message) {
                                errorMessage = err.message;
                            } else if (err.errors && typeof err.errors === 'object') {
                                // Get first error message from errors object
                                const errorKeys = Object.keys(err.errors);
                                if (errorKeys.length > 0) {
                                    const firstErrorKey = errorKeys[0];
                                    const firstError = err.errors[firstErrorKey];
                                    if (Array.isArray(firstError) && firstError.length > 0) {
                                        errorMessage = firstError[0];
                                    } else if (typeof firstError === 'string') {
                                        errorMessage = firstError;
                                    }
                                }
                            }
                            throw new Error(errorMessage);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast(data.message, 'success');

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
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    }
                })
                .then(data => {
                    console.log('Print response (fallback):', data);
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
            const button = event.target.closest('button');
            const buttonText = document.getElementById('printButtonText');

            // Disable button and show loading
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            buttonText.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Mencetak...';

            // Check if QZ Tray is available
            if (typeof qz === 'undefined') {
                console.log('QZ Tray not loaded, loading script...');
                loadQZTrayScript().then(() => {
                    printWithQZTray();
                }).catch(error => {
                    console.error('Failed to load QZ Tray:', error);
                    showPrintError('Gagal memuat QZ Tray');
                    resetPrintButton(button, buttonText);
                });
            } else {
                printWithQZTray();
            }
        }

        function printWithQZTray() {
            try {
                // Check if QZ Tray is available
                if (typeof qz === 'undefined') {
                    console.log('QZ Tray not loaded, loading script...');
                    loadQZTrayScript();
                    return;
                }

                // Connect and get printer from database
                qz.websocket.connect({
                    retries: 2,
                    delay: 1
                }).then(function() {
                    console.log('QZ Tray connected for invoice printing');

                    // Get default printer from database
                    fetch('{{ route('printer.get-settings') }}')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Printer settings response (invoice):', data);
                            if (data && data.success && data.settings && data.settings.default_printer) {
                                console.log('Using printer from database:', data.settings.default_printer);
                                printInvoice(data.settings.default_printer);
                            } else {
                                // Find any available printer
                                qz.printers.find().then(function(printers) {
                                    if (printers && printers.length > 0) {
                                        console.log('Using first available printer:', printers[0]);
                                        printInvoice(printers[0]);
                                    } else {
                                        console.log('No printers found for invoice printing');
                                        showPrintError('Tidak ada printer yang tersedia');
                                        resetPrintButton();
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error getting printer settings:', error);
                            // Fallback to first available printer
                            qz.printers.find().then(function(printers) {
                                if (printers && printers.length > 0) {
                                    console.log('Using first available printer (fallback):', printers[
                                        0]);
                                    printInvoice(printers[0]);
                                } else {
                                    showPrintError('Tidak ada printer yang tersedia');
                                    resetPrintButton();
                                }
                            });
                        });
                }).catch(error => {
                    console.error('QZ Tray connection failed:', error);
                    showPrintError('Gagal terhubung ke QZ Tray: ' + error.message);
                    resetPrintButton();
                });
            } catch (error) {
                console.error('Print error:', error);
                showPrintError('Terjadi kesalahan saat mencetak');
                resetPrintButton();
            }
        }

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
                    <title>Invoice {{ $pembelian->no_faktur }}</title>
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
            content += '        {{ $pengaturanUmum->nama_toko }}\n';
            @if ($pengaturanUmum->deskripsi)
                content += '       {{ $pengaturanUmum->deskripsi }}\n';
            @endif
            @if ($pengaturanUmum->alamat)
                content += '      {{ $pengaturanUmum->alamat }}\n';
            @endif
            @if ($pengaturanUmum->no_telepon)
                content += '      Telp: {{ $pengaturanUmum->no_telepon }}\n';
            @endif
            @if ($pengaturanUmum->email)
                content += '      Email: {{ $pengaturanUmum->email }}\n';
            @endif
            content += '================================\n\n';

            // Invoice info
            content += 'PEMBELIAN\n';
            content += 'No. Faktur: {{ $pembelian->no_faktur }}\n';
            content += 'Tanggal: {{ $pembelian->created_at->format('d/m/Y H:i') }}\n';
            content += 'Supplier: {{ $pembelian->supplier->nama ?? 'N/A' }}\n';
            content += 'Kasir: {{ $pembelian->user->name ?? 'N/A' }}\n';
            content += '================================\n\n';

            // Items
            @foreach ($pembelian->detailPembelian as $detail)
                content += '{{ substr($detail->produk->nama_produk, 0, 30) }}\n';
                content +=
                    '  {{ number_format($detail->qty, 2, ',', '.') }} {{ $detail->produk->satuan->nama ?? 'pcs' }}';
                @if ($detail->qty_discount > 0)
                    content +=
                        ' - {{ number_format($detail->qty_discount, 2, ',', '.') }} {{ $detail->produk->satuan->nama ?? 'pcs' }}';
                    content +=
                        ' = {{ number_format($detail->qty - $detail->qty_discount, 2, ',', '.') }} {{ $detail->produk->satuan->nama ?? 'pcs' }}';
                @endif
                content +=
                    ' x {{ number_format($detail->harga_beli, 0, ',', '.') }} = {{ number_format($detail->subtotal, 0, ',', '.') }}\n';
                @if ($detail->discount > 0)
                    content += '  Diskon: -{{ number_format($detail->discount, 0) }}\n';
                @endif
                @if ($detail->keterangan)
                    content += '  Note: {{ $detail->keterangan }}\n';
                @endif
                content += '\n';
            @endforeach

            content += '--------------------------------\n';

            // Totals
            content += 'Subtotal: Rp {{ number_format($pembelian->total, 0) }}\n';

            @if ($pembelian->diskon > 0)
                content += 'Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n';
            @endif

            @if ($pembelian->ppn > 0)
                content += 'PPN: Rp {{ number_format($pembelian->ppn, 0) }}\n';
            @endif

            content += 'TOTAL: Rp {{ number_format($pembelian->grand_total, 0) }}\n\n';

            // Payment info
            @php
                $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            @endphp
            @if ($totalBayar > 0)
                content += 'Bayar: Rp {{ number_format($totalBayar, 0) }}\n';

                @if ($totalBayar < $pembelian->grand_total)
                    @php
                        $sisa = $pembelian->grand_total - $totalBayar;
                    @endphp
                    content += 'Sisa: Rp {{ number_format($sisa, 0) }}\n';
                @endif
                content += '\n';
            @endif

            content += '================================\n';
            content += '   Terima kasih atas kunjungan Anda\n';
            content += '     Barang yang sudah dibeli\n';
            content += '     tidak dapat dikembalikan\n';
            content += '================================\n';

            return content;
        }

        function generateInvoiceData() {
            const invoiceLines = [];

            // Header
            invoiceLines.push("\x1B\x40"); // Initialize printer
            // @if ($pengaturanUmum->logo_url)
            //     invoiceLines.push({
            //         type: 'pixel',
            //         format: 'image',
            //         flavor: 'base64',
            //         data: getBase64FromUrl('{{ $pengaturanUmum->logo_url }}')
            //     });
            // @endif
            invoiceLines.push("\x1B\x61\x01"); // Center align
            invoiceLines.push("{{ $pengaturanUmum->nama_toko }}\n");
            @if ($pengaturanUmum->deskripsi)
                invoiceLines.push("{{ $pengaturanUmum->deskripsi }}\n");
            @endif
            @if ($pengaturanUmum->alamat)
                invoiceLines.push("{{ $pengaturanUmum->alamat }}\n");
            @endif
            @if ($pengaturanUmum->no_telepon)
                invoiceLines.push("Telp: {{ $pengaturanUmum->no_telepon }}\n");
            @endif
            @if ($pengaturanUmum->email)
                invoiceLines.push("Email: {{ $pengaturanUmum->email }}\n");
            @endif
            invoiceLines.push("================================\n");

            // Invoice info
            invoiceLines.push("\x1B\x61\x00"); // Left align
            invoiceLines.push("PEMBELIAN\n");
            invoiceLines.push("No. Faktur: {{ $pembelian->no_faktur }}\n");
            invoiceLines.push("Tanggal: {{ $pembelian->created_at->format('d/m/Y H:i') }}\n");
            invoiceLines.push("Supplier: {{ $pembelian->supplier->nama ?? 'N/A' }}\n");
            invoiceLines.push("Kasir: {{ $pembelian->user->name ?? 'N/A' }}\n");
            invoiceLines.push("================================\n");

            // Items
            @foreach ($pembelian->detailPembelian as $detail)
                invoiceLines.push("{{ substr($detail->produk->nama_produk, 0, 20) }}\n");
                @php
                    $qtyText = number_format($detail->qty, 2, ',', '.') . ' ' . ($detail->produk->satuan->nama ?? 'pcs');
                    if ($detail->qty_discount > 0) {
                        $qtyText .= ' - ' . number_format($detail->qty_discount, 2, ',', '.') . ' ' . ($detail->produk->satuan->nama ?? 'pcs');
                        $qtyText .= ' = ' . number_format($detail->qty - $detail->qty_discount, 2, ',', '.') . ' ' . ($detail->produk->satuan->nama ?? 'pcs');
                    }
                @endphp
                invoiceLines.push(
                    "  {{ $qtyText }} x {{ number_format($detail->harga_beli, 0, ',', '.') }} = {{ number_format($detail->subtotal, 0, ',', '.') }}\n"
                );
                @if ($detail->discount > 0)
                    invoiceLines.push("  Diskon: -{{ number_format($detail->discount, 0) }}\n");
                @endif
                @if ($detail->keterangan)
                    invoiceLines.push("  Note: {{ $detail->keterangan }}\n");
                @endif
            @endforeach

            invoiceLines.push("--------------------------------\n");

            // Totals
            invoiceLines.push("Subtotal: Rp {{ number_format($pembelian->total, 0) }}\n");

            @if ($pembelian->diskon > 0)
                invoiceLines.push("Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n");
            @endif

            @if ($pembelian->ppn > 0)
                invoiceLines.push("PPN: Rp {{ number_format($pembelian->ppn, 0) }}\n");
            @endif

            invoiceLines.push("TOTAL: Rp {{ number_format($pembelian->grand_total, 0) }}\n");

            // Payment info
            @php
                $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            @endphp
            @if ($totalBayar > 0)
                invoiceLines.push("Bayar: Rp {{ number_format($totalBayar, 0) }}\n");

                @if ($totalBayar < $pembelian->grand_total)
                    @php
                        $sisa = $pembelian->grand_total - $totalBayar;
                    @endphp
                    invoiceLines.push("Sisa: Rp {{ number_format($sisa, 0) }}\n");
                @endif
            @endif

            // Payment history detail
            @if ($pembelian->pembayaranPembelian->count() > 0)
                invoiceLines.push("================================\n");
                invoiceLines.push("RIWAYAT PEMBAYARAN:\n");
                invoiceLines.push("--------------------------------\n");
                invoiceLines.push(
                    "Faktur: {{ $pembelian->no_faktur }} - {{ $pembelian->created_at->format('d/m/Y H:i') }}\n");
                invoiceLines.push("--------------------------------\n");
                @foreach ($riwayatPembayaran as $pembayaran)
                    invoiceLines.push("{{ $pembayaran->no_bukti }}\n");
                    @php
                        $statusConfig = [
                            'D' => 'DP',
                            'A' => 'Angsuran',
                            'P' => 'Pelunasan',
                            'U' => 'Uang Muka',
                        ];
                        $status = $statusConfig[$pembayaran->status_bayar] ?? 'DP';
                    @endphp
                    invoiceLines.push(
                        "{{ $pembayaran->created_at->format('d/m/Y H:i') }} - {{ $pembayaran->metode_pembayaran }} ({{ $status }})\n"
                    );
                    invoiceLines.push("Rp {{ number_format($pembayaran->jumlah_bayar, 0) }}\n");
                    @if ($pembayaran->keterangan)
                        invoiceLines.push("{{ $pembayaran->keterangan }}\n");
                    @endif
                    invoiceLines.push("--------------------------------\n");
                @endforeach
            @endif

            invoiceLines.push("================================\n");
            invoiceLines.push("\x1B\x61\x01"); // Center align
            invoiceLines.push("Terima kasih atas kunjungan Anda\n");
            invoiceLines.push("\n\n\n");
            invoiceLines.push("\x1D\x56\x42\x00"); // Cut paper

            return invoiceLines;
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
                showPrintSuccess('✅ Invoice berhasil dicetak ke ' + printerName);

                // Reset button
                resetPrintButton();

                // Close connection after successful print
                setTimeout(function() {
                    if (qz.websocket && qz.websocket.disconnect) {
                        console.log('Closing QZ Tray connection after print');
                        qz.websocket.disconnect();
                    }
                }, 1000); // Wait 1 second before closing
            }).catch(function(err) {
                console.log('Invoice printing failed:', err);
                showPrintError('⚠️ Cetak invoice gagal: ' + err.message);
                resetPrintButton();

                // Close connection on error too
                setTimeout(function() {
                    if (qz.websocket && qz.websocket.disconnect) {
                        console.log('Closing QZ Tray connection after error');
                        qz.websocket.disconnect();
                    }
                }, 1000);
            });
        }

        // Update printer info when QZ Tray connects
        function updatePrinterInfo(printerName) {
            const printerInfo = document.getElementById('printerInfo');
            if (printerInfo) {
                printerInfo.textContent = `Printer: ${printerName}`;
            }
        }

        function loadQZTrayScript() {
            return new Promise((resolve, reject) => {
                if (typeof qz !== 'undefined') {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = '/js/qz/qz-tray.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        function showPrintSuccess(message) {
            showToast(message, 'success');
        }

        function showPrintError(message) {
            showToast(message, 'error');
        }

        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.className =
                `toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;

            // Set color based on type
            if (type === 'success') {
                toast.classList.add('bg-green-500');
            } else if (type === 'error') {
                toast.classList.add('bg-red-500');
            } else if (type === 'warning') {
                toast.classList.add('bg-yellow-500');
            } else {
                toast.classList.add('bg-blue-500');
            }

            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">
                        ${type === 'success' ? '<i class="ti ti-check-circle"></i>' : 
                          type === 'error' ? '<i class="ti ti-x-circle"></i>' : 
                          type === 'warning' ? '<i class="ti ti-alert-circle"></i>' : 
                          '<i class="ti ti-info-circle"></i>'}
                    </span>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        function resetPrintButton(button = null, buttonText = null) {
            // If no parameters provided, find the button and text elements
            if (!button) {
                button = document.querySelector('button[onclick*="printInvoiceWithQZTray"]');
            }
            if (!buttonText) {
                buttonText = document.getElementById('printButtonText');
            }

            if (button && buttonText) {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                buttonText.innerHTML = '<i class="ti ti-printer text-sm mr-2"></i>Cetak Struk';
            }
        }

        // Export to PDF function
        function exportToPDF() {
            const button = document.querySelector('button[onclick="exportToPDF()"]');
            const originalText = button.innerHTML;

            // Show loading state
            button.disabled = true;
            button.innerHTML = `
                <div class="w-4 h-4 bg-white/20 rounded flex items-center justify-center mr-2">
                    <i class="ti ti-loader animate-spin text-xs"></i>
                </div>
                Exporting...
            `;

            // Create a form to submit POST request for PDF download
            // This approach is more reliable than fetch for downloads
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('pembelian.export-pdf', $pembelian->encrypted_id) }}';
            form.style.display = 'none';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();

            // Remove form after a short delay
            setTimeout(() => {
                document.body.removeChild(form);
                // Reset button
                button.disabled = false;
                button.innerHTML = originalText;
            }, 2000);
        }

        // Payment Method Option Buttons Styling
        document.addEventListener('DOMContentLoaded', function() {
            // Update payment method grid columns based on count
            const paymentMethodContainer = document.getElementById('paymentMethodContainer');
            if (paymentMethodContainer) {
                const paymentMethodCount = document.querySelectorAll('.payment-method-radio').length;
                if (paymentMethodCount > 0) {
                    // Set grid columns to match number of payment methods, max 4 columns
                    const maxCols = Math.min(paymentMethodCount, 4);
                    paymentMethodContainer.style.gridTemplateColumns = `repeat(${maxCols}, 1fr)`;
                }
            }

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

                // Keep flex flex-col layout (vertical, full width)
                kasBankContainer.classList.remove('hidden');
                kasBankContainer.classList.add('flex', 'flex-col');

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

        // RAW BT Printing Function
        let bluetoothDevice = null;
        let bluetoothCharacteristic = null;

        async function printInvoiceRAWBT(event) {
            // Prevent default if event exists
            if (event && event.preventDefault) {
                event.preventDefault();
            }

            // Get button element - try multiple ways
            let button = null;
            if (event && event.target) {
                button = event.target.closest('button');
            }
            if (!button) {
                // Fallback: find button by onclick handler or by ID
                const buttons = document.querySelectorAll('button[onclick*="printInvoiceRAWBT"]');
                button = buttons.length > 0 ? buttons[0] : document.querySelector('#rawBtButtonText')?.closest(
                    'button');
            }

            const buttonText = document.getElementById('rawBtButtonText');

            // Disable button and show loading
            if (button) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
            }
            if (buttonText) {
                buttonText.innerHTML = '<i class="ti ti-loader animate-spin mr-2"></i>Mencetak...';
            }

            try {
                // Generate ESC/POS commands first
                const escPosData = generateESCPOSCommands();

                // Try multiple methods: Web Bluetooth, Web Serial, or Intent URL
                let printSuccess = false;

                // Method 1: Try Web Bluetooth API (if available)
                if (navigator.bluetooth) {
                    try {
                        let deviceConnected = false;
                        if (bluetoothDevice) {
                            try {
                                deviceConnected = bluetoothDevice.gatt && bluetoothDevice.gatt.connected;
                            } catch (e) {
                                deviceConnected = false;
                                bluetoothDevice = null;
                                bluetoothCharacteristic = null;
                            }
                        }

                        if (!deviceConnected) {
                            bluetoothDevice = await connectBluetoothPrinter();
                        }

                        await sendDataToPrinter(escPosData);
                        printSuccess = true;
                    } catch (btError) {
                        console.log('Web Bluetooth failed, trying alternative methods:', btError);
                    }
                }

                // Method 2: Try Web Serial API (more compatible)
                if (!printSuccess && navigator.serial) {
                    try {
                        await printViaWebSerial(escPosData);
                        printSuccess = true;
                    } catch (serialError) {
                        console.log('Web Serial failed, trying Intent URL:', serialError);
                    }
                }

                // Method 3: Use Intent URL for Android (most compatible) or download file
                if (!printSuccess) {
                    try {
                        await printViaIntentURL(escPosData);
                        printSuccess = true;
                    } catch (intentError) {
                        // If all methods fail, throw error
                        throw new Error(
                            'Tidak ada metode printing yang tersedia. Pastikan browser mendukung Web Bluetooth, Web Serial, atau gunakan aplikasi printer Bluetooth.'
                        );
                    }
                }

                // Show success message
                if (printSuccess) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Invoice berhasil dikirim ke printer',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert('Invoice berhasil dikirim ke printer');
                    }
                }

            } catch (error) {
                console.error('RAW BT Print Error:', error);

                // Show user-friendly error message
                let errorMessage = 'Terjadi kesalahan saat mencetak ke printer Bluetooth';

                if (error.message) {
                    errorMessage = error.message;
                } else if (error.name) {
                    switch (error.name) {
                        case 'NotFoundError':
                            errorMessage =
                                'Printer Bluetooth tidak ditemukan. Pastikan printer sudah dipasangkan dan dalam jangkauan.';
                            break;
                        case 'SecurityError':
                            errorMessage = 'Akses Bluetooth ditolak. Izinkan akses Bluetooth di pengaturan browser.';
                            break;
                        case 'NetworkError':
                            errorMessage =
                                'Gagal terhubung ke printer. Pastikan printer dalam jangkauan dan sudah dipasangkan.';
                            break;
                        default:
                            errorMessage = error.name + ': ' + (error.message || 'Error tidak diketahui');
                    }
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mencetak',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Gagal Mencetak: ' + errorMessage);
                }
            } finally {
                // Re-enable button
                if (button) {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                }
                if (buttonText) {
                    buttonText.textContent = 'Cetak RAW BT';
                }
            }
        }

        async function connectBluetoothPrinter() {
            try {
                // Request Bluetooth device - Try multiple service UUIDs for different printer types
                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true, // Allow all devices to be shown
                    optionalServices: [
                        '00001101-0000-1000-8000-00805f9b34fb', // Serial Port Profile (SPP)
                        '0000ffe0-0000-1000-8000-00805f9b34fb', // Some thermal printers
                        '49535343-fe7d-4ae5-8fa9-9fafd205e455' // Generic BLE service
                    ]
                });

                // Connect to GATT server
                const server = await device.gatt.connect();

                // Try to find the correct service
                let service = null;
                let characteristic = null;

                const serviceUUIDs = [
                    '00001101-0000-1000-8000-00805f9b34fb', // SPP
                    '0000ffe0-0000-1000-8000-00805f9b34fb', // Thermal printer service
                    '49535343-fe7d-4ae5-8fa9-9fafd205e455' // Generic BLE
                ];

                for (const serviceUUID of serviceUUIDs) {
                    try {
                        service = await server.getPrimaryService(serviceUUID);

                        // Try to find characteristic for writing
                        const characteristics = await service.getCharacteristics();

                        // Look for write characteristic
                        for (const char of characteristics) {
                            if (char.properties.write || char.properties.writeWithoutResponse) {
                                characteristic = char;
                                break;
                            }
                        }

                        if (characteristic) break;
                    } catch (e) {
                        // Try next service
                        continue;
                    }
                }

                if (!characteristic) {
                    throw new Error('Karakteristik penulisan tidak ditemukan. Pastikan printer mendukung BLE.');
                }

                bluetoothCharacteristic = characteristic;
                return device;

            } catch (error) {
                if (error.name === 'NotFoundError') {
                    throw new Error(
                        'Printer Bluetooth tidak ditemukan. Pastikan printer sudah dipasangkan dan dalam jangkauan.'
                    );
                } else if (error.name === 'SecurityError') {
                    throw new Error('Akses Bluetooth ditolak. Izinkan akses Bluetooth di pengaturan browser.');
                } else if (error.name === 'NetworkError') {
                    throw new Error(
                        'Gagal terhubung ke printer. Pastikan printer dalam jangkauan dan sudah dipasangkan.');
                } else if (error.name === 'AbortError' || error.name === 'DOMException') {
                    // User cancelled the device selection
                    throw new Error(
                        'Pemilihan printer dibatalkan. Silakan coba lagi dan pilih printer yang diinginkan.');
                } else {
                    throw new Error('Gagal terhubung ke printer Bluetooth: ' + (error.message || error.name ||
                        'Error tidak diketahui'));
                }
            }
        }

        async function sendDataToPrinter(data) {
            if (!bluetoothCharacteristic) {
                throw new Error('Tidak terhubung ke printer. Silakan hubungkan terlebih dahulu.');
            }

            try {
                // Convert string to Uint8Array
                const encoder = new TextEncoder();
                const dataArray = encoder.encode(data);

                // Check if writeWithoutResponse is supported (faster)
                const useWriteWithoutResponse = bluetoothCharacteristic.properties.writeWithoutResponse;

                // Split data into chunks (max 20 bytes per chunk for BLE)
                const chunkSize = useWriteWithoutResponse ? 512 :
                    20; // Larger chunks if writeWithoutResponse is available

                for (let i = 0; i < dataArray.length; i += chunkSize) {
                    const chunk = dataArray.slice(i, i + chunkSize);

                    if (useWriteWithoutResponse) {
                        await bluetoothCharacteristic.writeValueWithoutResponse(chunk);
                    } else {
                        await bluetoothCharacteristic.writeValue(chunk);
                    }

                    // Small delay between chunks to prevent buffer overflow
                    await new Promise(resolve => setTimeout(resolve, useWriteWithoutResponse ? 5 : 10));
                }

            } catch (error) {
                throw new Error('Gagal mengirim data ke printer: ' + (error.message || error.name));
            }
        }

        // Web Serial API printing (alternative method)
        async function printViaWebSerial(data) {
            if (!navigator.serial) {
                throw new Error('Web Serial API tidak didukung');
            }

            try {
                // Request port
                const port = await navigator.serial.requestPort();

                // Open port
                await port.open({
                    baudRate: 9600
                });

                // Convert data to Uint8Array
                const encoder = new TextEncoder();
                const dataArray = encoder.encode(data);

                // Write data
                const writer = port.writable.getWriter();
                await writer.write(dataArray);
                writer.releaseLock();

                // Close port
                await port.close();
            } catch (error) {
                throw new Error('Gagal mencetak via Web Serial: ' + (error.message || error.name));
            }
        }

        // Intent URL printing for Android (most compatible method)
        async function printViaIntentURL(data) {
            // Convert ESC/POS commands to base64 for sharing
            const encoder = new TextEncoder();
            const dataArray = encoder.encode(data);

            // Create blob with ESC/POS data
            const blob = new Blob([dataArray], {
                type: 'application/octet-stream'
            });
            const url = URL.createObjectURL(blob);

            // Try to use Web Share API first (most compatible)
            if (navigator.share && navigator.canShare) {
                try {
                    const file = new File([dataArray], `invoice_${new Date().getTime()}.prn`, {
                        type: 'application/octet-stream'
                    });

                    if (navigator.canShare({
                            files: [file]
                        })) {
                        await navigator.share({
                            files: [file],
                            title: 'Invoice Pembelian',
                            text: 'File invoice untuk printer Bluetooth'
                        });

                        URL.revokeObjectURL(url);
                        return; // Success
                    }
                } catch (shareError) {
                    console.log('Web Share API not available or cancelled:', shareError);
                }
            }

            // Fallback: Download file and show instructions
            const a = document.createElement('a');
            a.href = url;
            a.download = `invoice_${new Date().getTime()}.prn`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            // Show instructions
            const isAndroid = /Android/i.test(navigator.userAgent);
            let instructionText = 'File invoice telah didownload. ';

            if (isAndroid) {
                instructionText +=
                    'Buka aplikasi printer Bluetooth (seperti Star Print, PrinterShare, atau Bluetooth Printer) dan pilih file ini untuk mencetak ke printer Bluetooth Anda.';
            } else {
                instructionText += 'Buka aplikasi printer Bluetooth Anda dan pilih file ini untuk mencetak.';
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'File Disiapkan',
                    html: instructionText +
                        '<br><br><small>File tersimpan dengan ekstensi .prn untuk kompatibilitas dengan printer thermal.</small>',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(instructionText);
            }
        }

        function generateESCPOSCommands() {
            let commands = '';

            // Initialize printer
            commands += '\x1B\x40'; // ESC @ - Initialize printer

            // Header - Center align
            commands += '\x1B\x61\x01'; // ESC a 1 - Center align
            commands += '\x1B\x21\x10'; // ESC ! 16 - Double height
            commands += '{{ $pengaturanUmum->nama_toko }}\n';
            commands += '\x1B\x21\x00'; // ESC ! 0 - Normal text

            @if ($pengaturanUmum->deskripsi)
                commands += '{{ $pengaturanUmum->deskripsi }}\n';
            @endif
            @if ($pengaturanUmum->alamat)
                commands += '{{ $pengaturanUmum->alamat }}\n';
            @endif
            @if ($pengaturanUmum->no_telepon)
                commands += 'Telp: {{ $pengaturanUmum->no_telepon }}\n';
            @endif
            @if ($pengaturanUmum->email)
                commands += 'Email: {{ $pengaturanUmum->email }}\n';
            @endif
            commands += '================================\n';

            // Left align
            commands += '\x1B\x61\x00'; // ESC a 0 - Left align
            commands += '\x1B\x21\x08'; // ESC ! 8 - Bold, normal height
            commands += 'PEMBELIAN\n';
            commands += '\x1B\x21\x00'; // ESC ! 0 - Normal text
            commands += 'No. Faktur: {{ $pembelian->no_faktur }}\n';
            commands += 'Tanggal: {{ $pembelian->created_at->format('d/m/Y H:i') }}\n';
            commands += 'Supplier: {{ $pembelian->supplier->nama ?? 'N/A' }}\n';
            commands += 'Kasir: {{ $pembelian->user->name ?? 'N/A' }}\n';
            commands += '================================\n';

            // Items
            @foreach ($pembelian->detailPembelian as $detail)
                commands += '{{ substr($detail->produk->nama_produk, 0, 30) }}\n';
                @php
                    $qtyText = number_format($detail->qty, 2, ',', '.') . ' ' . ($detail->produk->satuan->nama ?? 'pcs');
                    if ($detail->qty_discount > 0) {
                        $qtyText .= ' - ' . number_format($detail->qty_discount, 2, ',', '.') . ' ' . ($detail->produk->satuan->nama ?? 'pcs');
                        $qtyText .= ' = ' . number_format($detail->qty - $detail->qty_discount, 2, ',', '.') . ' ' . ($detail->produk->satuan->nama ?? 'pcs');
                    }
                @endphp
                commands +=
                    '  {{ $qtyText }} x {{ number_format($detail->harga_beli, 0, ',', '.') }} = {{ number_format($detail->subtotal, 0, ',', '.') }}\n';
                @if ($detail->discount > 0)
                    commands += '  Diskon: -{{ number_format($detail->discount, 0) }}\n';
                @endif
                @if ($detail->keterangan)
                    commands += '  Note: {{ $detail->keterangan }}\n';
                @endif
            @endforeach

            commands += '--------------------------------\n';

            // Totals
            commands += 'Subtotal: Rp {{ number_format($pembelian->total, 0) }}\n';
            @if ($pembelian->diskon > 0)
                commands += 'Diskon: -Rp {{ number_format($pembelian->diskon, 0) }}\n';
            @endif
            @if ($pembelian->ppn > 0)
                commands += 'PPN: Rp {{ number_format($pembelian->ppn, 0) }}\n';
            @endif
            commands += '\x1B\x21\x08'; // ESC ! 8 - Bold
            commands += 'TOTAL: Rp {{ number_format($pembelian->grand_total, 0) }}\n';
            commands += '\x1B\x21\x00'; // ESC ! 0 - Normal text

            // Payment info
            @php
                $totalBayar = $pembelian->pembayaranPembelian->sum('jumlah_bayar');
            @endphp
            @if ($totalBayar > 0)
                commands += 'Bayar: Rp {{ number_format($totalBayar, 0) }}\n';
                @if ($totalBayar < $pembelian->grand_total)
                    @php
                        $sisa = $pembelian->grand_total - $totalBayar;
                    @endphp
                    commands += 'Sisa: Rp {{ number_format($sisa, 0) }}\n';
                @endif
                commands += '\n';
            @endif

            // Payment history
            @if ($pembelian->pembayaranPembelian->count() > 0)
                commands += '================================\n';
                commands += 'RIWAYAT PEMBAYARAN:\n';
                commands += '--------------------------------\n';
                @foreach ($riwayatPembayaran as $pembayaran)
                    commands += '{{ $pembayaran->no_bukti }}\n';
                    @php
                        $statusConfig = [
                            'D' => 'DP',
                            'A' => 'Angsuran',
                            'P' => 'Pelunasan',
                            'U' => 'Uang Muka',
                        ];
                        $status = $statusConfig[$pembayaran->status_bayar] ?? 'DP';
                    @endphp
                    commands +=
                        '{{ $pembayaran->created_at->format('d/m/Y H:i') }} - {{ $pembayaran->metode_pembayaran }} ({{ $status }})\n';
                    commands += 'Rp {{ number_format($pembayaran->jumlah_bayar, 0) }}\n';
                    @if ($pembayaran->keterangan)
                        commands += '{{ $pembayaran->keterangan }}\n';
                    @endif
                    commands += '--------------------------------\n';
                @endforeach
            @endif

            // Footer
            commands += '================================\n';
            commands += '\x1B\x61\x01'; // Center align
            commands += 'Terima kasih atas kunjungan Anda\n';
            commands += '\n\n\n';

            // Cut paper
            commands += '\x1D\x56\x42\x00'; // GS V B 0 - Partial cut

            return commands;
        }
    </script>
@endpush
