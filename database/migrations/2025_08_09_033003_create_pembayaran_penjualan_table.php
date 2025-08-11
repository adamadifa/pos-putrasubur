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
        Schema::create('pembayaran_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjualan_id');
            $table->string('no_bukti', 50)->unique();
            $table->datetime('tanggal');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_pembayaran', 50);
            $table->enum('status_bayar', ['D', 'A', 'P'])->comment('D=DP, A=Angsuran, P=Pelunasan');
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('penjualan_id')->references('id')->on('penjualan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');

            // Indexes
            $table->index('penjualan_id');
            $table->index('tanggal');
            $table->index('metode_pembayaran');
            $table->index('status_bayar');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_penjualan');
    }
};
