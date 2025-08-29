<?php

namespace App\Services;

use App\Models\TransaksiKasBank;
use App\Models\KasBank;
use App\Models\PembayaranPenjualan;
use App\Models\PembayaranPembelian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiKasBankService
{
    /**
     * Catat transaksi kas/bank untuk pembayaran penjualan
     */
    public function catatPembayaranPenjualan(PembayaranPenjualan $pembayaranPenjualan)
    {
        return DB::transaction(function () use ($pembayaranPenjualan) {
            $kasBank = KasBank::find($pembayaranPenjualan->kas_bank_id);

            if (!$kasBank) {
                throw new \Exception('Kas/Bank tidak ditemukan');
            }

            // Update saldo kas/bank (Debet = menambah saldo)
            $saldoInfo = $kasBank->updateSaldo($pembayaranPenjualan->jumlah_bayar, 'D');

            // Buat transaksi kas/bank
            $transaksi = TransaksiKasBank::create([
                'kas_bank_id' => $kasBank->id,
                'tanggal' => $pembayaranPenjualan->tanggal->toDateString(),
                'no_bukti' => $this->generateNoBukti('PJ'),
                'jenis_transaksi' => 'D', // Debet = pemasukan
                'kategori_transaksi' => 'PJ', // Penjualan
                'kategori_transaksi_id' => null,
                'referensi_id' => $pembayaranPenjualan->id,
                'referensi_tipe' => 'PPJ', // Pembayaran Penjualan
                'jumlah' => $pembayaranPenjualan->jumlah_bayar,
                'saldo_sebelum' => $saldoInfo['saldo_sebelum'],
                'saldo_sesudah' => $saldoInfo['saldo_sesudah'],
                'keterangan' => "Pembayaran penjualan {$pembayaranPenjualan->no_bukti} - {$pembayaranPenjualan->penjualan->no_faktur}",
                'user_id' => $pembayaranPenjualan->user_id
            ]);

            return $transaksi;
        });
    }

    /**
     * Catat transaksi kas/bank untuk pembayaran pembelian
     */
    public function catatPembayaranPembelian(PembayaranPembelian $pembayaranPembelian)
    {
        return DB::transaction(function () use ($pembayaranPembelian) {
            $kasBank = KasBank::find($pembayaranPembelian->kas_bank_id);

            if (!$kasBank) {
                throw new \Exception('Kas/Bank tidak ditemukan');
            }

            // Update saldo kas/bank (Kredit = mengurangi saldo)
            $saldoInfo = $kasBank->updateSaldo($pembayaranPembelian->jumlah_bayar, 'K');

            // Buat transaksi kas/bank
            $transaksi = TransaksiKasBank::create([
                'kas_bank_id' => $kasBank->id,
                'tanggal' => $pembayaranPembelian->tanggal->toDateString(),
                'no_bukti' => $this->generateNoBukti('PB'),
                'jenis_transaksi' => 'K', // Kredit = pengeluaran
                'kategori_transaksi' => 'PB', // Pembelian
                'kategori_transaksi_id' => null,
                'referensi_id' => $pembayaranPembelian->id,
                'referensi_tipe' => 'PPB', // Pembayaran Pembelian
                'jumlah' => $pembayaranPembelian->jumlah_bayar,
                'saldo_sebelum' => $saldoInfo['saldo_sebelum'],
                'saldo_sesudah' => $saldoInfo['saldo_sesudah'],
                'keterangan' => "Pembayaran pembelian {$pembayaranPembelian->no_bukti} - {$pembayaranPembelian->pembelian->no_faktur}",
                'user_id' => $pembayaranPembelian->user_id
            ]);

            return $transaksi;
        });
    }

    /**
     * Hapus transaksi kas/bank untuk pembayaran penjualan
     */
    public function hapusPembayaranPenjualan(PembayaranPenjualan $pembayaranPenjualan)
    {
        return DB::transaction(function () use ($pembayaranPenjualan) {
            $transaksi = TransaksiKasBank::where('referensi_id', $pembayaranPenjualan->id)
                ->where('referensi_tipe', 'PPJ')
                ->first();

            if ($transaksi) {
                $kasBank = KasBank::find($transaksi->kas_bank_id);

                if ($kasBank) {
                    // Kembalikan saldo (Kredit = mengurangi saldo yang sebelumnya ditambah)
                    $kasBank->updateSaldo($transaksi->jumlah, 'K');
                }

                $transaksi->delete();
            }
        });
    }

    /**
     * Hapus transaksi kas/bank untuk pembayaran pembelian
     */
    public function hapusPembayaranPembelian(PembayaranPembelian $pembayaranPembelian)
    {
        return DB::transaction(function () use ($pembayaranPembelian) {
            $transaksi = TransaksiKasBank::where('referensi_id', $pembayaranPembelian->id)
                ->where('referensi_tipe', 'PPB')
                ->first();

            if ($transaksi) {
                $kasBank = KasBank::find($transaksi->kas_bank_id);

                if ($kasBank) {
                    // Kembalikan saldo (Debet = menambah saldo yang sebelumnya dikurangi)
                    $kasBank->updateSaldo($transaksi->jumlah, 'D');
                }

                $transaksi->delete();
            }
        });
    }

    /**
     * Generate nomor bukti transaksi
     */
    private function generateNoBukti($prefix)
    {
        $date = now()->format('Ymd');
        $lastTransaksi = TransaksiKasBank::where('no_bukti', 'like', "{$prefix}{$date}%")
            ->orderBy('no_bukti', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = (int) substr($lastTransaksi->no_bukti, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

