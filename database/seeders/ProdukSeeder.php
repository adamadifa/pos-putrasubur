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
            [
                'kode_produk' => 'KMD001',
                'nama_produk' => 'Cengkeh',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 145000,
                'stok' => 450,
                'stok_minimal' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD002',
                'nama_produk' => 'Kapol',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 105000,
                'stok' => 175,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD003',
                'nama_produk' => 'Kopi',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 95000,
                'stok' => 350,
                'stok_minimal' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD004',
                'nama_produk' => 'Coklat',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 45000,
                'stok' => 720,
                'stok_minimal' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD005',
                'nama_produk' => 'Rinu',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 85000,
                'stok' => 120,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD006',
                'nama_produk' => 'Pakang',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 65000,
                'stok' => 95,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD007',
                'nama_produk' => 'Jagung',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 12000,
                'stok' => 800,
                'stok_minimal' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD008',
                'nama_produk' => 'Lada Pt',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 135000,
                'stok' => 160,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD009',
                'nama_produk' => 'Lada Hitam',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 115000,
                'stok' => 280,
                'stok_minimal' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD010',
                'nama_produk' => 'Vaneli',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 3200000,
                'stok' => 20,
                'stok_minimal' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD011',
                'nama_produk' => 'Pala',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 220000,
                'stok' => 175,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD012',
                'nama_produk' => 'Minyak Pala',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 450000,
                'stok' => 45,
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD013',
                'nama_produk' => 'Minyak Clo',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 380000,
                'stok' => 60,
                'stok_minimal' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD014',
                'nama_produk' => 'Abu Cengkeh',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 25000,
                'stok' => 200,
                'stok_minimal' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD015',
                'nama_produk' => 'Jambe',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 18000,
                'stok' => 150,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD016',
                'nama_produk' => 'Daun Ck',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 35000,
                'stok' => 80,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD017',
                'nama_produk' => 'Buah Laja',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 75000,
                'stok' => 110,
                'stok_minimal' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD018',
                'nama_produk' => 'BM',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 95000,
                'stok' => 90,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD019',
                'nama_produk' => 'Sempra',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 125000,
                'stok' => 140,
                'stok_minimal' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD020',
                'nama_produk' => 'Kayu Manis',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 58000,
                'stok' => 8,
                'stok_minimal' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD021',
                'nama_produk' => 'Sempra Broken',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 95000,
                'stok' => 75,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'KMD022',
                'nama_produk' => 'Cengkeh Hutan',
                'kategori_id' => 1,
                'satuan_id' => 1, // Kg
                'harga_jual' => 120000,
                'stok' => 65,
                'stok_minimal' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('produk')->insert($produks);
    }
}
