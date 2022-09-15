<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountFreshScholarship
 *
 * @ORM\Table(name="account_fresh_scholarship", uniqueConstraints={@ORM\UniqueConstraint(name="account_fresh_scholarship_account_id_unique", columns={"account_id"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\FreshScholarshipRepository")
 */
class AccountFreshScholarship
{
    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $accountId;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var array
     *
     * @ORM\Column(name="scholarships", type="simple_array", nullable=false)
     */
    private $scholarships;

    /**
     * AccountFreshScholarship constructor.
     *
     * @param int    $accountId
     * @param array $scholarships
     */
    public function __construct($accountId, $scholarships)
    {
        $this->accountId = $accountId;
        $this->scholarships = $scholarships;
        $this->count = count($scholarships);
    }

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     *
     * @return AccountFreshScholarship
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     *
     * @return AccountFreshScholarship
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return array
     */
    public function getScholarships()
    {
        return $this->scholarships;
    }

    /**
     * @param array $scholarships
     *
     * @return AccountFreshScholarship
     */
    public function setScholarships($scholarships)
    {
        $this->scholarships = $scholarships;

        return $this;
    }
}

