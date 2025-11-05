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
        // Kembalikan enum ke P, D, A, B saja (U sudah dihandle dengan field status_uang_muka)
        // Update data yang status_bayar = 'U' menjadi status_bayar sesuai logika pembayaran
        // Jika ada data dengan status_bayar = 'U', ubah menjadi 'D' (DP) karena ini adalah pembayaran pertama dengan uang muka
        DB::statement("UPDATE pembayaran_pembelian SET status_bayar = 'D' WHERE status_bayar = 'U'");
        
        // Modify enum to remove U (Uang Muka) - sekarang menggunakan field status_uang_muka
        DB::statement("ALTER TABLE pembayaran_pembelian MODIFY COLUMN status_bayar ENUM('P', 'D', 'A', 'B') COMMENT 'P=Pelunasan, D=DP, A=Angsuran, B=Bayar Sebagian'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to include U (Uang Muka) - untuk rollback
        DB::statement("ALTER TABLE pembayaran_pembelian MODIFY COLUMN status_bayar ENUM('P', 'D', 'A', 'B', 'U') COMMENT 'P=Pelunasan, D=DP, A=Angsuran, B=Bayar Sebagian, U=Uang Muka'");
    }
};

