<?php
namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AccountType
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="account_type")
 */
class AccountType
{
    use Dictionary;

    const ADMINISTRATOR = 1;

    const USER = 2;

    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(name="account_type_id", type="integer")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    protected $name;

}
