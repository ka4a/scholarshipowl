<?php namespace App\Entities;

use App\Contracts\DictionaryEntityContract;
use App\Traits\DictionaryEntity;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Field implements JsonApiResource, DictionaryEntityContract
{
    use DictionaryEntity;

    const EMAIL = 'email';
    const PHONE = 'phone';
    const STATE = 'state';
    const NAME = 'name';
    const DATE_OF_BIRTH = 'dateOfBirth';
    const SCHOOL_LEVEL = 'schoolLevel';
    const FIELD_OF_STUDY = 'fieldOfStudy';
    const GPA = 'GPA';
    const CITY = 'city';
    const ADDRESS = 'address';
    const ZIP = 'zip';

    const GENDER = 'gender';
    const ETHNICITY = 'ethnicity';
    const DEGREE_TYPE = 'degreeType';
    const ENROLLMENT_DATE = 'enrollmentDate';
    const CAREER_GOAL = 'careerGoal';
    const HIGH_SCHOOL_NAME = 'highSchoolName';
    const HIGH_SCHOOL_GRADUATION_DATE = 'highSchoolGraduationDate';
    const COLLEGE_NAME = 'collegeName';
    const COLLEGE_GRADUATION_DATE = 'collegeGraduationDate';

    const TYPE_EMAIL = 'email';
    const TYPE_PHONE = 'phone';
    const TYPE_DATE = 'date';
    const TYPE_TEXT = 'text';
    const TYPE_OPTION = 'option';

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'field';
    }

    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * Type is used for eligibility SQL condition building.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Available options for "options" type eligibility.
     *
     * @var array|null
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $options;

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}
