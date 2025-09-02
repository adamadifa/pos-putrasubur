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
        // Drop existing triggers first
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_insert');

        // Recreate trigger untuk INSERT pembayaran penjualan (tanpa field saldo)
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_penjualan_insert
            AFTER INSERT ON pembayaran_penjualan
            FOR EACH ROW
            BEGIN
                IF NEW.kas_bank_id IS NOT NULL THEN
                    -- Update saldo kas/bank
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini + NEW.jumlah_bayar
                    WHERE id = NEW.kas_bank_id;
                    
                    -- Insert ke transaksi_kas_bank untuk tracking (tanpa field saldo)
                    INSERT INTO transaksi_kas_bank (
                        kas_bank_id,
                        tanggal,
                        no_bukti,
                        jenis_transaksi,
                        kategori_transaksi,
                        referensi_id,
                        referensi_tipe,
                        jumlah,
                        keterangan,
                        user_id,
                        created_at,
                        updated_at
                    ) VALUES (
                        NEW.kas_bank_id,
                        NEW.tanggal,
                        NEW.no_bukti,
                        "D",
                        "PJ",
                        NEW.id,
                        "PPJ",
                        NEW.jumlah_bayar,
                        CONCAT("Pembayaran penjualan - ", NEW.keterangan),
                        NEW.user_id,
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ');

        // Recreate trigger untuk INSERT pembayaran pembelian (tanpa field saldo)
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_pembelian_insert
            AFTER INSERT ON pembayaran_pembelian
            FOR EACH ROW
            BEGIN
                IF NEW.kas_bank_id IS NOT NULL THEN
                    -- Update saldo kas/bank
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini - NEW.jumlah_bayar
                    WHERE id = NEW.kas_bank_id;
                    
                    -- Insert ke transaksi_kas_bank untuk tracking (tanpa field saldo)
                    INSERT INTO transaksi_kas_bank (
                        kas_bank_id,
                        tanggal,
                        no_bukti,
                        jenis_transaksi,
                        kategori_transaksi,
                        referensi_id,
                        referensi_tipe,
                        jumlah,
                        keterangan,
                        user_id,
                        created_at,
                        updated_at
                    ) VALUES (
                        NEW.kas_bank_id,
                        NEW.tanggal,
                        NEW.no_bukti,
                        "K",
                        "PB",
                        NEW.id,
                        "PPB",
                        NEW.jumlah_bayar,
                        CONCAT("Pembayaran pembelian - ", NEW.keterangan),
                        NEW.user_id,
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop updated triggers
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_insert');

        // Recreate old triggers dengan field saldo (untuk rollback)
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_penjualan_insert
            AFTER INSERT ON pembayaran_penjualan
            FOR EACH ROW
            BEGIN
                IF NEW.kas_bank_id IS NOT NULL THEN
                    -- Update saldo kas/bank
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini + NEW.jumlah_bayar
                    WHERE id = NEW.kas_bank_id;
                    
                    -- Insert ke transaksi_kas_bank untuk tracking (dengan field saldo)
                    INSERT INTO transaksi_kas_bank (
                        kas_bank_id,
                        tanggal,
                        no_bukti,
                        jenis_transaksi,
                        kategori_transaksi,
                        referensi_id,
                        referensi_tipe,
                        jumlah,
                        saldo_sebelum,
                        saldo_sesudah,
                        keterangan,
                        user_id,
                        created_at,
                        updated_at
                    ) VALUES (
                        NEW.kas_bank_id,
                        NEW.tanggal,
                        NEW.no_bukti,
                        "D",
                        "PJ",
                        NEW.id,
                        "PPJ",
                        NEW.jumlah_bayar,
                        (SELECT saldo_terkini - NEW.jumlah_bayar FROM kas_bank WHERE id = NEW.kas_bank_id),
                        (SELECT saldo_terkini FROM kas_bank WHERE id = NEW.kas_bank_id),
                        CONCAT("Pembayaran penjualan - ", NEW.keterangan),
                        NEW.user_id,
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_pembayaran_pembelian_insert
            AFTER INSERT ON pembayaran_pembelian
            FOR EACH ROW
            BEGIN
                IF NEW.kas_bank_id IS NOT NULL THEN
                    -- Update saldo kas/bank
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini - NEW.jumlah_bayar
                    WHERE id = NEW.kas_bank_id;
                    
                    -- Insert ke transaksi_kas_bank untuk tracking (dengan field saldo)
                    INSERT INTO transaksi_kas_bank (
                        kas_bank_id,
                        tanggal,
                        no_bukti,
                        jenis_transaksi,
                        kategori_transaksi,
                        referensi_id,
                        referensi_tipe,
                        jumlah,
                        saldo_sebelum,
                        saldo_sesudah,
                        keterangan,
                        user_id,
                        created_at,
                        updated_at
                    ) VALUES (
                        NEW.kas_bank_id,
                        NEW.tanggal,
                        NEW.no_bukti,
                        "K",
                        "PB",
                        NEW.id,
                        "PPB",
                        NEW.jumlah_bayar,
                        (SELECT saldo_terkini + NEW.jumlah_bayar FROM kas_bank WHERE id = NEW.kas_bank_id),
                        (SELECT saldo_terkini FROM kas_bank WHERE id = NEW.kas_bank_id),
                        CONCAT("Pembayaran pembelian - ", NEW.keterangan),
                        NEW.user_id,
                        NOW(),
                        NOW()
                    );
                END IF;
            END
        ');
    }
};
