<?php

namespace App\Entity;

use App\Facades\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * EligibilityCache
 *
 * @ORM\Table(name="eligibility_cache", indexes={@ORM\Index(name="fk_eligibility_cache_account_id", columns={"account_id"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\EligibilityCacheRepository")
 */
class EligibilityCache
{
    /**
     * Any cache per account
     */
    const CACHE_KEY_ACCOUNT = 'elb-cache-account-%s';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_shown_scholarship_ids", type="string", nullable=true)
     */
    private $lastShownScholarshipIds;

    /**
     * @var json|null
     *
     * @ORM\Column(name="eligible_scholarship_ids", type="string", nullable=true)
     */
    private $eligibleScholarshipIds;

    /**
     * @var \Account|int
     *
     * Foreign key constraint is not set in order to avoid deadlocks
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", unique=true)
     * })
     */
    private $account;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $updatedAt;

    /**
     * @var int
     */
    protected $notSeenScholarshipCount = 0;

    /**
     * @var int
     */
    protected $notSeenScholarshipAmount = 0;

    /**
     * @var array
     */
    protected $notSeenScholarshipIds = 0;

    /**
     * @var int
     */
    protected $eligibleScholarshipCount = 0;

    /**
     * @var int
     */
    protected $eligibleScholarshipAmount = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getLastShownScholarshipIds($extractIds = true)
    {
        $result = $this->lastShownScholarshipIds;

        if (!$result) {
            return [];
        }

        if (is_string($result)) {
            $result = json_decode($result, true);
        }

        return $extractIds ? array_keys($result) : $result;
    }


    /**
     * @param $lastShownScholarshipIds
     */
    public function setLastShownScholarshipIds($lastShownScholarshipIds): void
    {
        $this->lastShownScholarshipIds = $lastShownScholarshipIds;
    }

    /**
     * @return array
     */
    public function getEligibleScholarshipIds($extractIds = true)
    {
        $eligibleScholarshipIds = $this->eligibleScholarshipIds;

        if (!$eligibleScholarshipIds) {
            return [];
        }

        if (is_string($this->eligibleScholarshipIds)) {
            $eligibleScholarshipIds = json_decode($this->eligibleScholarshipIds, true);
        }

        return $extractIds ? array_keys($eligibleScholarshipIds) : $eligibleScholarshipIds;
    }

    /**
     * @param  $eligibleScholarshipIds
     */
    public function setEligibleScholarshipIds($eligibleScholarshipIds): void
    {
        $this->eligibleScholarshipIds = $eligibleScholarshipIds;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }


    /**
     * @param Account|int $account
     */
    public function setAccount($account): void
    {
        $account = ($account instanceof Account) ? $account: EntityManager::getReference(Account::class, $account);
        $this->account = $account;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getNotSeenScholarshipCount()
    {
        $diff = array_diff($this->getEligibleScholarshipIds(), $this->getLastShownScholarshipIds());
        $this->notSeenScholarshipCount = count($diff);

        return $this->notSeenScholarshipCount;
    }

    /**
     * @return int
     */
    public function getNotSeenScholarshipAmount()
    {
        $notSeenIds = $this->getNotSeenScholarshipIds();
        $eligibleIdsWithAmount = $this->getEligibleScholarshipIds(false);
        $notSeenAmount = 0;

        foreach ($eligibleIdsWithAmount as $id => $val) {
            if (in_array($id, $notSeenIds)) {
                $notSeenAmount += $val;
            }
        }

        $this->notSeenScholarshipAmount = $notSeenAmount;

        return $this->notSeenScholarshipAmount;
    }

    /**
     * @return array
     */
    public function getNotSeenScholarshipIds()
    {
        $diff = array_diff($this->getEligibleScholarshipIds(), $this->getLastShownScholarshipIds());
        $this->notSeenScholarshipIds = $diff;

        return array_values($this->notSeenScholarshipIds);
    }

    /**
     * @return int
     */
    public function getEligibleScholarshipCount()
    {
        $this->eligibleScholarshipAmount = count($this->getEligibleScholarshipIds());

        return $this->eligibleScholarshipAmount;
    }

    /**
     * @return float|int
     */
    public function getEligibleScholarshipAmount()
    {
        $this->eligibleScholarshipCount = array_sum($this->getEligibleScholarshipIds(false));

        return $this->eligibleScholarshipCount;
    }

    /**
     * @param bool $fullData
     * @return array
     */
    public function toArray(bool $fullData = false): array
    {
        return [
            'id' => $this->getId(),
            'accountId' => $this->getAccount()->getAccountId(),
            'lastShownScholarshipIds' => $this->getLastShownScholarshipIds(!$fullData),
            'eligibleScholarshipIds' => $this->getEligibleScholarshipIds(!$fullData),
            'notSeenScholarshipCount' => $this->getNotSeenScholarshipCount(),
            'notSeenScholarshipAmount' => $this->getNotSeenScholarshipAmount(),
            'notSeenScholarshipIds' => $this->getNotSeenScholarshipIds(),
            'eligibleScholarshipAmount' => $this->getEligibleScholarshipAmount(),
            'eligibleScholarshipCount' => $this->getEligibleScholarshipCount()
        ];
    }

    /**
     * Called on json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
