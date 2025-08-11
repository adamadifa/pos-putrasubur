<?php

if (!function_exists('formatRupiah')) {
    /**
     * Format angka ke format rupiah Indonesia (1.000,30)
     * 
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function formatRupiah($number, $decimals = 0)
    {
        if ($number === null || $number === '') {
            return '0';
        }

        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('formatNumber')) {
    /**
     * Format angka dengan pemisah ribuan (titik) dan desimal (koma)
     * 
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function formatNumber($number, $decimals = 0)
    {
        if ($number === null || $number === '') {
            return '0';
        }

        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format angka ke format mata uang rupiah lengkap
     * 
     * @param float|int $number
     * @param int $decimals
     * @param bool $showSymbol
     * @return string
     */
    function formatCurrency($number, $decimals = 0, $showSymbol = true)
    {
        if ($number === null || $number === '') {
            $formatted = '0';
        } else {
            $formatted = number_format($number, $decimals, ',', '.');
        }

        return $showSymbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('parseNumber')) {
    /**
     * Parse string format Indonesia ke float
     * Mengubah "1.000,50" menjadi 1000.50
     * 
     * @param string $formattedNumber
     * @return float
     */
    function parseNumber($formattedNumber)
    {
        if (empty($formattedNumber)) {
            return 0;
        }

        // Hapus semua karakter selain angka, titik, koma, dan minus
        $cleaned = preg_replace('/[^0-9.,-]/', '', $formattedNumber);

        // Ganti koma dengan titik untuk desimal
        $cleaned = str_replace(',', '.', $cleaned);

        // Jika ada lebih dari satu titik, yang terakhir adalah desimal
        $parts = explode('.', $cleaned);
        if (count($parts) > 2) {
            $decimal = array_pop($parts);
            $integer = implode('', $parts);
            $cleaned = $integer . '.' . $decimal;
        }

        return (float) $cleaned;
    }
}

if (!function_exists('formatPercentage')) {
    /**
     * Format angka ke persentase
     * 
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function formatPercentage($number, $decimals = 1)
    {
        if ($number === null || $number === '') {
            return '0%';
        }

        return number_format($number, $decimals, ',', '.') . '%';
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Format ukuran file ke format yang mudah dibaca
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function formatFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('formatQuantity')) {
    /**
     * Format quantity dengan satuan
     * 
     * @param float|int $quantity
     * @param string $unit
     * @param int $decimals
     * @return string
     */
    function formatQuantity($quantity, $unit = '', $decimals = 2)
    {
        if ($quantity === null || $quantity === '') {
            $formatted = '0';
        } else {
            $formatted = number_format($quantity, $decimals, ',', '.');
        }

        return $unit ? $formatted . ' ' . $unit : $formatted;
    }
}
