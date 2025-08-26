<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasBank extends Model
{
    use HasFactory;

    protected $table = 'kas_bank';

    protected $fillable = [
        'kode',
        'nama',
        'no_rekening'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Validation rules
    public static $rules = [
        'kode' => 'required|string|max:20|unique:kas_bank,kode',
        'nama' => 'required|string|max:100',
        'no_rekening' => 'nullable|string|max:50'
    ];

    public static $updateRules = [
        'kode' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'no_rekening' => 'nullable|string|max:50'
    ];

    // Scope untuk data aktif
    public function scopeActive($query)
    {
        return $query;
    }

    // Accessor untuk format kode
    public function getKodeFormattedAttribute()
    {
        return strtoupper($this->kode);
    }

    // Accessor untuk format nama
    public function getNamaFormattedAttribute()
    {
        return ucwords(strtolower($this->nama));
    }
}
