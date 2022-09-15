<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ApplicationEssayStatus;

/**
 * ApplicationEssay
 *
 * @ORM\Table(name="application_essay", indexes={@ORM\Index(name="ix_application_essay_account_id", columns={"account_id"}), @ORM\Index(name="ix_application_essay_essay_id", columns={"essay_id"}), @ORM\Index(name="fk_application_essay_application_essay_status", columns={"application_essay_status_id"})})
 * @ORM\Entity
 */
class ApplicationEssay
{
    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=16777215, precision=0, scale=0, nullable=false, unique=false)
     */
    private $text;

    /**
     * @var \App\Entity\Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var \App\Entity\ApplicationEssayStatus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ApplicationEssayStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="application_essay_status_id", referencedColumnName="application_essay_status_id", nullable=true)
     * })
     */
    private $applicationEssayStatus;

    /**
     * @var \App\Entity\Essay
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Essay")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="essay_id", referencedColumnName="essay_id", nullable=true)
     * })
     */
    private $essay;

    /**
     * ApplicationEssay constructor.
     *
     * @param Essay                       $essay
     * @param Account                     $account
     * @param string                      $text
     * @param int|ApplicationEssayStatus  $status
     */
    public function __construct(
        Essay     $essay,
        Account   $account,
        string    $text,
        $status = ApplicationEssayStatus::IN_PROGRESS
    ) {
        $this->setText($text);
        $this->setEssay($essay);
        $this->setAccount($account);
        $this->setApplicationEssayStatus($status);
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return ApplicationEssay
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return ApplicationEssay
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set applicationEssayStatus
     *
     * @param int|ApplicationEssayStatus $applicationEssayStatus
     *
     * @return ApplicationEssay
     */
    public function setApplicationEssayStatus($applicationEssayStatus = null)
    {
        $this->applicationEssayStatus = ApplicationEssayStatus::convert($applicationEssayStatus);

        return $this;
    }

    /**
     * Get applicationEssayStatus
     *
     * @return ApplicationEssayStatus
     */
    public function getApplicationEssayStatus()
    {
        return $this->applicationEssayStatus;
    }

    /**
     * Set essay
     *
     * @param \App\Entity\Essay $essay
     *
     * @return ApplicationEssay
     */
    public function setEssay(Essay $essay)
    {
        $this->essay = $essay;

        return $this;
    }

    /**
     * Get essay
     *
     * @return \App\Entity\Essay
     */
    public function getEssay()
    {
        return $this->essay;
    }
}

