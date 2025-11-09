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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('no_pinjaman', 50)->unique();
            $table->unsignedBigInteger('pelanggan_id');
            $table->date('tanggal');
            $table->decimal('jumlah_pinjaman', 15, 2)->default(0);
            $table->decimal('bunga', 15, 2)->default(0)->comment('Bunga dalam rupiah');
            $table->decimal('total_pinjaman', 15, 2)->default(0)->comment('Total pinjaman');
            $table->enum('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            // Indexes
            $table->index('no_pinjaman');
            $table->index('tanggal');
            $table->index('pelanggan_id');
            $table->index('status_pembayaran');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
