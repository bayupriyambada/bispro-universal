<?php

namespace App\Helpers;

class FormatRupiahHelpers
{
    public static function rupiah($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }
}
