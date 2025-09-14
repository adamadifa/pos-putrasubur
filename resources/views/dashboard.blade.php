@extends('layouts.pos')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-4 sm:p-6 lg:p-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-xl sm:text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-blue-100 text-base sm:text-lg">Berikut adalah ringkasan bisnis Anda hari ini</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-blue-100 text-sm">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                    <p class="text-blue-100 text-sm">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Today's Sales -->
            <div
                class="bg-gradient-to-br from-purple-50 to-violet-100 rounded-2xl shadow-lg border border-purple-200 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 relative">
                <div class="absolute top-3 right-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $salesGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%
                    </span>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-semibold text-purple-700">Penjualan Hari Ini</p>
                        <p class="text-lg sm:text-xl font-bold text-purple-900">Rp
                            {{ number_format($todaySales, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Today's Purchases -->
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl shadow-lg border border-blue-200 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 relative">
                <div class="absolute top-3 right-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $purchaseGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $purchaseGrowth >= 0 ? '+' : '' }}{{ number_format($purchaseGrowth, 1) }}%
                    </span>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-semibold text-blue-700">Pembelian Hari Ini</p>
                        <p class="text-lg sm:text-xl font-bold text-blue-900">Rp
                            {{ number_format($todayPurchases, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Piutang -->
            <div
                class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl shadow-lg border border-green-200 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 relative">
                <div class="absolute top-3 right-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        Belum Lunas
                    </span>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-semibold text-green-700">Total Piutang</p>
                        <p class="text-lg sm:text-xl font-bold text-green-900">Rp
                            {{ number_format($sisaPiutang, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Hutang -->
            <div
                class="bg-gradient-to-br from-red-50 to-rose-100 rounded-2xl shadow-lg border border-red-200 p-4 sm:p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105 relative">
                <div class="absolute top-3 right-3">
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        Belum Lunas
                    </span>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-red-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs font-semibold text-red-700">Total Hutang</p>
                        <p class="text-lg sm:text-xl font-bold text-red-900">Rp
                            {{ number_format($sisaHutang, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 gap-6 sm:gap-8">
            <!-- Sales Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 sm:mb-0">Grafik Penjualan & Pembelian</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <!-- Period Selector -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Periode:</span>
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <a href="{{ request()->fullUrlWithQuery(['chart_period' => 'weekly']) }}"
                                    class="px-2 sm:px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $chartPeriod === 'weekly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    Mingguan
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['chart_period' => 'monthly']) }}"
                                    class="px-2 sm:px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $chartPeriod === 'monthly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    Bulanan
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['chart_period' => 'yearly']) }}"
                                    class="px-2 sm:px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $chartPeriod === 'yearly' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                    Tahunan
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                            <span class="text-xs sm:text-sm text-gray-500">Total Penjualan: Rp
                                {{ number_format($monthlySalesTotal, 0, ',', '.') }}</span>
                            <span class="hidden sm:inline text-sm text-gray-500">|</span>
                            <span class="text-xs sm:text-sm text-gray-500">Total Pembelian: Rp
                                {{ number_format($monthlyPurchases, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="h-64 sm:h-80">
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Sales - Desktop Version -->
                <div
                    class="hidden sm:block bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg border border-blue-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Penjualan Terbaru</h3>
                            </div>
                            <a href="{{ route('penjualan.index') }}"
                                class="text-white/80 hover:text-white text-sm font-medium transition-colors">Lihat semua
                                →</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($recentSales as $transaction)
                                <div
                                    class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $transaction->pelanggan ? $transaction->pelanggan->nama : 'Pelanggan Umum' }}
                                                </p>
                                                <span
                                                    class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">#{{ $transaction->no_faktur }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ $transaction->tanggal->format('d/m/Y H:i') }} -
                                                {{ $transaction->kasir->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp
                                            {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        @if (strtolower($transaction->status_pembayaran) === 'lunas') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                            {{ strtolower($transaction->status_pembayaran) === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div
                                        class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">Belum ada penjualan hari ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Sales - Mobile Version -->
                <div class="sm:hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-white">Penjualan Terbaru</h3>
                            </div>
                            <a href="{{ route('penjualan.index') }}"
                                class="text-white/80 hover:text-white text-xs font-medium transition-colors">Lihat
                                semua</a>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="space-y-2">
                            @forelse($recentSales as $transaction)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <!-- Header Row -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-blue-500 rounded-md flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-3 h-3 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                            <span
                                                class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">#{{ $transaction->no_faktur }}</span>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                            @if (strtolower($transaction->status_pembayaran) === 'lunas') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ strtolower($transaction->status_pembayaran) === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </div>

                                    <!-- Customer Name -->
                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                        {{ $transaction->pelanggan ? $transaction->pelanggan->nama : 'Pelanggan Umum' }}
                                    </p>

                                    <!-- Amount -->
                                    <p class="text-lg font-bold text-gray-900 mb-1">
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    </p>

                                    <!-- Date and Cashier -->
                                    <p class="text-xs text-gray-500">
                                        {{ $transaction->tanggal->format('d/m/Y H:i') }} - {{ $transaction->kasir->name }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada penjualan hari ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Purchases - Desktop Version -->
                <div
                    class="hidden sm:block bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl shadow-lg border border-orange-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Pembelian Terbaru</h3>
                            </div>
                            <a href="{{ route('pembelian.index') }}"
                                class="text-white/80 hover:text-white text-sm font-medium transition-colors">Lihat semua
                                →</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($recentPurchases as $transaction)
                                <div
                                    class="flex items-center justify-between p-4 bg-white rounded-xl shadow-sm border border-orange-100 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $transaction->supplier ? $transaction->supplier->nama : 'Supplier Umum' }}
                                                </p>
                                                <span
                                                    class="text-xs font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full">#{{ $transaction->no_faktur }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ $transaction->tanggal->format('d/m/Y H:i') }} -
                                                {{ $transaction->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp
                                            {{ number_format($transaction->total, 0, ',', '.') }}</p>
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                            @if (strtolower($transaction->status_pembayaran) === 'lunas') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ strtolower($transaction->status_pembayaran) === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div
                                        class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-orange-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">Belum ada pembelian hari ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Purchases - Mobile Version -->
                <div class="sm:hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 bg-gradient-to-r from-orange-500 to-red-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-white">Pembelian Terbaru</h3>
                            </div>
                            <a href="{{ route('pembelian.index') }}"
                                class="text-white/80 hover:text-white text-xs font-medium transition-colors">Lihat
                                semua</a>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="space-y-2">
                            @forelse($recentPurchases as $transaction)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <!-- Header Row -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-orange-500 rounded-md flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-3 h-3 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                            </div>
                                            <span
                                                class="text-xs font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full">#{{ $transaction->no_faktur }}</span>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                            @if (strtolower($transaction->status_pembayaran) === 'lunas') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ strtolower($transaction->status_pembayaran) === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </div>

                                    <!-- Supplier Name -->
                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                        {{ $transaction->supplier ? $transaction->supplier->nama : 'Supplier Umum' }}
                                    </p>

                                    <!-- Amount -->
                                    <p class="text-lg font-bold text-gray-900 mb-1">
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    </p>

                                    <!-- Date and User -->
                                    <p class="text-xs text-gray-500">
                                        {{ $transaction->tanggal->format('d/m/Y H:i') }} - {{ $transaction->user->name }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-orange-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada pembelian hari ini</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Alerts -->
            <div class="space-y-6">
                <!-- Low Stock Products Alert -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Produk Stok Rendah</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-500">{{ $lowStockProducts }} produk</span>
                        </div>
                    </div>

                    @if ($lowStockProducts > 0)
                        <div class="space-y-2">
                            @foreach ($lowStockProductsList as $product)
                                <div
                                    class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $product->nama_produk }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $product->satuan->nama ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-600">{{ $product->stok }} stok</p>
                                        <p class="text-xs text-gray-500">Min: {{ $product->stok_minimal ?? 10 }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-gray-500">Semua produk memiliki stok yang cukup</p>
                        </div>
                    @endif
                </div>

                <!-- Top Selling Products This Month - Desktop Version -->
                <div class="hidden sm:block bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Top Penjualan Produk Bulan Ini</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @forelse($topSellingProducts as $index => $product)
                            <div
                                class="flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg hover:shadow-md transition-all duration-200">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm mr-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $product->nama_produk }}</p>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="text-xs text-gray-500">{{ $product->satuan }}</p>
                                        <div class="text-right">
                                            <p class="text-xs font-bold text-green-700">
                                                {{ number_format($product->total_terjual, 0, ',', '.') }} terjual</p>
                                            <p class="text-sm font-semibold text-gray-800">Rp
                                                {{ number_format($product->total_penjualan, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada data penjualan bulan ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Selling Products This Month - Mobile Version -->
                <div class="sm:hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-white">Top Produk Bulan Ini</h3>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-white/60 rounded-full"></div>
                                <span class="text-xs text-white/80">{{ \Carbon\Carbon::now()->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="space-y-2">
                            @forelse($topSellingProducts as $index => $product)
                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                    <!-- Header Row -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-green-500 rounded-md flex items-center justify-center">
                                                <span class="text-xs font-bold text-white">{{ $index + 1 }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $product->satuan }}</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-bold text-green-700">
                                                {{ number_format($product->total_terjual, 0, ',', '.') }} terjual</p>
                                        </div>
                                    </div>

                                    <!-- Product Name -->
                                    <p class="text-sm font-semibold text-gray-900 mb-1">
                                        {{ $product->nama_produk }}
                                    </p>

                                    <!-- Total Sales -->
                                    <p class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($product->total_penjualan, 0, ',', '.') }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada data penjualan bulan ini</p>
                                </div>
                            @endforelse
                        </div>
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
