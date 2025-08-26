@extends('layouts.pos')

@section('title', 'Detail Pembayaran Pembelian')
@section('page-title', 'Detail Pembayaran Pembelian')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Detail Pembayaran Pembelian</h2>
                <p class="text-sm text-gray-600">Informasi lengkap pembayaran pembelian</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('pembayaran-pembelian.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <i class="ti ti-arrow-left text-lg mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('pembayaran-pembelian.edit', $pembayaranPembelian->encrypted_id) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="ti ti-edit text-lg mr-2"></i>
                    Edit
                </a>
            </div>
        </div>

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
                </div>
            </div>
        @endif

        <!-- Error Alert -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-alert-circle text-lg text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Payment Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payment Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h3>
                    <div class="flex items-center space-x-2">
                        @php
                            $statusColors = [
                                'P' => 'bg-green-100 text-green-800',
                                'D' => 'bg-yellow-100 text-yellow-800',
                                'A' => 'bg-blue-100 text-blue-800',
                                'B' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'P' => 'Pelunasan',
                                'D' => 'DP',
                                'A' => 'Angsuran',
                                'B' => 'Batal',
                            ];
                        @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$pembayaranPembelian->status_bayar] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$pembayaranPembelian->status_bayar] ?? $pembayaranPembelian->status_bayar }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">No. Bukti</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $pembayaranPembelian->no_bukti }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Tanggal Pembayaran</span>
                        <span class="text-sm text-gray-900">{{ $pembayaranPembelian->tanggal->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Jumlah Bayar</span>
                        <span class="text-lg font-bold text-green-600">Rp
                            {{ number_format($pembayaranPembelian->jumlah_bayar) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Metode Pembayaran</span>
                        <span class="text-sm text-gray-900">{{ $pembayaranPembelian->metode_pembayaran_display }}</span>
                    </div>

                    @if ($pembayaranPembelian->keterangan)
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-600">Keterangan</span>
                            <span
                                class="text-sm text-gray-900 text-right max-w-xs">{{ $pembayaranPembelian->keterangan }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Dibuat Oleh</span>
                        <span class="text-sm text-gray-900">{{ $pembayaranPembelian->user->name }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Dibuat Pada</span>
                        <span
                            class="text-sm text-gray-900">{{ $pembayaranPembelian->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if ($pembayaranPembelian->updated_at != $pembayaranPembelian->created_at)
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Terakhir Diupdate</span>
                            <span
                                class="text-sm text-gray-900">{{ $pembayaranPembelian->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Purchase Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Informasi Pembelian</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">No. Faktur</span>
                        <span
                            class="text-sm text-gray-900 font-semibold">{{ $pembayaranPembelian->pembelian->no_faktur }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Tanggal Pembelian</span>
                        <span
                            class="text-sm text-gray-900">{{ $pembayaranPembelian->pembelian->tanggal->format('d/m/Y') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Supplier</span>
                        <span class="text-sm text-gray-900">{{ $pembayaranPembelian->pembelian->supplier->nama }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Total Pembelian</span>
                        <span class="text-lg font-bold text-blue-600">Rp
                            {{ number_format($pembayaranPembelian->pembelian->total) }}</span>
                    </div>

                    @php
                        $totalDibayar = $pembayaranPembelian->pembelian->pembayaranPembelian->sum('jumlah_bayar');
                        $sisaBayar = $pembayaranPembelian->pembelian->total - $totalDibayar;
                    @endphp

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Total Dibayar</span>
                        <span class="text-sm font-semibold text-green-600">Rp {{ number_format($totalDibayar) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Sisa Bayar</span>
                        <span class="text-sm font-semibold {{ $sisaBayar > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($sisaBayar) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Status Pembayaran</span>
                        @php
                            $statusPembayaranColors = [
                                'lunas' => 'bg-green-100 text-green-800',
                                'dp' => 'bg-yellow-100 text-yellow-800',
                                'belum_bayar' => 'bg-red-100 text-red-800',
                            ];
                            $statusPembayaranLabels = [
                                'lunas' => 'Lunas',
                                'dp' => 'DP',
                                'belum_bayar' => 'Belum Bayar',
                            ];
                        @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusPembayaranColors[$pembayaranPembelian->pembelian->status_pembayaran] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusPembayaranLabels[$pembayaranPembelian->pembelian->status_pembayaran] ?? $pembayaranPembelian->pembelian->status_pembayaran }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Riwayat Pembayaran</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Bukti
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pembayaranPembelian->pembelian->pembayaranPembelian->sortBy('tanggal') as $payment)
                            <tr class="{{ $payment->id === $pembayaranPembelian->id ? 'bg-blue-50' : '' }}">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $payment->no_bukti }}
                                    @if ($payment->id === $pembayaranPembelian->id)
                                        <span
                                            class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Saat Ini
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->tanggal->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                    Rp {{ number_format($payment->jumlah_bayar) }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $payment->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->status_bayar] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$payment->status_bayar] ?? $payment->status_bayar }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->user->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

