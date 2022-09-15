<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AccountScholarshipEligible
 *
 * @ORM\Table(name="account_scholarship_eligible")
 * @ORM\Entity
 */
class AccountEligibilityOld
{
    /**
     * @var array
     *
     * @ORM\Column(name="list", type="json", nullable=false)
     */
    private $list;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    private $account;

    /**
     * AccountScholarshipEligible constructor.
     *
     * @param Account $account
     * @param array   $list
     */
    public function __construct(array $list)
    {
        $this->setList($list);
    }

    /**
     * @param Scholarship|int p$scholarship
     *
     * @return bool
     */
    public function check($scholarship)
    {
        $id = ($scholarship instanceof Scholarship) ? $scholarship->getScholarshipId() : $scholarship;

        return isset($this->list[$id]) && $this->list[$id] === $id;
    }

    /**
     * @param Account $account
     *
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param array $list
     *
     * @return $this
     */
    public function setList(array $list)
    {
        $this->list = $list;
        $this->count = count($list);

        return $this;
    }

    /**
     * @param Scholarship|int $scholarship
     *
     * @return $this
     */
    public function addScholarship($scholarship)
    {
        $id = ($scholarship instanceof Scholarship) ? $scholarship->getScholarshipId() : $scholarship;
        $this->list[$id] = $id;

        return $this;
    }

    /**
     * @param Scholarship|int $scholarship
     *
     * @return $this
     */
    public function removeScholarship($scholarship)
    {
        $id = ($scholarship instanceof Scholarship) ? $scholarship->getScholarshipId() : $scholarship;

        if (isset($this->list[$id])) unset($this->list[$id]);

        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

