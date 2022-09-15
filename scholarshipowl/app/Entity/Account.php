<?php namespace App\Entity;

use App\Contracts\CachableEntity;
use App\Contracts\MappingTags;
use App\Entity\Marketing\Submission;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Services\EligibilityCacheService;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Entity\Annotations\Restricted;
use LaravelDoctrine\Extensions\SoftDeletes\SoftDeletes;
use LaravelDoctrine\ORM\Notifications\Notifiable;
use ScholarshipOwl\Data\DateHelper;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Account
 * @package App\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AccountRepository")
 * @ORM\Table(name="account")
 */
class Account implements Authenticatable, JWTSubject, CachableEntity, MappingTags
{

    const MEMBERSHIP_FREE = 'free';
    const MEMBERSHIP_FREE_TRIAL = 'free_trial';
    const MEMBERSHIP_FREEMIUM = 'freemium';
    const MEMBERSHIP_PAID = 'paid';

    use Notifiable;
    use SoftDeletes;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $accountId;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Restricted()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Restricted()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="password_external", type="string", length=32, nullable=false)
     */
    private $passwordExternal;

    /**
     * @var string
     *
     * @ORM\Column(name="remember_token", type="string", length=100, nullable=true)
     */
    private $rememberToken;

    /**
     * @var string
     *
     * @ORM\Column(name="referral_code", type="string", length=8, nullable=true)
     */
    private $referralCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated_date", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    private $lastUpdatedDate;

    /**
     * @var AccountStatus
     *
     * @ORM\OneToOne(targetEntity="AccountStatus", fetch="EAGER")
     * @ORM\JoinColumn(name="account_status_id", referencedColumnName="account_status_id")
     */
    private $accountStatus;

    /**
     * @var AccountType
     *
     * @ORM\ManyToOne(targetEntity="AccountType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_type_id", referencedColumnName="account_type_id")
     * })
     */
    private $accountType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AbTest", mappedBy="account")
     */
    private $abTest;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Account", inversedBy="referralAccount", fetch="LAZY")
     * @ORM\JoinTable(name="referral",
     *   joinColumns={
     *     @ORM\JoinColumn(name="referral_account_id", referencedColumnName="account_id", unique=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="referred_account_id", referencedColumnName="account_id")
     *   }
     * )
     */
    private $referredAccount;

    /**
     * @var string
     *
     * @ORM\Column(name="zendesk_user_id", type="bigint", nullable=true)
     */
    private $zendeskUserId;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="last_action_at", type="datetime", nullable=true)
     */
    private $lastActionAt;

    /**
     * @var ArrayCollection|Subscription[] $subscriptions
     *
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="account", cascade={"persist"})
     * @ORM\OrderBy({"priority"="ASC"})
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection|Application[]
     *
     * @ORM\OneToMany(targetEntity="Application", mappedBy="account")
     */
    protected $applications;

    /**

     * @var ArrayCollection|ApplicationText[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ApplicationText", mappedBy="account")
     */
    protected $applicationText;

    /**
     * @var Domain
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     * })
     */
    protected $domain;

    /**
     * @var Profile
     *
     * @ORM\OneToOne(targetEntity="Profile", inversedBy="account", fetch="LAZY", cascade={"all"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    protected $profile;

    /**
     * @var AccountOnBoardingCall
     *
     * @ORM\OneToMany(targetEntity="AccountOnBoardingCall", mappedBy="account", cascade={"persist", "remove"})
     */
    protected $accountOnBoardingCall;

    /**
     * @var ArrayCollection|SuperCollegeScholarshipMatch[]
     *
     * @ORM\OneToMany(targetEntity="SuperCollegeScholarshipMatch", mappedBy="account", fetch="LAZY", cascade={"all"})
     */
    private $superCollegeScholarshipMatches;

    /**
     * @var ArrayCollection|MarketingSystemAccountData[]
     *
     * @ORM\OneToMany(targetEntity="MarketingSystemAccountData", mappedBy="account", cascade={"all"})
     */
    protected $marketingData = [];

    /**
     * @var AccountEligibleScholarshipsCount
     *
     * @ORM\OneToOne(targetEntity="AccountEligibleScholarshipsCount", inversedBy="account", fetch="LAZY", cascade={"all"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    protected $accountEligibleScholarshipsCount;

    /**
     * @var Installations
     *
     * @ORM\OneToOne(targetEntity="Installations", mappedBy="account")
     */
    protected $installation;

    /**
     * @var SocialAccount
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\SocialAccount",
     *     mappedBy="account",
     *     fetch="EAGER",
     *     cascade={"all"},
     *     orphanRemoval=true
     * )
     */
    protected $socialAccount;

    /**
     * @var ArrayCollection|Submission[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Marketing\Submission", mappedBy="account", fetch="LAZY")
     */
    private $submissions;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_id", type="string", nullable=true)
     */
    private $stripeId;


    /**
     * @var integer
     *
     * @ORM\Column(name="is_read_inbox", type="integer", nullable=false)
     */
    private $isReadInbox = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="device_token", type="string", nullable=true)
     */
    private $deviceToken;

    /**
     * @var integer
     *
     * @ORM\Column(name="app_installed", type="integer", nullable=false)
     */
    private $appInstalled = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sell_information", type="integer", nullable=false)
     */
    private $sellInformation = 0;

    /**
     * @var FeatureSet
     *
     * @ORM\OneToOne(targetEntity="FeatureSet", fetch="EAGER")
     * @ORM\JoinColumn(name="fset", referencedColumnName="id")
     */
    private $fset;

    /**
     * @return array
     */
    public function tags() : array
    {
        $profile = $this->getProfile();

        /** @var ScholarshipRepository $repository */
        $repository = \EntityManager::getRepository(Scholarship::class);
        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app(EligibilityCacheService::class);

		return [
            'email'              => $this->getInternalEmail(),
            'private_email'      => $this->getEmail(),
            'password'           => $this->getPasswordExternal(),
            'username'           => $this->getUsername(),
            'first_name'         => ucwords(strtolower($profile->getFirstName())),
            'last_name'          => ucwords(strtolower($profile->getLastName())),
            'full_name'          => $profile->getFullName(),
            'phone'              => $profile->getPhone(false),
            'phone_mask'         => $profile->getPhoneMask(),
            'gender'             => ucfirst($profile->getGender()),
            'citizenship'        => $profile->getCitizenship(),
            'ethnicity'          => $profile->getEthnicity(),
            'country'            => $profile->getCountry(),
            'state'              => $profile->getState(),
            'state_name'         => $profile->getStateName(),
            'state_abbreviation' => $profile->getState() ? $profile->getState()->getAbbreviation() : null,
            'city'               => $profile->getCity(),
            'address'            => $profile->getFullAddress(),
            'zip'                => $profile->getZip(),
            'school_level'       => $profile->getSchoolLevel(),
            'degree'             => $profile->getDegree(),
            'degree_type'        => $profile->getDegreeType(),
            'enrollment_year'    => $profile->getEnrollmentYear(),
            'enrollment_month'   => $profile->getEnrollmentMonth(),
            'gpa'                => $profile->getGpa(),
            'career_goal'        => $profile->getCareerGoal(),
            'graduation_year'    => $profile->getGraduationYear(),
            'graduation_month'   => $profile->getGraduationMonth(),
            'hs_graduation_year' => $profile->getHighschoolGraduationYear(),
            'hs_graduation_month'=> $profile->getHighschoolGraduationMonth(),
            'study_online'       => $profile->getStudyOnline(),
            'highschool'         => $profile->getHighschool(),
            'highschool_address' => $profile->getHighschoolAddress1() .' '. $profile->getHighschoolAddress2(),
            'university'         => $profile->getUniversity() ?: 'I have not yet selected a university',
            'university_address' => $profile->getUniversityAddress1() .' '. $profile->getUniversityAddress2(),
            'date_of_birth'      => $profile->getDateOfBirth() ?                $profile->getDateOfBirth()->format(DateHelper::DEFAULT_DATE_FORMAT) : null,
            'age'                => $profile->getDateOfBirth() ?                Carbon::instance($profile->getDateOfBirth())->age : null,
            'eligible_scholarships_count' => $elbCacheService->getAccountEligibleCount($this->getAccountId()),
            'eligible_scholarships_amount' => number_format(                $elbCacheService->getAccountEligibleAmount($this->getAccountId())
            ),
		];
    }

    /**
     * @param string $text
     *
     * @return mixed
     */
    public function mapTags(string $text = null)
    {
        return map_tags($text ?? '', $this->tags());
    }

    /**
     * Account constructor.
     *
     * @param string  $email
     * @param string  $username
     * @param string  $password
     * @param         $domain
     * @param int     $accountStatus
     * @param int     $accountType
     */
    public function __construct(
        string   $email,
        string   $username,
        string   $password,
                 $domain = Domain::SCHOLARSHIPOWL,
                 $accountStatus = AccountStatus::ACTIVE,
                 $accountType = AccountType::USER
    ) {
        $this->abTest = new ArrayCollection();
        $this->referredAccount = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->submissions = new ArrayCollection();

        $this->setEmail($email);
        $this->setDomain($domain);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setAccountStatus($accountStatus);
        $this->setAccountType($accountType);
        $this->generateExternalPassword();

        $this->setIsReadInbox(0);
    }

    /**
     * @return string
     */
    public function cacheTag() : string
    {
        return sprintf('account_%s', $this->getAccountId());
    }

    /**
     * Flush account caches
     */
    public function flushCacheTag()
    {
        \Cache::tags([$this->cacheTag()])->flush();
    }

    /**
     * Get accountId
     *
     * @return integer
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param int|AccountStatus $accountStatus
     *
     * @return $this
     */
    public function setAccountStatus($accountStatus)
    {
        $this->accountStatus = AccountStatus::convert($accountStatus);

        return $this;
    }

    /**
     * @return AccountStatus
     */
    public function getAccountStatus()
    {
        return $this->accountStatus;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Account
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
     * Set username
     *
     * @param string $username
     *
     * @return Account
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Account
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     *
     * @return $this
     */
    public function setPasswordExternal($password)
    {
        $this->passwordExternal = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordExternal()
    {
        return $this->passwordExternal;
    }

    /**
     * Set rememberToken
     *
     * @param string $rememberToken
     *
     * @return Account
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * Get rememberToken
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * Set referralCode
     *
     * @param string $referralCode
     *
     * @return Account
     */
    public function setReferralCode($referralCode)
    {
        $this->referralCode = $referralCode;

        return $this;
    }

    /**
     * Get referralCode
     *
     * @return string
     */
    public function getReferralCode()
    {
        return $this->referralCode;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Account
     */
    public function setCreatedDate($createdDate)
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
     * @return Account
     */
    public function setLastUpdatedDate($lastUpdatedDate)
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
     * Set accountType
     *
     * @param int|AccountType $accountType
     *
     * @return Account
     */
    public function setAccountType($accountType)
    {
        $this->accountType = AccountType::convert($accountType);

        return $this;
    }

    /**
     * Get accountType
     *
     * @return AccountType
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Add abTest
     *
     * @param AbTest $abTest
     *
     * @return Account
     */
    public function addAbTest(AbTest $abTest)
    {
        $this->abTest[] = $abTest;

        return $this;
    }

    /**
     * Remove abTest
     *
     * @param AbTest $abTest
     */
    public function removeAbTest(AbTest $abTest)
    {
        $this->abTest->removeElement($abTest);
    }

    /**
     * Get abTest
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbTest()
    {
        return $this->abTest;
    }

    /**
     * Add referredAccount
     *
     * @param Account $referredAccount
     *
     * @return Account
     */
    public function addReferredAccount(Account $referredAccount)
    {
        if (!$this->referredAccount->contains($referredAccount)) {
            $this->referredAccount->add($referredAccount);
        }

        return $this;
    }

    /**
     * Remove referredAccount
     *
     * @param Account $referredAccount
     */
    public function removeReferredAccount(Account $referredAccount)
    {
        $this->referredAccount->removeElement($referredAccount);
    }

    /**
     * Get referredAccount
     *
     * @return \Doctrine\Common\Collections\Collection|Account[]
     */
    public function getReferredAccount()
    {
        return $this->referredAccount;
    }

    /**
     * @inheritdoc
     */
    public function getEmailForPasswordReset()
    {
        return $this->getEmail();
    }

    /**
     * @inheritdoc
     */
    public function getAuthIdentifierName()
    {
        return 'accountId';
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getAuthIdentifier()
    {
        return $this->getAccountId();
    }

    /**
     * @inheritdoc
     */
    public function getAuthPassword()
    {
        return $this->getPassword();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }

    /**
     * @param Profile $profile
     *
     * @return Profile
     */
    public function setProfile(Profile $profile)
    {
        $this->profile = $profile->setAccount($this);

        return $this;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return string
     */
    public function getInternalEmail()
    {
        return sprintf('%s@%s', $this->getUsername(), \App\Services\Mailbox\Email::MAILBOX_DOMAIN);
    }

    /**
     * Set zendeskUserId
     *
     * @param integer $zendeskUserId
     *
     * @return Account
     */
    public function setZendeskUserId($zendeskUserId)
    {
        $this->zendeskUserId = $zendeskUserId;

        return $this;
    }

    /**
     * Get zendeskUserId
     *
     * @return integer
     */
    public function getZendeskUserId()
    {
        return $this->zendeskUserId;
    }

    /**
     * @param \Datetime $lastActionAt
     *
     * @return $this
     */
    public function setLastActionAt(\DateTime $lastActionAt)
    {
        $this->lastActionAt = $lastActionAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastActionAt()
    {
        return $this->lastActionAt;
    }

    /**
     * Add subscription
     *
     * @param Subscription $subscription
     *
     * @return Account
     */
    public function addSubscription(Subscription $subscription)
    {
        $this->subscriptions->add($subscription->setAccount($this));

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param Subscription $subscription
     */
    public function removeSubscription(Subscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
    }

    /**
     * Get subscriptions
     *
     * @return ArrayCollection|Subscription[]
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @return bool
     */
    public function isMember()
    {
        /** @var ArrayCollection $activeCreditsSubscriptions */
        $activeCreditsSubscriptions = $this->getSubscriptions()
            ->filter(function(Subscription $subscription) {
                return ($subscription->isActive() || $subscription->getActiveUntil() >= (new \DateTime())) &&
                     ($subscription->isFreemium() || $subscription->hasCredits());
            });

        return !$activeCreditsSubscriptions->isEmpty();
    }

    /**
     * @return bool
     */
    public function isFreemium()
    {
        /** @var Subscription $subscription */
        if ($subscription = $this->getActiveSubscriptions()->first()) {
            return $subscription->isFreemium();
        }

        return false;
    }

    /**
     * @return ArrayCollection|Subscription[]
     */
    public function getActiveSubscriptions()
    {
        return $this->getSubscriptions()
            ->filter(function(Subscription $subscription) {
                return ($subscription->isActive() || $subscription->getActiveUntil() >= (new \DateTime()));
            });
    }

    /**
     * @return int
     */
    public function getCredits()
    {
        $credits = 0;

        $activeSubscriptions = $this->getSubscriptions()
            ->filter(function(Subscription $subscription) {
                return $subscription->isActive() && !$subscription->getIsScholarshipsUnlimited();
            });

        /** @var Subscription $subscription */
        foreach ($activeSubscriptions as $subscription) {
            $credits += $subscription->getCredit();
        }

        return $credits;
    }

    /**
     * @return int|null
     */
    public function getFreemiumCredits()
    {
        /** @var bool|Subscription $freemiumSubscription */
        $freemiumSubscription = $this->getSubscriptions()
            ->filter(function(Subscription $subscription) {
                return $subscription->isActive() && $subscription->isFreemium();
            })->first();

        return $freemiumSubscription ? $freemiumSubscription->getFreemiumCredits() : null;
    }

    /*
     * @return AccountOnBoardingCall
     */
    public function getAccountOnBoardingCall()
    {
        return $this->accountOnBoardingCall;
    }

    /**
     * @param AccountOnBoardingCall $accountOnBoardingCall
     *
     * @return $this
     */
    public function setAccountOnBoardingCall($accountOnBoardingCall)
    {
        $this->accountOnBoardingCall = $accountOnBoardingCall->setAccount($this);

        return $this;
    }

    /*
     * @return Application[]|ArrayCollection
     */
    public function getApplications() {
        return $this->applications;
    }

    /**
     * @param Application[]|ArrayCollection $applications
     */
    public function setApplications($applications) {
        $this->applications = $applications;
    }

    /**
     * @return ApplicationText[]|ArrayCollection
     */
    public function getApplicationText() {
        return $this->applicationText;
    }

    /**
     * @param ApplicationText[]|ArrayCollection $applicationText
     */
    public function setApplicationText($applicationText) {
        $this->applicationText = $applicationText;
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getAccountId();
    }

    /**
     * @return Domain
     */
    public function getDomain(): Domain
    {
        return $this->domain;
    }

    /**
     * @param $domain
     */
    public function setDomain($domain)
    {
        $this->domain = Domain::convert($domain);
    }

    /**
     * @return AccountEligibleScholarshipsCount
     */
    public function getAccountEligibleScholarshipsCount()
    {
        return $this->accountEligibleScholarshipsCount;
    }

    /**
     * @param AccountEligibleScholarshipsCount $accountEligibleScholarshipsCount
     */
    public function setAccountEligibleScholarshipsCount($accountEligibleScholarshipsCount)
    {
        $this->accountEligibleScholarshipsCount = $accountEligibleScholarshipsCount;
    }

    /**
     * @return ArrayCollection|MarketingSystemAccountData[]
     */
    public function getMarketingData()
    {
        return $this->marketingData;
    }

    /**
     * @param MarketingSystemAccountData $marketingData
     *
     * @return $this
     */
    public function addMarketingData(MarketingSystemAccountData $marketingData)
    {
        $this->marketingData->add($marketingData->setAccount($this));

        return $this;
    }

    /**
     * @return SuperCollegeScholarshipMatch[]|ArrayCollection
     */
    public function getSuperCollegeScholarshipMatches()
    {
        return $this->superCollegeScholarshipMatches;
    }

    /**
     * @param SuperCollegeScholarshipMatch[]|ArrayCollection $superCollegeScholarshipMatches
     */
    public function setSuperCollegeScholarshipMatches($superCollegeScholarshipMatches)
    {
        $this->superCollegeScholarshipMatches = $superCollegeScholarshipMatches;
    }

    /**
     * @return Account
     */
    private function generateExternalPassword()
    {
        return $this->setPasswordExternal(md5(date(DATE_RFC2822) . md5($this->getUsername())));
    }

    /**
     * @return Installations
     */
    public function getInstallation(): Installations
    {
        return $this->installation;
    }

    /**
     * @param Installations $installation
     */
    public function setInstallation(Installations $installation)
    {
        $this->installation = $installation;
    }

    /**
     * @return SocialAccount|null
     */
    public function getSocialAccount()
    {
        return $this->socialAccount;
    }

    /**
     * @param SocialAccount $socialAccount
     *
     * @return $this
     */
    public function setSocialAccount(SocialAccount $socialAccount)
    {
        $this->socialAccount = $socialAccount->setAccount($this);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubmissions()
    {
        return $this->submissions;
    }

    /**
     * @param mixed $submissions
     */
    public function setSubmissions($submissions)
    {
        $this->submissions = $submissions;
    }

    /**
     * @return bool
     */
    public function isUSA()
    {
        return $this->getProfile()->getCountry() ? $this->getProfile()->getCountry()->is(Country::USA) : true;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->getProfile()->getCountry();
    }

    /**
     * @return string
     */
    public function getStripeId()
    {
        return $this->stripeId;
    }

    /**
     * @param string $stripeId
     *
     * @return Account
     */
    public function setStripeId($stripeId)
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    /**
     * @return int
     */
    public function getIsReadInbox()
    {
        return $this->isReadInbox;
    }

    /**
     * @param int $isReadInbox
     *
     * @return Account
     */
    public function setIsReadInbox($isReadInbox)
    {
        $this->isReadInbox = $isReadInbox;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * @param string $deviceToken
     */
    public function setDeviceToken(string $deviceToken)
    {
        $this->deviceToken = $deviceToken;
    }


    /**
     * @return int
     */
    public function getAppInstalled()
    {
        return $this->appInstalled;
    }

    /**
     * @param int $appInstalled
     */
    public function setAppInstalled(int $appInstalled)
    {
        $this->appInstalled = $appInstalled;
    }

    /**
     * @return bool
     */
    public function isSellInformation()
    {
        return $this->sellInformation;
    }

    /**
     * @param bool $sellInformation
     */
    public function setSellInformation(bool $sellInformation)
    {
        $this->sellInformation = $sellInformation;
    }

    /**
     * @param string $k
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $k)
    {
        $getter = 'get'.ucfirst($k);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \Exception("Can not get property [ $k ]");
    }

    /**
     * @return mixed|string
     */
    public function membershipStatus ()
    {
        /**
         * @var Subscription $currentSubscription
         */
        $currentSubscription = $this->getActiveSubscriptions()->first();

        $status = [];

        if($currentSubscription) {
            $subscriptionStatuses = [
                self::MEMBERSHIP_FREE_TRIAL => $currentSubscription->isFreeTrial(),
                self::MEMBERSHIP_FREEMIUM => $currentSubscription->isFreemium(),
                self::MEMBERSHIP_PAID => $currentSubscription->isPaid() && !$currentSubscription->isFreeTrial(),
            ];

            $status = array_keys(array_filter($subscriptionStatuses, function ($s) {
                return $s === true;
            }, ARRAY_FILTER_USE_BOTH));
        }

        return empty($status) ? self::MEMBERSHIP_FREE : array_shift($status);
    }

    /**
     * @return bool
     */
    public function getIsPaid()
    {
        /**
         * @var Subscription $currSubscription
         */
        $currSubscription = $this->getActiveSubscriptions()->first();
        $isPaid = false;
        if($currSubscription != null) {
            $isPaid = $currSubscription->isPaid();
        }

        return $isPaid;
    }

    /**
     * @return FeatureSet|null
     */
    public function getFset()
    {
        return $this->fset;
    }

    /**
     * @param  $fset
     */
    public function setFset($fset)
    {
        $this->fset = $fset;
    }

}
