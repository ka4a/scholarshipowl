<?php namespace App\Entity;

use App\Contracts\CachableEntity;
use App\Contracts\MappingTags;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Traits\Hydratable;

use App\Entity\Traits\Recurrable;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

use Illuminate\Support\Str;
use Illuminate\Contracts\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Constraint;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Scholarship
 *
 * @ORM\Table(name="scholarship", indexes={@ORM\Index(name="ix_scholarship_expiration_date", columns={"expiration_date"}), @ORM\Index(name="ix_scholarship_amount", columns={"amount"}), @ORM\Index(name="ix_scholarship_up_to", columns={"up_to"}), @ORM\Index(name="ix_scholarship_is_free", columns={"is_free"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\ScholarshipRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Scholarship implements CachableEntity, MappingTags
{
    use Hydratable;
    use Recurrable;

    const DEFAULT_TIMEZONE = 'US/Eastern';

    const APPLICATION_STATUS_INCOMPLETE = 1;
    const APPLICATION_STATUS_IN_PROGRESS = 2;
    const APPLICATION_STATUS_READY_TO_SUBMIT = 3;
    const APPLICATION_STATUS_SUBMITTED = 4;

    const TRANSITIONAL_STATUS_CHOOSING_WINNER = 'choosing_winner';
    const TRANSITIONAL_STATUS_POTENTIAL_WINNER = 'potential_winner_chosen';
    const TRANSITIONAL_STATUS_FINAL_WINNER = 'final_winner_chosen';

    const DERIVED_STATUS_SENT = 'SENT';
    const DERIVED_STATUS_RECEIVED = 'RECEIVED'; //todo
    const DERIVED_STATUS_DECLINED = 'DECLINED';
    const DERIVED_STATUS_REVIEW = 'UNDER REVIEW'; //todo
    const DERIVED_STATUS_ACCEPTED = 'ACCEPTED';
    const DERIVED_STATUS_DRAW_CLOSED = 'DRAW CLOSED';
    const DERIVED_STATUS_CHOOSING = 'CHOOSING WINNER';
    const DERIVED_STATUS_WON = 'WON';
    const DERIVED_STATUS_MISSED = 'MISSED';
    const DERIVED_STATUS_CHOSEN = 'WINNER CHOSEN';
    const DERIVED_STATUS_AWARDED = 'AWARDED';


    const APPLICATION_TYPE_SUNRISE = "sunrise";
    const APPLICATION_TYPE_ONLINE = "online";
    const APPLICATION_TYPE_EMAIL = "email";
    const APPLICATION_TYPE_NONE = "none";

    const LOGO_IMAGE_WIDTH = 550;
    const LOGO_IMAGE_HEIGHT = null;

    const FORM_METHOD_POST = "post";
    const FORM_METHOD_GET = "get";

    const RECURRENCE_TYPE_DAY = 1;
    const RECURRENCE_TYPE_WEEK = 2;
    const RECURRENCE_TYPE_MONTH = 3;
    const RECURRENCE_TYPE_YEAR = 4;

    /**
     * Path to save logos on cloud storage
     */
    const CLOUD_FOLDER = '/scholarship/logos';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="scholarship_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $scholarshipId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=127, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=511, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="application_type", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    protected $applicationType = self::APPLICATION_TYPE_ONLINE;

    /**
     * @var string
     *
     * @ORM\Column(name="apply_url", type="string", length=511, precision=0, scale=0, nullable=false, unique=false)
     */
    protected $applyUrl = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    protected $expirationDate;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=false, unique=false)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="up_to", type="decimal", precision=10, scale=2, nullable=false, unique=false)
     */
    protected $upTo;

    /**
     * @var int
     *
     * @ORM\Column(name="awards", type="integer", nullable=false)
     */
    protected $awards;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2047, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="string", length=255, nullable=false, unique=false)
     */
    protected $notes = '';

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true, unique=false)
     */
    protected $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true, unique=false)
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=511, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_subject", type="string", length=511, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $emailSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="email_message", type="string", length=2047, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $emailMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="form_action", type="string", length=511, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $formAction;

    /**
     * @var string
     *
     * @ORM\Column(name="form_method", type="string", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $formMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="terms_of_service_url", type="string", length=511, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $termsOfServiceUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_policy_url", type="string", length=511, precision=0, scale=0, nullable=true, unique=false)
     */
    protected $privacyPolicyUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_free", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $isFree = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $createdDate;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="last_updated_date", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $lastUpdatedDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="files_alowed", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $filesAlowed = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_automatic", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $isAutomatic = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="send_to_private", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $sendToPrivate = false;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_author", type="string", nullable=true)
     */
    protected $metaAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", nullable=true)
     */
    protected $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", nullable=true)
     */
    protected $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="string", nullable=true)
     */
    protected $metaKeywords;

    /**
     * @var ArrayCollection|Eligibility[]
     *
     * @ORM\OneToMany(targetEntity="Eligibility", mappedBy="scholarship", cascade={"persist"})
     */
    protected $eligibilities;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ScholarshipFile", mappedBy="scholarship", cascade={"persist"})
     */
    protected $scholarshipFiles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Essay", mappedBy="scholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=true)
     * })
     */
    protected $essays;

    /**
     * @var ArrayCollection|Form[]
     *
     * @ORM\OneToMany(targetEntity="Form", mappedBy="scholarship", cascade={"all"})
     */
    protected $forms;

    /**
     * @var ArrayCollection|RequirementContract[]
     *
     * @ORM\OneToMany(targetEntity="RequirementText", mappedBy="scholarship", cascade={"all"}, orphanRemoval=true)
     */
    protected $requirementTexts;

    /**
     * @var ArrayCollection|RequirementContract[]
     *
     * @ORM\OneToMany(targetEntity="RequirementSurvey", mappedBy="scholarship", cascade={"all"}, orphanRemoval=true)
     */
    protected $requirementSurvey;

    /**
     * @var ArrayCollection|RequirementFile[]
     *
     * @ORM\OneToMany(targetEntity="RequirementFile", mappedBy="scholarship", cascade={"all"}, orphanRemoval=true)
     */
    protected $requirementFiles;

    /**
     * @var ArrayCollection|RequirementImage[]
     *
     * @ORM\OneToMany(targetEntity="RequirementImage", mappedBy="scholarship", cascade={"all"}, orphanRemoval=true)
     */
    protected $requirementImages;

    /**
     * @var ArrayCollection|RequirementInput[]
     *
     * @ORM\OneToMany(targetEntity="RequirementInput", mappedBy="scholarship", cascade={"all"}, orphanRemoval=true)
     */
    protected $requirementInputs;

    /**
     * @var ArrayCollection|RequirementSpecialEligibility[]
     *
     * @ORM\OneToMany(targetEntity="RequirementSpecialEligibility", mappedBy="scholarship", cascade={"all"}, orphanRemoval=true)
     */
    protected $requirementSpecialEligibility;

    /**
     * Application status placeholder.
     * Should be set manually.
     *
     * @var int
     */
    protected $applicationStatus;

    /**
     * @var ArrayCollection|Application[]
     *
     * @ORM\OneToMany(targetEntity="Application", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applications;

    /**
     * @var null|ArrayCollection|ApplicationText[]
     *
     * @ORM\OneToMany(targetEntity="ApplicationText", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applicationTexts;

    /**
     * @var null|ArrayCollection|ApplicationFile[]
     *
     * @ORM\OneToMany(targetEntity="ApplicationFile", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applicationFiles;

    /**
     * @var null|ArrayCollection|ApplicationImage[]
     *
     * @ORM\OneToMany(targetEntity="ApplicationImage", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applicationImages;

    /**
     * @var null|ArrayCollection|ApplicationInput[]
     *
     * @ORM\OneToMany(targetEntity="ApplicationInput", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applicationInputs;

    /**
     * @var null|ArrayCollection|ApplicationSurvey[]
     *
     * @ORM\OneToMany(targetEntity="ApplicationSurvey", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applicationSurvey;

    /**
     * @var null|ArrayCollection|ApplicationSpecialEligibility[]
     *
     * @ORM\OneToMany(targetEntity="ApplicationSpecialEligibility", mappedBy="scholarship", fetch="EXTRA_LAZY")
     */
    protected $applicationSpecialEligibility;

    /**
     * Set if new logo should be uploaded to cloud
     *
     * @var UploadedFile
     */
    private $shouldBeUploadedLogo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_recurrent", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $isRecurrent = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="recurrence_start_now", type="boolean")
     */
    protected $recurrenceStartNow = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="recurrence_end_month", type="boolean")
     */
    protected $recurrenceEndMonth = false;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string")
     */
    protected $timezone = self::DEFAULT_TIMEZONE;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eligibility_update", type="datetime")
     */
    protected $eligibilityUpdate;

    /**
     * @var ScholarshipStatus
     *
     * @ORM\OneToOne(targetEntity="ScholarshipStatus", fetch="EAGER")
     * @ORM\JoinColumn(name="status")
     */
    protected $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive = false;

    /**
     * @var Scholarship
     *
     * @ORM\OneToOne(targetEntity="Scholarship", fetch="LAZY")
     * @ORM\JoinColumn(name="parent_scholarship_id", referencedColumnName="scholarship_id")
     */
    protected $parentScholarship;

    /**
     * @var Scholarship
     *
     * @ORM\OneToOne(targetEntity="Scholarship", fetch="LAZY")
     * @ORM\JoinColumn(name="current_scholarship_id", referencedColumnName="scholarship_id")
     */
    protected $currentScholarship;

    /**
     * @var
     */
    protected $isFavorite = 0;

    /**
     * @var int
     */
    protected $isSent = 0;

    /**
     * Scholarship id from external source (e.g. Sunrise).
     * @var string
     *
     * @ORM\Column(name="external_scholarship_id", type="string", nullable=true, unique=true)
     */
    protected $externalScholarshipId;

    /**
     * Scholarship template from external source (e.g. Sunrise). Used with recurrence.
     * @var string
     *
     * @ORM\Column(name="external_scholarship_template_id", type="string", nullable=true, unique=true)
     */
    protected $externalScholarshipTemplateId;

    /**
     * @var string
     *
     * @ORM\Column(name="transitional_status", type="string", nullable=true)
     */
    protected $transitionalStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="winner_form_url", type="string", nullable=true)
     */
    protected $winnerFormUrl;

    /**
     * Derived status, based on a particular application status and transitionalStatus
     *
     * @var string
     */
    protected $derivedStatus;


    /**
     * Scholarship constructor.
     *
     * @param array $hydrate
     */
    public function __construct(array $hydrate)
    {
        $this->forms = new ArrayCollection();
        $this->eligibilities = new ArrayCollection();
        $this->scholarshipFiles = new ArrayCollection();
        $this->requirementFiles = new ArrayCollection();
        $this->requirementTexts = new ArrayCollection();
        $this->requirementImages = new ArrayCollection();
        $this->requirementInputs = new ArrayCollection();
        $this->requirementSpecialEligibility = new ArrayCollection();
        $this->requirementSurvey = new ArrayCollection();
        $this->essays = new ArrayCollection();
        $this->setCreatedDate(new \DateTime());
        $this->setStatus(ScholarshipStatus::UNPUBLISHED);
        $this->hydrate($hydrate);

        if (!$this->getStartDate()) {
            $this->setStartDate((new \DateTime())->setTimezone($this->getTimezoneObj()));
        }
    }

    /**
     * @return array
     */
    public function tags() : array
    {
        return [
            'id' => $this->getScholarshipId(),
            'title' => $this->getTitle(),
        ];
    }

    /**
     * @return string
     */
    public function cacheTag() : string
    {
        return static::cacheTagGenerate($this->getScholarshipId());
    }

    /**
     * @param int $scholarshipId
     *
     * @return string
     */
    public static function cacheTagGenerate(int $scholarshipId) : string
    {
        return sprintf('scholarship_%s', $scholarshipId);
    }

    /**
     * @return string
     */
    public static function cacheTagGeneral() : string
    {
        return 'scholarship_general';
    }


    /**
     * Clone relations
     */
    public function __clone()
    {
        $this->essays = [];
        $this->requirementTexts = [];
        $this->requirementFiles = [];
        $this->requirementImages = [];
        $this->requirementInputs = [];
        $this->requirementSpecialEligibility = [];
        $this->requirementSurvey = [];
        $this->applicationTexts = [];
        $this->applicationFiles = [];
        $this->applicationImages = [];
        $this->applicationInputs = [];
        $this->applicationSpecialEligibility = [];
        $this->applicationSurvey = [];
        $this->applications = [];

        $this->setCreatedDate(new \DateTime());
        $this->setLastUpdatedDate(new \DateTime());
        $this->setStatus(ScholarshipStatus::UNPUBLISHED);

        $eligibilities = $this->getEligibilities();
        $this->eligibilities = new ArrayCollection();
        foreach ($eligibilities as $eligibility) {
            $this->addEligibility(clone $eligibility);
        }

        $forms = $this->getForms();
        $this->forms = new ArrayCollection();
        foreach ($forms as $form) {
            $this->addForm(clone $form);
        }
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->getStatus()->is(ScholarshipStatus::PUBLISHED);
    }

    /**
     * @return bool
     */
    public function isUnpublished()
    {
        return $this->getStatus()->is(ScholarshipStatus::UNPUBLISHED);
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->getStatus()->is(ScholarshipStatus::EXPIRED);
    }

    /**
     * @param \DateTime|null $now
     *
     * @return bool
     */
    public function checkExpired(\DateTime $now = null)
    {
        $timezone = new \DateTimeZone($this->getTimezone());

        $now = $now ?: new \DateTime();
        $now->setTimezone($timezone);

        $expiresAt = Carbon::instance($this->getExpirationDate())->setTimezone($timezone);

        return $now >= $expiresAt;
    }

    /**
     * Get scholarshipId
     *
     * @return integer
     */
    public function getScholarshipId()
    {
        return $this->scholarshipId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Scholarship
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Scholarship
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set applicationType
     *
     * @param string $applicationType
     *
     * @return Scholarship
     */
    public function setApplicationType($applicationType)
    {
        $this->applicationType = $applicationType;

        return $this;
    }

    /**
     * Get applicationType
     *
     * @return string
     */
    public function getApplicationType()
    {
        return $this->applicationType;
    }

    /**
     * Set applyUrl
     *
     * @param string $applyUrl
     *
     * @return Scholarship
     */
    public function setApplyUrl($applyUrl)
    {
        $this->applyUrl = $applyUrl;

        return $this;
    }

    /**
     * Get applyUrl
     *
     * @return string
     */
    public function getApplyUrl()
    {
        return $this->applyUrl;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return Scholarship
     */
    public function setExpirationDate(\DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Scholarship
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set upTo
     *
     * @param string $upTo
     *
     * @return Scholarship
     */
    public function setUpTo($upTo)
    {
        $this->upTo = $upTo;

        return $this;
    }

    /**
     * Get upTo
     *
     * @return string
     */
    public function getUpTo()
    {
        return $this->upTo;
    }

    /**
     * Set awards
     *
     * @param integer $awards
     *
     * @return Scholarship
     */
    public function setAwards($awards)
    {
        $this->awards = $awards;

        return $this;
    }

    /**
     * Get awards
     *
     * @return integer
     */
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Scholarship
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @param bool $nl2br
     * @return string
     */
    public function getDescription(bool $nl2br = false)
    {
        return $nl2br ? nl2br($this->description) : $this->description;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param $notes
     *
     * @return $this
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return $this
     */
    protected function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Set logo file that will be uploaded to cloud on update
     *
     * @param UploadedFile $logo
     *
     * @return $this
     */
    public function setLogoFile(UploadedFile $logo)
    {
        $this->shouldBeUploadedLogo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Scholarship
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailSubject
     *
     * @param string $emailSubject
     *
     * @return Scholarship
     */
    public function setEmailSubject($emailSubject)
    {
        $this->emailSubject = $emailSubject;

        return $this;
    }

    /**
     * Get emailSubject
     *
     * @return string
     */
    public function getEmailSubject()
    {
        return $this->emailSubject;
    }

    /**
     * Set emailMessage
     *
     * @param string $emailMessage
     *
     * @return Scholarship
     */
    public function setEmailMessage($emailMessage)
    {
        $this->emailMessage = $emailMessage;

        return $this;
    }

    /**
     * Get emailMessage
     *
     * @return string
     */
    public function getEmailMessage()
    {
        return $this->emailMessage;
    }

    /**
     * Set formAction
     *
     * @param string $formAction
     *
     * @return Scholarship
     */
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;

        return $this;
    }

    /**
     * Get formAction
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->formAction;
    }

    /**
     * Set formMethod
     *
     * @param string $formMethod
     *
     * @return Scholarship
     */
    public function setFormMethod($formMethod)
    {
        $this->formMethod = $formMethod;

        return $this;
    }

    /**
     * Get formMethod
     *
     * @return string
     */
    public function getFormMethod()
    {
        return $this->formMethod;
    }

    /**
     * Set termsOfServiceUrl
     *
     * @param string $termsOfServiceUrl
     *
     * @return Scholarship
     */
    public function setTermsOfServiceUrl($termsOfServiceUrl)
    {
        $this->termsOfServiceUrl = $termsOfServiceUrl;

        return $this;
    }

    /**
     * Get termsOfServiceUrl
     *
     * @return string
     */
    public function getTermsOfServiceUrl()
    {
        return $this->termsOfServiceUrl;
    }

    /**
     * Set privacyPolicyUrl
     *
     * @param string $privacyPolicyUrl
     *
     * @return Scholarship
     */
    public function setPrivacyPolicyUrl($privacyPolicyUrl)
    {
        $this->privacyPolicyUrl = $privacyPolicyUrl;

        return $this;
    }

    /**
     * Get privacyPolicyUrl
     *
     * @return string
     */
    public function getPrivacyPolicyUrl()
    {
        return $this->privacyPolicyUrl;
    }

    /**
     * @param ScholarshipStatus|int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = ScholarshipStatus::convert($status);
        return $this;
    }

    /**
     * @return ScholarshipStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $externalScholarshipId
     *
     * @return Scholarship
     */
    public function setExternalScholarshipId($externalScholarshipId)
    {
        $this->externalScholarshipId = $externalScholarshipId;

        return $this;
    }

    /**
     * @param int $externalScholarshipTemplateId
     *
     * @return Scholarship
     */
    public function setExternalScholarshipTemplateId($externalScholarshipTemplateId)
    {
        $this->externalScholarshipTemplateId = $externalScholarshipTemplateId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExternalScholarshipTemplateId()
    {
        return $this->externalScholarshipTemplateId;
    }

    /**
     * @return int|null
     */
    public function getExternalScholarshipId()
    {
        return $this->externalScholarshipId;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param $active
     *
     * @return $this
     */
    public function setIsActive($active)
    {
        $this->isActive = $active;
        return $this;
    }

    /**
     * Set isFree
     *
     * @param boolean $isFree
     *
     * @return Scholarship
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree
     *
     * @return boolean
     */
    public function getIsFree()
    {
        return $this->isFree;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Scholarship
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set lastUpdatedDate
     *
     * @param \DateTime $lastUpdatedDate
     *
     * @return Scholarship
     */
    public function setLastUpdatedDate(\DateTime $lastUpdatedDate)
    {
        $this->lastUpdatedDate = $lastUpdatedDate;

        return $this;
    }

    /**
     * Get lastUpdatedDate
     *
     * @return \DateTime
     */
    public function getLastUpdatedDate()
    {
        return $this->lastUpdatedDate;
    }

    /**
     * Set filesAlowed
     *
     * @param boolean $filesAlowed
     *
     * @return Scholarship
     */
    public function setFilesAlowed($filesAlowed)
    {
        $this->filesAlowed = $filesAlowed;

        return $this;
    }

    /**
     * Get filesAlowed
     *
     * @return boolean
     */
    public function getFilesAlowed()
    {
        return $this->filesAlowed;
    }

    /**
     * Set isAutomatic
     *
     * @param boolean $isAutomatic
     *
     * @return Scholarship
     */
    public function setIsAutomatic($isAutomatic)
    {
        $this->isAutomatic = $isAutomatic;

        return $this;
    }

    /**
     * Get isAutomatic
     *
     * @return boolean
     */
    public function getIsAutomatic()
    {
        return $this->isAutomatic;
    }

    /**
     * @param $sendToPrivate
     *
     * @return $this
     */
    public function setSendToPrivate($sendToPrivate)
    {
        $this->sendToPrivate = $sendToPrivate;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSendToPrivate()
    {
        return $this->sendToPrivate;
    }

    /**
     * @return string
     */
    public function getMetaAuthor()
    {
        return $this->metaAuthor;
    }

    /**
     * @param string $author
     *
     * @return $this
     */
    public function setMetaAuthor(string $author)
    {
        $this->metaAuthor = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     *
     * @return $this
     */
    public function setMetaTitle(string $metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     *
     * @return $this
     */
    public function setMetaDescription(string $metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     *
     * @return $this
     */
    public function setMetaKeywords(string $metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Add eligibility
     *
     * @param \App\Entity\Eligibility $eligibility
     *
     * @return Scholarship
     */
    public function addEligibility(Eligibility $eligibility)
    {
        $this->eligibilities[] = $eligibility->setScholarship($this);

        return $this;
    }

    /**
     * Remove eligibility
     *
     * @param Eligibility $eligibility
     */
    public function removeEligibility(Eligibility $eligibility)
    {
        $this->eligibilities->removeElement($eligibility);
    }

    /**
     * @param ScholarshipFile $scholarshipFile
     *
     * @return $this
     */
    public function addScholarshipFile(ScholarshipFile $scholarshipFile)
    {
        if (!$this->scholarshipFiles->contains($scholarshipFile)) {
            $this->scholarshipFiles->add($scholarshipFile);
            $scholarshipFile->setScholarship($this);
        }

        return $this;
    }

    /**
     * @param ScholarshipFile $scholarshipFile
     *
     * @return $this
     */
    public function removeScholarshipFile(ScholarshipFile $scholarshipFile)
    {
        $this->scholarshipFiles->removeElement($scholarshipFile);

        return $this;
    }

    /**
     * @return ArrayCollection|ScholarshipFile[]
     */
    public function getScholarshipFiles()
    {
        return $this->scholarshipFiles;
    }

    /**
     * Get eligibilities
     *
     * @return ArrayCollection|Eligibility[]
     */
    public function getEligibilities()
    {
        return $this->eligibilities;
    }

    /**
     * @param int $applicationStatus
     *
     * @return $this
     */
    public function setApplicationStatus(int $applicationStatus)
    {
        $this->applicationStatus = $applicationStatus;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getApplicationStatus()
    {
        return $this->applicationStatus;
    }

    /**
     * @return Application[]|ArrayCollection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param Application[]|ArrayCollection $applications
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;
    }

    /**
     * @param Essay $essay
     *
     * @return $this
     */
    public function addEssay(Essay $essay)
    {
        if (!$this->essays->contains($essay)) {
            $this->essays->add($essay->setScholarship($this));
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Essay[]
     */
    public function getEssays()
    {
        return $this->essays;
    }

    /**
     * @param bool $nl2br
     * @return RequirementContract[]|ArrayCollection
     */
    public function getRequirementTexts(bool $nl2br = false)
    {
        if ($nl2br) {
            /** @var RequirementText $item */
            foreach ($this->requirementTexts as $k => $item) {
                $item->setDescription(nl2br($item->getDescription()));
            }
        }

        return  $this->requirementTexts;
    }

    /**
     * @return RequirementContract[]|ArrayCollection
     */
    public function getMandatoryRequirementFiles()
    {
        return $this->getMandatoryRequirement($this->requirementFiles);
    }

    /**
     * @return RequirementContract[]|ArrayCollection
     */
    public function getMandatoryRequirementTexts()
    {
        return $this->getMandatoryRequirement($this->requirementTexts);
    }

    /**
     * @return RequirementContract[]|ArrayCollection
     */
    public function getMandatoryRequirementImages()
    {
        return $this->getMandatoryRequirement($this->requirementImages);
    }

    /**
     * @return RequirementContract[]|ArrayCollection
     */
    public function getMandatoryRequirementInputs()
    {
        return $this->getMandatoryRequirement($this->requirementInputs);
    }
    /**
     * @return RequirementContract[]|ArrayCollection
     */
    public function getMandatoryRequirementSurvey()
    {
        return $this->getMandatoryRequirement($this->requirementSurvey);
    }

    /**
    * @return RequirementContract[]|ArrayCollection
    */
    public function getMandatoryRequirementSpecialEligibility()
    {
        return $this->getMandatoryRequirement($this->requirementSpecialEligibility);
    }

    /**
     * @param $requirementList
     * @return RequirementContract[]|ArrayCollection
     */
    protected function getMandatoryRequirement($requirementList)
    {
        $res = new ArrayCollection();
        foreach ($requirementList as $requirement) {
            if (!$requirement->isOptional()) {
                $res->add($requirement);
            }
        }

        return $res;
    }
    /**
     * @param RequirementContract $requirementText
     *
     * @return $this
     */
    public function addRequirementText(RequirementContract $requirementText)
    {
        if (!$this->requirementTexts->contains($requirementText)) {
            $this->requirementTexts->add($requirementText->setScholarship($this));
        }

        return $this;
    }

    /**
     * @param RequirementContract $requirementText
     *
     * @return $this
     */
    public function removeRequirementText(RequirementContract $requirementText)
    {
        $this->requirementTexts->removeElement($requirementText);

        return $this;
    }


    /**
     * @param RequirementContract $requirementSpecialEligibility
     *
     * @return $this
     */
    public function addRequirementSpecialEligibility(RequirementContract $requirementSpecialEligibility)
    {
        if (!$this->requirementSpecialEligibility->contains($requirementSpecialEligibility)) {
            $this->requirementSpecialEligibility->add($requirementSpecialEligibility->setScholarship($this));
        }

        return $this;
    }

    /**
     * @param RequirementContract $requirementText
     *
     * @return $this
     */
    public function removeRequirementSpecialEligibility(RequirementContract $requirementSpecialEligibility)
    {
        $this->requirementSpecialEligibility->removeElement($requirementSpecialEligibility);

        return $this;
    }

    /**
     * @param bool $nl2br
     * @return RequirementSpecialEligibility[]|ArrayCollection
     */
    public function getRequirementSpecialEligibility(bool $nl2br = false)
    {
        if ($nl2br) {
            /** @var RequirementSpecialEligibility $item */
            foreach ($this->requirementSpecialEligibility as $k => $item) {
                $item->setDescription(nl2br($item->getDescription()));
            }
        }

        return $this->requirementSpecialEligibility;
    }

    /**
     * @param RequirementContract $requirementSurvey
     *
     * @return $this
     */
    public function removeRequirementSurvey(RequirementContract $requirementSurvey)
    {
        $this->requirementSurvey->removeElement($requirementSurvey);

        return $this;
    }

    /**
     * @param RequirementContract $requirementSurvey
     *
     * @return $this
     */
    public function addRequirementSurvey(RequirementContract $requirementSurvey)
    {
        if (!$this->requirementSurvey->contains($requirementSurvey)) {
            $this->requirementSurvey->add($requirementSurvey->setScholarship($this));
        }

        return $this;
    }

    /**
     * @return RequirementContract[]|ArrayCollection
     */
    public function getRequirementSurvey(bool $nl2br = false)
    {
        if ($nl2br) {
            /** @var RequirementSurvey $item */
            foreach ($this->requirementSurvey as $k => $item) {
                $item->setDescription(nl2br($item->getDescription()));
            }
        }

        return $this->requirementSurvey;
    }

    /**
     * @param bool $nl2br
     * @return RequirementFile[]|ArrayCollection
     */
    public function getRequirementFiles(bool $nl2br = false)
    {
        if ($nl2br) {
            /** @var RequirementFile $item */
            foreach ($this->requirementFiles as $k => $item) {
                $item->setDescription(nl2br($item->getDescription()));
            }
        }

        return $this->requirementFiles;
    }

    /**
     * @param RequirementFile $requirementFile
     *
     * @return $this
     */
    public function addRequirementFile(RequirementFile $requirementFile)
    {
        if (!$this->requirementFiles->contains($requirementFile)) {
            $this->requirementFiles->add($requirementFile->setScholarship($this));
        }

        return $this;
    }

    /**
     * @param RequirementFile $requirementFile
     *
     * @return $this
     */
    public function removeRequirementFile(RequirementFile $requirementFile)
    {
        $this->requirementFiles->removeElement($requirementFile);

        return $this;
    }

    /**
     * @param bool $nl2br
     * @return RequirementImage[]|ArrayCollection
     */
    public function getRequirementImages(bool $nl2br = false)
    {
        if ($nl2br) {
            /** @var RequirementImage $item */
            foreach ($this->requirementImages as $k => $item) {
                $item->setDescription(nl2br($item->getDescription()));
            }
        }

        return $this->requirementImages;
    }

    /**
     * @param RequirementImage $requirementImage
     *
     * @return $this
     */
    public function addRequirementImage(RequirementImage $requirementImage)
    {
        if (!$this->requirementImages->contains($requirementImage)) {
            $this->requirementImages->add($requirementImage->setScholarship($this));
        }

        return $this;
    }

    /**
     * @param RequirementImage $requirementImage
     *
     * @return $this
     */
    public function removeRequirementImage(RequirementImage $requirementImage)
    {
        $this->requirementImages->removeElement($requirementImage);

        return $this;
    }

    /**
     * @param bool $nl2br
     * @return RequirementInput[]|ArrayCollection
     */
    public function getRequirementInputs(bool $nl2br = false)
    {
        if ($nl2br) {
            /** @var RequirementInput $item */
            foreach ($this->requirementInputs as $k => $item) {
                $item->setDescription(nl2br($item->getDescription()));
            }
        }

        return $this->requirementInputs;
    }

    /**
     * @param RequirementInput $requirementInput
     *
     * @return $this
     */
    public function addRequirementInput(RequirementInput $requirementInput)
    {
        if (!$this->requirementInputs->contains($requirementInput)) {
            $this->requirementInputs->add($requirementInput->setScholarship($this));
        }

        return $this;
    }

    /**
     * @param RequirementInput $requirementInput
     *
     * @return $this
     */
    public function removeRequirementInput(RequirementInput $requirementInput)
    {
        $this->requirementInputs->removeElement($requirementInput);

        return $this;
    }

    /**
     * @param RequirementContract $requirement
     */
    public function addRequirement(RequirementContract $requirement)
    {
        switch ($requirement->getType()) {
            case RequirementText::TYPE:
                /** @var $requirement RequirementText */
                $this->addRequirementText($requirement);
                break;
            case RequirementFile::TYPE:
                /** @var $requirement RequirementFile */
                $this->addRequirementFile($requirement);
                break;
            case RequirementImage::TYPE:
                /** @var $requirement RequirementImage */
                $this->addRequirementImage($requirement);
                break;
            case RequirementInput::TYPE:
                /** @var $requirement RequirementInput */
                $this->addRequirementInput($requirement);
                break;
            case RequirementSurvey::TYPE:
                /** @var $requirement RequirementSurvey */
                $this->addRequirementSurvey($requirement);
                break;
            case RequirementSpecialEligibility::TYPE:
                /** @var $requirement RequirementSpecialEligibility */
                $this->addRequirementSpecialEligibility($requirement);
                break;
            default:
                throw new \RuntimeException(sprintf('Unknown requirement type: %s', $requirement->getType()));
                break;
        }
    }

    /**
     * @return ArrayCollection|RequirementContract[]
     */
    public function getRequirements()
    {
        return new ArrayCollection(collection_merge(
            $this->getRequirementTexts(),
            $this->getRequirementFiles(),
            $this->getRequirementImages(),
            $this->getRequirementInputs(),
            $this->getRequirementSurvey(),
            $this->getRequirementSpecialEligibility()
        ));
    }

    /**
     * @param array $applicationTexts
     *
     * @return $this
     */
    public function setApplicationTexts(array $applicationTexts)
    {
        $this->applicationTexts = new ArrayCollection($applicationTexts);

        return $this;
    }

    /**
     * @param Account $account
     * @return ApplicationText[]|ArrayCollection|null
     */
    public function getApplicationTexts(Account $account)
    {
        if ($this->applicationTexts instanceof Selectable) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('account', $account));

            return $this->applicationTexts->matching($criteria);
        }

        return $this->applicationTexts;
    }

    /**
     * @param ApplicationText $applicationText
     *
     * @return $this
     */
    public function removeApplicationText(ApplicationText $applicationText)
    {
        if ($this->applicationTexts) {
            $this->applicationTexts->removeElement($applicationText);
        }

        return $this;
    }

    /**
     * @param array $applicationFiles
     *
     * @return $this
     */
    public function setApplicationFiles(array $applicationFiles)
    {
        $this->applicationFiles = new ArrayCollection($applicationFiles);

        return $this;
    }

    /**
     * @param Account $account
     * @return ApplicationFile[]|ArrayCollection
     */
    public function getApplicationFiles(Account $account)
    {
        if ($this->applicationFiles instanceof Selectable) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('account', $account));

            return $this->applicationFiles->matching($criteria);
        }

        return $this->applicationFiles;
    }

    /**
     * @param ApplicationFile $applicationFile
     *
     * @return $this
     */
    public function removeApplicationFile(ApplicationFile $applicationFile)
    {
        if ($this->applicationFiles) {
            $this->applicationFiles->removeElement($applicationFile);
        }

        return $this;
    }

    /**
     * @param array $applicationImages
     *
     * @return $this
     */
    public function setApplicationImages(array $applicationImages)
    {
        $this->applicationImages = new ArrayCollection($applicationImages);

        return $this;
    }

    /**
     * @param Account $account
     * @return ApplicationImage[]|ArrayCollection|null
     */
    public function getApplicationImages(Account $account)
    {
        if ($this->applicationImages instanceof Selectable) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('account', $account));

            return $this->applicationImages->matching($criteria);
        }

        return $this->applicationImages;
    }

    /**
     * @param ApplicationImage $applicationImage
     *
     * @return $this
     */
    public function removeApplicationImage(ApplicationImage $applicationImage)
    {
        if ($this->applicationImages) {
            $this->applicationImages->removeElement($applicationImage);
        }

        return $this;
    }

    /**
     * @param array $applicationInputs
     *
     * @return $this
     */
    public function setApplicationInputs(array $applicationInputs)
    {
        $this->applicationInputs = new ArrayCollection($applicationInputs);

        return $this;
    }

    /**
     * @param Account $account
     * @return ApplicationInput[]|ArrayCollection|null
     */
    public function getApplicationInputs(Account $account)
    {
        if ($this->applicationInputs instanceof Selectable) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('account', $account));

            return $this->applicationInputs->matching($criteria);
        }

        return $this->applicationInputs;
    }

    /**
     * @param ApplicationInput $applicationInput
     *
     * @return $this
     *
     */
    public function removeApplicationInput(ApplicationInput $applicationInput)
    {
        if ($this->applicationInputs) {
            $this->applicationInputs->removeElement($applicationInput);
        }

        return $this;
    }

    /**
     * @param Account $account
     * @return RequirementContract[]|ArrayCollection
     */
    public function getApplicationSurvey(Account $account)
    {
        if ($this->applicationSurvey instanceof Selectable) {

            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('account', $account));
            return $this->applicationSurvey->matching($criteria);
        }

        return $this->applicationSurvey;
    }

    /**
     * @param array $applicationSurvey
     *
     * @return $this
     */
    public function setApplicationSurveys(array $applicationSurvey)
    {
        $this->applicationSurvey = new ArrayCollection($applicationSurvey);

        return $this;
    }

    /**
     * @param array $applicationSpecialEligibility
     *
     * @return $this
     */
    public function setApplicationSpecialEligibility(array $applicationSpecialEligibility)
    {
        $this->applicationSpecialEligibility = new ArrayCollection($applicationSpecialEligibility);

        return $this;
    }

    /**
     * @param Account $account
     * @return ApplicationSpecialEligibility[]|ArrayCollection|null
     */
    public function getApplicationSpecialEligibility(Account $account)
    {
        if ($this->applicationSpecialEligibility instanceof Selectable) {
            $criteria = Criteria::create();
            $criteria->where(Criteria::expr()->eq('account', $account));

            return $this->applicationSpecialEligibility->matching($criteria);
        }

        return $this->applicationSpecialEligibility;
    }

    /**
     * @param ApplicationSpecialEligibility $applicationSpecialEligibility
     *
     * @return $this
     */
    public function removeApplicationSpecialEligibility(ApplicationSpecialEligibility $applicationSpecialEligibility)
    {
        if ($this->applicationSpecialEligibility) {
            $this->applicationSpecialEligibility->removeElement($applicationSpecialEligibility);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|ApplicationRequirementContract[]
     */
    public function getApplicationRequirements(Account $account)
    {
        return new ArrayCollection(collection_merge(
            $this->getApplicationTexts($account),
            $this->getApplicationFiles($account),
            $this->getApplicationImages($account),
            $this->getApplicationInputs($account),
            $this->getApplicationSurvey($account),
            $this->getApplicationSpecialEligibility($account)
        ));
    }

    /**
     * @return ArrayCollection|RequirementContract[]
     */
    public function getFinishedRequirements(Account $account)
    {
        return $this->getApplicationRequirements($account)->map(
            function (ApplicationRequirementContract $applicationRequirement) {
                return $applicationRequirement->getRequirement();
            }
        );
    }

    /**
     * @return Form[]|ArrayCollection
     */
    public function getForms()
    {
        return $this->forms;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function addForm(Form $form)
    {
        if (!$this->forms->contains($form)) {
            $this->forms->add($form->setScholarship($this));
        }

        return $this;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function removeForm(Form $form)
    {
        $this->forms->removeElement($form);

        return $this;
    }

    /**
     * @return string
     */
    public function getScholarshipPageUrl()
    {
        return $this->getScholarshipId() . "-" . Str::slug($this->getTitle());
    }

    /**
     * @return string
     */
    public function getTitleSlug()
    {
        return Str::slug($this->getTitle());
    }

    /**
     * Get scholarship page URL
     * @return string
     */
    public function getPublicUrl()
    {
        return \URL::route('scholarships.view', ['id' => $this->getScholarshipId(), 'slug' => $this->getTitleSlug()]);
    }

    /**
     * Public logo URL
     *
     * @return mixed
     */
    public function getLogoUrl()
    {
        return $this->getLogo() ? \Storage::public ($this->getLogo()) : asset("assets/img/scholarship/college.jpg");
    }

    /**
     * @ORM\PostUpdate()
     * @ORM\PostRemove()
     */
    public function flushCacheTag()
    {
        \Cache::tags([$this->cacheTag()])->flush();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostRemove()
     */
    public function flushCacheTagGeneral()
    {
        \Cache::tags([$this->cacheTagGeneral()])->flush();
        \Cache::tags(ScholarshipRepository::CACHE_YDI_SCHOLARSHIPS)->flush();
    }

    /**
     * After entity saved and we have scholarship ID we can upload logo to cloud then update the 'logo' field
     *
     * @param LifecycleEventArgs $event
     *
     * @ORM\PostUpdate
     * @ORM\PostPersist
     */
    public function uploadAndSetLogoOnPostPersistAndPreFlush(LifecycleEventArgs $event)
    {
        if ($this->getScholarshipId() && ($logo = $this->shouldBeUploadedLogo)) {
            \Image::make($logo)
                ->resize(static::LOGO_IMAGE_WIDTH, static::LOGO_IMAGE_HEIGHT,
                    function (Constraint $constraint) {
                        $constraint->aspectRatio();
                    })
                ->save($logo);

            $this->setLogo(static::CLOUD_FOLDER . '/' . $this->getScholarshipId() . '_' . $logo->getClientOriginalName());
            \Storage::disk('gcs')->put($this->getLogo(), file_get_contents($logo), Filesystem::VISIBILITY_PUBLIC);
            $this->shouldBeUploadedLogo = null;
            $event->getEntityManager()->flush($this);
        }
    }

    /**
     * @return $this
     */
    public function removeLogo()
    {
        if ($this->getLogo()) {
            \Storage::disk('gcs')->delete($this->getLogo());
            \EntityManager::flush($this->setLogo(null));
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRecurrent()
    {
        return $this->isRecurrent;
    }

    /**
     * @return bool
     */
    public function isRecurrent()
    {
        return $this->getIsRecurrent();
    }

    /**
     * @param boolean $isRecurrent
     */
    public function setIsRecurrent($isRecurrent)
    {
        $this->isRecurrent = $isRecurrent;

        return $this;
    }

    /**
     * @return Scholarship
     */
    public function getParentScholarship()
    {
        return $this->parentScholarship;
    }

    /**
     * @param Scholarship $parentScholarship
     *
     * @return $this
     */
    public function setParentScholarship($parentScholarship)
    {
        $this->parentScholarship = $parentScholarship;

        return $this;
    }

    /**
     * @param $currentScholarship
     *
     * @return $this
     */
    public function setCurrentScholarship(Scholarship $currentScholarship)
    {
        $this->currentScholarship = $currentScholarship;

        return $this;
    }

    /**
     * @return Scholarship
     */
    public function getCurrentScholarship()
    {
        $currentScholarship = $this->currentScholarship;

        if($this->applicationType == self::APPLICATION_TYPE_SUNRISE) {
            $currentScholarship = \EntityManager::getRepository(static::class)->getCurrentScholarshipForSunrise($this);
            $currentScholarship = empty($currentScholarship) ? null : array_pop($currentScholarship);
        }

        return $currentScholarship ;
    }

    /**
     * @param bool $recurrenceStartNow
     *
     * @return $this
     */
    public function setRecurrenceStartNow($recurrenceStartNow)
    {
        $this->recurrenceStartNow = (bool) $recurrenceStartNow;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRecurrenceStartNow()
    {
        return $this->recurrenceStartNow;
    }

    /**
     * @return bool
     */
    public function isRecurrenceStartNow()
    {
        return $this->recurrenceStartNow;
    }

    /**
     * @param bool $recurrenceEndMonth
     *
     * @return $this
     */
    public function setRecurrenceEndMonth($recurrenceEndMonth)
    {
        $this->recurrenceEndMonth = (bool) $recurrenceEndMonth;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRecurrenceEndMonth()
    {
        return $this->recurrenceEndMonth;
    }

    /**
     * @return bool
     */
    public function isRecurrenceEndMonth()
    {
        return $this->recurrenceEndMonth;
    }

    /**
     * @param \DateTime $eligibilityUpdate
     *
     * @return $this
     */
    public function setEligibilityUpdate(\DateTime $eligibilityUpdate)
    {
        $this->eligibilityUpdate = $eligibilityUpdate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEligibilityUpdate()
    {
        return $this->eligibilityUpdate;
    }

    /**
     * @param string $timezone
     *
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezoneObj()
    {
        return new \DateTimeZone($this->getTimezone());
    }

    /**
     * Replaces \n\l with <br> in description fields
     */
    public function nl2br()
    {
        $this->description = $this->getDescription(true);
        $this->requirementFiles = $this->getRequirementFiles(true);
        $this->requirementTexts = $this->getRequirementTexts(true);
        $this->requirementInputs = $this->getRequirementInputs(true);
        $this->requirementImages = $this->getRequirementImages(true);
        $this->requirementSpecialEligibility = $this->getRequirementSpecialEligibility(true);
        $this->requirementSurvey = $this->getRequirementSurvey(true);
    }

    /**
     * @return $this
     */
    public function setFavorite(){
        $this->isFavorite = 1;
        return $this;
    }

    /**
     * @return int
     */
    public function isFavorite(){
        return $this->isFavorite;
    }

    /**
     * @return int
     */
    public function isSent()
    {
        return $this->isSent;
    }

    /**
     * @param int $isSent
     *
     * @return Scholarship
     */
    public function setIsSent()
    {
        $this->isSent = 1;
        return $this;
    }

    /**
     * @param string $status
     * @throws \ReflectionException
     */
    public function setTransitionalStatus(string $status = null)
    {
        if ($status && !in_array($status, $this->transitionalStatuses())) {
            throw new \InvalidArgumentException(sprintf('Unknown transitional status: [ %s ]', $status));
        }

        $this->transitionalStatus = $status;
    }

    /**
     * @return string|null
     */
    public function getTransitionalStatus()
    {
        return $this->transitionalStatus;
    }

    /**
     * @param string $winnerFormUrl
     */
    public function setWinnerFormUrl(string $url = null)
    {
        $this->winnerFormUrl = $url;
    }

    /**
     * @return string|null
     */
    public function getWinnerFormUrl()
    {
        return $this->winnerFormUrl;
    }

    /**
     * @param string $status
     */
    public function setDerivedStatus(string $status)
    {
        $this->derivedStatus = $status;
    }

    /**
     * @return string
     */
    public function getDerivedStatus()
    {
        return $this->derivedStatus;
    }

    /**
     * List of possible transitional statuses
     *
     * @return array
     * @throws \ReflectionException
     */
    public function transitionalStatuses()
    {
        $statuses = array_filter((new \ReflectionClass(__CLASS__))->getConstants(), function ($key) {
            return strpos($key, 'TRANSITIONAL_STATUS_') !== false;
        }, ARRAY_FILTER_USE_KEY);

        return $statuses;
    }

    /**
     * List of possible derived statuses
     *
     * @return array
     * @throws \ReflectionException
     */
    public function derivedStatuses()
    {
        $statuses = array_filter((new \ReflectionClass(__CLASS__))->getConstants(), function ($key) {
            return strpos($key, 'DERIVED_STATUS_') !== false;
        }, ARRAY_FILTER_USE_KEY);

        return $statuses;
    }
}

