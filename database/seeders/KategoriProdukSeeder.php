<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriProduk;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Makanan',
            'Minuman',
            'Snack',
            'Rokok',
            'Pulsa & E-Money',
            'Kebutuhan Rumah Tangga',
            'Kesehatan & Kecantikan',
            'Elektronik',
            'Pakaian',
            'Lainnya'
        ];

        foreach ($kategoris as $nama) {
            KategoriProduk::create([
                'nama' => $nama,
            ]);
        }
    }
}
