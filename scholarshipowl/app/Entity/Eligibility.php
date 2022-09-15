<?php namespace App\Entity;

use App\Events\Scholarship\ScholarshipUpdatedEvent;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Eligibility
 *
 * @ORM\Table(name="eligibility")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Eligibility implements \JsonSerializable
{
    const TYPE_REQUIRED = 'required';
    const TYPE_VALUE = 'value';
    const TYPE_LESS_THAN = 'less_than';
    const TYPE_LESS_THAN_OR_EQUAL = 'less_than_or_equal';
    const TYPE_GREATER_THAN = 'greater_than';
    const TYPE_GREATER_THAN_OR_EQUAL = 'greater_than_or_equal';
    const TYPE_NOT = 'not';
    const TYPE_IN = 'in';
    const TYPE_NIN = 'nin';
    const TYPE_BETWEEN = 'between';
    const TYPE_BOOL = 'boolean';

    /**
     * @var array
     */
    private $types = [
        self::TYPE_REQUIRED => 'Required',
        self::TYPE_VALUE => 'Value',
        self::TYPE_LESS_THAN => 'Less than',
        self::TYPE_LESS_THAN_OR_EQUAL => 'Less than or equal',
        self::TYPE_GREATER_THAN => 'Greater than',
        self::TYPE_GREATER_THAN_OR_EQUAL => 'Greater than or equal',
        self::TYPE_NOT => 'Not',
        self::TYPE_IN => 'In',
        self::TYPE_NIN => 'Not in',
        self::TYPE_BETWEEN => 'Between',
        self::TYPE_BOOL => 'Boolean (Yes/No)'
    ];

    /**
     *
     * @var array
     */
    static public $fields = [
        Field::EMAIL => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::FIRST_NAME => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::LAST_NAME => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::PHONE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::DATE_OF_BIRTH => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
        ],
        Field::AGE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::BIRTHDAY_YEAR => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::BIRTHDAY_MONTH => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::BIRTHDAY_DAY => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::GENDER => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::CITIZENSHIP => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::ETHNICITY => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::PICTURE => [],
        Field::COUNTRY => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::STATE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::CITY => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::ADDRESS => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::ZIP => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::SCHOOL_LEVEL => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::DEGREE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::DEGREE_TYPE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::ENROLLMENT_YEAR => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::ENROLLMENT_MONTH => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::GPA => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::CAREER_GOAL => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::STUDY_ONLINE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::HIGH_SCHOOL_NAME => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::HIGH_SCHOOL_GRADUATION_YEAR => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::HIGH_SCHOOL_GRADUATION_MONTH => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::HIGH_SCHOOL_COUNTRY => [],
        Field::HIGH_SCHOOL_STATE => [],
        Field::HIGH_SCHOOL_CITY => [],
        Field::HIGH_SCHOOL_ADDRESS => [
            Eligibility::TYPE_REQUIRED,
        ],
        Field::HIGH_SCHOOL_ZIP => [],
        Field::COLLEGE_NAME => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::COLLEGE_GRADUATION_YEAR => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::COLLEGE_GRADUATION_MONTH => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::COLLEGE_COUNTRY => [],
        Field::COLLEGE_STATE => [],
        Field::COLLEGE_CITY => [],
        Field::COLLEGE_ADDRESS => [
            Eligibility::TYPE_REQUIRED,
        ],
        Field::COLLEGE_ZIP => [],
        Field::ACCEPT_CONFIRMATION => [],
        Field::EMAIL_CONFIRMATION => [],
        Field::PHONE_AREA_CODE => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::PHONE_PREFIX => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::PHONE_LOCAL => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::FULL_NAME => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::STATE_ABBREVIATION => [],
        Field::MILITARY_AFFILIATION => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_GREATER_THAN,
            Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            Eligibility::TYPE_LESS_THAN,
            Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::COUNTRY_OF_STUDY => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
            Eligibility::TYPE_BETWEEN,
        ],
        Field::STATE_FREE_TEXT => [
            Eligibility::TYPE_REQUIRED,
            Eligibility::TYPE_VALUE,
            Eligibility::TYPE_NOT,
            Eligibility::TYPE_IN,
            Eligibility::TYPE_NIN,
        ],
        Field::ENROLLED => [
            Eligibility::TYPE_BOOL,
        ],
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="eligibility_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $eligibilityId;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type = self::TYPE_REQUIRED;

    /**
     * @var mixed
     *
     * @ORM\Column(name="value", type="json", length=20000, nullable=true)
     */
    protected $value;

    /**
     * @var Field
     *
     * @ORM\OneToOne(targetEntity="Field")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_id", referencedColumnName="field_id")
     * })
     */
    protected $field;

    /**
     * @var Scholarship
     *
     * @ORM\OneToOne(targetEntity="Scholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     * })
     */
    protected $scholarship;

    /**
     * @var mixed
     *
     * @ORM\Column(name="is_optional", type="boolean")
     */
    protected $isOptional;


    /**
     * Eligibility constructor.
     *
     * @param $field
     * @param $type
     * @param $value
     */
    public function __construct($field, $type, $value, $isOptional = false)
    {
        if (!isset($this->types[$type])) {
            throw new \InvalidArgumentException(sprintf('Invalid type %s', $type));
        }

        $this->setField(Field::convert($field));
        $this->setValue($value);
        $this->setType($type);
        $this->setIsOptional($isOptional);
    }

    /**
     * Get eligibilityId
     *
     * @return integer
     */
    public function getEligibilityId()
    {
        return $this->eligibilityId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Eligibility
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Eligibility
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
     * Set field
     *
     * @param Field $field
     *
     * @return Eligibility
     */
    public function setField(Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set scholarship
     *
     * @param Scholarship $scholarship
     *
     * @return Eligibility
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
     * @param bool $val
     * @return $this
     */
    public function setIsOptional(bool $val)
    {
        $this->isOptional = $val;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsOptional()
    {
        return $this->isOptional;
    }

    /**
     * @param LifecycleEventArgs $event
     *
     * @ORM\PostUpdate()
     */
    public function postUpdateEvent()
    {
        \Event::dispatch(new ScholarshipUpdatedEvent($this->getScholarship()));
    }

        /**
     * Called on json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
			'eligibility_id' => $this->getEligibilityId(),
			'scholarship_id' => $this->getScholarship()->getScholarshipId(),
			'field_id' => $this->getField()->getId(),
			'type' => $this->getType(),
			'value' => $this->getValue(),
			'is_optional' => $this->getIsOptional()
        ];
    }
}

