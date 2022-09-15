<?php

/**
 * Auto-generated entity class
 */

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\UserSocialRepository")
 */
class UserSocial implements JsonApiResource
{
	use Timestamps;

	const GOOGLE_PROVIDER = 'google';

    /**
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="User")
     */
	protected $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
	protected $provider;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
	protected $externalId;

    /**
     * Access token
     *
     * @var string
     * @ORM\Column(type="string")
     */
	protected $token;

    /**
     * Refresh token
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
	protected $refreshToken;

    /**
	 * @return string
	 */
	public static function getResourceKey()
	{
		return "user_social";
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

    /**
     * @param User $user
     * @return UserSocial
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param string $provider
     * @return UserSocial
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
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
     * @param string $externalId
     * @return UserSocial
     */
    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param string $token
     * @return UserSocial
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $refreshToken
     * @return UserSocial
     */
    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
