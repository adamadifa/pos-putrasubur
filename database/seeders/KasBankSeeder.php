<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KasBank;

class KasBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kasBank = [
            [
                'kode' => 'KAS001',
                'nama' => 'Kas Utama',
                'no_rekening' => null,
                'saldo_awal' => 1000000,
                'saldo_terkini' => 1000000,
            ],
            [
                'kode' => 'BANK001',
                'nama' => 'Bank BCA',
                'no_rekening' => '1234567890',
                'saldo_awal' => 5000000,
                'saldo_terkini' => 5000000,
            ],
            [
                'kode' => 'BANK002',
                'nama' => 'Bank Mandiri',
                'no_rekening' => '0987654321',
                'saldo_awal' => 3000000,
                'saldo_terkini' => 3000000,
            ],
        ];

        foreach ($kasBank as $kas) {
            KasBank::create($kas);
        }
    }
}
