@extends('layouts.pos')

@section('title', 'Tambah Produk')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('produk.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Tambah Produk</h1>
                    <p class="text-xs text-gray-500">Input data produk baru</p>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 shadow-sm flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                <button type="button" class="ml-auto text-green-500 hover:bg-green-100 p-1.5 rounded-lg" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800">Terdapat kesalahan pada form:</p>
                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Instruction Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Petunjuk Pengisian Produk</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Isi <strong>Nama Produk</strong> dengan lengkap dan jelas.</li>
                            <li><strong>Kode Produk</strong> akan digenerate otomatis, namun anda bisa menyesuaikannya.</li>
                            <li>Pastikan <strong>Harga Beli</strong> dan <strong>Harga Jual</strong> diisi dengan angka yang valid.</li>
                            <li>Upload foto produk (opsional) dengan format <strong>JPG/PNG</strong> maksimal 2MB.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 space-y-4">
                    <!-- Kode Produk (Auto) -->
                    <div class="field-wrapper">
                        <label for="kode_produk" class="block text-xs font-bold text-gray-700 mb-1">
                            Kode <span class="text-gray-400 font-normal">(Auto)</span>
                        </label>
                        <input type="text" name="kode_produk" id="kode_produk" value="{{ old('kode_produk') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed"
                               placeholder="PRD..." readonly disabled>
                        @error('kode_produk') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Produk -->
                    <div class="field-wrapper">
                        <label for="nama_produk" class="block text-xs font-bold text-gray-700 mb-1">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50/30 @error('nama_produk') border-red-500 @enderror"
                               placeholder="Contoh: Kopi Bubuk Robusta">
                        @error('nama_produk') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="field-wrapper">
                        <label for="kategori_id" class="block text-xs font-bold text-gray-700 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_id" id="kategori_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('kategori_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Satuan -->
                    <div class="field-wrapper">
                        <label for="satuan_id" class="block text-xs font-bold text-gray-700 mb-1">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select name="satuan_id" id="satuan_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('satuan_id') border-red-500 @enderror">
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan->id }}" {{ old('satuan_id') == $satuan->id ? 'selected' : '' }}>
                                    {{ $satuan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan_id') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Harga Beli -->
                    <div class="field-wrapper">
                        <label for="harga_beli" class="block text-xs font-bold text-gray-700 mb-1">
                            Harga Beli <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">Rp</span>
                            <input type="text" name="harga_beli" id="harga_beli" value="{{ old('harga_beli') }}"
                                   class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('harga_beli') border-red-500 @enderror"
                                   placeholder="0">
                        </div>
                        @error('harga_beli') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="field-wrapper">
                        <label for="harga_jual" class="block text-xs font-bold text-gray-700 mb-1">
                            Harga Jual <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">Rp</span>
                            <input type="text" name="harga_jual" id="harga_jual" value="{{ old('harga_jual') }}"
                                   class="w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('harga_jual') border-red-500 @enderror"
                                   placeholder="0">
                        </div>
                        @error('harga_jual') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Stok Saat Ini -->
                    <div class="field-wrapper">
                        <label for="stok" class="block text-xs font-bold text-gray-700 mb-1">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="stok" id="stok" value="{{ old('stok', 0) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('stok') border-red-500 @enderror"
                               placeholder="0">
                        @error('stok') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Stok Minimal -->
                    <div class="field-wrapper">
                        <label for="stok_minimal" class="block text-xs font-bold text-gray-700 mb-1">
                            Stok Minimal
                        </label>
                        <input type="text" name="stok_minimal" id="stok_minimal" value="{{ old('stok_minimal', 0) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-gray-50/30 @error('stok_minimal') border-red-500 @enderror"
                               placeholder="0">
                        @error('stok_minimal') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                    </div>

                    <!-- Compact Image Upload -->
                    <div class="border-t border-gray-100 pt-4 mt-2">
                       <label class="block text-xs font-bold text-gray-700 mb-3">Foto Produk</label>
                       
                       <div class="flex items-start gap-4">
                            <!-- Preview Box -->
                            <div id="image-preview-container" class="w-16 h-16 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <img id="preview-img" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                            </div>
                            
                            <!-- Input & Info -->
                            <div class="flex-1 field-wrapper">
                                <div class="upload-area relative">
                                    <input id="foto" name="foto" type="file" class="block w-full text-xs text-gray-500
                                      file:mr-4 file:py-1.5 file:px-3
                                      file:rounded-md file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-gray-100 file:text-gray-600
                                      file:cursor-pointer hover:file:bg-gray-200
                                      cursor-pointer
                                    " accept="image/*">
                                </div>
                                <p class="mt-1.5 text-[10px] text-gray-400">
                                    Format: JPG, PNG, max 2MB. Opsional.
                                </p>
                                @error('foto') <p class="mt-1 text-xs text-red-500 error-message">{{ $message }}</p> @enderror
                            </div>
                       </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Reset Form
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Frontend Validation Rules and Messages
            const validationRules = {
                kode_produk: {
                    required: true,
                    maxLength: 50
                },
                nama_produk: {
                    required: true,
                    maxLength: 100
                },
                kategori_id: {
                    required: true
                },
                satuan_id: {
                    required: true
                },
                harga_jual: {
                    required: true,
                    numeric: true,
                    min: 0
                },
                harga_beli: {
                    required: true,
                    numeric: true,
                    min: 0
                },
                stok: {
                    required: true,
                    numeric: true,
                    min: 0
                },
                stok_minimal: {
                    required: true,
                    numeric: true,
                    min: 0
                },
                foto: {
                    required: false,
                    image: true,
                    maxSize: 2048 // KB
                }
            };

            const validationMessages = {
                kode_produk: {
                    required: 'Kode produk wajib diisi.',
                    maxLength: 'Kode produk maksimal 50 karakter.'
                },
                nama_produk: {
                    required: 'Nama produk wajib diisi.',
                    maxLength: 'Nama produk maksimal 100 karakter.'
                },
                kategori_id: {
                    required: 'Kategori wajib dipilih.'
                },
                satuan_id: {
                    required: 'Satuan wajib dipilih.'
                },
                harga_jual: {
                    required: 'Harga jual wajib diisi.',
                    numeric: 'Harga jual harus berupa angka.',
                    min: 'Harga jual tidak boleh kurang dari 0.'
                },
                harga_beli: {
                    required: 'Harga beli wajib diisi.',
                    numeric: 'Harga beli harus berupa angka.',
                    min: 'Harga beli tidak boleh kurang dari 0.'
                },
                stok: {
                    required: 'Stok awal wajib diisi.',
                    numeric: 'Stok awal harus berupa angka.',
                    min: 'Stok awal tidak boleh kurang dari 0.'
                },
                stok_minimal: {
                    required: 'Stok minimal wajib diisi.',
                    numeric: 'Stok minimal harus berupa angka.',
                    min: 'Stok minimal tidak boleh kurang dari 0.'
                },
                foto: {
                    image: 'File harus berupa gambar.',
                    mimes: 'Format gambar harus JPEG, PNG, atau JPG.',
                    maxSize: 'Ukuran gambar maksimal 2MB.'
                }
            };

            // Real-time validation for form fields
            const fieldsToValidate = ['kode_produk', 'nama_produk', 'kategori_id', 'satuan_id', 'harga_jual',
                'harga_beli',
                'stok', 'stok_minimal'
            ];

            fieldsToValidate.forEach(function(fieldName) {
                const field = $(`#${fieldName}`);
                let validationTimeout;

                field.on('input change blur', function() {
                    const value = $(this).val();

                    // Clear previous timeout
                    clearTimeout(validationTimeout);

                    // Don't validate empty fields on input (only on blur)
                    if (!value && $(this)[0].type !== 'blur') {
                        return;
                    }

                    // Set timeout to avoid too many validations
                    validationTimeout = setTimeout(function() {
                        validateField(fieldName, value);
                    }, 300);
                });

                // Immediate validation on blur for required fields
                field.on('blur', function() {
                    const value = $(this).val();
                    clearTimeout(validationTimeout);
                    validateField(fieldName, value);
                });
            });

            // Frontend Validate field function
            function validateField(fieldName, value) {
                const field = $(`#${fieldName}`);
                const fieldContainer = field.closest('.field-wrapper');
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Remove existing error and success states
                field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
                fieldContainer.find('.error-message').remove();

                // Skip validation for empty optional fields
                if (!value && !rules.required) {
                    return;
                }

                let isValid = true;
                let errorMessage = '';

                // Required validation
                if (rules.required && (!value || value.toString().trim() === '')) {
                    isValid = false;
                    errorMessage = messages.required;
                }
                // Max length validation
                else if (rules.maxLength && value && value.length > rules.maxLength) {
                    isValid = false;
                    errorMessage = messages.maxLength;
                }
                // Numeric validation
                else if (rules.numeric && value) {
                    // Parse Indonesian format number
                    let numericValue = parseIndonesianNumber(value);
                    if (isNaN(numericValue)) {
                        isValid = false;
                        errorMessage = messages.numeric;
                    } else if (numericValue < rules.min) {
                        isValid = false;
                        errorMessage = messages.min;
                    }
                }

                if (!isValid) {
                    // Add error styling
                    field.removeClass('border-gray-300 border-green-500').addClass('border-red-500');

                    // Add error message
                    const errorHtml = `
                <p class="mt-2 text-sm text-red-600 flex items-center error-message">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    ${errorMessage}
                </p>
            `;
                    fieldContainer.append(errorHtml);
                } else {
                    // Add success styling (green border only)
                    field.removeClass('border-gray-300 border-red-500').addClass('border-green-500');
                }
            }

            // File upload preview and validation
            const fileInput = document.getElementById('foto');
            const uploadArea = document.querySelector('.upload-area');

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-purple-500', 'bg-purple-100');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-purple-500', 'bg-purple-100');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-purple-500', 'bg-purple-100');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFileSelect(file);
                }
            });

            function handleFileSelect(file) {
                // Validate file
                validateFileField('foto', file);

                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.innerHTML = `
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center space-x-4">
                        <img src="${e.target.result}" alt="Preview" class="h-20 w-20 object-cover rounded-lg border-2 border-white shadow-md">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">${file.name}</p>
                            <p class="text-xs text-gray-500 mt-1">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Siap diupload
                                </span>
                            </div>
                        </div>
                        <button type="button" onclick="removePreview()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;

                    // Remove existing preview
                    const existingPreview = uploadArea.querySelector('.preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    preview.className = 'preview';
                    uploadArea.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }

            // File validation function
            function validateFileField(fieldName, file) {
                const fieldContainer = uploadArea.parentElement;
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Remove existing error and success states
                fieldContainer.querySelectorAll('.error-message').forEach(el => el.remove());

                if (!file && !rules.required) {
                    return;
                }

                let isValid = true;
                let errorMessage = '';

                if (file) {
                    // Check file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!validTypes.includes(file.type)) {
                        isValid = false;
                        errorMessage = messages.mimes;
                    }
                    // Check file size (in KB)
                    else if (file.size / 1024 > rules.maxSize) {
                        isValid = false;
                        errorMessage = messages.maxSize;
                    }
                }

                if (!isValid) {
                    // Add error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'mt-3 error-message';
                    errorDiv.innerHTML = `
                <p class="text-sm text-red-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    ${errorMessage}
                </p>
            `;
                    fieldContainer.appendChild(errorDiv);
                }
            }

            // Auto-generate kode produk based on nama produk
            const namaProdukInput = document.getElementById('nama_produk');
            const kodeProdukInput = document.getElementById('kode_produk');

            namaProdukInput.addEventListener('input', function() {
                if (!kodeProdukInput.value) {
                    const nama = this.value.toUpperCase();
                    let kode = '';

                    // Take first 3 characters of each word
                    const words = nama.split(' ');
                    words.forEach((word, index) => {
                        if (index < 2 && word.length > 0) {
                            kode += word.substring(0, 3);
                        }
                    });

                    // Add random number
                    kode += String(Math.floor(Math.random() * 100)).padStart(2, '0');

                    if (kode.length > 3) {
                        kodeProdukInput.value = kode;
                        // Trigger validation for auto-generated code
                        validateField('kode_produk', kode);
                    }
                }
            });


            // Advanced format number input for stok fields (same as penyesuaian stok)
            function formatNumberInput(input) {
                input.addEventListener('input', function() {
                    // Get cursor position
                    let cursorPos = input.selectionStart;
                    let oldValue = input.value;

                    // Remove all non-numeric characters except dots and commas
                    let cleanValue = oldValue.replace(/[^\d\.\,]/g, '');

                    // Indonesian format: dots as thousand separators, comma as decimal separator
                    // Smart detection: if there's a comma, treat everything after as decimal
                    let commaIndex = cleanValue.lastIndexOf(',');
                    let hasDecimal = false;
                    let integerPart = '';
                    let decimalPart = '';

                    if (commaIndex !== -1) {
                        // Has comma - treat as decimal separator
                        hasDecimal = true;
                        integerPart = cleanValue.substring(0, commaIndex).replace(/\./g,
                            ''); // Remove dots from integer part
                        decimalPart = cleanValue.substring(commaIndex + 1);

                        // Limit decimal places to 2
                        if (decimalPart.length > 2) {
                            decimalPart = decimalPart.substring(0, 2);
                        }
                    } else {
                        // No comma - check if last dot might be decimal
                        let parts = cleanValue.split('.');
                        if (parts.length > 1) {
                            let lastPart = parts[parts.length - 1];
                            // If last part has 1-2 digits, treat as decimal
                            if (lastPart.length <= 2 && lastPart.length > 0) {
                                hasDecimal = true;
                                integerPart = parts.slice(0, -1).join('');
                                decimalPart = lastPart;
                            } else {
                                // If last part has more than 2 digits, treat as thousand separator
                                integerPart = cleanValue.replace(/\./g, '');
                            }
                        } else {
                            integerPart = cleanValue.replace(/\./g, '');
                        }
                    }

                    // Format with Indonesian format
                    if (cleanValue !== '' && cleanValue !== '.' && cleanValue !== ',') {
                        if (hasDecimal) {
                            // Format integer part with thousand separators, keep decimal with comma
                            if (integerPart !== '') {
                                let formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                input.value = formatted + ',' + decimalPart;
                            } else {
                                input.value = ',' + decimalPart;
                            }
                        } else {
                            // No decimal, format as integer with thousand separators
                            if (integerPart !== '') {
                                let formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                input.value = formatted;
                            } else {
                                input.value = '';
                            }
                        }
                    } else {
                        input.value = '';
                    }

                    // Adjust cursor position
                    let newLength = input.value.length;
                    let oldLength = oldValue.length;
                    let lengthDiff = newLength - oldLength;
                    input.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);
                });

                input.addEventListener('blur', function() {
                    let value = this.value.replace(/[^\d\.\,]/g, '');
                    if (value) {
                        let numericValue = parseIndonesianNumber(value);
                        if (!isNaN(numericValue) && numericValue !== 0) {
                            // Only format if the value has changed or is different from original
                            let formattedValue = formatNumberWithDecimals(numericValue);
                            if (formattedValue !== this.value) {
                                this.value = formattedValue;
                            }
                        }
                    }
                });

                input.addEventListener('focus', function() {
                    // Store the original formatted value for reference
                    this.dataset.originalValue = this.value;
                    // Don't change the format on focus - keep Indonesian format for editing
                });
            }

            // Helper functions for number parsing and formatting
            function parseIndonesianNumber(value) {
                if (!value || value === '') return 0;

                // Remove all non-numeric characters except dots and commas
                let clean = value.replace(/[^\d\.\,]/g, '');

                // Convert Indonesian format to standard format
                // Indonesian: 1.000,50 (thousand separator: dot, decimal separator: comma)
                // Standard: 1000.50 (decimal separator: dot)

                // Split by comma to separate integer and decimal parts
                let parts = clean.split(',');

                if (parts.length === 2) {
                    // Has decimal part
                    let integerPart = parts[0].replace(/\./g, ''); // Remove thousand separators
                    let decimalPart = parts[1];
                    clean = integerPart + '.' + decimalPart;
                } else if (parts.length === 1) {
                    // No decimal part, just remove thousand separators
                    clean = clean.replace(/\./g, '');
                } else {
                    // Multiple commas, invalid
                    return 0;
                }

                return parseFloat(clean) || 0;
            }

            function formatNumberWithDecimals(number) {
                // Format number with thousand separators and decimal places
                if (isNaN(number)) return '0';

                // Convert to string and split integer and decimal parts
                let parts = number.toString().split('.');
                let integerPart = parts[0];
                let decimalPart = parts.length > 1 ? parts[1] : '';

                // Add thousand separators to integer part
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Limit decimal places to 2 and pad if necessary
                if (decimalPart.length > 2) {
                    decimalPart = decimalPart.substring(0, 2);
                } else if (decimalPart.length === 1) {
                    decimalPart = decimalPart + '0';
                }

                // Return formatted number
                if (decimalPart) {
                    return integerPart + ',' + decimalPart;
                } else {
                    return integerPart;
                }
            }

            // Apply formatting to stok and harga fields
            formatNumberInput(document.getElementById('stok'));
            formatNumberInput(document.getElementById('stok_minimal'));
            formatNumberInput(document.getElementById('harga_jual'));
            formatNumberInput(document.getElementById('harga_beli'));

            // Form submission validation
            $('form').on('submit', function(e) {
                let hasErrors = false;


                // Check for any visible error messages
                if ($('.error-message').length > 0) {
                    hasErrors = true;
                }

                // Check for empty required fields
                fieldsToValidate.forEach(function(fieldName) {
                    const field = $(`#${fieldName}`);
                    if (!field.val() && fieldName !== 'foto') {
                        hasErrors = true;
                        validateField(fieldName, field.val());
                    }
                });

                if (hasErrors) {
                    e.preventDefault();

                    // Scroll to first error
                    const firstError = $('.error-message').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }

                    // Show notification
                    showNotification('Harap perbaiki kesalahan pada form sebelum melanjutkan.', 'error');
                } else {

                    // Disable submit button and show loading state
                    const submitButton = $('button[type="submit"]');
                    const originalText = submitButton.html();

                    submitButton.prop('disabled', true);
                    submitButton.removeClass(
                        'hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-[1.02]');
                    submitButton.addClass('opacity-75 cursor-not-allowed');

                    // Change button content to loading state
                    submitButton.html(`
                        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    `);

                    // Show notification
                    showNotification('Sedang menyimpan produk...', 'info');

                    // Re-enable button if form submission fails (fallback)
                    setTimeout(function() {
                        if (submitButton.prop('disabled')) {
                            submitButton.prop('disabled', false);
                            submitButton.removeClass('opacity-75 cursor-not-allowed');
                            submitButton.addClass(
                                'hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:scale-[1.02]'
                            );
                            submitButton.html(originalText);
                        }
                    }, 10000); // 10 seconds fallback
                }
            });

            // Show notification function
            function showNotification(message, type = 'info') {
                let bgColor, icon;

                switch (type) {
                    case 'error':
                        bgColor = 'bg-red-500';
                        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>`;
                        break;
                    case 'success':
                        bgColor = 'bg-green-500';
                        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>`;
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        icon = `<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>`;
                        break;
                    default:
                        bgColor = 'bg-blue-500';
                        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.20a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>`;
                }

                const animationClass = type === 'success' ? 'animate-bounce' : '';
                const notification = $(`
            <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 notification transform transition-all duration-300 translate-x-full">
                <div class="flex items-center">
                    <div class="${animationClass}">
                        ${icon}
                    </div>
                    <span class="ml-1">${message}</span>
                    <button type="button" class="ml-4 text-white hover:text-gray-200 focus:outline-none" onclick="$(this).closest('.notification').addClass('translate-x-full'); setTimeout(() => $(this).closest('.notification').remove(), 300);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        `);

                $('body').append(notification);

                // Animate in with special effect for success
                setTimeout(function() {
                    notification.removeClass('translate-x-full');
                    if (type === 'success') {
                        // Add pulse effect for success
                        setTimeout(function() {
                            notification.addClass('animate-pulse');
                            setTimeout(function() {
                                notification.removeClass('animate-pulse animate-bounce');
                            }, 1000);
                        }, 500);
                    }
                }, 100);

                // Animate out and remove
                // Auto hide after different durations based on type
                const duration = type === 'success' ? 6000 : 4000; // Success messages stay longer
                setTimeout(function() {
                    notification.addClass('translate-x-full');
                    setTimeout(function() {
                        notification.remove();
                    }, 300);
                }, duration);
            }

            // Show success toast notification after page load
            @if (session('success'))
                setTimeout(function() {
                    showNotification('{{ session('success') }}', 'success');
                }, 500); // Small delay to ensure DOM is ready
            @endif
        });

        function removePreview() {
            const fileInput = document.getElementById('foto');
            const preview = document.querySelector('.preview');

            fileInput.value = '';
            if (preview) {
                preview.remove();
            }
        }
    </script>
@endpush
