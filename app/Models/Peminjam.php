<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    use HasFactory;

    protected $table = 'peminjam';

    protected $fillable = [
        'kode_peminjam',
        'nama',
        'nomor_telepon',
        'alamat',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $attributes = [
        'status' => true,
    ];

    protected $appends = ['encrypted_id'];

    // Accessor untuk encrypted ID
    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    // Static method untuk find by encrypted ID
    public static function findByEncryptedId($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
            return static::findOrFail($id);
        } catch (\Exception $e) {
            abort(404, 'Peminjam tidak ditemukan');
        }
    }

    // Relationships
    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status', false);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    // Boot method untuk auto-generate kode
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($peminjam) {
            if (empty($peminjam->kode_peminjam)) {
                $count = static::count() + 1;
                $peminjam->kode_peminjam = 'PMJ-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
