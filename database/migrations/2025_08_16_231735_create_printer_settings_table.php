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
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama pengaturan printer');
            $table->string('printer_name')->nullable()->comment('Nama printer yang dipilih');
            $table->string('printer_port')->nullable()->comment('Port printer (COM, USB, dll)');
            $table->text('printer_config')->nullable()->comment('Konfigurasi tambahan printer dalam JSON');
            $table->boolean('is_default')->default(false)->comment('Apakah ini pengaturan default');
            $table->boolean('is_active')->default(true)->comment('Status aktif pengaturan');
            $table->text('description')->nullable()->comment('Deskripsi pengaturan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_settings');
    }
};
