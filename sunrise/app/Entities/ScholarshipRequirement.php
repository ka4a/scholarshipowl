<?php namespace App\Entities;

use App\Entities\Super\AbstractScholarshipRequirement;
use Doctrine\ORM\Mapping as ORM;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity()
 */
class ScholarshipRequirement extends AbstractScholarshipRequirement implements JsonApiResource
{
    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_requirement';
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
