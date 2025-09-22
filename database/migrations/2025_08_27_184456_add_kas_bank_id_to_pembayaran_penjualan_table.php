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
        if (!Schema::hasColumn('pembayaran_penjualan', 'kas_bank_id')) {
            Schema::table('pembayaran_penjualan', function (Blueprint $table) {
                $table->unsignedBigInteger('kas_bank_id')->nullable()->after('metode_pembayaran');
                $table->foreign('kas_bank_id')->references('id')->on('kas_bank')->onUpdate('cascade')->onDelete('restrict');
                $table->index('kas_bank_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_penjualan', function (Blueprint $table) {
            $table->dropForeign(['kas_bank_id']);
            $table->dropIndex(['kas_bank_id']);
            $table->dropColumn('kas_bank_id');
        });
    }
};
