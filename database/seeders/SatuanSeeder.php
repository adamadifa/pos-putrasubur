<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuans = [
            [
                'nama' => 'Kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bungkus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Botol',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kaleng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Liter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ml',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('satuan')->insert($satuans);
    }
}
