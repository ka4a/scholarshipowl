<?php namespace App\Entities;

use App\Contracts\LegalContentContract;
use App\Doctrine\Types\RecurrenceConfigType\ConfigFactory;
use App\Doctrine\Types\RecurrenceConfigType\IRecurrenceConfig;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Entity represents scholarship configurations.
 * When scholarship published (becoming active) we MUST copy the settings from this entity.
 *
 * @ORM\Table(name="scholarship_template")
 * @ORM\Entity(repositoryClass="App\Repositories\ScholarshipTemplateRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable()
 */
class ScholarshipTemplate  extends ScholarshipAbstract implements JsonApiResource
{
    use Timestamps;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_template';
    }

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var Scholarship[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Scholarship", mappedBy="template", cascade={"persist"})
     */
    private $published;

    /**
     * @var ScholarshipWebsite
     * @ORM\OneToOne(targetEntity="ScholarshipWebsite", cascade={"persist"}, inversedBy="template")
     * @ORM\JoinColumn(unique=true)
     */
    protected $website;

    /**
     * @var Organisation
     * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="scholarships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;

    /**
     * @var ArrayCollection|ScholarshipTemplateField[]
     * @ORM\OneToMany(targetEntity="ScholarshipTemplateField", mappedBy="template", cascade={"persist"}, orphanRemoval=true)
     */
    protected $fields;

    /**
     * @var ArrayCollection|ScholarshipTemplateRequirement[]
     * @ORM\OneToMany(targetEntity="ScholarshipTemplateRequirement", mappedBy="template", cascade={"persist"}, orphanRemoval=true)
     */
    protected $requirements;

    /**
     * @var ArrayCollection|ScholarshipTemplateContent[]
     *
     * @ORM\OneToMany(targetEntity="ScholarshipTemplateContent", mappedBy="template", cascade={"persist"})
     */
    protected $contents;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $paused = false;

    /**
     * @var IRecurrenceConfig
     *
     * @ORM\Column(type="recurrence_config", nullable=true)
     */
    protected $recurrenceConfig;

    /**
     * ScholarshipTemplate constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->published = new ArrayCollection();
        $this->contents = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @throws \Doctrine\ORM\ORMException
     */
    public function setupDefaultTemplateConfigurations()
    {
        $this->addDefaultFields();
        $this->addDefaultContents();
    }

    /**
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     */
    public function addDefaultFields()
    {
         $this->fields->add(
            ScholarshipTemplateField::create()
                ->setTemplate($this)
                ->setField(Field::find(Field::NAME))
        );
        $this->fields->add(
            ScholarshipTemplateField::create()
                ->setTemplate($this)
                ->setField(Field::find(Field::PHONE))
        );
        $this->fields->add(
            ScholarshipTemplateField::create()
                ->setTemplate($this)
                ->setField(Field::find(Field::EMAIL))
        );
        $this->fields->add(
            ScholarshipTemplateField::create()
                ->setTemplate($this)
                ->setField(Field::find(Field::STATE))
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function addDefaultContents()
    {
        $this->contents->add(
            (new ScholarshipTemplateContent())
                ->setTemplate($this)
                ->setType(LegalContentContract::TYPE_AFFIDAVIT)
                ->setContent(Settings::find(Settings::CONFIG_LEGAL_AFFIDAVIT)->getConfig())
        );
        $this->contents->add(
            (new ScholarshipTemplateContent())
                ->setTemplate($this)
                ->setType(LegalContentContract::TYPE_TERMS_OF_USE)
                ->setContent(Settings::find(Settings::CONFIG_LEGAL_TERMS_OF_USE)->getConfig())
        );
        $this->contents->add(
            (new ScholarshipTemplateContent())
                ->setTemplate($this)
                ->setType(LegalContentContract::TYPE_PRIVACY_POLICY)
                ->setContent(Settings::find(Settings::CONFIG_LEGAL_PRIVACY_POLICY)->getConfig())
        );

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ScholarshipWebsite $website
     * @return $this
     */
    public function setWebsite(ScholarshipWebsite $website)
    {
        $this->website = $website->setTemplate($this);
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
     * @param Organisation|int $organisation
     * @return $this
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = Organisation::convert($organisation);
        return $this;
    }

    /**
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @return ScholarshipTemplateField[]|ArrayCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array|ArrayCollection $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param ScholarshipTemplateField $field
     * @return $this
     */
    public function addFields(ScholarshipTemplateField $field)
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field->setTemplate($this);
        }
        return $this;
    }

    /**
     * @param ScholarshipTemplateField $field
     * @return $this
     */
    public function removeFields(ScholarshipTemplateField $field)
    {
        $this->fields->removeElement($field);
        return $this;
    }

    /**
     * @return bool
     */
    public function isPaused()
    {
        return $this->paused;
    }

    /**
     * @param bool $paused
     * @return $this
     */
    public function setPaused($paused)
    {
        $this->paused = $paused;
        return $this;
    }

    /**
     * @return Scholarship[]|ArrayCollection
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param Scholarship $scholarship
     * @return $this
     */
    public function addPublished(Scholarship $scholarship)
    {
        if (!$this->published->contains($scholarship)) {
            $this->published->add($scholarship->setTemplate($this));
        }
        return $this;
    }

    /**
     * @return IRecurrenceConfig
     */
    public function getRecurrenceConfig()
    {
        return $this->recurrenceConfig;
    }

    /**
     * @param IRecurrenceConfig|array $config
     * @return $this
     */
    public function setRecurrenceConfig($config)
    {
        $this->recurrenceConfig = $config instanceof IRecurrenceConfig ? $config : ConfigFactory::fromConfig($config);

        return $this;
    }

    /**
     * @return ScholarshipTemplateContent[]|ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $type
     * @return ScholarshipTemplateContent
     * @throws \RuntimeException
     */
    public function getContentByType($type)
    {
        /** @var ArrayCollection $contents */
        $contents = $this->getContents()
            ->filter(function(ScholarshipTemplateContent $content) use ($type) {
                return $content->getType() === $type;
            });

        if (false === ($content = $contents->first())) {
            throw new \RuntimeException(sprintf('Content type "%s" not found. (%s)', $type, $this->getId()));
        }

        return $content;
    }

    /**
     * @param ArrayCollection $contents
     * @return $this
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * @param ScholarshipTemplateRequirement $requirement
     * @return $this
     */
    public function addRequirements(ScholarshipTemplateRequirement $requirement)
    {
        if (!$this->requirements->contains($requirement)) {
            $this->requirements->add($requirement->setTemplate($this));
        }
        return $this;
    }

    /**
     * @param ScholarshipTemplateRequirement $requirement
     * @return $this
     */
    public function removeRequirements(ScholarshipTemplateRequirement $requirement)
    {
        $this->requirements->removeElement($requirement);
        return $this;
    }

    /**
     * @param array|ArrayCollection $requirements
     * @return $this
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;
        return $this;
    }

    /**
     * @return ScholarshipTemplateRequirement[]|ArrayCollection
     */
    public function getRequirements()
    {
        return $this->requirements;
    }
}
