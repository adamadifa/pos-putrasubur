<?php

namespace Database\Seeders;

use App\Models\PengaturanUmum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengaturanUmum::create([
            'nama_toko' => 'Toko Saya',
            'alamat' => 'Jl. Contoh Alamat No. 123, Kota Contoh',
            'no_telepon' => '081234567890',
            'email' => 'info@tokosaya.com',
            'deskripsi' => 'Toko yang menyediakan berbagai kebutuhan sehari-hari dengan kualitas terbaik.',
            'is_active' => true
        ]);
    }
}