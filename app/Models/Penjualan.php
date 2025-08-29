<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Penjualan extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Cascade delete saat model dihapus
        static::deleting(function ($penjualan) {
            // Log sebelum penghapusan untuk audit trail
            Log::info('Penjualan akan dihapus', [
                'penjualan_id' => $penjualan->id,
                'no_faktur' => $penjualan->no_faktur,
                'total' => $penjualan->total,
                'user_id' => auth()->id(),
                'deleted_at' => now()
            ]);
        });
    }

    protected $table = 'penjualan';

    protected $fillable = [
        'no_faktur',
        'tanggal',
        'pelanggan_id',
        'total',
        'diskon',
        'status_pembayaran',
        'jatuh_tempo',
        'kasir_id',
        'jenis_transaksi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
        'total' => 'decimal:2',
        'diskon' => 'decimal:2',
    ];

    /**
     * Relationship dengan Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Relationship dengan User (Kasir)
     */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    /**
     * Relationship dengan DetailPenjualan
     */
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    /**
     * Relationship dengan PembayaranPenjualan
     */
    public function pembayaranPenjualan()
    {
        return $this->hasMany(PembayaranPenjualan::class, 'penjualan_id');
    }

    /**
     * Get total setelah diskon
     */
    public function getTotalSetelahDiskonAttribute()
    {
        return $this->total - $this->diskon;
    }

    /**
     * Get status pembayaran display
     */
    public function getStatusPembayaranDisplayAttribute()
    {
        $status = [
            'lunas' => 'Lunas',
            'dp' => 'DP',
            'angsuran' => 'Angsuran',
            'belum_bayar' => 'Belum Bayar'
        ];

        return $status[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    /**
     * Get total yang sudah dibayar
     */
    public function getTotalDibayarAttribute()
    {
        return $this->pembayaranPenjualan()->sum('jumlah_bayar');
    }

    /**
     * Get sisa pembayaran
     */
    public function getSisaPembayaranAttribute()
    {
        return $this->total_setelah_diskon - $this->total_dibayar;
    }

    /**
     * Check if payment is complete
     */
    public function isLunas()
    {
        return $this->sisa_pembayaran <= 0;
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
}
