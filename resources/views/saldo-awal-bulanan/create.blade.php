@extends('layouts.pos')

@section('title', 'Tambah Saldo Awal Bulanan')
@section('page-title', 'Tambah Saldo Awal Bulanan')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('saldo-awal-bulanan.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Saldo Awal Bulanan</h1>
                    <p class="text-xs text-gray-500">Set saldo awal untuk periode pembukuan</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-center">
                    <i class="ti ti-check-circle text-green-400 mr-3 text-lg"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex">
                    <i class="ti ti-alert-circle text-red-400 mr-3 mt-0.5 text-lg"></i>
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
            <form action="{{ route('saldo-awal-bulanan.store') }}" method="POST" id="createSaldoAwalForm">
                @csrf
                
                <div class="p-6 space-y-6">
                    <!-- Instruction -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                        <i class="ti ti-info-circle text-blue-500 mt-0.5"></i>
                        <div class="text-sm text-blue-900 leading-relaxed">
                            <strong>Tips:</strong> Gunakan fitur <strong>"Get Saldo"</strong> untuk otomatis mengambil saldo akhir bulan sebelumnya sebagai saldo awal bulan ini.
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kas/Bank -->
                        <div class="field-wrapper md:col-span-2">
                            <label for="kas_bank_id" class="block text-xs font-bold text-gray-700 mb-1">
                                Kas/Bank <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="ti ti-building-bank text-sm"></i>
                                </span>
                                <select name="kas_bank_id" id="kas_bank_id"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('kas_bank_id') border-red-500 @enderror">
                                    <option value="">Pilih Kas/Bank</option>
                                    @foreach ($kasBankList as $kasBank)
                                        <option value="{{ $kasBank->id }}" {{ old('kas_bank_id') == $kasBank->id ? 'selected' : '' }}>
                                            {{ $kasBank->nama }} ({{ $kasBank->jenis }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kas_bank_id') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Periode Bulan -->
                        <div class="field-wrapper">
                            <label for="periode_bulan" class="block text-xs font-bold text-gray-700 mb-1">
                                Bulan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="ti ti-calendar text-sm"></i>
                                </span>
                                <select name="periode_bulan" id="periode_bulan"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('periode_bulan') border-red-500 @enderror">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($bulanList as $key => $bulan)
                                        <option value="{{ $key }}" {{ old('periode_bulan') == $key ? 'selected' : '' }}>
                                            {{ $bulan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('periode_bulan') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                         <!-- Periode Tahun -->
                         <div class="field-wrapper">
                            <label for="periode_tahun" class="block text-xs font-bold text-gray-700 mb-1">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="ti ti-calendar-event text-sm"></i>
                                </span>
                                <select name="periode_tahun" id="periode_tahun"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('periode_tahun') border-red-500 @enderror">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($tahunList as $tahun)
                                        <option value="{{ $tahun }}" {{ old('periode_tahun', now()->year) == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('periode_tahun') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 my-4"></div>

                    <!-- Saldo Awal Section -->
                    <div class="field-wrapper">
                        <label for="saldo_awal" class="block text-xs font-bold text-gray-700 mb-2">
                            Nominal Saldo Awal <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="flex flex-col sm:flex-row gap-3 items-start">
                            <div class="relative w-full">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="text" name="saldo_awal_display" id="saldo_awal"
                                    value="{{ old('saldo_awal') ? number_format(old('saldo_awal'), 0, ',', '.') : '' }}"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 text-right font-medium @error('saldo_awal') border-red-500 @enderror"
                                    placeholder="0">
                                <input type="hidden" name="saldo_awal_raw" id="saldo_awal_raw" value="{{ old('saldo_awal') }}">
                            </div>

                            <button type="button" id="getSaldoBtn" disabled
                                class="w-full sm:w-auto px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors shadow-sm whitespace-nowrap">
                                <i class="ti ti-calculator mr-1.5"></i>Get Saldo
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" id="saldoInfoText">Pilih periode dahulu untuk mengambil saldo otomatis.</p>
                        @error('saldo_awal') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror

                        <!-- Loading & Result Area -->
                        <div id="saldoLoading" class="hidden mt-3 p-3 bg-blue-50 rounded-lg flex items-center gap-2 text-sm text-blue-700">
                            <i class="ti ti-loader animate-spin"></i> Menghitung saldo, mohon tunggu...
                        </div>

                        <div id="saldoResultInfo" class="hidden mt-3 p-3 bg-green-50 border border-green-100 rounded-lg">
                             <div class="flex items-start gap-2">
                                <i class="ti ti-check-circle text-green-600 mt-0.5"></i>
                                <div class="text-sm">
                                    <div class="font-bold text-green-800 mb-1" id="saldoResultTitle"></div>
                                    <div class="text-green-700 space-y-0.5 text-xs" id="saldoResultDetails"></div>
                                </div>
                             </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="field-wrapper">
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 mb-1">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('keterangan') border-red-500 @enderror"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Preview -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Preview Entri</p>
                            <div class="font-medium text-gray-900 text-sm" id="preview-kas-bank">Pilih Kas/Bank</div>
                            <div class="text-xs text-gray-400 mt-0.5" id="preview-periode">Pilih Bulan & Tahun</div>
                        </div>
                        <div class="text-right">
                             <div class="text-lg font-bold text-gray-900" id="preview-saldo">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button type="reset" id="resetBtn" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Reset
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        Simpan Saldo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // -- ELEMENTS --
            const elements = {
                kasBank: $('#kas_bank_id'),
                bulan: $('#periode_bulan'),
                tahun: $('#periode_tahun'),
                saldoInput: $('#saldo_awal'),
                saldoRaw: $('#saldo_awal_raw'),
                btnGet: $('#getSaldoBtn'),
                infoText: $('#saldoInfoText'),
                loading: $('#saldoLoading'),
                result: $('#saldoResultInfo'),
                resultTitle: $('#saldoResultTitle'),
                resultDetail: $('#saldoResultDetails'),
                previewKas: $('#preview-kas-bank'),
                previewPeriode: $('#preview-periode'),
                previewSaldo: $('#preview-saldo')
            };

            // -- PREVIEW LOGIC --
            function updatePreview() {
                const kasNames = elements.kasBank.find('option:selected').text().trim() || 'Pilih Kas/Bank';
                const blnName = elements.bulan.find('option:selected').text().trim();
                const thnName = elements.tahun.val();
                const saldoVal = elements.saldoInput.val() || '0';

                elements.previewKas.text(kasNames);
                if(blnName && blnName !== 'Pilih Bulan' && thnName) {
                    elements.previewPeriode.text(`${blnName} ${thnName}`);
                } else {
                    elements.previewPeriode.text('Pilih Bulan & Tahun');
                }
                elements.previewSaldo.text('Rp ' + saldoVal);
            }

            elements.kasBank.add(elements.bulan).add(elements.tahun).on('change', function() {
                checkReady();
                updatePreview();
            });
            
            elements.saldoInput.on('input', updatePreview);

            // -- GET SALDO CHECK --
            function checkReady() {
                if(elements.kasBank.val() && elements.bulan.val() && elements.tahun.val()) {
                    elements.btnGet.prop('disabled', false);
                    elements.infoText.text('Klik "Get Saldo" untuk hitung otomatis.');
                } else {
                    elements.btnGet.prop('disabled', true);
                    elements.infoText.text('Pilih Kas/Bank, Bulan, dan Tahun dahulu.');
                }
            }

            // -- GET SALDO AJAX --
            elements.btnGet.on('click', function() {
                elements.loading.removeClass('hidden');
                elements.result.addClass('hidden');
                elements.btnGet.prop('disabled', true);

                $.ajax({
                    url: '{{ route("saldo-awal-bulanan.get-saldo-akhir") }}',
                    method: 'POST',
                    data: {
                        kas_bank_id: elements.kasBank.val(),
                        periode_bulan: elements.bulan.val(),
                        periode_tahun: elements.tahun.val()
                    },
                    success: function(res) {
                        if(res.success) {
                            const d = res.data;
                            const fmtVal = formatNumber(d.saldo_akhir_bulan_sebelumnya);
                            
                            elements.saldoInput.val(fmtVal).trigger('input');
                            
                            elements.resultTitle.text(`Saldo Akhir Total: Rp ${fmtVal}`);
                            
                            let detail = `
                                <div>• Bulan Sebelumnya: ${d.bulan_sebelumnya}</div>
                                <div>• Saldo Awal: Rp ${formatNumber(d.saldo_awal_bulan_sebelumnya)}</div>
                                <div>• Transaksi: Rp ${formatNumber(d.total_transaksi_bulan_sebelumnya)}</div>
                            `;
                            if(d.sudah_ada_saldo_awal) {
                                detail += `<div class="text-orange-600 font-bold mt-1">⚠️ Sudah ada saldo terdaftar: Rp ${formatNumber(d.saldo_awal_terdaftar)}</div>`;
                            }
                            
                            elements.resultDetail.html(detail);
                            elements.result.removeClass('hidden');
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function(e) {
                        Swal.fire('Error', 'Gagal mengambil data saldo', 'error');
                    },
                    complete: function() {
                        elements.loading.addClass('hidden');
                        elements.btnGet.prop('disabled', false);
                    }
                });
            });

            // -- NUMBER FORMATTING --
            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }

            function formatInput(val) {
                let v = val.toString().replace(/\D/g, '');
                return v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            elements.saldoInput.on('input', function() {
                const val = $(this).val();
                const fmt = formatInput(val);
                $(this).val(fmt);
                elements.saldoRaw.val(fmt.replace(/\./g, ''));
            });

            // -- SUBMIT --
            $('#createSaldoAwalForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true).html('<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...');
            });
            
            // -- INIT --
            updatePreview();
        });
    </script>
@endsection
