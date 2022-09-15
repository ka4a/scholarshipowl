<?php namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application batch used as job for applying on many scholarships.
 * @ORM\Entity()
 */
class ApplicationBatch implements JsonApiResource
{
    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'application_batch';
    }

    /**
     * Job is pending for start.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Job is running
     */
    const STATUS_RUNNING = 'running';

    /**
     * Job is finished.
     */
    const STATUS_FINISHED = 'finished';

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    protected $data;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    protected $status;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $source = Application::SOURCE_NONE;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $eligible;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $applied;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $errors;

    /**
     * @var ArrayCollection|Application[]
     * @ORM\ManyToMany(targetEntity="Application", cascade={"persist"})
     */
    protected $applications;

    /**
     * ApplicationBatch constructor.
     */
    public function __construct()
    {
        $this->status = self::STATUS_PENDING;
        $this->applications = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ArrayCollection $applications
     * @return $this
     */
    public function setApplications(ArrayCollection $applications)
    {
        $this->applications = $applications;
        return $this;
    }

    /**
     * @param Application $application
     * @return $this
     */
    public function addApplications(Application $application)
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
        }
        return $this;
    }

    /**
     * @param Application $application
     * @return $this
     */
    public function removeApplications(Application $application)
    {
        $this->applications->removeElement($application);
        return $this;
    }

    /**
     * @return Application[]|ArrayCollection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $applied
     * @return $this
     */
    public function setApplied($applied)
    {
        $this->applied = $applied;
        return $this;
    }

    /**
     * @return int
     */
    public function getApplied()
    {
        return $this->applied;
    }

    /**
     * @param int $eligible
     * @return $this
     */
    public function setEligible($eligible)
    {
        $this->eligible = $eligible;
        return $this;
    }

    /**
     * @return int
     */
    public function getEligible()
    {
        return $this->eligible;
    }

    /**
     * @param int $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return int
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
