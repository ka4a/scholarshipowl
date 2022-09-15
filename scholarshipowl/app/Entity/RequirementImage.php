<?php namespace App\Entity;

use App\Entity\Contracts\RequirementContract;
use App\Contracts\RequirementFileContract;
use App\Traits\OptionalRequirement;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * RequirementImage
 *
 * @ORM\Table(name="requirement_image", indexes={@ORM\Index(name="requirement_image_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="requirement_image_requirement_name_id_foreign", columns={"requirement_name_id"})})
 * @ORM\Entity
 */
class RequirementImage implements RequirementFileContract, RequirementContract
{
    use Timestamps,
        OptionalRequirement;

    const TYPE = 'image';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
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
     * @var integer
     *
     * @ORM\Column(name="min_width", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $minWidth;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_width", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxWidth;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_height", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $minHeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_height", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxHeight;

    /**
     * @var \App\Entity\RequirementName
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementName")
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
     * RequirementImage constructor.
     *
     * @param int|RequirementName $requirementName
     * @param string              $title
     * @param string              $description
     * @param string|null         $fileExtension
     * @param int|null            $maxFileSize
     * @param int|null            $minWidth
     * @param int|null            $maxWidth
     * @param int|null            $minHeight
     * @param int|null            $maxHeight
     */
    public function __construct(
                    $requirementName,
        string      $title,
        string      $description,
        string      $fileExtension = null,
        int         $maxFileSize = null,
        int         $minWidth = null,
        int         $maxWidth = null,
        int         $minHeight = null,
        int         $maxHeight = null
    ) {
        $this->setRequirementName($requirementName);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setFileExtension($fileExtension);
        $this->setMaxFileSize($maxFileSize);
        $this->setMinWidth($minWidth);
        $this->setMaxWidth($maxWidth);
        $this->setMinHeight($minHeight);
        $this->setMaxHeight($maxHeight);
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
     * @return RequirementImage
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
     * @return RequirementImage
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
     * @return RequirementImage
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
     * @return RequirementImage
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
     * Set minWidth
     *
     * @param integer $minWidth
     *
     * @return RequirementImage
     */
    public function setMinWidth($minWidth)
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    /**
     * Get minWidth
     *
     * @return integer
     */
    public function getMinWidth()
    {
        return $this->minWidth;
    }

    /**
     * Set maxWidth
     *
     * @param integer $maxWidth
     *
     * @return RequirementImage
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;

        return $this;
    }

    /**
     * Get maxWidth
     *
     * @return integer
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Set minHeight
     *
     * @param integer $minHeight
     *
     * @return RequirementImage
     */
    public function setMinHeight($minHeight)
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    /**
     * Get minHeight
     *
     * @return integer
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * Set maxHeight
     *
     * @param integer $maxHeight
     *
     * @return RequirementImage
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    /**
     * Get maxHeight
     *
     * @return integer
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * Set requirementName
     *
     * @param RequirementName $requirementName
     *
     * @return RequirementImage
     */
    public function setRequirementName($requirementName)
    {
        $requirementName = RequirementName::convert($requirementName);

        if ($requirementName->getType() !== RequirementName::TYPE_IMAGE) {
            throw new \InvalidArgumentException(sprintf(
                '$requirementName should be type of %s', RequirementName::TYPE_IMAGE
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
        return ApplicationImage::class;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return RequirementImage
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

