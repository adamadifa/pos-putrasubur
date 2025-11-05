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
        'kas_bank_id',
        'status_bayar',
        'status_uang_muka',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
        'status_uang_muka' => 'integer',
    ];

    protected $appends = ['encrypted_id'];

    // Relationships
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    public function transaksiKasBank()
    {
        return $this->hasOne(TransaksiKasBank::class, 'referensi_id')
            ->where('referensi_tipe', 'PPB');
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

    // Encryption methods
    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    public static function findByEncryptedId($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
            return static::find($id);
        } catch (\Exception $e) {
            return null;
        }
    }
}
