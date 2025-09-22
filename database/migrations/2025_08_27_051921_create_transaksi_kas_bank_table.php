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
        Schema::create('transaksi_kas_bank', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kas_bank_id');
            $table->date('tanggal');
            $table->string('no_bukti', 50)->unique();
            $table->enum('jenis_transaksi', ['D', 'K'])->comment('D=Debet, K=Kredit');
            $table->enum('kategori_transaksi', ['PJ', 'PB', 'MN', 'TF'])->comment('PJ=Penjualan, PB=Pembelian, MN=Manual, TF=Transfer');
            $table->unsignedBigInteger('kategori_transaksi_id')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->enum('referensi_tipe', ['PPJ', 'PPB', 'MN'])->comment('PPJ=PembayaranPenjualan, PPB=PembayaranPembelian, MN=Manual');
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2);
            $table->decimal('saldo_sesudah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('kas_bank_id')->references('id')->on('kas_bank')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kategori_transaksi_id')->references('id')->on('kategori_transaksi')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');

            // Indexes
            $table->index('kas_bank_id');
            $table->index('tanggal');
            $table->index('no_bukti');
            $table->index('jenis_transaksi');
            $table->index('kategori_transaksi');
            $table->index('kategori_transaksi_id');
            $table->index('referensi_id');
            $table->index('referensi_tipe');
            $table->index('user_id');
            $table->index(['kas_bank_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kas_bank');
    }
};
