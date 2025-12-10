@extends('layouts.pos')

@section('title', 'Detail Uang Muka Pelanggan')
@section('page-title', 'Detail Uang Muka Pelanggan')

@section('content')
    <div class="mx-4 xl:mx-6 2xl:mx-8 space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('uang-muka-pelanggan.index') }}"
                            class="p-2 text-gray-400 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 rounded-xl transition-all">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $uangMuka->no_uang_muka }}</h1>
                            <p class="text-sm text-gray-500">{{ $uangMuka->tanggal->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if ($uangMuka->status == 'aktif')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="ti ti-circle-check mr-1"></i>
                                Aktif
                            </span>
                        @elseif ($uangMuka->status == 'habis')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <i class="ti ti-check mr-1"></i>
                                Habis
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="ti ti-x mr-1"></i>
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Pelanggan Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-users text-green-600"></i>
                            </div>
                            Informasi Pelanggan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                <i class="ti ti-user text-2xl text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $uangMuka->pelanggan->nama ?? 'N/A' }}</h4>
                                @if ($uangMuka->pelanggan->kode_pelanggan)
                                    <p class="text-sm text-gray-500">{{ $uangMuka->pelanggan->kode_pelanggan }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Histori Penggunaan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-history text-blue-600"></i>
                            </div>
                            Histori Penggunaan Uang Muka
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($uangMuka->penggunaanPenjualan->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Faktur</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah Digunakan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($uangMuka->penggunaanPenjualan as $penggunaan)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $penggunaan->tanggal_penggunaan->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <a href="{{ route('penjualan.show', $penggunaan->penjualan->encrypted_id) }}"
                                                        class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                                        {{ $penggunaan->penjualan->no_faktur }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                                    <span class="text-sm font-medium text-red-600">
                                                        Rp {{ number_format($penggunaan->jumlah_digunakan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    {{ $penggunaan->keterangan ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <a href="{{ route('penjualan.show', $penggunaan->penjualan->encrypted_id) }}"
                                                        class="text-blue-600 hover:text-blue-800" title="Lihat Faktur">
                                                        <i class="ti ti-eye text-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="ti ti-inbox text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Belum ada penggunaan uang muka</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Histori Pengembalian -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-arrow-back text-green-600"></i>
                            </div>
                            Histori Pengembalian Uang Muka
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($uangMuka->pengembalianUang->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                No. Bukti</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Jumlah Dikembalikan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Kas/Bank</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Keterangan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Dibuat Oleh</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($uangMuka->pengembalianUang as $pengembalian)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($pengembalian->tanggal)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $pengembalian->no_bukti }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                                    <span class="text-sm font-medium text-green-600">
                                                        Rp {{ number_format($pengembalian->jumlah, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pengembalian->kasBank->nama ?? '-' }}
                                                    @if ($pengembalian->kasBank)
                                                        <span class="text-xs text-gray-400">
                                                            ({{ $pengembalian->kasBank->jenis }})
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    {{ $pengembalian->keterangan ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pengembalian->user->name ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <form
                                                        action="{{ route('uang-muka-pelanggan.return.delete', [$uangMuka->encrypted_id, $pengembalian->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengembalian ini? Sisa uang muka akan dikembalikan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-800 transition-colors"
                                                            title="Hapus Pengembalian">
                                                            <i class="ti ti-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="2" class="px-4 py-3 text-sm font-medium text-gray-900">
                                                Total Dikembalikan:
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-sm font-bold text-green-600">
                                                    Rp
                                                    {{ number_format($uangMuka->pengembalianUang->sum('jumlah'), 0, ',', '.') }}
                                                </span>
                                            </td>
                        <td colspan="4"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="ti ti-inbox text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Belum ada pengembalian uang muka</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-currency-dollar text-green-600"></i>
                            </div>
                            Ringkasan
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Jumlah Uang Muka</span>
                            <span class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($uangMuka->jumlah_uang_muka, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Digunakan</span>
                            <span class="text-lg font-bold text-red-600">
                                Rp {{ number_format($uangMuka->penggunaanPenjualan->sum('jumlah_digunakan'), 0, ',', '.') }}
                            </span>
                        </div>
                        @if ($uangMuka->pengembalianUang->count() > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Dikembalikan</span>
                                <span class="text-lg font-bold text-green-600">
                                    Rp {{ number_format($uangMuka->pengembalianUang->sum('jumlah'), 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Sisa Uang Muka</span>
                                <span class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($uangMuka->sisa_uang_muka, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-pink-50">
                        <h3 class="font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="ti ti-credit-card text-purple-600"></i>
                            </div>
                            Informasi Pembayaran
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <span class="text-xs text-gray-500">Metode Pembayaran</span>
                            <p class="text-sm font-medium text-gray-900 capitalize">{{ $uangMuka->metode_pembayaran }}</p>
                        </div>
                        @if ($uangMuka->kasBank)
                            <div>
                                <span class="text-xs text-gray-500">Kas/Bank</span>
                                <p class="text-sm font-medium text-gray-900">{{ $uangMuka->kasBank->nama }}</p>
                            </div>
                        @endif
                        <div>
                            <span class="text-xs text-gray-500">Dibuat oleh</span>
                            <p class="text-sm font-medium text-gray-900">{{ $uangMuka->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Tanggal Dibuat</span>
                            <p class="text-sm font-medium text-gray-900">{{ $uangMuka->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Keterangan Card -->
                @if ($uangMuka->keterangan)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-slate-50">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="ti ti-note text-gray-600"></i>
                                </div>
                                Keterangan
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-700">{{ $uangMuka->keterangan }}</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-3">
                    @if ($uangMuka->status == 'aktif' && $uangMuka->sisa_uang_muka > 0)
                        <button type="button" onclick="openReturnModal()"
                            class="w-full py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                            <i class="ti ti-arrow-back text-lg mr-2"></i>
                            Kembalikan Uang
                        </button>
                    @endif
                    @if ($uangMuka->status == 'aktif' && $uangMuka->penggunaanPenjualan->isEmpty())
                        <form action="{{ route('uang-muka-pelanggan.cancel', $uangMuka->encrypted_id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan uang muka ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                                <i class="ti ti-x text-lg mr-2"></i>
                                Batalkan Uang Muka
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

