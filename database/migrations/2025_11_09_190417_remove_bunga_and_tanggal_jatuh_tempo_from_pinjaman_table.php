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
            $table->dropColumn(['bunga', 'tanggal_jatuh_tempo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->decimal('bunga', 15, 2)->default(0)->after('jumlah_pinjaman');
            $table->date('tanggal_jatuh_tempo')->nullable()->after('status_pembayaran');
        });
    }
};
