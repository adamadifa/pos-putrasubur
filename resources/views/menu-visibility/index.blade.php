@extends('layouts.pos')

@section('title', 'Show/Hide Menu')
@section('page-title', 'Show/Hide Menu')

@section('content')
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="min-h-screen py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="ti ti-eye text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Show/Hide Menu</h1>
                        <p class="text-sm text-gray-500">Kelola menu yang ditampilkan di sidebar</p>
                    </div>
                </div>
            </div>

            <!-- Menu List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Menu</h3>
                    <p class="text-sm text-gray-600 mt-1">Aktifkan atau nonaktifkan menu yang ingin ditampilkan di sidebar</p>
                </div>

                <div class="p-6">
                    <div class="space-y-6">
                        @foreach ($menusBySection as $sectionName => $menus)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3 px-2">
                                    {{ $sectionName }}
                                </h3>
                                <div class="space-y-2">
                                    @foreach ($menus as $menuKey => $menu)
                                        @php
                                            $isHidden = isset($menuVisibilities[$menuKey]) && $menuVisibilities[$menuKey];
                                        @endphp
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                            <div class="flex items-center space-x-3 flex-1">
                                                <div class="p-2 bg-blue-100 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-semibold text-gray-900">{{ $menu['name'] }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $menu['route'] }}</p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                                <input type="checkbox" class="sr-only peer" 
                                                    data-menu-key="{{ $menuKey }}"
                                                    {{ !$isHidden ? 'checked' : '' }}
                                                    onchange="toggleMenu('{{ $menuKey }}', this.checked)">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <i class="ti ti-info-circle text-blue-600 mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Catatan:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-700">
                                    <li>Menu yang dinonaktifkan akan disembunyikan dari sidebar</li>
                                    <li>Anda masih dapat mengakses halaman melalui URL langsung</li>
                                    <li>Pengaturan ini hanya berlaku untuk akun Anda</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `px-4 py-3 rounded-lg shadow-lg text-white flex items-center space-x-2 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.innerHTML = `
                <i class="ti ti-${type === 'success' ? 'check' : 'x'}"></i>
                <span>${message}</span>
            `;
            
            const container = document.getElementById('toast-container');
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function toggleMenu(menuKey, isVisible) {
            fetch('{{ route("menu-visibility.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    menu_key: menuKey
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memperbarui menu', 'error');
            });
        }
    </script>
@endsection

