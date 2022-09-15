<?php namespace App\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use ScholarshipOwl\Data\DateHelper;

/**
 * Form
 *
 * @ORM\Table(name="form", indexes={@ORM\Index(name="ix_form_scholarship_id", columns={"scholarship_id"})})
 * @ORM\Entity
 */
class Form
{
    const EMAIL = "email";
    const PASSWORD = 'password';
    const USERNAME = 'username';
	const EMAIL_CONFIRMATION = "email_confirmation";
	const FIRST_NAME = "first_name";
	const LAST_NAME = "last_name";
	const FULL_NAME = "full_name";
	const PHONE = "phone";
	const PHONE_AREA = "phone_area";
	const PHONE_PREFIX = "phone_prefix";
	const PHONE_LOCAL = "phone_local";
	const DATE_OF_BIRTH = "date_of_birth";
	const DATE_OF_BIRTH_DAY = "date_of_birth_day";
	const DATE_OF_BIRTH_MONTH = "date_of_birth_month";
	const DATE_OF_BIRTH_YEAR = "date_of_birth_year";
	const AGE = "age";
	const GENDER = "gender";
	const CITIZENSHIP = "citizenship";
	const CITIZENSHIP_NAME = "citizenship_name";
	const ETHNICITY = "ethnicity";
	const ETHNICITY_NAME = "ethnicity_name";
	const PICTURE = "picture";
	const COUNTRY = "country";
	const COUNTRY_ABBREVIATION = "country_abbreviation";
	const STATE = "state";
	const STATE_ABBREVIATION = "state_abbreviation";
	const CITY = "city";
	const ADDRESS = "address";
	const ZIP = "zip";
    const FULL_ADDRESS = 'full_address';
	const SCHOOL_LEVEL = "school_level";
	const SCHOOL_LEVEL_NAME = "school_level_name";
	const DEGREE = "degree";
	const DEGREE_NAME = "degree_name";
	const DEGREE_TYPE = "degree_type";
	const DEGREE_TYPE_NAME = "degree_type_name";
	const ENROLLMENT_YEAR = "enrollment_year";
	const ENROLLMENT_MONTH = "enrollment_month";
	const GRADUATION_YEAR = "graduation_year";
	const GRADUATION_MONTH = "graduation_month";
    const GRADUATION_DATE = "graduation_date";
	const GPA = "gpa";
	const GPA_RANGE = "gpa_range";
	const CAREER_GOAL = "career_goal";
	const CAREER_GOAL_NAME = "career_goal_name";
	const STUDY_ONLINE = "study_online";
	const HIGHSCHOOL = "highschool";
	const UNIVERSITY = "university";
    const HIGHSCHOOL_ADDRESS = "highschool_address";
    const UNIVERSITY_ADDRESS = "university_address";
	const ACCEPT_CONFIRMATION = "accept_confirmation";
	const HIDDEN_FIELD = "hidden_field";
	const STATIC_FIELD = "static_field";
	const SUBMIT_FIELD = "submit_field";

    // File requirements
    const REQUIREMENT_UPLOAD_TEXT = 'upload_text_field';
    const REQUIREMENT_UPLOAD_FILE = 'upload_file_field';
    const REQUIREMENT_UPLOAD_IMAGE = 'upload_image_field';

    // Text requirements
	const TEXT = "requirement_text_field";
	const INPUT = "requirement_input_field";

    /**
     * @var integer
     *
     * @ORM\Column(name="form_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $formId;

    /**
     * @var string
     *
     * @ORM\Column(name="form_field", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $formField;

    /**
     * @var string
     *
     * @ORM\Column(name="system_field", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $systemField;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", length=16777215, precision=0, scale=0, nullable=true, unique=false)
     */
    private $value;

    /**
     * @var array
     *
     * @ORM\Column(name="mapping", type="json", nullable=true, unique=false)
     */
    private $mapping;

    /**
     * @var \App\Entity\Scholarship
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Scholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id", nullable=false)
     * })
     */
    private $scholarship;

    /**
     * Form constructor.
     *
     * @param string $formField
     * @param string $systemField
     * @param null   $value
     * @param null   $mapping
     */
    public function __construct(string $formField, string $systemField, $value = null, $mapping = null)
    {
        $this->setFormField($formField);
        $this->setSystemField($systemField);
        $this->setMapping($mapping);
        $this->setValue($value);
    }

    /**
     * Get formId
     *
     * @return integer
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * Set formField
     *
     * @param string $formField
     *
     * @return Form
     */
    public function setFormField($formField)
    {
        $this->formField = $formField;

        return $this;
    }

    /**
     * Get formField
     *
     * @return string
     */
    public function getFormField()
    {
        return $this->formField;
    }

    /**
     * Set systemField
     *
     * @param string $systemField
     *
     * @return Form
     */
    public function setSystemField($systemField)
    {
        $this->systemField = $systemField;

        return $this;
    }

    /**
     * Get systemField
     *
     * @return string
     */
    public function getSystemField()
    {
        return $this->systemField;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Form
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set mapping
     *
     * @param array $mapping
     *
     * @return Form
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;

        return $this;
    }

