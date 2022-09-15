<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use http\Exception\RuntimeException;

/**
 * SocialAccounts
 *
 * @ORM\Table(name="social_account")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class SocialAccount
{
    const FACEBOOK = 'facebook';

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Account", inversedBy="socialAccount", fetch="EAGER")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="provider_user_id", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $providerUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $provider;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $token;

    /**
     * @var string Link to public profile.
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=false)
     */
    private $link;

    /**
     * SocialAccount constructor.
     *
     * @param string  $providerUserId
     * @param string  $link
     * @param string  $provider
     */
    public function __construct(string $providerUserId, string $link = null, $provider = SocialAccount::FACEBOOK)
    {
        $this->setProviderUserId($providerUserId);
        $this->setLink();
        $this->setProvider($provider);
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return SocialAccount
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getProviderUserId(): string
    {
        return $this->providerUserId;
    }

    /**
     * @param string $providerUserId
     */
    public function setProviderUserId(string $providerUserId)
    {
        $this->providerUserId = $providerUserId;
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
     */
    public function setProvider(string $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string|null $link
     * @return $this
     */
    public function setLink(string $link = null)
    {
        if ($link) {
            $this->link = $link;
        } else {
            if (!$this->providerUserId) {
                throw new \Exception('providerUserId property must be set before setting a link property');
            }
           $this->link = "https://www.facebook.com/app_scoped_user_id/{$this->providerUserId}/";
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}

