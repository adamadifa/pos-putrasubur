<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanUangMukaPembelian extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_uang_muka_pembelian';

    protected $fillable = [
        'uang_muka_supplier_id',
        'pembelian_id',
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
    public function uangMukaSupplier()
    {
        return $this->belongsTo(UangMukaSupplier::class, 'uang_muka_supplier_id');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
