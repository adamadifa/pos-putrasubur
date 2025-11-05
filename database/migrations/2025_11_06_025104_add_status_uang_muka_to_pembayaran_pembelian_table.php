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
        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->tinyInteger('status_uang_muka')->default(0)->comment('1=Penggunaan uang muka, 0=Tidak menggunakan uang muka')->after('status_bayar');
        });

        // Update data yang sudah ada dengan status_bayar = 'U' menjadi status_uang_muka = 1
        // dan status_bayar = 'D' (DP) karena ini adalah pembayaran pertama dengan uang muka
        DB::statement("UPDATE pembayaran_pembelian SET status_uang_muka = 1, status_bayar = 'D' WHERE status_bayar = 'U'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->dropColumn('status_uang_muka');
        });
    }
};
