<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Pinjaman extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Cascade delete saat model dihapus
        static::deleting(function ($pinjaman) {
            // Log sebelum penghapusan untuk audit trail
            Log::info('Pinjaman akan dihapus', [
                'pinjaman_id' => $pinjaman->id,
                'no_pinjaman' => $pinjaman->no_pinjaman,
                'total_pinjaman' => $pinjaman->total_pinjaman,
                'user_id' => auth()->id(),
                'deleted_at' => now()
            ]);
        });
    }

    protected $table = 'pinjaman';

    protected $fillable = [
        'no_pinjaman',
        'peminjam_id',
        'tanggal',
        'jumlah_pinjaman',
        'total_pinjaman',
        'status_pembayaran',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_pinjaman' => 'decimal:2',
        'total_pinjaman' => 'decimal:2',
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
            abort(404, 'Pinjaman tidak ditemukan');
        }
    }

    // Relationships
    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function pembayaranPinjaman()
    {
        return $this->hasMany(PembayaranPinjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getTotalDibayarAttribute()
    {
        return $this->pembayaranPinjaman->sum('jumlah_bayar');
    }

    public function getSisaPinjamanAttribute()
    {
        $totalDibayar = $this->total_dibayar;
        return $this->total_pinjaman - $totalDibayar;
    }

    public function getStatusPembayaranDisplayAttribute()
    {
        switch ($this->status_pembayaran) {
            case 'belum_bayar':
                return 'Belum Bayar';
            case 'sebagian':
                return 'Sebagian';
            case 'lunas':
                return 'Lunas';
            default:
                return $this->status_pembayaran;
        }
    }

    public function getStatusPembayaranColorAttribute()
    {
        switch ($this->status_pembayaran) {
            case 'belum_bayar':
                return 'red';
            case 'sebagian':
                return 'yellow';
            case 'lunas':
                return 'green';
            default:
                return 'gray';
        }
    }
}
