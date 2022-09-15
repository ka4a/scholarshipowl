<?php

if (!function_exists('phone_format')) {
    /**
     * @param string $phone
     * @return string
     */
    function phone_format($phone) {
        $phone = preg_replace('/^\+1/', '', $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return $phone;
    }
}

if (!function_exists('phone_format_us')) {
    /**
     * @param string $phone
     * @return string
     */
    function phone_format_us($phone) {
        $phone = phone_format($phone);
        $areaCode = substr($phone, 0, 3);
        $nextTree = substr($phone, 3, 3);
        $last = substr($phone, 6);
        return sprintf('+1 (%s) %s-%s', $areaCode, $nextTree, $last);
    }
}
