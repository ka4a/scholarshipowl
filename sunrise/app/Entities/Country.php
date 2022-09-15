<?php

/**
 * Auto-generated entity class
 */

declare(strict_types=1);

namespace App\Entities;

use App\Traits\DictionaryEntity;
use Doctrine\ORM\Mapping as ORM;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\CountryRepository")
 */
class Country implements JsonApiResource
{
    use DictionaryEntity;

    const USA = '1';

    /**
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
	protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
	protected $abbreviation;

    /**
	 * @return string
	 */
	public static function getResourceKey()
	{
		return "country";
	}

    /**
     * @param string $abbreviation
     * @return $this
     */
    public function setAbbreviation(string $abbreviation)
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }
}
