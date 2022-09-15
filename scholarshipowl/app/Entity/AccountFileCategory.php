<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * AccountFileCategory
 *
 * @ORM\Table(name="account_file_categories")
 * @ORM\Entity
 */
class AccountFileCategory
{
    use Dictionary;

    const OTHER = 1;
    const ESSAY = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
}

