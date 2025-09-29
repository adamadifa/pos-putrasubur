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
            $table->tinyInteger('status_card_payment')->default(0)->after('saldo_terkini');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_bank', function (Blueprint $table) {
            $table->dropColumn('status_card_payment');
        });
    }
};
