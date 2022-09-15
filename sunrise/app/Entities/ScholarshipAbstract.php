<?php namespace App\Entities;

use App\Entities\Super\AbstractScholarshipField;
use App\Entities\Super\AbstractScholarshipRequirement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass()
 */
abstract class ScholarshipAbstract
{
    /**
     * Default timezone for scholarships.
     */
    const DEFAULT_TIMEZONE = 'America/New_York';

    /**
     * @return int
     */
    abstract public function getId();

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127)
     * @Assert\NotNull()
     * @Assert\Length(min="3", max="127")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2047, nullable=true)
     * @Assert\Length( max="2047")
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $timezone = self::DEFAULT_TIMEZONE;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=false)
     * @Assert\NotNull()
     */
    private $amount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $awards = 1;

    /**
     * Used to identify YDI scholarship must be free on SOWL side.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isFree = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $scholarshipUrl;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $scholarshipPPUrl;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $scholarshipTOSUrl;

    /**
     * @var ArrayCollection|AbstractScholarshipField[]
     */
    protected $fields;

    /**
     * @var ArrayCollection|AbstractScholarshipRequirement[]
     */
    protected $requirements;

    /**
     * ScholarshipAbstract constructor.
     */
    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->requirements = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezoneObj()
    {
        return new \DateTimeZone($this->getTimezone());
    }

    /**
     * @param string $timezone
     *
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * @param int $awards
     * @return $this
     */
    public function setAwards($awards)
    {
        $this->awards = $awards;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsFree()
    {
        return $this->isFree;
    }

    /**
     * @param $isFree
     * @return $this
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;
        return $this;
    }

    /**
     * @return ScholarshipField[]|ArrayCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param array|ArrayCollection $requirements
     * @return $this
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;
        return $this;
    }

    /**
     * @return ScholarshipRequirement[]|ArrayCollection
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param string $scholarshipUrl
     * @return $this
     */
    public function setScholarshipUrl(?string $scholarshipUrl)
    {
        $this->scholarshipUrl = $scholarshipUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getScholarshipUrl(): ?string
    {
        return $this->scholarshipUrl;
    }

    /**
     * @param string $scholarshipPPUrl
     * @return $this
     */
    public function setScholarshipPPUrl(?string $scholarshipPPUrl)
    {
        $this->scholarshipPPUrl = $scholarshipPPUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getScholarshipPPUrl(): ?string
    {
        return $this->scholarshipPPUrl;
    }

    /**
     * @param string $scholarshipTOSUrl
     * @return $this
     */
    public function setScholarshipTOSUrl(?string $scholarshipTOSUrl)
    {
        $this->scholarshipTOSUrl = $scholarshipTOSUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getScholarshipTOSUrl(): ?string
    {
        return $this->scholarshipTOSUrl;
    }
}
