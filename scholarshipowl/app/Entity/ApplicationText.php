<?php namespace App\Entity;

use App\Entity\Contracts\ApplicationFileContract;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * ApplicationText
 *
 * @ORM\Table(name="application_text", uniqueConstraints={@ORM\UniqueConstraint(name="unique_account_requirement_text", columns={"account_id", "requirement_text_id"})}, indexes={@ORM\Index(name="application_text_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="application_text_requirement_text_id_foreign", columns={"requirement_text_id"}), @ORM\Index(name="application_text_account_file_id_foreign", columns={"account_file_id"}), @ORM\Index(name="application_text_account_id_scholarship_id_index", columns={"account_id", "scholarship_id"}), @ORM\Index(name="IDX_CC67CF4F9B6B5FBA", columns={"account_id"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class ApplicationText implements ApplicationRequirementContract, ApplicationFileContract
{
    use Timestamps;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $text;

    /**
     * @var \App\Entity\AccountFile
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AccountFile",  fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_file_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $accountFile;

    /**
     * @var \App\Entity\Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=false)
     * })
     */
    private $account;

    /**
     * @var \App\Entity\RequirementText
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementText", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_text_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $requirement;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="applicationTexts")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * ApplicationText constructor.
     *
     * @param RequirementContract $requirementText
     * @param AccountFile|null    $accountFile
     * @param string|null         $text
     * @param Account|null        $account
     */
    public function __construct(
        RequirementContract $requirementText,
        AccountFile     $accountFile = null,
        string          $text = null,
        Account         $account = null
    )
    {
        $this->setRequirement($requirementText);

        if ($accountFile) {
            $this->setAccountFile($accountFile);
        } else if ($text !== null && $account !== null) {
            $this->setText($text);
            $this->setAccount($account);
        } else {
            throw new \InvalidArgumentException('accountFile or text and account should be provided!');
        }
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
     * Set text
     *
     * @param string $text
     *
     * @return ApplicationText
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ApplicationText
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ApplicationText
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set accountFile
     *
     * @param AccountFile $accountFile
     *
     * @return ApplicationText
     */
    public function setAccountFile(AccountFile $accountFile)
    {
        if (!$this->getRequirement()->getAllowFile()) {
            throw new \LogicException('Setting account file for not allowed text requirement.');
        }

        $this->accountFile = $accountFile;
        $this->setAccount($accountFile->getAccount());

        return $this;
    }

    /**
     * Get accountFile
     *
     * @return AccountFile
     */
    public function getAccountFile()
    {
        return $this->accountFile;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return ApplicationText
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \App\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set requirementText
     *
     * @param RequirementText $requirement
     *
     * @return ApplicationText
     */
    public function setRequirement(RequirementContract $requirement)
    {
        if ( ! $requirement instanceof RequirementText) {
            throw new \InvalidArgumentException('Requirement should be RequirementText!');
        }

        $this->requirement = $requirement;
        $this->setScholarship($requirement->getScholarship());

        return $this;
    }

    /**
     * Get requirementText
     *
     * @return RequirementText
     */
    public function getRequirement()
    {
        return $this->requirement;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return ApplicationText
     */
    public function setScholarship(Scholarship $scholarship)
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
     * @ORM\postRemove
     */
    public function postRemove()
    {
        //remove account file from GC
        if($this->getAccountFile() instanceof AccountFile){
            $this->getAccountFile()->remove();
        }
        $this->getScholarship()->removeApplicationText($this);
    }
}

