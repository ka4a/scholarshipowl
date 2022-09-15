<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * DegreeType
 *
 * @ORM\Table(name="degree_type")
 * @ORM\Entity
 */
class DegreeType implements DictionaryContract
{
    use Dictionary;

    const DEGREE_UNDECIDED = 1;
    const DEGREE_CERTIFICATE = 2;
    const DEGREE_ASSOCIATE = 3;
    const DEGREE_BACHELOR = 4;
    const DEGREE_GRADUATE_CERTIFICATE = 5;
    const DEGREE_MASTER = 6;
    const DEGREE_DOCTOR = 7;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="degree_type_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

