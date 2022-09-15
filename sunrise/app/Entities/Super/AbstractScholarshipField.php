<?php namespace App\Entities\Super;

use App\Entities\Field;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\MappedSuperclass()
 */
abstract class AbstractScholarshipField
{
    use Timestamps;

    const ELIGIBILITY_TYPE_EQUALS       = 'eq';
    const ELIGIBILITY_TYPE_LT           = 'lt';
    const ELIGIBILITY_TYPE_LTE          = 'lte';
    const ELIGIBILITY_TYPE_GT           = 'gt';
    const ELIGIBILITY_TYPE_GTE          = 'gte';
    const ELIGIBILITY_TYPE_BETWEEN      = 'between';
    const ELIGIBILITY_TYPE_NOT          = 'neq';
    const ELIGIBILITY_TYPE_IN           = 'in';
    const ELIGIBILITY_TYPE_NOT_IN       = 'nin';

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Field
     * @ORM\ManyToOne(targetEntity="App\Entities\Field")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $field;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $optional = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $eligibilityType;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    protected $eligibilityValue;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param bool $optional
     * @return AbstractScholarshipField
     */
    public function setOptional(bool $optional): self
    {
        $this->optional = $optional;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function setField(Field $field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getEligibilityType()
    {
        return $this->eligibilityType;
    }

    /**
     * @param string $eligibilityType
     * @return $this
     */
    public function setEligibilityType($eligibilityType)
    {
        $this->eligibilityType = $eligibilityType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEligibilityValue()
    {
        return $this->eligibilityValue;
    }

    /**
     * @param $eligibilityValue
     * @return $this
     */
    public function setEligibilityValue($eligibilityValue)
    {
        $this->eligibilityValue = $eligibilityValue;
        return $this;
    }
}
