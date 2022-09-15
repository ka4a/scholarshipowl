<?php namespace App\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Http\Request;

/**
 * AdminActivityLog
 *
 * @ORM\Table(name="admin_activity_log")
 * @ORM\Entity
 */
class AdminActivityLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="admin_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $adminId;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $adminName;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * AdminActivityLog constructor.
     *
     * @param Admin  $admin
     * @param string $route
     * @param string $data
     */
    public function __construct(Admin $admin, string $route, string $data)
    {
        $this->setAdminId($admin->getAdminId());
        $this->setAdminName($admin->getName());
        $this->setRoute($route);
        $this->setData($data);
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
     * Set adminId
     *
     * @param integer $adminId
     *
     * @return AdminActivityLog
     */
    public function setAdminId($adminId)
    {
        $this->adminId = $adminId;

        return $this;
    }

    /**
     * Get adminId
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set adminName
     *
     * @param string $adminName
     *
     * @return AdminActivityLog
     */
    public function setAdminName($adminName)
    {
        $this->adminName = $adminName;

        return $this;
    }

    /**
     * Get adminName
     *
     * @return string
     */
    public function getAdminName()
    {
        return $this->adminName;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return AdminActivityLog
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return AdminActivityLog
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AdminActivityLog
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
}

