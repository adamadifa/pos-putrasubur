<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'kode_supplier' => 'SUP001',
                'nama' => 'PT Sukses Makmur',
                'alamat' => 'Jl. Industri No. 123, Jakarta',
                'telepon' => '021-5550123',
                'email' => 'info@suksesmakmur.com',
                'keterangan' => 'Supplier utama untuk produk elektronik',
                'status' => true,
            ],
            [
                'kode_supplier' => 'SUP002',
                'nama' => 'CV Maju Jaya',
                'alamat' => 'Jl. Raya Utama No. 456, Bandung',
                'telepon' => '022-5550456',
                'email' => 'contact@majujaya.co.id',
                'keterangan' => 'Supplier untuk produk makanan',
                'status' => true,
            ],
            [
                'kode_supplier' => 'SUP003',
                'nama' => 'UD Berkah Abadi',
                'alamat' => 'Jl. Pasar Baru No. 789, Surabaya',
                'telepon' => '031-5550789',
                'email' => 'berkah@abadi.com',
                'keterangan' => 'Supplier untuk produk tekstil',
                'status' => true,
            ],
            [
                'kode_supplier' => 'SUP004',
                'nama' => 'PT Mitra Sejati',
                'alamat' => 'Jl. Komersial No. 321, Medan',
                'telepon' => '061-5550321',
                'email' => 'mitra@sejati.com',
                'keterangan' => 'Supplier untuk produk kosmetik',
                'status' => true,
            ],
            [
                'kode_supplier' => 'SUP005',
                'nama' => 'CV Sumber Rejeki',
                'alamat' => 'Jl. Bisnis No. 654, Semarang',
                'telepon' => '024-5550654',
                'email' => 'sumber@rejeki.co.id',
                'keterangan' => 'Supplier untuk produk pertanian',
                'status' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
