<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EssayFiles
 *
 * @ORM\Table(name="essay_files", uniqueConstraints={@ORM\UniqueConstraint(name="uq_essay_files", columns={"essay_id", "scholarship_id", "file_id"})}, indexes={@ORM\Index(name="fk_essay_idx", columns={"essay_id"}), @ORM\Index(name="fk_scholarship_idx", columns={"scholarship_id"}), @ORM\Index(name="fk_file_idx", columns={"account_file_id"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\EssayFilesRepository")
 */
class EssayFiles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="essay_file_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $essayFileId;

    /**
     * @var \App\Entity\Essay
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Essay", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="essay_id", referencedColumnName="essay_id", nullable=true)
     * })
     */
    private $essay;

    /**
     * TODO: Should be removed with `files` table
     *
     * @var null
     * @deprecated
     * @ORM\Column(name="file_id")
     */
    private $oldFile = null;

    /**
     * @var \App\Entity\AccountFile
     *
     * @ORM\OneToOne(targetEntity="App\Entity\AccountFile", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_file_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $file;

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
     * EssayFiles constructor.
     *
     * @param Essay       $essay
     * @param AccountFile $accountFile
     */
    public function __construct(Essay $essay, AccountFile $accountFile)
    {
        $this->setEssay($essay);
        $this->setScholarship($essay->getScholarship());
        $this->setFile($accountFile);
    }

    /**
     * Get essayFileId
     *
     * @return integer
     */
    public function getEssayFileId()
    {
        return $this->essayFileId;
    }

    /**
     * Set essay
     *
     * @param \App\Entity\Essay $essay
     *
     * @return EssayFiles
     */
    public function setEssay(\App\Entity\Essay $essay = null)
    {
        $this->essay = $essay;

        return $this;
    }

    /**
     * Get essay
     *
     * @return \App\Entity\Essay
     */
    public function getEssay()
    {
        return $this->essay;
    }

    /**
     * Set file
     *
     * @param \App\Entity\AccountFile $file
     *
     * @return EssayFiles
     */
    public function setFile(\App\Entity\AccountFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \App\Entity\AccountFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set scholarship
     *
     * @param \App\Entity\Scholarship $scholarship
     *
     * @return EssayFiles
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

