<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountsFavoriteScholarships
 *
 * @ORM\Table(name="accounts_favorite_scholarships")
 * @ORM\Entity
 */
class AccountsFavoriteScholarships
{
    /**
     * @var Account
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Account", fetch="LAZY")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    private $accountId;

    /**
     * @var Account
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Scholarship", fetch="LAZY")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * @var integer
     *
     * @ORM\Column(name="favorite", type="integer", nullable=false)
     */
    private $favorite;

    public function __construct(Account $acc, Scholarship $scholarship, $favorite = 1)
    {
        $this->accountId = $acc;
        $this->scholarship = $scholarship;
        $this->favorite = $favorite;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     *
     * @return AccountsFavoriteScholarships
     */
    public function setAccount(Account $account)
    {
        $this->accountId = $account;

        return $this;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @param int $scholarhip
     *
     * @return AccountsFavoriteScholarships
     */
    public function setScholarship($scholarship)
    {
        $this->scholarship = $scholarship;

        return $this;
    }

    /**
     * @return int
     */
    public function getFavorite(): int
    {
        return $this->favorite;
    }

    /**
     * @param int $favorite
     *
     * @return AccountsFavoriteScholarships
     */
    public function setFavorite(int $favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }
}

