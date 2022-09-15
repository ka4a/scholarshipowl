<?php namespace App\Events\Account;

use App\Events\Event;

class DeleteTestAccountEvent extends Event
{
    /**
     * @var int
     */
    protected $accountId;

    /**
     * @param int $accountId
     */
    public function __construct($accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}
