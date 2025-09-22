<?php

use App\Helpers\PengaturanUmumHelper;

if (!function_exists('pengaturan_umum')) {
    /**
     * Get pengaturan umum data
     */
    function pengaturan_umum()
    {
        return PengaturanUmumHelper::getActive();
    }
}

if (!function_exists('nama_toko')) {
    /**
     * Get nama toko
     */
    function nama_toko()
    {
        return PengaturanUmumHelper::getNamaToko();
    }
}

if (!function_exists('alamat_toko')) {
    /**
     * Get alamat toko
     */
    function alamat_toko()
    {
        return PengaturanUmumHelper::getAlamat();
    }
}

if (!function_exists('no_telepon_toko')) {
    /**
     * Get nomor telepon toko
     */
    function no_telepon_toko()
    {
        return PengaturanUmumHelper::getNoTelepon();
    }
}

if (!function_exists('email_toko')) {
    /**
     * Get email toko
     */
    function email_toko()
    {
        return PengaturanUmumHelper::getEmail();
    }
}

if (!function_exists('deskripsi_toko')) {
    /**
     * Get deskripsi toko
     */
    function deskripsi_toko()
    {
        return PengaturanUmumHelper::getDeskripsi();
    }
}

if (!function_exists('logo_toko')) {
    /**
     * Get logo URL toko
     */
    function logo_toko()
    {
        return PengaturanUmumHelper::getLogoUrl();
    }
}

if (!function_exists('has_logo_toko')) {
    /**
     * Check if toko has logo
     */
    function has_logo_toko()
    {
        return PengaturanUmumHelper::hasLogo();
    }
}

if (!function_exists('clear_pengaturan_cache')) {
    /**
     * Clear pengaturan cache
     */
    function clear_pengaturan_cache()
    {
        return PengaturanUmumHelper::clearCache();
    }
}
