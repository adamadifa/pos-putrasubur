<form action="{{ route('uang-muka-supplier.store') }}" method="POST" class="space-y-3" novalidate>
    @csrf

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="metode_pembayaran" id="metode_pembayaran_hidden" value="{{ old('metode_pembayaran') }}">
    <input type="hidden" name="kas_bank_id" id="kas_bank_id_hidden" value="{{ old('kas_bank_id') }}">

    <!-- Row 1: Supplier, Tanggal, Jumlah Uang Muka -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- Supplier -->
        <div>
            <label for="supplier_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Supplier <span class="text-red-500">*</span>
            </label>
            <select name="supplier_id" id="supplier_id"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm {{ $errors->has('supplier_id') ? 'border-red-500' : 'border-gray-300' }}">
                <option value="">Pilih Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama }}
                    </option>
                @endforeach
            </select>
            @error('supplier_id')
                <p class="mt-1 text-xs text-red-600 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Tanggal -->
        <div>
            <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Tanggal <span class="text-red-500">*</span>
            </label>
            <div class="date-input-wrapper">
                <input type="text" id="tanggal" value="{{ old('tanggal', date('d/m/Y')) }}"
                    class="flatpickr-input w-full py-2 pl-10 pr-3 rounded-lg border text-sm {{ $errors->has('tanggal') ? 'border-red-500' : 'border-gray-300' }}"
                    placeholder="Pilih tanggal" required readonly>
                <i class="ti ti-calendar" style="left: 0.75rem;"></i>
            </div>
            <input type="hidden" name="tanggal" id="tanggal_hidden" value="{{ old('tanggal', date('Y-m-d')) }}">
            @error('tanggal')
                <p class="mt-1 text-xs text-red-600 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Jumlah Uang Muka -->
        <div>
            <label for="jumlah_uang_muka" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Jumlah Uang Muka <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jumlah_uang_muka" id="jumlah_uang_muka" value="{{ old('jumlah_uang_muka') }}"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm {{ $errors->has('jumlah_uang_muka') ? 'border-red-500' : 'border-gray-300' }}"
                placeholder="Masukkan jumlah (Rp)" required>
            @error('jumlah_uang_muka')
                <p class="mt-1 text-xs text-red-600 flex items-center">
                    <i class="ti ti-alert-circle mr-1"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <!-- Row 2: Metode Pembayaran -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Metode Pembayaran <span class="text-red-500">*</span>
        </label>
        <div class="grid gap-2 w-full" id="paymentMethodContainer"
            style="grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));">
            @foreach ($metodePembayaran as $metode)
                <label class="relative cursor-pointer payment-method-option">
                    <input type="radio" name="metode_pembayaran_radio" value="{{ $metode->kode }}"
                        class="sr-only payment-method-radio"
                        {{ old('metode_pembayaran') == $metode->kode ? 'checked' : '' }}>
                    <div
                        class="p-2.5 border-2 rounded-lg transition-all duration-200 payment-method-card {{ $errors->has('metode_pembayaran') ? 'border-red-500' : 'border-gray-200' }} hover:border-orange-300 hover:bg-orange-50">
                        <div class="flex items-center justify-center">
                            <div class="w-7 h-7 bg-orange-100 rounded-lg flex items-center justify-center mr-2">
                                <i
                                    class="ti {{ $metode->icon_display ?? 'ti-credit-card' }} text-orange-600 text-xs"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-900">{{ $metode->nama }}</span>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
        @error('metode_pembayaran')
            <p class="mt-1 text-xs text-red-600 flex items-center">
                <i class="ti ti-alert-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Row 3: Kas/Bank Selection -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Kas/Bank <span class="text-red-500">*</span>
        </label>
        <!-- Message when no payment method selected -->
        <div id="kasBankMessage"
            class="text-center py-4 text-gray-500 border-2 border-dashed border-gray-200 rounded-lg"
            style="min-height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <i class="ti ti-arrow-up text-xl mb-1"></i>
            <p class="text-xs">Pilih metode pembayaran terlebih dahulu untuk melihat pilihan kas/bank</p>
        </div>

        <div class="flex flex-col gap-2 w-full" id="kasBankContainer" style="display: none;">
            @foreach ($kasBank as $kas)
                <label class="relative cursor-pointer kas-bank-option">
                    <input type="radio" name="kas_bank_radio" value="{{ $kas->id }}"
                        data-jenis="{{ $kas->jenis }}" class="sr-only kas-bank-radio"
                        {{ old('kas_bank_id') == $kas->id ? 'checked' : '' }}>
                    <div
                        class="p-3 border-2 rounded-lg transition-all duration-300 kas-bank-card {{ $errors->has('kas_bank_id') ? 'border-red-500' : 'border-gray-200' }} hover:border-orange-400 hover:bg-gradient-to-br hover:from-orange-50 hover:to-red-50 flex items-center justify-between shadow-sm hover:shadow-md w-full">
                        <div class="flex items-center flex-1">
                            <div
                                class="w-12 h-12 rounded-lg flex items-center justify-center mr-3 overflow-hidden shadow-sm flex-shrink-0">
                                @if ($kas->jenis === 'KAS')
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                        <i class="ti ti-cash text-green-600 text-lg"></i>
                                    </div>
                                @else
                                    @if ($kas->image)
                                        <img src="{{ asset('storage/' . $kas->image) }}" alt="{{ $kas->nama }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                            <i class="ti ti-building-bank text-blue-600 text-lg"></i>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 text-sm">{{ $kas->nama }}</div>
                                @if ($kas->no_rekening)
                                    <div class="text-xs text-gray-500">{{ $kas->no_rekening }}</div>
                                @endif
                            </div>
                        </div>
                        <div
                            class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center flex-shrink-0 kas-bank-check">
                            <i class="ti ti-check text-white text-xs opacity-0"></i>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
        @error('kas_bank_id')
            <p class="mt-1 text-xs text-red-600 flex items-center">
                <i class="ti ti-alert-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Row 4: Keterangan -->
    <div>
        <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Keterangan
        </label>
        <textarea name="keterangan" id="keterangan" rows="2"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none text-sm {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}"
            placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
        @error('keterangan')
            <p class="mt-1 text-xs text-red-600 flex items-center">
                <i class="ti ti-alert-circle mr-1"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-3 pt-2 border-t border-gray-200 mt-3">
        <button type="button" id="cancelFormBtn"
            class="flex-1 py-2 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors text-center text-sm">
            <i class="ti ti-x mr-1.5"></i>
            Batal
        </button>
        <button type="submit"
            class="flex-1 py-2 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors text-sm">
            <i class="ti ti-device-floppy mr-1.5"></i>
            Simpan Uang Muka
        </button>
    </div>
</form>
