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
        // Kembalikan enum ke D, A, P saja (U sudah dihandle dengan field status_uang_muka)
        // Update data yang status_bayar = 'U' menjadi status_bayar sesuai logika pembayaran
        // Jika ada data dengan status_bayar = 'U', ubah menjadi 'D' (DP) karena ini adalah pembayaran pertama dengan uang muka
        DB::statement("UPDATE pembayaran_penjualan SET status_bayar = 'D' WHERE status_bayar = 'U'");
        
        // Modify enum to remove U (Uang Muka) - sekarang menggunakan field status_uang_muka
        DB::statement("ALTER TABLE pembayaran_penjualan MODIFY COLUMN status_bayar ENUM('D', 'A', 'P') COMMENT 'D=DP, A=Angsuran, P=Pelunasan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to include U (Uang Muka) - untuk rollback
        DB::statement("ALTER TABLE pembayaran_penjualan MODIFY COLUMN status_bayar ENUM('D', 'A', 'P', 'U') COMMENT 'D=DP, A=Angsuran, P=Pelunasan, U=Uang Muka'");
    }
};
