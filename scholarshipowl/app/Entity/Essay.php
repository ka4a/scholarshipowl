<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Essay
 *
 * @ORM\Table(name="essay", indexes={@ORM\Index(name="ix_essay_scholarship_id", columns={"scholarship_id"})})
 * @ORM\Entity
 */
class Essay
{
    /**
     * @var integer
     *
     * @ORM\Column(name="essay_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $essayId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=2045, precision=0, scale=0, nullable=false, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=4095, precision=0, scale=0, nullable=true, unique=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_words", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    private $minWords;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_words", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxWords;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_characters", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    private $minCharacters;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_characters", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    private $maxCharacters;

    /**
     * @var string
     *
     * @ORM\Column(name="send_type", type="string", precision=0, scale=0, nullable=true, unique=false)
     */
    private $sendType;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment_type", type="string", precision=0, scale=0, nullable=true, unique=false)
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
     * @ORM\Column(name="field_name", type="string", length=127, precision=0, scale=0, nullable=true, unique=false)
     */
    private $fieldName;

    /**
     * @var \App\Entity\Scholarship
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Scholarship", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=true)
     * })
     */
    private $scholarship;

    /**
     * Essay constructor.
     *
     * @param string $title
     * @param string $description
     */
    public function __construct(string $title, string $description)
    {
        $this->setTitle($title);
        $this->setDescription($description);
    }

    /**
     * Get essayId
     *
     * @return integer
     */
    public function getEssayId()
    {
        return $this->essayId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Essay
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
     * @return Essay
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
     * Set minWords
     *
     * @param integer $minWords
     *
     * @return Essay
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
     * @return Essay
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
     * @return Essay
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
     * @return Essay
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
     * Set sendType
     *
     * @param string $sendType
     *
     * @return Essay
     */
    public function setSendType($sendType)
    {
        $this->sendType = $sendType;

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
     * @return Essay
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
     * @return Essay
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
     * Set fieldName
     *
     * @param string $fieldName
     *
     * @return Essay
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Set scholarship
     *
     * @param \App\Entity\Scholarship $scholarship
     *
     * @return Essay
     */
    public function setScholarship(\App\Entity\Scholarship $scholarship = null)
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

