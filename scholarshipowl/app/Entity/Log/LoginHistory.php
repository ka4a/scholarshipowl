<?php namespace App\Entity\Log;

use App\Entity\Account;
use App\Entity\FeatureSet;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Annotations\Restricted;

/**
 * Class LoginHistory
 *
 * @ORM\Entity
 * @ORM\Table(name="login_history")
 */
class LoginHistory
{
    const ACTION_LOGIN  = "login";
    const ACTION_LOGOUT = "logout";

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $loginHistoryId;

    /**
     * @var Account
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    protected $account;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $action;

    /**
     * @var string
     *
     * @ORM\Column(name="fset", type="string", nullable=true)
     */
    protected $featureSet;

    /**
     * @var string
     *
     * @ORM\Column(name="srv", type="string", nullable=true)
     */
    protected $srv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $actionDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     * @Restricted()
     */
    protected $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="agent", type="string", nullable=true)
     */
    protected $agent;

    /**
     * LoginHistory constructor.
     *
     * @param Account   $account
     * @param string    $action
     * @param string    $fset
     * @param string    $srv
     * @param string    $ip
     * @param string    $agent
     */
    public function __construct(Account $account, $action, $fset, $srv, $ip, $agent)
    {
        $this->setAccount($account);
        $this->setAction($action);
        $this->setFeatureSet($fset);
        $this->setSrv($srv);
        $this->setIpAddress($ip);
        $this->setAgent($agent);
    }

    /**
     * @return int
     */
    public function getLoginHistoryId()
    {
        return $this->loginHistoryId;
    }

    /**
     * @param Account $account
     *
     * @return Account
     */
    public function setAccount(Account $account)
    {
        return $this->account = $account;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param string $action
     *
     * @return string
     */
    public function setAction(string $action)
    {
        return $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $featureSet
     *
     * @return $this
     */
    public function setFeatureSet($featureSet)
    {
        $this->featureSet = $featureSet;
        return $this;
    }

    /**
     * @return string
     */
    public function getFeatureSet()
    {
        return $this->featureSet;
    }

    /**
     * @param string $agent
     *
     * @return $this
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param string $srv
     *
     * @return $this
     */
    public function setSrv($srv)
    {
        $this->srv = $srv;
        return $this;
    }

    /**
     * @return string
     */
    public function getSrv()
    {
        return $this->srv;
    }

    /**
     * @param \DateTime $time
     *
     * @return \DateTime
     */
    public function setActionDate(\DateTime $time)
    {
        return $this->actionDate = $time;
    }

    /**
     * @return \DateTime
     */
    public function getActionDate()
    {
        return $this->actionDate;
    }

    /**
     * @param string $ip
     *
     * @return string
     */
    public function setIpAddress(string $ip)
    {
        return $this->ipAddress = $ip;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }
}
