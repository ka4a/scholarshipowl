<?php
/**
 * Created by PhpStorm.
 * User: r3volut1oner
 * Date: 3/7/16
 * Time: 4:22 PM
 */

namespace ScholarshipOwl\Domain\Payment\Gate2Shop;


class Helper
{

    /**
     * @param null $key
     * @return mixed
     */
    static public function getConfig($key = null)
    {
        $config = \Config::get("scholarshipowl.payment.gate2shop");

        return  ($key === null || !array_key_exists($key, $config)) ? $config : $config[$key];
    }

    /**
     * @return string
     */
    static public function getSecretKey()
    {
        return static::getConfig('secret_key');
    }

    static public function buildChecksum(array $checkSumArray)
    {
        return md5(implode('', $checkSumArray));
    }

}