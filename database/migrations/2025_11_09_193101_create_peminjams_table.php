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
        Schema::create('peminjam', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjam', 50)->unique();
            $table->string('nama', 100);
            $table->string('nomor_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status')->default(true)->comment('1=aktif, 0=nonaktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('nama');
            $table->index('kode_peminjam');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjam');
    }
};
