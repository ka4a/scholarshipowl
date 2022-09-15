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
class ScholarshipWebsiteFile implements JsonApiResource
{
    use UploadableFile;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_website_file';
    }

    /**
     * @return PublicListener
     */
    static public function listener()
    {
        return app(PublicListener::class);
    }

    /**
     * @var ScholarshipWebsite
     * @ORM\ManyToOne(targetEntity="ScholarshipWebsite")
     */
    protected $website;

    /**
     * @param ScholarshipWebsite $website
     * @return $this
     */
    public function setWebsite(ScholarshipWebsite $website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @return ScholarshipWebsite
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @return string
     */
    public function basePath()
    {
        return sprintf('website/%s', $this->getWebsite()->getId());
    }
}
