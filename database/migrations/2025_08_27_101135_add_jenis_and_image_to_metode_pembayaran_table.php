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
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->enum('jenis', ['KAS', 'BANK'])->default('KAS')->after('nama')->comment('KAS=Kas, BANK=Bank');
            $table->string('image', 255)->nullable()->after('jenis')->comment('Path to bank logo image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'image']);
        });
    }
};
