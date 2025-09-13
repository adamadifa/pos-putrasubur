<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanUmum extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_umum';

    protected $fillable = [
        'nama_toko',
        'alamat',
        'no_telepon',
        'logo',
        'email',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the current active settings
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Set as active settings (deactivate others)
     */
    public function setAsActive()
    {
        // Deactivate all other settings
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this one
        $this->update(['is_active' => true]);
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }
}