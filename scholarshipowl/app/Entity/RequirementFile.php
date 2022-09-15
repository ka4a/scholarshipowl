<?php namespace App\Entity;

use App\Entity\Contracts\RequirementContract;
use App\Contracts\RequirementFileContract;
use App\Traits\OptionalRequirement;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * RequirementFile
 *
 * @ORM\Table(name="requirement_file", indexes={@ORM\Index(name="requirement_file_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="requirement_file_requirement_name_id_foreign", columns={"requirement_name_id"})})
 * @ORM\Entity
 */
class RequirementFile implements RequirementFileContract, RequirementContract
{
    use Timestamps,
        OptionalRequirement;

    const TYPE = 'file';

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
     * @ORM\Column(name="description", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="file_extension", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $fileExtension;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_file_size", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxFileSize;

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
     * RequirementFile constructor.
     *
     * @param int|RequirementName $requirementName
     * @param string              $title
     * @param string              $description
     * @param string|null         $fileExtension
     * @param int|null            $maxFileSize
     */
    public function __construct(
                    $requirementName,
        string      $title,
        string      $description,
        string      $fileExtension = null,
        int         $maxFileSize = null
    ) {
        $this->setRequirementName($requirementName);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setFileExtension($fileExtension);
        $this->setMaxFileSize($maxFileSize);
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
     * @return RequirementFile
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
     * Set description
     *
     * @param string $description
     *
     * @return RequirementFile
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
     * Set fileExtension
     *
     * @param string $fileExtension
     *
     * @return RequirementFile
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    /**
     * Get fileExtension
     *
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Set maxFileSize
     *
     * @param integer $maxFileSize
     *
     * @return RequirementFile
     */
    public function setMaxFileSize($maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    /**
     * Get maxFileSize
     *
     * @return integer
     */
    public function getMaxFileSize()
    {
        return $this->maxFileSize;
    }

    /**
     * Set requirementName
     *
     * @param int|RequirementName $requirementName
     *
     * @return RequirementFile
     */
    public function setRequirementName($requirementName)
    {
        $requirementName = RequirementName::convert($requirementName);

        if ($requirementName->getType() !== RequirementName::TYPE_FILE) {
            throw new \InvalidArgumentException(sprintf(
                '$requirementName should be type of %s', RequirementName::TYPE_FILE
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
        return ApplicationFile::class;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return RequirementFile
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;

        return $this;
    }

    /**
     * Get scholarship
     *
     * @return \App\Entity\Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }
}

