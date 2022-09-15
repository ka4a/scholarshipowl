<?php

/**
 * Auto-generated entity class
 */

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\UserTokenRepository")
 * @Gedmo\SoftDeleteable(fieldName="revoked", hardDelete=false)
 */
class UserToken implements JsonApiResource
{
	use Timestamps;

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
	protected $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=40)
     */
	protected $token;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $revoked;

	/**
	 * @return string
	 */
	public static function getResourceKey()
	{
		return "user_token";
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
     * @return UserToken
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param string $name
     * @return UserToken
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param \DateTime $revoked
     * @return UserToken
     */
    public function setRevoked(\DateTime $revoked): self
    {
        $this->revoked = $revoked;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRevoked(): ?\DateTime
    {
        return $this->revoked;
    }

    /**
     * @param string $token
     * @return UserToken
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
}
