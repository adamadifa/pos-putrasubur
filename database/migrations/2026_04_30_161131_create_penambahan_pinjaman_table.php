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
        Schema::create('penambahan_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pinjaman_id');
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->unsignedBigInteger('kas_bank_id')->nullable();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('pinjaman_id')->references('id')->on('pinjaman')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('kas_bank_id')->references('id')->on('kas_bank')->onDelete('restrict');

            // Indexes
            $table->index('pinjaman_id');
            $table->index('tanggal');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penambahan_pinjaman');
    }
};
