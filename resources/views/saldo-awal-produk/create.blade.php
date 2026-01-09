@extends('layouts.pos')

@section('title', 'Tambah Saldo Awal Produk')
@section('page-title', 'Tambah Saldo Awal Produk')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('saldo-awal-produk.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Saldo Awal Produk</h1>
                    <p class="text-xs text-gray-500">Set stok awal produk untuk periode pembukuan</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm flex items-center gap-3">
                <i class="ti ti-check-circle text-green-500 text-lg"></i>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="ti ti-alert-circle text-red-500 text-lg mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800">Terdapat kesalahan:</p>
                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('saldo-awal-produk.store') }}" method="POST" id="saldoAwalForm">
                @csrf
                <div class="p-6 space-y-6">
                    
                    <!-- Periode & Generate -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div class="field-wrapper">
                            <label for="periode_bulan" class="block text-xs font-bold text-gray-700 mb-1">
                                Bulan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                <select name="periode_bulan" id="periode_bulan"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($bulanList as $key => $bulan)
                                        <option value="{{ $key }}" {{ old('periode_bulan') == $key ? 'selected' : '' }}>{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field-wrapper">
                            <label for="periode_tahun" class="block text-xs font-bold text-gray-700 mb-1">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar-event text-sm"></i></span>
                                <select name="periode_tahun" id="periode_tahun"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($tahunList as $tahun)
                                        <option value="{{ $tahun }}" {{ old('periode_tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="field-wrapper">
                            <button type="button" id="generateBtn" disabled
                                class="w-full py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors shadow-sm">
                                <i class="ti ti-refresh mr-1.5 align-middle"></i>Generate Produk
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="loadingState" class="hidden py-12 text-center text-blue-600">
                        <i class="ti ti-loader-2 animate-spin text-3xl mb-2 block"></i>
                        <span class="text-sm font-medium">Memuat daftar produk...</span>
                    </div>

                    <!-- Product Table Section -->
                    <div id="produkListContainer" class="hidden space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-800">Daftar Produk</h3>
                            <div class="text-xs text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 flex items-center gap-1.5">
                                <i class="ti ti-info-circle"></i>
                                Masukkan saldo awal jika ada. Kosongkan jika 0.
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white">
                             <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Foto</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Saldo Awal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produkListBody" class="bg-white divide-y divide-gray-100">
                                        <!-- JS Populated -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="field-wrapper">
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 resize-none"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('saldo-awal-produk.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn" disabled
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Simpan Saldo Awal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            const els = {
                bln: $('#periode_bulan'),
                thn: $('#periode_tahun'),
                btnGen: $('#generateBtn'),
                loader: $('#loadingState'),
                listCont: $('#produkListContainer'),
                listBody: $('#produkListBody'),
                btnSub: $('#submitBtn')
            };

            function checkGen() {
                els.btnGen.prop('disabled', !(els.bln.val() && els.thn.val()));
            }

            els.bln.add(els.thn).on('change', checkGen);

            els.btnGen.on('click', function() {
                const bln = els.bln.val();
                const thn = els.thn.val();
                if(!bln || !thn) return;

                els.btnGen.prop('disabled', true);
                els.loader.removeClass('hidden');
                els.listCont.addClass('hidden');
                els.btnSub.prop('disabled', true);

                $.ajax({
                    url: '{{ route("saldo-awal-produk.get-all-produk") }}',
                    method: 'POST',
                    data: { periode_bulan: bln, periode_tahun: thn },
                    success: res => {
                        if(res.success) {
                            renderTable(res.data);
                            els.listCont.removeClass('hidden');
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: () => Swal.fire('Error', 'Gagal memuat produk', 'error'),
                    complete: () => {
                        els.loader.addClass('hidden');
                        els.btnGen.prop('disabled', false);
                    }
                });
            });

            function renderTable(data) {
                // Check previous saldo condition (same logic as before)
                if (data.some(p => p.saldo_sebelumnya_belum_diset)) {
                    els.listBody.empty();
                    Swal.fire('Perhatian', 'Saldo bulan sebelumnya belum di-set. Tidak dapat melanjutkan.', 'warning');
                    return;
                }

                let html = '';
                data.forEach(p => {
                    const img = p.foto ? 
                        `<img src="${p.foto}" class="w-8 h-8 rounded border border-gray-200 object-contain">` : 
                        `<div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">${p.nama.charAt(0)}</div>`;

                    let status = '<span class="text-xs text-gray-400">Manual</span>';
                    let defVal = '';
                    
                    if(p.has_existing) {
                        status = `<span class="text-[10px] px-1.5 py-0.5 rounded bg-yellow-50 text-yellow-700 border border-yellow-100 font-medium">Existing: ${fmtNum(p.existing_saldo)}</span>`;
                        defVal = p.existing_saldo;
                    } else if (p.calculated_saldo > 0) {
                        status = `<span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 font-medium">Auto: ${fmtNum(p.calculated_saldo)}</span>`;
                        defVal = p.calculated_saldo;
                    }

                    const bgRow = p.has_existing ? 'bg-yellow-50/30' : 'hover:bg-gray-50/50';

                    html += `
                        <tr class="${bgRow} transition-colors">
                            <td class="px-4 py-2">${img}</td>
                            <td class="px-4 py-2">
                                <div class="text-sm font-medium text-gray-900">${p.nama}</div>
                                <div class="mt-0.5">${status}</div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="text-xs text-gray-500">${p.kategori}</div>
                                <div class="text-[10px] text-gray-400">${p.satuan}</div>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <input type="text" 
                                       name="saldo_awal[${p.id}]"
                                       value="${fmtInput(defVal)}"
                                       class="w-32 px-2 py-1.5 text-sm border border-gray-200 rounded-md text-right focus:ring-1 focus:ring-blue-500 focus:border-blue-500 saldo-input bg-white"
                                       placeholder="0">
                            </td>
                        </tr>
                    `;
                });

                els.listBody.html(html);
                els.btnSub.prop('disabled', false);
                
                // Re-bind input formatting
                $('.saldo-input').on('input', function() {
                    let v = $(this).val().replace(/[^\d]/g, ''); // simple integer only for simplicity, or keep full logic
                    $(this).val(v ? new Intl.NumberFormat('id-ID').format(v) : '');
                });
            }

            // Simplified Number Formatters
            function fmtNum(n) { return new Intl.NumberFormat('id-ID').format(n); }
            function fmtInput(n) { return n ? new Intl.NumberFormat('id-ID').format(n) : ''; }

            // Submit Handler
            $('#saldoAwalForm').on('submit', function(e) {
                if(els.listBody.children().length === 0) {
                    e.preventDefault();
                    return Swal.fire('Error', 'Belum ada produk di-generate', 'error');
                }
                
                // Unformat inputs
                $('.saldo-input').each(function() {
                    const val = $(this).val().replace(/\./g, '').replace(/,/g, '.');
                    $(this).val(val);
                });
                
                els.btnSub.prop('disabled', true).html('<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...');
            });
        });
    </script>
    @endpush
@endsection
