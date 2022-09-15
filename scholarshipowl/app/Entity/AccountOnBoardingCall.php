<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * Class AccountOnBoardingCall
 *
 *
 * @ORM\Entity
 * @ORM\Table(name="account_onboarding_call")
 */
class AccountOnBoardingCall
{
    use Timestamps;

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account", mappedBy="accountOnboardingCall")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    protected $account;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $call1;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $call2;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $call3;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $call4;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $call5;

    /**
     * AccountOnBoardingCall constructor.
     *
     * @param bool $call1
     * @param bool $call2
     * @param bool $call3
     * @param bool $call4
     * @param bool $call5
     */
    public function __construct(
                $call1 = false,
                $call2 = false,
                $call3 = false,
                $call4 = false,
                $call5 = false
    ) {
        $this->setCall1($call1);
        $this->setCall2($call2);
        $this->setCall3($call3);
        $this->setCall4($call4);
        $this->setCall5($call5);
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     
     * @return $this
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getCall1()
    {
        return $this->call1;
    }

    /**
     * @param boolean $call1
     */
    public function setCall1($call1)
    {
        $this->call1 = $call1;
    }

    /**
     * @return boolean
     */
    public function getCall2()
    {
        return $this->call2;
    }

    /**
     * @param boolean $call2
     */
    public function setCall2($call2)
    {
        $this->call2 = $call2;
    }

    /**
     * @return boolean
     */
    public function getCall3()
    {
        return $this->call3;
    }

    /**
     * @param boolean $call3
     */
    public function setCall3($call3)
    {
        $this->call3 = $call3;
    }

    /**
     * @return boolean
     */
    public function getCall4()
    {
        return $this->call4;
    }

    /**
     * @param boolean $call4
     */
    public function setCall4($call4)
    {
        $this->call4 = $call4;
    }

    /**
     * @return boolean
     */
    public function getCall5()
    {
        return $this->call5;
    }

    /**
     * @param boolean $call5
     */
    public function setCall5($call5)
    {
        $this->call5 = $call5;
    }
}
