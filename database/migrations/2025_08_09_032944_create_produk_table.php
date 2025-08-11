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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk', 50)->unique();
            $table->string('nama_produk', 100);
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('satuan_id');
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimal')->default(0);
            $table->string('foto', 255)->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('kategori_id')->references('id')->on('kategori_produk')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('satuan_id')->references('id')->on('satuan')->onUpdate('cascade')->onDelete('restrict');

            // Indexes
            $table->index('nama_produk');
            $table->index('kategori_id');
            $table->index('satuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
