<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Ethnicity;
use App\Entity\MilitaryAffiliation;
use App\Entity\Profile;
use App\Entity\SchoolLevel;
use App\Entity\State;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class OptionsManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    const OPTIONS_COUNTRIES             = 'countries';
    const OPTIONS_STATES                = 'states';
    const OPTIONS_GENDERS               = 'genders';
    const OPTIONS_CITIZENSHISP          = 'citizenships';
    const OPTIONS_CITIZENSHISP_ALL      = 'citizenshipsAll';
    const OPTIONS_ETHNICITIES           = 'ethnicities';
    const OPTIONS_GPAS                  = 'gpas';
    const OPTIONS_DEGREES               = 'degrees';
    const OPTIONS_DEGREE_TYPES          = 'degreeTypes';
    const OPTIONS_CAREER_GOALS          = 'careerGoals';
    const OPTIONS_SCHOOL_LEVELS         = 'schoolLevels';
    const OPTIONS_STUDY_ONLINE          = 'studyOnline';
    const OPTIONS_STUDY_COUNTRIES       = 'studyCountries';
    const OPTIONS_PROFILE_TYPE          = 'profileTypes';
    const OPTIONS_MILITARY_AFFILIATIONS = 'militaryAffiliations';

    /**
     * List of all available options.
     *
     * @var array
     */
    public static $list = [
        self::OPTIONS_COUNTRIES,
        self::OPTIONS_STATES,
        self::OPTIONS_GENDERS,
        self::OPTIONS_CITIZENSHISP,
        self::OPTIONS_ETHNICITIES,
        self::OPTIONS_GPAS,
        self::OPTIONS_DEGREES,
        self::OPTIONS_DEGREE_TYPES,
        self::OPTIONS_CAREER_GOALS,
        self::OPTIONS_SCHOOL_LEVELS,
        self::OPTIONS_STUDY_ONLINE,
        self::OPTIONS_STUDY_COUNTRIES,
        self::OPTIONS_PROFILE_TYPE,
        self::OPTIONS_MILITARY_AFFILIATIONS,
    ];

    /**
     * OptionsManager constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Account|null $account
     * @param array|null   $only
     *
     * @return array
     */
    public function all(Account $account = null, array $only = null)
    {
        $all = [];
        $only = empty($only) ? static::$list : $only;

        foreach ($only as $name) {
            $all[$name] = $this->get($name, $account);
        }

        return $all;
    }

    /**
     * @param string       $name
     * @param Account|null $account
     *
     * @return array
     */
    public function get(string $name, Account $account = null)
    {
         switch ($name) {
             case self::OPTIONS_COUNTRIES:
                 return $this->countries($account);
                 break;
             case self::OPTIONS_STATES:
                 return $this->states(['country' => $account ? $account->getCountry() : Country::USA]);
                 break;
             case self::OPTIONS_GENDERS:
                 return $this->genders();
                 break;
             case self::OPTIONS_CITIZENSHISP:
                 return $this->citizenships($account && $account->getCountry()->getId() === Country::USA ?
                    ['country' => Country::USA] : []);
                 break;
             case self::OPTIONS_CITIZENSHISP_ALL:
                 return $this->citizenships([]);
                 break;
             case self::OPTIONS_ETHNICITIES:
                 return $this->ethnicities();
                 break;
             case self::OPTIONS_GPAS:
                 return $this->gpas();
                 break;
             case self::OPTIONS_DEGREES:
                 return $this->degrees();
                 break;
             case self::OPTIONS_DEGREE_TYPES:
                 return $this->degreeTypes();
                 break;
             case self::OPTIONS_CAREER_GOALS:
                 return $this->careerGoals();
                 break;
             case self::OPTIONS_SCHOOL_LEVELS:
                 return $this->schoolLevels();
                 break;
             case self::OPTIONS_STUDY_ONLINE:
                 return $this->studyOnline();
                 break;
             case self::OPTIONS_STUDY_COUNTRIES:
                 return $this->studyCountries();
                 break;
             case self::OPTIONS_PROFILE_TYPE:
                 return $this->profileTypes();
                 break;
             case self::OPTIONS_MILITARY_AFFILIATIONS:
                 return $this->militaryAffiliations();
                 break;
             default:
                throw new \InvalidArgumentException(sprintf('Unknown option name: %s', $name));
                break;
        }
    }

    /**
     * @param Account|null $account
     *
     * @return array
     */
    public function countries(Account $account = null)
    {
        $countries = Country::options();

        if ($account && !$account->isUSA()) {
            $countries = array_filter(
                $countries,
                function($country) {
                    return $country !== Country::USA;
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        return $countries;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function states(array $options)
    {
        return State::options($options);
    }

    /**
     * @return array
     */
    public function genders()
    {
        return Profile::genders();
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function citizenships(array $options)
    {
        return Citizenship::options($options);
    }

    /**
     * @return array
     */
    public function ethnicities()
    {
        return Ethnicity::options();
    }

    /**
     * @return array
     */
    public function gpas()
    {
        return Profile::gpas();
    }

    /**
     * @return array
     */
    public function degrees()
    {
        return Degree::options();
    }

    /**
     * @return array
     */
    public function degreeTypes()
    {
        return DegreeType::options();
    }

    /**
     * @return array
     */
    public function careerGoals()
    {
        return CareerGoal::options();
    }

    /**
     * @return array
     */
    public function schoolLevels()
    {
        return SchoolLevel::options();
    }

    /**
     * @return array
     */
    public function studyOnline()
    {
        return Profile::studyOnlineOptions();
    }

    /**
     * @return array
     */
    public function studyCountries()
    {
        return array_filter(
            Country::options(),
            function($country) {
                return $country !== Country::USA;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @return array
     */
    public function profileTypes()
    {
        return Profile::profileTypes();
    }

    /**
     * @return array
     */
    public function militaryAffiliations()
    {
        return MilitaryAffiliation::options();
    }

    /**
     * @return array
     */
    public function yesNo()
    {
        return [
            0 => 'No',
            1 => 'Yes',
        ];
    }
}
