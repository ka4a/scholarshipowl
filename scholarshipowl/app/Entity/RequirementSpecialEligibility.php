<?php namespace App\Entity;

use App\Entity\Contracts\RequirementContract;
use App\Entity\Traits\Hydratable;
use App\Entity\Traits\RequirementTag;
use App\Traits\OptionalRequirement;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * RequirementSpecialEligibility
 *
 * @ORM\Table(name="requirement_special_eligibility", indexes={@ORM\Index(name="requirement_special_eligibility_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="requirement_special_eligibility_requirement_name_id_foreign", columns={"requirement_name_id"})})
 * @ORM\Entity
 */
class RequirementSpecialEligibility implements RequirementContract
{
    use Timestamps;
    use Hydratable;
    use RequirementTag;
    use OptionalRequirement;

    const TYPE = 'special-eligibility';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="external_id", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $externalId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="external_id_permanent", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $externalIdPermanent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_tag", type="string", length=20, nullable=false, options={"fixed"=true})
     */
    private $permanentTag;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

    /**
     * @var RequirementName
     *
     * @ORM\ManyToOne(targetEntity="RequirementName")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_name_id", referencedColumnName="id")
     * })
     */
    private $requirementName;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     * })
     */
    private $scholarship;

    /**
     * RequirementSpecialEligibility constructor.
     *
     * @param array $hydrateData
     */
    public function __construct(array $hydrateData)
    {
        $this->hydrate($hydrateData);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    /**
     * @param int|null $externalId
     */
    public function setExternalId(?int $externalId): void
    {
        $this->externalId = $externalId;
    }

    /**
     * @return int|null
     */
    public function getExternalIdPermanent(): ?int
    {
        return $this->externalIdPermanent;
    }

    /**
     * @param int|null $externalIdPermanent
     */
    public function setExternalIdPermanent(?int $externalIdPermanent): void
    {
        $this->externalIdPermanent = $externalIdPermanent;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPermanentTag(): string
    {
        return $this->permanentTag;
    }

    /**
     * @param string $permanentTag
     */
    public function setPermanentTag(string $permanentTag): void
    {
        $this->permanentTag = $permanentTag;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return RequirementName
     */
    public function getRequirementName(): RequirementName
    {
        return $this->requirementName;
    }

    /**
     * @param RequirementName $requirementName
     */
    public function setRequirementName($requirementName)
    {
        $requirementName = RequirementName::convert($requirementName);

        if ($requirementName->getType() !== RequirementName::TYPE_SPECIAL_ELIGIBILITY) {
            throw new \InvalidArgumentException(sprintf(
                'requirementName should be type of %s (%s given)', RequirementName::TYPE_SPECIAL_ELIGIBILITY, $requirementName->getType()
            ));
        }

        $this->requirementName = $requirementName;

        return $this;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship(): Scholarship
    {
        return $this->scholarship;
    }

    /**
     * @param Scholarship $scholarship
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationClass()
    {
        return ApplicationSpecialEligibility::class;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    public function __clone()
    {
        if (!self::getScholarship()->isRecurrent()) {
            $this->permanentTag = substr(uniqid(), -8);
        }
    }
}
