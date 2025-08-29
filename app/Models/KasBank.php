<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasBank extends Model
{
    use HasFactory;

    protected $table = 'kas_bank';

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'image',
        'no_rekening',
        'saldo_awal',
        'saldo_terkini'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'saldo_awal' => 'decimal:2',
        'saldo_terkini' => 'decimal:2',
    ];

    // Validation rules
    public static $rules = [
        'kode' => 'required|string|max:20|unique:kas_bank,kode',
        'nama' => 'required|string|max:100',
        'jenis' => 'required|in:KAS,BANK',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'no_rekening' => 'nullable|string|max:50'
    ];

    public static $updateRules = [
        'kode' => 'required|string|max:20',
        'nama' => 'required|string|max:100',
        'jenis' => 'required|in:KAS,BANK',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'no_rekening' => 'nullable|string|max:50'
    ];

    // Scope untuk data aktif
    public function scopeActive($query)
    {
        return $query;
    }

    // Accessor untuk format kode
    public function getKodeFormattedAttribute()
    {
        return strtoupper($this->kode);
    }

    // Accessor untuk format nama
    public function getNamaFormattedAttribute()
    {
        return ucwords(strtolower($this->nama));
    }

    /**
     * Get image URL attribute
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Get jenis display attribute
     */
    public function getJenisDisplayAttribute()
    {
        return $this->jenis ?: 'KAS';
    }

    // Relationships
    public function transaksiKasBank()
    {
        return $this->hasMany(TransaksiKasBank::class, 'kas_bank_id');
    }

    public function pembayaranPenjualan()
    {
        return $this->hasMany(PembayaranPenjualan::class, 'kas_bank_id');
    }

    public function pembayaranPembelian()
    {
        return $this->hasMany(PembayaranPembelian::class, 'kas_bank_id');
    }

    // Methods untuk update saldo
    public function updateSaldo($jumlah, $jenisTransaksi = 'D')
    {
        $saldoSebelum = $this->saldo_terkini;

        if ($jenisTransaksi === 'D') {
            // Debet = menambah saldo
            $this->saldo_terkini += $jumlah;
        } else {
            // Kredit = mengurangi saldo
            $this->saldo_terkini -= $jumlah;
        }

        $this->save();

        return [
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $this->saldo_terkini
        ];
    }
}
