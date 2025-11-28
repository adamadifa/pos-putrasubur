<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    |
    | Kumpulan flag sederhana untuk mengaktifkan/menonaktifkan fitur tertentu
    | tanpa perlu menghapus logika asli. Atur nilai melalui environment variable
    | agar bisa diganti sewaktu-waktu.
    |
    */

    'restrict_pembelian_delete_same_day' => env('RESTRICT_PEMBELIAN_DELETE_SAME_DAY', false),
];

