<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to include PPN (Pembayaran Pinjaman)
        DB::statement("ALTER TABLE transaksi_kas_bank MODIFY COLUMN referensi_tipe ENUM('PPJ', 'PPB', 'MN', 'UMS', 'UMP', 'PPN') COMMENT 'PPJ=PembayaranPenjualan, PPB=PembayaranPembelian, MN=Manual, UMS=UangMukaSupplier, UMP=UangMukaPelanggan, PPN=PembayaranPinjaman'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum (without PPN)
        DB::statement("ALTER TABLE transaksi_kas_bank MODIFY COLUMN referensi_tipe ENUM('PPJ', 'PPB', 'MN', 'UMS', 'UMP') COMMENT 'PPJ=PembayaranPenjualan, PPB=PembayaranPembelian, MN=Manual, UMS=UangMukaSupplier, UMP=UangMukaPelanggan'");
    }
};
