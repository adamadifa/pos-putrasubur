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
        Schema::create('pembayaran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pinjaman_id');
            $table->string('no_bukti', 50)->unique();
            $table->datetime('tanggal');
            $table->decimal('jumlah_bayar', 15, 2)->default(0);
            $table->string('metode_pembayaran', 50);
            $table->unsignedBigInteger('kas_bank_id')->nullable();
            $table->enum('status_bayar', ['P', 'A'])->default('A')->comment('P=Pelunasan, A=Angsuran');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('pinjaman_id')->references('id')->on('pinjaman')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('kas_bank_id')->references('id')->on('kas_bank')->onDelete('restrict');

            // Indexes
            $table->index('pinjaman_id');
            $table->index('no_bukti');
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
        Schema::dropIfExists('pembayaran_pinjaman');
    }
};
