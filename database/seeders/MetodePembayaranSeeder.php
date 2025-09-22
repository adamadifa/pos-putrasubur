<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodePembayaran = [
            [
                'kode' => 'TUNAI',
                'nama' => 'Tunai',
                'deskripsi' => 'Pembayaran dengan uang tunai',
                'icon' => 'ti-cash',
                'status' => true,
                'urutan' => 1,
            ],
            [
                'kode' => 'TRANSFER',
                'nama' => 'Transfer Bank',
                'deskripsi' => 'Pembayaran melalui transfer bank',
                'icon' => 'ti-credit-card',
                'status' => true,
                'urutan' => 2,
            ],
            [
                'kode' => 'QRIS',
                'nama' => 'QRIS',
                'deskripsi' => 'Pembayaran menggunakan QRIS',
                'icon' => 'ti-device-mobile',
                'status' => true,
                'urutan' => 3,
            ],
            [
                'kode' => 'KARTU',
                'nama' => 'Kartu Debit/Credit',
                'deskripsi' => 'Pembayaran menggunakan kartu debit atau kredit',
                'icon' => 'ti-credit-card',
                'status' => true,
                'urutan' => 4,
            ],
            [
                'kode' => 'EWALLET',
                'nama' => 'E-Wallet',
                'deskripsi' => 'Pembayaran menggunakan e-wallet',
                'icon' => 'ti-device-mobile',
                'status' => true,
                'urutan' => 5,
            ],
        ];

        foreach ($metodePembayaran as $metode) {
            MetodePembayaran::create($metode);
        }
    }
}
