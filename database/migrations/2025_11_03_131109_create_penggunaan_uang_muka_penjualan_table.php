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
        Schema::create('penggunaan_uang_muka_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uang_muka_pelanggan_id');
            $table->unsignedBigInteger('penjualan_id');
            $table->decimal('jumlah_digunakan', 15, 2)->comment('Jumlah uang muka yang digunakan untuk faktur ini');
            $table->date('tanggal_penggunaan');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('uang_muka_pelanggan_id')->references('id')->on('uang_muka_pelanggan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('penjualan_id')->references('id')->on('penjualan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');

            // Indexes
            $table->index('uang_muka_pelanggan_id');
            $table->index('penjualan_id');
            $table->index('tanggal_penggunaan');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_uang_muka_penjualan');
    }
};
