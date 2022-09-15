<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\AccountEligibility;
use App\Entity\ApplicationStatus;
use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Scholarship;
use App\Entity\Eligibility;
use App\Entity\Field;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Subscription;
use App\Events\Account\AccountEligibilityUpdateEvent;

use App\Events\ReasonInterface;
use App\Events\Scholarship\ScholarshipAccountEligibilityUpdate;

use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

/**
 * Class EligibilityService
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class EligibilityService
{
    const CACHE_ELIGIBILITY_CONDITIONS = 'scholarships-eligibility-conditions-sql';
    const CACHE_ELIGIBILITY_CONDITIONS_TTL = 60 * 60 * 6;

    const CACHE_TAGS = ['eligibility-service'];

    const EVALUE_STRING = 'e.value';
    const EVALUE_JSON = 'TRIM(BOTH \'"\' FROM e.value)';
    const EVALUE_CONF = self::EVALUE_JSON;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * @var AccountRepository
     */
    protected $accounts;

    /**
     * @var array
     */
    protected $eligibilities = [];

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * EligibilityService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, CacheManager $cacheManager)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Eligibility::class);
        $this->scholarships = $em->getRepository(Scholarship::class);
        $this->accounts = $em->getRepository(Account::class);

        /** @var Repository $cacheRepository */
        $cacheRepository = $cacheManager->driver();
        $this->cache = $cacheRepository->tags(static::CACHE_TAGS);
    }

    /**
     * @param Account|int          $account
     * @param null|int|Scholarship $scholarship
     *
     * @return bool
     */
    public function isEligible($account, $scholarship)
    {
        $accountId = ($account instanceof Account) ? $account->getAccountId() : $account;
        $scholarshipId = ($scholarship instanceof Scholarship) ? $scholarship->getScholarshipId() : $scholarship;
        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app(EligibilityCacheService::class);

        return !empty($elbCacheService->getAccountEligibleScholarshipIds($accountId, [$scholarshipId]));
    }

    /**
     * @param int $accountId
     * @return array [[account_id, scholarship_id, count(eligibilities), amount, title, expiration_date]]
     *
     */
    public function fetchEligibleScholarshipsData(int $accountId)
    {
        $scholarships = $this->scholarships->getActiveScholarshipIdsForAccount($accountId);

        if (!$scholarships) {
            return [];
        }

        $sql = $this->buildEligibilitySQL($scholarships, [$accountId], true);

        $scholarshipsData = \DB::select(\DB::raw($sql));

        return array_filter($scholarshipsData, function($v) use ($accountId) {
            return $accountId === $v->account_id;
        });
    }


    /**
     * @param Scholarship $scholarship
     * @return int
     */
    public function deleteEligibilities(Scholarship $scholarship)
    {
        return \DB::delete("
            DELETE FROM eligibility WHERE scholarship_id = {$scholarship->getScholarshipId()}
        ");
    }


    /**
     * SQL Returns accounts eligibility in next form:
     *
     * account_id, scholarship_id
     * 1           1
     * 1           2
     * 1           3
     * 2           2
     * 2           3
     * 3           4
     *
     * @param array $accounts
     * @param array $scholarships
     *
     * @return string
     */
    protected function buildEligibilitySQL(array $scholarships, array $accounts = null, $skipApplied = false)
    {
        $query = sprintf(
            'SELECT DISTINCT p.account_id, e1.scholarship_id, e1.count AS count, e1.title, e1.expiration_date, CAST(e1.amount AS SIGNED) as amount
             FROM account a
             INNER JOIN profile p ON p.account_id = a.account_id %s
             INNER JOIN (
				SELECT s.scholarship_id, s.title, s.expiration_date, s.amount, COUNT(e1.eligibility_id) AS count
				FROM scholarship s
				LEFT JOIN eligibility e1 ON e1.scholarship_id = s.scholarship_id AND e1.is_optional = FALSE
				WHERE s.scholarship_id IN (%s)
				GROUP BY s.scholarship_id
			 ) AS e1
             LEFT JOIN eligibility e ON (e.scholarship_id = e1.scholarship_id AND e.is_optional = FALSE)',
            is_array($accounts) ? sprintf('AND a.account_id IN (%s)', implode(',', $accounts)) : '',
            implode(',', $scholarships)
        );

        $where = ' WHERE '.$this->buildEligibilityConditions();

        if ($skipApplied) {
            $query .= '
             LEFT JOIN application ap ON ap.account_id = p.account_id AND ap.scholarship_id = e1.scholarship_id';

            $where .= '
             AND ap.scholarship_id IS NULL OR ap.application_status_id = '.ApplicationStatus::NEED_MORE_INFO;
        }

        $query .= $where;

        $query .= '
             GROUP BY p.account_id, e1.scholarship_id
             HAVING count IN (0, COUNT(e.eligibility_id))
             ORDER BY e1.scholarship_id;';

        return $query;
    }

    /**
     * @param int   $scholarship
     * @param array $accounts
     *
     * @return mixed
     */
    protected function buildScholarshipEligibilitySQL(int $scholarship, array $accounts)
    {
        return sprintf(
            'SELECT a.account_id, a.eligibility_id, ae.list, COUNT(e.eligibility_id) AS count
             FROM account a
             INNER JOIN profile p ON p.account_id = a.account_id
             LEFT JOIN account_eligibility ae ON ae.id = a.eligibility_id
             LEFT JOIN eligibility e ON e.scholarship_id = %s AND e.is_optional = FALSE AND %s
             WHERE a.account_id IN (%s)
             GROUP BY a.account_id
             ',
            $scholarship,
            $this->buildEligibilityConditions(),
            implode(',', $accounts)
        );
    }

    /**
     * Build conditions for WHERE sql statement.
     * Conditions same for all scholarships depends on eligibility configs.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @return string
     */
    protected function buildEligibilityConditions()
    {
        if (null === ($where = \Cache::get(static::CACHE_ELIGIBILITY_CONDITIONS))) {

            $conditions = [];
            foreach (array_keys(Eligibility::$fields) as $field) {
                switch ($field) {
                    case Field::EMAIL:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'a.email');
                        break;
                    case Field::FIRST_NAME:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.first_name');
                        break;
                    case Field::LAST_NAME:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.last_name');
                        break;
                    case Field::FULL_NAME:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field,
                            'CONCAT_WS(" ", p.first_name, p.last_name)');
                        break;
                    case Field::GENDER:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.gender');
                        break;
                    case Field::CITIZENSHIP:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.citizenship_id');
                        break;
                    case Field::ETHNICITY:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.ethnicity_id');
                        break;
                    case Field::COUNTRY:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.country_id');
                        break;
                    case Field::STATE:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.state_id');
                        break;
                    case Field::CITY:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.city');
                        break;
                    case Field::ADDRESS:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.address');
                        break;
                    case Field::ZIP:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.zip');
                        break;
                    case Field::SCHOOL_LEVEL:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.school_level_id');
                        break;
                    case Field::DEGREE:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.degree_id');
                        break;
                    case Field::DEGREE_TYPE:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.degree_type_id');
                        break;
                    case Field::ENROLLMENT_YEAR:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.enrollment_year');
                        break;
                    case Field::ENROLLMENT_MONTH:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.enrollment_month');
                        break;
                    case Field::GPA:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field,
                            'CAST(p.gpa AS DECIMAL(2,1))',
                            sprintf('CAST(%s AS DECIMAL(2,1))', static::EVALUE_CONF)
                        );
                        break;
                    case Field::CAREER_GOAL:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.career_goal_id');
                        break;
                    case Field::STUDY_ONLINE:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.study_online');
                        break;
                    case Field::HIGH_SCHOOL_NAME:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.highschool');
                        break;
                    case Field::HIGH_SCHOOL_GRADUATION_YEAR:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.highschool_graduation_year');
                        break;
                    case Field::HIGH_SCHOOL_GRADUATION_MONTH:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.highschool_graduation_month');
                        break;
                    case Field::COLLEGE_GRADUATION_YEAR:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.graduation_year');
                        break;
                    case Field::COLLEGE_GRADUATION_MONTH:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.graduation_month');
                        break;
                    case Field::COLLEGE_NAME:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.university');
                        break;
                    case Field::MILITARY_AFFILIATION:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.military_affiliation_id');
                        break;
                    case Field::PHONE:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.phone');
                        break;
                    case Field::PHONE_AREA_CODE:
                        //assume phone stored in format (xxx) xxx - xxxx
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'SUBSTRING(p.phone, 2, 3)');
                        break;
                    case Field::PHONE_PREFIX:
                        //assume phone stored in format (xxx) xxx - xxxx
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'SUBSTRING(p.phone, 7, 3)');
                        break;
                    case Field::PHONE_LOCAL:
                        //assume phone stored in format (xxx) xxx - xxxx
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'SUBSTRING(p.phone, 13, 4)');
                        break;
                    case Field::DATE_OF_BIRTH:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field,
                            'DATE(p.date_of_birth)',
                            sprintf('DATE(%s)', static::EVALUE_CONF)
                        );
                        break;
                    case Field::AGE:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field,
                            'TIMESTAMPDIFF(YEAR,DATE(p.date_of_birth),CURRENT_TIMESTAMP())'
                        );
                        break;
                    case Field::BIRTHDAY_YEAR:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'YEAR(p.date_of_birth)');
                        break;
                    case Field::BIRTHDAY_MONTH:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'MONTH(p.date_of_birth)');
                        break;
                    case Field::BIRTHDAY_DAY:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'DAY(p.date_of_birth)');
                        break;
                    case Field::STATE_FREE_TEXT:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'p.state_name');
                        break;
                    case Field::COUNTRY_OF_STUDY:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, [
                            'p.study_country1',
                            'p.study_country2',
                            'p.study_country3',
                            'p.study_country4',
                            'p.study_country5',
                        ]);
                        break;
                    case Field::ENROLLED:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, 'COALESCE(p.enrolled, 0)');
                        break;
                    case Field::HIGH_SCHOOL_ADDRESS:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, [
                            'p.highschool_address1',
                            'p.highschool_address2',
                        ]);
                        break;
                    case Field::COLLEGE_ADDRESS:
                        $conditions[] = $this->buildEligibilityFieldConditionCase($field, [
                            'p.university_address1',
                            'p.university_address2',
                        ]);
                        break;
                    case Field::PICTURE:
                    case Field::HIGH_SCHOOL_COUNTRY:
                    case Field::HIGH_SCHOOL_STATE:
                    case Field::HIGH_SCHOOL_CITY:

                    case Field::HIGH_SCHOOL_ZIP:
                    case Field::COLLEGE_NAME:
                    case Field::COLLEGE_COUNTRY:
                    case Field::COLLEGE_STATE:
                    case Field::COLLEGE_CITY:
                    case Field::COLLEGE_ZIP:
                    case Field::ACCEPT_CONFIRMATION:
                    case Field::EMAIL_CONFIRMATION:
                    case Field::PHONE_AREA_CODE:
                    case Field::STATE_ABBREVIATION:
                        // not implemented yet
                        break;
                    default:
                        throw new \RuntimeException(sprintf('Unknown field: %s', $field));
                        break;
                }
            }

            $where = sprintf("CASE e.field_id %s ELSE TRUE END", implode("\n", $conditions));

            \Cache::put(static::CACHE_ELIGIBILITY_CONDITIONS, $where, static::CACHE_ELIGIBILITY_CONDITIONS_TTL);
        }

        return $where;
    }

    /**
     * @param int           $field
     * @param string|array  $value
     * @param string        $conValue
     *
     * @return Expr\Orx
     */
    protected function buildEligibilityFieldConditionCase(int $field, $value, string $conValue = self::EVALUE_CONF)
    {
        if (!isset(Eligibility::$fields[$field])) {
            throw new \InvalidArgumentException('Unknown eligibility field: ' . $field);
        }

        $cases = [];
        $values = is_array($value) ? $value : [$value];
        $expr = $this->em->getExpressionBuilder();
        foreach (Eligibility::$fields[$field] as $operator) {
            switch ($operator) {
                case Eligibility::TYPE_REQUIRED:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->isNotNull($value).' AND '.$expr->neq($value, "''");
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_REQUIRED, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_BOOL:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->eq($conValue, $value);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_BOOL, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_VALUE:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->eq($conValue, $value);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_VALUE, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_LESS_THAN:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->lt($value, $conValue);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_LESS_THAN, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_LESS_THAN_OR_EQUAL:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->lte($value, $conValue);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_LESS_THAN_OR_EQUAL, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_GREATER_THAN:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->gt($value, $conValue);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_GREATER_THAN, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_GREATER_THAN_OR_EQUAL:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->gte($value, $conValue);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_GREATER_THAN_OR_EQUAL, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_NOT:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->neq($conValue, $value);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_NOT, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_IN:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->gt(new Expr\Func('FIND_IN_SET', [$value, $conValue]), 0);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_IN, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_NIN:
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        return $expr->eq(new Expr\Func('FIND_IN_SET', [$value, $conValue]), 0);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_NIN, new Expr\Orx($compressions));
                    break;
                case Eligibility::TYPE_BETWEEN:
                    $conValue = static::EVALUE_CONF;
                    $compressions = array_map(function($value) use ($expr, $conValue) {
                        // something like this: SELECT 7 BETWEEN LEFT('5,8', LOCATE(',', '5,8')-1) AND SUBSTRING('5,8' FROM LOCATE(',', '5,8')+1) > 0
                        // will produce 7 BETWEEN 5 AND 8 > 0
                        return $expr->gt("{$value} BETWEEN LEFT({$conValue}, LOCATE(',', {$conValue})-1) AND SUBSTRING({$conValue} FROM LOCATE(',', {$conValue})+1)", 0);
                    }, $values);

                    $cases[] = sprintf('WHEN \'%s\' THEN %s', Eligibility::TYPE_BETWEEN, new Expr\Orx($compressions));
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unknown eligibility type: %s', $operator));
            }
        }

        return sprintf("WHEN %s THEN \n CASE e.type \n%s\n ELSE TRUE END\n", $field, implode("\n", $cases));
    }

    /**
     * @param $info
     */
    protected function info($info)
    {
        if (config('app.debug')) {
            \Log::info($info);
            echo "$info\n";
        }
    }

    /**
     * @param $gender
     * @param $schoolLevel
     * @param $degree
     * @param $age
     *
     * @return array
     */
    public function getBasicEligibilityScholarshipIds($gender, $schoolLevel, $degree, $age)
    {
        $key = sprintf('basic-eligibility-scholarship-ids-%s-%s-%s-%s', $gender, $schoolLevel, $degree, $age);
        if (null === $basicEligibilities = $this->cache->get($key)) {
            $ids = [];
            $active = $this->scholarships->findActiveScholarshipsIds();
            $sql = "
            select scholarship_id, field_id, type, value from eligibility as e
            where e.scholarship_id IN (%scholarships%)
              and field_id in (6, 10, 19, 20)
            order by scholarship_id;";
            $sqlString = str_replace(
                ['%scholarships%'],
                [implode(',', $active)],
                $sql
            );
            $res = json_decode(json_encode(\DB::select($sqlString)), true);
            $sortedScholarships = array();
            foreach ($res as $key => $item) {
                $sortedScholarships[$item['scholarship_id']][] = $item;
            }
            foreach ($sortedScholarships as $scholarshipEligibility) {
                if (!empty($scholarshipEligibility)) {
                    $eligible = true;
                    foreach ($scholarshipEligibility as $eligibility) {
                        switch ($eligibility['field_id']) {
                            case Field::AGE:
                                if ($age) {
                                    if (!$this->assert($age, $eligibility['type'], $eligibility['value'])) {
                                        $eligible = false;
                                    }
                                }
                                break;
                            case Field::GENDER:
                                if (!$this->assert($gender, $eligibility['type'], $eligibility['value'])) {
                                    $eligible = false;
                                }
                                break;
                            case Field::SCHOOL_LEVEL:
                                if (!$this->assert($schoolLevel, $eligibility['type'], $eligibility['value'])) {
                                    $eligible = false;
                                }
                                break;
                            case Field::DEGREE:
                                if ($this->assert($degree, $eligibility['type'], $eligibility['value'])) {
                                    $eligible = false;
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    if ($eligible) {
                        $ids[] = $eligibility['scholarship_id'];
                    }
                }
            }
            $this->cache->put($key, $basicEligibilities = array_unique(array_map('intval', $ids)), 60 * 60 * 2);
        }
        return $basicEligibilities;
    }

    private function assert($value, $eligibilityType, $eligibilityValue)
    {
        $eligibilityValue = trim($eligibilityValue, "\"");

        switch ($eligibilityType) {
            case Eligibility::TYPE_REQUIRED:
                return true;
                break;
            case Eligibility::TYPE_VALUE:
                return $value == $eligibilityValue;
                break;
            case Eligibility::TYPE_LESS_THAN:
                return $value < $eligibilityValue;
                break;
            case Eligibility::TYPE_LESS_THAN_OR_EQUAL:
                return $value <= $eligibilityValue;
                break;
            case Eligibility::TYPE_GREATER_THAN:
                return $value > $eligibilityValue;
                break;
            case Eligibility::TYPE_GREATER_THAN_OR_EQUAL:
                return $value >= $eligibilityValue;
                break;
            case Eligibility::TYPE_NOT:
                return $value != $eligibilityValue;
                break;
            case Eligibility::TYPE_IN:
                return in_array($value, explode(',', $eligibilityValue));
                break;
            default;
                break;
        }

        return false;
    }
}