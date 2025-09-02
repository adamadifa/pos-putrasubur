@extends('layouts.pos')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Stok</h2>
                <p class="text-sm text-gray-600">Laporan saldo awal dan pergerakan stok produk per periode</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form id="filterForm">
                <!-- Periode Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Periode</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="periode_type" value="bulan" checked
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Per Bulan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="periode_type" value="tanggal"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300"
                                onchange="togglePeriodeType()">
                            <span class="ml-2 text-sm text-gray-700">Periode Tanggal</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
                    <!-- Filter Produk -->
                    <div class="flex-1">
                        <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                        <select id="produk_id" name="produk_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Produk</option>
                            @foreach ($produkList as $produk)
                                <option value="{{ $produk->id }}">
                                    {{ $produk->nama_produk }} ({{ $produk->kategori->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div id="filterBulan" class="flex-1 lg:flex lg:space-x-4">
                        <div class="flex-1">
                            <label for="periode_bulan_select"
                                class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select id="periode_bulan_select" name="periode_bulan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label for="periode_tahun_select"
                                class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select id="periode_tahun_select" name="periode_tahun"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200">
                                <option value="">Pilih Tahun</option>
                                @for ($i = 2020; $i <= now()->year + 1; $i++)
                                    <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Filter Tanggal -->
                    <div id="filterTanggal" class="flex-1 lg:flex lg:space-x-4" style="display: none;">
                        <div class="flex-1">
                            <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Dari</label>
                            <input type="text" id="tanggal_dari" name="tanggal_dari"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Pilih tanggal dari">
                        </div>
                        <div class="flex-1">
                            <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                Sampai</label>
                            <input type="text" id="tanggal_sampai" name="tanggal_sampai"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                placeholder="Pilih tanggal sampai">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button type="submit"
                            class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="ti ti-search mr-2"></i>
                            Generate Laporan
                        </button>
                        <button type="button" id="exportPdfBtn" disabled
                            class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="ti ti-file-export mr-2"></i>
                            Export PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="bg-white rounded-xl shadow-lg border border-gray-100 p-8" style="display: none;">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-100 rounded-full mb-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Memproses Laporan Stok</h3>
                <p class="text-gray-600">Mohon tunggu sebentar...</p>
            </div>
        </div>

        <!-- Laporan Results -->
        <div id="laporanResults" style="display: none;">
            <!-- Header dengan Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="laporanTitle">Laporan Stok</h3>
                        <p class="text-sm text-gray-600" id="laporanSubtitle">Periode laporan stok</p>
                    </div>
                    <div class="flex space-x-3 mt-4 sm:mt-0">
                        <button type="button" id="printBtn"
                            class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="ti ti-printer mr-2"></i>
                            Print
                        </button>
                        <button type="button" id="exportPdfBtn2"
                            class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="ti ti-file-export mr-2"></i>
                            Export PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-package text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-2xl font-bold" id="totalProduk">0</h4>
                            <p class="text-blue-100">Total Produk</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="ti ti-currency-dollar text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-2xl font-bold" id="totalNilaiStok">Rp 0</h4>
                            <p class="text-green-100">Total Nilai Stok</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="laporanTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Satuan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo Awal</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pembelian</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Penjualan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Saldo Akhir</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nilai Stok</th>
                            </tr>
                        </thead>
                        <tbody id="laporanTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
@endsection

@section('scripts')
    <script>
        // Toggle periode type function
        function togglePeriodeType() {
            const periodeType = $('input[name="periode_type"]:checked').val();

            if (periodeType === 'bulan') {
                $('#filterBulan').show();
                $('#filterTanggal').hide();
            } else {
                $('#filterBulan').hide();
                $('#filterTanggal').show();
            }
        }

        $(document).ready(function() {
            // Initialize Flatpickr for date inputs
            flatpickr("#tanggal_dari", {
                dateFormat: "Y-m-d",
                locale: "id"
            });

            flatpickr("#tanggal_sampai", {
                dateFormat: "Y-m-d",
                locale: "id"
            });

            // Form submission
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const periodeType = $('input[name="periode_type"]:checked').val();

                // Validation
                if (periodeType === 'bulan') {
                    if (!formData.get('periode_bulan') || !formData.get('periode_tahun')) {
                        showNotification('Pilih bulan dan tahun terlebih dahulu', 'error');
                        return;
                    }
                } else {
                    if (!formData.get('tanggal_dari') || !formData.get('tanggal_sampai')) {
                        showNotification('Pilih tanggal dari dan sampai terlebih dahulu', 'error');
                        return;
                    }
                }

                // Optional: Validasi produk (bisa kosong untuk semua produk)
                // if (!formData.get('produk_id')) {
                //     showNotification('Pilih produk terlebih dahulu', 'error');
                //     return;
                // }

                // Show loading state
                $('#loadingState').show();
                $('#laporanResults').hide();
                $('#exportPdfBtn').prop('disabled', true);

                // Make AJAX request
                $.ajax({
                    url: '{{ route('laporan.stok.generate') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Response received:', response); // Debug log
                        if (response.success) {
                            console.log('Data to display:', response.data); // Debug log
                            displayLaporan(response.data);
                            $('#laporanResults').show();
                            $('#exportPdfBtn').prop('disabled', false);
                            showNotification('Laporan stok berhasil di-generate', 'success');
                        } else {
                            showNotification('Gagal generate laporan: ' + response.message,
                                'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat generate laporan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#loadingState').hide();
                    }
                });
            });

            // Display laporan data
            function displayLaporan(data) {
                console.log('displayLaporan called with data:', data); // Debug log

                // Update title and summary
                $('#laporanTitle').text('Laporan Stok - ' + data.periode);
                $('#laporanSubtitle').text('Periode: ' + data.periode);
                $('#totalProduk').text(data.summary.total_produk.toLocaleString('id-ID'));
                $('#totalNilaiStok').text('Rp ' + data.summary.total_nilai_stok.toLocaleString('id-ID'));

                // Populate table
                let html = '';
                console.log('Number of products:', data.produk ? data.produk.length : 'undefined'); // Debug log

                if (data.produk && data.produk.length > 0) {
                    data.produk.forEach(function(item, index) {
                        html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center">
                                    ${item.foto ? 
                                        `<img src="${item.foto}" alt="${item.nama_produk}" class="h-10 w-10 rounded-lg object-cover">` :
                                        `<div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i class="ti ti-package text-gray-500"></i>
                                                </div>`
                                    }
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${item.nama_produk}</div>
                                <div class="text-sm text-gray-500">Harga: Rp ${item.harga_jual.toLocaleString('id-ID')}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.kategori}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.satuan}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${item.saldo_awal.toLocaleString('id-ID')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-right font-medium">+${item.pembelian.toLocaleString('id-ID')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right font-medium">-${item.penjualan.toLocaleString('id-ID')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">${item.saldo_akhir.toLocaleString('id-ID')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 text-right">Rp ${item.nilai_stok.toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                    });
                } else {
                    html =
                        '<tr><td colspan="10" class="px-6 py-4 text-center text-gray-500">Tidak ada data produk</td></tr>';
                }

                $('#laporanTableBody').html(html);
                console.log('Table HTML generated:', html.substring(0, 200) + '...'); // Debug log
            }

            // Export PDF functionality
            $('#exportPdfBtn, #exportPdfBtn2').on('click', function() {
                showNotification('Fitur export PDF sedang dalam pengembangan', 'info');
            });

            // Print functionality
            $('#printBtn').on('click', function() {
                window.print();
            });

            // Show notification function
            function showNotification(message, type = 'info') {
                const notificationContainer = $('#notificationContainer');
                let bgColor, icon;

                switch (type) {
                    case 'success':
                        bgColor = 'bg-success';
                        icon = '<i class="ti ti-check text-lg me-2"></i>';
                        break;
                    case 'error':
                        bgColor = 'bg-danger';
                        icon = '<i class="ti ti-x text-lg me-2"></i>';
                        break;
                    case 'warning':
                        bgColor = 'bg-warning';
                        icon = '<i class="ti ti-alert-triangle text-lg me-2"></i>';
                        break;
                    case 'info':
                    default:
                        bgColor = 'bg-info';
                        icon = '<i class="ti ti-info-circle text-lg me-2"></i>';
                        break;
                }

                const notification = $(`
            <div class="toast ${bgColor} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header ${bgColor} text-white border-0">
                    ${icon}
                    <strong class="me-auto">Notifikasi</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `);

                notificationContainer.append(notification);

                const toast = new bootstrap.Toast(notification[0]);
                toast.show();

                // Remove notification element after it's hidden
                notification.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }
        });
    </script>
@endsection
