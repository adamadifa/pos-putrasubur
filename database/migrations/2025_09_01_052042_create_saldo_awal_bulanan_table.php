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
        Schema::create('saldo_awal_bulanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kas_bank_id');
            $table->tinyInteger('periode_bulan'); // 1-12
            $table->year('periode_tahun');
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('kas_bank_id')->references('id')->on('kas_bank')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi saldo awal per kas/bank per bulan
            $table->unique(['kas_bank_id', 'periode_bulan', 'periode_tahun'], 'unique_saldo_awal_per_bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_awal_bulanan');
    }
};
