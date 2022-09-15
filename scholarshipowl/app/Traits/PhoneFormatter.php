<?php

namespace App\Traits;

trait PhoneFormatter
{
    /**
     * @param string $phone
     * @return string
     */
    public function unifyPhoneFormat(string $phone): string
    {
        // If we have not entered a phone number just return empty string
        if (empty($phone)) {
            return '';
        }

        $hasCountryCode = strpos($phone, '+') === 0 && !(strpos($phone, '+(') === 0);

        // assume that USA phones stored in format (xxx) xxx - xxxx
        $isUs = strpos($phone, '(') !== false;

        $prefix = $isUs ? '+1' : '+';
        if ($hasCountryCode) {
            $prefix = substr($phone, 0, 2);
            $phone = substr($phone, 2);
        }

        // Strip out any extra characters that we do not need only keep letters and numbers
        $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
        $phone = $prefix.$phone;

        return $phone;
    }

    /**
     * Return default format of US phone is $isUSA - true
     *
     *  +11231231234 ==> (123) 123-1234
     *
     * @param string $unifiedPhone
     * @param bool   $isUSA
     *
     * @return string
     */
    public function toPhoneFormat(string $unifiedPhone, bool $isUSA = true): string
    {
        if ($isUSA) {
            $unifiedPhone = str_replace('+1', '', $unifiedPhone);
            $unifiedPhone = preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2 - $3", $unifiedPhone);
        }

        return $unifiedPhone;
    }
}
