<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Services;

class PasswordService
{
    private static $letters         = 'abcdefghijkmnpqrstuvwxyz0123456789';
    private static $special         = '!@#$%^&*+';
    private static $passwordLength  = 12;
    private static $minLetters      = 6;

    /**
     * Make seed
     */
    private static function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $sec + ((float) $usec * 100000);
    }

    /**
     * Return rand digit
     * @param $letter
     * @return string
     */
    private static function randRegister($letter) : string
    {
        mt_srand(self::make_seed());
        return mt_rand(0, 1) ? strtolower($letter) : strtoupper($letter);
    }

    /**
     * Generating password
     * @param int $length(min: self::$minLetters)
     * @param bool $specialChars
     * @return string
     */
    public static function generatePassword(int $length = null, bool $specialChars = true) : string
    {
        $length = $length !== null && $length < self::$minLetters ? self::$minLetters : $length ??
            self::$passwordLength;

        $password = null;
        // If special letters
        self::$letters = $specialChars ? self::$letters . self::$special : self::$letters;
        for ($i = 0; $i < $length; $i++) {
            mt_srand(self::make_seed());
            $password .= self::randRegister(self::$letters[mt_rand(0, strlen(self::$letters) - 1)]);
        }
        $array_mix = preg_split('//', $password, -1, PREG_SPLIT_NO_EMPTY);

        // Mix
        mt_srand(self::make_seed());
        while ($length--) {
            shuffle ($array_mix);
        }

        return implode("", $array_mix);
    }
}
