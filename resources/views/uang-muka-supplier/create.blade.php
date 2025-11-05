@extends('layouts.pos')

@section('title', 'Tambah Uang Muka Supplier')
@section('page-title', 'Tambah Uang Muka Supplier Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('uang-muka-supplier.index') }}"
                        class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-red-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl shadow-lg">
                            <i class="ti ti-currency-dollar text-xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                Tambah Uang Muka Supplier
                            </h1>
                            <p class="text-gray-500 mt-1">Input uang muka yang diberikan kepada supplier</p>
                        </div>
                    </div>
                </div>
                <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                    <i class="ti ti-info-circle text-sm"></i>
                    <span>Fields bertanda <span class="text-red-500 font-medium">*</span> wajib diisi</span>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-check text-lg text-green-400"></i>
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
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-alert-circle text-lg text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terdapat {{ $errors->count() }} kesalahan yang perlu diperbaiki:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
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

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="ti ti-currency-dollar text-lg text-orange-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Form Uang Muka Supplier</h2>
                </div>
            </div>

            <form action="{{ route('uang-muka-supplier.store') }}" method="POST" class="p-8">
                @csrf
                <div class="space-y-6">
                    <!-- Supplier -->
                    <div>
                        <label for="supplier_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Supplier <span class="text-red-500">*</span>
                        </label>
                        <select name="supplier_id" id="supplier_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <div class="date-input-wrapper">
                            <input type="text" id="tanggal" value="{{ old('tanggal', date('d/m/Y')) }}"
                                class="flatpickr-input w-full px-4 py-3" placeholder="Pilih tanggal" required readonly>
                            <i class="ti ti-calendar"></i>
                        </div>
                        <input type="hidden" name="tanggal" id="tanggal_hidden" value="{{ old('tanggal', date('Y-m-d')) }}">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah Uang Muka -->
                    <div>
                        <label for="jumlah_uang_muka" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Uang Muka <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jumlah_uang_muka" id="jumlah_uang_muka"
                            value="{{ old('jumlah_uang_muka') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Masukkan jumlah uang muka (Rp)" required>
                        @error('jumlah_uang_muka')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode_pembayaran" id="metode_pembayaran" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Pilih Metode Pembayaran</option>
                            @foreach ($metodePembayaran as $metode)
                                <option value="{{ $metode->kode }}" {{ old('metode_pembayaran') == $metode->kode ? 'selected' : '' }}>
                                    {{ $metode->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('metode_pembayaran')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kas/Bank -->
                    <div>
                        <label for="kas_bank_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kas/Bank <span class="text-red-500">*</span>
                        </label>
                        <select name="kas_bank_id" id="kas_bank_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Pilih Kas/Bank</option>
                            @foreach ($kasBank as $kas)
                                <option value="{{ $kas->id }}" {{ old('kas_bank_id') == $kas->id ? 'selected' : '' }}>
                                    {{ $kas->nama }} @if($kas->no_rekening) - {{ $kas->no_rekening }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('kas_bank_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <a href="{{ route('uang-muka-supplier.index') }}"
                            class="flex-1 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors text-center">
                            <i class="ti ti-x text-lg mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-1 py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors">
                            <i class="ti ti-device-floppy text-lg mr-2"></i>
                            Simpan Uang Muka
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Setup number formatting untuk jumlah uang muka
            function setupNumberInput(input) {
                if (!input) return;
                
                input.addEventListener('input', function(e) {
                    // Get cursor position before formatting
                    let cursorPos = e.target.selectionStart;
                    let oldValue = e.target.value;
                    
                    // Remove all non-digit characters
                    let cleanValue = oldValue.replace(/[^\d]/g, '');
                    
                    // Format with thousand separator (Indonesian format)
                    if (cleanValue) {
                        let formattedValue = new Intl.NumberFormat('id-ID').format(parseInt(cleanValue));
                        e.target.value = formattedValue;
                        
                        // Adjust cursor position after formatting
                        // Count dots before cursor position in old value
                        let dotsBeforeCursor = (oldValue.substring(0, cursorPos).match(/\./g) || []).length;
                        let dotsAfterFormat = (formattedValue.match(/\./g) || []).length;
                        let newCursorPos = cursorPos + (dotsAfterFormat - dotsBeforeCursor);
                        e.target.setSelectionRange(Math.min(newCursorPos, formattedValue.length), Math.min(newCursorPos, formattedValue.length));
                    } else {
                        e.target.value = '';
                    }
                });
            }

            // Setup number input on page load
            document.addEventListener('DOMContentLoaded', function() {
                setupNumberInput(document.getElementById('jumlah_uang_muka'));
            });

            // Flatpickr for date
            document.addEventListener('DOMContentLoaded', function() {
                if (document.querySelector('#tanggal')) {
                    flatpickr("#tanggal", {
                        dateFormat: "d/m/Y",
                        defaultDate: "{{ old('tanggal', date('d/m/Y')) }}",
                        onChange: function(selectedDates, dateStr, instance) {
                            const date = selectedDates[0];
                            if (date) {
                                const formattedDate = date.getFullYear() + '-' + 
                                    String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                                    String(date.getDate()).padStart(2, '0');
                                document.getElementById('tanggal_hidden').value = formattedDate;
                            }
                        }
                    });
                }
            });

            // Form submission - convert currency format
            document.querySelector('form').addEventListener('submit', function(e) {
                const jumlahInput = document.getElementById('jumlah_uang_muka');
                if (jumlahInput && jumlahInput.value) {
                    // Remove all non-digit characters (dots, commas, spaces, etc)
                    let numericValue = jumlahInput.value.replace(/[^\d]/g, '');
                    if (numericValue && numericValue.length > 0) {
                        // Ensure it's a valid number
                        numericValue = parseInt(numericValue).toString();
                        jumlahInput.value = numericValue;
                        console.log('Converted value:', numericValue); // Debug log
                    } else {
                        e.preventDefault();
                        alert('Jumlah uang muka tidak valid');
                        return false;
                    }
                }
            });
        </script>
    @endpush
@endsection

