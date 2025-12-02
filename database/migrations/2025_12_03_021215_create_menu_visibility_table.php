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
        Schema::create('menu_visibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('menu_key', 100)->comment('Key menu seperti dashboard, master-data, penjualan, dll');
            $table->boolean('is_hidden')->default(false)->comment('true jika menu dihide');
            $table->timestamps();

            // Unique constraint: satu user hanya bisa punya satu record per menu_key
            $table->unique(['user_id', 'menu_key']);
            
            // Indexes
            $table->index('user_id');
            $table->index('menu_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_visibility');
    }
};
