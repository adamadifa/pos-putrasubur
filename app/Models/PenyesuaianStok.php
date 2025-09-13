<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenyesuaianStok extends Model
{
    use HasFactory;

    protected $table = 'penyesuaian_stok';

    protected $fillable = [
        'kode_penyesuaian',
        'tanggal_penyesuaian',
        'produk_id',
        'stok_sebelum',
        'jumlah_penyesuaian',
        'stok_sesudah',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_penyesuaian' => 'date',
    ];

    /**
     * Get the user that owns the penyesuaian stok.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the produk that owns the penyesuaian stok.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Generate kode penyesuaian.
     */
    public static function generateKodePenyesuaian(): string
    {
        $lastRecord = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastRecord ? (int) substr($lastRecord->kode_penyesuaian, 3) : 0;
        $newNumber = $lastNumber + 1;

        return 'PS' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
