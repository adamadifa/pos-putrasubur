<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'pelanggan';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'nomor_telepon',
        'alamat',
        'status',
        'foto',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should have default values.
     */
    protected $attributes = [
        'status' => true, // 1 = aktif, 0 = nonaktif
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Relationship dengan Penjualan
     */
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }

    /**
     * Get total transaksi pelanggan
     */
    public function getTotalTransaksiAttribute()
    {
        return $this->penjualan()->count();
    }

    /**
     * Get total nilai transaksi pelanggan
     */
    public function getTotalNilaiTransaksiAttribute()
    {
        return $this->penjualan()->sum('total');
    }

    /**
     * Get rata-rata nilai transaksi pelanggan
     */
    public function getRataRataTransaksiAttribute()
    {
        $total = $this->penjualan()->count();
        if ($total > 0) {
            return $this->penjualan()->sum('total') / $total;
        }
        return 0;
    }

    /**
     * Encrypt ID untuk URL
     */
    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    /**
     * Decrypt ID dari URL
     */
    public static function decryptId($encryptedId)
    {
        try {
            return decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'ID tidak valid');
        }
    }

    /**
     * Find by encrypted ID
     */
    public static function findByEncryptedId($encryptedId)
    {
        $id = self::decryptId($encryptedId);
        return self::findOrFail($id);
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include inactive customers.
     */
    public function scopeNonaktif($query)
    {
        return $query->where('status', false);
    }

    /**
     * Get the customer's full address.
     */
    public function getAlamatLengkapAttribute(): string
    {
        return $this->alamat ?: 'Alamat tidak tersedia';
    }

    /**
     * Check if customer is active.
     */
    public function isAktif(): bool
    {
        return $this->status === true;
    }

    /**
     * Get the customer's photo URL or default avatar.
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        return asset('images/default-avatar.png');
    }

    /**
     * Get the customer's status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate kode_pelanggan if not provided
        static::creating(function ($pelanggan) {
            if (empty($pelanggan->kode_pelanggan)) {
                $pelanggan->kode_pelanggan = 'P-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
