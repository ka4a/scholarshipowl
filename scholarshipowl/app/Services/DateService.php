<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Services;

class DateService
{
    protected static $aFormats = [
        'd/m/Y',
        'm/d/Y',
        'Y/m/d',
        'Y/d/m',
    ];

    /**
     * @param string $date
     * @return string|null
     */
    public static function getFormat(string $date)
    {
        foreach (self::$aFormats as $aFormat) {
            $aDate = date_parse_from_format($aFormat, $date);
            if ($aDate['warning_count'] == 0) {
                return $aFormat;
            }
        }
        return null;
    }
}