<?php namespace App\Entities;

use App\Entities\Traits\LegalContent;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ScholarshipContent implements JsonApiResource
{
    use Timestamps;
    use LegalContent;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_content';
    }

    /**
     * @var Scholarship
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Scholarship", inversedBy="content")
     */
    protected $scholarship;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->scholarship->getId();
    }

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
