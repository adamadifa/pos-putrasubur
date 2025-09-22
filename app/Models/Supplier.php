<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'kode_supplier',
        'nama',
        'alamat',
        'telepon',
        'email',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Accessor untuk encrypted ID
    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    // Relationships
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status', false);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    public function getTotalTransaksiAttribute()
    {
        return $this->pembelian()->count();
    }

    public function getTotalNilaiTransaksiAttribute()
    {
        return $this->pembelian()->sum('total');
    }

    public function getRataRataTransaksiAttribute()
    {
        $totalTransaksi = $this->total_transaksi;
        if ($totalTransaksi > 0) {
            return $this->total_nilai_transaksi / $totalTransaksi;
        }
        return 0;
    }

    // Accessors untuk halaman show
    public function getTotalPembelianAttribute()
    {
        return $this->pembelian()->count();
    }

    public function getTotalNilaiPembelianAttribute()
    {
        return $this->pembelian()->sum('total');
    }

    public function getRataRataPembelianAttribute()
    {
        $totalPembelian = $this->total_pembelian;
        if ($totalPembelian > 0) {
            return $this->total_nilai_pembelian / $totalPembelian;
        }
        return 0;
    }

    public function getTotalHutangAttribute()
    {
        return $this->pembelian()->sum('total');
    }

    public function getTotalDibayarAttribute()
    {
        return $this->pembelian()->with('pembayaranPembelian')->get()->sum(function ($pembelian) {
            return $pembelian->pembayaranPembelian->sum('jumlah_bayar');
        });
    }

    public function getSisaHutangAttribute()
    {
        return $this->total_hutang - $this->total_dibayar;
    }

    public function getPembelianBelumLunasAttribute()
    {
        return $this->pembelian()->where('status_pembayaran', '!=', 'lunas')->count();
    }
}
