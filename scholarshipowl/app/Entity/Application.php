<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use http\Exception\InvalidArgumentException;

/**
 * Application
 *
 * @ORM\Table(name="application", indexes={
 *     @ORM\Index(name="ix_application_account_id", columns={"account_id"}),
 *     @ORM\Index(name="ix_application_scholarship_id", columns={"scholarship_id"}),
 *     @ORM\Index(name="ix_application_application_status_id", columns={"application_status_id"}),
 *     @ORM\Index(name="ix_application_subscription_id", columns={"subscription_id"}),
 *     @ORM\Index(name="ix_application_date_applied", columns={"date_applied"})
 * })
 * @ORM\Entity(repositoryClass="App\Entity\Repository\ApplicationRepository")
 */
class Application
{
    const EXTERNAL_STATUS_SENT = 1;
    const EXTERNAL_STATUS_RECEIVED = 10; // at the moment we use ACCEPTED instead todo
    const EXTERNAL_STATUS_IN_PROGRESS = 20; //(review) todo
    const EXTERNAL_STATUS_ACCEPTED = 21;
    const EXTERNAL_STATUS_DECLINED = 22;
    const EXTERNAL_STATUS_POTENTIAL_WINNER = 30;
    const EXTERNAL_STATUS_DISQUALIFIED_WINNER = 31;
    const EXTERNAL_STATUS_PROVED_WINNER = 32;

