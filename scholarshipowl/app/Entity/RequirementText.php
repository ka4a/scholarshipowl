<?php namespace App\Entity;

use App\Entity\Contracts\RequirementContract;
use App\Contracts\RequirementFileContract;
use App\Entity\Traits\Hydratable;
use App\Entity\Traits\RequirementTag;
use App\Traits\OptionalRequirement;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
/**
 * RequirementText
 *
 * @ORM\Table(name="requirement_text", indexes={@ORM\Index(name="requirement_text_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="requirement_text_requirement_name_id_foreign", columns={"requirement_name_id"})})
 * @ORM\Entity
 */
class RequirementText implements RequirementFileContract, RequirementContract
{
    use Timestamps;
    use Hydratable;
    use RequirementTag;
    use OptionalRequirement;

    const TYPE = 'text';

    const SEND_TYPE_ATTACHMENT = "attachment";
    const SEND_TYPE_FIELD = "field";
    const SEND_TYPE_BODY = "body";

    public static $sendTypes = [
        Scholarship::APPLICATION_TYPE_EMAIL => [
            self::SEND_TYPE_BODY => "Body",
            self::SEND_TYPE_ATTACHMENT => "Attachment",
        ],
        Scholarship::APPLICATION_TYPE_ONLINE => [
            self::SEND_TYPE_FIELD => "Field",
        ],
        Scholarship::APPLICATION_TYPE_NONE => [
            self::SEND_TYPE_ATTACHMENT => "Attachment",
        ],
        Scholarship::APPLICATION_TYPE_SUNRISE => [
            self::SEND_TYPE_FIELD => "Field",
        ]
    ];

    //TODO: Remove use DocumentGenerator
    const ATTACHMENT_TYPE_PDF = "pdf";
    const ATTACHMENT_TYPE_DOC = "doc";
    const ATTACHMENT_TYPE_TXT = "txt";

    public static $attachmentTypes = [
        self::ATTACHMENT_TYPE_PDF => "PDF",
        self::ATTACHMENT_TYPE_DOC => "DOC",
        self::ATTACHMENT_TYPE_TXT => "TXT"
    ];

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
     * @var string
     *
     * @ORM\Column(name="send_type", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $sendType;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment_type", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $attachmentType;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment_format", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $attachmentFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="file_extension", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $fileExtension;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_file", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $allowFile = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_file_size", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxFileSize;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_words", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $minWords;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_words", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxWords;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_characters", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $minCharacters;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_characters", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxCharacters;

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
     * RequirementText constructor.
     *
     * @param array $hydrateData
     */
    public function __construct(array $hydrateData)
    {
        $this->hydrate($hydrateData);
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
     * @return RequirementText
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
     * @return RequirementText
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
     * Set sendType
     *
     * @param string $sendType
     *
     * @return RequirementText
     */
    public function setSendType($sendType)
    {
        $this->sendType = $sendType;

        if ($sendType === self::SEND_TYPE_BODY) {
            $this->setAllowFile(false);
        }

        return $this;
    }

    /**
     * Get sendType
     *
     * @return string
     */
    public function getSendType()
    {
        return $this->sendType;
    }

    /**
     * Set attachmentType
     *
     * @param string $attachmentType
     *
     * @return RequirementText
     */
    public function setAttachmentType($attachmentType)
    {
        $this->attachmentType = $attachmentType;

        return $this;
    }

    /**
     * Get attachmentType
     *
     * @return string
     */
    public function getAttachmentType()
    {
        return $this->attachmentType;
    }

    /**
     * Set attachmentFormat
     *
     * @param string $attachmentFormat
     *
     * @return RequirementText
     */
    public function setAttachmentFormat($attachmentFormat)
    {
        $this->attachmentFormat = $attachmentFormat;

        return $this;
    }

    /**
     * Get attachmentFormat
     *
     * @return string
     */
    public function getAttachmentFormat()
    {
        return $this->attachmentFormat;
    }

    /**
     * Set fileExtension
     *
     * @param string $fileExtension
     *
     * @return RequirementText
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
     * @param bool $allowFile
     *
     * @return $this
     */
    public function setAllowFile($allowFile)
    {
        $this->allowFile = (bool) $allowFile;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowFile()
    {
        return $this->allowFile;
    }

    /**
     * Set maxFileSize
     *
     * @param integer $maxFileSize
     *
     * @return RequirementText
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
     * Set minWords
     *
     * @param integer $minWords
     *
     * @return RequirementText
     */
    public function setMinWords($minWords)
    {
        $this->minWords = $minWords;

        return $this;
    }

    /**
     * Get minWords
     *
     * @return integer
     */
    public function getMinWords()
    {
        return $this->minWords;
    }

    /**
     * Set maxWords
     *
     * @param integer $maxWords
     *
     * @return RequirementText
     */
    public function setMaxWords($maxWords)
    {
        $this->maxWords = $maxWords;

        return $this;
    }

    /**
     * Get maxWords
     *
     * @return integer
     */
    public function getMaxWords()
    {
        return $this->maxWords;
    }

    /**
     * Set minCharacters
     *
     * @param integer $minCharacters
     *
     * @return RequirementText
     */
    public function setMinCharacters($minCharacters)
    {
        $this->minCharacters = $minCharacters;

        return $this;
    }

    /**
     * Get minCharacters
     *
     * @return integer
     */
    public function getMinCharacters()
    {
        return $this->minCharacters;
    }

    /**
     * Set maxCharacters
     *
     * @param integer $maxCharacters
     *
     * @return RequirementText
     */
    public function setMaxCharacters($maxCharacters)
    {
        $this->maxCharacters = $maxCharacters;

        return $this;
    }

    /**
     * Get maxCharacters
     *
     * @return integer
     */
    public function getMaxCharacters()
    {
        return $this->maxCharacters;
    }

    /**
     * Set requirementName
     *
     * @param int|RequirementName $requirementName
     *
     * @return RequirementText
     */
    public function setRequirementName($requirementName)
    {
        $requirementName = RequirementName::convert($requirementName);

        if ($requirementName->getType() !== RequirementName::TYPE_TEXT) {
            throw new \InvalidArgumentException(sprintf(
                'requirementName should be type of %s (%s given)', RequirementName::TYPE_TEXT, $requirementName->getType()
            ));
        }

        $this->requirementName = $requirementName;

        return $this;
    }

    /**
     * Get requirementName
     *
     * @return RequirementName
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
        return ApplicationText::class;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return RequirementText
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

    public function __clone()
    {
        if (!self::getScholarship()->isRecurrent()) {
            $this->permanentTag = substr(uniqid(), -8);
        }
    }
}

