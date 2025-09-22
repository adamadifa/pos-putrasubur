<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaldoAwalProduk extends Model
{
    use HasFactory;

    protected $table = 'saldo_awal_produk';

    protected $fillable = [
        'periode_bulan',
        'periode_tahun',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer'
    ];

    /**
     * Get the user that created the saldo awal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the detail saldo awal produk.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailSaldoAwalProduk::class);
    }

    /**
     * Get saldo awal untuk produk tertentu pada periode tertentu
     */
    public static function getSaldoAwal($produkId, $bulan, $tahun)
    {
        $saldoAwal = self::whereHas('details', function ($query) use ($produkId) {
            $query->where('produk_id', $produkId);
        })
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->first();

        if ($saldoAwal) {
            $detail = $saldoAwal->details()->where('produk_id', $produkId)->first();
            return $detail ? $detail->saldo_awal : 0;
        }

        return 0;
    }

    /**
     * Check if saldo awal can be edited/deleted
     * (bisa diedit jika belum ada saldo awal bulan berikutnya)
     */
    public static function canEdit($bulan, $tahun)
    {
        // Hitung bulan berikutnya
        $tanggalPeriode = \Carbon\Carbon::create($tahun, $bulan, 1);
        $bulanBerikutnya = $tanggalPeriode->copy()->addMonth();

        // Cek apakah sudah ada saldo awal bulan berikutnya
        $saldoAwalBulanBerikutnya = self::where('periode_bulan', $bulanBerikutnya->month)
            ->where('periode_tahun', $bulanBerikutnya->year)
            ->exists();

        return !$saldoAwalBulanBerikutnya;
    }

    /**
     * Get nama bulan
     */
    public function getBulanNamaAttribute()
    {
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $bulanList[$this->periode_bulan] ?? '';
    }

    /**
     * Get periode lengkap
     */
    public function getPeriodeLengkapAttribute()
    {
        return $this->bulan_nama . ' ' . $this->periode_tahun;
    }
}
