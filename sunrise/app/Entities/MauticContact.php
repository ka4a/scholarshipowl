<?php namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MauticContact
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $mauticId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $mauticId
     * @return $this
     */
    public function setMauticId($mauticId)
    {
        $this->mauticId = $mauticId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMauticId()
    {
        return $this->mauticId;
    }
}
