<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSaldoAwalProduk extends Model
{
    use HasFactory;

    protected $table = 'detail_saldo_awal_produks';

    protected $fillable = [
        'saldo_awal_produk_id',
        'produk_id',
        'saldo_awal',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
    ];

    /**
     * Get the saldo awal produk that owns the detail.
     */
    public function saldoAwalProduk(): BelongsTo
    {
        return $this->belongsTo(SaldoAwalProduk::class);
    }

    /**
     * Get the produk that owns the detail.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
