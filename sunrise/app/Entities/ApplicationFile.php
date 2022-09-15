<?php namespace App\Entities;

use App\Doctrine\Extensions\Uploadable\PrivateListener;
use App\Traits\UploadableFile;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;


/**
 * @ORM\Entity
 * @Gedmo\Uploadable(appendNumber=true, pathMethod="basePath")
 */
class ApplicationFile implements JsonApiResource
{
    use UploadableFile;

    /**
     * @return PrivateListener
     */
    static public function listener()
    {
        return app(PrivateListener::class);
    }

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'application_file';
    }

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="Application")
     * @ORM\JoinColumn(nullable=false, unique=false)
     */
    private $application;

    /**
     * @param Application $application
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return string
     */
    public function basePath()
    {
        return sprintf('scholarships/%s/applications/%s',
            $this->getApplication()->getScholarship()->getId(),
            $this->getApplication()->getId()
        );
    }
}
