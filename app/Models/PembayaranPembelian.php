<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPembelian extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_pembelian';

    protected $fillable = [
        'pembelian_id',
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

    // Relationships
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getStatusBayarDisplayAttribute()
    {
        switch ($this->status_bayar) {
            case 'P':
                return 'Pelunasan';
            case 'D':
                return 'DP';
            case 'A':
                return 'Angsuran';
            case 'B':
                return 'Bayar Sebagian';
            default:
                return $this->status_bayar;
        }
    }

    public function getMetodePembayaranDisplayAttribute()
    {
        switch ($this->metode_pembayaran) {
            case 'tunai':
                return 'Tunai';
            case 'transfer':
                return 'Transfer Bank';
            case 'qris':
                return 'QRIS';
            case 'kartu':
                return 'Kartu Debit/Credit';
            case 'ewallet':
                return 'E-Wallet';
            default:
                return ucfirst($this->metode_pembayaran);
        }
    }
}

