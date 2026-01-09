@extends('layouts.pos')

@section('title', 'Tambah Transaksi Kas & Bank')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('transaksi-kas-bank.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Transaksi</h1>
                    <p class="text-xs text-gray-500">Buat transaksi operasional baru</p>
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

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('transaksi-kas-bank.store') }}" method="POST" id="createTransaksiForm">
                @csrf
                
                <div class="p-6 space-y-5">
                    <!-- Instruction -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                        <i class="ti ti-info-circle text-blue-500 mt-0.5"></i>
                        <div class="text-sm text-blue-900">
                            <strong>Tips:</strong> Pilih jenis transaksi (Masuk/Keluar) dengan teliti. Transaksi yang disimpan akan tercatat sebagai "Manual".
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal -->
                        <div class="field-wrapper">
                            <label for="tanggal" class="block text-xs font-bold text-gray-700 mb-1">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="ti ti-calendar text-xs"></i>
                                </span>
                                <input type="text" id="tanggal" value="{{ old('tanggal_display', date('d/m/Y')) }}"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 cursor-pointer @error('tanggal_hidden') border-red-500 @enderror"
                                    placeholder="Pilih tanggal" readonly>
                                <input type="hidden" name="tanggal_hidden" id="tanggal_hidden"
                                    value="{{ old('tanggal_hidden', date('Y-m-d')) }}">
                            </div>
                            @error('tanggal_hidden') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kas/Bank -->
                        <div class="field-wrapper">
                            <label for="kas_bank_id" class="block text-xs font-bold text-gray-700 mb-1">
                                Kas/Bank <span class="text-red-500">*</span>
                            </label>
                            <select name="kas_bank_id" id="kas_bank_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('kas_bank_id') border-red-500 @enderror">
                                <option value="">Pilih Kas/Bank</option>
                                @foreach ($kasBankList as $kasBank)
                                    <option value="{{ $kasBank->id }}" {{ old('kas_bank_id') == $kasBank->id ? 'selected' : '' }}>
                                        {{ $kasBank->nama }} ({{ $kasBank->kode }})
                                        {{ $kasBank->no_rekening ? '- ' . $kasBank->no_rekening : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kas_bank_id') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Hidden Kategori -->
                    <input type="hidden" name="kategori_transaksi" value="MN">

                    <!-- Jenis Transaksi -->
                    <div class="field-wrapper">
                        <label class="block text-xs font-bold text-gray-700 mb-2">
                            Jenis Transaksi <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <input type="radio" name="jenis_transaksi" value="D" {{ old('jenis_transaksi', 'D') === 'D' ? 'checked' : '' }} class="peer sr-only">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                        <i class="ti ti-arrow-down"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-700">Pemasukan</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-green-500 rounded-lg pointer-events-none"></div>
                            </label>
                            <label class="relative flex items-center justify-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="jenis_transaksi" value="K" {{ old('jenis_transaksi') === 'K' ? 'checked' : '' }} class="peer sr-only">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                        <i class="ti ti-arrow-up"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-700">Pengeluaran</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-red-500 rounded-lg pointer-events-none"></div>
                            </label>
                        </div>
                        @error('jenis_transaksi') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Jumlah -->
                    <div class="field-wrapper">
                        <label for="jumlah" class="block text-xs font-bold text-gray-700 mb-1">
                            Jumlah Transaksi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" name="jumlah" id="jumlah" value="{{ old('jumlah') }}"
                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 text-right font-medium @error('jumlah_raw') border-red-500 @enderror"
                                placeholder="0">
                            <input type="hidden" name="jumlah_raw" id="jumlah_raw" value="{{ old('jumlah_raw') }}">
                        </div>
                        @error('jumlah_raw') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="field-wrapper">
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 mb-1">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('keterangan') border-red-500 @enderror"
                            placeholder="Catatan tambahan (opsional)" maxlength="255">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Compact Preview -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Estimasi Transaksi</p>
                                <div class="font-medium text-gray-900 text-sm" id="preview-kas-bank">Pilih Kas/Bank</div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    <span id="preview-jenis">Masuk</span> • Manual
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900" id="preview-jumlah">Rp 0</div>
                                <div class="text-xs text-gray-500" id="preview-tanggal">{{ date('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button type="reset" id="resetBtn" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Reset
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    
    <script>
        $(document).ready(function() {
            // -- NUMBER FORMATTING LOGIC --
            function formatNumberInput(value) {
                const numericValue = value.toString().replace(/\D/g, '');
                return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function parseFormattedNumber(value) {
                return parseInt(value.replace(/\./g, '')) || 0;
            }

            const jumlahInput = $('#jumlah');
            const jumlahRaw = $('#jumlah_raw');

            jumlahInput.on('input', function(e) {
                const value = $(this).val();
                const formatted = formatNumberInput(value);
                $(this).val(formatted);
                jumlahRaw.val(parseFormattedNumber(formatted));
                updatePreview();
            });

            // -- FLATPICKR --
            flatpickr("#tanggal", {
                dateFormat: "d/m/Y",
                locale: "id",
                allowInput: false,
                clickOpens: true,
                defaultDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const backendDate = selectedDates[0].toISOString().split('T')[0];
                        $('#tanggal_hidden').val(backendDate);
                        updatePreview();
                    }
                }
            });

            // -- PREVIEW LOGIC --
            function updatePreview() {
                const kasBankText = $('#kas_bank_id option:selected').text().trim() || 'Pilih Kas/Bank';
                const jenisVal = $('input[name="jenis_transaksi"]:checked').val();
                const jumlahVal = jumlahInput.val() || '0';
                const tanggalVal = $('#tanggal').val() || '-';

                $('#preview-kas-bank').text(kasBankText);
                $('#preview-jenis').text(jenisVal === 'D' ? 'Pemasukan' : 'Pengeluaran');
                $('#preview-jumlah').text('Rp ' + jumlahVal);
                $('#preview-tanggal').text(tanggalVal);
                
                // Color tweaks for preview
                if(jenisVal === 'D') {
                    $('#preview-jumlah').removeClass('text-red-600').addClass('text-green-600');
                } else {
                    $('#preview-jumlah').removeClass('text-green-600').addClass('text-red-600');
                }
            }

            $('#kas_bank_id, input[name="jenis_transaksi"]').on('change', updatePreview);
            updatePreview(); // Init

            // -- FORM SUBMIT --
            $('#createTransaksiForm').on('submit', function() {
                const btn = $('#submitBtn');
                btn.prop('disabled', true).html('<i class="ti ti-loader animate-spin mr-2"></i>Menyimpan...');
            });

            // -- RESET --
            $('#resetBtn').on('click', function(e) {
                e.preventDefault();
                $('#createTransaksiForm')[0].reset();
                jumlahRaw.val('');
                updatePreview();
            });
        });
    </script>
@endsection
