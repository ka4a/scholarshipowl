<?php namespace App\Entity;

use App\Entity\Contracts\ApplicationFileContract;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * ApplicationImage
 *
 * @ORM\Table(name="application_image", uniqueConstraints={@ORM\UniqueConstraint(name="unique_account_requirement_image", columns={"account_id", "requirement_image_id"})}, indexes={@ORM\Index(name="application_image_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="application_image_requirement_image_id_foreign", columns={"requirement_image_id"}), @ORM\Index(name="application_image_account_file_id_foreign", columns={"account_file_id"}), @ORM\Index(name="application_image_account_id_scholarship_id_index", columns={"account_id", "scholarship_id"}), @ORM\Index(name="IDX_26A9E3259B6B5FBA", columns={"account_id"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class ApplicationImage implements ApplicationRequirementContract, ApplicationFileContract
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
     * @var \App\Entity\RequirementImage
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementImage", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_image_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $requirement;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="applicationImages")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * @var integer
     *
     * @ORM\Column(name="from_camera", type="integer", nullable=false)
     */
    private $fromCamera = 0;

    /**
     * ApplicationImage constructor.
     *
     * @param AccountFile      $accountFile
     * @param RequirementImage $requirementImage
     * @param integer          $fromCamera
     */
    public function __construct(AccountFile $accountFile, RequirementImage $requirementImage, $fromCamera = 0)
    {
        $this->setAccountFile($accountFile);
        $this->setRequirement($requirementImage);
        $this->setFromCamera($fromCamera);
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
     * @param AccountFile $accountFile
     *
     * @return ApplicationImage
     */
    public function setAccountFile(AccountFile $accountFile = null)
    {
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
     * @return ApplicationImage
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set requirementImage
     *
     * @param \App\Entity\RequirementImage $requirement
     *
     * @return ApplicationImage
     */
    public function setRequirement(RequirementContract $requirement)
    {
        if ( ! $requirement instanceof RequirementImage) {
            throw new \InvalidArgumentException('Requirement should be RequirementImage!');
        }

        $this->requirement = $requirement;
        $this->setScholarship($requirement->getScholarship());

        return $this;
    }

    /**
     * Get requirementImage
     *
     * @return RequirementImage
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
     * @return ApplicationImage
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
     * @return int
     */
    public function getFromCamera()
    {
        return $this->fromCamera;
    }

    /**
     * @param int $fromCamera
     *
     * @return ApplicationImage
     */
    public function setFromCamera(int $fromCamera)
    {
        $this->fromCamera = $fromCamera;

        return $this;
    }

    /**
     * @ORM\postRemove
     */
    public function postRemove()
    {
        $this->getScholarship()->removeApplicationImage($this);
    }
}

