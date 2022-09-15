<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Field
 *
 * @ORM\Table(name="field")
 * @ORM\Entity
 */
class Field
{
    use Dictionary;

    const EMAIL = 1;
    const FIRST_NAME = 2;
    const LAST_NAME = 3;
    const PHONE = 4;
    const DATE_OF_BIRTH = 5;
    const AGE = 6;
    const BIRTHDAY_YEAR = 7;
    const BIRTHDAY_MONTH = 8;
    const BIRTHDAY_DAY = 9;
    const GENDER = 10;
    const CITIZENSHIP = 11;
    const ETHNICITY = 12;
    const PICTURE = 13;
    const COUNTRY = 14;
    const STATE = 15;
    const CITY = 16;
    const ADDRESS = 17;
    const ZIP = 18;
    const SCHOOL_LEVEL = 19;
    const DEGREE = 20;
    const DEGREE_TYPE = 21;
    const ENROLLMENT_YEAR = 22;
    const ENROLLMENT_MONTH = 23;
    const GPA = 24;
    const CAREER_GOAL = 25;
    const STUDY_ONLINE = 26;
    const HIGH_SCHOOL_NAME = 27;
    const HIGH_SCHOOL_GRADUATION_YEAR = 28;
    const HIGH_SCHOOL_GRADUATION_MONTH = 29;
    const HIGH_SCHOOL_COUNTRY = 30;
    const HIGH_SCHOOL_STATE = 31;
    const HIGH_SCHOOL_CITY = 32;
    const HIGH_SCHOOL_ADDRESS = 33;
    const HIGH_SCHOOL_ZIP = 34;
    const COLLEGE_NAME = 35;
    const COLLEGE_GRADUATION_YEAR = 36;
    const COLLEGE_GRADUATION_MONTH = 37;
    const COLLEGE_COUNTRY = 38;
    const COLLEGE_STATE = 39;
    const COLLEGE_CITY = 40;
    const COLLEGE_ADDRESS = 41;
    const COLLEGE_ZIP = 42;
    const ACCEPT_CONFIRMATION = 43;
    const EMAIL_CONFIRMATION = 44;
    const PHONE_AREA_CODE = 45;
    const PHONE_PREFIX = 46;
    const PHONE_LOCAL = 47;
    const FULL_NAME = 48;
    const ACCEPT_CONFIRMATION_2 = 49;
    const ACCEPT_CONFIRMATION_3 = 50;
    const ACCEPT_CONFIRMATION_4 = 51;
    const ACCEPT_CONFIRMATION_5 = 52;
    const STATE_ABBREVIATION = 53;
    const MILITARY_AFFILIATION = 64;
    const COUNTRY_OF_STUDY = 65;
    const STATE_FREE_TEXT = 66;
    const ENROLLED = 67;


    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="field_id", type="integer", nullable=false)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    protected $name;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

