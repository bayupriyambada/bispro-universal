<?php

namespace App\Helpers;

use DateTime;
use DateTimeZone;

class IndonesiaTimeHelpers
{
    public static function getIndonesiaTime($value)
    {
        $location = 'Asia/Jakarta';
        $time = Date('H:i:s');
        $date = Date('Y-m-d');
        $dateTime = new DateTime($date . ' ' . $time);
        $dateTime->setTimezone(new DateTimeZone($location));
        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function getDateIndonesia($value)
    {
        $location = 'Asia/Jakarta';
        $date = new DateTime($value);
        $date->setTimezone(new DateTimeZone($location));
        return $date->format('Y-m-d');
    }
    public static function getTimeIndonesia($value)
    {
        $location = 'Asia/Jakarta';
        $time = new DateTime($value);
        $time->setTimezone(new DateTimeZone($location));
        return $time->format('H:i:s');
    }
}
