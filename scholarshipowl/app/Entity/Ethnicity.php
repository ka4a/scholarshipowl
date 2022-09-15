<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ethnicity
 *
 * @ORM\Table(name="ethnicity")
 * @ORM\Entity
 */
class Ethnicity implements DictionaryContract
{
    use Dictionary;

    const ETHNICITY_CAUCASIAN = 1;
    const ETHNICITY_OTHER = 6;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="ethnicity_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

