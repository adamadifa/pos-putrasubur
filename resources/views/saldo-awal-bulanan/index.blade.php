@extends('layouts.pos')

@section('title', 'Saldo Awal Bulanan')
@section('page-title', 'Saldo Awal Bulanan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Saldo Awal Bulanan</h2>
                <p class="text-xs text-gray-500">Kelola saldo awal periode pembukuan</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('saldo-awal-bulanan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                    <i class="ti ti-plus text-lg mr-2"></i>
                    Tambah Saldo
                </a>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm flex items-center gap-3">
                <i class="ti ti-check-circle text-green-500 text-lg"></i>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.remove()">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm flex items-center gap-3">
                <i class="ti ti-alert-circle text-red-500 text-lg"></i>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('saldo-awal-bulanan.index') }}" class="flex flex-col lg:flex-row gap-4 lg:items-end">
                <div class="flex-1">
                    <label for="kas_bank_id" class="block text-xs font-bold text-gray-700 mb-1">Kas/Bank</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-search text-xs"></i></span>
                        <select name="kas_bank_id" id="kas_bank_id" class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                            <option value="">Semua Kas/Bank</option>
                            @foreach ($kasBankList as $kasBank)
                                <option value="{{ $kasBank->id }}" {{ request('kas_bank_id') == $kasBank->id ? 'selected' : '' }}>
                                    {{ $kasBank->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="w-full lg:w-32">
                    <label for="tahun" class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" id="tahun" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                        @foreach ($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun', now()->year) == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-40">
                    <label for="bulan" class="block text-xs font-bold text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" id="bulan" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                        <option value="">Semua Bulan</option>
                        @foreach ($bulanList as $key => $bulan)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                {{ $bulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                    @if (request()->hasAny(['kas_bank_id', 'tahun', 'bulan']))
                        <a href="{{ route('saldo-awal-bulanan.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Compact Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kas/Bank</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">User</th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($saldoAwal as $index => $saldo)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-2 text-center text-xs text-gray-500">
                                    {{ $saldoAwal->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                            {{ substr($saldo->kasBank->nama, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $saldo->kasBank->nama }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <span>{{ $saldo->kasBank->jenis }}</span>
                                                @if($saldo->kasBank->no_rekening)
                                                    <span class="text-gray-300">•</span>
                                                    <span>{{ $saldo->kasBank->no_rekening }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $saldo->periode_key }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <span class="text-sm font-bold text-gray-900 font-mono">
                                        {{ number_format($saldo->saldo_awal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-xs text-gray-500 truncate max-w-xs block" title="{{ $saldo->keterangan }}">
                                        {{ $saldo->keterangan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-gray-900">{{ $saldo->user->name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $saldo->created_at->format('d/m H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if (\App\Models\SaldoAwalBulanan::canEdit($saldo->kas_bank_id, $saldo->periode_bulan, $saldo->periode_tahun))
                                        <form action="{{ route('saldo-awal-bulanan.destroy', $saldo) }}" method="POST" 
                                            class="inline-block"
                                            onsubmit="return confirm('Hapus saldo awal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.form)" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-1.5 text-gray-300 cursor-not-allowed" title="Terkunci (Ada saldo bulan berikutnya)">
                                            <i class="ti ti-lock"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                    <i class="ti ti-inbox text-3xl mb-2 block"></i>
                                    <span class="text-sm">Belum ada data saldo awal</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($saldoAwal->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                    {{ $saldoAwal->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(form) {
            Swal.fire({
                title: 'Hapus Saldo Awal?',
                text: "Data yang dihapus akan mempengaruhi perhitungan saldo berjalan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @endpush
@endsection
