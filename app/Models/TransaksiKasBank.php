<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKasBank extends Model
{
    use HasFactory;

    protected $table = 'transaksi_kas_bank';

    protected $fillable = [
        'kas_bank_id',
        'tanggal',
        'no_bukti',
        'jenis_transaksi',
        'kategori_transaksi',
        'referensi_id',
        'referensi_tipe',
        'jumlah',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    // Relationships
    public function kasBank()
    {
        return $this->belongsTo(KasBank::class, 'kas_bank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function pembayaranPenjualan()
    {
        return $this->belongsTo(PembayaranPenjualan::class, 'referensi_id')
            ->where('referensi_tipe', 'PPJ');
    }

    public function pembayaranPembelian()
    {
        return $this->belongsTo(PembayaranPembelian::class, 'referensi_id')
            ->where('referensi_tipe', 'PPB');
    }

    // Accessors
    public function getJenisTransaksiDisplayAttribute()
    {
        return $this->jenis_transaksi === 'D' ? 'Debet' : 'Kredit';
    }

    public function getKategoriTransaksiDisplayAttribute()
    {
        $kategori = [
            'PJ' => 'Penjualan',
            'PB' => 'Pembelian',
            'MN' => 'Manual',
            'TF' => 'Transfer'
        ];
        return $kategori[$this->kategori_transaksi] ?? $this->kategori_transaksi;
    }

    public function getReferensiTipeDisplayAttribute()
    {
        $tipe = [
            'PPJ' => 'Pembayaran Penjualan',
            'PPB' => 'Pembayaran Pembelian',
            'MN' => 'Manual'
        ];
        return $tipe[$this->referensi_tipe] ?? $this->referensi_tipe;
    }

    // Scopes
    public function scopeByKasBank($query, $kasBankId)
    {
        return $query->where('kas_bank_id', $kasBankId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    public function scopeByJenisTransaksi($query, $jenis)
    {
        return $query->where('jenis_transaksi', $jenis);
    }

    public function scopeByKategoriTransaksi($query, $kategori)
    {
        return $query->where('kategori_transaksi', $kategori);
    }
}
