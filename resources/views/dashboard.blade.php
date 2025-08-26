@extends('layouts.pos')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-blue-100 text-lg">Berikut adalah ringkasan bisnis Anda hari ini</p>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <p class="text-blue-100 text-sm">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                        <p class="text-blue-100 text-sm">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Today's Sales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H4.5m2.25 0v3m0 0v.375c0 .621-.504 1.125-1.125 1.125H4.5m0 0h-.375A1.125 1.125 0 0 1 3 4.5V4.5m6 0v.75c0 .414.336.75.75.75h.75m0-1.5h.25m0 0c.621 0 1.125.504 1.125 1.125v.375m0 0v3.75c0 .621-.504 1.125-1.125 1.125H9.75A1.125 1.125 0 0 1 8.625 10.5v-3.75M15.75 4.5v.75c0 .414.336.75.75.75h.75m0-1.5h.25m0 0c.621 0 1.125.504 1.125 1.125v.375m0 0v3.75c0 .621-.504 1.125-1.125 1.125H15.75a1.125 1.125 0 0 1-1.125-1.125v-3.75" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Penjualan Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $salesGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Today's Transactions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Transaksi Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $todayTransactions }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transactionGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transactionGrowth >= 0 ? '+' : '' }}{{ number_format($transactionGrowth, 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- New Customers -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pelanggan Baru</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $newCustomers }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customerGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $customerGrowth >= 0 ? '+' : '' }}{{ number_format($customerGrowth, 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Stok Menipis</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Perlu Restock
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 gap-8">
            <!-- Sales Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Grafik Penjualan & Pembelian</h3>
                    <div class="flex items-center space-x-4">
                        <!-- Period Selector -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Periode:</span>
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <a href="{{ request()->fullUrlWithQuery(['chart_period' => 'weekly']) }}"
                                    class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $chartPeriod === 'weekly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    Mingguan
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['chart_period' => 'monthly']) }}"
                                    class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $chartPeriod === 'monthly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    Bulanan
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['chart_period' => 'yearly']) }}"
                                    class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $chartPeriod === 'yearly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    Tahunan
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Total Penjualan: Rp
                                {{ number_format($monthlySalesTotal, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500">|</span>
                            <span class="text-sm text-gray-500">Total Pembelian: Rp
                                {{ number_format($monthlyPurchases, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
                        <a href="{{ route('penjualan.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-500 font-medium">Lihat semua →</a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentTransactions as $transaction)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">#{{ $transaction->no_faktur }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $transaction->pelanggan ? $transaction->pelanggan->nama : 'Pelanggan Umum' }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $transaction->tanggal->format('d/m/Y H:i') }}
                                            - {{ $transaction->kasir->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">Rp
                                        {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                        @if ($transaction->status_pembayaran === 'Lunas') bg-green-100 text-green-800
                                        @elseif($transaction->status_pembayaran === 'DP') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $transaction->status_pembayaran }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500">Belum ada transaksi hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Alerts -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('penjualan.create') }}"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Transaksi Baru
                        </a>

                        <a href="{{ route('pembelian.create') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Pembelian Baru
                        </a>

                        <a href="{{ route('produk.index') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Kelola Produk
                        </a>

                        <a href="{{ route('pelanggan.create') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                            Tambah Pelanggan
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Peringatan</h3>
                    <div class="space-y-4">
                        @if ($lowStockProducts > 0)
                            <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-red-800">{{ $lowStockProducts }} produk stok
                                        menipis</p>
                                    <a href="{{ route('produk.index') }}"
                                        class="text-xs text-red-600 hover:text-red-500">Lihat detail →</a>
                                </div>
                            </div>
                        @endif

                        @if ($outstandingPayments > 0)
                            <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-yellow-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">{{ $outstandingPayments }} pembayaran
                                        tertunda</p>
                                    <a href="{{ route('pembayaran.index') }}"
                                        class="text-xs text-yellow-600 hover:text-yellow-500">Lihat detail →</a>
                                </div>
                            </div>
                        @endif

                        @if ($outstandingPurchases > 0)
                            <div class="flex items-center p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-orange-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-orange-800">{{ $outstandingPurchases }} pembelian
                                        belum lunas</p>
                                    <a href="{{ route('pembayaran-pembelian.index') }}"
                                        class="text-xs text-orange-600 hover:text-orange-500">Lihat detail →</a>
                                </div>
                            </div>
                        @endif

                        @if ($lowStockProducts == 0 && $outstandingPayments == 0 && $outstandingPurchases == 0)
                            <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-green-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Semua sistem berjalan dengan baik!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sales Chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            const chartData = @json($monthlySales);

            const labels = chartData.map(item => {
                const date = new Date(item.date);
                @if ($chartPeriod === 'yearly')
                    return date.toLocaleDateString('id-ID', {
                        month: 'short',
                        year: 'numeric'
                    });
                @else
                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    });
                @endif
            });

            const salesData = chartData.map(item => item.total_sales);
            const purchaseData = chartData.map(item => item.total_purchases);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: salesData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Pembelian (Rp)',
                        data: purchaseData,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        </script>
    @endpush
@endsection


