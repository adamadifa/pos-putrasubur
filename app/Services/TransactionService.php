<?php

namespace App\Services;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PembayaranPenjualan;
use App\Models\PembayaranPembelian;
use App\Models\TransaksiKasBank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TransactionService
{
    /**
     * Hapus penjualan dengan validasi business rules
     */
    public function deletePenjualan(Penjualan $penjualan): array
    {
        try {
            DB::beginTransaction();

            // Debug: Log sebelum validasi
            Log::info('Starting penjualan deletion process', [
                'penjualan_id' => $penjualan->id,
                'no_faktur' => $penjualan->no_faktur,
                'total_pembayaran' => $penjualan->pembayaranPenjualan->sum('jumlah_bayar'),
                'created_at' => $penjualan->created_at,
                'hours_diff' => now()->diffInHours($penjualan->created_at)
            ]);

            // 1. Validasi business rules
            $validationResult = $this->validatePenjualanDeletion($penjualan);
            if (!$validationResult['success']) {
                DB::rollback();
                return $validationResult;
            }

            // 2. Log activity untuk audit trail
            $this->logPenjualanDeletion($penjualan);

            // 3. Debug: Log sebelum delete
            Log::info('About to delete penjualan', [
                'penjualan_id' => $penjualan->id,
                'detail_count' => $penjualan->detailPenjualan->count(),
                'pembayaran_count' => $penjualan->pembayaranPenjualan->count(),
                'has_payment' => $validationResult['has_payment'] ?? false,
                'total_payment' => $validationResult['total_payment'] ?? 0
            ]);

            // 4. Kembalikan stok produk sebelum menghapus penjualan
            $this->restoreStockFromPenjualan($penjualan);

            // 5. Kembalikan uang muka jika ada sebelum menghapus pembayaran
            $penggunaanUangMukaList = \App\Models\PenggunaanUangMukaPenjualan::where('penjualan_id', $penjualan->id)->get();
            foreach ($penggunaanUangMukaList as $penggunaan) {
                $uangMuka = $penggunaan->uangMukaPelanggan;
                $jumlahDigunakan = $penggunaan->jumlah_digunakan;

                // Kembalikan sisa uang muka (tambah kembali jumlah yang digunakan)
                $uangMuka->sisa_uang_muka += $jumlahDigunakan;

                // Update status uang muka jika perlu
                if ($uangMuka->sisa_uang_muka > 0 && $uangMuka->status === 'habis') {
                    $uangMuka->status = 'aktif';
                }

                $uangMuka->save();

                Log::info('Uang muka dikembalikan saat penjualan dihapus', [
                    'penjualan_id' => $penjualan->id,
                    'uang_muka_id' => $uangMuka->id,
                    'jumlah_dikembalikan' => $jumlahDigunakan,
                    'sisa_uang_muka_baru' => $uangMuka->sisa_uang_muka,
                    'status_uang_muka' => $uangMuka->status
                ]);

                // Hapus record penggunaan uang muka
                $penggunaan->delete();
            }

            // 6. Hapus pembayaran terlebih dahulu untuk memastikan trigger berjalan
            $pembayaranList = $penjualan->pembayaranPenjualan;
            foreach ($pembayaranList as $pembayaran) {
                $pembayaran->delete(); // Ini akan memicu trigger after_pembayaran_penjualan_delete
            }

            // 7. Hapus penjualan (trigger akan otomatis menghapus detail)
            $deleted = $penjualan->delete();

            // Debug: Log hasil delete
            Log::info('Delete result', [
                'penjualan_id' => $penjualan->id,
                'deleted' => $deleted
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => $validationResult['message'],
                'data' => [
                    'penjualan_id' => $penjualan->id,
                    'no_faktur' => $penjualan->no_faktur,
                    'total' => $penjualan->total,
                    'deleted' => $deleted,
                    'has_payment' => $validationResult['has_payment'] ?? false,
                    'total_payment' => $validationResult['total_payment'] ?? 0
                ]
            ];
        } catch (Exception $e) {
            DB::rollback();

            Log::error('Error deleting penjualan', [
                'penjualan_id' => $penjualan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus penjualan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Hapus pembelian dengan validasi business rules
     */
    public function deletePembelian(Pembelian $pembelian): array
    {
        try {
            DB::beginTransaction();

            // 1. Validasi business rules
            $validationResult = $this->validatePembelianDeletion($pembelian);
            if (!$validationResult['success']) {
                return $validationResult;
            }

            // 2. Log activity untuk audit trail
            $this->logPembelianDeletion($pembelian);

            // 3. Debug: Log sebelum delete
            Log::info('About to delete pembelian', [
                'pembelian_id' => $pembelian->id,
                'detail_count' => $pembelian->detailPembelian->count(),
                'pembayaran_count' => $pembelian->pembayaranPembelian->count(),
                'has_payment' => $validationResult['has_payment'] ?? false,
                'total_payment' => $validationResult['total_payment'] ?? 0
            ]);

            // 4. Kurangi stok produk sebelum menghapus pembelian
            $this->reduceStockFromPembelian($pembelian);

            // 5. Kembalikan uang muka jika ada sebelum menghapus pembayaran
            $penggunaanUangMukaList = \App\Models\PenggunaanUangMukaPembelian::where('pembelian_id', $pembelian->id)->get();
            foreach ($penggunaanUangMukaList as $penggunaan) {
                $uangMuka = $penggunaan->uangMukaSupplier;
                $jumlahDigunakan = $penggunaan->jumlah_digunakan;

                // Kembalikan sisa uang muka (tambah kembali jumlah yang digunakan)
                $uangMuka->sisa_uang_muka += $jumlahDigunakan;

                // Update status uang muka jika perlu
                if ($uangMuka->sisa_uang_muka > 0 && $uangMuka->status === 'habis') {
                    $uangMuka->status = 'aktif';
                }

                $uangMuka->save();

                Log::info('Uang muka supplier dikembalikan saat pembelian dihapus', [
                    'pembelian_id' => $pembelian->id,
                    'uang_muka_id' => $uangMuka->id,
                    'jumlah_dikembalikan' => $jumlahDigunakan,
                    'sisa_uang_muka_baru' => $uangMuka->sisa_uang_muka,
                    'status_uang_muka' => $uangMuka->status
                ]);

                // Hapus record penggunaan uang muka
                $penggunaan->delete();
            }

            // 6. Hapus pembayaran terlebih dahulu untuk memastikan trigger berjalan
            $pembayaranList = $pembelian->pembayaranPembelian;
            foreach ($pembayaranList as $pembayaran) {
                $pembayaran->delete(); // Ini akan memicu trigger after_pembayaran_pembelian_delete
            }

            // 7. Hapus pembelian (trigger akan otomatis menghapus detail)
            $deleted = $pembelian->delete();

            // Debug: Log hasil delete
            Log::info('Delete result', [
                'pembelian_id' => $pembelian->id,
                'deleted' => $deleted
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => $validationResult['message'],
                'data' => [
                    'pembelian_id' => $pembelian->id,
                    'no_faktur' => $pembelian->no_faktur,
                    'total' => $pembelian->total,
                    'deleted' => $deleted,
                    'has_payment' => $validationResult['has_payment'] ?? false,
                    'total_payment' => $validationResult['total_payment'] ?? 0
                ]
            ];
        } catch (Exception $e) {
            DB::rollback();

            Log::error('Error deleting pembelian', [
                'pembelian_id' => $pembelian->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pembelian: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validasi penghapusan penjualan
     */
    private function validatePenjualanDeletion(Penjualan $penjualan): array
    {
        // Cek apakah penjualan masih dalam hari yang sama
        $createdAt = $penjualan->created_at;
        $isSameDay = $createdAt->isSameDay(now());

        if (!$isSameDay) {
            return [
                'success' => false,
                'message' => 'Tidak dapat menghapus penjualan yang sudah bukan hari yang sama. Hanya penjualan hari ini yang dapat dihapus.'
            ];
        }

        // Cek apakah ada penggunaan uang muka
        $penggunaanUangMuka = \App\Models\PenggunaanUangMukaPenjualan::where('penjualan_id', $penjualan->id)->get();
        $totalUangMukaDigunakan = $penggunaanUangMuka->sum('jumlah_digunakan');

        // Cek apakah ada pembayaran
        $totalPembayaran = $penjualan->pembayaranPenjualan->sum('jumlah_bayar');

        if ($totalPembayaran > 0 || $totalUangMukaDigunakan > 0) {
            $message = 'Penjualan akan dihapus beserta pembayaran sebesar Rp ' . number_format($totalPembayaran, 0, ',', '.');
            if ($totalUangMukaDigunakan > 0) {
                $message .= ' dan uang muka sebesar Rp ' . number_format($totalUangMukaDigunakan, 0, ',', '.') . ' akan dikembalikan ke pelanggan.';
            } else {
                $message .= ' dan saldo kas/bank akan disesuaikan.';
            }
            
            return [
                'success' => true,
                'message' => $message,
                'has_payment' => $totalPembayaran > 0,
                'has_uang_muka' => $totalUangMukaDigunakan > 0,
                'total_payment' => $totalPembayaran,
                'total_uang_muka' => $totalUangMukaDigunakan
            ];
        }

        return [
            'success' => true,
            'message' => 'Penjualan akan dihapus.',
            'has_payment' => false,
            'has_uang_muka' => false,
            'total_payment' => 0,
            'total_uang_muka' => 0
        ];
    }

    /**
     * Validasi penghapusan pembelian
     */
    private function validatePembelianDeletion(Pembelian $pembelian): array
    {
        $restrictDeletion = config('features.restrict_pembelian_delete_same_day', false);

        if ($restrictDeletion) {
            // Cek apakah pembelian masih dalam hari yang sama
            $createdAt = $pembelian->created_at;
            $isSameDay = $createdAt->isSameDay(now());

            if (!$isSameDay) {
                return [
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pembelian yang sudah bukan hari yang sama. Hanya pembelian hari ini yang dapat dihapus.'
                ];
            }
        }

        // Cek apakah ada penggunaan uang muka
        $penggunaanUangMuka = \App\Models\PenggunaanUangMukaPembelian::where('pembelian_id', $pembelian->id)->get();
        $totalUangMukaDigunakan = $penggunaanUangMuka->sum('jumlah_digunakan');

        // Cek apakah ada pembayaran
        $totalPembayaran = $pembelian->pembayaranPembelian->sum('jumlah_bayar');

        if ($totalPembayaran > 0 || $totalUangMukaDigunakan > 0) {
            $message = 'Pembelian akan dihapus beserta pembayaran sebesar Rp ' . number_format($totalPembayaran, 0, ',', '.');
            if ($totalUangMukaDigunakan > 0) {
                $message .= ' dan uang muka sebesar Rp ' . number_format($totalUangMukaDigunakan, 0, ',', '.') . ' akan dikembalikan ke supplier.';
            } else {
                $message .= ' dan saldo kas/bank akan disesuaikan.';
            }
            
            return [
                'success' => true,
                'message' => $message,
                'has_payment' => $totalPembayaran > 0,
                'has_uang_muka' => $totalUangMukaDigunakan > 0,
                'total_payment' => $totalPembayaran,
                'total_uang_muka' => $totalUangMukaDigunakan
            ];
        }

        return [
            'success' => true,
            'message' => 'Pembelian akan dihapus.',
            'has_payment' => false,
            'has_uang_muka' => false,
            'total_payment' => 0,
            'total_uang_muka' => 0
        ];
    }

    /**
     * Log penghapusan penjualan untuk audit trail
     */
    private function logPenjualanDeletion(Penjualan $penjualan): void
    {
        Log::info('Penjualan dihapus', [
            'penjualan_id' => $penjualan->id,
            'no_faktur' => $penjualan->no_faktur,
            'total' => $penjualan->total,
            'status_pembayaran' => $penjualan->status_pembayaran,
            'jenis_transaksi' => $penjualan->jenis_transaksi,
            'supplier_id' => $penjualan->supplier_id,
            'user_id' => auth()->id(),
            'deleted_at' => now(),
            'detail_items' => $penjualan->detailPenjualan->map(function ($detail) {
                return [
                    'produk_id' => $detail->produk_id,
                    'qty' => $detail->qty,
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal
                ];
            })->toArray()
        ]);
    }

    /**
     * Log penghapusan pembelian untuk audit trail
     */
    private function logPembelianDeletion(Pembelian $pembelian): void
    {
        Log::info('Pembelian dihapus', [
            'pembelian_id' => $pembelian->id,
            'no_faktur' => $pembelian->no_faktur,
            'total' => $pembelian->total,
            'status_pembayaran' => $pembelian->status_pembayaran,
            'jenis_transaksi' => $pembelian->jenis_transaksi,
            'supplier_id' => $pembelian->supplier_id,
            'user_id' => auth()->id(),
            'deleted_at' => now(),
            'detail_items' => $pembelian->detailPembelian->map(function ($detail) {
                return [
                    'produk_id' => $detail->produk_id,
                    'qty' => $detail->qty,
                    'harga_beli' => $detail->harga_beli,
                    'subtotal' => $detail->subtotal
                ];
            })->toArray()
        ]);
    }

    /**
     * Recalculate saldo kas/bank
     */
    public function recalculateKasBankSaldo(int $kasBankId): array
    {
        try {
            DB::beginTransaction();

            // Ambil saldo awal
            $kasBank = \App\Models\KasBank::findOrFail($kasBankId);
            $saldoAwal = $kasBank->saldo_awal;

            // Hitung total transaksi masuk (pembayaran penjualan)
            $totalMasuk = PembayaranPenjualan::where('kas_bank_id', $kasBankId)
                ->sum('jumlah_bayar');

            // Hitung total transaksi keluar (pembayaran pembelian)
            $totalKeluar = PembayaranPembelian::where('kas_bank_id', $kasBankId)
                ->sum('jumlah_bayar');

            // Hitung saldo terkini
            $saldoTerkini = $saldoAwal + $totalMasuk - $totalKeluar;

            // Update saldo
            $kasBank->update(['saldo_terkini' => $saldoTerkini]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Saldo kas/bank berhasil dihitung ulang.',
                'data' => [
                    'kas_bank_id' => $kasBankId,
                    'saldo_awal' => $saldoAwal,
                    'total_masuk' => $totalMasuk,
                    'total_keluar' => $totalKeluar,
                    'saldo_terkini' => $saldoTerkini
                ]
            ];
        } catch (Exception $e) {
            DB::rollback();

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung ulang saldo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kembalikan stok produk dari penjualan yang akan dihapus
     */
    private function restoreStockFromPenjualan(Penjualan $penjualan): void
    {
        try {
            // Ambil detail penjualan
            $detailPenjualan = $penjualan->detailPenjualan;

            foreach ($detailPenjualan as $detail) {
                // Kembalikan stok produk
                $produk = \App\Models\Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->increment('stok', $detail->qty);

                    Log::info('Stock restored for product', [
                        'produk_id' => $produk->id,
                        'nama_produk' => $produk->nama_produk,
                        'qty_restored' => $detail->qty,
                        'stok_sebelum' => $produk->stok - $detail->qty,
                        'stok_sesudah' => $produk->stok,
                        'penjualan_id' => $penjualan->id
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Error restoring stock from penjualan', [
                'penjualan_id' => $penjualan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw untuk ditangani oleh caller
        }
    }

    /**
     * Kurangi stok produk dari pembelian yang akan dihapus
     */
    private function reduceStockFromPembelian(Pembelian $pembelian): void
    {
        try {
            // Ambil detail pembelian
            $detailPembelian = $pembelian->detailPembelian;

            foreach ($detailPembelian as $detail) {
                // Kurangi stok produk
                $produk = \App\Models\Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->decrement('stok', $detail->qty);

                    Log::info('Stock reduced for product from pembelian deletion', [
                        'produk_id' => $produk->id,
                        'nama_produk' => $produk->nama_produk,
                        'qty_reduced' => $detail->qty,
                        'stok_sebelum' => $produk->stok + $detail->qty,
                        'stok_sesudah' => $produk->stok,
                        'pembelian_id' => $pembelian->id
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Error reducing stock from pembelian', [
                'pembelian_id' => $pembelian->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw untuk ditangani oleh caller
        }
    }
}
