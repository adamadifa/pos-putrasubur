<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'no_faktur',
        'supplier_id',
        'tanggal',
        'subtotal',
        'diskon',
        'total',
        'status_pembayaran',
        'jenis_transaksi',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected $appends = ['encrypted_id'];

    // Accessor untuk encrypted ID
    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }

    public function pembayaranPembelian()
    {
        return $this->hasMany(PembayaranPembelian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getSisaPembayaranAttribute()
    {
        $totalDibayar = $this->pembayaranPembelian->sum('jumlah_bayar');
        return $this->total - $totalDibayar;
    }

    public function getTotalDibayarAttribute()
    {
        return $this->pembayaranPembelian->sum('jumlah_bayar');
    }

    public function getTotalSetelahDiskonAttribute()
    {
        return $this->total - $this->diskon;
    }

    public function getStatusPembayaranDisplayAttribute()
    {
        switch ($this->status_pembayaran) {
            case 'lunas':
                return 'Lunas';
            case 'dp':
                return 'DP';
            case 'belum_bayar':
                return 'Belum Bayar';
            default:
                return $this->status_pembayaran;
        }
    }

    public function getJenisTransaksiDisplayAttribute()
    {
        switch ($this->jenis_transaksi) {
            case 'tunai':
                return 'Tunai';
            case 'kredit':
                return 'Kredit';
            default:
                return $this->jenis_transaksi;
        }
    }

    // Scopes
    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status_pembayaran', ['belum_bayar', 'dp']);
    }

    public function scopeTunai($query)
    {
        return $query->where('jenis_transaksi', 'tunai');
    }

    public function scopeKredit($query)
    {
        return $query->where('jenis_transaksi', 'kredit');
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
