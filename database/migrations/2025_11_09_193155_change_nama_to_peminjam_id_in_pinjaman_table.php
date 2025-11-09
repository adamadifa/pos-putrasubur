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
            // Drop index and column nama
            $table->dropIndex(['nama']);
            $table->dropColumn('nama');

            // Add peminjam_id column
            $table->unsignedBigInteger('peminjam_id')->after('no_pinjaman');
            $table->foreign('peminjam_id')->references('id')->on('peminjam')->onDelete('restrict');
            $table->index('peminjam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            // Drop foreign key and index
            $table->dropForeign(['peminjam_id']);
            $table->dropIndex(['peminjam_id']);

            // Drop column
            $table->dropColumn('peminjam_id');

            // Add back nama column
            $table->string('nama', 255)->after('no_pinjaman');
            $table->index('nama');
        });
    }
};
