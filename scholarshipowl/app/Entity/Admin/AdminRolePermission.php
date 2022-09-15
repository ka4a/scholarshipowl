<?php

namespace App\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminRolePermission
 *
 * @ORM\Table(name="admin_role_permission", indexes={@ORM\Index(name="IDX_53AD1461123FA025", columns={"admin_role_id"})})
 * @ORM\Entity
 */
class AdminRolePermission
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="permission", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $permission;

    /**
     * @var \App\Entity\Admin\AdminRole
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="App\Entity\Admin\AdminRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_role_id", referencedColumnName="admin_role_id", nullable=true)
     * })
     */
    private $adminRole;

    /**
     * AdminRolePermission constructor.
     *
     * @param      $permission
     * @param null $adminRole
     */
    public function __construct($permission, $adminRole = null)
    {
        $this->setPermission($permission);
        $this->setAdminRole($adminRole);
    }

    /**
     * Set permission
     *
     * @param string $permission
     *
     * @return AdminRolePermission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set adminRole
     *
     * @param AdminRole $adminRole
     *
     * @return AdminRolePermission
     */
    public function setAdminRole(AdminRole $adminRole)
    {
        $this->adminRole = $adminRole;

        return $this;
    }

    /**
     * Get adminRole
     *
     * @return AdminRole
     */
    public function getAdminRole()
    {
        return $this->adminRole;
    }
}

