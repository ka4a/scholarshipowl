<?php namespace App\Entity\Admin;

use App\Contracts\HasPermission as HasPermissionsContract;
use App\Entity\Traits\HasPermission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * AdminRole
 *
 * @ORM\Table(name="admin_role")
 * @ORM\Entity
 */
class AdminRole implements HasPermissionsContract
{
    use HasPermission;
    use Timestamps;

    const ROOT = 1;

    const LEVEL_ACCESS_FULL = 3;
    const LEVEL_ACCESS_RESTRICTED = 2;
    const LEVEL_ACCESS_NO_DATA = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="admin_role_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminRoleId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var ArrayCollection|AdminRolePermission[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Admin\AdminRolePermission",
     *     mappedBy="adminRole",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     */
    protected $permissions;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_level", type="integer")
     */
    private $accessLevel;

    /**
     * AdminRole constructor.
     *
     * @param string $name
     * @param string $description
     * @param string $accessLevel
     */
    public function __construct(string $name, string $description, string $accessLevel)
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->accessLevel = $accessLevel;
        $this->permissions = new ArrayCollection();
    }

    /**
     * @param int $roleId
     *
     * @return bool
     */
    public function is(int $roleId)
    {
        return $this->getAdminRoleId() === $roleId;
    }

    /**
     * Get adminRoleId
     *
     * @return integer
     */
    public function getAdminRoleId()
    {
        return $this->adminRoleId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AdminRole
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
     * Set description
     *
     * @param string $description
     *
     * @return AdminRole
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AdminRole
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return AdminRole
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set accessLevel
     *
     * @param integer $accessLevel
     *
     * @return AdminRole
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * Get accessLevel
     *
     * @return integer
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }


    /**
     * @param string|AdminRolePermission $permission
     *
     * @return $this
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = \EntityManager::find(AdminRolePermission::class,
                ['adminRole' => $this, 'permission' => $permission]
            );
        }

        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
        }

        return $this;
    }

    /**
     * @param string|AdminRolePermission $permission
     *
     * @return $this
     */
    public function addPermission($permission)
    {
        if (is_string($permission)) {
            $permission = \EntityManager::find(AdminRolePermission::class,
                ['adminRole' => $this, 'permission' => $permission]
            ) ?: new AdminRolePermission($permission, $this);
        }

        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission->setAdminRole($this));
        }

        return $this;
    }

    /**
     * @return ArrayCollection|AdminRolePermission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}

