<?php
namespace App\Events\Scholarship;

class FreshScholarshipEvent
{
    /**
     * @var array
     */
    protected $newFreshAccountsScholarships;

    /**
     * @var array
     */
    protected $lastFreshAccountsScholarships;

    /**
     * @var array
     */
    protected $accounts;

    /**
     * FreshScholarshipEvent constructor.
     *
     * @param $accounts
     * @param $newFreshAccountsScholarships
     * @param $lastFreshAccountsScholarships
     *
     * @internal param Scholarship|int $scholarship
     */
    public function __construct($accounts, $newFreshAccountsScholarships, $lastFreshAccountsScholarships)
    {
        $this->accounts = $accounts;
        $this->newFreshAccountsScholarships = $newFreshAccountsScholarships;
        $this->lastFreshAccountsScholarships= $lastFreshAccountsScholarships;
    }

    /**
     * @return array
     */
    public function getNewFreshAccountsScholarships()
    {
        return $this->newFreshAccountsScholarships;
    }

    /**
     * @param array $newFreshAccountsScholarships
     *
     * @return FreshScholarshipEvent
     */
    public function setNewFreshAccountsScholarships($newFreshAccountsScholarships
    ) {
        $this->newFreshAccountsScholarships = $newFreshAccountsScholarships;

        return $this;
    }

    /**
     * @return array
     */
    public function getLastFreshAccountsScholarships()
    {
        return $this->lastFreshAccountsScholarships;
    }

    /**
     * @param array $lastFreshAccountsScholarships
     *
     * @return FreshScholarshipEvent
     */
    public function setLastFreshAccountsScholarships($lastFreshAccountsScholarships
    ) {
        $this->lastFreshAccountsScholarships = $lastFreshAccountsScholarships;

        return $this;
    }

    /**
     * @return array
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param array $accounts
     *
     * @return FreshScholarshipEvent
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }
}
