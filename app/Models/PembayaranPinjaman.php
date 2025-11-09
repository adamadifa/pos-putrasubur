<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPinjaman extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_pinjaman';

    protected $fillable = [
        'pinjaman_id',
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

    protected $appends = ['encrypted_id'];

    // Relationships
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
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
            ->where('referensi_tipe', 'PPN');
    }

    // Accessors
    public function getStatusBayarDisplayAttribute()
    {
        switch ($this->status_bayar) {
            case 'P':
                return 'Pelunasan';
            case 'A':
                return 'Angsuran';
            default:
                return $this->status_bayar;
        }
    }

    public function getMetodePembayaranDisplayAttribute()
    {
        $metode = [
            'tunai' => '💵 Tunai',
            'transfer' => '🏦 Transfer Bank',
            'qris' => '📱 QRIS',
            'kartu' => '💳 Kartu Debit/Credit',
            'ewallet' => '📱 E-Wallet'
        ];

        return $metode[$this->metode_pembayaran] ?? ucfirst($this->metode_pembayaran);
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
