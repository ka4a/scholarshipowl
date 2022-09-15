<?php

namespace App\Events\Account;

use Illuminate\Support\Facades\Event;

class ElbCacheAccountEvent extends Event
{
    /**
     * @var AccountEvent
     */
    protected $accountEvent;

    /**
     * AccountEvent constructor.
     * @param $account
     * @param null $referer
     */
    public function __construct(AccountEvent $accountEvent)
    {
        $this->accountEvent = $accountEvent;
    }

    /**
     * @return AccountEvent
     */
    public function getAccountEvent()
    {
        return $this->accountEvent;
    }
}