    /**
     * Get mapping
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return Form
     */
    public function setScholarship(Scholarship $scholarship = null)
    {
        $this->scholarship = $scholarship;

        return $this;
    }

    /**
     * Get scholarship
     *
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @return array
     */
	public static function getSystemFields() {
		return array(
			self::EMAIL => "Email",
            self::PASSWORD => 'Password',
            self::USERNAME => 'Username',
			self::EMAIL_CONFIRMATION => "Email Confirmation",
			self::FIRST_NAME => "First Name",
			self::LAST_NAME => "Last Name",
			self::FULL_NAME => "Full Name",
			self::PHONE => "Phone",
			self::PHONE_AREA => "Phone Area Code",
			self::PHONE_PREFIX => "Phone Prefix",
			self::PHONE_LOCAL => "Phone Local",
			self::DATE_OF_BIRTH => "Date Of Birth",
			self::DATE_OF_BIRTH_DAY => "Day Of Birth",
			self::DATE_OF_BIRTH_MONTH => "Month Of Birth",
			self::DATE_OF_BIRTH_YEAR => "Year Of Birth",
			self::AGE => "Age",
			self::GENDER => "Gender",
			self::CITIZENSHIP => "Citizenship",
			self::CITIZENSHIP_NAME => "Citizenship Name",
			self::ETHNICITY => "Ethnicity",
			self::ETHNICITY_NAME => "Ethnicity Name",
			self::PICTURE => "Picture",
			self::COUNTRY => "Country",
			self::COUNTRY_ABBREVIATION => "Country Abbreviation",
			self::STATE => "State",
			self::STATE_ABBREVIATION => "State Abbreviation",
			self::CITY => "City",
			self::ADDRESS => "Address",
			self::ZIP => "Zip",
            self::FULL_ADDRESS => 'Full address',
			self::SCHOOL_LEVEL => "School Level",
			self::SCHOOL_LEVEL_NAME => "School Level Name",
			self::DEGREE => "Degree",
			self::DEGREE_NAME => "Degree Name",
			self::DEGREE_TYPE => "Degree Type",
			self::DEGREE_TYPE_NAME => "Degree Type Name",
			self::ENROLLMENT_YEAR => "Enrollment Year",
			self::ENROLLMENT_MONTH => "Enrollment Month",
			self::GRADUATION_YEAR => "Graduation Year",
			self::GRADUATION_MONTH => "Graduation Month",
            self::GRADUATION_DATE => "Graduation Date",
			self::GPA => "GPA",
			self::GPA_RANGE => "GPA Range",
			self::CAREER_GOAL => "Career Goal",
			self::CAREER_GOAL_NAME => "Career Goal Name",
			self::STUDY_ONLINE => "Study Online",
			self::HIGHSCHOOL => "High School",
            self::HIGHSCHOOL_ADDRESS => "High School Address",
			self::UNIVERSITY => "University",
            self::UNIVERSITY_ADDRESS => "University Address",
			self::ACCEPT_CONFIRMATION => "Accept Confirmation",
			self::HIDDEN_FIELD => "Hidden Field",
			self::STATIC_FIELD => "Static Field",
			self::SUBMIT_FIELD => "Submit Field",
            self::REQUIREMENT_UPLOAD_FILE => 'File Requirement',
            self::REQUIREMENT_UPLOAD_TEXT => 'File Requirement Text',
            self::REQUIREMENT_UPLOAD_IMAGE => 'File Requirement Image',
            self::INPUT => 'Requirement Input (YouTube video link, Facebook etc.)',
            self::TEXT => 'Requirement Text Only (Plain text)',
		);
	}

