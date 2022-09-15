<?php namespace App\Events\Account;

class ChangeEmailEvent extends AccountEvent
{
    /**
     * @var string
     */
    protected $prevEmail;

    public function __construct($account, $prevEmail)
    {
        parent::__construct($account);

        $this->prevEmail = $prevEmail;
    }

    /**
     * @return string
     */
    public function getPrevEmail() {
        return $this->prevEmail;
    }
}
