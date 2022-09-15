<?php namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Entity represent scholarship application.
 *
 * @ORM\Entity(repositoryClass="App\Repositories\ApplicationRepository")
 * @ORM\Table(name="application",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uk_application_id", columns={"id"}),
 *          @ORM\UniqueConstraint(name="uk_scholarship_id_email", columns={"scholarship_id", "email"})
 *     }
 * )
 * @Gedmo\Loggable(logEntryClass="LogEntry");
 */
class Application implements JsonApiResource
{
    use Timestamps;

    const SOURCE_NONE   = 'none';
    const SOURCE_SOWL   = 'sowl';
    const SOURCE_BARN   = 'barn';
    const SOURCE_API    = 'api';
    const SOURCE_IFRAME = 'iframe';

    /**
     * @return string
     */
    public static function getResourceKey()
    {
        return 'application';
    }

    /**
     * Application unique id.
     *
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    protected $source = self::SOURCE_NONE;

    /**
     * @var Scholarship
     * @ORM\ManyToOne(targetEntity="Scholarship", fetch="LAZY")
     * @ORM\JoinColumn(name="scholarship_id", nullable=false)
     */
    protected $scholarship;

    /**
     * Applicant email.
     *
     * @var string
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * Applicant name.
     *
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * Applicant phone number.
     *
     * @var string
     * @ORM\Column(name="phone", type="string")
     */
    protected $phone;

    /**
     * @var State
     * @ORM\ManyToOne(targetEntity="State", fetch="EAGER")
     * @ORM\JoinColumn(name="state_id", unique=false)
     */
    protected $state;

    /**
     * All extra application data we store as JSON value in DB.
     *
     * @var array
     * @ORM\Column(name="data", type="json_array", nullable=false)
     */
    protected $data;

    /**
     * @var ArrayCollection|ApplicationRequirement[]
     * @ORM\OneToMany(targetEntity="ApplicationRequirement", mappedBy="application", cascade={"persist"})
     */
    protected $requirements;

    /**
     * @var ApplicationStatus
     * @ORM\ManyToOne(targetEntity="ApplicationStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $status;

    /**
     * @var ApplicationWinner
     * @ORM\OneToOne(targetEntity="ApplicationWinner", mappedBy="application")
     */
    protected $winner;

    /**
     * @var ArrayCollection|ApplicationFile[]
     * @ORM\OneToMany(targetEntity="ApplicationFile", mappedBy="application")
     */
    protected $files;

    /**
     * @var array
     * @ORM\ManyToMany(targetEntity="ApplicationBatch", cascade={"persist"}, mappedBy="applications")
     */
    protected $batch;

    /**
     * Application constructor.
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->requirements = new ArrayCollection();
        $this->status = ApplicationStatus::find(ApplicationStatus::RECEIVED);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
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
     * @param Scholarship $scholarship
     * @return $this
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
        return $this;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = phone_format($phone);
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param State|int $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = State::convert($state);
        return $this;
    }

    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
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
     * @param ApplicationRequirement $requirement
     * @return $this
     */
    public function addRequirements(ApplicationRequirement $requirement)
    {
        if (!$this->requirements->contains($requirement)) {
            $this->requirements->add($requirement->setApplication($this));
        }
        return $this;
    }

    /**
     * @param array $requirements
     * @return $this
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;
        return $this;
    }

    /**
     * @return array|ApplicationRequirement[]
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param ApplicationWinner $winner
     * @return $this
     */
    public function setWinner(ApplicationWinner $winner)
    {
        $this->winner = $winner;
        return $this;
    }

    /**
     * @param int|ApplicationStatus $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = ApplicationStatus::convert($status);
        return $this;
    }

    /**
     * @return ApplicationStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return ApplicationWinner
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param ApplicationFile $file
     * @return $this
     */
    public function addFiles($file)
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file->setApplication($this));
        }
        return $this;
    }

    /**
     * @param ApplicationFile $file
     * @return $this
     */
    public function removeFiles($file)
    {
        $this->files->removeElement($file);
        return $this;
    }

    /**
     * @return ApplicationFile[]|ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
