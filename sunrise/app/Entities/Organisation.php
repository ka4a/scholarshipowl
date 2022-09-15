<?php namespace App\Entities;

use App\Traits\DictionaryEntity;
use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\ACL\Contracts\Organisation as OrganisationContract;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Company entity that have the scholarships.
 *
 * @ORM\Entity()
 * @ORM\Table(name="organisation")
 *
 * @static Organisation convert($entity)
 */
class Organisation implements OrganisationContract, AuthenticatableContract, JsonApiResource
{
    use DictionaryEntity;
    use Authenticatable;

    const DEFAULT = 1;

    /**
     * @return string
     */
    static function getResourceKey()
    {
        return 'organisation';
    }

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $businessName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, unique=false)
     */
    protected $country;

    /**
     * @var State
     * @ORM\ManyToOne(targetEntity="State", fetch="EAGER")
     * @ORM\JoinColumn(unique=false)
     */
    protected $state;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $address2;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $zip;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $website;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * Token for API authorization.
     *
     * @var int
     * @ORM\Column(name="api_token", type="string", length=60)
     */
    protected $api_token;

    /**
     * @var ArrayCollection|OrganisationRole[]
     * @ORM\OneToMany(
     *     targetEntity="OrganisationRole",
     *     mappedBy="organisation",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    protected $roles;

    /**
     * @var ArrayCollection|ScholarshipTemplate[]
     * @ORM\OneToMany(targetEntity="ScholarshipTemplate", mappedBy="organisation")
     */
    protected $scholarships;

    /**
     * Organization constructor.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->password = str_random(40);
        $this->scholarships = new ArrayCollection();

        $this->country = Country::find(Country::USA);

        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        do {
            $this->api_token = str_random(60);
        } while ($em->getRepository(static::class)->findOneBy(['api_token' => $this->api_token]));

        // Add owner role by default to organization
        $owner = new OrganisationRole();
        $owner->setName('Owner');
        $owner->setOwner(true);

        $this->addRoles($owner);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->getId();
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
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param $businessName
     * @return $this
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param Country $country
     * @return $this
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param State $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
     * @param $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param $zip
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param $website
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setApiToken($token)
    {
        $this->api_token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->api_token;
    }

    /**
     * @return OrganisationRole[]|ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param OrganisationRole $role
     * @return $this
     */
    public function addRoles(OrganisationRole $role)
    {
        $role->setOrganisation($this);
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
        return $this;
    }

    /**
     * @param OrganisationRole|int $role
     * @return $this
     */
    public function removeRoles(OrganisationRole $role)
    {
        if ($role->isOwner()) {
            throw new \RuntimeException('Can\'t remove owner role from organization roles.');
        }
        $this->roles->removeElement($role);
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return sprintf('org-%d', $this->getId());
    }

    /**
     * @return OrganisationRole
     */
    public function getOwnerRole()
    {
        foreach ($this->roles as $organisationRole) {
            if ($organisationRole->isOwner()) {
                return $organisationRole;
            }
        }
        throw new \LogicException('Should not happen. Organisation role owner is missing.');
    }

    /**
     * @param ScholarshipTemplate $scholarship
     * @return $this
     */
    public function addScholarships(ScholarshipTemplate $scholarship)
    {
        if (!$this->scholarships->contains($scholarship)) {
            $this->scholarships->add($scholarship->setOrganisation($this));
        }
        return $this;
    }

    /**
     * @param ScholarshipTemplate $scholarship
     * @return $this
     */
    public function removeScholarships(ScholarshipTemplate $scholarship)
    {
        $this->scholarships->removeElement($scholarship);
        return $this;
    }

    /**
     * @return ScholarshipTemplate[]|ArrayCollection
     */
    public function getScholarships()
    {
        return $this->scholarships;
    }
}
