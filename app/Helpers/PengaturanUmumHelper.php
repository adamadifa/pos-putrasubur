<?php

namespace App\Helpers;

use App\Models\PengaturanUmum;
use Illuminate\Support\Facades\Cache;

class PengaturanUmumHelper
{
    /**
     * Get active pengaturan umum with cache
     */
    public static function getActive()
    {
        return Cache::remember('pengaturan_umum_aktif', 3600, function () {
            return PengaturanUmum::getActive();
        });
    }

    /**
     * Get nama toko
     */
    public static function getNamaToko()
    {
        $pengaturan = self::getActive();
        return $pengaturan ? $pengaturan->nama_toko : 'Toko Saya';
    }

    /**
     * Get alamat toko
     */
    public static function getAlamat()
    {
        $pengaturan = self::getActive();
        return $pengaturan ? $pengaturan->alamat : null;
    }

    /**
     * Get nomor telepon
     */
    public static function getNoTelepon()
    {
        $pengaturan = self::getActive();
        return $pengaturan ? $pengaturan->no_telepon : null;
    }

    /**
     * Get email toko
     */
    public static function getEmail()
    {
        $pengaturan = self::getActive();
        return $pengaturan ? $pengaturan->email : null;
    }

    /**
     * Get deskripsi toko
     */
    public static function getDeskripsi()
    {
        $pengaturan = self::getActive();
        return $pengaturan ? $pengaturan->deskripsi : null;
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl()
    {
        $pengaturan = self::getActive();
        return $pengaturan && $pengaturan->logo ? $pengaturan->logo_url : null;
    }

    /**
     * Check if logo exists
     */
    public static function hasLogo()
    {
        $pengaturan = self::getActive();
        return $pengaturan && $pengaturan->logo;
    }

    /**
     * Clear cache (call when pengaturan is updated)
     */
    public static function clearCache()
    {
        Cache::forget('pengaturan_umum_aktif');
    }

    /**
     * Get all pengaturan data as array
     */
    public static function getAllData()
    {
        $pengaturan = self::getActive();

        if (!$pengaturan) {
            return [
                'nama_toko' => 'Toko Saya',
                'alamat' => null,
                'no_telepon' => null,
                'email' => null,
                'deskripsi' => null,
                'logo' => null,
                'logo_url' => null,
            ];
        }

        return [
            'nama_toko' => $pengaturan->nama_toko,
            'alamat' => $pengaturan->alamat,
            'no_telepon' => $pengaturan->no_telepon,
            'email' => $pengaturan->email,
            'deskripsi' => $pengaturan->deskripsi,
            'logo' => $pengaturan->logo,
            'logo_url' => $pengaturan->logo_url,
        ];
    }
}
