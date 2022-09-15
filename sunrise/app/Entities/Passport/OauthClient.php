<?php namespace App\Entities\Passport;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * OauthClients
 *
 * @ORM\Table(name="oauth_clients", indexes={@ORM\Index(name="oauth_clients_user_id_index", columns={"user_id"})})
 * @ORM\Entity
 */
class OauthClient implements ClientEntityInterface
{
    use Timestamps;

    const BARN_CLIENT_NAME = 'Barn client';


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Entities\User")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=100, nullable=false)
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect", type="text", length=65535, nullable=false)
     */
    private $redirect;

    /**
     * @var boolean
     *
     * @ORM\Column(name="personal_access_client", type="boolean", nullable=false)
     */
    private $personalAccessClient = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="password_client", type="boolean", nullable=false)
     */
    private $passwordClient = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="revoked", type="boolean", nullable=false)
     */
    private $revoked = false;

    /**
     * @return OauthClient|object
     */
    static public function barn()
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return $em->getRepository(static::class)
            ->findOneBy(['name' => static::BARN_CLIENT_NAME]);
    }

    /**
     * @return OauthClient
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    static public function password()
    {
         /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return $em->getRepository(static::class)
            ->createQueryBuilder('c')
            ->where('c.passwordClient = true')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return OauthClient
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    static public function personal()
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return $em->getRepository(static::class)
            ->createQueryBuilder('c')
            ->where('c.personalAccessClient = true')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * OauthClients constructor.
     */
    public function __construct()
    {
        $this->secret = str_random(100);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return 'id';
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $secret
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $redirect
     * @return $this
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->getRedirect();
    }

    /**
     * @param bool $personalAccessClient
     * @return $this
     */
    public function setPersonalAccessClient($personalAccessClient)
    {
        $this->personalAccessClient = $personalAccessClient;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPersonalAccessClient()
    {
        return $this->personalAccessClient;
    }

    /**
     * @param bool $passwordClient
     * @return $this
     */
    public function setPasswordClient($passwordClient)
    {
        $this->passwordClient = $passwordClient;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPasswordClient()
    {
        return $this->passwordClient;
    }

    /**
     * @param bool $revoked
     * @return $this
     */
    public function setRevoked($revoked)
    {
        $this->revoked = $revoked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRevoked()
    {
        return $this->revoked;
    }
}

