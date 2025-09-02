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
        Schema::create('detail_saldo_awal_produks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('saldo_awal_produk_id');
            $table->unsignedBigInteger('produk_id');
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('saldo_awal_produk_id')->references('id')->on('saldo_awal_produk')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');

            // Index
            $table->index(['saldo_awal_produk_id', 'produk_id']);
            $table->unique(['saldo_awal_produk_id', 'produk_id'], 'unique_detail_saldo_awal_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_saldo_awal_produks');
    }
};
