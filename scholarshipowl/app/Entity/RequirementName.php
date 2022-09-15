<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * RequirementName
 *
 * @ORM\Table(name="requirement_name")
 * @ORM\Entity
 */
class RequirementName
{
    use Dictionary;

    const TYPE_TEXT = 1;
    const TYPE_FILE = 2;
    const TYPE_IMAGE = 3;
    const TYPE_INPUT = 4;
    const TYPE_SURVEY = 5;
    const TYPE_SPECIAL_ELIGIBILITY = 6;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $type;

    /**
     * RequirementName constructor.
     *
     * @param string $name
     * @param string $type
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->setType($type);
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return RequirementName
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}

