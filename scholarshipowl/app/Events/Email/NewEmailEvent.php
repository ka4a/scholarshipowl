<?php

namespace App\Events\Email;

use App\Entity\Account;
use App\Events\Event;
use App\Services\Mailbox\Email;

class NewEmailEvent extends Event
{
	/**
	 * @var Account $account
	 */
    public $account;

    /* @var Email $email */
    public $email;

	/**
	 * CreateAccountEvent constructor.
	 *
	 * @param Account $account
     * @param Email $email
	 */
    public function __construct(Account $account, Email $email = null)
    {
        $this->account = $account;
        $this->email = $email;
    }

    /**
 * @return Account
 */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }
}
