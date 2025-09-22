@extends('layouts.pos')

@section('title', 'Tambah Produk')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('produk.index') }}"
                            class="group p-2 text-gray-500 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Tambah Produk Baru</h1>
                                <p class="text-gray-500 mt-1">Lengkapi informasi produk dengan detail yang akurat</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Fields bertanda <span class="text-red-500 font-medium">*</span> wajib diisi</span>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Form Tambah Produk</h2>
                    </div>
                </div>

                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <div class="space-y-8">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Kode Produk -->
                                <div class="space-y-2">
                                    <label for="kode_produk" class="block text-sm font-semibold text-gray-700">
                                        Kode Produk <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75H16.5v-.75ZM13.5 13.5h4.5v4.5h-4.5v-4.5Z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="kode_produk" id="kode_produk"
                                            value="{{ old('kode_produk') }}"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('kode_produk') border-red-500 @enderror"
                                            placeholder="Contoh: CEN001">
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                                        </svg>
                                        Akan otomatis terisi berdasarkan nama produk
                                    </p>
                                    @error('kode_produk')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Produk -->
                                <div class="space-y-2">
                                    <label for="nama_produk" class="block text-sm font-semibold text-gray-700">
                                        Nama Produk <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 6h.008v.008H6V6Z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="nama_produk" id="nama_produk"
                                            value="{{ old('nama_produk') }}"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('nama_produk') border-red-500 @enderror"
                                            placeholder="Contoh: Cengkeh Kering Grade A">
                                    </div>
                                    @error('nama_produk')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kategori -->
                                <div class="space-y-2">
                                    <label for="kategori_id" class="block text-sm font-semibold text-gray-700">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 6h.008v.008H6V6Z" />
                                            </svg>
                                        </div>
                                        <select name="kategori_id" id="kategori_id"
                                            class="w-full pl-11 pr-10 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('kategori_id') border-red-500 @enderror appearance-none">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('kategori_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Satuan -->
                                <div class="space-y-2">
                                    <label for="satuan_id" class="block text-sm font-semibold text-gray-700">
                                        Satuan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.589-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.589-1.202L5.25 4.971Z" />
                                            </svg>
                                        </div>
                                        <select name="satuan_id" id="satuan_id"
                                            class="w-full pl-11 pr-10 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('satuan_id') border-red-500 @enderror appearance-none">
                                            <option value="">Pilih Satuan</option>
                                            @foreach ($satuans as $satuan)
                                                <option value="{{ $satuan->id }}"
                                                    {{ old('satuan_id') == $satuan->id ? 'selected' : '' }}>
                                                    {{ $satuan->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('satuan_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Harga Jual -->
                                <div class="space-y-2">
                                    <label for="harga_jual" class="block text-sm font-semibold text-gray-700">
                                        Harga Jual <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span
                                                class="text-gray-500 font-medium group-hover:text-orange-500 transition-colors">Rp</span>
                                        </div>
                                        <input type="text" name="harga_jual" id="harga_jual"
                                            value="{{ old('harga_jual') }}"
                                            class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('harga_jual') border-red-500 @enderror"
                                            placeholder="0">
                                    </div>
                                    @error('harga_jual')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Stok Awal -->
                                <div class="space-y-2">
                                    <label for="stok" class="block text-sm font-semibold text-gray-700">
                                        Stok Awal
                                        <span class="text-xs text-gray-500">(Akan diatur melalui Saldo Awal Stok)</span>
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="stok" id="stok" value="0" readonly
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                                            placeholder="0">
                                    </div>
                                    <p class="text-xs text-gray-500">Stok akan diatur melalui menu Saldo Awal Stok</p>
                                </div>

                                <!-- Stok Minimal -->
                                <div class="space-y-2">
                                    <label for="stok_minimal" class="block text-sm font-semibold text-gray-700">
                                        Stok Minimal
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="stok_minimal" id="stok_minimal"
                                            value="{{ old('stok_minimal', '0') }}"
                                            class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white @error('stok_minimal') border-red-500 @enderror"
                                            placeholder="0">
                                    </div>
                                    <p class="text-xs text-gray-500">Batas minimum untuk notifikasi stok menipis</p>
                                    @error('stok_minimal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Foto Produk -->
                        <div class="space-y-6">
                            <div
                                class="upload-area border-2 border-dashed border-gray-300 rounded-xl hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 cursor-pointer group">
                                <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                                    <div
                                        class="mx-auto h-16 w-16 text-gray-400 group-hover:text-purple-500 transition-colors duration-300 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1" stroke="currentColor" class="w-16 h-16">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="foto" class="relative cursor-pointer">
                                            <span
                                                class="text-lg font-semibold text-purple-600 hover:text-purple-700 group-hover:text-purple-700 transition-colors">
                                                Klik untuk upload foto produk
                                            </span>
                                            <input id="foto" name="foto" type="file" class="sr-only"
                                                accept="image/*">
                                        </label>
                                        <p class="text-gray-500">atau drag & drop file gambar di sini</p>
                                    </div>
                                    <div class="mt-4 flex items-center space-x-6 text-sm text-gray-500">
                                        <div class="flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>PNG, JPG, JPEG</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            <span>Max 2MB</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                            <span>Opsional</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('foto')
                                <p class="mt-3 text-sm text-red-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                        <a href="{{ route('produk.index') }}"
                            class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 group-hover:text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>

                        <div class="flex items-center space-x-4">
                            <button type="reset"
                                class="inline-flex items-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold text-base rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 mr-3 group-hover:text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Reset Form
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-base rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Simpan Produk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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
                stok: {
                    required: false,
                    numeric: true,
                    min: 0
                },
                stok_minimal: {
                    required: false,
                    numeric: true,
                    min: 0,
                    allowEmpty: true
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
                stok: {
                    numeric: 'Stok awal harus berupa angka.',
                    min: 'Stok awal tidak boleh kurang dari 0.'
                },
                stok_minimal: {
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
                'stok_minimal'
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
                const fieldContainer = field.closest('.space-y-2');
                const rules = validationRules[fieldName];
                const messages = validationMessages[fieldName];

                // Remove existing error and success states
                field.removeClass('border-red-500 border-green-500').addClass('border-gray-300');
                fieldContainer.find('.error-message').remove();

                // Skip validation for empty optional fields
                if (!value && !rules.required) {
                    return;
                }

                // Skip validation for empty fields that allow empty
                if (!value && rules.allowEmpty) {
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

            // Apply formatting to stok_minimal and harga fields
            formatNumberInput(document.getElementById('stok_minimal'));
            formatNumberInput(document.getElementById('harga_jual'));

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
                    const rules = validationRules[fieldName];
                    if (!field.val() && fieldName !== 'foto' && rules.required) {
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

                const notification = $(`
            <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-xl z-50 notification transform transition-all duration-300 translate-x-full">
                <div class="flex items-center">
                    ${icon}
                    ${message}
                </div>
            </div>
        `);

                $('body').append(notification);

                // Animate in
                setTimeout(function() {
                    notification.removeClass('translate-x-full');
                }, 100);

                // Animate out and remove
                setTimeout(function() {
                    notification.addClass('translate-x-full');
                    setTimeout(function() {
                        notification.remove();
                    }, 300);
                }, 4000);
            }
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
