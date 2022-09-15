<?php namespace App\Payment\Events;

use App\Entity\Account;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;

class PaymentsEvent
{
    /**
     * @var Account
     */
    protected $account;

    /**
     * @var FeatureSet
     */
    protected $fset;

    /**
     * TransactionRefundedEvent constructor.
     *
     * @param Account           $account
     * @param FeatureSet $fset
     */
    public function __construct(Account $account, FeatureSet $fset)
    {
        $this->account = $account;
        $this->fset = $fset;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return PaymentsEvent
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return FeatureSet
     */
    public function getFset()
    {
        return $this->fset;
    }

    /**
     * @param FeatureSet $fset
     *
     * @return PaymentsEvent
     */
    public function setFset(FeatureSet $fset)
    {
        $this->fset = $fset;

        return $this;
    }
}
