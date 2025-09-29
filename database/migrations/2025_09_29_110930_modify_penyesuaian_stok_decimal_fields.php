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
        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            // Change integer fields to decimal to support decimal values
            $table->decimal('stok_sebelum', 15, 2)->change();
            $table->decimal('jumlah_penyesuaian', 15, 2)->change();
            $table->decimal('stok_sesudah', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            // Revert back to integer fields
            $table->integer('stok_sebelum')->change();
            $table->integer('jumlah_penyesuaian')->change();
            $table->integer('stok_sesudah')->change();
        });
    }
};
