<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentFsetHistory
 *
 * @ORM\Table(name="payment_fset_history")
 * @ORM\Entity
 */
class PaymentFsetHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=true)
     */
    private $accountId;

    /**
     * @var integer
     *
     * @ORM\Column(name="fset_id", type="integer", nullable=true)
     */
    private $fsetId;

    /**
     * @var integer
     *
     * @ORM\Column(name="fset_title", type="integer", nullable=true)
     */
    private $fsetTitle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * PaymentFsetHistory constructor.
     *
     * @param int $accountId
     * @param int $fsetId
     * @param string $fsetTitle
     * @param string $createdDate
     */
    public function __construct($accountId, $fsetId, $fsetTitle, $createdDate)
    {
        $this->accountId = $accountId;
        $this->fsetId = $fsetId;
        $this->fsetTitle = $fsetTitle;
        $this->createdDate = $createdDate;
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
     * @return PaymentFsetHistory
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFsetId()
    {
        return $this->fsetId;
    }

    /**
     * @param int $fsetId
     *
     * @return PaymentFsetHistory
     */
    public function setFsetId($fsetId)
    {
        $this->fsetId = $fsetId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFsetTitle()
    {
        return $this->fsetTitle;
    }

    /**
     * @param int $fsetTitle
     *
     * @return PaymentFsetHistory
     */
    public function setFsetTitle($fsetTitle)
    {
        $this->fsetTitle = $fsetTitle;

        return $this;
    }
}

