<?php namespace App\Entity;

use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationSurvey
 *
 * @ORM\Table(name="application_survey", uniqueConstraints={@ORM\UniqueConstraint(name="unique_account_requirement_survey", columns={"account_id", "requirement_survey_id"})}, indexes={@ORM\Index(name="application_survey_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="application_survey_account_id_scholarship_id_index", columns={"account_id", "scholarship_id"}), @ORM\Index(name="application_survey_requirement_survey_id_foreign", columns={"requirement_survey_id"}), @ORM\Index(name="IDX_1D6C97399B6B5FBA", columns={"account_id"})})
 * @ORM\Entity
 */
class ApplicationSurvey implements ApplicationRequirementContract
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
     * @var json
     *
     * @ORM\Column(name="answers", type="json", nullable=false)
     */
    private $answers;

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
     * @var RequirementSurvey
     *
     * @ORM\ManyToOne(targetEntity="RequirementSurvey")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_survey_id", referencedColumnName="id")
     * })
     */
    private $requirement;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="applicationSurvey")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * ApplicationSurvey constructor.
     * @param RequirementContract $requirement
     * @param array $answers
     */
    public function __construct(
        RequirementContract $requirement,
        array $answers,
        Account $account
    )
    {
        $this->setRequirement($requirement);
        $this->setAnswers($answers);
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
     * Get account
     *
     * @return Account
     */
    public function getAccount()
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
     * Set requirementText
     *
     * @param RequirementSurvey $requirement
     *
     * @return ApplicationSurvey
     */
    public function setRequirement(RequirementContract $requirement)
    {
        if ( ! $requirement instanceof RequirementSurvey) {
            throw new \InvalidArgumentException('Requirement should be RequirementSurvey!');
        }

        $this->requirement = $requirement;
        $this->setScholarship($requirement->getScholarship());

        return $this;
    }

    /**
     * Get requirementText
     *
     * @return RequirementSurvey
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
     * @return ApplicationSurvey
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
     * @param array $answers
     */
    public function setAnswers(array $answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return json
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Return answers in styled string format
     * @return string
     */
    public function getStyledAnswers()
    {
        $styledAnswers = '';
        $answers = $this->getAnswers();
        foreach ($answers as $answer) {
            $styledAnswers.= $answer['question'].': <br>';
            foreach ($answer['options'] as $op) {
                $styledAnswers .= "&nbsp; ". $op.'<br>';
            }

        }

        return $styledAnswers.'<br>';
    }

}
