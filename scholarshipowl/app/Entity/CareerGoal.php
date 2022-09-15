<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * CareerGoal
 *
 * @ORM\Table(name="career_goal")
 * @ORM\Entity
 */
class CareerGoal implements DictionaryContract
{
    use Dictionary;

    const OTHER = 10;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="career_goal_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

