<?php namespace App\Entity;

use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationSpecialEligibility
 *
 * @ORM\Table(name="application_special_eligibility", indexes={@ORM\Index(name="application_special_eligibility_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="application_special_eligibility_account_id_foreign", columns={"account_id"})})
 * @ORM\Entity
 */
class ApplicationSpecialEligibility implements ApplicationRequirementContract
{
    use Timestamps;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \App\Entity\RequirementSpecialEligibility
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\RequirementSpecialEligibility", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $requirement;

    /**
     * @var int
     *
     * @ORM\Column(name="val", type="boolean", nullable=true)
     */
    private $val;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    private $account;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="applicationSpecialEligibility")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * ApplicationSpecialEligibility constructor.
     * @param RequirementContract $requirementSpecialEligibility
     * @param Account|null $account
     */
    public function __construct(
        RequirementSpecialEligibility $requirementSpecialEligibility,
        Account $account,
        int $val = 0
    )
    {
        $this->setRequirement($requirementSpecialEligibility);
        $this->setVal($val);
        $this->setAccount($account);
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getVal(): int
    {
        return $this->val;
    }

    /**
     * @param int $val
     */
    public function setVal(int $val): void
    {
        $this->val = $val;
    }

    /**
     * @return string
     */
    public function getStyledText()
    {
        return $this->requirement->getText()."\n &nbsp;". ($this->val ? 'Yes' : 'No');
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship(): Scholarship
    {
        return $this->scholarship;
    }

    /**
     * @param Scholarship $scholarship
     */
    public function setScholarship(Scholarship $scholarship): void
    {
        $this->scholarship = $scholarship;
    }

    /**
     * @return RequirementContract|RequirementSpecialEligibility|int
     */
    public function getRequirement()
    {
        return $this->requirement;
    }

    /**
     * @param RequirementContract $requirement
     * @return ApplicationRequirementContract|void
     */
    public function setRequirement(RequirementContract $requirement)
    {
        if (!$requirement instanceof RequirementSpecialEligibility) {
            throw new \InvalidArgumentException('Requirement should be RequirementSpecialEligibility!');
        }

        $this->requirement = $requirement;
        $this->setScholarship($requirement->getScholarship());

        return $this;

    }


}