    /**
     * @param Form    $form
     * @param Account $account
     *
     * @return string
     */
    public static function mapField(Form $form, Account $account)
    {
        $profile = $account->getProfile();

        switch ($form->getSystemField()) {
            case self::EMAIL:
                return $account->getInternalEmail();
            case self::PASSWORD:
                return $account->getPasswordExternal();
            case self::USERNAME:
                return $account->getUsername();
            case self::EMAIL_CONFIRMATION:
                return $account->getInternalEmail();
            case self::FIRST_NAME:
                return $profile->getFirstName();
            case self::LAST_NAME:
                return $profile->getLastName();
            case self::FULL_NAME:
                return $profile->getFirstName(). ' ' . $profile->getLastName();
            case self::PHONE:
                return $profile->getPhone();
            case self::PHONE_AREA:
                return $profile->getPhoneAreaCode();
            case self::PHONE_PREFIX:
                return $profile->getPhonePrefix();
            case self::PHONE_LOCAL:
                return $profile->getPhoneLocal();
            case self::DATE_OF_BIRTH:
                return $profile->getDateOfBirth() ?
                    $profile->getDateOfBirth()->format(DateHelper::DEFAULT_DATE_FORMAT) : null;
            case self::DATE_OF_BIRTH_DAY:
                return $profile->getDateOfBirth() ? Carbon::instance($profile->getDateOfBirth())->day : null;
            case self::DATE_OF_BIRTH_MONTH:
                return $profile->getDateOfBirth() ? Carbon::instance($profile->getDateOfBirth())->month : null;
            case self::DATE_OF_BIRTH_YEAR:
                return $profile->getDateOfBirth() ? Carbon::instance($profile->getDateOfBirth())->year : null;
            case self::AGE:
                return $profile->getDateOfBirth() ? Carbon::instance($profile->getDateOfBirth())->age : null;
            case self::GENDER:
                return $profile->getGender() ?
                    self::getMappingValue($form, $profile->getGender()) : null;
            case self::CITIZENSHIP:
                return $profile->getCitizenship() ?
                    self::getMappingValue($form, $profile->getCitizenship()->getId()) : null;
            case self::CITIZENSHIP_NAME:
                return $profile->getCitizenship();
            case self::ETHNICITY:
                return $profile->getEthnicity() ?
                    self::getMappingValue($form, $profile->getEthnicity()->getId()) : null;
            case self::ETHNICITY_NAME:
                return $profile->getEthnicity();
            case self::COUNTRY:
                return $profile->getCountry();
            case self::COUNTRY_ABBREVIATION:
                return $profile->getCountry() ? $profile->getCountry()->getAbbreviation() : null;
            case self::STATE:
                return $profile->getState();
            case self::STATE_ABBREVIATION;
                return $profile->getState() ? $profile->getState()->getAbbreviation() : null;
            case self::CITY:
                return $profile->getCity();
            case self::ADDRESS:
                return $profile->getFullAddress();
            case self::ZIP:
                return $profile->getZip();
            case self::SCHOOL_LEVEL:
                return $profile->getSchoolLevel() ?
                    self::getMappingValue($form, $profile->getSchoolLevel()->getId()) : null;
            case self::SCHOOL_LEVEL_NAME:
                return $profile->getSchoolLevel();
            case self::DEGREE:
                return $profile->getDegree() ?
                    self::getMappingValue($form, $profile->getDegree()->getId()) : null;
            case self::DEGREE_NAME:
                return $profile->getDegree();
            case self::DEGREE_TYPE:
                return $profile->getDegreeType() ?
                    self::getMappingValue($form, $profile->getDegreeType()->getId()) : null;
            case self::DEGREE_TYPE_NAME:
                return $profile->getDegreeType();
            case self::ENROLLMENT_YEAR:
                return $profile->getEnrollmentYear();
            case self::ENROLLMENT_MONTH:
                return $profile->getEnrollmentMonth();
            case self::GRADUATION_YEAR:
                return $profile->getGraduationYear();
            case self::GRADUATION_MONTH:
                return $profile->getGraduationMonth();
            case self::GRADUATION_DATE:
                return sprintf('%s/%s', $profile->getGraduationMonth(), $profile->getGraduationYear());
            case self::GPA:
                return $profile->getGpa();
            case self::GPA_RANGE:
                return self::getMappingValue($form, $profile->getGpa());
            case self::CAREER_GOAL:
                return $profile->getCareerGoal() ?
                    self::getMappingValue($form, $profile->getCareerGoal()->getId()) : null;
            case self::CAREER_GOAL_NAME:
                return $profile->getCareerGoal();
            case self::STUDY_ONLINE:
                return self::getMappingValue($form, $profile->getStudyOnline());
            case self::HIGHSCHOOL:
                return self::getMappingValue($form, $profile->getHighschool());
            case self::HIGHSCHOOL_ADDRESS:
                return $profile->getHighschoolAddress1() .' '. $profile->getHighschoolAddress2();
            case self::UNIVERSITY:
                return self::getMappingValue($form, $profile->getUniversity());
            case self::UNIVERSITY_ADDRESS:
                return $profile->getUniversityAddress1() .' '. $profile->getUniversityAddress2();
            case self::FULL_ADDRESS:
                // '[[address]], [[city]], [[state_abbreviation]] [[zip]]
                // (please notice that there are two spaces between state and zip)
                return sprintf(
                    '%s, %s, %s  %s',
                    $profile->getAddress(),
                    $profile->getCity(),
                    $profile->getState() ? $profile->getState()->getAbbreviation() : null,
                    $profile->getZip()
                );
            case self::ACCEPT_CONFIRMATION:
            case self::HIDDEN_FIELD:
            case self::STATIC_FIELD:
            case self::SUBMIT_FIELD:
                return $form->getValue();
            default:
                throw new \LogicException(sprintf('Unknown system field: %s', $form->getSystemField()));
        }
    }

    /**
     * @param Form $form
     * @param      $userValue
     *
     * @return int|null|string
     */
	private static function getMappingValue(Form $form, $userValue)
    {
		$result = '';
		$mapping = $form->getMapping();
		if (!empty($mapping) && is_array($mapping)) {
			foreach ($mapping as $externalValue => $ourValues) {
                if (is_array($ourValues) && in_array($userValue, $ourValues)) {
                    $result = $externalValue;
                    break;
                }
			}
		} else {
            $result = $userValue;
        }

		return $result;
	}
}

