<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori_id',
        'satuan_id',
        'harga_jual',
        'stok',
        'stok_minimal',
        'foto',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'stok' => 'decimal:2',
        'stok_minimal' => 'decimal:2',
    ];

    /**
     * Relationship dengan KategoriProduk
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    /**
     * Relationship dengan Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    /**
     * Accessor untuk status stok
     */
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'habis';
        } elseif ($this->stok <= $this->stok_minimal) {
            return 'menipis';
        } else {
            return 'tersedia';
        }
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    /**
     * Scope untuk produk tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope untuk produk menipis
     */
    public function scopeMenipis($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0);
    }

    /**
     * Scope untuk produk habis
     */
    public function scopeHabis($query)
    {
        return $query->where('stok', '<=', 0);
    }
}
