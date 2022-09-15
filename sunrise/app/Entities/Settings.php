<?php namespace App\Entities;

use App\Traits\DictionaryEntity;
use Doctrine\ORM\EntityManager;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @Gedmo\Loggable(logEntryClass="SettingsLog");
 */
class Settings implements JsonApiResource
{
    use Timestamps;
    use DictionaryEntity;

    const CONFIG_LEGAL_AFFIDAVIT        = 'affidavit';
    const CONFIG_LEGAL_PRIVACY_POLICY   = 'privacyPolicy';
    const CONFIG_LEGAL_TERMS_OF_USE     = 'termsOfUse';

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=32)
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var mixed
     * @Gedmo\Versioned()
     * @ORM\Column(type="json")
     */
    protected $config;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'settings';
    }

    /**
     * @param mixed $id
     *
     * @return object|static
     */
    public static function find($id)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return $em->getReference(static::class, $id);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }
}
