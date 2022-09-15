<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Installations
 *
 * @ORM\Table(name="installations", indexes={@ORM\Index(name="installations_account_id_foreign", columns={"account_id"})})
 * @ORM\Entity
 */
class Installations
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="device_token", type="string", length=255, nullable=true)
     */
    protected $deviceToken;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=255, nullable=true)
     */
    protected $provider;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var Account
     *
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    protected $account;

    /**
     * @param string $deviceToken
     * @param string $provider
     * @param Account $account
     */
    public function __construct(string $deviceToken, string $provider, Account $account)
    {
        $this->setDeviceToken($deviceToken);
        $this->setProvider($provider);
        $this->setAccount($account);
    }

    /**
     * @return string
     */
    public function getDeviceToken(): string
    {
        return $this->deviceToken;
    }

    /**
     * @param string $deviceToken
     * @return $this
     */
    public function setDeviceToken(string $deviceToken)
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @return $this
     */
    public function setProvider(string $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }


}

