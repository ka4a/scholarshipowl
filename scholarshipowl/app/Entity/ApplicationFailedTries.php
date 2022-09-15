<?php namespace App\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationFailedTries
 *
 * @ORM\Table(name="application_failed_tries")
 * @ORM\Entity
 */
class ApplicationFailedTries
{
    /**
     * @var int
     *
     * @ORM\Column(name="scholarship_id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $scholarshipId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="account_id", type="integer", nullable=true)
     */
    private $accountId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tries", type="integer", nullable=true)
     */
    private $tries = 3;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=true)
     */
    private $lastUpdate;

    public function __construct($accountId, $scholarshipId, $resendNumber = 3)
    {
        $this->scholarshipId = $scholarshipId;
        $this->accountId = $accountId;
        $this->setTries($resendNumber);
        $this->lastUpdate = Carbon::now();
    }

    /**
     * @return int
     */
    public function getScholarshipId(): int
    {
        return $this->scholarshipId;
    }

    /**
     * @param int $scholarshipId
     *
     * @return ApplicationFailedTries
     */
    public function setScholarshipId(int $scholarshipId)
    {
        $this->scholarshipId = $scholarshipId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     *
     * @return ApplicationFailedTries
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTries()
    {
        return $this->tries;
    }

    /**
     * @param int|null $tries
     *
     * @return ApplicationFailedTries
     */
    public function setTries($tries)
    {
        $this->tries = $tries;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param \DateTime $lastUpdate
     *
     * @return ApplicationFailedTries
     */
    public function setLastUpdate( $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function decreaseTriesNumber($tries = 1){
        if($this->getTries() > 0 && $this->getTries() - $tries >= 0){
            $this->setTries($this->getTries()-$tries);
        }
        return $this;
    }
}
