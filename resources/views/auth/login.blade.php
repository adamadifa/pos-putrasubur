<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pengaturanUmum->nama_toko ?? 'POS System' }} - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#2563eb',
                            dark: '#1e40af'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">
    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- Left panel - Login Form -->
        <div class="flex items-center justify-center p-6 lg:p-12 bg-white">
            <div class="w-full max-w-sm">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-12">
                    @if ($pengaturanUmum && $pengaturanUmum->logo)
                        <div class="h-16 w-16 rounded-xl overflow-hidden shadow-lg">
                            <img src="{{ $pengaturanUmum->logo_url }}" alt="Logo"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-16 w-16 rounded-xl bg-blue-600 text-white grid place-items-center shadow-lg">
                            <i class="ti ti-building-store text-2xl"></i>
                        </div>
                    @endif
                    <span class="text-xl font-bold">{{ $pengaturanUmum->nama_toko ?? 'POS System' }}</span>
                </div>

                <!-- Title -->
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-2">Log in to your account.</h1>
                    <p class="text-sm text-gray-500">Enter your email address and password to log in.</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-6.9 4.14a2.25 2.25 0 0 1-2.31 0l-6.9-4.14a2.25 2.25 0 0 1-1.07-1.916V6.75Z" />
                                </svg>
                            </span>
                            <input id="email" name="email" type="email" autocomplete="username"
                                value="{{ old('email') }}" required autofocus
                                class="block w-full rounded-lg border border-gray-300 pl-10 pr-3 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                placeholder="Email Address" />
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-.75 11.25h10.5A2.25 2.25 0 0 0 19.5 19.5v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 12.75V19.5a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </span>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full rounded-lg border border-gray-300 pl-10 pr-10 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                placeholder="Password" />
                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Forgot Password -->
                    <div class="text-right">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-600 hover:text-blue-500">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white py-3 font-medium transition-colors">
                        Login
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white px-2 text-gray-500">Or</span>
                        </div>
                    </div>

                    <!-- Social Login -->
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 py-3 text-sm font-medium hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Google
                        </button>
                        <button type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 py-3 text-sm font-medium hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5" fill="#1877F2" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                            Facebook
                        </button>
                    </div>
                </form>

                <!-- Sign Up Link -->
                <p class="mt-8 text-center text-sm text-gray-600">
                    Don't you have an account?
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">Sign
                            Up</a>
                    @endif
                </p>
            </div>
        </div>

        <!-- Right panel - Background Image with Overlay -->
        <div class="hidden lg:flex items-center justify-center p-12 relative overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                @if ($pengaturanUmum && $pengaturanUmum->foto_toko) style="background-image: url('{{ $pengaturanUmum->foto_toko_url }}');"
                @else
                    style="background-image: url('https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');" @endif>
            </div>

            <!-- Blue Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/75 to-blue-800/75"></div>

            <!-- Content -->
            <div class="relative z-10 text-center text-white max-w-lg">
                <!-- Logo and Company Name -->
                <div class="flex flex-col items-center justify-center mb-8">
                    @if ($pengaturanUmum && $pengaturanUmum->logo)
                        <div class="h-32 w-32 rounded-3xl overflow-hidden shadow-2xl mb-6">
                            <img src="{{ $pengaturanUmum->logo_url }}" alt="Logo"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div
                            class="h-32 w-32 rounded-3xl bg-white/20 backdrop-blur-sm grid place-items-center shadow-2xl mb-6">
                            <i class="ti ti-building-store text-5xl"></i>
                        </div>
                    @endif
                    <span
                        class="text-4xl font-bold text-center">{{ $pengaturanUmum->nama_toko ?? 'POS System' }}</span>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
