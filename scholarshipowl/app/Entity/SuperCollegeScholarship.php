<?php namespace App\Entity;

use App\Entity\Traits\Hydratable;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * SuperCollegeScholarship
 *
 * @ORM\Table(name="super_college_scholarship")
 * @ORM\Entity
 */
class SuperCollegeScholarship
{
    use Hydratable;
    use Timestamps;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=true, unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=36)
     */
    protected $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="patron", type="string", length=255)
     */
    protected $patron;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=255)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=255)
     */
    protected $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255)
     */
    protected $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="address3", type="string", length=255)
     */
    protected $address3;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=255)
     */
    protected $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="deadline", type="string", length=255)
     */
    protected $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="how_to_apply", type="text")
     */
    protected $howToApply;

    /**
     * @var integer
     *
     * @ORM\Column(name="level_min", type="integer", nullable=true, options={"unsigned":true})
     */
    protected $levelMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="level_max", type="integer", nullable=true, options={"unsigned":true})
     */
    protected $levelMax;

    /**
     * @var int
     *
     * @ORM\Column(name="awards", type="integer", nullable=true, options={"unsigned":true})
     */
    protected $awards;

    /**
     * @var int
     *
     * @ORM\Column(name="renew", type="integer", nullable=true, options={"unsigned":true})
     */
    protected $renew;

    /**
     * @var string
     *
     * @ORM\Column(name="eligibility", type="text")
     */
    protected $eligibility;

    /**
     * @var string
     *
     * @ORM\Column(name="purpose", type="text")
     */
    protected $purpose;

    /**
     * @var ArrayCollection|SuperCollegeScholarshipMatch[]
     *
     * @ORM\OneToMany(targetEntity="SuperCollegeScholarshipMatch", mappedBy="superCollegeScholarship", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     */
    private $superCollegeScholarshipMatches;

    /**
     * SuperCollegeScholarship constructor.
     */
    public function __construct()
    {
        $this->superCollegeScholarshipMatches = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPatron()
    {
        return $this->patron;
    }

    /**
     * @param string $patron
     */
    public function setPatron($patron)
    {
        $this->patron = $patron;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
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
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * @param string $address3
     */
    public function setAddress3($address3)
    {
        $this->address3 = $address3;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param string $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return string
     */
    public function getHowToApply()
    {
        return $this->howToApply;
    }

    /**
     * @param string $howToApply
     */
    public function setHowToApply($howToApply)
    {
        $this->howToApply = $howToApply;
    }

    /**
     * @return int
     */
    public function getLevelMin()
    {
        return $this->levelMin;
    }

    /**
     * @param int $levelMin
     */
    public function setLevelMin($levelMin)
    {
        $this->levelMin = $levelMin;
    }

    /**
     * @return int
     */
    public function getLevelMax()
    {
        return $this->levelMax;
    }

    /**
     * @param int $levelMax
     */
    public function setLevelMax($levelMax)
    {
        $this->levelMax = $levelMax;
    }

    /**
     * @return int
     */
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * @param int $awards
     */
    public function setAwards($awards)
    {
        $this->awards = $awards;
    }

    /**
     * @return int
     */
    public function getRenew()
    {
        return $this->renew;
    }

    /**
     * @param int $renew
     */
    public function setRenew($renew)
    {
        $this->renew = $renew;
    }

    /**
     * @return string
     */
    public function getEligibility()
    {
        return $this->eligibility;
    }

    /**
     * @param string $eligibility
     */
    public function setEligibility($eligibility)
    {
        $this->eligibility = $eligibility;
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return SuperCollegeScholarshipMatch[]|ArrayCollection
     */
    public function getSuperCollegeScholarshipMatches()
    {
        return $this->superCollegeScholarshipMatches;
    }

    /**
     * @param SuperCollegeScholarshipMatch $superCollegeScholarshipMatch
     *
     * @return $this
     */
    public function addSuperCollegeScholarshipMatch(SuperCollegeScholarshipMatch $superCollegeScholarshipMatch)
    {
        if (!$this->superCollegeScholarshipMatches->contains($superCollegeScholarshipMatch)) {
            $this->superCollegeScholarshipMatches->add($superCollegeScholarshipMatch->setSuperCollegeScholarship($this));
        }

        return $this;
    }

    /**
     * @param SuperCollegeScholarshipMatch $superCollegeScholarshipMatch
     *
     * @return $this
     */
    public function removeSuperCollegeScholarshipMatch(SuperCollegeScholarshipMatch $superCollegeScholarshipMatch)
    {
        $this->superCollegeScholarshipMatches->removeElement($superCollegeScholarshipMatch);

        return $this;
    }
}