<?php

// Test file untuk memvalidasi global access pengaturan umum
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST GLOBAL ACCESS PENGATURAN UMUM ===\n\n";

// Test 1: Cek apakah helper functions tersedia
echo "1. Testing Helper Functions:\n";
echo "   - nama_toko(): " . (function_exists('nama_toko') ? 'OK' : 'FAILED') . "\n";
echo "   - alamat_toko(): " . (function_exists('alamat_toko') ? 'OK' : 'FAILED') . "\n";
echo "   - no_telepon_toko(): " . (function_exists('no_telepon_toko') ? 'OK' : 'FAILED') . "\n";
echo "   - email_toko(): " . (function_exists('email_toko') ? 'OK' : 'FAILED') . "\n";
echo "   - logo_toko(): " . (function_exists('logo_toko') ? 'OK' : 'FAILED') . "\n";
echo "   - has_logo_toko(): " . (function_exists('has_logo_toko') ? 'OK' : 'FAILED') . "\n\n";

// Test 2: Test fallback behavior (tanpa data di database)
echo "2. Testing Fallback Behavior (no data in DB):\n";
try {
    echo "   - nama_toko(): " . nama_toko() . "\n";
    echo "   - alamat_toko(): " . (alamat_toko() ?: 'NULL') . "\n";
    echo "   - no_telepon_toko(): " . (no_telepon_toko() ?: 'NULL') . "\n";
    echo "   - email_toko(): " . (email_toko() ?: 'NULL') . "\n";
    echo "   - logo_toko(): " . (logo_toko() ?: 'NULL') . "\n";
    echo "   - has_logo_toko(): " . (has_logo_toko() ? 'true' : 'false') . "\n\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n\n";
}

// Test 3: Test dengan data seeder
echo "3. Testing with Seeder Data:\n";
try {
    // Run seeder
    $seeder = new \Database\Seeders\PengaturanUmumSeeder();
    $seeder->run();

    // Clear cache untuk force reload
    if (function_exists('clear_pengaturan_cache')) {
        clear_pengaturan_cache();
    }

    echo "   - nama_toko(): " . nama_toko() . "\n";
    echo "   - alamat_toko(): " . (alamat_toko() ?: 'NULL') . "\n";
    echo "   - no_telepon_toko(): " . (no_telepon_toko() ?: 'NULL') . "\n";
    echo "   - email_toko(): " . (email_toko() ?: 'NULL') . "\n";
    echo "   - logo_toko(): " . (logo_toko() ?: 'NULL') . "\n";
    echo "   - has_logo_toko(): " . (has_logo_toko() ? 'true' : 'false') . "\n\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n\n";
}

// Test 4: Test Service Provider
echo "4. Testing Service Provider:\n";
try {
    $provider = new \App\Providers\PengaturanUmumServiceProvider(app());
    echo "   - Service Provider loaded: OK\n";
    echo "   - Cache key exists: " . (app('cache')->has('pengaturan_umum_aktif') ? 'YES' : 'NO') . "\n\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n\n";
}

echo "=== TEST COMPLETED ===\n";
