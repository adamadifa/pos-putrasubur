<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SaldoAwalBulanan extends Model
{
    use HasFactory;

    protected $table = 'saldo_awal_bulanan';

    protected $fillable = [
        'kas_bank_id',
        'periode_bulan',
        'periode_tahun',
        'saldo_awal',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'saldo_awal' => 'decimal:2',
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

    // Accessors
    public function getPeriodeDisplayAttribute()
    {
        $bulan = [
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

        return $bulan[$this->periode_bulan] . ' ' . $this->periode_tahun;
    }

    public function getPeriodeKeyAttribute()
    {
        return $this->periode_tahun . '-' . str_pad($this->periode_bulan, 2, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopeByKasBank($query, $kasBankId)
    {
        return $query->where('kas_bank_id', $kasBankId);
    }

    public function scopeByPeriode($query, $bulan, $tahun)
    {
        return $query->where('periode_bulan', $bulan)->where('periode_tahun', $tahun);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('periode_tahun', $tahun);
    }

    // Methods
    public static function getSaldoAwal($kasBankId, $bulan, $tahun)
    {
        return static::where('kas_bank_id', $kasBankId)
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->value('saldo_awal') ?? 0;
    }

    public static function canEdit($kasBankId, $bulan, $tahun)
    {
        // Cek apakah ada saldo awal bulan berikutnya
        $nextMonth = Carbon::create($tahun, $bulan, 1)->addMonth();
        $nextMonthSaldo = static::where('kas_bank_id', $kasBankId)
            ->where('periode_bulan', $nextMonth->month)
            ->where('periode_tahun', $nextMonth->year)
            ->exists();

        return !$nextMonthSaldo;
    }
}
