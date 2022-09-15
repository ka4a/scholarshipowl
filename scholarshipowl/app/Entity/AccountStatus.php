<?php

namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AccountStatus
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="account_status")
 */
class AccountStatus
{
    use Dictionary;

    const REQUESTED = 1;

    const PENDING = 2;

    const ACTIVE = 3;

    const DISABLED = 4;

    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(name="account_status_id", type="integer")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    protected $name;

}
