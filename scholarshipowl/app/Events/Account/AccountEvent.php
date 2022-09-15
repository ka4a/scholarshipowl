<?php namespace App\Events\Account;

use App\Entity\Account;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Event;

class AccountEvent extends Event
{
    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var string Http referer, url from which an action was made
     */
    protected $referer;

    /**
     * AccountEvent constructor.
     * @param $account
     * @param null $referer
     */
    public function __construct($account, $referer = null)
    {
        $this->accountId = ($account instanceof Account) ? $account->getAccountId() : $account;
        $this->referer = $referer;
    }

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        return $em->getReference(Account::class, $this->getAccountId());
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }
}
