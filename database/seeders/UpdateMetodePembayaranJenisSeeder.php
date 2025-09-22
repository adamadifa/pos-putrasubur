<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateMetodePembayaranJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing metode pembayaran to set jenis based on kode
        \App\Models\MetodePembayaran::where('kode', 'like', '%tunai%')
            ->orWhere('kode', 'like', '%cash%')
            ->orWhere('kode', 'like', '%kas%')
            ->update(['jenis' => 'KAS']);

        \App\Models\MetodePembayaran::where('kode', 'like', '%transfer%')
            ->orWhere('kode', 'like', '%bank%')
            ->orWhere('kode', 'like', '%qris%')
            ->orWhere('kode', 'like', '%edc%')
            ->orWhere('kode', 'like', '%kartu%')
            ->orWhere('kode', 'like', '%card%')
            ->update(['jenis' => 'BANK']);

        // Set default KAS for any remaining records
        \App\Models\MetodePembayaran::whereNull('jenis')->update(['jenis' => 'KAS']);

        echo "Updated metode pembayaran jenis field successfully!\n";
    }
}
