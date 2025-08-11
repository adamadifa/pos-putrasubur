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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur', 50)->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('pelanggan_id');
            $table->decimal('total', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->enum('status_pembayaran', ['lunas', 'dp', 'angsuran', 'belum_bayar'])->default('belum_bayar');
            $table->date('jatuh_tempo')->nullable();
            $table->unsignedBigInteger('kasir_id');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kasir_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');

            // Indexes
            $table->index('tanggal');
            $table->index('pelanggan_id');
            $table->index('kasir_id');
            $table->index('status_pembayaran');
            $table->index('jatuh_tempo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
