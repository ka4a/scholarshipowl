<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationEssayStatus
 *
 * @ORM\Table(name="application_essay_status")
 * @ORM\Entity
 */
class ApplicationEssayStatus
{
    use Dictionary;

    const NOT_NEEDED = 0;
    const NOT_STARTED = 1;
    const IN_PROGRESS = 2;
    const DONE = 3;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="application_essay_status_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=15, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;
}

