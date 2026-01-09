@extends('layouts.pos')

@section('title', 'Detail Produk')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-6">
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
                    <h1 class="text-xl font-bold text-gray-800">Detail Produk</h1>
                    <p class="text-xs text-gray-500">Informasi lengkap produk</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                 <button onclick="confirmDelete({{ $produk->id }}, '{{ $produk->nama_produk }}')" 
                        class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition-colors focus:ring-2 focus:ring-red-200">
                    Hapus
                </button>
                <a href="{{ route('produk.edit', $produk->id) }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-sm hover:shadow focus:ring-2 focus:ring-blue-500">
                    Edit Produk
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Image & Identity -->
            <div class="md:col-span-1 space-y-6">
                <!-- Image Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 border-t-4 border-t-blue-500">
                    <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden border border-gray-100 flex items-center justify-center relative">
                        @if ($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" 
                                 alt="{{ $produk->nama_produk }}" 
                                 class="w-full h-full object-cover relative z-10"
                                 onerror="this.style.display='none'; document.getElementById('fallback-icon').classList.remove('hidden');">
                        @endif
                        
                        <div id="fallback-icon" class="text-center text-gray-300 p-6 absolute inset-0 flex flex-col items-center justify-center {{ $produk->foto ? 'hidden' : '' }} z-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mb-2 opacity-50">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span class="text-xs font-medium">Tidak ada foto</span>
                        </div>
                    </div>
                </div>

                <!-- Status & Meta -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-t-4 border-t-purple-500">
                     <h3 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Status Produk</h3>
                     <div class="space-y-4">
                        <div class="flex justify-between items-center">
                             <span class="text-xs text-gray-500">Status Stok</span>
                             @php
                                $statusClass = $produk->status_stok === 'tersedia' ? 'bg-green-100 text-green-700' : ($produk->status_stok === 'menipis' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                             @endphp
                             <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                 {{ ucfirst($produk->status_stok) }}
                             </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Terakhir Update</span>
                            <span class="text-xs font-medium text-gray-700">{{ $produk->updated_at->format('d M Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Dibuat Pada</span>
                            <span class="text-xs font-medium text-gray-700">{{ $produk->created_at->format('d M Y') }}</span>
                        </div>
                     </div>
                </div>
            </div>

            <!-- Right Column: Details & Stats -->
            <div class="md:col-span-2 space-y-6">
                 <!-- Main Info -->
                 <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-t-4 border-t-indigo-500 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50/50 to-transparent rounded-bl-full -mr-4 -mt-4 pointer-events-none"></div>
                    
                    <div class="flex items-start justify-between relative z-10">
                        <div>
                            <span class="inline-block px-2 py-1 mb-2 text-[10px] font-semibold text-indigo-600 bg-indigo-50 rounded border border-indigo-100">
                                {{ $produk->kode_produk }}
                            </span>
                            <h2 class="text-2xl font-bold text-gray-900 leading-tight mb-2">{{ $produk->nama_produk }}</h2>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $produk->kategori->nama }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                    {{ $produk->satuan->nama }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($produk->deskripsi)
                        <div class="mt-4 pt-4 border-t border-gray-50">
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $produk->deskripsi }}</p>
                        </div>
                    @endif
                 </div>

                 <!-- Financials Grid -->
                 <div class="grid grid-cols-2 gap-4">
                     <!-- Harga Beli -->
                     <div class="bg-gradient-to-br from-white to-orange-50/50 rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-orange-400 hover:shadow-md transition-shadow">
                         <div class="flex items-center gap-3 mb-2">
                             <div class="p-2 bg-white rounded-lg text-orange-600 shadow-sm border border-orange-100">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                  </svg>
                             </div>
                             <span class="text-xs font-bold uppercase tracking-wider text-orange-700/70">Harga Beli</span>
                         </div>
                         <p class="text-xl font-bold text-gray-900">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</p>
                     </div>

                     <!-- Harga Jual -->
                     <div class="bg-gradient-to-br from-white to-green-50/50 rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-emerald-500 hover:shadow-md transition-shadow">
                         <div class="flex items-center gap-3 mb-2">
                             <div class="p-2 bg-white rounded-lg text-emerald-600 shadow-sm border border-green-100">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                  </svg>
                             </div>
                             <span class="text-xs font-bold uppercase tracking-wider text-green-700/70">Harga Jual</span>
                         </div>
                         <p class="text-xl font-bold text-gray-900">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
                     </div>
                 </div>

                 <!-- Stock Grid -->
                 <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                     <!-- Current Stock -->
                     <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 relative overflow-hidden group hover:border-blue-300 transition-colors">
                         <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                             <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                         </div>
                         <span class="text-xs font-medium text-gray-500 block mb-1 uppercase tracking-wide">Stok Saat Ini</span>
                         <p class="text-2xl font-bold text-gray-900 relative z-10">{{ number_format($produk->stok, 0, ',', '.') }}</p>
                         <span class="text-[10px] text-gray-400 font-medium">{{ $produk->satuan->nama }}</span>
                     </div>
                     
                     <!-- Minimal Stock -->
                     <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 relative overflow-hidden group hover:border-yellow-300 transition-colors">
                         <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                             <svg class="w-12 h-12 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                         </div>
                         <span class="text-xs font-medium text-gray-500 block mb-1 uppercase tracking-wide">Stok Minimal</span>
                         <p class="text-2xl font-bold text-gray-900 relative z-10">{{ number_format($produk->stok_minimal, 0, ',', '.') }}</p>
                     </div>

                     <!-- Placeholder Stats -->
                     <div class="col-span-2 md:col-span-1 bg-gray-50 rounded-xl border border-gray-200 p-5 flex flex-col justify-center">
                         <span class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Total Terjual</span>
                         <p class="text-lg font-bold text-gray-600">0</p>
                         <span class="text-[10px] text-gray-400">Unit</span>
                     </div>
                 </div>

                 <!-- Recent Activity Placeholder -->
                 <div class="bg-gray-50 rounded-xl border border-gray-200 border-dashed p-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada riwayat transaksi untuk produk ini.</p>
                 </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Confirm delete function with SweetAlert
        function confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: `Anda akan menghapus "${productName}". Data yang dihapus tidak dapat dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg',
                    cancelButton: 'px-4 py-2 bg-gray-500 text-white rounded-lg ml-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('produk.destroy', '') }}/${productId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
