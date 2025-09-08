<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Putra Subur') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Custom Flatpickr Styling - Clean & Modern */
        .flatpickr-calendar {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            width: 320px;
            padding: 0;
        }

        /* Header Section */
        .flatpickr-months {
            background: #3b82f6;
            color: white;
            padding: 16px 0;
            margin: 0;
        }

        .flatpickr-month {
            color: white;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
        }

        .flatpickr-current-month {
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .flatpickr-monthDropdown-months {
            background: transparent;
            border: none;
            color: white;
            font-weight: 700;
            font-size: 16px;
            padding: 4px 8px;
            border-radius: 8px;
            cursor: pointer;
        }

        .flatpickr-monthDropdown-months:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .flatpickr-monthDropdown-months option {
            background: #3b82f6;
            color: white;
            padding: 8px;
        }

        .flatpickr-current-month .numInputWrapper {
            background: transparent;
            border: none;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .flatpickr-current-month .numInputWrapper input {
            background: transparent;
            border: none;
            color: white;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            padding: 4px 8px;
            border-radius: 8px;
            cursor: pointer;
        }

        .flatpickr-current-month .numInputWrapper input:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Navigation Arrows */
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: white;
            fill: white;
            padding: 8px;
            border-radius: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.1);
        }

        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            width: 18px;
            height: 18px;
        }

        /* Weekdays Header */
        .flatpickr-weekdays {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 0 8px 0;
            margin: 0;
        }

        .flatpickr-weekday {
            color: #64748b;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 0;
        }

        /* Days Grid */
        .flatpickr-days {
            background: white;
            padding: 12px;
            margin: 0;
        }

        .flatpickr-day {
            border-radius: 12px;
            border: 2px solid transparent;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
            height: 40px;
            line-height: 36px;
            margin: 2px;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .flatpickr-day:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #1e40af;
            transform: scale(1.05);
        }

        .flatpickr-day.selected {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .flatpickr-day.selected:hover {
            background: #2563eb;
            border-color: #2563eb;
            transform: scale(1.05);
        }

        .flatpickr-day.today {
            background: #10b981;
            border-color: #10b981;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .flatpickr-day.today:hover {
            background: #059669;
            border-color: #059669;
            transform: scale(1.05);
        }

        .flatpickr-day.disabled {
            color: #cbd5e1;
            background: #f8fafc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .flatpickr-day.disabled:hover {
            background: #f8fafc;
            border-color: transparent;
            transform: none;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #94a3b8;
            opacity: 0.7;
        }

        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover {
            background: #f1f5f9;
            color: #64748b;
            opacity: 1;
            transform: scale(1.05);
        }

        /* Input Field Styling */
        .flatpickr-input {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            width: 100%;
        }

        .flatpickr-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 4px 6px rgba(0, 0, 0, 0.1);
            outline: none;
        }

        .flatpickr-input:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Calendar Icon */
        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper .ti-calendar {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 18px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .date-input-wrapper:focus-within .ti-calendar,
        .date-input-wrapper:hover .ti-calendar {
            color: #3b82f6;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .flatpickr-calendar {
                width: 100% !important;
                max-width: 300px;
                margin: 0 auto;
            }

            .flatpickr-day {
                height: 36px;
                line-height: 32px;
                font-size: 13px;
            }

            .flatpickr-months {
                padding: 12px 0;
            }

            .flatpickr-month {
                font-size: 14px;
            }
        }

        /* Smooth Animations */
        .flatpickr-calendar {
            animation: calendarSlideIn 0.3s ease-out;
        }

        @keyframes calendarSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Fix scrolling issues */
        html {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
        }

        /* Improve scrolling performance */
        .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        /* Custom scrollbar for webkit browsers */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Prevent layout shift during scrolling */
        * {
            box-sizing: border-box;
        }

        /* Optimize animations and transitions */
        .transition-all {
            will-change: transform, opacity;
        }

        /* Sidebar toggle button animations */
        .sidebar-toggle-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-toggle-btn:hover {
            transform: scale(1.05);
        }

        .sidebar-toggle-btn:active {
            transform: scale(0.95);
        }

        /* Sidebar slide animation */
        .sidebar-slide {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Icon rotation animation */
        .icon-rotate {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Fix for mobile scrolling */
        @media (max-width: 1024px) {
            .overflow-y-auto {
                -webkit-overflow-scrolling: touch;
                overscroll-behavior: contain;
            }
        }

        /* Additional scroll optimizations */
        .scroll-smooth {
            scroll-behavior: smooth;
        }

        /* Prevent horizontal scroll */
        .min-h-full {
            min-height: 100%;
        }

        /* Optimize gradient background */
        .bg-gradient-to-br {
            background-attachment: fixed;
        }

        /* Improve button transitions */
        #scrollToTop {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #scrollToTop:hover {
            transform: translateY(-2px) scale(1.05);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased" x-data="{
    sidebarOpen: false,
    init() {
        // Get saved sidebar state from localStorage
        const savedState = localStorage.getItem('sidebarOpen');

        // Set initial state based on saved value and screen size
        if (savedState !== null) {
            this.sidebarOpen = savedState === 'true';
        } else {
            // Default to open on desktop, closed on mobile
            this.sidebarOpen = window.innerWidth >= 1024;
        }

        // Save sidebar state to localStorage whenever it changes
        this.$watch('sidebarOpen', (value) => {
            localStorage.setItem('sidebarOpen', value.toString());
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            // Auto-close sidebar on mobile if it was open
            if (window.innerWidth < 1024 && this.sidebarOpen) {
                this.sidebarOpen = false;
            }
        });
    }
}">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="sidebar-slide fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-all duration-300 ease-in-out flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            style="backdrop-filter: blur(10px);">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-primary-600 text-white grid place-items-center text-sm font-bold">
                        P
                    </div>
                    <span class="text-xl font-semibold text-gray-900">PUTRA SUBUR</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto min-h-0">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>

                <!-- Master Data Section -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Master Data</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('produk.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('produk.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Produk
                        </a>

                        <a href="{{ route('kategori.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('kategori.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            Kategori
                        </a>

                        <a href="{{ route('satuan.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('satuan.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Satuan
                        </a>

                        <a href="{{ route('pelanggan.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggan.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Pelanggan
                        </a>

                        <a href="{{ route('supplier.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('supplier.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8a3 3 0 100-6 3 3 0 000 6z" />
                            </svg>
                            Supplier
                        </a>

                        <a href="{{ route('metode-pembayaran.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('metode-pembayaran.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            Metode Pembayaran
                        </a>

                        <a href="{{ route('kas-bank.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('kas-bank.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            Kas & Bank
                        </a>
                    </div>
                </div>

                <!-- Penjualan Section -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penjualan</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('penjualan.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('penjualan.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Penjualan
                        </a>

                        <a href="{{ route('pembayaran.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pembayaran.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            Pembayaran
                        </a>
                    </div>
                </div>

                <!-- Pembelian Section -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembelian</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('pembelian.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pembelian.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Pembelian
                        </a>

                        <a href="{{ route('pembayaran-pembelian.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pembayaran-pembelian.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            Pembayaran
                        </a>
                    </div>
                </div>

                <!-- Kas & Bank Section -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kas & Bank</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('transaksi-kas-bank.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('transaksi-kas-bank.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                            Mutasi Kas & Bank
                        </a>
                        <a href="{{ route('saldo-awal-bulanan.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('saldo-awal-bulanan.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            Saldo Awal Bulanan
                        </a>
                        <a href="{{ route('saldo-awal-produk.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('saldo-awal-produk.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Saldo Awal Produk
                        </a>
                    </div>
                </div>

                <!-- Laporan Section -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('laporan.penjualan.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.penjualan.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Laporan Penjualan
                        </a>

                        <a href="{{ route('laporan.pembelian.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.pembelian.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Laporan Pembelian
                        </a>


                        <a href="{{ route('laporan.pembayaran.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.pembayaran.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Laporan Pembayaran
                        </a>

                        <a href="{{ route('laporan.kas-bank.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.kas-bank.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                            Laporan Kas & Bank
                        </a>

                        <a href="{{ route('laporan.stok.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.stok.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Laporan Stok
                        </a>

                        <a href="{{ route('laporan.hutang.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.hutang.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Laporan Hutang
                        </a>

                        <a href="{{ route('laporan.piutang.index') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.piutang.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            Laporan Piutang
                        </a>

                    </div>
                </div>

                <!-- Pengaturan Section -->
                <div class="pt-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengaturan</h3>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('printer.settings') }}"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('printer.*') ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M18.25 9.456v5.294M21 7.5V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v1.5m18 0V9a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9v.75m18-2.25h-18M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            Pengaturan Printer
                        </a>
                    </div>
                </div>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-gray-200 flex-shrink-0">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-sm font-medium text-gray-700">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Sidebar overlay (only for mobile) -->
        <div x-show="sidebarOpen && window.innerWidth < 1024"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden" :class="sidebarOpen ? 'ml-64' : 'ml-0'"
            style="transition: margin-left 0.3s ease-in-out;">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-4">
                        <!-- Sidebar toggle button (mobile & desktop) -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="sidebar-toggle-btn p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition-all duration-200"
                            :class="sidebarOpen ? 'bg-primary-50 text-primary-600' : ''" title="Toggle Sidebar">
                            <svg class="h-6 w-6 icon-rotate" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" :class="sidebarOpen ? 'rotate-180' : ''">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Page Title -->
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-4">
                        <!-- Current Date Time -->
                        <div class="hidden sm:block text-sm text-gray-500" x-data="{
                            time: new Date().toLocaleString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })
                        }"
                            x-init="setInterval(() => {
                                time = new Date().toLocaleString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })
                            }, 1000)">
                            <span x-text="time"></span>
                        </div>

                        <!-- Notifications -->
                        <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-sm font-medium text-gray-700">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main
                class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 scroll-smooth">
                <div class="p-6 min-h-full">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Scroll to Top Button -->
        <button id="scrollToTop" onclick="scrollToTop()"
            class="fixed bottom-6 right-6 z-40 p-3 bg-primary-600 text-white rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 opacity-0 pointer-events-none transform translate-y-4"
            style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
        </button>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Tabler Icons -->
    <script src="https://unpkg.com/@tabler/icons@latest/icons-react/dist/index.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">

    <script>
        // Optimize scrolling performance
        document.addEventListener('DOMContentLoaded', function() {
            // Debounce scroll events for better performance
            let ticking = false;

            function updateScroll() {
                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateScroll);
                    ticking = true;
                }
            }

            // Optimize scroll performance
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.addEventListener('scroll', requestTick, {
                    passive: true
                });
            }

            // Prevent scroll chaining on mobile
            document.addEventListener('touchmove', function(e) {
                if (e.target.closest('.overflow-y-auto')) {
                    e.stopPropagation();
                }
            }, {
                passive: true
            });

            // Smooth scroll to top functionality
            window.scrollToTop = function() {
                const mainContent = document.querySelector('main');
                if (mainContent) {
                    mainContent.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            };

            // Show/hide scroll to top button

            const scrollToTopBtn = document.getElementById('scrollToTop');
            if (mainContent && scrollToTopBtn) {
                mainContent.addEventListener('scroll', function() {
                    if (this.scrollTop > 300) {
                        scrollToTopBtn.style.display = 'block';
                        setTimeout(() => {
                            scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none',
                                'translate-y-4');
                        }, 100);
                    } else {
                        scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                        setTimeout(() => {
                            scrollToTopBtn.style.display = 'none';
                        }, 300);

                    }
                });
            }
        });

        // Optimize Alpine.js performance
        document.addEventListener('alpine:init', () => {
            Alpine.data('scrollOptimizer', () => ({
                init() {
                    // Optimize transitions during scroll
                    this.$watch('$store.scrollY', (value) => {
                        if (value > 100) {
                            this.$el.classList.add('scrolled');
                        } else {
                            this.$el.classList.remove('scrolled');
                        }
                    });
                }
            }));
        });
    </script>

    @stack('scripts')
</body>

</html>
