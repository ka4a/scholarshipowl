<?php namespace App\Entity\Admin;

use App\Contracts\HasPermission;
use App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity
 */
class Admin implements Authenticatable, HasPermission
{
    use Authorizable;
    use Timestamps;

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLED = 'disabled';

    /**
     * @var integer
     *
     * @ORM\Column(name="admin_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="remember_token", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     */
    private $rememberToken;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     */
    private $status;

    /**
     * @var Account
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var \App\Entity\Admin\AdminRole
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin\AdminRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_role_id", referencedColumnName="admin_role_id", nullable=true)
     * })
     */
    private $adminRole;

    /**
     * Admin constructor.
     *
     * @param string    $name
     * @param string    $email
     * @param string    $status
     * @param string    $password
     * @param AdminRole $adminRole
     */
    public function __construct(
        string $name,
        string $email,
        string $status,
        string $password,
        AdminRole $adminRole
    )
    {
        $this->setName($name);
        $this->setEmail($email);
        $this->setStatus($status);
        $this->setAdminRole($adminRole);
        $this->setHashPassword($password);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getAdminId()
    {
        return $this->adminId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Admin
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
     * Set email
     *
     * @param string $email
     *
     * @return Admin
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param $password
     *
     * @return Admin
     */
    public function setHashPassword($password) {
        return $this->setPassword(\Hash::make($password));
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set rememberToken
     *
     * @param string $rememberToken
     *
     * @return Admin
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * Get rememberToken
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Account $account
     *
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return int
     */
    public function getAccountId()
    {
        return $this->getAccount()->getAccountId();
    }

    /**
     * Set adminRole
     *
     * @param AdminRole $adminRole
     *
     * @return Admin
     */
    public function setAdminRole(AdminRole $adminRole = null)
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

    /**
     * @param int $roleId
     *
     * @return bool
     */
    public function isRole(int $roleId)
    {
        return $this->getAdminRole()->is($roleId);
    }

    /**
     * @param string $check
     * @param bool   $all
     *
     * @return bool
     */
    public function hasPermissionTo($check, $all = false)
    {
        return $this->getAdminRole()->hasPermissionTo($check, $all);
    }

    /**
     * @inheritdoc
     */
    public function getEmailForPasswordReset()
    {
        return $this->getEmail();
    }

    /**
     * @inheritdoc
     */
    public function getAuthIdentifierName()
    {
        return 'adminId';
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getAuthIdentifier()
    {
        return $this->getAdminId();
    }

    /**
     * @inheritdoc
     */
    public function getAuthPassword()
    {
        return $this->getPassword();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }

    /**
     * @param string $k
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $k)
    {
        $getter = 'get'.ucfirst($k);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \Exception("Can not get property [ $k ]");
    }
}

