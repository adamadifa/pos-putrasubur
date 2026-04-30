<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompensasiPembelian extends Model
{
    use HasFactory;

    protected $table = 'kompensasi_pembelian';

    protected $fillable = [
        'pembelian_id',
        'penjualan_id',
        'jumlah_kompensasi',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'jumlah_kompensasi' => 'decimal:2',
    ];

    // Relationships
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
