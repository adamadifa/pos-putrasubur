<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'qty',
        'harga',
        'subtotal',
        'discount',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    /**
     * Relationship dengan Penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    /**
     * Relationship dengan Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
