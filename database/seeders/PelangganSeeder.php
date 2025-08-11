<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelangganData = [
            [
                'kode_pelanggan' => 'P-0001',
                'nama' => 'Ahmad Rizki',
                'nomor_telepon' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0002',
                'nama' => 'Siti Nurhaliza',
                'nomor_telepon' => '081234567891',
                'alamat' => 'Jl. Thamrin No. 45, Jakarta',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0003',
                'nama' => 'Budi Santoso',
                'nomor_telepon' => '081234567892',
                'alamat' => 'Jl. Gatot Subroto No. 67, Jakarta',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0004',
                'nama' => 'Dewi Sartika',
                'nomor_telepon' => '081234567893',
                'alamat' => 'Jl. Asia Afrika No. 89, Bandung',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0005',
                'nama' => 'Rudi Hermawan',
                'nomor_telepon' => '081234567894',
                'alamat' => 'Jl. Malioboro No. 12, Yogyakarta',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0006',
                'nama' => 'Nina Kartika',
                'nomor_telepon' => '081234567895',
                'alamat' => 'Jl. Diponegoro No. 34, Semarang',
                'status' => false, // 0 = nonaktif
            ],
            [
                'kode_pelanggan' => 'P-0007',
                'nama' => 'Eko Prasetyo',
                'nomor_telepon' => '081234567896',
                'alamat' => 'Jl. Ahmad Yani No. 56, Surabaya',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0008',
                'nama' => 'Maya Indah',
                'nomor_telepon' => '081234567897',
                'alamat' => 'Jl. Hayam Wuruk No. 78, Jakarta',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0009',
                'nama' => 'Agus Setiawan',
                'nomor_telepon' => '081234567898',
                'alamat' => 'Jl. Pasar Baru No. 90, Jakarta',
                'status' => true, // 1 = aktif
            ],
            [
                'kode_pelanggan' => 'P-0010',
                'nama' => 'Lina Marlina',
                'nomor_telepon' => '081234567899',
                'alamat' => 'Jl. Cikini No. 23, Jakarta',
                'status' => true, // 1 = aktif
            ],
        ];

        foreach ($pelangganData as $data) {
            Pelanggan::create($data);
        }
    }
}
