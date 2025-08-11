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
        Schema::table('pelanggan', function (Blueprint $table) {
            // Drop columns that are no longer needed
            $table->dropColumn('email');
            $table->dropColumn('kota');
            $table->dropColumn('kode_pos');
            $table->dropColumn('tipe_member');
            $table->dropColumn('catatan');
            $table->dropColumn('deleted_at');

            // Change status to boolean (0 = nonaktif, 1 = aktif)
            $table->boolean('status')->default(1)->after('alamat')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nama');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('kode_pos')->nullable()->after('kota');
            $table->string('tipe_member')->nullable()->after('kode_pos');
            $table->text('catatan')->nullable()->after('tipe_member');
            $table->softDeletes()->after('catatan');
        });
    }
};
