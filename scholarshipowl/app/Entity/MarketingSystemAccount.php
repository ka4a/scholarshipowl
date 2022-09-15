<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarketingSystemAccount
 *
 * @ORM\Table(name="marketing_system_account", indexes={@ORM\Index(name="ix_marketing_system_account_account_id", columns={"account_id"}), @ORM\Index(name="ix_marketing_system_account_marketing_id", columns={"marketing_system_id"}), @ORM\Index(name="ix_marketing_system_conversion_date", columns={"conversion_date"})})
 * @ORM\Entity
 */
class MarketingSystemAccount
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="conversion_date", type="datetime", nullable=false)
     */
    private $conversionDate;

    /**
     * @var MarketingSystem
     *
     * @ORM\ManyToOne(targetEntity="MarketingSystem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="marketing_system_id", referencedColumnName="marketing_system_id")
     * })
     */
    private $marketingSystem;

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
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return MarketingSystem
     */
    public function getMarketingSystem()
    {
        return $this->marketingSystem;
    }

    /**
     * @return \DateTime
     */
    public function getConversionDate()
    {
        return $this->conversionDate;
    }
}

