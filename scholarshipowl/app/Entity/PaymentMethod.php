<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PaymentMethod
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="payment_method")
 */
class PaymentMethod
{
    use Dictionary;

    const CREDIT_CARD = 1;
    const PAYPAL = 2;
    const BRAINTREE = 3;
    const RECURLY = 4;
    /**
     * TODO: add migration
     */
    const STRIPE = 5;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="payment_method_id", type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var FeatureCompanyDetailsSet
     *
     * @ORM\OneToOne(targetEntity="FeatureCompanyDetailsSet",  fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="feature_company_details_set_id", referencedColumnName="id")
     * })
     */

    private $featureCompanyDetailsSet;

    /**
     * @return int
     */
    public static function getDefault()
    {
        return FeaturePaymentSet::config()->getPaymentMethod()->getId();
    }

    /**
     * Is new checkout should be used.
     *
     * @return bool
     */
    public static function isCheckout()
    {
        return static::isRecurly() || static::isBraintree() || static::isStripe();
    }

    /**
     * @return bool
     */
    public static function isBraintree()
    {
        return static::getDefault() === static::BRAINTREE;
    }

    /**
     * @return bool
     */
    public static function isRecurly()
    {
        return static::getDefault() === static::RECURLY;
    }

    /**
     * @return bool
     */
    public static function isStripe()
    {
        return static::getDefault() === static::STRIPE;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return PaymentMethod
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return FeatureCompanyDetailsSet
     */
    public function getFeatureCompanyDetailsSet()
    {
        return $this->featureCompanyDetailsSet;
    }

    /**
     * @param FeatureCompanyDetailsSet|int $featureCompanyDetailsSet
     *
     * @return PaymentMethod
     */
    public function setFeatureCompanyDetailsSet($featureCompanyDetailsSet)
    {
        $this->featureCompanyDetailsSet = FeatureCompanyDetailsSet::convert($featureCompanyDetailsSet);

        return $this;
    }
}
