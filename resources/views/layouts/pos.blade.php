<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $pengaturanUmum->nama_toko ?? config('app.name', 'Putra Subur') }}</title>

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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">




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

        /* Select2 Custom Styling - Tailwind Compatible */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0;
            background: white;
            transition: all 0.2s ease;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #3b82f6;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            line-height: 40px;
            padding-left: 12px;
            padding-right: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
            top: 1px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent;
            border-width: 5px 5px 0 5px;
            margin-left: -5px;
            margin-top: -2px;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6b7280 transparent;
            border-width: 0 5px 5px 5px;
            margin-top: -7px;
        }

        .select2-dropdown {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: white;
            z-index: 9999;
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 14px;
            color: #374151;
            transition: all 0.2s ease;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
            color: white;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #dbeafe;
            color: #1e40af;
            font-weight: 600;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected=true] {
            background-color: #3b82f6;
            color: white;
        }

        .select2-search--dropdown .select2-search__field {
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            margin: 8px;
            width: calc(100% - 16px);
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .select2-results__message {
            color: #6b7280;
            font-style: italic;
            padding: 8px 12px;
        }

        /* Loading spinner */
        .select2-container--default .select2-selection--single .select2-selection__loading {
            background-image: url("data:image/svg+xml,%3csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill='%236b7280' d='M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z'/%3e%3cpath fill='%236b7280' d='M10 2a8 8 0 0 1 8 8h-2a6 6 0 0 0-6-6V2z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
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

        // Set initial state based on screen size
        // Always default to closed on mobile, open on desktop
        this.sidebarOpen = window.innerWidth >= 1024;

        // Only use saved state if it's desktop and we have a saved state
        if (savedState !== null && window.innerWidth >= 1024) {
            this.sidebarOpen = savedState === 'true';
        }

        // Save sidebar state to localStorage whenever it changes (only on desktop)
        this.$watch('sidebarOpen', (value) => {
            if (window.innerWidth >= 1024) {
                localStorage.setItem('sidebarOpen', value.toString());
            }
        });

        // Handle window resize with debounce
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                // Auto-close sidebar on mobile if it was open
                if (window.innerWidth < 1024 && this.sidebarOpen) {
                    this.sidebarOpen = false;
                }
                // Auto-open sidebar on desktop if it was closed
                else if (window.innerWidth >= 1024 && !this.sidebarOpen) {
                    this.sidebarOpen = true;
                }
            }, 100);
        });
    }
}">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="sidebar-slide fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-600 via-blue-700 to-indigo-800 shadow-2xl transform transition-all duration-300 ease-in-out flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            style="backdrop-filter: blur(10px);">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-blue-500/30 flex-shrink-0">
                <div class="flex items-center gap-3">
                    @if ($pengaturanUmum->logo_url)
                        <div class="h-10 w-10 rounded-lg overflow-hidden border-2 border-gray-100 shadow-sm">
                            <img src="{{ $pengaturanUmum->logo_url }}" alt="{{ $pengaturanUmum->nama_toko }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div
                            class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white grid place-items-center text-sm font-bold shadow-lg">
                            {{ strtoupper(substr($pengaturanUmum->nama_toko, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-white leading-tight">{{ $pengaturanUmum->nama_toko }}</span>
                        @if ($pengaturanUmum->deskripsi)
                            <span
                                class="text-xs text-blue-100 leading-tight">{{ Str::limit($pengaturanUmum->deskripsi, 20) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto min-h-0">
                @php
                    $menus = \App\Helpers\MenuHelper::getMenuItems();
                @endphp

                <!-- Dashboard -->
                @if (isset($menus['dashboard']))
                    @php
                        $isDashboardActive =
                            request()->routeIs($menus['dashboard']['route']) || request()->routeIs('dashboard.*');
                    @endphp
                    <a href="{{ route($menus['dashboard']['route']) }}"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $isDashboardActive ? 'bg-white/20 text-white border-r-2 border-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="{{ $menus['dashboard']['icon'] }}" />
                        </svg>
                        {{ $menus['dashboard']['name'] }}
                    </a>
                @endif

                @foreach ($menus as $sectionKey => $section)
                    @if ($sectionKey !== 'dashboard' && isset($section['items']) && count($section['items']) > 0)
                        <div class="pt-4">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">
                                {{ $section['name'] }}</h3>
                            <div class="mt-2 space-y-1">
                                @foreach ($section['items'] as $itemKey => $item)
                                    @php
                                        // Extract base route name for better matching
                                        $baseRoute = str_replace('.index', '', $item['route']);
                                        $isActive =
                                            request()->routeIs($item['route']) ||
                                            request()->routeIs($baseRoute . '.*') ||
                                            request()->routeIs($baseRoute . '.create') ||
                                            request()->routeIs($baseRoute . '.edit') ||
                                            request()->routeIs($baseRoute . '.show');
                                    @endphp
                                    <a href="{{ route($item['route']) }}"
                                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $isActive ? 'bg-white/20 text-white border-r-2 border-white shadow-lg' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="{{ $item['icon'] }}" />
                                        </svg>
                                        {{ $item['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-blue-500/30 flex-shrink-0">
                <!-- Store Contact Info -->
                @if ($pengaturanUmum->no_telepon || $pengaturanUmum->email)
                    <div class="mb-4 p-3 bg-white/10 rounded-lg border border-white/20 backdrop-blur-sm">
                        <div class="space-y-2">
                            @if ($pengaturanUmum->no_telepon)
                                <div class="flex items-center gap-2 text-xs text-blue-100">
                                    <i class="ti ti-phone text-white"></i>
                                    <span>{{ $pengaturanUmum->no_telepon }}</span>
                                </div>
                            @endif
                            @if ($pengaturanUmum->email)
                                <div class="flex items-center gap-2 text-xs text-blue-100">
                                    <i class="ti ti-mail text-white"></i>
                                    <span>{{ $pengaturanUmum->email }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-sm font-medium text-white border border-white/30">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-blue-100 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg hover:bg-white/20 transition-colors border border-white/30 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-3">
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
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
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

    <!-- jQuery (required for Select2) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script> --}}

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
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
