<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationStatus
 *
 * @ORM\Table(name="application_status")
 * @ORM\Entity
 */
class ApplicationStatus
{
    use Dictionary;

    const PENDING = 1;
    const IN_PROGRESS = 2;
    const SUCCESS = 3;
    const ERROR = 4;
    const NEED_MORE_INFO = 5;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="application_status_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=15, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->is(static::PENDING);
    }
}

