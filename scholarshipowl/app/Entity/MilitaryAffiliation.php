<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * MilitaryAffiliation
 *
 * @ORM\Table(name="military_affiliation")
 * @ORM\Entity
 */
class MilitaryAffiliation implements DictionaryContract
{
    use Dictionary;

    const MILITARY_AFFILIATION_NONE = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="military_affiliation_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

