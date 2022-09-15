<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuperCollegeScholarshipMatch
 *
 * @ORM\Table(name="super_college_scholarship_match")
 * @ORM\Entity
 */
class SuperCollegeScholarshipMatch
{
    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=false)
     * })
     */
    private $account;

    /**
     * @var SuperCollegeScholarship
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="App\Entity\SuperCollegeScholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="super_college_scholarship_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $superCollegeScholarship;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="match_date", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $matchDate;

    /**
     * SuperCollegeScholarshipMatch constructor.
     *
     * @param Account $account
     * @param SuperCollegeScholarship $superCollegeScholarship
     */
    public function __construct(Account $account, SuperCollegeScholarship $superCollegeScholarship)
    {
        $this->setAccount($account);
        $this->setSuperCollegeScholarship($superCollegeScholarship);
        $this->setMatchDate(new \DateTime());
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return SuperCollegeScholarship
     */
    public function getSuperCollegeScholarship()
    {
        return $this->superCollegeScholarship;
    }

    /**
     * @param SuperCollegeScholarship $superCollegeScholarship
     *
     * @return $this
     */
    public function setSuperCollegeScholarship($superCollegeScholarship)
    {
        $this->superCollegeScholarship = $superCollegeScholarship;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMatchDate()
    {
        return $this->matchDate;
    }

    /**
     * @param \DateTime $matchDate
     */
    public function setMatchDate($matchDate)
    {
        $this->matchDate = $matchDate;
    }
}
