@extends('layouts.pos')

@section('title', 'Detail Uang Muka Pelanggan')
@section('page-title', 'Detail Uang Muka Pelanggan')

@section('content')
    <div class="max-w-screen-2xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-4 mb-2">
            <div class="flex items-center gap-3">
                <a href="{{ route('uang-muka-pelanggan.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $uangMuka->no_uang_muka }}</h1>
                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                        <i class="ti ti-calendar"></i>
                        <span>{{ $uangMuka->tanggal->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0">
                @php
                    $statusClass = match ($uangMuka->status) {
                        'aktif' => 'bg-green-50 text-green-700 border border-green-200',
                        'habis' => 'bg-gray-50 text-gray-700 border border-gray-200',
                        'dibatalkan' => 'bg-red-50 text-red-700 border border-red-200',
                        default => 'bg-gray-50 text-gray-700 border border-gray-200',
                    };
                    $statusIcon = match ($uangMuka->status) {
                        'aktif' => 'ti-circle-check',
                        'habis' => 'ti-circle-check-filled',
                        'dibatalkan' => 'ti-alert-circle',
                        default => 'ti-help',
                    };
                @endphp
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusClass }}">
                    <i class="ti {{ $statusIcon }} mr-1.5"></i>
                    {{ ucfirst($uangMuka->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT COLUMN: Main Info & History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Pelanggan</p>
                            <h3 class="text-xl font-bold text-gray-900 uppercase tracking-wide">
                                {{ $uangMuka->pelanggan->nama ?? '-' }}
                            </h3>
                            @if ($uangMuka->pelanggan->kode_pelanggan)
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 font-mono">
                                    {{ $uangMuka->pelanggan->kode_pelanggan }}
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                             <p class="text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</p>
                             <div class="flex items-center justify-end gap-2">
                                 <span class="font-semibold text-gray-900 capitalize">{{ $uangMuka->metode_pembayaran }}</span>
                                 @if($uangMuka->kasBank)
                                    <span class="text-xs text-gray-400">| {{ $uangMuka->kasBank->nama }}</span>
                                 @endif
                             </div>
                             <p class="text-xs text-gray-400 mt-1">Dibuat oleh: {{ $uangMuka->user->name ?? '-' }}</p>
                        </div>
                    </div>
                    
                    @if ($uangMuka->keterangan)
                        <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                            <div class="flex gap-2">
                                <i class="ti ti-note text-gray-400 mt-0.5"></i>
                                <div class="text-sm text-gray-600">
                                    {{ $uangMuka->keterangan }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- History Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Penggunaan Header -->
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-history text-lg text-blue-600"></i>
                            <h3 class="font-semibold text-gray-900">Riwayat Penggunaan</h3>
                        </div>
                        <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">
                            {{ $uangMuka->penggunaanPenjualan->count() }} Transaksi
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        @if ($uangMuka->penggunaanPenjualan->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">
                                            <div class="flex items-center gap-1.5"><i class="ti ti-calendar text-blue-600"></i> Tanggal</div>
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1.5"><i class="ti ti-receipt text-blue-600"></i> No. Faktur</div>
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center justify-end gap-1.5"><i class="ti ti-currency-dollar text-blue-600"></i> Jumlah</div>
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center gap-1.5"><i class="ti ti-note text-blue-600"></i> Ket</div>
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($uangMuka->penggunaanPenjualan as $penggunaan)
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 pl-6">
                                                {{ $penggunaan->tanggal_penggunaan->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <a href="{{ route('penjualan.show', $penggunaan->penjualan->encrypted_id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                    {{ $penggunaan->penjualan->no_faktur }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1 text-red-600 font-medium text-sm">
                                                    <i class="ti ti-arrow-down text-xs"></i>
                                                    Rp {{ number_format($penggunaan->jumlah_digunakan, 0, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 truncate max-w-xs">
                                                {{ $penggunaan->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('penjualan.show', $penggunaan->penjualan->encrypted_id) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="ti ti-eye text-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-8 text-center text-gray-500">
                                <i class="ti ti-receipt-off text-3xl mb-2 opacity-50"></i>
                                <p class="text-sm">Belum ada riwayat penggunaan</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Return History Section -->
                @if ($uangMuka->pengembalianUang->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-arrow-back-up text-lg text-green-600"></i>
                            <h3 class="font-semibold text-gray-900">Riwayat Pengembalian</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider pl-6">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Bukti</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kas/Bank</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($uangMuka->pengembalianUang as $pengembalian)
                                    <tr class="hover:bg-green-50/30 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 pl-6">
                                            {{ \Carbon\Carbon::parse($pengembalian->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $pengembalian->no_bukti }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1 text-green-600 font-medium text-sm">
                                                <i class="ti ti-arrow-up text-xs"></i>
                                                Rp {{ number_format($pengembalian->jumlah, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pengembalian->kasBank->nama ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <form action="{{ route('uang-muka-pelanggan.return.delete', [$uangMuka->encrypted_id, $pengembalian->id]) }}" method="POST" onsubmit="return confirm('Hapus pengembalian ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                                    <i class="ti ti-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: Summary & Actions -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6">Ringkasan Dana</h3>
                    
                    <div class="space-y-6">
                        <!-- Total -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-gray-500">Total Uang Muka</span>
                                <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded">IDR</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format($uangMuka->jumlah_uang_muka, 0, ',', '.') }}
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Usage -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">
                                <i class="ti ti-arrow-down-right text-red-500 mr-1.5"></i>Digunakan
                            </span>
                            <span class="text-base font-bold text-red-600">
                                {{ number_format($uangMuka->penggunaanPenjualan->sum('jumlah_digunakan'), 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Returned -->
                        @if($uangMuka->pengembalianUang->count() > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">
                                <i class="ti ti-arrow-up-right text-green-500 mr-1.5"></i>Dikembalikan
                            </span>
                            <span class="text-base font-bold text-green-600">
                                {{ number_format($uangMuka->pengembalianUang->sum('jumlah'), 0, ',', '.') }}
                            </span>
                        </div>
                        @endif

                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-700">Sisa Dana</span>
                                <span class="text-xl font-bold text-gray-900">
                                    {{ number_format($uangMuka->sisa_uang_muka, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    @if ($uangMuka->status == 'aktif' && $uangMuka->sisa_uang_muka > 0)
                        <button type="button" onclick="openReturnModal()"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 shadow-sm transition-all">
                            <i class="ti ti-arrow-back-up"></i>
                            Kembalikan Sisa
                        </button>
                    @endif
                    
                    @if ($uangMuka->status == 'aktif' && $uangMuka->penggunaanPenjualan->isEmpty())
                        <form action="{{ route('uang-muka-pelanggan.cancel', $uangMuka->encrypted_id) }}" method="POST"
                            onsubmit="return confirm('Batalkan uang muka ini?');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white text-red-600 border border-red-200 rounded-xl font-semibold hover:bg-red-50 transition-all">
                                <i class="ti ti-x"></i>
                                Batalkan Transaksi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Pengembalian Uang -->
    <div id="returnModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="ti ti-arrow-back text-green-600"></i>
                        </div>
                        Pengembalian Uang Muka
                    </h3>
                    <button type="button" onclick="closeReturnModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="returnForm" action="{{ route('uang-muka-pelanggan.return', $uangMuka->encrypted_id) }}"
                method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <!-- Info Sisa Uang Muka -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-900">Sisa Uang Muka:</span>
                            <span class="text-lg font-bold text-blue-900" id="sisaUangMukaDisplay">
                                Rp {{ number_format($uangMuka->sisa_uang_muka, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Jumlah Kembali -->
                    <div>
                        <label for="jumlah_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah yang Dikembalikan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="text" id="jumlah_kembali" name="jumlah_kembali" required
                                value="{{ number_format($uangMuka->sisa_uang_muka, 0, ',', '.') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="0">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Maksimal: Rp
                            {{ number_format($uangMuka->sisa_uang_muka, 0, ',', '.') }}</p>
                    </div>

                    <!-- Kas/Bank -->
                    <div>
                        <label for="kas_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Kas/Bank
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="kas_bank_id" name="kas_bank_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="" disabled selected>Pilih kas/bank</option>
                            @foreach (\App\Models\KasBank::orderBy('nama')->get() as $kasBank)
                                <option value="{{ $kasBank->id }}">{{ $kasBank->nama }} ({{ $kasBank->jenis }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" required value="{{ now()->toDateString() }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeReturnModal()"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition-colors">
                        Simpan Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openReturnModal() {
            const modal = document.getElementById('returnModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            // Set default value to sisa uang muka
            const sisaUangMuka = {{ $uangMuka->sisa_uang_muka }};
            document.getElementById('jumlah_kembali').value = formatNumber(sisaUangMuka);
        }

        function closeReturnModal() {
            const modal = document.getElementById('returnModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        // Format number with thousand separator
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Parse formatted number back to numeric value
        function parseFormattedNumber(value) {
            return parseInt(value.toString().replace(/\./g, '')) || 0;
        }

        // Format currency input
        const jumlahKembaliInput = document.getElementById('jumlah_kembali');
        if (jumlahKembaliInput) {
            jumlahKembaliInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    e.target.value = formatNumber(parseInt(value));
                } else {
                    e.target.value = '';
                }
            });

            jumlahKembaliInput.addEventListener('blur', function(e) {
                const maxValue = {{ $uangMuka->sisa_uang_muka }};
                const currentValue = parseFormattedNumber(e.target.value);
                if (currentValue > maxValue) {
                    e.target.value = formatNumber(maxValue);
                    alert('Jumlah yang dikembalikan tidak boleh melebihi sisa uang muka.');
                }
            });
        }
    </script>
@endpush

