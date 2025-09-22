<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPenjualan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_penjualan';

    protected $fillable = [
        'penjualan_id',
        'no_bukti',
        'tanggal',
        'jumlah_bayar',
        'metode_pembayaran',
        'kas_bank_id',
        'status_bayar',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
    ];

    /**
     * Relationship dengan Penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship dengan KasBank
     */
    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    /**
     * Relationship dengan TransaksiKasBank
     */
    public function transaksiKasBank()
    {
        return $this->hasOne(TransaksiKasBank::class, 'referensi_id')
            ->where('referensi_tipe', 'PPJ');
    }

    /**
     * Get metode pembayaran display
     */
    public function getMetodePembayaranDisplayAttribute()
    {
        $metode = [
            'tunai' => '💵 Tunai',
            'transfer' => '🏦 Transfer Bank',
            'qris' => '📱 QRIS',
            'kartu' => '💳 Kartu Debit/Credit',
            'ewallet' => '📱 E-Wallet'
        ];

        return $metode[$this->metode_pembayaran] ?? $this->metode_pembayaran;
    }

    /**
     * Get status bayar display
     */
    public function getStatusBayarDisplayAttribute()
    {
        $status = [
            'D' => 'DP',
            'A' => 'Angsuran',
            'P' => 'Pelunasan'
        ];

        return $status[$this->status_bayar] ?? $this->status_bayar;
    }
}
