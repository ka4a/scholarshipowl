<?php namespace App\Entity;

use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * ApplicationInput
 *
 * @ORM\Table(name="application_input", uniqueConstraints={@ORM\UniqueConstraint(name="unique_account_requirement_input", columns={"account_id", "requirement_input_id"})}, indexes={@ORM\Index(name="application_input_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="application_input_requirement_input_id_foreign", columns={"requirement_input_id"}),@ORM\Index(name="application_input_account_id_scholarship_id_index", columns={"account_id", "scholarship_id"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class ApplicationInput implements ApplicationRequirementContract
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
     * @var \App\Entity\Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var \App\Entity\RequirementInput
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementInput", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_input_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $requirement;

    /**
     * @var \App\Entity\Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="applicationInputs")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=true)
     */
    private $scholarship;

    /**
     * ApplicationInput constructor.
     *
     * @param RequirementInput $requirementInput
     * @param Account $account
     * @param string $text
     */
    public function __construct(
        RequirementInput $requirementInput,
        Account $account,
        string          $text = null
    )
    {
        $this->setRequirement($requirementInput);
        $this->setAccount($account);

        if($text){
            $this->setText($text);
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
     * Set account
     *
     * @param Account $account
     *
     * @return ApplicationInput
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
     * Set requirementInput
     *
     * @param \App\Entity\RequirementInput $requirement
     *
     * @return ApplicationInput
     */
    public function setRequirement(RequirementContract $requirement)
    {
        if ( ! $requirement instanceof RequirementInput) {
            throw new \InvalidArgumentException('Requirement should be RequirementInput!');
        }

        $this->requirement = $requirement;
        $this->setScholarship($requirement->getScholarship());

        return $this;
    }

    /**
     * Get requirementInput
     *
     * @return RequirementInput
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
     * @return ApplicationInput
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
     * @ORM\postRemove
     */
    public function postRemove()
    {
        $this->getScholarship()->removeApplicationInput($this);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }
}

