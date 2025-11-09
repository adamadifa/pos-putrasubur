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
        Schema::table('pinjaman', function (Blueprint $table) {
            // Drop foreign key and index
            $table->dropForeign(['pelanggan_id']);
            $table->dropIndex(['pelanggan_id']);
            
            // Drop column
            $table->dropColumn('pelanggan_id');
            
            // Add nama column
            $table->string('nama', 255)->after('no_pinjaman');
            
            // Add index for nama
            $table->index('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            // Drop nama column and index
            $table->dropIndex(['nama']);
            $table->dropColumn('nama');
            
            // Add back pelanggan_id
            $table->unsignedBigInteger('pelanggan_id')->after('no_pinjaman');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('restrict');
            $table->index('pelanggan_id');
        });
    }
};
