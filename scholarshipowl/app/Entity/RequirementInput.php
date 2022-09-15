<?php namespace App\Entity;

use App\Entity\Contracts\RequirementContract;
use App\Entity\Traits\Hydratable;
use App\Entity\Traits\RequirementTag;
use App\Traits\OptionalRequirement;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * RequirementInput
 *
 * @ORM\Table(name="requirement_input", indexes={@ORM\Index(name="requirement_input_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="requirement_input_requirement_name_id_foreign", columns={"requirement_name_id"})})
 * @ORM\Entity
 */
class RequirementInput implements RequirementContract
{
    use Timestamps;
    use Hydratable;
    use RequirementTag;
    use OptionalRequirement;

    const TYPE = 'input';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * Sunrise's requirement id
     *
     * @var integer
     *
     * @ORM\Column(name="external_id", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $externalId;

    /**
     * Sunrise's requirement id (permanent, does not change between recurrences)
     *
     * @var integer
     *
     * @ORM\Column(name="external_id_permanent", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $externalIdPermanent;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_tag", type="string", length=20, nullable=true, unique=false)
     */
    private $permanentTag;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var \App\Entity\RequirementName
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementName", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_name_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $requirementName;

    /**
     * @var \App\Entity\Scholarship
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Scholarship", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=false)
     * })
     */
    private $scholarship;

    /**
     * RequirementInput constructor.
     *
     * @param int|RequirementName $requirementName
     * @param string              $title
     * @param string              $permanentTag
     * @param string              $description
     */
    public function __construct(
        $requirementName,
        string      $title,
        string      $permanentTag,
        string      $description
    )
    {
        $this->setRequirementName($requirementName);
        $this->setTitle($title);
        $this->setPermanentTag($permanentTag);
        $this->setDescription($description);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param int $val
     * @return $this
     */
    public function setExternalId(int $val)
    {
        $this->externalId = $val;

        return $this;
    }

    /**
     * @return int
     */
    public function getExternalIdPermanent()
    {
        return $this->externalIdPermanent;
    }

    /**
     * @param int $val
     * @return $this
     */
    public function setExternalIdPermanent(int $val)
    {
        $this->externalIdPermanent = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return RequirementInput
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $permanentTag
     * @return $this
     */
    public function setPermanentTag($permanentTag)
    {
        $this->permanentTag = $permanentTag;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPermanentTag()
    {
        return $this->permanentTag;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return RequirementInput
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set requirementName
     *
     * @param int|RequirementName $requirementName
     *
     * @return RequirementInput
     */
    public function setRequirementName($requirementName)
    {
        $requirementName = RequirementName::convert($requirementName);

        if ($requirementName->getType() !== RequirementName::TYPE_INPUT) {
            throw new \InvalidArgumentException(sprintf(
                '$requirementName should be type of %s', RequirementName::TYPE_INPUT
            ));
        }

        $this->requirementName = $requirementName;

        return $this;
    }

    /**
     * Get requirementName
     *
     * @return \App\Entity\RequirementName
     */
    public function getRequirementName()
    {
        return $this->requirementName;
    }

    /**
     * @return mixed
     */
    public function getApplicationClass()
    {
        return ApplicationInput::class;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return RequirementInput
     */
    public function setScholarship(Scholarship $scholarship = null)
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

    public function getText()
    {
        return "hardkodet text here";
    }

    public function __clone()
    {
        if (!self::getScholarship()->isRecurrent()) {
            $this->permanentTag = substr(uniqid(), -8);
        }
    }
}

