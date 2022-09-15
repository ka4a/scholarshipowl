<?php namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ORM\Notifications\Notifiable;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Class User
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_1483A5E9E7927C74", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repositories\UserRepository")
 */
class User implements
    AuthContract,
    JsonApiResource,
    AuthorizableContract,
    HasRolesContract,
    UserEntityInterface,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use Timestamps;
    use HasRoles;
    use HasPermissions;
    use HasApiTokens;
    use Notifiable;
    use CanResetPassword;

    /**
     * @return string
     */
    public static function getResourceKey()
    {
        return 'user';
    }

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var ArrayCollection
     *
     * @ORM\Column(name="permissions", type="json_array")
     */
    protected $permissions;

    /**
     * @var ArrayCollection|Role[]
     *
     * @ACL\HasRoles()
     */
    protected $roles;

    /**
     * @var ArrayCollection|OrganisationRole[]
     * @ORM\ManyToMany(targetEntity="OrganisationRole", inversedBy="users", fetch="LAZY", cascade={"all"})
     */
    protected $organisationRoles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->organisationRoles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->getId();
    }

    /**
     * @param string $name
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     *
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param UploadedFile|string $picture
     * @return $this
     */
    public function setPicture($picture)
    {
        if ($picture instanceof UploadedFile) {
            $path = sprintf('users/avatars/%d', $this->id);
            Storage::disk('public')->put($path, file_get_contents($picture), 'public');
            $picture = Storage::disk('public')->url($path);
        }

        $this->picture = $picture;
        return $this;
    }

    /**
     * @param Role[]|ArrayCollection $roles
     *
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRoles(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function removeRoles(Role $role)
    {
        $this->roles->removeElement($role);
        return $this;
    }

    /**
     * @return ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return ArrayCollection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @param int|OrganisationRole $role
     * @return $this
     */
    public function addOrganisationRoles($role)
    {
        $role = OrganisationRole::convert($role);
        if (!$this->organisationRoles->contains($role)) {
            $this->organisationRoles->add($role);
        }
        return $this;
    }

    /**
     * @param int|OrganisationRole $role
     * @return $this
     */
    public function removeOrganisationRoles($role)
    {
        $role = OrganisationRole::convert($role);
        $this->organisationRoles->removeElement($role);
        return $this;
    }

    /**
     * @return OrganisationRole[]|ArrayCollection
     */
    public function getOrganisationRoles()
    {
        return $this->organisationRoles;
    }

    /**
     * @param Organisation|int $organisation
     * @return bool
     */
    public function belongsToOrganisation($organisation)
    {
        $organisation = Organisation::convert($organisation);
        foreach ($this->getOrganisationRoles() as $role) {
            if ($role->getOrganisation() === $organisation) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int|OrganisationRole $role
     * @return bool
     */
    public function hasOrganisationRole($role)
    {
        $role = OrganisationRole::convert($role);
        foreach ($this->getOrganisationRoles() as $organisationRole) {
            if ($organisationRole === $role) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        /** @var Role $role */
        foreach ($this->getRoles() as $role) {
            if ($role->isRoot()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%d [%s]', $this->id, $this->email);
    }
}
