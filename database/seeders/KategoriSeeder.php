<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Rempah-Rempah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Hasil Perkebunan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Biji-Bijian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Umbi-Umbian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Buah Kering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategori_produk')->insert($kategoris);
    }
}
