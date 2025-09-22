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
            // Makanan
            [
                'kode_produk' => 'MIN001',
                'nama_produk' => 'Nasi Goreng Instan',
                'kategori_id' => 1, // Makanan
                'satuan_id' => 3, // Pcs
                'harga_jual' => 3500,
                'stok' => 150,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN002',
                'nama_produk' => 'Mie Instan Indomie',
                'kategori_id' => 1, // Makanan
                'satuan_id' => 3, // Pcs
                'harga_jual' => 2500,
                'stok' => 200,
                'stok_minimal' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN003',
                'nama_produk' => 'Roti Tawar',
                'kategori_id' => 1, // Makanan
                'satuan_id' => 4, // Bungkus
                'harga_jual' => 12000,
                'stok' => 25,
                'stok_minimal' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN004',
                'nama_produk' => 'Telur Ayam',
                'kategori_id' => 1, // Makanan
                'satuan_id' => 3, // Pcs
                'harga_jual' => 2000,
                'stok' => 100,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN005',
                'nama_produk' => 'Beras Premium',
                'kategori_id' => 1, // Makanan
                'satuan_id' => 1, // Kg
                'harga_jual' => 15000,
                'stok' => 50,
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Minuman
            [
                'kode_produk' => 'MIN006',
                'nama_produk' => 'Aqua Botol 600ml',
                'kategori_id' => 2, // Minuman
                'satuan_id' => 5, // Botol
                'harga_jual' => 3000,
                'stok' => 100,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN007',
                'nama_produk' => 'Coca Cola 330ml',
                'kategori_id' => 2, // Minuman
                'satuan_id' => 5, // Botol
                'harga_jual' => 5000,
                'stok' => 80,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN008',
                'nama_produk' => 'Teh Botol Sosro',
                'kategori_id' => 2, // Minuman
                'satuan_id' => 5, // Botol
                'harga_jual' => 4000,
                'stok' => 60,
                'stok_minimal' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN009',
                'nama_produk' => 'Susu Ultra 1L',
                'kategori_id' => 2, // Minuman
                'satuan_id' => 5, // Botol
                'harga_jual' => 12000,
                'stok' => 30,
                'stok_minimal' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN010',
                'nama_produk' => 'Kopi Kapal Api',
                'kategori_id' => 2, // Minuman
                'satuan_id' => 4, // Bungkus
                'harga_jual' => 8000,
                'stok' => 40,
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Snack
            [
                'kode_produk' => 'MIN011',
                'nama_produk' => 'Keripik Singkong',
                'kategori_id' => 3, // Snack
                'satuan_id' => 4, // Bungkus
                'harga_jual' => 6000,
                'stok' => 50,
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN012',
                'nama_produk' => 'Biskuit Oreo',
                'kategori_id' => 3, // Snack
                'satuan_id' => 4, // Bungkus
                'harga_jual' => 8000,
                'stok' => 35,
                'stok_minimal' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN013',
                'nama_produk' => 'Permen Mentos',
                'kategori_id' => 3, // Snack
                'satuan_id' => 3, // Pcs
                'harga_jual' => 2000,
                'stok' => 80,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN014',
                'nama_produk' => 'Coklat Silver Queen',
                'kategori_id' => 3, // Snack
                'satuan_id' => 3, // Pcs
                'harga_jual' => 3000,
                'stok' => 60,
                'stok_minimal' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN015',
                'nama_produk' => 'Kacang Garuda',
                'kategori_id' => 3, // Snack
                'satuan_id' => 4, // Bungkus
                'harga_jual' => 5000,
                'stok' => 45,
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Rokok
            [
                'kode_produk' => 'MIN016',
                'nama_produk' => 'Rokok Sampoerna Mild',
                'kategori_id' => 4, // Rokok
                'satuan_id' => 3, // Pcs
                'harga_jual' => 25000,
                'stok' => 20,
                'stok_minimal' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN017',
                'nama_produk' => 'Rokok Djarum Super',
                'kategori_id' => 4, // Rokok
                'satuan_id' => 3, // Pcs
                'harga_jual' => 22000,
                'stok' => 25,
                'stok_minimal' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kebutuhan Rumah Tangga
            [
                'kode_produk' => 'MIN018',
                'nama_produk' => 'Sabun Mandi Lifebuoy',
                'kategori_id' => 6, // Kebutuhan Rumah Tangga
                'satuan_id' => 3, // Pcs
                'harga_jual' => 4000,
                'stok' => 40,
                'stok_minimal' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN019',
                'nama_produk' => 'Shampoo Pantene',
                'kategori_id' => 6, // Kebutuhan Rumah Tangga
                'satuan_id' => 5, // Botol
                'harga_jual' => 15000,
                'stok' => 20,
                'stok_minimal' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'MIN020',
                'nama_produk' => 'Pasta Gigi Pepsodent',
                'kategori_id' => 6, // Kebutuhan Rumah Tangga
                'satuan_id' => 3, // Pcs
                'harga_jual' => 8000,
                'stok' => 30,
                'stok_minimal' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('produk')->insert($produks);
    }
}
