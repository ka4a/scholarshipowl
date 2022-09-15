<?php namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Http\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Application winner created after winner choosing.
 *
 * @ORM\Entity(repositoryClass="App\Repositories\ApplicationWinnersRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ApplicationWinner implements JsonApiResource
{
    use Timestamps;

    const FORM_FIELDS = [
        'name',
        'email',
        'phone',
        'state',
        'address',
        'address2',
        'zip',
        'dateOfBirth',
        'testimonial',
        'paypal',
        'bankName',
        'nameOfAccount',
        'accountNumber',
        'routingNumber',
        'swiftCode',
        'photo',
        'affidavit',
    ];

    /**
     * @return string
     */
    public static function getResourceKey()
    {
        return 'application_winner';
    }

    /**
     * @var int
     *
     * @ORM\Id();
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Application
     *
     * @ORM\OneToOne(targetEntity="Application", inversedBy="winner")
     * @ORM\JoinColumn(name="application_id", unique=false)
     */
    protected $application;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Applicant email.
     *
     * @var string
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * Applicant phone number.
     *
     * @var string
     * @ORM\Column(name="phone", type="string", nullable=false)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @var State
     * @ORM\ManyToOne(targetEntity="State", fetch="EAGER")
     * @ORM\JoinColumn(name="state_id", nullable=false, unique=false)
     */
    protected $state;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address2;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $zip;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @var string
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    protected $testimonial;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paypal;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $bankName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nameOfAccount;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $accountNumber;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $routingNumber;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $swiftCode;

    /**
     * @var ApplicationFile
     * @ORM\OneToOne(targetEntity="ApplicationFile", cascade={"persist","remove"})
     */
    protected $photo;

    /**
     * @var ApplicationFile
     * @ORM\OneToOne(targetEntity="ApplicationFile", cascade={"persist","remove"})
     */
    protected $photoSmall;

    /**
     * @var ArrayCollection|ApplicationFile[]
     * @ORM\ManyToMany(targetEntity="ApplicationFile", cascade={"persist"})
     */
    protected $affidavit;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $filled = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $paused = false;

    /**
     * Number of notifications sent to user.
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $notified = 0;

    /**
     * @var \DateTime
     * @ORM\Column(name="disqualifiedAt", type="datetime", nullable=true)
     */
    protected $disqualifiedAt;

    /**
     * @var ScholarshipWinner
     * @ORM\OneToOne(targetEntity="ScholarshipWinner", mappedBy="applicationWinner")
     */
    protected $scholarshipWinner;

    /**
     * ApplicationWinner constructor.
     */
    public function __construct()
    {
        $this->affidavit = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function markAsFilled()
    {
        if (!empty($this->bankName) || !empty($this->paypal)) {
            $this->setFilled(true);
        }
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
    public function setApplication(Application $application)
    {
        $this->application = $application->setWinner($this);
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
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $accountNumber
     * @return $this
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $bankName
     * @return $this
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @param \DateTime|string $dateOfBirth
     * @return $this
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = is_string($dateOfBirth) ? new \DateTime($dateOfBirth) : $dateOfBirth;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string $nameOfAccount
     * @return $this
     */
    public function setNameOfAccount($nameOfAccount)
    {
        $this->nameOfAccount = $nameOfAccount;
        return $this;
    }

    /**
     * @return string
     */
    public function getNameOfAccount()
    {
        return $this->nameOfAccount;
    }

    /**
     * @param string $paypal
     * @return $this
     */
    public function setPaypal($paypal)
    {
        $this->paypal = $paypal;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaypal()
    {
        return $this->paypal;
    }

    /**
     * @param string $routingNumber
     * @return $this
     */
    public function setRoutingNumber($routingNumber)
    {
        $this->routingNumber = $routingNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * @param string $swiftCode
     * @return $this
     */
    public function setSwiftCode($swiftCode)
    {
        $this->swiftCode = $swiftCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getSwiftCode()
    {
        return $this->swiftCode;
    }

    /**
     * @param string $testimonial
     * @return $this
     */
    public function setTestimonial($testimonial)
    {
        $this->testimonial = $testimonial;
        return $this;
    }

    /**
     * @return string
     */
    public function getTestimonial()
    {
        return $this->testimonial;
    }

    /**
     * @param UploadedFile|ApplicationFile $photo
     * @return $this
     */
    public function setPhoto($photo)
    {
        if ($photo instanceof UploadedFile) {
            $this->getApplication()->addFiles($photo = ApplicationFile::uploaded($photo));
        }

        $this->photo = $photo;
        return $this;
    }

    /**
     * @return ApplicationFile
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param ApplicationFile|UploadedFile $photo
     * @return $this
     */
    public function setPhotoSmall($photo)
    {
        if ($photo instanceof UploadedFile) {
            $this->getApplication()->addFiles($photo = ApplicationFile::uploaded($photo));
        }

        $this->photoSmall = $photo;
        return $this;
    }

    /**
     * @return ApplicationFile
     */
    public function getPhotoSmall()
    {
        return $this->photoSmall;
    }

    /**
     * @param array $files
     * @return $this
     */
    public function setAffidavit($files)
    {
        $affidavits = [];
        foreach ($files as $affidavit) {
            if ($affidavit instanceof UploadedFile) {
                $affidavit = ApplicationFile::uploaded($affidavit);
                $this->getApplication()->addFiles($affidavit);
            }

            $affidavits[] = $affidavit;
        }

        $this->affidavit = $affidavits;
        return $this;
    }

    /**
     * @return ApplicationFile
     */
    public function getAffidavit()
    {
        return $this->affidavit;
    }

    /**
     * @param bool $filled
     * @return $this
     */
    public function setFilled($filled)
    {
        $this->filled = $filled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilled()
    {
        return $this->filled;
    }

    /**
     * @param $paused
     * @return $this
     */
    public function setPaused($paused)
    {
        $this->paused = $paused;
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
     * @return $this
     */
    public function incrementNotified()
    {
        $this->notified++;
        return $this;
    }

    /**
     * @param int $notified
     * @return $this
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;
        return $this;
    }

    /**
     * @return int
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * @param \DateTime $disqualifiedAt
     * @return $this
     */
    public function setDisqualifiedAt(\DateTime $disqualifiedAt)
    {
        $this->disqualifiedAt = $disqualifiedAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisqualified()
    {
        return !is_null($this->getDisqualifiedAt());
    }

    /**
     * @return \DateTime
     */
    public function getDisqualifiedAt()
    {
        return $this->disqualifiedAt;
    }

    /**
     * @return ScholarshipWinner
     */
    public function getScholarshipWinner()
    {
        return $this->scholarshipWinner;
    }

    /**
     * @param ScholarshipWinner $scholarshipWinner
     * @return $this
     */
    public function setScholarshipWinner(ScholarshipWinner $scholarshipWinner)
    {
        $this->scholarshipWinner = $scholarshipWinner;
        return $this;
    }
}
