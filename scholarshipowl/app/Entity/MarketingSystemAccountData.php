<?php namespace App\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * MarketingSystemAccountData
 *
 * @ORM\Table(name="marketing_system_account_data", indexes={@ORM\Index(name="ix_marketing_system_account_data_account_id", columns={"account_id"}), @ORM\Index(name="ix_marketing_system_account_data_name", columns={"name"})})
 * @ORM\Entity
 */
class MarketingSystemAccountData
{
    const AFFILIATE_ID = 'affiliate_id';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=1023, nullable=false)
     */
    private $value;

    /**
     * @var \Account
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
     * @param Account $account
     *
     * @return mixed
     */
    public static function getAffiliateId(Account $account)
    {
        /** @var MarketingSystemAccountData $data */
        $data =  $account->getMarketingData()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('name', static::AFFILIATE_ID)))
            ->first();

        return $data ? $data->getValue() : null;
    }

    /**
     * MarketingSystemAccountData constructor.
     *
     * @param string  $name
     * @param null    $value
     */
    public function __construct(string $name, $value = null)
    {
        $this->setName($name)->setValue($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $account
     *
     * @return $this
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return \Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

