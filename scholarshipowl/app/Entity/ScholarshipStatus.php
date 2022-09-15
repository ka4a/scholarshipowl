<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ScholarshipStatus
 *
 * @ORM\Entity()
 * @ORM\Table(name="scholarship_status")
 */
class ScholarshipStatus
{
    use Dictionary;

    /**
     * Scholarship is displayed on website.
     * Users can apply to it.
     */
    const PUBLISHED = 1;

    /**
     * Scholarship hidden from all users.
     * Waiting to became published.
     */
    const UNPUBLISHED = 2;

    /**
     * Scholarship expired.
     */
    const EXPIRED = 3;

    /**
     * Scholarship can't be processed with current application driver.
     * Stored in data for future use.
     */
    const UNPROCESSABLE = 4;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
