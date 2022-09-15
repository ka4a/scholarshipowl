<?php namespace App\Events\Firebase;


class NewMatchEvent
{
    /**
     * @var array $eligibilityCache
     */
    protected $newScholarshipList;

    /**
     * @var int
     */
    protected $accountId;

    /**
     * NewMatchEvent constructor.
     * @param $accountId
     * @param array $newScholarshipList
     */
    public function __construct($accountId, array $newScholarshipList = [])
    {
        $this->accountId = $accountId;
        $this->newScholarshipList = $newScholarshipList;
    }

    /**
     * @return array
     */
    public function getNewScholarshipList()
    {
        return $this->newScholarshipList;
    }

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}
