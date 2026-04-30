<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PenambahanPinjaman extends Model
{
    use HasFactory;

    protected $table = 'penambahan_pinjaman';

    protected $fillable = [
        'pinjaman_id',
        'tanggal',
        'jumlah',
        'kas_bank_id',
        'metode_pembayaran',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
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

    // Encryption
    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    public static function findByEncryptedId($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            return static::findOrFail($id);
        } catch (\Exception $e) {
            abort(404, 'Data tidak ditemukan');
        }
    }
}
