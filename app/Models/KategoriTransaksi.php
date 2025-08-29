<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTransaksi extends Model
{
    use HasFactory;

    protected $table = 'kategori_transaksi';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi'
    ];

    // Relationships
    public function transaksiKasBank()
    {
        return $this->hasMany(TransaksiKasBank::class, 'kategori_transaksi_id');
    }
}

