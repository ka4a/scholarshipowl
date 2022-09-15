<?php namespace App\Entities\Passport;

use App\Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * OauthAccessTokens
 *
 * @ORM\Table(name="oauth_access_tokens", indexes={@ORM\Index(name="oauth_access_tokens_user_id_index", columns={"user_id"})})
 * @ORM\Entity
 */
class OauthAccessTokens
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
 */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumn(name="user_id", nullable=true, unique=false)
     */
    private $user;

    /**
     * @var OauthClient
     * @ORM\ManyToOne(targetEntity="OauthClient")
     * @ORM\JoinColumn(name="client_id", nullable=true, unique=false)
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="scopes", type="text", length=65535, nullable=true)
     */
    private $scopes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revoked", type="boolean", nullable=false)
     */
    private $revoked;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    private $expiresAt;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return OauthClient
     */
    public function getClient(): OauthClient
    {
        return $this->client;
    }

    /**
     * @param bool $revoked
     * @return $this
     */
    public function setRevoked(bool $revoked)
    {
        $this->revoked = $revoked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->revoked;
    }
}

