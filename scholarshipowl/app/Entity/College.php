<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * College
 *
 * @ORM\Table(name="college")
 * @ORM\Entity
 */
class College
{
    /**
     * @var integer
     *
     * @ORM\Column(name="college_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $collegeId;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country", referencedColumnName="country_id")
     * })
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="canonical_name", type="string", length=255, nullable=false)
     */
    private $canonicalName;

    /**
     * @var string
     *
     * @ORM\Column(name="colloquial_name", type="string", length=255, nullable=true)
     */
    private $colloquialName;

    /**
     * @var string
     *
     * @ORM\Column(name="doe_code", type="string", length=10, nullable=true)
     */
    private $doeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="iped_code", type="string", length=10, nullable=true)
     */
    private $ipedCode;

    /**
     * College constructor.
     *
     * @param string $canonicalName
     * @param string $colloquialName
     * @param string $doeCode
     * @param string $ipedCode
     */
    public function __construct($canonicalName, $colloquialName, $doeCode, $ipedCode)
    {
        $this->setCanonicalName($canonicalName);
        $this->setColloquialName($colloquialName);
        $this->setDoeCode($doeCode);
        $this->setIpedCode($ipedCode);
    }

    /**
     * @return int
     */
    public function getCollegeId() {
        return $this->collegeId;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param int|Country $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = Country::convert($country);
        return $this;
    }

    /**
     * @param int $collegeId
     */
    public function setCollegeId($collegeId) {
        $this->collegeId = $collegeId;
    }

    /**
     * @return string
     */
    public function getCanonicalName() {
        return $this->canonicalName;
    }

    /**
     * @param string $canonicalName
     */
    public function setCanonicalName($canonicalName) {
        $this->canonicalName = $canonicalName;
    }

    /**
     * @return string
     */
    public function getColloquialName() {
        return $this->colloquialName;
    }

    /**
     * @param string $colloquialName
     */
    public function setColloquialName($colloquialName) {
        $this->colloquialName = $colloquialName;
    }

    /**
     * @return string
     */
    public function getDoeCode() {
        return $this->doeCode;
    }

    /**
     * @param string $doeCode
     */
    public function setDoeCode($doeCode) {
        $this->doeCode = $doeCode;
    }

    /**
     * @return string
     */
    public function getIpedCode() {
        return $this->ipedCode;
    }

    /**
     * @param string $ipedCode
     */
    public function setIpedCode($ipedCode) {
        $this->ipedCode = $ipedCode;
    }

}

