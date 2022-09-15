<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Degree
 *
 * @ORM\Table(name="degree")
 * @ORM\Entity
 */
class Degree implements DictionaryContract
{
    use Dictionary;

    const DEGREE_AGRICULTURE_AND_RELATED_SCIENCES = 1;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="degree_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