    /**
     * @var string
     *
     * @ORM\Column(name="submited_data", type="text", length=16777215, precision=0, scale=0, nullable=true, unique=false)
     */
    private $submitedData;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=4095, precision=0, scale=0, nullable=true, unique=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_applied", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $dateApplied;

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="applications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var ApplicationStatus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ApplicationStatus", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="application_status_id", referencedColumnName="application_status_id", nullable=true)
     * })
     */
    private $applicationStatus;

    /**
     * @var Scholarship
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="App\Entity\Scholarship", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=true)
     * })
     */
    private $scholarship;

    /**
     * @var Subscription
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Subscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription_id", referencedColumnName="subscription_id", nullable=true)
     * })
     */
    private $subscription;

    /**
     * @var ApplicationText[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationText", fetch="EXTRA_LAZY", mappedBy="scholarship")
     */
    private $applicationTexts;

    /**
     * @var ApplicationFile[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationFile", fetch="EXTRA_LAZY", mappedBy="scholarship")
     */
    private $applicationFiles;

    /**
     * @var ApplicationImage[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationImage", fetch="EXTRA_LAZY", mappedBy="scholarship")
     */
    private $applicationImages;

    /**
     * @var string
     *
     * @ORM\Column(name="external_scholarship_template_id", type="string", length=40, nullable=true, unique=false)
     */
    protected $externalScholarshipTemplateId;

    /**
     * @var string
     *
     * @ORM\Column(name="external_application_id", type="string", length=40, nullable=true, unique=false)
     */
    protected $externalApplicationId;

    /**
     * @var string
     *
     * @ORM\Column(name="external_status", type="integer", nullable=true, unique=false)
     */
    protected $externalStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="external_status_updated_at", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $externalStatusUpdatedAt;


    /**
     * Application constructor.
     *
     * @param Account           $account
     * @param Scholarship       $scholarship
     * @param Subscription|null $subscription
     * @param int               $status
     */
    public function __construct(
        Account      $account,
        Scholarship  $scholarship,
        Subscription $subscription = null,
                     $status = ApplicationStatus::NEED_MORE_INFO
    ) {
        $this->setAccount($account);
        $this->setScholarship($scholarship);
        $this->setApplicationStatus($status);

        if ($subscription) {
            $this->setSubscription($subscription);
        }
    }

    /**
     * Set submitedData
     *
     * @param string|array $submittedData
     *
     * @return Application
     */
    public function setSubmitedData($submittedData)
    {
        $this->submitedData = is_string($submittedData) ? $submittedData : json_encode($submittedData);

        return $this;
    }

    /**
     * Get submitedData
     *
     * @return string
     */
    public function getSubmitedData()
    {
        return $this->submitedData;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Application
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set dateApplied
     *
     * @param \DateTime $dateApplied
     *
     * @return Application
     */
    public function setDateApplied($dateApplied)
    {
        $this->dateApplied = $dateApplied;

        return $this;
    }

    /**
     * Get dateApplied
     *
     * @return \DateTime
     */
    public function getDateApplied()
    {
        return $this->dateApplied;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Application
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
     * Set applicationStatus
     *
     * @param int|ApplicationStatus $applicationStatus
     *
     * @return Application
     */
    public function setApplicationStatus($applicationStatus)
    {
        $this->applicationStatus = ApplicationStatus::convert($applicationStatus);

        return $this;
    }

    /**
     * Get applicationStatus
     *
     * @return ApplicationStatus
     */
    public function getApplicationStatus()
    {
        return $this->applicationStatus;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return Application
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;

        return $this;
    }

    /**
     * Get scholarship
     *
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * Set subscription
     *
     * @param Subscription $subscription
     *
     * @return Application
     */
    public function setSubscription(Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @return ArrayCollection|ApplicationText[]
     */
    public function getApplicationTexts()
    {
        return $this->applicationTexts;
    }

    /**
     * @return ArrayCollection|ApplicationFile[]
     */
    public function getApplicationFiles()
    {
        return $this->applicationFiles;
    }

    /**
     * @return ArrayCollection|ApplicationImage[]
     */
    public function getApplicationImages()
    {
        return $this->applicationImages;
    }

    /**
     * @param int $externalScholarshipTemplateId
     *
     * @return Application
     */
    public function setExternalScholarshipTemplateId($externalScholarshipTemplateId)
    {
        $this->externalScholarshipTemplateId = $externalScholarshipTemplateId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExternalScholarshipTemplateId()
    {
        return $this->externalScholarshipTemplateId;
    }

    /**
     * @param int $externalApplicationId
     *
     * @return Application
     */
    public function setExternalApplicationId($externalApplicationId)
    {
        $this->externalApplicationId = $externalApplicationId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExternalApplicationId()
    {
        return $this->externalApplicationId;
    }

    /**
     * @param int $externalStatus
     *
     * @return Application
     */
    public function setExternalStatus($externalStatus)
    {
        if (!array_key_exists($externalStatus, $this->externalStatuses())) {
            throw new \InvalidArgumentException(
                sprintf('Invalid external application status: [ %s ]', $externalStatus)
            );
        }

        $this->externalStatus = $externalStatus;

        $this->setExternalStatusUpdatedAt(new \DateTime());

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExternalStatus($describe = false)
    {
        if ($describe) {
            return $this->externalStatus ? $this->externalStatuses($this->externalStatus) : null;
        }

        return $this->externalStatus;
    }

    /**
     * @param \DateTime $externalStatusUpdatedAt
     */
    public function setExternalStatusUpdatedAt(\DateTime $externalStatusUpdatedAt)
    {
        $this->externalStatusUpdatedAt = $externalStatusUpdatedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getExternalStatusUpdatedAt()
    {
        return $this->externalStatusUpdatedAt;
    }

    public function externalStatuses()
    {
        return [
            self::EXTERNAL_STATUS_IN_PROGRESS => [
                'id' => self::EXTERNAL_STATUS_IN_PROGRESS,
                'name' => 'IN PROGRESS',
                'description' => 'Application being processed by scholarship provider'
            ],
            self::EXTERNAL_STATUS_ACCEPTED => [
                'id' => self::EXTERNAL_STATUS_ACCEPTED,
                'name' => 'ACCEPTED',
                'description' => 'Scholarship provider accepted an application'
            ],
            self::EXTERNAL_STATUS_DECLINED => [
                'id' => self::EXTERNAL_STATUS_DECLINED,
                'name' => 'DECLINED',
                'Scholarship provider declined an application'
            ],
            self::EXTERNAL_STATUS_POTENTIAL_WINNER => [
                'id' => self::EXTERNAL_STATUS_POTENTIAL_WINNER,
                'name' => 'WON',
                'description' => 'Scholarship provider chose an application as a winner'
            ],
            self::EXTERNAL_STATUS_DISQUALIFIED_WINNER => [
                'id' => self::EXTERNAL_STATUS_DISQUALIFIED_WINNER,
                'name' => 'MISSED',
                'description' => 'Scholarship provider disqualified a winner'
            ],
            self::EXTERNAL_STATUS_PROVED_WINNER => [
                'id' => self::EXTERNAL_STATUS_PROVED_WINNER,
                'name' => 'AWARDED',
                'description' => 'A user provided all required information and can be rewarded by Scholarship provider'
            ]
        ];
    }

}

