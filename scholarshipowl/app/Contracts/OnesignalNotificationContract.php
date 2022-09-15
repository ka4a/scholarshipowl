<?php namespace App\Contracts;

use App\Entity\Account;

interface OnesignalNotificationContract
{
    /**
     * @return int
     */
    public function getType() : int;

    /**
     * @param string  $content
     * @param Account $account
     *
     * @return string
     */
    public function mapTags(string $content, Account $account) : string;

    /**
     * @return array
     */
    public function getData() : array;
}
