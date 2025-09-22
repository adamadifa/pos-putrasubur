<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'icon',
        'status',
        'urutan',
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Get encrypted ID attribute
     */
    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    /**
     * Scope for active methods
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for inactive methods
     */
    public function scopeNonaktif($query)
    {
        return $query->where('status', false);
    }

    /**
     * Scope for ordered methods
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('nama', 'asc');
    }

    /**
     * Get status display attribute
     */
    public function getStatusDisplayAttribute()
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get icon display attribute
     */
    public function getIconDisplayAttribute()
    {
        return $this->icon ?: 'ti-credit-card';
    }
}
