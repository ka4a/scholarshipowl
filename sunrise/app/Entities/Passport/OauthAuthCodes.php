<?php namespace App\Entities\Passport;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthAuthCodes
 *
 * @ORM\Table(name="oauth_auth_codes")
 * @ORM\Entity
 */
class OauthAuthCodes
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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false)
     */
    private $clientId;

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
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    private $expiresAt;


}

