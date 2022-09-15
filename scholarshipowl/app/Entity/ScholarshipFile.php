<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * ScholarshipFile
 *
 * @ORM\Table(name="scholarship_file", indexes={
 *     @ORM\Index(name="fk_scholarship_file_scholarship", columns={"scholarship_id"}),
 *     @ORM\Index(name="fk_scholarship_file_account_file_category", columns={"category_id"})
 * })
 * @ORM\Entity
 */
class ScholarshipFile
{
    use Timestamps;

    /**
     * @var integer
     *
     * @ORM\Column(name="scholarship_file_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $scholarshipFileId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_size", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $maxSize;

    /**
     * @var \App\Entity\AccountFileCategory
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AccountFileCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $category;

    /**
     * @var \App\Entity\Scholarship
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Scholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=true)
     * })
     */
    private $scholarship;

    /**
     * @var \Doctrine\Common\Collections\Collection|AccountFileType[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\AccountFileType", inversedBy="scholarshipFile")
     * @ORM\JoinTable(name="scholarship_file_account_file_type",
     *   joinColumns={
     *     @ORM\JoinColumn(name="scholarship_file_id", referencedColumnName="scholarship_file_id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="file_type_id", referencedColumnName="id", nullable=true)
     *   }
     * )
     */
    private $fileTypes;

    /**
     * ScholarshipFile constructor.
     *
     * @param string                  $description
     * @param int                     $maxSize
     * @param int|AccountFileCategory $category
     * @param array|AccountFileTYpe[] $fileTypes
     */
    public function __construct(string $description, int $maxSize, $category, array $fileTypes)
    {
        $this->fileTypes = new ArrayCollection();

        $this->setDescription($description);
        $this->setMaxSize($maxSize);
        $this->setCategory($category);
        foreach ($fileTypes as $fileType) {
            $this->addFileType($fileType);
        }
    }

    /**
     * Get scholarshipFileId
     *
     * @return integer
     */
    public function getScholarshipFileId()
    {
        return $this->scholarshipFileId;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ScholarshipFile
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
     * Set maxSize
     *
     * @param integer $maxSize
     *
     * @return ScholarshipFile
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    /**
     * Get maxSize
     *
     * @return integer
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * Set category
     *
     * @param int|AccountFileCategory $category
     *
     * @return ScholarshipFile
     */
    public function setCategory($category = null)
    {
        $this->category = AccountFileCategory::convert($category);

        return $this;
    }

    /**
     * Get category
     *
     * @return AccountFileCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return ScholarshipFile
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

    /**
     * Add fileType
     *
     * @param int|AccountFileType $fileType
     *
     * @return ScholarshipFile
     */
    public function addFileType($fileType)
    {
        if (!$this->fileTypes->contains($fileType = AccountFileType::convert($fileType))) {
            $this->fileTypes->add($fileType);
        }

        return $this;
    }

    /**
     * Remove fileType
     *
     * @param AccountFileType $fileType
     *
     * @return $this
     */
    public function removeFileType($fileType)
    {
        $this->fileTypes->removeElement(AccountFileType::convert($fileType));

        return $this;
    }

    /**
     * Get fileType
     *
     * @return ArrayCollection
     */
    public function getFileTypes()
    {
        return $this->fileTypes;
    }
}

