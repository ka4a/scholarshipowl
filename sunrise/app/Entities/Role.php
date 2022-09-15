<?php namespace App\Entities;

use App\Traits\DictionaryEntity;
use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Mappings as ACL;
use Symfony\Component\Validator\Constraints as Assert;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Class UserRole
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="role",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="ix_name", columns={"name"})},
 * )
 */
class Role implements RoleContract, JsonApiResource
{
    use DictionaryEntity;
    use HasPermissions;
    use Timestamps;

    const ROOT = 1;

    /**
     * @return static
     */
    public static function root()
    {
        return static::find(static::ROOT);
    }

    /**
     * @return string
     */
    public static function getResourceKey()
    {
        return 'role';
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     * @Assert\NotNull()
     * @Assert\Length(min="3", max="255")
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ACL\HasPermissions()
     */
    protected $permissions;

    /**
     * UserRole constructor.
     */
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        return $this->is(static::ROOT);
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
}
