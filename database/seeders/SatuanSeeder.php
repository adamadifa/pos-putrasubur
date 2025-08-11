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
                'nama' => 'Ton',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kwintal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Karung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('satuan')->insert($satuans);
    }
}
