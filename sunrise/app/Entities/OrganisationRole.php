<?php namespace App\Entities;

use App\Traits\DictionaryEntity;
use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use LaravelDoctrine\ACL\Mappings as ACL;

/**
 * Organization role. Used for managing organizations ACL.
 * Each organization should have it is own roles.
 *
 * Default organization roles should be created on organization create.
 *
 * @ORM\Entity()
 * @ORM\Table(name="organisation_role")
 *
 * @static OrganisationRole convert($entity)
 */
class OrganisationRole  implements RoleContract, JsonApiResource, BelongsToOrganisation
{
    use Timestamps;
    use HasPermissions;
    use DictionaryEntity;

    /**
     * @return string
     */
    public static function getResourceKey()
    {
        return 'organization_role';
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotNull()
     * @Assert\Length(min="3", max="255")
     */
    protected $name;

    /**
     * @var bool
     * @ORM\Column(name="is_owner", type="boolean")
     */
    protected $owner = false;

    /**
     * @var ArrayCollection
     *
     * @ACL\HasPermissions()
     */
    protected $permissions;

    /**
     * @var Organisation
     * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="roles", fetch="EAGER")
     * @ORM\JoinColumn(name="organisation_id", nullable=false)
     */
    protected $organisation;

    /**
     * @var ArrayCollection|User[]
     * @ORM\ManyToMany(targetEntity="User", mappedBy="organisationRoles", cascade={"all"})
     * @ORM\JoinTable(
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="organization_role_id",
     *             referencedColumnName="id",
     *             nullable=false
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="user_id",
     *             referencedColumnName="id",
     *             nullable=false
     *         )
     *     }
     * )
     */
    protected $users;

    /**
     * UserRole constructor.
     */
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @param array|ArrayCollection $permissions
     *
     * @return $this
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param Organisation $organisation
     * @return $this
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUsers(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUsers(User $user)
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * @return User[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param bool $owner
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOwner()
    {
        return $this->owner;
    }
}
