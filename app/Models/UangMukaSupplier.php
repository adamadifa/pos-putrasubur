<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangMukaSupplier extends Model
{
    use HasFactory;

    protected $table = 'uang_muka_supplier';

    protected $fillable = [
        'no_uang_muka',
        'supplier_id',
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
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    public function penggunaanPembelian()
    {
        return $this->hasMany(PenggunaanUangMukaPembelian::class, 'uang_muka_supplier_id');
    }

    public function pengembalianUang()
    {
        return $this->hasMany(TransaksiKasBank::class, 'referensi_id')
            ->where('referensi_tipe', 'UMS')
            ->where('jenis_transaksi', 'D')
            ->where('no_bukti', 'like', 'RT-UM-SUP%')
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
        $totalDigunakan = $this->penggunaanPembelian->sum('jumlah_digunakan');
        $sisa = $this->jumlah_uang_muka - $totalDigunakan;
        
        $this->update([
            'sisa_uang_muka' => max(0, $sisa),
            'status' => $sisa <= 0 ? 'habis' : 'aktif'
        ]);
    }

    public function getTotalDigunakanAttribute()
    {
        return $this->penggunaanPembelian->sum('jumlah_digunakan');
    }

    public function canBeCancelled()
    {
        return $this->status === 'aktif' && $this->penggunaanPembelian->isEmpty();
    }
}
