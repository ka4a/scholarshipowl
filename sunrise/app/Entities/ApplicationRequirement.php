<?php namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity()
 */
class ApplicationRequirement implements JsonApiResource
{
    use Timestamps;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'application_requirement';
    }

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Application
     * @ORM\ManyToOne(targetEntity="Application")
     * @ORM\JoinColumn(nullable=false, unique=false)
     */
    private $application;

    /**
     * @var ScholarshipRequirement
     * @ORM\ManyToOne(targetEntity="ScholarshipRequirement")
     */
    protected $requirement;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="ApplicationFile", cascade={"persist"})
     */
    protected $files;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value;

    /**
     * ApplicationRequirement constructor.
     */
    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Application $application
     * @return $this
     */
    public function setApplication($application)
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
     * @param ScholarshipRequirement $requirement
     * @return $this
     */
    public function setRequirement($requirement)
    {
        $this->requirement = $requirement;
        return $this;
    }

    /**
     * @return ScholarshipRequirement
     */
    public function getRequirement()
    {
        return $this->requirement;
    }

    /**
     * @param ApplicationFile $file
     * @return ArrayCollection
     */
    public function addFiles(ApplicationFile $file)
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file->setApplication($this->getApplication()));
        }
        return $this;
    }

    /**
     * @param ApplicationFile $file
     * @return $this
     */
    public function removeFiles(ApplicationFile $file)
    {
        $this->files->removeElement($file);
        return $this;
    }

    /**
     * @param array|ArrayCollection $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
