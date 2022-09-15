<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolLevel
 *
 * @ORM\Table(name="school_level")
 * @ORM\Entity
 */
class SchoolLevel implements DictionaryContract
{
    use Dictionary;

    const LEVEL_HIGH_SCHOOL_FRESHMAN = 1;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="school_level_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

