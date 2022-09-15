<?php namespace App\Entity;

use App\Entity\Contracts\ApplicationFileContract;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * ApplicationFile
 *
 * @ORM\Table(name="application_file", uniqueConstraints={@ORM\UniqueConstraint(name="application_file_account_id_scholarship_id_requirement_id_unique", columns={"account_id", "scholarship_id", "requirement_id"})}, indexes={@ORM\Index(name="application_file_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="application_file_requirement_id_foreign", columns={"requirement_id"}), @ORM\Index(name="application_file_account_file_id_foreign", columns={"account_file_id"}), @ORM\Index(name="application_file_account_id_scholarship_id_index", columns={"account_id", "scholarship_id"}), @ORM\Index(name="IDX_7B735E989B6B5FBA", columns={"account_id"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class ApplicationFile implements ApplicationRequirementContract, ApplicationFileContract
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
     * @var \App\Entity\AccountFile
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AccountFile", fetch="EAGER")
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
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var \App\Entity\RequirementFile
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementFile", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_file_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $requirement;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="applicationFiles")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * ApplicationFile constructor.
     *
     * @param AccountFile     $accountFile
     * @param RequirementFile $requirement
     */
    public function __construct(AccountFile $accountFile, RequirementFile $requirement)
    {
        $this->setAccountFile($accountFile);
        $this->setRequirement($requirement);
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
     * Set accountFile
     *
     * @param \App\Entity\AccountFile $accountFile
     *
     * @return ApplicationFile
     */
    public function setAccountFile(\App\Entity\AccountFile $accountFile = null)
    {
        $this->accountFile = $accountFile;
        $this->setAccount($accountFile->getAccount());

        return $this;
    }

    /**
     * Get accountFile
     *
     * @return \App\Entity\AccountFile
     */
    public function getAccountFile()
    {
        return $this->accountFile;
    }

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return ApplicationFile
     */
    public function setAccount(\App\Entity\Account $account = null)
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
     * Set requirement
     *
     * @param RequirementFile $requirement
     *
     * @return ApplicationFile
     */
    public function setRequirement(RequirementContract $requirement)
    {
        if ( ! $requirement instanceof RequirementFile) {
            throw new \InvalidArgumentException('Requirement should be RequirementFile!');
        }

        $this->requirement = $requirement;
        $this->setScholarship($requirement->getScholarship());

        return $this;
    }

    /**
     * Get requirement
     *
     * @return \App\Entity\RequirementFile
     */
    public function getRequirement()
    {
        return $this->requirement;
    }

    /**
     * Set scholarship
     *
     * @param \App\Entity\Scholarship $scholarship
     *
     * @return ApplicationFile
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

    /**
     * @ORM\postRemove
     */
    public function postRemove()
    {
        $this->getScholarship()->removeApplicationFile($this);
    }
}

