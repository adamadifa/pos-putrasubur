@extends('layouts.pos')

@section('title', 'Detail User')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap user</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('users.edit', Crypt::encrypt($user->id)) }}"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="p-6">
                <div class="flex items-center mb-8">
                    <div class="h-20 w-20 rounded-full bg-primary-100 flex items-center justify-center mr-6">
                        <span class="text-2xl font-bold text-primary-600">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="mt-2">
                            <span
                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $user->role === 'admin'
                                ? 'bg-red-100 text-red-800'
                                : ($user->role === 'manager'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <p class="mt-1">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->role === 'admin'
                                        ? 'bg-red-100 text-red-800'
                                        : ($user->role === 'manager'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID User</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $user->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            @if ($user->email_verified_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Terverifikasi</label>
                                    <p class="mt-1 text-sm text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $user->email_verified_at->format('d/m/Y H:i:s') }}
                                    </p>
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status Email</label>
                                    <p class="mt-1 text-sm text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        Belum terverifikasi
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Role Permissions -->
                <div class="mt-6 p-6 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Hak Akses Role: {{ ucfirst($user->role) }}</h3>
                    <div class="text-sm text-blue-700">
                        @if ($user->role === 'admin')
                            <ul class="space-y-1">
                                <li>✓ Akses penuh ke semua fitur sistem</li>
                                <li>✓ Mengelola user dan pengaturan sistem</li>
                                <li>✓ Melihat semua laporan dan data</li>
                                <li>✓ Melakukan backup dan restore data</li>
                            </ul>
                        @elseif($user->role === 'manager')
                            <ul class="space-y-1">
                                <li>✓ Akses ke laporan dan analisis</li>
                                <li>✓ Mengelola data master (produk, pelanggan, supplier)</li>
                                <li>✓ Melihat dan mengelola transaksi</li>
                                <li>✓ Mengatur pengaturan toko</li>
                            </ul>
                        @else
                            <ul class="space-y-1">
                                <li>✓ Melakukan transaksi penjualan</li>
                                <li>✓ Mengelola pembayaran</li>
                                <li>✓ Melihat data produk dan pelanggan</li>
                                <li>✓ Print struk dan laporan harian</li>
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @if ($user->id !== auth()->id())
                    <div class="mt-8 flex items-center justify-end">
                        <form action="{{ route('users.destroy', Crypt::encrypt($user->id)) }}" method="POST"
                            class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Hapus User
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
