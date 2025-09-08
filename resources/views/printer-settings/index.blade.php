@extends('layouts.pos')

@section('title', 'Pengaturan Printer')
@section('page-title', 'Pengaturan Printer')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-xl shadow-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">🖨️ Pengaturan Printer</h1>
                    <p class="text-blue-100">Kelola koneksi printer dan pengaturan cetak untuk sistem POS Anda</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 rounded-lg p-4 backdrop-blur-sm">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Connection Status Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Status Koneksi QZ Tray
                </h2>
            </div>
            <div class="p-6">
                <div
                    class="flex items-center justify-between bg-gradient-to-r from-gray-50 to-blue-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div id="connection-status" class="relative">
                            <div class="w-4 h-4 rounded-full bg-red-500 animate-pulse"></div>
                            <div class="absolute top-0 left-0 w-4 h-4 rounded-full bg-red-500 animate-ping opacity-75">
                            </div>
                        </div>
                        <div>
                            <span id="connection-text" class="text-sm font-medium text-gray-700">QZ Tray Disconnected</span>
                            <p class="text-xs text-gray-500">Pastikan QZ Tray sudah berjalan</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button id="generate-cert-btn"
                            class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Generate Certificate
                        </button>
                        <button id="connect-btn"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                            Connect to QZ Tray
                        </button>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                    <p class="text-sm text-blue-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <strong>Tips:</strong> Jika muncul "Untrusted Website", klik "Generate Certificate" terlebih dahulu,
                        lalu restart QZ Tray.
                    </p>
                </div>
            </div>
        </div>


        <!-- Printer Selection Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-green-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Pilih Printer
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <select id="printer-select"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                disabled>
                                <option value="">🖨️ Pilih Printer...</option>
                            </select>
                        </div>
                        <button id="refresh-printers"
                            class="px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled>
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-r-lg">
                        <p class="text-sm text-yellow-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            Pastikan QZ Tray sudah terhubung untuk melihat daftar printer
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Printer Settings Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Printer
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            📄 Ukuran Kertas
                        </label>
                        <select id="paper-size"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                            <option value="A4">📄 A4 (210 × 297 mm)</option>
                            <option value="A5">📄 A5 (148 × 210 mm)</option>
                            <option value="Letter">📄 Letter (8.5 × 11 in)</option>
                            <option value="Legal">📄 Legal (8.5 × 14 in)</option>
                            <option value="58mm">🧾 58mm (Thermal)</option>
                            <option value="80mm">🧾 80mm (Thermal)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            🔄 Orientasi
                        </label>
                        <select id="orientation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                            <option value="portrait">📱 Portrait (Vertikal)</option>
                            <option value="landscape">📺 Landscape (Horizontal)</option>
                        </select>
                    </div>
                </div>

                <!-- Auto Print Setting -->
                <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                🖨️ Auto Print Receipt
                            </h4>
                            <p class="text-xs text-gray-600 mt-1">Otomatis cetak struk setelah transaksi penjualan berhasil
                                disimpan</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="auto-print" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Print Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Test Print
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            📝 Test Content
                        </label>
                        <div class="relative">
                            <textarea id="test-content" rows="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200 font-mono text-sm"
                                placeholder="Masukkan konten untuk test print...">
=================================
        TEST PRINT
=================================
Toko: POS System
Tanggal: {{ date('d/m/Y H:i:s') }}
Kasir: {{ auth()->user()->name }}

Item Test:
- Produk A    Rp 10,000
- Produk B    Rp 15,000
              ----------
Total:        Rp 25,000

