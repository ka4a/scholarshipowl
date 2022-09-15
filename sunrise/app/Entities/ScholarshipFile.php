<?php namespace App\Entities;

use App\Doctrine\Extensions\Uploadable\PublicListener;
use App\Traits\UploadableFile;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @Gedmo\Uploadable(pathMethod="basePath", filenameGenerator="SHA1")
 */
class ScholarshipFile implements JsonApiResource
{
    use UploadableFile;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_file';
    }

    /**
     * @return PublicListener
     */
    static public function listener()
    {
        return app(PublicListener::class);
    }

    /**
     * @var Scholarship
     * @ORM\ManyToOne(targetEntity="Scholarship")
     */
    protected $scholarship;

    /**
     * @param Scholarship $scholarship
     * @return $this
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
        return $this;
    }

    /**
     * @return ScholarshipWebsite
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @return string
     */
    public function basePath()
    {
        return sprintf('scholarship/%s', $this->getScholarship()->getId());
    }
}
