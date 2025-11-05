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
        Schema::create('uang_muka_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('no_uang_muka', 50)->unique()->comment('Contoh: UM-PEL-20250101-001');
            $table->unsignedBigInteger('pelanggan_id');
            $table->date('tanggal');
            $table->decimal('jumlah_uang_muka', 15, 2)->comment('Total uang muka yang diterima');
            $table->decimal('sisa_uang_muka', 15, 2)->default(0)->comment('Sisa yang belum digunakan');
            $table->string('metode_pembayaran', 50);
            $table->unsignedBigInteger('kas_bank_id')->nullable();
            $table->enum('status', ['aktif', 'habis', 'dibatalkan'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kas_bank_id')->references('id')->on('kas_bank')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');

            // Indexes
            $table->index('no_uang_muka');
            $table->index('pelanggan_id');
            $table->index('tanggal');
            $table->index('status');
            $table->index('kas_bank_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_muka_pelanggan');
    }
};
