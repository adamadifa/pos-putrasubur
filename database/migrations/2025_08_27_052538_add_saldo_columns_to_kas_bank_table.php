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
        Schema::table('kas_bank', function (Blueprint $table) {
            $table->decimal('saldo_awal', 15, 2)->default(0)->after('no_rekening');
            $table->decimal('saldo_terkini', 15, 2)->default(0)->after('saldo_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_bank', function (Blueprint $table) {
            $table->dropColumn(['saldo_awal', 'saldo_terkini']);
        });
    }
};
