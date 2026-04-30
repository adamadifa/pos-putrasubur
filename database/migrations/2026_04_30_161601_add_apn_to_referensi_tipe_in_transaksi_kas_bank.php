<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to include APN (Addition Pinjaman)
        DB::statement("ALTER TABLE transaksi_kas_bank MODIFY COLUMN referensi_tipe ENUM('PPJ', 'PPB', 'MN', 'UMS', 'UMP', 'PPN', 'APN') COMMENT 'PPJ=PembayaranPenjualan, PPB=PembayaranPembelian, MN=Manual, UMS=UangMukaSupplier, UMP=UangMukaPelanggan, PPN=PembayaranPinjaman, APN=PenambahanPinjaman'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum (without APN)
        DB::statement("ALTER TABLE transaksi_kas_bank MODIFY COLUMN referensi_tipe ENUM('PPJ', 'PPB', 'MN', 'UMS', 'UMP', 'PPN') COMMENT 'PPJ=PembayaranPenjualan, PPB=PembayaranPembelian, MN=Manual, UMS=UangMukaSupplier, UMP=UangMukaPelanggan, PPN=PembayaranPinjaman'");
    }
};
