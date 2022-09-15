<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * FeatureCompanyDetailsSet
 *
 * @ORM\Table(name="feature_company_details_set", uniqueConstraints={@ORM\UniqueConstraint(name="feature_company_details_set_id_uindex", columns={"id"})})
 * @ORM\Entity
 */
class FeatureCompanyDetailsSet
{
    use Dictionary;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name_2", type="string", length=255, nullable=true)
     */
    private $companyName2;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address_1", type="text", length=65535, nullable=true)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address_2", type="text", length=65535, nullable=true)
     */
    private $address2;

    /**
     * @return FeatureCompanyDetailsSet
     */
    static public function config(){
        return FeaturePaymentSet::config()->getPaymentMethod()->getFeatureCompanyDetailsSet();
    }

    /**
     * FeatureCompanyDetailsSet constructor.
     *
     * @param string $companyName
     * @param string $companyName2
     * @param string $name
     * @param string $address1
     * @param string $address2
     */
    public function __construct($companyName, $companyName2 = null, $name, $address1, $address2 = null)
    {
        $this->companyName = $companyName;
        $this->companyName2 = $companyName2;
        $this->name = $name;
        $this->address1 = $address1;
        $this->address2 = $address2;
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
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     *
     * @return FeatureCompanyDetailsSet
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyName2()
    {
        return $this->companyName2;
    }

    /**
     * @param string $companyName2
     *
     * @return FeatureCompanyDetailsSet
     */
    public function setCompanyName2($companyName2)
    {
        $this->companyName2 = $companyName2;

        return $this;
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
     * @return FeatureCompanyDetailsSet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     *
     * @return FeatureCompanyDetailsSet
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     *
     * @return FeatureCompanyDetailsSet
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s (%s), %s', $this->getName(), $this->getCompanyName(), $this->getAddress1());
    }
}

