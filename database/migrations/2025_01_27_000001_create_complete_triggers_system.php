<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCompleteTriggersSystem extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing triggers first
        DB::unprepared('DROP TRIGGER IF EXISTS after_penjualan_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembelian_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_delete');

        // ===== TRIGGER UNTUK PENJUALAN =====

        // Trigger untuk DELETE penjualan
        DB::unprepared('
            CREATE TRIGGER after_penjualan_delete
            AFTER DELETE ON penjualan
            FOR EACH ROW
            BEGIN
                -- Hapus pembayaran penjualan (trigger pembayaran akan otomatis update saldo dan hapus transaksi)
                DELETE FROM pembayaran_penjualan WHERE penjualan_id = OLD.id;
                
                -- Hapus detail penjualan
                DELETE FROM detail_penjualan WHERE penjualan_id = OLD.id;
            END
        ');

        // Trigger untuk INSERT pembayaran penjualan
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
                    
                    -- Insert ke transaksi_kas_bank untuk tracking
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

        // Trigger untuk UPDATE pembayaran penjualan
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_penjualan_update
            AFTER UPDATE ON pembayaran_penjualan
            FOR EACH ROW
            BEGIN
                -- Jika kas_bank_id berubah
                IF OLD.kas_bank_id != NEW.kas_bank_id THEN
                    -- Kurangi saldo dari kas/bank lama
                    IF OLD.kas_bank_id IS NOT NULL THEN
                        UPDATE kas_bank 
                        SET saldo_terkini = saldo_terkini - OLD.jumlah_bayar
                        WHERE id = OLD.kas_bank_id;
                    END IF;
                    
                    -- Tambah saldo ke kas/bank baru
                    IF NEW.kas_bank_id IS NOT NULL THEN
                        UPDATE kas_bank 
                        SET saldo_terkini = saldo_terkini + NEW.jumlah_bayar
                        WHERE id = NEW.kas_bank_id;
                    END IF;
                -- Jika hanya jumlah yang berubah
                ELSEIF OLD.jumlah_bayar != NEW.jumlah_bayar AND NEW.kas_bank_id IS NOT NULL THEN
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini + (NEW.jumlah_bayar - OLD.jumlah_bayar)
                    WHERE id = NEW.kas_bank_id;
                END IF;
            END
        ');

        // Trigger untuk DELETE pembayaran penjualan
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_penjualan_delete
            AFTER DELETE ON pembayaran_penjualan
            FOR EACH ROW
            BEGIN
                -- Update saldo kas/bank (kurangi karena pembayaran dihapus)
                IF OLD.kas_bank_id IS NOT NULL THEN
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini - OLD.jumlah_bayar
                    WHERE id = OLD.kas_bank_id;
                END IF;
                
                -- Hapus transaksi kas bank terkait
                DELETE FROM transaksi_kas_bank 
                WHERE referensi_tipe = "PPJ" 
                AND referensi_id = OLD.id;
            END
        ');

        // ===== TRIGGER UNTUK PEMBELIAN =====

        // Trigger untuk DELETE pembelian
        DB::unprepared('
            CREATE TRIGGER after_pembelian_delete
            AFTER DELETE ON pembelian
            FOR EACH ROW
            BEGIN
                -- Hapus pembayaran pembelian (trigger pembayaran akan otomatis update saldo dan hapus transaksi)
                DELETE FROM pembayaran_pembelian WHERE pembelian_id = OLD.id;
                
                -- Hapus detail pembelian
                DELETE FROM detail_pembelian WHERE pembelian_id = OLD.id;
            END
        ');

        // Trigger untuk INSERT pembayaran pembelian
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
                    
                    -- Insert ke transaksi_kas_bank untuk tracking
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

        // Trigger untuk UPDATE pembayaran pembelian
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_pembelian_update
            AFTER UPDATE ON pembayaran_pembelian
            FOR EACH ROW
            BEGIN
                -- Jika kas_bank_id berubah
                IF OLD.kas_bank_id != NEW.kas_bank_id THEN
                    -- Tambah saldo ke kas/bank lama (karena pembelian mengurangi saldo)
                    IF OLD.kas_bank_id IS NOT NULL THEN
                        UPDATE kas_bank 
                        SET saldo_terkini = saldo_terkini + OLD.jumlah_bayar
                        WHERE id = OLD.kas_bank_id;
                    END IF;
                    
                    -- Kurangi saldo dari kas/bank baru
                    IF NEW.kas_bank_id IS NOT NULL THEN
                        UPDATE kas_bank 
                        SET saldo_terkini = saldo_terkini - NEW.jumlah_bayar
                        WHERE id = NEW.kas_bank_id;
                    END IF;
                -- Jika hanya jumlah yang berubah
                ELSEIF OLD.jumlah_bayar != NEW.jumlah_bayar AND NEW.kas_bank_id IS NOT NULL THEN
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini - (NEW.jumlah_bayar - OLD.jumlah_bayar)
                    WHERE id = NEW.kas_bank_id;
                END IF;
            END
        ');

        // Trigger untuk DELETE pembayaran pembelian
        DB::unprepared('
            CREATE TRIGGER after_pembayaran_pembelian_delete
            AFTER DELETE ON pembayaran_pembelian
            FOR EACH ROW
            BEGIN
                -- Update saldo kas/bank (tambah karena pembayaran pembelian dihapus)
                IF OLD.kas_bank_id IS NOT NULL THEN
                    UPDATE kas_bank 
                    SET saldo_terkini = saldo_terkini + OLD.jumlah_bayar
                    WHERE id = OLD.kas_bank_id;
                END IF;
                
                -- Hapus transaksi kas bank terkait
                DELETE FROM transaksi_kas_bank 
                WHERE referensi_tipe = "PPB" 
                AND referensi_id = OLD.id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all triggers
        DB::unprepared('DROP TRIGGER IF EXISTS after_penjualan_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_penjualan_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembelian_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembayaran_pembelian_delete');
    }
}
