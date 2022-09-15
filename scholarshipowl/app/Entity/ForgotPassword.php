<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ForgotPassword
 *
 * @ORM\Table(name="forgot_password", uniqueConstraints={@ORM\UniqueConstraint(name="ix_forgot_password_token", columns={"token"})}, indexes={@ORM\Index(name="ix_forgot_password_account_id", columns={"account_id"})})
 * @ORM\Entity
 */
class ForgotPassword
{
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $expireDate;

    /**
     * @var \App\Entity\Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * ForgotPassword constructor.
     *
     * @param Account   $account
     * @param string    $token
     * @param \DateTime $expireDate
     */
    public function __construct(Account $account, string $token, \DateTime $expireDate)
    {
        $this->setAccount($account);
        $this->setExpireDate($expireDate);
        $this->setToken($token);
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return ForgotPassword
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set expireDate
     *
     * @param \DateTime $expireDate
     *
     * @return ForgotPassword
     */
    public function setExpireDate(\DateTime $expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return ForgotPassword
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \App\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

