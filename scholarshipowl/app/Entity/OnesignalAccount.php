<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * OnesignalAccount
 *
 * @ORM\Table(name="onesignal_account", indexes={@ORM\Index(name="onesignal_account_user_id_index", columns={"user_id"}), @ORM\Index(name="onesignal_account_app_id_user_id_index", columns={"app_id", "user_id"}), @ORM\Index(name="onesignal_account_account_id_index", columns={"account_id"}), @ORM\Index(name="IDX_BDDC1EFD7987212D", columns={"app_id"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\OnesignalAccountRepository")
 */
class OnesignalAccount
{
    use Timestamps;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="user_id", type="string", length=36, nullable=false)
     */
    private $userId;

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="app", type="string")
     */
    private $app;

    /**
     * OnesignalAccount constructor.
     *
     * @param Account   $account
     * @param string    $id
     * @param string    $app
     */
    public function __construct(Account $account, string $id, $app)
    {
        $this->setAccount($account);
        $this->setUserId($id);
        $this->setApp($app);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setUserId(string $id)
    {
        $this->userId = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
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
     * @param string $app
     *
     * @return $this
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }
}

