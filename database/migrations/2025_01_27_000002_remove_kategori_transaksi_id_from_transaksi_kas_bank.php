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
        Schema::table('transaksi_kas_bank', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['kategori_transaksi_id']);

            // Hapus index jika ada
            $table->dropIndex(['kategori_transaksi_id']);

            // Hapus kolom kategori_transaksi_id
            $table->dropColumn('kategori_transaksi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_kas_bank', function (Blueprint $table) {
            // Tambahkan kembali kolom kategori_transaksi_id
            $table->unsignedBigInteger('kategori_transaksi_id')->nullable()->after('kategori_transaksi');

            // Tambahkan foreign key constraint
            $table->foreign('kategori_transaksi_id')->references('id')->on('kategori_transaksi')->onUpdate('cascade')->onDelete('restrict');

            // Tambahkan index
            $table->index('kategori_transaksi_id');
        });
    }
};




