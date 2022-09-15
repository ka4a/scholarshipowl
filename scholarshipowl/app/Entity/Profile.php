<?php namespace App\Entity;

use App\Traits\PhoneFormatter;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Annotations\Restricted;
use App\Entity\Traits\Hydratable;

/**
 * Class Profile
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="profile")
 */
class Profile extends AbstractEntity
{
    use Hydratable;
    use PhoneFormatter;

    const GENDER_FEMALE = "female";
    const GENDER_MALE = "male";
    const GENDER_OTHER = 'other';

    const GPA_NOT_AVAILABLE = "N/A";

    const TOTAL_PROFILE_FIELDS = 27;
    const PROFILE_TYPE_STUDENT = 'student';
    const PROFILE_TYPE_PARENT = 'parent';

    const STUDY_ONLINE_YES = "yes";
    const STUDY_ONLINE_NO = "no";
    const STUDY_ONLINE_MAYBE = "maybe";

    const RECURRENT_APPLY_DISABLED = 0;
    const RECURRENT_APPLY_ASAP = 1;
    const RECURRENT_APPLY_ON_DEADLINE = 2;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=127, nullable=false)
     * @Restricted()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=127, nullable=false)
     * @Restricted()
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=127, nullable=true)
     * @Restricted()
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    private $gender;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_subscribed", type="boolean", nullable=true)
     */
    private $isSubscribed;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=1023, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @Restricted()
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=511, nullable=true)
     * @Restricted()
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(name="address2", type="string", length=511, nullable=true)
     * @Restricted()
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=31, nullable=true)
     * @Restricted()
     */
    private $zip;

    /**
     * @var integer
     *
     * @ORM\Column(name="enrollment_year", type="smallint", nullable=true)
     */
    private $enrollmentYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="enrollment_month", type="smallint", nullable=true)
     */
    private $enrollmentMonth;

    /**
     * @var string
     *
     * @ORM\Column(name="gpa", type="string", length=3, nullable=true)
     */
    private $gpa;

    /**
     * @var integer
     *
     * @ORM\Column(name="graduation_year", type="smallint", nullable=true)
     */
    private $graduationYear;

    /**
     * @var boolean
     *
     * @ORM\Column(name="graduation_month", type="smallint", nullable=true)
     */
    private $graduationMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="highschool_graduation_year", type="smallint", nullable=true)
     */
    private $highschoolGraduationYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="highschool_graduation_month", type="smallint", nullable=true)
     */
    private $highschoolGraduationMonth;

    /**
     * @var string
     *
     * @ORM\Column(name="study_online", type="string", nullable=true)
     */
    private $studyOnline;

    /**
     * @var string
     *
     * @ORM\Column(name="highschool", type="string", length=511, nullable=true)
     */
    private $highschool;

    /**
     * @var string
     *
     * @ORM\Column(name="highschool_address1", type="string", length=255, nullable=true)
     */
    private $highschoolAddress1;

    /**
     * @var string
     *
     * @ORM\Column(name="highschool_address2", type="string", length=255, nullable=true)
     */
    private $highschoolAddress2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enrolled", type="boolean", nullable=true)
     */
    private $enrolled;

    /**
     * @var string
     *
     * @ORM\Column(name="university", type="string", length=511, nullable=true)
     */
    private $university;

    /**
     * @var string
     *
     * @ORM\Column(name="university_address1", type="string", length=255, nullable=true)
     */
    private $universityAddress1;

    /**
     * @var string
     *
     * @ORM\Column(name="university_address2", type="string", length=255, nullable=true)
     */
    private $universityAddress2;

    /**
     * @var string
     *
     * @ORM\Column(name="university1", type="string", length=511, nullable=true)
     */
    private $university1;

    /**
     * @var string
     *
     * @ORM\Column(name="university2", type="string", length=511, nullable=true)
     */
    private $university2;

    /**
     * @var string
     *
     * @ORM\Column(name="university3", type="string", length=511, nullable=true)
     */
    private $university3;

    /**
     * @var string
     *
     * @ORM\Column(name="university4", type="string", length=511, nullable=true)
     */
    private $university4;

    /**
     * @var string
     *
     * @ORM\Column(name="distribution_channel", type="string", nullable=false)
     */
    private $distributionChannel = 'web_app';

    /**
     * @var string
     *
     * @ORM\Column(name="signup_method", type="string", nullable=false)
     */
    private $signupMethod = 'manual';

    /**
     * @var string
     *
     * @ORM\Column(name="profile_type", type="string", length=10)
     */
    private $profileType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pro", type="boolean", nullable=false)
     */
    private $pro = '0';

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account", mappedBy="profile", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    private $account;

    /**
     * @var CareerGoal
     *
     * @ORM\ManyToOne(targetEntity="CareerGoal", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="career_goal_id", referencedColumnName="career_goal_id")
     * })
     */
    private $careerGoal;

    /**
     * @var Citizenship
     *
     * @ORM\ManyToOne(targetEntity="Citizenship", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="citizenship_id", referencedColumnName="citizenship_id")
     * })
     */
    private $citizenship;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;

    /**
     * @var Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="degree_id")
     * })
     */
    private $degree;

    /**
     * @var DegreeType
     *
     * @ORM\ManyToOne(targetEntity="DegreeType", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_type_id", referencedColumnName="degree_type_id")
     * })
     */
    private $degreeType;

    /**
     * @var Ethnicity
     *
     * @ORM\ManyToOne(targetEntity="Ethnicity", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ethnicity_id", referencedColumnName="ethnicity_id")
     * })
     */
    private $ethnicity;

    /**
     * @var MilitaryAffiliation
     *
     * @ORM\ManyToOne(targetEntity="MilitaryAffiliation", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="military_affiliation_id", referencedColumnName="military_affiliation_id")
     * })
     */
    private $militaryAffiliation;

    /**
     * @var SchoolLevel
     *
     * @ORM\ManyToOne(targetEntity="SchoolLevel", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="school_level_id", referencedColumnName="school_level_id")
     * })
     */
    private $schoolLevel;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_id", referencedColumnName="state_id")
     * })
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(name="state_name", type="string", length=127, nullable=true)
     * @Restricted()
     */
    private $stateName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="agree_call", type="boolean", nullable=true)
     */
    private $agreeCall;

    /**
     * @var integer
     *
     * @ORM\Column(name="recurring_application", type="smallint", nullable=true)
     */
    private $recurringApplication = self::RECURRENT_APPLY_DISABLED;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="study_country1", referencedColumnName="country_id")
     * })
     */
    private $studyCountry1;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="study_country2", referencedColumnName="country_id")
     * })
     */
    private $studyCountry2;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="study_country3", referencedColumnName="country_id")
     * })
     */
    private $studyCountry3;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="study_country4", referencedColumnName="country_id")
     * })
     */
    private $studyCountry4;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="study_country5", referencedColumnName="country_id")
     * })
     */
    private $studyCountry5;

    /**
     * @return array
     */
    public static function genders()
    {
        return [
            static::GENDER_FEMALE   => 'Female',
            static::GENDER_MALE     => 'Male',
            static::GENDER_OTHER    => 'Other',
        ];
    }

    /**
     * @return array
     */
    public static function profileTypes()
    {
        return [
            static::PROFILE_TYPE_STUDENT    => 'Student',
            static::PROFILE_TYPE_PARENT     => 'Parent',
        ];
    }

    /**
     * @return array
     */
    public static function gpas()
    {
        $result = [Profile::GPA_NOT_AVAILABLE => Profile::GPA_NOT_AVAILABLE];

        for($i = 2.0; $i <= 4.1; $i += 0.1) {
            $key = sprintf("%.01f", $i);
            $result[$key] = $key;
        }

        return $result;
    }

    /**
     * @return array
     */
	public static function studyOnlineOptions()
    {
		return [
			self::STUDY_ONLINE_YES      => "Yes",
			self::STUDY_ONLINE_NO       => "No",
			self::STUDY_ONLINE_MAYBE    => "Maybe"
		];
	}

    /**
     * @return array
     */
	public static function recurringApplicationOptions()
    {
		return [
			self::RECURRENT_APPLY_DISABLED      => "Do not apply automatically",
			self::RECURRENT_APPLY_ASAP          => "Apply as soon as new scholarship is available",
			self::RECURRENT_APPLY_ON_DEADLINE   => "Apply on deadline",
		];
	}

    /**
     * Profile constructor.
     *
     * @param string        $firstName
     * @param string        $lastName
     * @param int|Country   $country
     * @param string        $phone
     */
    public function __construct($firstName, $lastName, $country, $phone = null)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setCountry($country);
        $this->setPhone($phone);
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Profile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Profile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Profile
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone($formatUSAPhone = true)
    {
        $phone = $this->phone;

        if ($phone) {
            $phone = $this->unifyPhoneFormat($phone);
            if ($formatUSAPhone) {
                $phone = $this->toPhoneFormat($phone, $this->getCountry()->getId() === Country::USA);
            }
        }

        return $phone;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Profile
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Profile
     */
    public function setGender($gender)
    {
        $this->gender = (is_string($gender) && in_array(strtolower($gender), array_keys(static::genders()))) ?
            strtolower($gender) : null;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set isSubscribed
     *
     * @param boolean $isSubscribed
     *
     * @return Profile
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->isSubscribed = (bool) $isSubscribed;

        return $this;
    }

    /**
     * Get isSubscribed
     *
     * @return boolean
     */
    public function getIsSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Profile
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Profile
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Profile
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Profile
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set enrollmentYear
     *
     * @param integer $enrollmentYear
     *
     * @return Profile
     */
    public function setEnrollmentYear($enrollmentYear)
    {
        $this->enrollmentYear = $enrollmentYear;

        return $this;
    }

    /**
     * Get enrollmentYear
     *
     * @return integer
     */
    public function getEnrollmentYear()
    {
        return $this->enrollmentYear;
    }

    /**
     * Set enrollmentMonth
     *
     * @param int $enrollmentMonth
     *
     * @return Profile
     */
    public function setEnrollmentMonth($enrollmentMonth)
    {
        $this->enrollmentMonth = $enrollmentMonth;

        return $this;
    }

    /**
     * Get enrollmentMonth
     *
     * @return int
     */
    public function getEnrollmentMonth()
    {
        return $this->enrollmentMonth;
    }

    /**
     * @return string|null
     */
    public function getEnrollmentDate()
    {
        return $this->getEnrollmentMonth() && $this->getEnrollmentYear() ?
            sprintf('%d/01/%d', $this->getEnrollmentMonth(), $this->getEnrollmentYear()) : null;
    }

    /**
     * Set gpa
     *
     * @param string $gpa
     *
     * @return Profile
     */
    public function setGpa($gpa)
    {
        $this->gpa = $gpa;

        return $this;
    }

    /**
     * Get gpa
     *
     * @return string
     */
    public function getGpa()
    {
        return $this->gpa;
    }

    /**
     * Set graduationYear
     *
     * @param integer $graduationYear
     *
     * @return Profile
     */
    public function setGraduationYear($graduationYear)
    {
        $this->graduationYear = $graduationYear;

        return $this;
    }

    /**
     * Get graduationYear
     *
     * @return integer
     */
    public function getGraduationYear()
    {
        return $this->graduationYear;
    }

    /**
     * Set graduationMonth
     *
     * @param int $graduationMonth
     *
     * @return Profile
     */
    public function setGraduationMonth($graduationMonth)
    {
        $this->graduationMonth = $graduationMonth;

        return $this;
    }

    /**
     * Get graduationMonth
     *
     * @return int
     */
    public function getGraduationMonth()
    {
        return $this->graduationMonth;
    }

    /**
     * @return string|null
     */
    public function getCollegeGraduationDate()
    {
        return $this->getGraduationMonth() && $this->getGraduationYear() ?
            sprintf('%d/01/%d', $this->getGraduationMonth(), $this->getGraduationYear()) : null;
    }

    /**
     * Set highschoolGraduationYear
     *
     * @param int $value
     *
     * @return Profile
     */
    public function setHighschoolGraduationYear($value)
    {
        $this->highschoolGraduationYear = $value;

        return $this;
    }

    /**
     * Get highschoolGraduationYear
     *
     * @return int
     */
    public function getHighschoolGraduationYear()
    {
        return $this->highschoolGraduationYear;
    }

    /**
     * Set highschoolGraduationMonth
     *
     * @param int $value
     *
     * @return Profile
     */
    public function setHighschoolGraduationMonth($value)
    {
        $this->highschoolGraduationMonth = $value;

        return $this;
    }

    /**
     * Get highschoolGraduationMonth
     *
     * @return int
     */
    public function getHighschoolGraduationMonth()
    {
        return $this->highschoolGraduationMonth;
    }

    /**
     * @return string|null
     */
    public function getHighschoolGraduationDate()
    {
        return $this->getHighschoolGraduationMonth() && $this->getHighschoolGraduationYear() ?
            sprintf('%d/01/%d', $this->getHighschoolGraduationMonth(), $this->getHighschoolGraduationYear()) : null;
    }

    /**
     * Set studyOnline
     *
     * @param string $studyOnline
     *
     * @return Profile
     */
    public function setStudyOnline($studyOnline)
    {
        $this->studyOnline = $studyOnline;

        return $this;
    }

    /**
     * Get studyOnline
     *
     * @return string
     */
    public function getStudyOnline()
    {
        return $this->studyOnline;
    }

    /**
     * Set highschool
     *
     * @param string $highschool
     *
     * @return Profile
     */
    public function setHighschool($highschool)
    {
        $this->highschool = $highschool;

        return $this;
    }

    /**
     * Get highschool
     *
     * @return string
     */
    public function getHighschool()
    {
        return $this->highschool;
    }

    /**
     * @return string
     */
    public function getHighschoolAddress1()
    {
        return $this->highschoolAddress1;
    }

    /**
     * @param $highschoolAddress1
     *
     * @return $this
     */
    public function setHighschoolAddress1($highschoolAddress1)
    {
        $this->highschoolAddress1 = $highschoolAddress1;

        return $this;
    }

    /**
     * @return string
     */
    public function getHighschoolAddress2()
    {
        return $this->highschoolAddress2;
    }

    /**
     * @param $highschoolAddress2
     *
     * @return $this
     */
    public function setHighschoolAddress2($highschoolAddress2)
    {
        $this->highschoolAddress2 = $highschoolAddress2;

        return $this;
    }

    /**
     * Set enrolled
     *
     * @param boolean $enrolled
     *
     * @return Profile
     */
    public function setEnrolled($enrolled)
    {
        $this->enrolled = (bool) $enrolled;

        return $this;
    }

    /**
     * Get enrolled
     *
     * @return boolean
     */
    public function getEnrolled()
    {
        return $this->enrolled;
    }

    /**
     * Set university
     *
     * @param string $university
     *
     * @return Profile
     */
    public function setUniversity($university)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * @param array $universities
     *
     * @return $this
     */
    public function setUniversities(array $universities)
    {
        $this->setUniversity($universities[0] ?? null);
        $this->setUniversity1($universities[1] ?? null);
        $this->setUniversity2($universities[2] ?? null);
        $this->setUniversity3($universities[3] ?? null);
        $this->setUniversity4($universities[4] ?? null);

        return $this;
    }

    /**
     * @return array
     */
    public function getUniversities()
    {
        $universities = [];

        if ($this->getUniversity() !== null) {
            $universities[] = $this->getUniversity();
        }
        if ($this->getUniversity1() !== null) {
            $universities[] = $this->getUniversity1();
        }
        if ($this->getUniversity2() !== null) {
            $universities[] = $this->getUniversity2();
        }
        if ($this->getUniversity3() !== null) {
            $universities[] = $this->getUniversity3();
        }
        if ($this->getUniversity4() !== null) {
            $universities[] = $this->getUniversity4();
        }

        return $universities;
    }

    /**
     * Get university
     *
     * @return string
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * Set university1
     *
     * @param string $university1
     *
     * @return Profile
     */
    public function setUniversity1($university1)
    {
        $this->university1 = $university1;

        return $this;
    }

    /**
     * @return string
     */
    public function getUniversityAddress1()
    {
        return $this->universityAddress1;
    }

    /**
     * @param $universityAddress1
     *
     * @return $this
     */
    public function setUniversityAddress1($universityAddress1)
    {
        $this->universityAddress1 = $universityAddress1;

        return $this;
    }

    /**
     * @return string
     */
    public function getUniversityAddress2()
    {
        return $this->universityAddress2;
    }

    /**
     * @param $universityAddress2
     *
     * @return $this
     */
    public function setUniversityAddress2($universityAddress2)
    {
        $this->universityAddress2 = $universityAddress2;

        return $this;
    }

    /**
     * Get university1
     *
     * @return string
     */
    public function getUniversity1()
    {
        return $this->university1;
    }

    /**
     * Set university2
     *
     * @param string $university2
     *
     * @return Profile
     */
    public function setUniversity2($university2)
    {
        $this->university2 = $university2;

        return $this;
    }

    /**
     * Get university2
     *
     * @return string
     */
    public function getUniversity2()
    {
        return $this->university2;
    }

    /**
     * Set university3
     *
     * @param string $university3
     *
     * @return Profile
     */
    public function setUniversity3($university3)
    {
        $this->university3 = $university3;

        return $this;
    }

    /**
     * Get university3
     *
     * @return string
     */
    public function getUniversity3()
    {
        return $this->university3;
    }

    /**
     * Set university4
     *
     * @param string $university4
     *
     * @return Profile
     */
    public function setUniversity4($university4)
    {
        $this->university4 = $university4;

        return $this;
    }

    /**
     * Get university4
     *
     * @return string
     */
    public function getUniversity4()
    {
        return $this->university4;
    }

    /**
     * Set distributionChannel
     *
     * @param string $distributionChannel
     *
     * @return Profile
     */
    public function setDistributionChannel($distributionChannel)
    {
        $this->distributionChannel = $distributionChannel;

        return $this;
    }

    /**
     * Get distributionChannel
     *
     * @return string
     */
    public function getDistributionChannel()
    {
        return $this->distributionChannel;
    }

    /**
     * Set signupMethod
     *
     * @param string $signupMethod
     *
     * @return Profile
     */
    public function setSignupMethod($signupMethod)
    {
        $this->signupMethod = $signupMethod;

        return $this;
    }

    /**
     * Get signupMethod
     *
     * @return string
     */
    public function getSignupMethod()
    {
        return $this->signupMethod;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Profile
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set careerGoal
     *
     * @param CareerGoal|int $careerGoal
     *
     * @return Profile
     */
    public function setCareerGoal($careerGoal)
    {
        if (!$careerGoal) {
            return $this;
        }

        $this->careerGoal = CareerGoal::convert($careerGoal);

        return $this;
    }

    /**
     * Get careerGoal
     *
     * @return \App\Entity\CareerGoal
     */
    public function getCareerGoal()
    {
        return $this->careerGoal;
    }

    /**
     * Set citizenship
     *
     * @param Citizenship|int $citizenship
     *
     * @return Profile
     */
    public function setCitizenship($citizenship)
    {
        if (!$citizenship) {
            return $this;
        }

        $this->citizenship = $citizenship === null ? null : Citizenship::convert($citizenship);

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return \App\Entity\Citizenship
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Set country
     *
     * @param Country|int $country
     *
     * @return Profile
     */
    public function setCountry($country)
    {
        if (!$country) {
            return $this;
        }
        $this->country = Country::convert($country);

        return $this;
    }

    /**
     * Get country
     *
     * @return \App\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set degree
     *
     * @param Degree|int $degree
     *
     * @return Profile
     */
    public function setDegree($degree)
    {
        if (!$degree) {
            return $this;
        }

        $this->degree = Degree::convert($degree);

        return $this;
    }

    /**
     * Get degree
     *
     * @return \App\Entity\Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set degreeType
     *
     * @param DegreeType|int $degreeType
     *
     * @return Profile
     */
    public function setDegreeType($degreeType)
    {
        if (!$degreeType) {
            return $this;
        }

        $this->degreeType = DegreeType::convert($degreeType);

        return $this;
    }

    /**
     * Get degreeType
     *
     * @return \App\Entity\DegreeType
     */
    public function getDegreeType()
    {
        return $this->degreeType;
    }

    /**
     * Set ethnicity
     *
     * @param Ethnicity|int $ethnicity
     *
     * @return Profile
     */
    public function setEthnicity($ethnicity)
    {
        if (!$ethnicity) {
            return $this;
        }

        $this->ethnicity = Ethnicity::convert($ethnicity);

        return $this;
    }

    /**
     * Get ethnicity
     *
     * @return \App\Entity\Ethnicity
     */
    public function getEthnicity()
    {
        return $this->ethnicity;
    }

    /**
     * Set militaryAffiliation
     *
     * @param int|MilitaryAffiliation $militaryAffiliation
     *
     * @return Profile
     */
    public function setMilitaryAffiliation($militaryAffiliation)
    {
        if (!$militaryAffiliation && $militaryAffiliation != 0) {
            return $this;
        }

        $this->militaryAffiliation = MilitaryAffiliation::convert($militaryAffiliation);

        return $this;
    }

    /**
     * Get militaryAffiliation
     *
     * @return \App\Entity\MilitaryAffiliation
     */
    public function getMilitaryAffiliation()
    {
        return $this->militaryAffiliation;
    }

    /**
     * Set schoolLevel
     *
     * @param SchoolLevel|int $schoolLevel
     *
     * @return Profile
     */
    public function setSchoolLevel($schoolLevel)
    {
        if (!$schoolLevel) {
            return $this;
        }

        $this->schoolLevel = SchoolLevel::convert($schoolLevel);

        return $this;
    }

    /**
     * Get schoolLevel
     *
     * @return \App\Entity\SchoolLevel
     */
    public function getSchoolLevel()
    {
        return $this->schoolLevel;
    }

    /**
     * Set state
     *
     * @param State|int $state
     *
     * @return Profile
     */
    public function setState($state)
    {
        if (!$state) {
            return $this;
        }

        $this->state = State::convert($state);

        return $this;
    }

    /**
     * Get state
     *
     * @return \App\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set profileType
     *
     * @param string $profileType
     *
     * @return Profile
     */
    public function setProfileType($profileType)
    {
        $this->profileType = $profileType;

        return $this;
    }

    /**
     * Get profileType
     *
     * @return string
     */
    public function getProfileType()
    {
        return $this->profileType;
    }

	/**
	 * @return bool
	 */
	public function getPro()
	{
		return $this->pro;
	}

	/**
	 * @param $pro
	 */
	public function setPro($pro)
	{
		$this->pro = $pro;
	}

    /**
     * Method that calculates profiles completeness in percentage
     *
     * @return float
     */
    public function getCompleteness()
    {
        $count = 0;

        $fields = [
            "firstName", "lastName", "phone", "gender", "city", "address", "zip",
            "studyOnline", "enrollmentYear", "enrollmentMonth", "graduationYear", "graduationMonth",
            "highschoolGraduationMonth", "highschoolGraduationYear",
            "highschool", "university", "militaryAffiliation"
        ];

        foreach($fields as $field)
        {
            if(property_exists($this, $field))
            {
                $value = trim($this->$field);

                if(!empty($value))
                {
                    $count ++;
                }
            }
        }

        if($this->gpa != null)
        {
            $count++;
        }

        if($this->dateOfBirth instanceof \DateTime && $this->dateOfBirth != "0000-00-00 00:00:00")
        {
            $count++;
        }

        $data = [];
        $data["careerGoalId"] = $this->getCareerGoal() != null ? $this->getCareerGoal()->getId() : null;
        $data["citizenshipId"] = $this->getCitizenship() != null ? $this->getCitizenship()->getId() : null;
        $data["ethnicityId"] = $this->getEthnicity() != null ? $this->getEthnicity()->getId() : null;
        $data["countryId"] = $this->getCountry() != null ? $this->getCountry()->getId() : null;
        $data["stateId"] = $this->getState() != null ? $this->getState()->getId() : null ;
        $data["schoolLevelId"] = $this->getSchoolLevel() ? $this->getSchoolLevel()->getId() : null;
        $data["degreeId"] = $this->getDegree() != null ? $this->getDegree()->getId() : null;
        $data["degreeTypeId"] = $this->getDegreeType() != null ? $this->getDegreeType()->getId() : null;

        foreach($data as $key => $value)
        {
            $value = trim($value);

            if(!empty($value))
            {
                $count++;
            }
        }

        return floor((($count) * 100) / self::TOTAL_PROFILE_FIELDS);
    }


    /**
     * @return string
     */
    public function getPhoneAreaCode()
    {
        return $this->getPhone(false) ? substr($this->getPhone(false), 2, 3) : '';
    }

    /**
     * @return string
     */
    public function getPhonePrefix()
    {
        return $this->getPhone(false) ? substr($this->getPhone(false), 5, 3) : '';
    }

    /**
     * @return string
     */
    public function getPhoneLocal()
    {
        return $this->getPhone(false) ? substr($this->getPhone(false), 8, 4) : '';
    }

    /**
     * @return string
     */
    public function getPhoneMask()
    {
        return $this->getPhone(false) ?
            sprintf('(%s) %s - %s', $this->getPhoneAreaCode(), $this->getPhonePrefix(), $this->getPhoneLocal()) : '';
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return ucwords(strtolower($this->getFirstName())).' '.ucwords(strtolower($this->getLastName()));
    }

    /**
     * @return boolean
     */
    public function getAgreeCall()
    {
        return $this->agreeCall;
    }

    /**
     * @param boolean $agreeCall
     *
     * @return Profile
     */
    public function setAgreeCall($agreeCall)
    {
        $this->agreeCall = (bool) $agreeCall;

        return $this;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->dateOfBirth ? (int) Carbon::instance($this->dateOfBirth)->age : null;
    }

    /**
     * @return int
     */
    public function getDateOfBirthYear()
    {
        return $this->dateOfBirth ? (int) $this->dateOfBirth->format('Y') : null;
    }

    /**
     * @return int
     */
    public function getDateOfBirthMonth()
    {
        return $this->dateOfBirth ? (int) $this->dateOfBirth->format('m') : null;
    }

    /**
     * @return int
     */
    public function getDateOfBirthDay()
    {
        return $this->dateOfBirth ? (int) $this->dateOfBirth->format('d') : null;
    }

    /**
     * @return int
     */
    public function getRecurringApplication()
    {
        return $this->recurringApplication;
    }

    /**
     * @param int $recurringApplication
     */
    public function setRecurringApplication($recurringApplication)
    {
        $this->recurringApplication = $recurringApplication;
    }

    /**
     * @param int|Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry1($studyCountry)
    {
        $this->studyCountry1 = $studyCountry === null ? null : Country::convert($studyCountry);

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry1()
    {
        return $this->studyCountry1;
    }

    /**
     * @param int|Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry2($studyCountry)
    {
        $this->studyCountry2 = $studyCountry === null ? null : Country::convert($studyCountry);

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry2()
    {
        return $this->studyCountry2;
    }

    /**
     * @param int|Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry3($studyCountry)
    {
        $this->studyCountry3 = $studyCountry === null ? null : Country::convert($studyCountry);

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry3()
    {
        return $this->studyCountry3;
    }

    /**
     * @param int|Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry4($studyCountry)
    {
        $this->studyCountry4 = $studyCountry === null ? null : Country::convert($studyCountry);

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry4()
    {
        return $this->studyCountry4;
    }

    /**
     * @param int|Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry5($studyCountry)
    {
        $this->studyCountry5 = $studyCountry === null ? null : Country::convert($studyCountry);

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry5()
    {
        return $this->studyCountry5;
    }

    /**
     * @param array $studyCountry
     *
     * @return $this
     */
    public function setStudyCountries(array $studyCountry)
    {
        $this->setStudyCountry1($studyCountry[0] ?? null);
        $this->setStudyCountry2($studyCountry[1] ?? null);
        $this->setStudyCountry3($studyCountry[2] ?? null);
        $this->setStudyCountry4($studyCountry[3] ?? null);
        $this->setStudyCountry5($studyCountry[4] ?? null);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string|null $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        return $this->getAddress() . ($this->getAddress2() ? ' ' . $this->getAddress2() : '');
    }

    /**
     * @return string|null
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * @param string $stateName
     *
     * @return $this
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;
        return $this;
    }

    /**
     * @return array|Country[]
     */
    public function getStudyCountries()
    {
        $countries = [];

        if ($this->getStudyCountry1()) {
            $countries[] = $this->getStudyCountry1();
        }
        if ($this->getStudyCountry2()) {
            $countries[] = $this->getStudyCountry2();
        }
        if ($this->getStudyCountry3()) {
            $countries[] = $this->getStudyCountry3();
        }
        if ($this->getStudyCountry4()) {
            $countries[] = $this->getStudyCountry4();
        }
        if ($this->getStudyCountry5()) {
            $countries[] = $this->getStudyCountry5();
        }

        return $countries;
    }

    public static function getProfileTypes(){
        return array(
            self::PROFILE_TYPE_STUDENT => "Student",
            self::PROFILE_TYPE_PARENT => "Parent",
        );
    }

    /**
     * @param null $options
     * @return array|false|null
     */
    public static function getMonthsArray($options = null) {
        $result = array_combine(range(1, 12), range(1, 12));

        if(isset($options)) {
            $result = $options + $result;
        }

        return $result;
    }

    /**
     * @param null $options
     * @return array|false|null
     */
    public static function getDaysArray($options = null) {
        $result = array_combine(range(1, 31), range(1, 31));

        if(isset($options)) {
            $result = $options + $result;
        }

        return $result;
    }

    /**
     * @param null $options
     * @return array|false|null
     */
    public static function getYearsArray($options = null) {
        $result = array_combine(range(date("Y") - 16, 1900, -1), range(date("Y") - 16, 1900, -1));

        if(isset($options)) {
            $result = $options + $result;
        }

        return $result;
    }

    /**
     * @param null $options
     * @param int $distance
     * @return array|false|null
     */
    public static function getFutureYearsArray($options = null, $distance = 30) {
        $result = array_combine(range(date("Y") + $distance, 1950, -1), range(date("Y") + $distance, 1950, -1));

        if(isset($options)) {
            $result = $options + $result;
        }

        return $result;
    }
}
