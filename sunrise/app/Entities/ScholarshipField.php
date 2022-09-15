<?php namespace App\Entities;

use App\Entities\Super\AbstractScholarshipField;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ScholarshipField extends AbstractScholarshipField implements JsonApiResource
{
    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_field';
    }

    /**
     * @var Scholarship
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="fields")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $scholarship;

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @param Scholarship $scholarship
     * @return $this
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
        return $this;
    }
}
