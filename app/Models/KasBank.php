<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'saldo_terkini',
        'status_card_payment'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'saldo_awal' => 'decimal:2',
        'saldo_terkini' => 'decimal:2',
        'status_card_payment' => 'boolean',
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

    // Scope untuk bank dengan card payment aktif
    public function scopeCardPaymentActive($query)
    {
        return $query->where('status_card_payment', 1);
    }

    // Method untuk mendapatkan bank aktif untuk card payment
    public static function getActiveCardPaymentBank()
    {
        return self::cardPaymentActive()->first();
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

    public function saldoAwalBulanan()
    {
        return $this->hasMany(SaldoAwalBulanan::class, 'kas_bank_id');
    }

    // Methods untuk update saldo
    public function updateSaldo($jumlah, $jenisTransaksi = 'D')
    {
        if ($jenisTransaksi === 'D') {
            // Debet = menambah saldo
            $this->saldo_terkini += $jumlah;
        } else {
            // Kredit = mengurangi saldo
            $this->saldo_terkini -= $jumlah;
        }

        $this->save();

        return [
            'saldo_terkini' => $this->saldo_terkini
        ];
    }

    // Method untuk menghitung saldo terkini berdasarkan saldo awal bulanan
    public function getSaldoTerkiniAttribute()
    {
        $today = now();
        $bulan = $today->month;
        $tahun = $today->year;

        // Ambil saldo awal bulan ini
        $saldoAwal = SaldoAwalBulanan::getSaldoAwal($this->id, $bulan, $tahun);

        // Hitung total transaksi bulan ini sampai hari ini
        $totalTransaksi = $this->transaksiKasBank()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->whereDate('tanggal', '<=', $today)
            ->get()
            ->sum(function ($transaksi) {
                return $transaksi->jenis_transaksi == 'D' ? $transaksi->jumlah : -$transaksi->jumlah;
            });

        return $saldoAwal + $totalTransaksi;
    }

    // Method untuk mendapatkan saldo pada tanggal tertentu
    public function getSaldoPadaTanggal($tanggal)
    {
        $date = Carbon::parse($tanggal);
        $bulan = $date->month;
        $tahun = $date->year;

        // Ambil saldo awal bulan tersebut
        $saldoAwal = SaldoAwalBulanan::getSaldoAwal($this->id, $bulan, $tahun);

        // Hitung total transaksi bulan tersebut sampai tanggal yang diminta
        $totalTransaksi = $this->transaksiKasBank()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->whereDate('tanggal', '<=', $date)
            ->get()
            ->sum(function ($transaksi) {
                return $transaksi->jenis_transaksi == 'D' ? $transaksi->jumlah : -$transaksi->jumlah;
            });

        return $saldoAwal + $totalTransaksi;
    }
}
