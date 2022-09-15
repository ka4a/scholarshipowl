<?php namespace App\Entities;

use App\Entities\Traits\BelongsToScholarshipTemplate;
use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Published scholarship.
 *
 * @ORM\Entity(repositoryClass="App\Repositories\ScholarshipRepository")
 * @ORM\Table(name="scholarship",
 *     indexes={
 *          @ORM\Index(name="ix_scholarship_status", columns={"status"})
 *     },
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uk_scholarship_id", columns={"id"})
 *     }
 * )
 */
class Scholarship extends ScholarshipAbstract implements JsonApiResource
{
    use BelongsToScholarshipTemplate;
    use Timestamps;

    const STATUS_UNPROCESSABLE = 'unprocessable';
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_PUBLISHED = 'published';
    const STATUS_EXPIRED = 'expired';

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship';
    }

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiredAt;

    /**
     * @var ScholarshipTemplate
     * @ORM\ManyToOne(targetEntity="ScholarshipTemplate")
     * @ORM\JoinColumn(name="scholarship_template_id")
     */
    protected $template;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    protected $status = self::STATUS_UNPROCESSABLE;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $active = true;

    /**
     * @var ScholarshipContent
     * @ORM\OneToOne(targetEntity="ScholarshipContent", mappedBy="scholarship")
     */
    protected $content;

    /**
     * @var ArrayCollection|Application[]
     * @ORM\OneToMany(targetEntity="Application", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applications;

    /**
     * @var ScholarshipWinner[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="ScholarshipWinner", mappedBy="scholarship", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $winners;

    /**
     * @var ArrayCollection|ScholarshipField[]
     * @ORM\OneToMany(targetEntity="ScholarshipField", mappedBy="scholarship", cascade={"persist"}, orphanRemoval=true)
     */
    protected $fields;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $occurrence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     */
    private $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="recurring_type", type="string", nullable=true, unique=false)
     */
    protected $recurringType;

    /**
     * @var integer
     *
     * @ORM\Column(name="recurring_value", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $recurringValue;

    /**
     * @var ArrayCollection|ScholarshipRequirement[]
     * @ORM\OneToMany(targetEntity="ScholarshipRequirement", mappedBy="scholarship", cascade={"persist"}, orphanRemoval=true)
     */
    protected $requirements;

    /**
     * Scholarship constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->winners = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ScholarshipTemplate $template
     * @return $this
     */
    public function setTemplate(ScholarshipTemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return ScholarshipTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Scholarship
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Scholarship
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return ScholarshipContent
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param ScholarshipContent $content
     * @return $this
     */
    public function setContent(ScholarshipContent $content)
    {
        $this->content = $content->setScholarship($this);
        return $this;
    }

    /**
     * @param \DateTime $expiredAt
     * @return $this
     */
    public function setExpiredAt(\DateTime $expiredAt)
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isExpired()
    {
        return $this->expiredAt && new \DateTime('now') >= $this->expiredAt;
    }

    /**
     * @return ScholarshipWinner[]|ArrayCollection
     */
    public function getWinners()
    {
        return $this->winners;
    }

    /**
     * @param ScholarshipWinner $winner
     * @return $this
     */
    public function addWinners(ScholarshipWinner $winner)
    {
        if (!$this->winners->contains($winner)) {
            $this->winners->add($winner->setScholarship($this));
        }
        return $this;
    }

    /**
     * @param ScholarshipWinner $winner
     * @return $this
     */
    public function removeWinners(ScholarshipWinner $winner)
    {
        $this->winners->removeElement($winner);
        return $this;
    }

    /**
     * @return Application[]|ArrayCollection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param int $occurrence
     * @return $this
     */
    public function setOccurrence($occurrence)
    {
        $this->occurrence = $occurrence;
        return $this;
    }

    /**
     * @return int
     */
    public function getOccurrence()
    {
        return $this->occurrence;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime|string $start
     * @return $this
     * @throws \Exception
     */
    public function setStart($start)
    {
        $this->start = $start instanceof \DateTime ? $start : new \DateTime($start);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param \DateTime|string $deadline
     * @return $this
     * @throws \Exception
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline instanceof \DateTime ? $deadline : new \DateTime($deadline);
        return $this;
    }

    /**
     * @return int
     */
    public function getRecurringType()
    {
        return $this->recurringType;
    }

    /**
     * @param string $recurringType
     *
     * @return $this
     */
    public function setRecurringType($recurringType)
    {
        $this->recurringType = $recurringType;

        return $this;
    }

    /**
     * @return int
     */
    public function getRecurringValue()
    {
        return $this->recurringValue;
    }

    /**
     * @param int $recurringValue
     *
     * @return $this
     */
    public function setRecurringValue($recurringValue)
    {
        $this->recurringValue = $recurringValue;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getNextDate()
    {
        $recurrenceConfig = $this->getTemplate()->getRecurrenceConfig();
        if (!$recurrenceConfig || !$recurrenceConfig->isRecurrable() || $this->getTemplate()->isPaused()) {
            return null;
        }

        if ($recurrenceConfig->getOccurrences() && $recurrenceConfig->getOccurrences() <= $this->getOccurrence()) {
            return null;
        }

        return $recurrenceConfig->getStartDate($this->getStart(), 2);
    }

    /**
     * @return string|null
     */
    public function getPublicUrl()
    {
        if ($this->getTemplate()->getWebsite()) {
            return $this->getTemplate()->getWebsite()->getUrl();
        }
        return $this->getScholarshipUrl();
    }

    /**
     * @return string|null
     */
    public function getPublicPPUrl()
    {
        if ($this->getTemplate()->getWebsite()) {
            return $this->getTemplate()->getWebsite()->getPrivacyPolicyUrl();
        }
        return $this->getScholarshipPPUrl();
    }

    /**
     * @return string|null
     */
    public function getPublicTOSUrl()
    {
        if ($this->getTemplate()->getWebsite()) {
            return $this->getTemplate()->getWebsite()->getTermsOfUseUrl();
        }
        return $this->getScholarshipTOSUrl();
    }
}
