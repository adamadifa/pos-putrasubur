@extends('layouts.pos')

@section('title', 'Tambah Penyesuaian Stok')
@section('page-title', 'Tambah Penyesuaian Stok')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('penyesuaian-stok.index') }}"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Penyesuaian</h1>
                    <p class="text-xs text-gray-500">Formulir penyesuaian stok produk</p>
                </div>
            </div>
        </div>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('penyesuaian-stok.store') }}" method="POST" id="penyesuaianForm">
                @csrf
                <div class="p-6 space-y-8">
                    
                    <!-- Section 1: Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal -->
                        <div class="space-y-1">
                            <label for="tanggal_penyesuaian" class="block text-xs font-bold text-gray-700">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-calendar text-sm"></i></span>
                                <input type="text" name="tanggal_penyesuaian" id="tanggal_penyesuaian"
                                    value="{{ old('tanggal_penyesuaian', date('Y-m-d')) }}"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 flatpickr-input"
                                    placeholder="Pilih tanggal">
                            </div>
                            @error('tanggal_penyesuaian') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Produk -->
                        <div class="space-y-1">
                            <label for="produk_id" class="block text-xs font-bold text-gray-700">
                                Produk <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="ti ti-box text-sm"></i></span>
                                <select name="produk_id" id="produk_id" onchange="updateStokSistem()"
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}"
                                            data-stok="{{ number_format($produk->stok, 2, '.', '') }}"
                                            data-satuan="{{ $produk->satuan->nama }}"
                                            {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }} ({{ $produk->satuan->nama }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('produk_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <!-- Section 2: Calculation -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 mb-4">Perhitungan Stok</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Stok Saat Ini -->
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-500">Stok Saat Ini</label>
                                <div class="relative">
                                    <input type="number" id="stok_saat_ini" readonly
                                        class="w-full px-3 py-2 text-sm font-mono font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                                </div>
                            </div>

                            <!-- Jumlah Penyesuaian -->
                            <div class="space-y-1">
                                <label for="jumlah_penyesuaian" class="block text-xs font-bold text-gray-700">
                                    Jumlah Penyesuaian (+/-) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="jumlah_penyesuaian" id="jumlah_penyesuaian"
                                        value="{{ old('jumlah_penyesuaian') }}"
                                        oninput="formatNumberInput(this); calculateStokSesudah()"
                                        class="w-full px-3 py-2 text-sm font-mono font-bold text-gray-900 border border-blue-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-blue-50/10 placeholder-gray-400"
                                        placeholder="Contoh: -5 or 10">
                                </div>
                                <p class="text-[10px] text-gray-400">Gunakan tanda minus (-) untuk pengurangan.</p>
                                @error('jumlah_penyesuaian') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Stok Akhir -->
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-700">Stok Akhir</label>
                                <div class="relative">
                                    <input type="text" id="stok_sesudah" readonly
                                        class="w-full px-3 py-2 text-sm font-mono font-bold text-gray-900 bg-gray-50 border border-gray-200 rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <!-- Section 3: Notes -->
                    <div class="space-y-1">
                         <label for="keterangan" class="block text-xs font-bold text-gray-700">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 bg-gray-50/30 resize-none"
                            placeholder="Alasan penyesuaian stok (min. 10 karakter)">{{ old('keterangan') }}</textarea>
                         @error('keterangan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('penyesuaian-stok.index') }}" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                        Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr
            flatpickr("#tanggal_penyesuaian", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,
                theme: "light",
                defaultDate: "{{ old('tanggal_penyesuaian', date('Y-m-d')) }}"
            });

            // Make functions available globally for inline event handlers
            window.updateStokSistem = function() {
                const produkSelect = document.getElementById('produk_id');
                const stokSaatIniInput = document.getElementById('stok_saat_ini');
                const selectedOption = produkSelect.options[produkSelect.selectedIndex];

                if (selectedOption && selectedOption.value) {
                    const stok = parseFloat(selectedOption.dataset.stok) || 0;
                    stokSaatIniInput.value = stok.toFixed(2);
                    calculateStokSesudah();
                } else {
                    stokSaatIniInput.value = '';
                    document.getElementById('stok_sesudah').value = '';
                }
            };

            window.formatNumberInput = function(input) {
                let cursorPos = input.selectionStart;
                let oldValue = input.value;
                let cleanValue = oldValue.replace(/[^\d\+\-\.\,]/g, '');

                // Handle minus
                let minusCount = (cleanValue.match(/-/g) || []).length;
                if (minusCount > 1) cleanValue = '-' + cleanValue.replace(/-/g, '');
                
                // Remove plus
                cleanValue = cleanValue.replace(/\+/g, '');

                let isNegative = cleanValue.startsWith('-');
                if (isNegative) cleanValue = cleanValue.substring(1);

                cleanValue = cleanValue.replace(/[^\d\.\,]/g, '');

                let commaIndex = cleanValue.lastIndexOf(',');
                let hasDecimal = false;
                let integerPart = '';
                let decimalPart = '';

                if (commaIndex !== -1) {
                    hasDecimal = true;
                    integerPart = cleanValue.substring(0, commaIndex).replace(/\./g, '');
                    decimalPart = cleanValue.substring(commaIndex + 1).substring(0, 2);
                } else {
                    let parts = cleanValue.split('.');
                    if (parts.length > 1 && parts[parts.length - 1].length <= 2 && parts[parts.length - 1].length > 0) {
                        hasDecimal = true;
                        integerPart = parts.slice(0, -1).join('');
                        decimalPart = parts[parts.length - 1];
                    } else {
                        integerPart = cleanValue.replace(/\./g, '');
                    }
                }

                 if (cleanValue !== '' && cleanValue !== '.' && cleanValue !== ',') {
                    if (hasDecimal) {
                        if (integerPart !== '') {
                            let formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            input.value = (isNegative ? '-' : '') + formatted + ',' + decimalPart;
                        } else {
                            input.value = (isNegative ? '-' : '') + ',' + decimalPart;
                        }
                    } else {
                        if (integerPart !== '') {
                            let formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            input.value = (isNegative ? '-' : '') + formatted;
                        } else {
                            input.value = isNegative ? '-' : '';
                        }
                    }
                } else {
                    input.value = isNegative ? '-' : '';
                }

                let lengthDiff = input.value.length - oldValue.length;
                input.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);
            };

            window.calculateStokSesudah = function() {
                try {
                    const stokSaatIni = parseFloat(document.getElementById('stok_saat_ini').value) || 0;
                    const jumlahRaw = document.getElementById('jumlah_penyesuaian').value;
                    const jumlahPenyesuaian = parseIndonesianNumber(jumlahRaw);
                    const stokSesudah = stokSaatIni + jumlahPenyesuaian;

                    const el = document.getElementById('stok_sesudah');
                    if(!el) return;

                    el.value = formatNumberWithDecimals(stokSesudah);

                    // Update styling based on result
                    el.classList.remove('bg-green-50', 'bg-red-50', 'bg-gray-50', 'text-green-700', 'text-red-700', 'text-gray-900');
                    if (jumlahPenyesuaian > 0) {
                        el.classList.add('bg-green-50', 'text-green-700');
                    } else if (jumlahPenyesuaian < 0) {
                        el.classList.add('bg-red-50', 'text-red-700');
                    } else {
                        el.classList.add('bg-gray-50', 'text-gray-900');
                    }
                } catch (e) { console.error(e); }
            };

            function parseIndonesianNumber(value) {
                if (!value) return 0;
                let clean = value.replace(/[^\d\-\.\,]/g, '');
                let isNegative = clean.startsWith('-');
                if (isNegative) clean = clean.substring(1);
                
                let parts = clean.split(',');
                let result = 0;
                
                if (parts.length === 2) {
                    result = parseFloat(parts[0].replace(/\./g, '') + '.' + parts[1]);
                } else {
                    result = parseFloat(clean.replace(/\./g, ''));
                }
                
                return isNegative ? -result : (isNaN(result) ? 0 : result);
            }

            function formatNumberWithDecimals(number) {
                 if (isNaN(number)) return '0';
                 let parts = number.toString().split('.');
                 let intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                 let decPart = parts.length > 1 ? parts[1].substring(0, 2) : '';
                 if(decPart.length === 1) decPart += '0';
                 return decPart ? intPart + ',' + decPart : intPart;
            }

            // Toast Logic
            function showToast(message, type = 'error') {
                const container = document.getElementById('toast-container');
                const id = 'toast-' + Date.now();
                const color = type === 'error' ? 'bg-red-500' : 'bg-green-500';
                const icon = type === 'error' ? 'ti-alert-circle' : 'ti-check-circle';
                
                const el = document.createElement('div');
                el.id = id;
                el.className = `${color} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 text-sm transform translate-x-full transition-transform duration-300`;
                el.innerHTML = `<i class="ti ${icon} text-lg"></i><span>${message}</span>`;
                
                container.appendChild(el);
                setTimeout(() => el.classList.remove('translate-x-full'), 50);
                setTimeout(() => {
                    el.classList.add('translate-x-full');
                    setTimeout(() => el.remove(), 300);
                }, 4000);
            }

            // Validation & Submit
            document.getElementById('penyesuaianForm').addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = [
                    {id: 'tanggal_penyesuaian', msg: 'Tanggal harus diisi'},
                    {id: 'produk_id', msg: 'Produk harus dipilih'},
                    {id: 'jumlah_penyesuaian', msg: 'Jumlah penyesuaian wajib diisi'},
                    {id: 'keterangan', msg: 'Keterangan wajib diisi (min 10 karakter)', rule: v => v.length >= 10},
                ];

                requiredFields.forEach(f => {
                    const el = document.getElementById(f.id);
                    const val = el.value.trim();
                    const valid = f.rule ? f.rule(val) : !!val;
                    
                    el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    if(!valid) {
                        el.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        isValid = false;
                    }
                });

                // Check numeric amount
                const amt = parseIndonesianNumber(document.getElementById('jumlah_penyesuaian').value);
                if(amt === 0) {
                     document.getElementById('jumlah_penyesuaian').classList.add('border-red-500');
                     isValid = false;
                }

                if(!isValid) {
                    e.preventDefault();
                    showToast('Mohon lengkapi form dengan benar', 'error');
                } else {
                    // Unformat number for submission
                    const el = document.getElementById('jumlah_penyesuaian');
                    const original = el.value;
                    el.value = amt; // Set to standard float for backend
                    
                    // Show loading ui
                    const btn = document.getElementById('submitBtn');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="ti ti-loader-2 animate-spin mr-2"></i>Menyimpan...';

                    // Revert visual if submit is cancelled/fails quickly (optional safety)
                    setTimeout(() => { 
                       if(!btn.disabled) el.value = original; 
                    }, 500);
                }
            });

            @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
            @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif
        });
    </script>
@endsection
