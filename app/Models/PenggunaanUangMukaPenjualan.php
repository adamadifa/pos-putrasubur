<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanUangMukaPenjualan extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_uang_muka_penjualan';

    protected $fillable = [
        'uang_muka_pelanggan_id',
        'penjualan_id',
        'jumlah_digunakan',
        'tanggal_penggunaan',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_penggunaan' => 'date',
        'jumlah_digunakan' => 'decimal:2',
    ];

    // Relationships
    public function uangMukaPelanggan()
    {
        return $this->belongsTo(UangMukaPelanggan::class, 'uang_muka_pelanggan_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
