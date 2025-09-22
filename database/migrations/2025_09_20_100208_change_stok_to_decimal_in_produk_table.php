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
        Schema::table('produk', function (Blueprint $table) {
            // Change stok and stok_minimal from integer to decimal
            $table->decimal('stok', 15, 2)->default(0)->change();
            $table->decimal('stok_minimal', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Revert back to integer
            $table->integer('stok')->default(0)->change();
            $table->integer('stok_minimal')->default(0)->change();
        });
    }
};
