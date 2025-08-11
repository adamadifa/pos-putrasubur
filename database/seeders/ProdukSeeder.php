<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = [
            // Rempah-Rempah (Kategori ID: 1)
            [
                'kode_produk' => 'RMP001',
                'nama_produk' => 'Cengkeh Kering Grade A',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 145000,
                'stok' => 450,
                'stok_minimal' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'RMP002',
                'nama_produk' => 'Pala Utuh Premium',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 220000,
                'stok' => 175,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'RMP003',
                'nama_produk' => 'Kapol (Bunga Pala)',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 105000,
                'stok' => 120,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'RMP004',
                'nama_produk' => 'Lada Hitam Lampung',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 115000,
                'stok' => 280,
                'stok_minimal' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'RMP005',
                'nama_produk' => 'Lada Putih Bangka',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 135000,
                'stok' => 160,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'RMP006',
                'nama_produk' => 'Kayu Manis Cassia',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 58000,
                'stok' => 8, // Stok menipis
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Hasil Perkebunan (Kategori ID: 2)
            [
                'kode_produk' => 'PKB001',
                'nama_produk' => 'Kopi Arabika Gayo',
                'kategori_id' => 2,
                'satuan_id' => 1, // Kg
                'harga_jual' => 95000,
                'stok' => 350,
                'stok_minimal' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'PKB002',
                'nama_produk' => 'Kopi Robusta Lampung',
                'kategori_id' => 2,
                'satuan_id' => 1, // Kg
                'harga_jual' => 60000,
                'stok' => 520,
                'stok_minimal' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'PKB003',
                'nama_produk' => 'Kakao Fermentasi',
                'kategori_id' => 2,
                'satuan_id' => 1, // Kg
                'harga_jual' => 45000,
                'stok' => 720,
                'stok_minimal' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'PKB004',
                'nama_produk' => 'Vanili Planifolia',
                'kategori_id' => 2,
                'satuan_id' => 1, // Kg
                'harga_jual' => 3200000,
                'stok' => 20,
                'stok_minimal' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'PKB005',
                'nama_produk' => 'Kopi Luwak Premium',
                'kategori_id' => 2,
                'satuan_id' => 1, // Kg
                'harga_jual' => 1200000,
                'stok' => 0, // Stok habis
                'stok_minimal' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Biji-Bijian (Kategori ID: 3)
            [
                'kode_produk' => 'BJI001',
                'nama_produk' => 'Kemiri Kupas',
                'kategori_id' => 3,
                'satuan_id' => 1, // Kg
                'harga_jual' => 32000,
                'stok' => 210,
                'stok_minimal' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'BJI002',
                'nama_produk' => 'Kacang Tanah Kupas',
                'kategori_id' => 3,
                'satuan_id' => 1, // Kg
                'harga_jual' => 24000,
                'stok' => 420,
                'stok_minimal' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Umbi-Umbian (Kategori ID: 4)
            [
                'kode_produk' => 'UMB001',
                'nama_produk' => 'Jahe Gajah Kering',
                'kategori_id' => 4,
                'satuan_id' => 1, // Kg
                'harga_jual' => 35000,
                'stok' => 180,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'UMB002',
                'nama_produk' => 'Kunyit Bubuk',
                'kategori_id' => 4,
                'satuan_id' => 1, // Kg
                'harga_jual' => 28000,
                'stok' => 125,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'UMB003',
                'nama_produk' => 'Lengkuas Kering',
                'kategori_id' => 4,
                'satuan_id' => 1, // Kg
                'harga_jual' => 20000,
                'stok' => 85,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Buah Kering (Kategori ID: 5)
            [
                'kode_produk' => 'BKR001',
                'nama_produk' => 'Kelapa Parut Kering',
                'kategori_id' => 5,
                'satuan_id' => 1, // Kg
                'harga_jual' => 16000,
                'stok' => 250,
                'stok_minimal' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'BKR002',
                'nama_produk' => 'Asam Jawa Kering',
                'kategori_id' => 5,
                'satuan_id' => 1, // Kg
                'harga_jual' => 12000,
                'stok' => 150,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('produk')->insert($produks);
    }
}
