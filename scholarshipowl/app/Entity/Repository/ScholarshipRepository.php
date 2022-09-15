<?php

namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationStatus;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Contracts\RequirementContract;
use App\Entity\Scholarship;
use App\Entity\Form;
use App\Entity\ScholarshipStatus;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use App\Services\ScholarshipService;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Cache\Repository;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

/**
 * Class ScholarshipRepository
 *
 * @method Scholarship|null find($id)
 * @method Scholarship      findById($id)
 */
class ScholarshipRepository extends EntityRepository
{
    const CACHE_ACCOUNT_ELIGIBILITY_KEY = 'account-eligibility-%s';
    const CACHE_ACTIVE_NOT_APPLIED = 'active-not-applied-scholarships-%s';
    const CACHE_YDI_SCHOLARSHIPS = 'ydi-scholarships';

    const PROVIDER_SUNRISE = 'sunrise';
    const PROVIDER_SOWL = 'sowl';

    /**
     * Cache for eligible scholarships sum.
     * @var array
     */
    static $sum = [];

    /**
     * @var ScholarshipService
     */
    private $scholarshipService;

    /**
     * @return ScholarshipService
     */
    protected function getScholarshipService()
    {
        if ($this->scholarshipService === null) {
            $this->scholarshipService = app(ScholarshipService::class);
        }

        return $this->scholarshipService;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Account|int  $account
     *
     * @return QueryBuilder
     */
    private static function notApplied(QueryBuilder $queryBuilder, $account): QueryBuilder
    {
        return $queryBuilder
            ->leftJoin('s.applications', 'notAppliedApp', Join::WITH, 'notAppliedApp.account = :notAppliedAccount')
            ->andWhere('notAppliedApp.scholarship IS NULL OR notAppliedApp.applicationStatus = :needMoreInfo')
            ->setParameter('needMoreInfo', ApplicationStatus::NEED_MORE_INFO)
            ->setParameter('notAppliedAccount', $account);
    }

    /**
     * Active scholarships filter
     *
     * @param QueryBuilder   $queryBuilder
     *
     * @return QueryBuilder
     */
    private static function publishedScholarships(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->andWhere('s.isActive = true AND s.status = :published')
            ->setParameter('published', ScholarshipStatus::PUBLISHED);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Account      $account
     *
     * @return QueryBuilder
     */
    public static function withApplicationRequirements(QueryBuilder $queryBuilder, Account $account)
    {
        return $queryBuilder
            ->addSelect(['rt', 'rf', 'ri', 'rit', 'rs', 'rsp', 'at', 'af', 'ai', 'ait', 'asp', 'asr'])
            ->leftJoin('s.requirementTexts', 'rt')
            ->leftJoin('s.requirementFiles', 'rf')
            ->leftJoin('s.requirementImages', 'ri')
            ->leftJoin('s.requirementInputs', 'rit')
            ->leftJoin('s.requirementSpecialEligibility', 'rsp')
            ->leftJoin('s.requirementSurvey', 'rs')
            ->leftJoin('s.applicationTexts', 'at', Join::WITH, 'at.account = :requirementsAccount')
            ->leftJoin('s.applicationFiles', 'af', Join::WITH, 'af.account = :requirementsAccount')
            ->leftJoin('s.applicationImages', 'ai', Join::WITH, 'ai.account = :requirementsAccount')
            ->leftJoin('s.applicationInputs', 'ait', Join::WITH, 'ait.account = :requirementsAccount')
            ->leftJoin('s.applicationSpecialEligibility', 'asp', Join::WITH, 'asp.account = :requirementsAccount')
            ->leftJoin('s.applicationSurvey', 'asr', Join::WITH, 'asr.account = :requirementsAccount')
            ->setParameter('requirementsAccount', $account);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Account      $account
     *
     * @return QueryBuilder
     */
    public static function withApplications(QueryBuilder $queryBuilder, Account $account)
    {
        return $queryBuilder
            ->addSelect('a')
            ->leftJoin('s.applications', 'a', Join::WITH, 'a.account = :applicationAccount')
            ->setParameter('applicationAccount', $account);
    }

    /**
     * @param array   $scholarships
     * @param Account $account
     *
     * @return QueryBuilder
     */
    public function withAccountApplications(array $scholarships, Account $account)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::withApplications($queryBuilder, $account);
        $queryBuilder = static::withApplicationRequirements($queryBuilder, $account);

        return $queryBuilder
            ->where('s.scholarshipId IN (:scholarships)')
            ->setParameter('scholarships', $scholarships);
    }

    /**
     * @param array   $scholarships
     * @param Account $account
     *
     * @return array
     */
    public function findWithAccountApplications(array $scholarships, Account $account)
    {
        return $this->withAccountApplications($scholarships, $account)
            ->getQuery()
            ->setHint(Query::HINT_REFRESH, true)
            ->getResult();
    }

    /**
     * @param array|Scholarship[]|int[] $scholarships
     * @param Account                   $account
     *
     * @return array
     */
    public function getScholarshipStatus(array $scholarships, Account $account)
    {
        $results = [];

        $countQuery = <<<SQL
            SELECT s.scholarship_id, s.status, s.transitional_status,
              a.application_status_id, a.external_status as application_external_status,
              (COALESCE(text.req, 0) + COALESCE(file.req, 0) + COALESCE(image.req, 0) + COALESCE(input.req, 0) + COALESCE(special.req, 0) + COALESCE(survey.req, 0)) AS req,
              (COALESCE(text.app, 0) + COALESCE(file.app, 0) + COALESCE(image.app, 0) + COALESCE(input.app, 0) + COALESCE(special.app, 0) + COALESCE(survey.app, 0)) AS app
            FROM scholarship AS s
            LEFT JOIN application AS a ON a.scholarship_id = s.scholarship_id AND a.account_id = :account
            LEFT JOIN (
                SELECT rt.scholarship_id, COUNT(rt.id) AS req, COUNT(at.id) AS app
                FROM requirement_text AS rt
                LEFT JOIN application_text AS at ON at.scholarship_id = rt.scholarship_id
                  AND rt.id = at.requirement_text_id
                  AND at.account_id = :account
                where rt.is_optional = 0
                GROUP BY rt.scholarship_id
            ) AS text ON text.scholarship_id = s.scholarship_id
            LEFT JOIN (
                SELECT rf.scholarship_id, COUNT(rf.id) AS req, COUNT(af.id) AS app
                FROM requirement_file AS rf
                LEFT JOIN application_file AS af ON af.scholarship_id = rf.scholarship_id
                  AND rf.id = af.requirement_file_id
                  AND af.account_id = :account
                 where rf.is_optional = 0
                GROUP BY rf.scholarship_id
            ) AS file ON file.scholarship_id = s.scholarship_id
            LEFT JOIN (
                SELECT ri.scholarship_id, COUNT(ri.id) AS req, COUNT(ai.id) AS app
                FROM requirement_image AS ri
                LEFT JOIN application_image AS ai ON ai.scholarship_id = ri.scholarship_id
                  AND ri.id = ai.requirement_image_id
                  AND ai.account_id = :account
                where ri.is_optional = 0
                GROUP BY ri.scholarship_id
            ) AS image ON image.scholarship_id = s.scholarship_id
            LEFT JOIN (
                SELECT rit.scholarship_id, COUNT(rit.id) AS req, COUNT(ait.id) AS app
                FROM requirement_input AS rit
                LEFT JOIN application_input AS ait ON ait.scholarship_id = rit.scholarship_id
                  AND rit.id = ait.requirement_input_id
                  AND ait.account_id = :account
                where rit.is_optional = 0
                GROUP BY rit.scholarship_id
            ) AS input ON input.scholarship_id = s.scholarship_id
            LEFT JOIN (
                SELECT rsp.scholarship_id, COUNT(rsp.id) AS req, COUNT(asp.id) AS app
                FROM requirement_special_eligibility AS rsp
                LEFT JOIN application_special_eligibility AS asp ON asp.scholarship_id = rsp.scholarship_id
                  AND rsp.id = asp.requirement_id
                  AND asp.account_id = :account
                  AND asp.val = 1
                where rsp.is_optional = 0
                GROUP BY rsp.scholarship_id
            ) AS special ON special.scholarship_id = s.scholarship_id
            LEFT JOIN (
                SELECT rsr.scholarship_id, COUNT(rsr.id) AS req, COUNT(asr.id) AS app
                FROM requirement_survey AS rsr
                LEFT JOIN application_survey AS asr ON asr.scholarship_id = rsr.scholarship_id
                  AND rsr.id = asr.requirement_survey_id
                  AND asr.account_id = :account
                where rsr.is_optional = 0
                GROUP BY rsr.scholarship_id
            ) AS survey ON survey.scholarship_id = s.scholarship_id                     
            WHERE s.scholarship_id IN (:scholarships)
SQL;

        $statement = $this->getEntityManager()->getConnection()->executeQuery(
            $countQuery,
            [
                'account' => $account->getAccountId(),
                'scholarships' => array_map(function($scholarship) {
                    if ($scholarship instanceof Scholarship) {
                        return $scholarship->getScholarshipId();
                    }

                    return $scholarship;
                }, $scholarships),

            ],
            [
                'account' => \PDO::PARAM_INT,
                'scholarships' => Connection::PARAM_INT_ARRAY
            ]
        );

        foreach ($statement->fetchAll(\PDO::FETCH_UNIQUE) as $scholarshipId => $item) {
            $derivedStatus = $this->getDerivedStatus($item);
            $results[$scholarshipId]['derivedStatus'] = $derivedStatus;

            if (isset($item['req']) && isset($item['app'])) {
                $applicationStatus = $item['application_status_id'] ?? false;
                $requirementApplications = (int) $item['app'];
                $requirements = (int) $item['req'];

                if ($applicationStatus && $applicationStatus !== ApplicationStatus::NEED_MORE_INFO) {
                    $results[$scholarshipId]['status'] = Scholarship::APPLICATION_STATUS_SUBMITTED;
                } else if ($requirementApplications === $requirements) {
                    $results[$scholarshipId]['status'] = Scholarship::APPLICATION_STATUS_READY_TO_SUBMIT;
                } else if ($requirementApplications === 0) {
                    $results[$scholarshipId]['status'] = Scholarship::APPLICATION_STATUS_INCOMPLETE;
                } else {
                    $results[$scholarshipId]['status'] = Scholarship::APPLICATION_STATUS_IN_PROGRESS;
                }
            }
        }

        return $results;
    }

    /**
     * Derive a status based on application and scholarship statuses
     *
     * @param Application $application
     * @return string
     */
    public function getApplicationDerivedStatus(Application $application)
    {
        $scholarship = $application->getScholarship();

        $item = [
            'application_external_status' => $application->getExternalStatus(),
            'application_status_id' => $application->getApplicationStatus() ?
                 $application->getApplicationStatus()->getId() : null,
            'status' => $scholarship->getStatus()->getId(),
            'transitional_status' => $scholarship->getTransitionalStatus()
        ];

        return $this->getDerivedStatus($item);
    }

    protected function getDerivedStatus(array $scholarshipItem)
    {
        $applicationExternalStatus = $scholarshipItem['application_external_status'];
        $isExpired = $scholarshipItem['status'] == ScholarshipStatus::EXPIRED;
        $transitionalStatus = $scholarshipItem['transitional_status'];

        if ($isExpired) {
            if ($transitionalStatus == Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER) {
                if ($applicationExternalStatus == Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER) {
                    $derivedStatus = Scholarship::DERIVED_STATUS_MISSED;
                } else {
                    $derivedStatus = Scholarship::DERIVED_STATUS_CHOOSING;
                }
            } else if ($transitionalStatus == Scholarship::TRANSITIONAL_STATUS_POTENTIAL_WINNER) {
                if ($applicationExternalStatus == Application::EXTERNAL_STATUS_POTENTIAL_WINNER) {
                    $derivedStatus = Scholarship::DERIVED_STATUS_WON;
                } else if ($applicationExternalStatus == Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER) {
                    $derivedStatus = Scholarship::DERIVED_STATUS_MISSED;
                } else {
                    $derivedStatus = Scholarship::DERIVED_STATUS_CHOOSING;
                }
            } else if ($transitionalStatus == Scholarship::TRANSITIONAL_STATUS_FINAL_WINNER) {
                if ($applicationExternalStatus == Application::EXTERNAL_STATUS_PROVED_WINNER) {
                    $derivedStatus = Scholarship::DERIVED_STATUS_AWARDED;
                } else if ($applicationExternalStatus == Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER) {
                    $derivedStatus = Scholarship::DERIVED_STATUS_MISSED;
                } else {
                    $derivedStatus = Scholarship::DERIVED_STATUS_CHOSEN;
                }
            } else {
                $derivedStatus = Scholarship::DERIVED_STATUS_DRAW_CLOSED;
            }
        } else {
            if (is_null($scholarshipItem['application_status_id'])) {
                $derivedStatus =  '';
            } else if ($applicationExternalStatus == Application::EXTERNAL_STATUS_ACCEPTED) {
                $derivedStatus = Scholarship::DERIVED_STATUS_ACCEPTED;
            } else if ($applicationExternalStatus == Application::EXTERNAL_STATUS_DECLINED) {
                $derivedStatus = Scholarship::DERIVED_STATUS_DECLINED;
            } else {
                $derivedStatus = Scholarship::DERIVED_STATUS_SENT;
            }
        }

        return $derivedStatus;
    }

    /**
     * @param array $scholarships
     *
     * @return array
     */
    public function isPublished(array $scholarships)
    {
        $results = [];
        foreach ($scholarships as $scholarship) {
            $results[$scholarship] = false;
        }

        $qb = static::publishedScholarships($this->createQueryBuilder('s'))
            ->select('s.scholarshipId')
            ->andWhere('s.scholarshipId IN (:scholarships)')
            ->setParameter('scholarships', $scholarships);

        foreach ($qb->getQuery()->getArrayResult() as $result) {
            $results[$result['scholarshipId']] = true;
        }

        return $results;
    }

    /**
     * @param array $ids
     *
     * @return array|Scholarship[]
     */
    public function findByIds(array $ids)
    {
        return $this->findBy(['scholarshipId' => $ids]);
    }

    /**
     * @param string $email
     *
     * @return null|Scholarship
     */
    public function findByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @return null|Scholarship
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findYouDeserveItScholarship()
    {
        $cache = \Cache::tags(self::CACHE_YDI_SCHOLARSHIPS);
        $key = "ydi-scholarship";

        if (null === $ydiScholarhips = $cache->get($key)) {

            $ydiScholarhips = static::publishedScholarships($this->createQueryBuilder('s'))
                ->andWhere('s.isAutomatic = true')
                ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult();

            $cache->put($key, $ydiScholarhips, 60 * 60);
        }

        return $ydiScholarhips;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function findLatestScholarships($limit = 5)
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.scholarshipId', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findActiveScholarshipsIds()
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::publishedScholarships($queryBuilder);
        $queryBuilder->select('s.scholarshipId');

        return array_map('current', $queryBuilder->getQuery()->getScalarResult());
    }

    /**
     * @param Account $account
     *
     * @return ArrayCollection|Scholarship[]
     */
    public function findEligibleScholarships(Account $account)
    {
        return new ArrayCollection($this->findByIds($this->findEligibleNotAppliedScholarshipsIds($account)));
    }

    /**
     * @param Account $account
     *
     * @return int
     */
    public function countEligibleScholarships(Account $account)
    {
        return count($this->findEligibleNotAppliedScholarshipsIds($account));
    }

    /**
     * @param array[int]|Account $ids
     *
     * @return int
     */
    public function sumEligibleScholarships($ids)
    {
        if ($ids instanceof Account) {
            if (!isset(static::$sum[$ids->getAccountId()])) {
                static::$sum[$ids->getAccountId()] = $this->sumEligibleScholarships(
                    $this->findEligibleNotAppliedScholarshipsIds($ids)
                );
            }

            return static::$sum[$ids->getAccountId()];
        }

        return $this->createQueryBuilder('s')
            ->select(['SUM(s.amount)'])
            ->where('s.scholarshipId IN (:scholarships)')
            ->setParameter('scholarships', $ids)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Account|int $account
     *
     * @return array
     */
    public function findEligibleNotAppliedScholarshipsIds($account)
    {
        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app(EligibilityCacheService::class);
        $accountId = $account instanceof Account ? $account->getAccountId() : $account;

        // eligibility cache already contains only not applied scholarships;
        return $elbCacheService->getAccountEligibleScholarshipIds($accountId);
    }


    /**
     * @param int $accountId
     * @param bool $skipApplied
     * @return array|mixed
     */
    public function getActiveScholarshipIdsForAccount(int $accountId, $skipApplied = true) {

        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::publishedScholarships($queryBuilder);
        $queryBuilder->andWhere('DATE(s.expirationDate) >= DATE(NOW())');

        if ($skipApplied) {
            $queryBuilder = static::notApplied($queryBuilder, $accountId);
        }
        $queryBuilder->select('s.scholarshipId');

        $activeScholarshipIds = array_column(
            $queryBuilder->getQuery()->setCacheable(true)->getScalarResult(), 'scholarshipId'
        );

        return $activeScholarshipIds;
    }

//    /**
//     * @param Account|int $account
//     *
//     * @return array
//     */
//    public function findEligibleScholarshipsIds($account)
//    {
//        $accountId = $account instanceof Account ? $account->getAccountId() : $account;
//
//        /** @var EligibilityCacheService $elbCacheService */
//        $elbCacheService = app(EligibilityCacheService::class);
//
//        return $elbCacheService->getAccountEligibleScholarshipIds($accountId);
//    }

    /**
     * @param Account $account
     * @param Int $expiresIn
     *
     * @return ArrayCollection
     */
    public function findExpiringRecurringScholarships(Account $account, $expiresIn = 7, $onlySucceeded = false)
    {
        $eligibleScholarships = $this->findEligibleNotAppliedScholarshipsIds($account);

        $qb = static::publishedScholarships($this->createQueryBuilder('s'));
        $qb->select(['s.scholarshipId', 's.expirationDate', 's.timezone']);
        $qb->join(Application::class, 'a', Join::WITH, 's.parentScholarship = a.scholarship and s.parentScholarship IS NOT NULL');
        $qb->andWhere('s.isRecurrent = true');
        $qb->andWhere('s.scholarshipId IN (:eligible)');
        $qb->andWhere('a.account = :account');
        if ($onlySucceeded) {
            $qb->andWhere('a.applicationStatus IN (:statuses)');
            $qb->setParameter('statuses', [ApplicationStatus::SUCCESS, ApplicationStatus::PENDING]);
        }
        $qb->setParameter('eligible', $eligibleScholarships);
        $qb->setParameter('account', $account);


        $checkDate = Carbon::now()->addDay($expiresIn);

        $filterResult = function(array $items) use ($checkDate) {
            return array_map(
                function($scholarship) {
                    return $scholarship['scholarshipId'];
                },
                array_filter(
                    $items,
                    function($scholarship) use ($checkDate) {
                        if ($scholarship['expirationDate'] instanceof \DateTime) {
                            $timezone = new \DateTimeZone($scholarship['timezone']);
                            $expires = $scholarship['expirationDate']->setTimezone($timezone);

                            if ($checkDate->setTimezone($timezone) >= $expires) {
                                return true;
                            }
                        }

                        return false;
                    }
                )
            );
        };

        $expiringSowl = $filterResult($qb->getQuery()->getResult());

        $qb->resetDQLPart('join');
        $qb->join(
            Application::class, 'a', Join::WITH, 's.externalScholarshipTemplateId = a.externalScholarshipTemplateId'
        );

        $expiringSunrise = $filterResult($qb->getQuery()->getResult());

        $expiring = array_unique(array_merge($expiringSowl, $expiringSunrise));

        return new ArrayCollection($this->findByIds($expiring));
    }

    /**
     * @param Account $account
     *
     * @return ArrayCollection|Scholarship[]
     */
    public function findAutomaticScholarships(Account $account)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::publishedScholarships($queryBuilder);
        $queryBuilder = static::notApplied($queryBuilder, $account)
            ->andWhere('s.isAutomatic = true');

        $automatic = array_map('current', $queryBuilder->select('s.scholarshipId')->getQuery()->getScalarResult());

        /** @var ScholarshipService $service */
        $service = app(ScholarshipService::class);
        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app(EligibilityCacheService::class);

        return new ArrayCollection(
            count($automatic) ? $this->findByIds($elbCacheService->getAccountEligibleScholarshipIds($account->getAccountId(), $automatic)) : []
        );
    }

    /**
     * @param Account $account
     * @param array   $ids
     *
     * @return array|Scholarship[]
     */
    public function findFreeScholarships(Account $account, array $ids = [])
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::publishedScholarships($queryBuilder);
        $queryBuilder = static::notApplied($queryBuilder, $account)
            ->andWhere('s.isFree = true');

        if (!empty($ids)) $queryBuilder->andWhere('s.scholarshipId IN (:ids)')->setParameter('ids', $ids);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Return ready to submit scholarships for user.
     *
     * @param array $ids
     * @param Account $account
     *
     * @return ArrayCollection|Scholarship[]
     */
    public function findReadyToSubmitScholarships(Account $account, array $ids = null)
    {
        $qb = $this->createQueryBuilder('s');
        $qb = static::withApplications($qb, $account);
        $qb = static::withApplicationRequirements($qb, $account)
            ->andWhere('s.scholarshipId IN (:eligibleScholarships)')
            ->setParameter('eligibleScholarships', $this->findEligibleNotAppliedScholarshipsIds($account));

        if ($ids !== null) {
            $qb->andWhere('s.scholarshipId IN (:filterIds)')->setParameter('filterIds', $ids);
        }

        /** @var ArrayCollection $scholarships */
        $scholarships = new ArrayCollection(
            $qb->getQuery()
                ->setHint(Query::HINT_REFRESH, true)
                ->getResult()
        );

        $scholarships = $scholarships->filter(function (Scholarship $scholarship) use ($account) {
            return !$scholarship->checkExpired() &&
                ($scholarship->getFinishedRequirements($account)->count() === $scholarship->getRequirements()->count());
        });

        if (!$account->isMember()) {
            $scholarships = $scholarships->filter(function (Scholarship $scholarship) {
                return $scholarship->getIsFree();
            });
        }

        return $scholarships;
    }

    /**
     * @param Scholarship $scholarship
     * @param string      $formField
     *
     * @return Form
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findFormField(Scholarship $scholarship, string $formField)
    {
        return $this->_em->createQueryBuilder()
            ->select('f')
            ->from(Form::class, 'f')
            ->where('f.scholarship = :scholarship AND f.formField = :formField')
            ->setParameter('scholarship', $scholarship)
            ->setParameter('formField', $formField)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param \DateTime|null $now
     *
     * @return array
     */
    public function findExpiredRecurrentScholarships(\DateTime $now = null)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isRecurrent = true AND s.isActive = true AND s.currentScholarship IS NULL')
            ->andWhere('DATE(s.expirationDate) < DATE(:now)')
            ->setParameter('now', $now ?: new \DateTime())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $days
     *
     * @return array|int[]
     */
    public function findExpiringScholarshipsIds(int $days = 7)
    {
        return array_map(
            'current',
            static::publishedScholarships($this->createQueryBuilder('s'))
                ->select('s.scholarshipId')
                ->andWhere('DATE(s.expirationDate) <= DATE(:expire)')
                ->setParameter('expire', Carbon::now()->addDays($days))
                ->getQuery()
                ->getScalarResult()
        );
    }

    /**
     * @param int $days
     *
     * @return array|int[]
     */
    public function findNewScholarshipIds(int $days = 7)
    {
        return array_map(
            'current',
            static::publishedScholarships($this->createQueryBuilder('s'))
                ->select('s.scholarshipId')
                ->andWhere('DATE(s.startDate) >= DATE(:start)')
                ->andWhere('DATE(s.startDate) < DATE(:to)')
                ->setParameter('start', Carbon::now()->subDays($days))
                ->setParameter('to', Carbon::now()->addDays(1))
                ->getQuery()
                ->getScalarResult()
        );
    }

    /**
     * @param Scholarship         $scholarship
     * @param RequirementContract $requirement
     *
     * @return ApplicationRequirementContract[]
     */
    public function findApplicationRequirements(Scholarship $scholarship, RequirementContract $requirement)
    {
        return $this->_em->createQueryBuilder()
            ->select('ar')
            ->from($requirement->getApplicationClass(), 'ar')
            ->where('ar.scholarship = :scholarship AND ar.requirement = :requirement')
            ->setParameter('scholarship', $scholarship)
            ->setParameter('requirement', $requirement)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Scholarship $scholarship
     * @param RequirementContract $requirement
     * @return mixed
     */
    public function findSunriseApplicationRequirements(Scholarship $scholarship, RequirementContract $requirement)
    {
        return $this->_em->createQueryBuilder()
            ->select('ar')
            ->from($requirement->getApplicationClass(), 'ar')
            ->innerJoin(get_class($requirement), 'sr', Join::WITH, 'sr.externalIdPermanent = :permanentId')
            ->where('ar.scholarship = :scholarship AND ar.requirement = :requirement')
            ->setParameter('scholarship', $scholarship)
            ->setParameter('permanentId', $requirement->getExternalIdPermanent())
            ->setParameter('requirement', $requirement)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryIterator
     */
    public function queryIteratorActive()
    {
        $qb = $this->createQueryBuilder('s');
        $qb = static::publishedScholarships($qb);

        return QueryIterator::create($qb->getQuery());
    }

    /**
     * @param $date
     *
     * @return array
     */
    public function findActiveScholarshipsIdsByStartDate($date)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::publishedScholarships($queryBuilder);
        $queryBuilder->select('s.scholarshipId');
        $queryBuilder->where('s.startDate >= :date');
        $queryBuilder->setParameters(['date' => $date]);

        return array_map('current', $queryBuilder->getQuery()->getScalarResult());
    }

    /**
     * @return array
     */
    public function totalPriceOfActiveAndNotExpired()
    {
        $cacheKey = 'scholarships:price_of_active_and_not_expired';

        $result = \Cache::tags([Scholarship::cacheTagGeneral()])->get($cacheKey, function() use($cacheKey) {
            $queryBuilder = $this->createQueryBuilder('s');
            $queryBuilder = static::publishedScholarships($queryBuilder);
            $queryBuilder->select('SUM(s.amount)');

            $result =  $queryBuilder->getQuery()->getSingleScalarResult();

            \Cache::tags([Scholarship::cacheTagGeneral()])
                ->put($cacheKey, $result, 60*60*24);

            return $result;
        });


        return $result;
    }

    /**
     * @return array
     */
    public function totalPriceOfActiveAndNotExpiredPerMonth()
    {
        $cacheKey = 'scholarships:price_of_active_and_not_expired_per_month';

        $result = \Cache::tags([Scholarship::cacheTagGeneral()])->get($cacheKey, function() use($cacheKey) {
            $queryBuilder = $this->createQueryBuilder('s');
            $queryBuilder = static::publishedScholarships($queryBuilder)
                ->select('SUM(s.amount)')
                ->andWhere('s.startDate >= :start')
                ->andWhere('s.startDate <= :end')
                ->setParameter('start',  (new Carbon('first day of last month'))->startOfMonth())
                ->setParameter('end',  (new Carbon('last day of last month'))->endOfMonth());

            $result =  $queryBuilder->getQuery()->getSingleScalarResult();

            \Cache::tags([Scholarship::cacheTagGeneral()])
                ->put($cacheKey, $result, 60*60*24);

            return $result;
        });


        return $result;
    }

    /**
     * Get current active Sunrise scholarship based on externalScholarshipTemplateId
     * @param Scholarship $scholarship
     * @return array
     */
    public function getCurrentScholarshipForSunrise(Scholarship $scholarship)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = static::publishedScholarships($queryBuilder)
            ->andWhere('s.externalScholarshipTemplateId = :externalScholarshipTemplateId')
            ->setParameter('externalScholarshipTemplateId', $scholarship->getExternalScholarshipTemplateId());

        return $queryBuilder->getQuery()->getResult();
    }
}
