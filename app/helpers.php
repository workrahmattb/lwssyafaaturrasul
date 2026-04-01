<?php

if (!function_exists('formatRupiah')) {
    /**
     * Format angka menjadi format Rupiah yang rapi
     * Contoh: Rp 1.000.000
     *
     * @param int|float $amount
     * @return string
     */
    function formatRupiah($amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }
}

if (!function_exists('formatRupiahCompact')) {
    /**
     * Format angka menjadi format Rupiah yang compact untuk angka besar
     * Contoh: 1.5Jt, 2.3M
     *
     * @param int|float $amount
     * @return string
     */
    function formatRupiahCompact($amount): string
    {
        $amount = (float) $amount;
        
        if ($amount >= 1000000000) {
            return 'Rp ' . number_format($amount / 1000000000, 1, ',', '.') . 'M';
        }
        
        if ($amount >= 1000000) {
            return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . 'Jt';
        }
        
        if ($amount >= 1000) {
            return 'Rp ' . number_format($amount / 1000, 1, ',', '.') . 'rb';
        }
        
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
