<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function getMenuItems()
    {
        $user = Auth::user();
        $role = $user ? $user->role : 'kasir';

        $menus = [
            'dashboard' => [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
                'roles' => ['admin', 'kasir']
            ]
        ];

        // Master Data Section
        $masterDataItems = [
            'produk' => [
                'name' => 'Produk',
                'route' => 'produk.index',
                'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
                'roles' => ['admin', 'kasir']
            ],
            'kategori' => [
                'name' => 'Kategori',
                'route' => 'kategori.index',
                'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z',
                'roles' => ['admin', 'kasir']
            ],
            'satuan' => [
                'name' => 'Satuan',
                'route' => 'satuan.index',
                'icon' => 'M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z',
                'roles' => ['admin', 'kasir']
            ],
            'pelanggan' => [
                'name' => 'Pelanggan',
                'route' => 'pelanggan.index',
                'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                'roles' => ['admin', 'kasir']
            ],
            'supplier' => [
                'name' => 'Supplier',
                'route' => 'supplier.index',
                'icon' => 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8a3 3 0 100-6 3 3 0 000 6z',
                'roles' => ['admin', 'kasir']
            ],
            'metode-pembayaran' => [
                'name' => 'Metode Pembayaran',
                'route' => 'metode-pembayaran.index',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'roles' => ['admin', 'kasir']
            ],
            'kas-bank' => [
                'name' => 'Kas & Bank',
                'route' => 'kas-bank.index',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'roles' => ['admin', 'kasir']
            ],
            'users' => [
                'name' => 'User',
                'route' => 'users.index',
                'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
                'roles' => ['admin', 'manager']
            ]
        ];

        // Penjualan Section
        $penjualanItems = [
            'penjualan' => [
                'name' => 'Penjualan',
                'route' => 'penjualan.index',
                'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
                'roles' => ['admin', 'kasir']
            ],
            'pembayaran' => [
                'name' => 'Pembayaran',
                'route' => 'pembayaran.index',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'roles' => ['admin', 'kasir']
            ]
        ];

        // Pembelian Section
        $pembelianItems = [
            'pembelian' => [
                'name' => 'Pembelian',
                'route' => 'pembelian.index',
                'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
                'roles' => ['admin', 'kasir']
            ],
            'pembayaran-pembelian' => [
                'name' => 'Pembayaran',
                'route' => 'pembayaran-pembelian.index',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'roles' => ['admin', 'kasir']
            ]
        ];

        // Kas & Bank Section
        $kasBankItems = [
            'transaksi-kas-bank' => [
                'name' => 'Mutasi Kas & Bank',
                'route' => 'transaksi-kas-bank.index',
                'icon' => 'M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z',
                'roles' => ['admin', 'kasir']
            ]
        ];

        // Saldo Awal Section
        $saldoAwalItems = [
            'saldo-awal-bulanan' => [
                'name' => 'Saldo Awal Kas Bank',
                'route' => 'saldo-awal-bulanan.index',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'roles' => ['admin', 'kasir']
            ],
            'saldo-awal-produk' => [
                'name' => 'Saldo Awal Stok',
                'route' => 'saldo-awal-produk.index',
                'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
                'roles' => ['admin', 'kasir']
            ],
            'penyesuaian-stok' => [
                'name' => 'Penyesuaian Stok',
                'route' => 'penyesuaian-stok.index',
                'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'roles' => ['admin', 'kasir']
            ]
        ];

        // Laporan Section
        $laporanItems = [
            'laporan-penjualan' => [
                'name' => 'Laporan Penjualan',
                'route' => 'laporan.penjualan.index',
                'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
                'roles' => ['admin', 'manager', 'kasir']
            ],
            'laporan-pembelian' => [
                'name' => 'Laporan Pembelian',
                'route' => 'laporan.pembelian.index',
                'icon' => 'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
                'roles' => ['admin', 'manager', 'kasir']
            ],
            'laporan-pembayaran' => [
                'name' => 'Laporan Pembayaran',
                'route' => 'laporan.pembayaran.index',
                'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'roles' => ['admin', 'manager', 'kasir']
            ],
            'laporan-kas-bank' => [
                'name' => 'Laporan Kas & Bank',
                'route' => 'laporan.kas-bank.index',
                'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
                'roles' => ['admin', 'manager', 'kasir']
            ],
            'laporan-stok' => [
                'name' => 'Laporan Stok',
                'route' => 'laporan.stok.index',
                'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
                'roles' => ['admin', 'manager', 'kasir']
            ],
            'laporan-hutang' => [
                'name' => 'Laporan Hutang',
                'route' => 'laporan.hutang.index',
                'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'roles' => ['admin', 'manager', 'kasir']
            ],
            'laporan-piutang' => [
                'name' => 'Laporan Piutang',
                'route' => 'laporan.piutang.index',
                'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z',
                'roles' => ['admin', 'manager', 'kasir']
            ]
        ];

        // Pengaturan Section
        $pengaturanItems = [
            'pengaturan-umum' => [
                'name' => 'Pengaturan Umum',
                'route' => 'pengaturan-umum.index',
                'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'roles' => ['admin', 'manager']
            ],
            'printer-settings' => [
                'name' => 'Pengaturan Printer',
                'route' => 'printer.settings',
                'icon' => 'M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096L3 16.5l2.72-2.671m0 0a3.001 3.001 0 00-1.5-5.25c-1.66 0-3 1.34-3 3 0 .75.28 1.43.75 1.95m.72-.096L9 12.75l-2.28 2.25m0 0a3.001 3.001 0 001.5 5.25c1.66 0 3-1.34 3-3 0-.75-.28-1.43-.75-1.95m-2.25-2.25L12 9.75l2.25 2.25m0 0a3.001 3.001 0 001.5 5.25c1.66 0 3-1.34 3-3 0-.75-.28-1.43-.75-1.95m-2.25-2.25L15 12.75l-2.25 2.25',
                'roles' => ['admin', 'manager']
            ]
        ];

        // Filter menu items based on user role
        $filteredMenus = [
            'dashboard' => $menus['dashboard'],
            'master-data' => [
                'name' => 'Master Data',
                'items' => self::filterByRole($masterDataItems, $role)
            ],
            'penjualan' => [
                'name' => 'Penjualan',
                'items' => self::filterByRole($penjualanItems, $role)
            ],
            'pembelian' => [
                'name' => 'Pembelian',
                'items' => self::filterByRole($pembelianItems, $role)
            ],
            'kas-bank' => [
                'name' => 'Kas & Bank',
                'items' => self::filterByRole($kasBankItems, $role)
            ],
            'saldo-awal' => [
                'name' => 'Saldo Awal',
                'items' => self::filterByRole($saldoAwalItems, $role)
            ],
            'laporan' => [
                'name' => 'Laporan',
                'items' => self::filterByRole($laporanItems, $role)
            ],
            'pengaturan' => [
                'name' => 'Pengaturan',
                'items' => self::filterByRole($pengaturanItems, $role)
            ]
        ];

        return $filteredMenus;
    }

    private static function filterByRole($items, $role)
    {
        return array_filter($items, function ($item) use ($role) {
            return in_array($role, $item['roles']);
        });
    }

    public static function hasAccess($route, $role = null)
    {
        if (!$role) {
            $user = Auth::user();
            $role = $user ? $user->role : 'kasir';
        }

        $allMenus = self::getMenuItems();

        foreach ($allMenus as $section) {
            if (isset($section['items'])) {
                foreach ($section['items'] as $item) {
                    if ($item['route'] === $route) {
                        return in_array($role, $item['roles']);
                    }
                }
            }
        }

        return false;
    }
}
