<?php namespace App\Entities;

use App\Traits\DictionaryEntity;
use Doctrine\ORM\Mapping as ORM;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * State
 *
 * @ORM\Entity
 * @ORM\Table(name="state")
 */
class State implements JsonApiResource
{
    use DictionaryEntity;

    const STATE_ALABAMA = 1;
    const STATE_CONNECTICUT = 7;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'state';
    }

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=7, nullable=false)
     */
    private $abbreviation;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }
}

