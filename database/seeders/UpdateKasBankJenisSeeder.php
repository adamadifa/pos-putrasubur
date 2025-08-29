<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateKasBankJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing kas/bank to set jenis based on nama
        \App\Models\KasBank::where('nama', 'like', '%kas%')
            ->orWhere('nama', 'like', '%cash%')
            ->orWhere('nama', 'like', '%tunai%')
            ->update(['jenis' => 'KAS']);

        \App\Models\KasBank::where('nama', 'like', '%bank%')
            ->orWhere('nama', 'like', '%bca%')
            ->orWhere('nama', 'like', '%mandiri%')
            ->orWhere('nama', 'like', '%bni%')
            ->orWhere('nama', 'like', '%bri%')
            ->update(['jenis' => 'BANK']);

        // Set default KAS for any remaining records
        \App\Models\KasBank::whereNull('jenis')->update(['jenis' => 'KAS']);

        echo "Updated kas/bank jenis field successfully!\n";
    }
}
