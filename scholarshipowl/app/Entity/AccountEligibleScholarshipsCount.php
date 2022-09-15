<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * AccountEligibleScholarshipsCount
 *
 * @ORM\Table(name="account_eligible_scholarships_count")
 * @ORM\Entity
 */
class AccountEligibleScholarshipsCount
{
    use Timestamps;

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account", mappedBy="account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    private $account;

    /**
     * @var int
     *
     * @ORM\Column(name="scholarship_count", type="string", length=32, nullable=false, options={"unsigned"=true})
     */
    private $scholarshipCount;

    /**
     * AccountEligibleScholarshipsCount constructor.
     * @param Account $account
     * @param int $scholarshipCount
     */
    public function __construct(Account $account, $scholarshipCount)
    {
        $this->account = $account;
        $this->scholarshipCount = $scholarshipCount;
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
     *
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return int
     */
    public function getScholarshipCount()
    {
        return $this->scholarshipCount;
    }

    /**
     * @param int $scholarshipCount
     */
    public function setScholarshipCount($scholarshipCount)
    {
        $this->scholarshipCount = $scholarshipCount;
    }
}
