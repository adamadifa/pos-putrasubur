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
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->foreign('transaksi_kas_bank_id')->references('id')->on('transaksi_kas_bank')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->foreign('transaksi_kas_bank_id')->references('id')->on('transaksi_kas_bank')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->dropForeign(['transaksi_kas_bank_id']);
        });

        Schema::table('pembayaran_pembelian', function (Blueprint $table) {
            $table->dropForeign(['transaksi_kas_bank_id']);
        });
    }
};
