<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiKasBank;

class UangMukaPelanggan extends Model
{
    use HasFactory;

    protected $table = 'uang_muka_pelanggan';

    protected $fillable = [
        'no_uang_muka',
        'pelanggan_id',
        'tanggal',
        'jumlah_uang_muka',
        'sisa_uang_muka',
        'metode_pembayaran',
        'kas_bank_id',
        'status',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_uang_muka' => 'decimal:2',
        'sisa_uang_muka' => 'decimal:2',
    ];

    protected $appends = ['encrypted_id'];

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    public function penggunaanPenjualan()
    {
        return $this->hasMany(PenggunaanUangMukaPenjualan::class, 'uang_muka_pelanggan_id');
    }

    public function pengembalianUang()
    {
        return $this->hasMany(TransaksiKasBank::class, 'referensi_id')
            ->where('referensi_tipe', 'UMP')
            ->where('jenis_transaksi', 'K')
            ->where('no_bukti', 'like', 'RT-UM-PEL%')
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    public function getStatusDisplayAttribute()
    {
        $status = [
            'aktif' => 'Aktif',
            'habis' => 'Habis',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $status[$this->status] ?? $this->status;
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeHabis($query)
    {
        return $query->where('status', 'habis');
    }

    // Helper methods
    public function updateSisa()
    {
        $totalDigunakan = $this->penggunaanPenjualan->sum('jumlah_digunakan');
        $sisa = $this->jumlah_uang_muka - $totalDigunakan;
        
        $this->update([
            'sisa_uang_muka' => max(0, $sisa),
            'status' => $sisa <= 0 ? 'habis' : 'aktif'
        ]);
    }

    public function getTotalDigunakanAttribute()
    {
        return $this->penggunaanPenjualan->sum('jumlah_digunakan');
    }

    public function canBeCancelled()
    {
        return $this->status === 'aktif' && $this->penggunaanPenjualan->isEmpty();
    }
}
