@extends('layouts.pos')

@section('title', 'Detail Pinjaman')
@section('page-title', 'Detail Pinjaman')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
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

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="ti ti-alert-circle text-red-500 mr-3"></i>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    <button type="button" class="ml-auto text-red-500 hover:text-red-700"
                        onclick="this.parentElement.parentElement.remove()">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-lg shadow border">
            <div class="px-4 py-3 md:px-6 md:py-4 border-b bg-gray-50">
                <div class="hidden md:flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pinjaman.index') }}"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $pinjaman->no_pinjaman }}</h1>
                            <p class="text-sm text-gray-500">{{ $pinjaman->tanggal->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @php
                            $statusConfig = [
                                'lunas' => [
                                    'bg' => 'bg-green-100',
                                    'text' => 'text-green-800',
                                    'icon' => 'ti-check-circle',
                                    'label' => 'Lunas',
                                ],
                                'sebagian' => [
                                    'bg' => 'bg-yellow-100',
                                    'text' => 'text-yellow-800',
                                    'icon' => 'ti-clock',
                                    'label' => 'Sebagian',
                                ],
                                'belum_bayar' => [
                                    'bg' => 'bg-red-100',
                                    'text' => 'text-red-800',
                                    'icon' => 'ti-x-circle',
                                    'label' => 'Belum Bayar',
                                ],
                            ];
                            $config = $statusConfig[$pinjaman->status_pembayaran] ?? $statusConfig['belum_bayar'];
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="ti {{ $config['icon'] }} mr-1"></i>
                            {{ $config['label'] }}
                        </span>
                        <a href="{{ route('pinjaman.edit', $pinjaman->encrypted_id) }}"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                            <i class="ti ti-edit mr-2"></i>Edit
                        </a>
                    </div>
                </div>

                <div class="block md:hidden">
                    <div class="flex items-center space-x-3 mb-3">
                        <a href="{{ route('pinjaman.index') }}"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-lg font-bold text-gray-900 truncate">{{ $pinjaman->no_pinjaman }}</h1>
                            <p class="text-xs text-gray-500">{{ $pinjaman->tanggal->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class="ti {{ $config['icon'] }} text-xs mr-1"></i>
                            {{ $config['label'] }}
                        </span>
                        <a href="{{ route('pinjaman.edit', $pinjaman->encrypted_id) }}"
                            class="px-3 py-1.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-xs">
                            <i class="ti ti-edit mr-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Peminjam Info -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-user text-blue-600"></i>
                            </div>
                            Informasi Peminjam
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-500">Kode Peminjam</label>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $pinjaman->peminjam->kode_peminjam ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Nama Peminjam</label>
                                <p class="text-base font-semibold text-gray-900">{{ $pinjaman->peminjam->nama ?? '-' }}</p>
                            </div>
                            @if ($pinjaman->peminjam->nomor_telepon)
                                <div>
                                    <label class="text-sm text-gray-500">Telepon</label>
                                    <p class="text-base text-gray-900">{{ $pinjaman->peminjam->nomor_telepon }}</p>
                                </div>
                            @endif
                            @if ($pinjaman->peminjam->alamat)
                                <div>
                                    <label class="text-sm text-gray-500">Alamat</label>
                                    <p class="text-base text-gray-900">{{ $pinjaman->peminjam->alamat }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pinjaman Details -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-file-invoice text-purple-600"></i>
                            </div>
                            Detail Pinjaman
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-gray-600">Jumlah Pinjaman</span>
                                <span class="text-xl font-bold text-gray-900">Rp
                                    {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b">
                                <span class="text-gray-600">Total Dibayar</span>
                                <span class="text-lg font-semibold text-green-600">Rp
                                    {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-semibold">Sisa Pinjaman</span>
                                <span class="text-xl font-bold text-red-600">Rp
                                    {{ number_format($sisaPinjaman, 0, ',', '.') }}</span>
                            </div>
                            @if ($pinjaman->keterangan)
                                <div class="pt-3 border-t">
                                    <span class="text-gray-600 block mb-2">Keterangan</span>
                                    <p class="text-base text-gray-900">{{ $pinjaman->keterangan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-wallet text-green-600"></i>
                            </div>
                            Riwayat Pembayaran ({{ count($riwayatPembayaran) }} transaksi)
                        </h3>
                    </div>
                    <div class="p-6">
                        @if (count($riwayatPembayaran) > 0)
                            <div class="space-y-4">
                                @foreach ($riwayatPembayaran as $pembayaran)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span
                                                        class="text-sm font-semibold text-gray-900">{{ $pembayaran->no_bukti }}</span>
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_bayar == 'P' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $pembayaran->status_bayar == 'P' ? 'Pelunasan' : 'Angsuran' }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500">
                                                    {{ $pembayaran->tanggal->format('d M Y, H:i') }}</p>
                                            </div>
                                            <div class="text-right ml-4">
                                                <p class="text-lg font-bold text-green-600">Rp
                                                    {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-500">{{ $pembayaran->metode_pembayaran }}</p>
                                            </div>
                                            <div class="ml-4">
                                                <form
                                                    action="{{ route('pinjaman.pembayaran.destroy', $pembayaran->encrypted_id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini? Saldo kas/bank akan dikembalikan.');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 p-2 transition-colors"
                                                        title="Hapus Pembayaran">
                                                        <i class="ti ti-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @if ($pembayaran->keterangan)
                                            <p class="text-sm text-gray-600 mt-2">{{ $pembayaran->keterangan }}</p>
                                        @endif
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <p class="text-xs text-gray-500">
                                                Oleh: {{ $pembayaran->user->name ?? 'N/A' }}
                                                @if ($pembayaran->kasBank)
                                                    | Kas/Bank: {{ $pembayaran->kasBank->nama }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="ti ti-inbox text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Belum ada pembayaran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Payment Form -->
                @if ($sisaPinjaman > 0)
                    <div class="bg-white rounded-lg shadow border">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-orange-50 to-red-50">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="ti ti-cash text-orange-600"></i>
                                </div>
                                Tambah Pembayaran
                            </h3>
                        </div>
                        <div class="p-6">
                            @if ($errors->any())
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <i class="ti ti-alert-circle text-red-600 mr-2"></i>
                                        <h4 class="text-sm font-semibold text-red-800">Terjadi kesalahan:</h4>
                                    </div>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="ti ti-check-circle text-green-600 mr-2"></i>
                                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('pinjaman.pembayaran.store', $pinjaman->encrypted_id) }}"
                                method="POST">
                                @csrf

                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="text-sm text-gray-600 mb-1">Sisa Pinjaman</div>
                                    <div class="text-xl font-bold text-red-600">Rp
                                        {{ number_format($sisaPinjaman, 0, ',', '.') }}</div>
                                </div>

                                <div class="mb-4">
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="tanggal" id="tanggal"
                                        value="{{ old('tanggal', date('d/m/Y')) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('tanggal') border-red-500 @enderror"
                                        required>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="jumlah" id="jumlah" value="{{ old('jumlah') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('jumlah') border-red-500 @enderror"
                                        placeholder="0" required>
                                    @error('jumlah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="metode_pembayaran" id="metode_pembayaran"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('metode_pembayaran') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Metode</option>
                                        @foreach (\App\Models\MetodePembayaran::where('status', true)->get() as $metode)
                                            <option value="{{ $metode->kode }}"
                                                {{ old('metode_pembayaran') == $metode->kode ? 'selected' : '' }}>
                                                {{ $metode->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('metode_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kas/Bank <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kas_bank_id" id="kas_bank_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('kas_bank_id') border-red-500 @enderror"
                                        required>
                                        <option value="">Pilih Kas/Bank</option>
                                        @foreach (\App\Models\KasBank::orderBy('nama')->get() as $kasBank)
                                            <option value="{{ $kasBank->id }}"
                                                {{ old('kas_bank_id') == $kasBank->id ? 'selected' : '' }}>
                                                {{ $kasBank->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kas_bank_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Keterangan
                                    </label>
                                    <textarea name="keterangan" id="keterangan" rows="2"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full px-4 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="ti ti-cash mr-2"></i>Simpan Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Summary Card -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="font-semibold text-gray-900">Ringkasan</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah Pinjaman</span>
                                <span class="font-semibold text-gray-900">Rp
                                    {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Dibayar</span>
                                <span class="font-semibold text-green-600">Rp
                                    {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t">
                                <span class="font-semibold text-gray-900">Sisa Pinjaman</span>
                                <span class="text-xl font-bold text-red-600">Rp
                                    {{ number_format($sisaPinjaman, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Format currency input untuk jumlah pembayaran
            const jumlahInput = document.getElementById('jumlah');
            if (jumlahInput) {
                jumlahInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^\d]/g, '');
                    if (value) {
                        e.target.value = new Intl.NumberFormat('id-ID').format(value);
                    }
                });
            }

            // Date picker untuk tanggal
            const tanggalInput = document.getElementById('tanggal');
            if (tanggalInput && typeof flatpickr !== 'undefined') {
                flatpickr("#tanggal", {
                    dateFormat: "d/m/Y",
                    defaultDate: "today"
                });
            }
        </script>
    @endpush
@endsection
