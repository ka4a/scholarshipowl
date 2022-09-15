<?php

namespace App\Entity\Marketing\Coreg;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * CoregResubmissionTries
 *
 * @ORM\Table(name="coreg_resubmission_tries")
 * @ORM\Entity
 */
class CoregResubmissionTries
{
    /**
     * @var int
     *
     * @ORM\Column(name="submission_id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $submissionId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tries", type="integer", nullable=true)
     */
    private $tries = 3;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=true)
     */
    private $lastUpdate;


    public function __construct($submissionId)
    {
        $this->setSubmissionId($submissionId);
        $this->setLastUpdate((new Carbon())->now());
    }

    /**
     * @return int
     */
    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }

    /**
     * @param int $submissionId
     *
     * @return CoregResubmissionTries
     */
    public function setSubmissionId(int $submissionId)
    {
        $this->submissionId = $submissionId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTries(): ?int
    {
        return $this->tries;
    }

    /**
     * @param int|null $tries
     *
     * @return CoregResubmissionTries
     */
    public function setTries(?int $tries)
    {
        $this->tries = $tries;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdate(): ?\DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * @param \DateTime|null $lastUpdate
     *
     * @return CoregResubmissionTries
     */
    public function setLastUpdate(?\DateTime $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * @param int $number
     *
     * @return $this
     */
    public function decreaseTriesNumber($number = 1){
        if($this->getTries() > 0){
            $this->setTries($this->getTries()-$number);
        }

        return $this;
    }
}
