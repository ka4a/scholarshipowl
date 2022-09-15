<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Counter
 *
 * @ORM\Table(name="counter")
 * @ORM\Entity
 *
 * @method Counter static findByName(string $name)
 */
class Counter
{
    use Dictionary;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="count", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $count;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @param int $decrease
     *
     * @return string
     */
    public function displayCount($decrease = 0)
    {
        if (($count = ($this->getCount() - $decrease)) <= 0) {
            $count = $this->getCount();
        }

        return number_format($count);
    }
}