Terima kasih atas kunjungan Anda!
=================================</textarea>
                            <div class="absolute top-2 right-2">
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded">Preview</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button id="test-print-btn"
                            class="flex-1 min-w-0 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled>
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Test Print
                        </button>
                        <button id="test-raw-print-btn"
                            class="flex-1 min-w-0 px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled>
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Test Raw Print (ESC/POS)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Settings Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">💾 Simpan Pengaturan</h3>
                        <p class="text-sm text-gray-600">Simpan konfigurasi printer untuk digunakan secara default</p>
                    </div>
                    <button id="save-settings-btn"
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>

        <!-- Existing Printer Settings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-green-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Daftar Pengaturan Printer
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach ($printerSettings as $setting)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-3 h-3 rounded-full {{ $setting->is_default ? 'bg-green-500' : 'bg-gray-300' }}">
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $setting->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $setting->printer_name }}</p>
                                        @if ($setting->printer_port)
                                            <p class="text-xs text-gray-500">Port: {{ $setting->printer_port }}</p>
                                        @endif
                                        @if ($setting->description)
                                            <p class="text-xs text-gray-500">{{ $setting->description }}</p>
                                        @endif
                                        @if ($setting->printer_config)
                                            <p class="text-xs text-gray-500">
                                                Config: {{ $setting->printer_config['paper_size'] ?? 'A4' }} |
                                                {{ $setting->printer_config['orientation'] ?? 'portrait' }} |
                                                Auto-print: {{ $setting->printer_config['auto_print'] ? 'Ya' : 'Tidak' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if (!$setting->is_default)
                                    <button onclick="setAsDefault({{ $setting->id }})"
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                        Set Default
                                    </button>
                                @endif
                                <button onclick="deletePrinterSetting({{ $setting->id }})"
                                    class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Log Messages Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Log Messages
                </h2>
            </div>
            <div class="p-6">
                <div class="bg-gray-900 rounded-lg p-4 h-40 overflow-y-auto">
                    <div id="log-messages" class="text-sm font-mono space-y-1">
                        <div class="text-green-400 flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            <span class="text-gray-400 mr-2">[Ready]</span>
                            <span>System ready to connect...</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Log akan menampilkan status koneksi dan aktivitas printer secara real-time
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/qz/qz-tray.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/qz/qz-config.js') }}"></script>
    <script>
        // QZ Tray Integration
        let qzConnected = false;
        let selectedPrinter = '';

        // DOM Elements
        const connectionStatus = document.getElementById('connection-status');
        const connectionText = document.getElementById('connection-text');
        const connectBtn = document.getElementById('connect-btn');
        const generateCertBtn = document.getElementById('generate-cert-btn');
        const printerSelect = document.getElementById('printer-select');
        const refreshPrintersBtn = document.getElementById('refresh-printers');
        const testPrintBtn = document.getElementById('test-print-btn');
        const testRawPrintBtn = document.getElementById('test-raw-print-btn');
        const saveSettingsBtn = document.getElementById('save-settings-btn');
        const logMessages = document.getElementById('log-messages');

        // Logging function with modern styling
        function addLog(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');

            let iconColor, textColor, icon;
            switch (type) {
                case 'error':
                    iconColor = 'bg-red-400';
                    textColor = 'text-red-400';
                    icon = '❌';
                    break;
                case 'success':
                    iconColor = 'bg-green-400';
                    textColor = 'text-green-400';
                    icon = '✅';
                    break;
                case 'warning':
                    iconColor = 'bg-yellow-400';
                    textColor = 'text-yellow-400';
                    icon = '⚠️';
                    break;
                default:
                    iconColor = 'bg-blue-400';
                    textColor = 'text-blue-400';
                    icon = 'ℹ️';
            }

            logEntry.className = `${textColor} flex items-center`;
            logEntry.innerHTML = `
                <span class="w-2 h-2 ${iconColor} rounded-full mr-2 animate-pulse"></span>
                <span class="text-gray-400 mr-2">[${timestamp}]</span>
                <span>${icon} ${message}</span>
            `;

            logMessages.appendChild(logEntry);
            logMessages.scrollTop = logMessages.scrollHeight;
        }

        // Generate Certificate
        function generateCertificate() {
            addLog('Generating QZ Tray certificate...', 'info');
            generateCertBtn.disabled = true;
            generateCertBtn.textContent = 'Generating...';

            fetch('{{ route('generate-qz-certificate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addLog('Certificate generated successfully!', 'success');
                        addLog('Instructions:', 'info');
                        data.instructions.forEach(instruction => {
                            addLog('  ' + instruction, 'info');
                        });
                        addLog('Please restart QZ Tray and refresh this page', 'info');
                    } else {
                        addLog('Failed to generate certificate', 'error');
                    }
                })
                .catch(err => {
                    addLog('Error generating certificate: ' + err.message, 'error');
                })
                .finally(() => {
                    generateCertBtn.disabled = false;
                    generateCertBtn.textContent = 'Generate Certificate';
                });
        }

        // Connect to QZ Tray
        function connectToQZ() {
            addLog('Attempting to connect to QZ Tray...', 'info');
            connectBtn.disabled = true;
            connectBtn.textContent = 'Connecting...';

            // Setup QZ security (unsigned mode)
            try {
                addLog('Setting up QZ security...', 'info');

                // Setup certificate promise - allow unsigned
                qz.security.setCertificatePromise(function(resolve, reject) {
                    addLog('Certificate promise called - using unsigned mode', 'info');
                    resolve(); // Allow unsigned requests
                });

                // Setup signature promise - QZ Tray expects a function that returns a promise
                qz.security.setSignaturePromise(function(toSign) {
                    return function(resolve, reject) {
                        addLog('Signature promise called - returning unsigned', 'info');
                        resolve(toSign); // Allow unsigned requests
                    };
                });

                addLog('QZ security configured successfully (unsigned mode)', 'success');
            } catch (error) {
                addLog('Error setting up QZ security: ' + error.message, 'error');
            }

            qz.websocket.connect({
                retries: 3,
                delay: 1
            }).then(function() {
                qzConnected = true;
                // Update connection status with modern styling
                connectionStatus.innerHTML = `
                    <div class="w-4 h-4 rounded-full bg-green-500"></div>
                    <div class="absolute top-0 left-0 w-4 h-4 rounded-full bg-green-500 animate-ping opacity-75"></div>
                `;
                connectionText.innerHTML = `
                    <span class="text-sm font-medium text-gray-700">QZ Tray Connected</span>
                    <p class="text-xs text-gray-500">Siap untuk mencetak</p>
                `;
                connectBtn.textContent = '✅ Connected';
                connectBtn.className =
                    'px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-medium rounded-lg shadow-md cursor-not-allowed';

                // Enable printer controls
                printerSelect.disabled = false;
                refreshPrintersBtn.disabled = false;
                testPrintBtn.disabled = false;
                testRawPrintBtn.disabled = false;

                addLog('Successfully connected to QZ Tray!', 'success');

                // Auto-load printers
                loadPrinters();

            }).catch(function(err) {
                addLog('Failed to connect to QZ Tray: ' + err.message, 'error');
                connectBtn.disabled = false;
                connectBtn.textContent = 'Retry Connection';
            });
        }

        // Load available printers
        function loadPrinters() {
            addLog('Loading available printers...', 'info');

            qz.printers.find().then(function(printers) {
                // Clear existing options
                printerSelect.innerHTML = '<option value="">Pilih Printer...</option>';

                // Add printers to select
                printers.forEach(function(printer) {
                    const option = document.createElement('option');
                    option.value = printer;
                    option.textContent = printer;
                    printerSelect.appendChild(option);
                });

                addLog(`Found ${printers.length} printer(s)`, 'success');

                // Load saved settings
                loadSavedSettings();

            }).catch(function(err) {
                addLog('Error loading printers: ' + err.message, 'error');
            });
        }

        // Test print function
        function testPrint() {
            if (!selectedPrinter) {
                addLog('Please select a printer first', 'error');
                return;
            }

            const testContent = document.getElementById('test-content').value;
            addLog(`Sending test print to ${selectedPrinter}...`, 'info');

            const config = qz.configs.create(selectedPrinter, {
                units: 'mm',
                orientation: document.getElementById('orientation').value
            });

            const data = [{
                type: 'html',
                format: 'plain',
                data: `<pre style="font-family: monospace; font-size: 12px;">${testContent}</pre>`
            }];

            qz.print(config, data).then(function() {
                addLog('Test print sent successfully!', 'success');

                // Send to backend for logging
                fetch('{{ route('printer.test-print') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        printer_name: selectedPrinter,
                        test_content: testContent
                    })
                });

            }).catch(function(err) {
                addLog('Test print failed: ' + err.message, 'error');
            });
        }

        // Test raw print (ESC/POS)
        function testRawPrint() {
            if (!selectedPrinter) {
                addLog('Please select a printer first', 'error');
                return;
            }

            addLog(`Sending raw test print to ${selectedPrinter}...`, 'info');

            const config = qz.configs.create(selectedPrinter);

            // ESC/POS commands for thermal printer
            const rawData = [
                '\x1B\x40', // Initialize printer
                '\x1B\x61\x01', // Center align
                'TEST PRINT\n',
                '================\n',
                '\x1B\x61\x00', // Left align
                'Toko: POS System\n',
                'Tanggal: ' + new Date().toLocaleString() + '\n',
                'Kasir: {{ auth()->user()->name }}\n\n',
                'Item Test:\n',
                '- Produk A    Rp 10,000\n',
                '- Produk B    Rp 15,000\n',
                '              ----------\n',
                'Total:        Rp 25,000\n\n',
                '\x1B\x61\x01', // Center align
                'Terima kasih!\n',
                '================\n\n\n',
                '\x1D\x56\x42\x00' // Cut paper
            ];

            qz.print(config, rawData).then(function() {
                addLog('Raw test print sent successfully!', 'success');
            }).catch(function(err) {
                addLog('Raw test print failed: ' + err.message, 'error');
            });
        }

        // Save printer settings
        function saveSettings() {
            const settings = {
                name: 'Default Printer Setting',
                printer_name: selectedPrinter || 'Auto-detect',
                printer_port: null,
                printer_config: {
                    paper_size: document.getElementById('paper-size')?.value || 'A4',
                    orientation: document.getElementById('orientation')?.value || 'portrait',
                    auto_print: document.getElementById('auto-print')?.checked || false
                },
                description: 'Pengaturan printer default dari form',
                is_default: true
            };

            fetch('{{ route('printer.settings.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(settings)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addLog('Pengaturan printer berhasil disimpan ke database!', 'success');
                        // Reload page to show new setting
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        addLog('Gagal menyimpan pengaturan: ' + data.message, 'error');
                    }
                })
                .catch(err => {
                    addLog('Error menyimpan pengaturan: ' + err.message, 'error');
                });
        }

        // Set printer as default
        function setAsDefault(settingId) {
            fetch(`/printer/settings/${settingId}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addLog('Printer berhasil di-set sebagai default!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        addLog('Gagal set default: ' + data.message, 'error');
                    }
                })
                .catch(err => {
                    addLog('Error set default: ' + err.message, 'error');
                });
        }

        // Delete printer setting
        function deletePrinterSetting(settingId) {
            if (confirm('Apakah Anda yakin ingin menghapus pengaturan printer ini?')) {
                fetch(`/printer/settings/${settingId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addLog('Pengaturan printer berhasil dihapus!', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            addLog('Gagal menghapus: ' + data.message, 'error');
                        }
                    })
                    .catch(err => {
                        addLog('Error menghapus: ' + err.message, 'error');
                    });
            }
        }

        // Load saved settings
        function loadSavedSettings() {
            fetch('{{ route('printer.get-settings') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.settings) {
                        const settings = data.settings;
                        if (settings.default_printer) {
                            printerSelect.value = settings.default_printer;
                            selectedPrinter = settings.default_printer;
                        }
                        if (settings.paper_size) {
                            document.getElementById('paper-size').value = settings.paper_size;
                        }
                        if (settings.orientation) {
                            document.getElementById('orientation').value = settings.orientation;
                        }
                        if (settings.auto_print !== undefined) {
                            document.getElementById('auto-print').checked = settings.auto_print;
                        }
                        addLog('Settings loaded successfully', 'success');
                    }
                })
                .catch(err => {
                    addLog('Error loading settings: ' + err.message, 'error');
                });
        }

        // Event Listeners
        connectBtn.addEventListener('click', connectToQZ);
        generateCertBtn.addEventListener('click', generateCertificate);
        refreshPrintersBtn.addEventListener('click', loadPrinters);
        testPrintBtn.addEventListener('click', testPrint);
        testRawPrintBtn.addEventListener('click', testRawPrint);

        saveSettingsBtn.addEventListener('click', saveSettings);

        printerSelect.addEventListener('change', function() {
            selectedPrinter = this.value;
            if (selectedPrinter) {
                addLog(`Selected printer: ${selectedPrinter}`, 'info');
            }
        });

        // Auto-connect on page load with loading animation
        document.addEventListener('DOMContentLoaded', function() {
            // Add welcome message
            addLog('🚀 Sistem Pengaturan Printer dimuat', 'success');
            addLog('📡 Mencoba koneksi otomatis ke QZ Tray...', 'info');

            // Try to connect automatically after a short delay
            setTimeout(connectToQZ, 2000);

            // Add some interactive effects
            const cards = document.querySelectorAll('.bg-white.rounded-xl');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Add printer selection change handler with visual feedback
        printerSelect.addEventListener('change', function() {
            selectedPrinter = this.value;
            if (selectedPrinter) {
                addLog(`🖨️ Printer dipilih: ${selectedPrinter}`, 'success');
                // Add visual feedback
                this.classList.add('ring-2', 'ring-green-500');
                setTimeout(() => {
                    this.classList.remove('ring-2', 'ring-green-500');
                }, 2000);
            }
        });

        // Add hover effects for buttons
        const buttons = document.querySelectorAll('button[id*="btn"]');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                if (!this.disabled) {
                    this.style.transform = 'translateY(-2px)';
                }
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
@endpush
