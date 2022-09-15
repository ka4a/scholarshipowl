<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table(name="state", indexes={@ORM\Index(name="ix_state_country_id", columns={"country_id"})})
 * @ORM\Entity
 */
class State implements DictionaryContract
{
    use Dictionary;

    const STATE_US_ALABAMA = 1;
    const STATE_US_CONNECTICUT = 7;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="state_id", type="integer", nullable=false)
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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;

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

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}

