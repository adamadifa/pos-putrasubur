<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->decimal('total_potongan', 15, 2)->default(0)->after('total');
            $table->decimal('nett_total', 15, 2)->default(0)->after('total_potongan');
        });

        // Set nett_total = total for all existing records
        DB::statement('UPDATE pembelian SET nett_total = total WHERE nett_total = 0 AND total > 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn(['total_potongan', 'nett_total']);
        });
    }
};
