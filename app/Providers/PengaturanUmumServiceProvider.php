<?php

namespace App\Providers;

use App\Models\PengaturanUmum;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class PengaturanUmumServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share pengaturan umum to all views
        View::composer('*', function ($view) {
            $pengaturan = Cache::remember('pengaturan_umum_aktif', 3600, function () {
                return PengaturanUmum::getActive();
            });

            // Fallback data jika belum ada pengaturan
            $pengaturanFallback = [
                'nama_toko' => 'Toko Saya',
                'alamat' => null,
                'no_telepon' => null,
                'email' => null,
                'deskripsi' => null,
                'logo' => null,
                'logo_url' => null,
                'foto_toko' => null,
                'foto_toko_url' => null,
            ];

            // Merge dengan data fallback
            $pengaturanData = $pengaturan ? $pengaturan->toArray() : $pengaturanFallback;

            // Tambahkan logo_url jika ada logo
            if ($pengaturan && $pengaturan->logo) {
                $pengaturanData['logo_url'] = $pengaturan->logo_url;
            }

            // Tambahkan foto_toko_url jika ada foto_toko
            if ($pengaturan && $pengaturan->foto_toko) {
                $pengaturanData['foto_toko_url'] = $pengaturan->foto_toko_url;
            }

            $view->with('pengaturanUmum', (object) $pengaturanData);
        });
    }
}
