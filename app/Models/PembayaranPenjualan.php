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
