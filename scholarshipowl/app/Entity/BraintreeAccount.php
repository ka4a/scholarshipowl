<?php namespace App\Entity;

use App\Payment\Braintree\BraintreeTransactionData;
use Braintree\Configuration;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * BraintreeAccount
 *
 * @ORM\Table(name="braintree_account")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\BraintreeAccountRepository")
 */
class BraintreeAccount
{
    use Timestamps;

    const SETTING_DEFAULT_ACCOUNT = 'payment.braintree.default';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="merchant_id", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $merchantId;

    /**
     * @var string
     *
     * @ORM\Column(name="public_key", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $publicKey;

    /**
     * @var string
     *
     * @ORM\Column(name="private_key", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $privateKey;

    /**
     * BraintreeAccount constructor.
     *
     * @param string $name
     * @param string $merchantId
     * @param string $publicKey
     * @param string $privateKey
     */
    public function __construct(string $name, string $merchantId, string $publicKey, string $privateKey)
    {
        $this->setName($name);
        $this->setMerchantId($merchantId);
        $this->setPublicKey($publicKey);
        $this->setPrivateKey($privateKey);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BraintreeAccount
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set merchantId
     *
     * @param string $merchantId
     *
     * @return BraintreeAccount
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Get merchantId
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Set publicKey
     *
     * @param string $publicKey
     *
     * @return BraintreeAccount
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set privateKey
     *
     * @param string $privateKey
     *
     * @return BraintreeAccount
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Get privateKey
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}

