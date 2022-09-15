<?php

namespace App\Services;

use App\Entity\Account;
use App\Entity\Eligibility;
use App\Entity\EligibilityCache;
use App\Entity\Field;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Jobs\EligibilityCacheAddScholarshipJob;
use App\Jobs\EligibilityCacheRemoveScholarshipJob;
use Carbon\Carbon;
use function Clue\StreamFilter\fun;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Join;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class EligibilityCacheService
{
    const FETCH_AS_OBJECT = 0;
    const FETCH_AS_ARRAY = 1;
    const FETCH_AS_FULL_ARRAY = 2;

    const BATCH_SIZE_ADD = 3000;
    const BATCH_SIZE_REMOVE = 3000;

    const ACTIVE_ACCOUNT_DAYS = 14;

    /**
     * @var EligibilityCacheRepository
     */
    protected $repo;

    /**
     * @var EligibilityService
     */
    protected $elbService;

    /**
     * @var EntityManager
     */
    protected $em;

    protected $fieldsMap = [
        Field::EMAIL =>  'a.email',
        Field::FIRST_NAME =>'p.first_name',
        Field::LAST_NAME => 'p.last_name',
        Field::FULL_NAME => 'CONCAT_WS(" ", p.first_name, p.last_name)',
        Field::GENDER => 'p.gender',
        Field::CITIZENSHIP =>'p.citizenship_id',
        Field::ETHNICITY =>'p.ethnicity_id',
        Field::COUNTRY => 'p.country_id',
        Field::STATE => 'p.state_id',
        Field::CITY => 'p.city',
        Field::ADDRESS => 'p.address',
        Field::ZIP => 'p.zip',
        Field::SCHOOL_LEVEL =>'p.school_level_id',
        Field::DEGREE => 'p.degree_id',
        Field::DEGREE_TYPE =>'p.degree_type_id',
        Field::ENROLLMENT_YEAR =>'p.enrollment_year',
        Field::ENROLLMENT_MONTH => 'p.enrollment_month',
        Field::GPA => 'p.gpa',
        Field::CAREER_GOAL => 'p.career_goal_id',
        Field::STUDY_ONLINE => 'p.study_online',
        Field::HIGH_SCHOOL_NAME => 'p.highschool',
        Field::COLLEGE_GRADUATION_YEAR => 'p.graduation_year',
        Field::COLLEGE_GRADUATION_MONTH => 'p.graduation_month',
        Field::COLLEGE_NAME => 'p.university',
        Field::MILITARY_AFFILIATION =>'p.military_affiliation_id',
        Field::PHONE => 'p.phone',
        Field::PHONE_AREA_CODE => 'SUBSTRING(p.phone, 2, 3)',
        Field::PHONE_PREFIX => 'SUBSTRING(p.phone, 7, 3)',
        Field::PHONE_LOCAL => 'SUBSTRING(p.phone, 13, 4)',
        Field::DATE_OF_BIRTH =>'DATE(p.date_of_birth)',
        Field::AGE =>'TIMESTAMPDIFF(YEAR,DATE(p.date_of_birth),CURRENT_TIMESTAMP())',
        Field::BIRTHDAY_YEAR => 'YEAR(p.date_of_birth)',
        Field::BIRTHDAY_MONTH => 'MONTH(p.date_of_birth)',
        Field::BIRTHDAY_DAY => 'DAY(p.date_of_birth)',
        Field::STATE_FREE_TEXT => 'p.state_name',
        Field::COUNTRY_OF_STUDY => [
            'p.study_country1',
            'p.study_country2',
            'p.study_country3',
            'p.study_country4',
            'p.study_country5',
        ],
        Field::ENROLLED => 'p.enrolled',
        Field::HIGH_SCHOOL_ADDRESS => ['p.highschool_address1', 'p.highschool_address2'],
        Field::COLLEGE_ADDRESS => ['p.university_address1', 'p.university_address2']
    ];

    /**
     * @var ScholarshipRepository
     */
    protected $scholarshipRepo;

    public function __construct(EligibilityService $es, EntityManager $em)
    {
        $this->elbService = $es;
        $this->em = $em;
        $this->scholarshipRepo = $em->getRepository(Scholarship::class);
        $this->repo = $this->em->getRepository(EligibilityCache::class);
    }

    /**
     * Adds a scholarship to eligible account and removes from not eligible ones.
     *
     * @param Scholarship $scholarship
     */
    public function rotateScholarship(Scholarship $scholarship)
    {
        $data = $this->addToEligibilityCache($scholarship);
        $eligibleAccountIds = $data['accountIdsAll'];
        $procHash = $data['procHash'];
        $delaySeconds = $this->calcDelay(count($eligibleAccountIds));
        $this->removeFromEligibilityCache($scholarship, $eligibleAccountIds, $procHash, $delaySeconds);
    }

    /**
     * @param Scholarship $scholarship
     * @param array $exceptAccountIds
     * @param string|null $procHash
     * @param int $delay
     */
    public function removeFromEligibilityCache(
        Scholarship $scholarship,
        array $exceptAccountIds = [],
        string $procHash = null,
        int $delay = 0
    )
    {
        if (!$procHash) {
            $procHash = $this->generateProcHash();
        }

        $batchSize = self::BATCH_SIZE_REMOVE;
        $iterator = $this->repo->fetchAccountIds($batchSize, 0, null, $exceptAccountIds, $scholarship->getScholarshipId());
        $i = 0;
        $finished = false;
        foreach ($iterator as $accountIds) {
            $accountIds = $accountIds ?? [];

            $progress = EligibilityCacheRemoveScholarshipJob::STAT_PROCESS_WORK;
            if ($i === 0) {
                $progress = EligibilityCacheRemoveScholarshipJob::STAT_PROCESS_START;
            } else if (count($accountIds) < $batchSize) {
                $progress = EligibilityCacheRemoveScholarshipJob::STAT_PROCESS_FINISH;
                $finished = true;
                $delay += 5; // + 5 to be sure it will be a trailing log record
            }

            EligibilityCacheRemoveScholarshipJob::dispatch(
                $scholarship, $accountIds, $procHash, now()->addSecond($delay), $progress
            );

            $i++;
        }

        if (!$finished) {
            $progress = EligibilityCacheRemoveScholarshipJob::STAT_PROCESS_FINISH;
            EligibilityCacheRemoveScholarshipJob::dispatch(
                $scholarship, [], $procHash, now()->addSecond($delay + 5), $progress
            );
        }
    }

    /**
     * @param Scholarship $scholarship
     * @return array Scholarship eligible account ids and procHash ['accountIdsAll', 'procHash']
     */
    public function addToEligibilityCache(Scholarship $scholarship)
    {
        $isPublished = (int)$scholarship->getStatus()->getId() === ScholarshipStatus::PUBLISHED;
        $isActive = (int)$scholarship->isActive() === 1;

        $procHash = $this->generateProcHash();
        $accountIdsAll = [];

        if (!$isPublished || !$isActive) {
            return compact('accountIdsAll', 'procHash');
        }

        // fetch all ids at once
        $iterator = $this->fetchScholarshipEligibleAccountIds($scholarship, 9e6);
        $accountIdsAll = iterator_to_array($iterator)[0];
        $start = microtime(true);
        $totalCount = count($accountIdsAll);

        $cacheCountKey = sprintf(EligibilityCacheAddScholarshipJob::STAT_COUNT_KEY, $procHash);
        $cacheTimeKey = sprintf(EligibilityCacheAddScholarshipJob::STAT_START_TIME_KEY, $procHash);

        $this->repo->getCacheSore()->put($cacheCountKey, $totalCount, 600);
        $this->repo->getCacheSore()->put($cacheTimeKey, $start, 600);

        \Log::debug(
            sprintf(
                '[ %s ] Started adding scholarship [ %s ] to Eligibility cache for [ %s ] accounts',
                $procHash,
                $scholarship->getScholarshipId(),
                $totalCount
            )
        );

        $batchSize = self::BATCH_SIZE_ADD;
        for ($i = 0; $i < $totalCount; $i += $batchSize) {
            $accountIds = array_slice($accountIdsAll, $i, $batchSize);
            if (count($accountIds)) {
                EligibilityCacheAddScholarshipJob::dispatch($scholarship, $accountIds, $procHash);
            }
        }

        return compact('accountIdsAll', 'procHash');
    }

    /**
     * @param array $accountIds
     * @param array $targetScholarshipIds
     * @param null|array|EligibilityCache[] $elbCacheItems If eligibility cache for target accounts
     * was already retrieved before it might be provided here to avoid unnecessary db query
     * @param bool $doNotPersistIfNotExists If eligibility record does not exist for a user we create it.
     * If FALSE a not persisted entity will be created and returned.
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getEligibleScholarshipIds(
        array $accountIds,
        array $targetScholarshipIds = [],
        array $elbCacheItems = null,
        bool $doNotPersistIfNotExists = false
    ): array
    {
        $result = [];

        // if it's only one account we try to fetch data from cache
        if (count($accountIds) === 1 && is_null($elbCacheItems)) {
            $result[$accountIds[0]] = $this->getAccountEligibleScholarshipIds($accountIds[0], $targetScholarshipIds);

            return $result;
        }

        if (is_null($elbCacheItems)) {
            $elbCacheItems = $this->fetchEligibilityCacheItems($accountIds, $doNotPersistIfNotExists);
        }

        /** @var EligibilityCache $item */
        foreach ($elbCacheItems as $item) {
            if ($targetScholarshipIds) {
                $ids = array_intersect($item->getEligibleScholarshipIds(), $targetScholarshipIds);
            } else {
                $ids = $item->getEligibleScholarshipIds();
            }

            $result[$item->getAccount()->getAccountId()] = $ids;
        }

        return $result;
    }

    /**
     * Gtt account eligible ids. This method must be used whenever it's pertinent since it cashes the result.
     *
     * @param int $accountId
     * @param array $targetScholarshipIds
     * @return array
     */
    public function getAccountEligibleScholarshipIds(int $accountId, array $targetScholarshipIds = []): array
    {
        $data = $this->getAccountEligibilityCache($accountId, self::FETCH_AS_ARRAY);
        $ids = $data['eligibleScholarshipIds'];

        if ($targetScholarshipIds) {
            $ids = array_intersect($data['eligibleScholarshipIds'], $targetScholarshipIds);
        }

        return $ids;
    }

    /**
     * Eligible scholarship count for an account. Uses cache.
     *
     * @param int $accountId
     * @param array $targetScholarshipIds
     * @return int
     */
    public function getAccountEligibleCount(int $accountId, array $targetScholarshipIds = []): int
    {
        return count($this->getAccountEligibleScholarshipIds($accountId, $targetScholarshipIds));
    }

    /**
     * Eligible scholarships amount fo an account. Uses cache.
     *
     * @param int $accountId
     * @param array $targetScholarshipIds
     * @return int
     */
    public function getAccountEligibleAmount(int $accountId, array $targetScholarshipIds = []): int
    {
        $data = $this->getAccountEligibilityCache($accountId, self::FETCH_AS_FULL_ARRAY);

        if ($targetScholarshipIds) {
            $targetItems = array_intersect_key($data['eligibleScholarshipIds'], array_flip($targetScholarshipIds));
            $amount = array_sum($targetItems);
        } else {
            $amount = (int)array_sum($data['eligibleScholarshipIds']);
        }

        return $amount;
    }

    /**
     * @param array $accountIds
     * @param array $targetScholarshipIds
     * @return array
     */
    /**
     * @param array $accountIds
     * @param array $targetScholarshipIds
     * @param null|array|EligibilityCache[] $elbCacheItems If eligibility cache for target accounts
     * was already retrieved before it might be provided here to avoid unnecessary db query
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getEligibleCount(array $accountIds, array $targetScholarshipIds = [], array $elbCacheItems = null): array
    {
        $result = [];

        // if it's only one account we try to fetch data from cache
        if (count($accountIds) === 1 && is_null($elbCacheItems)) {
            $result[$accountIds[0]] = $this->getAccountEligibleCount($accountIds[0], $targetScholarshipIds);

            return $result;
        }

        if (is_null($elbCacheItems)) {
            $elbCacheItems = $this->fetchEligibilityCacheItems($accountIds);
        }

        /** @var EligibilityCache $item */
        foreach ($elbCacheItems as $item) {
           if ($targetScholarshipIds) {
                $dataItems = array_intersect_key(array_flip($item->getEligibleScholarshipIds()), array_flip($targetScholarshipIds));
                $count = count($dataItems);
           } else {
               $count = count($item->getEligibleScholarshipIds());
           }

           $result[$item->getAccount()->getAccountId()] = $count;
        }

        return $result;
    }

    /**
     * @param array $accountIds
     * @param array $targetScholarshipIds
     * @param anull|rray|EligibilityCache[] $elbCacheItems If eligibility cache for target accounts
     * was already retrieved before it might be provided here to avoid unnecessary db query
     * @param bool $castToFloat
     * @return array
     */
    public function getEligibleAmount(
        array $accountIds, array $targetScholarshipIds = [], array $elbCacheItems = null, bool $castToFloat = false
    ): array
    {
        $result = [];

        // if it's only one account we try to fetch data from cache
        if (count($accountIds) === 1 && is_null($elbCacheItems)) {
            $result[$accountIds[0]] = $this->getAccountEligibleAmount($accountIds[0], $targetScholarshipIds);

            return $result;
        }

        if (is_null($elbCacheItems)) {
            $elbCacheItems = $this->fetchEligibilityCacheItems($accountIds);
        }

        /** @var EligibilityCache $item */
        foreach ($elbCacheItems as $item) {
            if ($targetScholarshipIds) {
                $dataItems = array_intersect_key($item->getEligibleScholarshipIds(false), array_flip($targetScholarshipIds));
                $amount = array_sum($dataItems);
            } else {
                $amount = array_sum($item->getEligibleScholarshipIds(false));
            }

           $result[$item->getAccount()->getAccountId()] = $castToFloat? (float)$amount : $amount;
        }

        return $result;
    }

    /**
     * @param array $accountIds
     * @param bool $doNotPersistIfNotExists If eligibility record does not exist for a user we create it.
     * If FALSE a not persisted entity will be created and returned.
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function fetchEligibilityCacheItems(array $accountIds, bool $doNotPersistIfNotExists = false): array
    {
        $items = $this->repo->fetchAccountsEligibilityCache($accountIds);

        $result = [];
        /**
         * @var EligibilityCache $cacheItem
         */
        foreach ($items as $cacheItem) {
            $result[$cacheItem->getAccount()->getAccountId()] = $cacheItem;
        }

        $accountsWithoutEligibilityCache = array_diff($accountIds, array_keys($result));

        foreach ($accountsWithoutEligibilityCache as $accountId) {
            if ($doNotPersistIfNotExists) {
                $scholarshipsData = $this->elbService->fetchEligibleScholarshipsData($accountId);
                $result[$accountId] = $this->repo->getNotPersisted($scholarshipsData, $accountId);
            } else {
                $result[$accountId] = $this->updateAccountEligibilityCache($accountId);
            }
        }

        return $result;
    }

    /**
     * @param int $accountId
     * @param bool $create Cache item does not exist and it's an insert of a new item instead of update
     * @return EligibilityCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateAccountEligibilityCache(int $accountId, $create = false): EligibilityCache
    {
        $mutexKey = sprintf(EligibilityCacheRepository::MUTEX_CACHE_UPDATE_ACCOUNT, $accountId);
        $isCacheBeingUpdated = (bool)$this->repo->getCacheSore()->get($mutexKey);

        try {
            if ($isCacheBeingUpdated) {
                \Log::debug('Waiting for eligibility cache because of MUTEX');
                $item = $this->repo->fetchAccountEligibilityCache($accountId);

                if (!$item) {
                    $scholarshipsData = $this->elbService->fetchEligibleScholarshipsData($accountId);
                    $item = $this->repo->getNotPersisted($scholarshipsData, $accountId);
                    \Log::debug("Non persisted eligibility cache used for account [ $accountId ]");
                }
            } else {
                $this->repo->getCacheSore()->put($mutexKey, 1, 60);

                $scholarshipsData = $this->elbService->fetchEligibleScholarshipsData($accountId);
                $item = $this->repo->updateAccountEligibilityCache($scholarshipsData, $accountId, $create);

                $this->repo->getCacheSore()->forget($mutexKey);
            }
        } catch (\Exception $e) {
            $this->repo->getCacheSore()->forget($mutexKey);
            \Log::error($e);

            $scholarshipsData = $this->elbService->fetchEligibleScholarshipsData($accountId);
            $item = $this->repo->getNotPersisted($scholarshipsData, $accountId);
            \Log::debug("Non persisted eligibility cache used for account [ $accountId ] because on an exception");
        }

        return $item;
    }

    /**
     * @param int $accountId
     * @param array $scholarshipIds
     * @return EligibilityCache
     * @throws \Exception
     */
    public function updateAccountLastShownScholarships(int $accountId, array $scholarshipIds): EligibilityCache
    {
        $scholarshipsData = \DB::table('scholarship')
            ->select(['scholarship_id', \DB::raw('CAST(amount AS SIGNED) as amount')])
            ->whereIn('scholarship_id', array_unique($scholarshipIds))
            ->get();

        $item = $this->getAccountEligibilityCache($accountId);

        return $this->repo->updateLastShownScholarships($item, $scholarshipsData->toArray());
    }

    /**
     * Get EligibilityCache for an account. If it does not exist create a new one.
     * If data is fetched in self::FETCH_AS_ARRAY or self::FETCH_AS_FULL_ARRAY the result is taken from cache.
     *
     * @param int $accountId
     * @return EligibilityCache|array
     */
    public function getAccountEligibilityCache(int $accountId, int $fetchMode = self::FETCH_AS_OBJECT)
    {
        $fetchItem = function() use($accountId) {
            /** @var Eligibility|null $item */
            $item = $this->repo->fetchAccountEligibilityCache($accountId);

            /**
             * generate new eligibility cache for an account
             */
            if (is_null($item)) {
                $item = $this->updateAccountEligibilityCache($accountId, true);
            }

            return $item;
        };

        if ($fetchMode === self::FETCH_AS_OBJECT) {
            $item = $fetchItem();
        } else {
            $cacheKey = sprintf(EligibilityCache::CACHE_KEY_ACCOUNT, $accountId);
            if (!$item = $this->repo->getCacheSore()->get($cacheKey)) {
                $item = $fetchItem();
                $item = $item->toArray(true);
                $this->repo->getCacheSore()->put($cacheKey, $item, 60 * 60 * 24 * 7);
            }

            if ($fetchMode !== self::FETCH_AS_FULL_ARRAY) {
                $item['eligibleScholarshipIds'] = array_keys($item['eligibleScholarshipIds']);
                $item['lastShownScholarshipIds'] = array_keys($item['lastShownScholarshipIds']);
            }
        }

        return $item;
    }

    /**
     * @param Scholarship $scholarship
     * @param int $batchSize Rows per iteration
     * @param int $maxResults Maximum rows to fetch
     * @return \Generator
     */
    public function fetchScholarshipEligibleAccountIds(
        Scholarship $scholarship, int $batchSize = 1000, int $maxResults = null, $filterOutWithoutCache = true
    ): \Generator
    {
        $whereClause = $this->generateWherePart($scholarship);

        $activeSubscriptionStatus = SubscriptionStatus::ACTIVE;
        $today = Carbon::instance(new \DateTime())->format('Y-m-d');

        $sql = "
            select distinct(a.account_id)
            from account a
            inner join profile p on p.account_id = a.account_id
        ";

        if ($filterOutWithoutCache) {
            $sql .= "
                inner join eligibility_cache ec on ec.account_id = a.account_id       
            ";
        }

        $sql .= "
            where 1 {$whereClause}
            order by account_id DESC
            limit %s,%s        
        ";

        $offset = 0;
        $isLastIteration = false;

        do {
            if (!is_null($maxResults) && $batchSize > $maxResults) {
                $batchSize = $maxResults;
                $isLastIteration = true;
            }

            $query = sprintf($sql, $offset, $batchSize);
            $result = \DB::select(\DB::raw($query));

            $offset += $batchSize;

            $accountIds = array_column($result, 'account_id');

            yield $accountIds;

            if ($isLastIteration || count($accountIds) < $batchSize) {
                break;
            }
        } while($result);
    }

    /**
     * @param Scholarship $scholarship
     * @return string
     */
    protected function generateWherePart(Scholarship $scholarship): string
    {
        $eligibilities = $scholarship->getEligibilities();
        $expr = $this->em->getExpressionBuilder();

        $eligibilitiesWhere = [];

        foreach ($eligibilities as $eligibility) {
            $comparisonType = $eligibility->getType();

            $elbField = $eligibility->getField();

            if (isset($this->fieldsMap[$elbField->getId()])) {
                $elbValue = is_string($eligibility->getValue()) ?
                    sprintf("'%s'", $eligibility->getValue()) : $eligibility->getValue();

                $dbFields = (array)$this->fieldsMap[$elbField->getId()];

                $cases = [];
                foreach ($dbFields as $fieldName) {
                    switch ($comparisonType) {
                        case Eligibility::TYPE_NIN:
                            $cases[] = $expr->eq("FIND_IN_SET({$fieldName}, {$elbValue})", 0);
                            break;
                        case Eligibility::TYPE_IN:
                            $cases[] = $expr->gt("FIND_IN_SET({$fieldName}, {$elbValue})", 0);
                            break;
                        case Eligibility::TYPE_BETWEEN:
                            // something like this: SELECT 7 BETWEEN LEFT('5,8', LOCATE(',', '5,8')-1) AND SUBSTRING('5,8' FROM LOCATE(',', '5,8')+1) > 0
                            // will produce 7 BETWEEN 5 AND 8 > 0
                            $cases[] = $expr->gt("{$fieldName} BETWEEN LEFT({$elbValue}, LOCATE(',', {$elbValue})-1) AND SUBSTRING({$elbValue} FROM LOCATE(',', {$elbValue})+1)", 0);
                            break;
                        case Eligibility::TYPE_BOOL:
                            $cases[] = $expr->eq("COALESCE({$fieldName}, 0)", $elbValue);
                            break;
                        case Eligibility::TYPE_REQUIRED:
                            $cases[] = $expr->isNotNull($fieldName) .' and '. $expr->neq($fieldName, "''");
                            break;
                        case Eligibility::TYPE_VALUE:
                            $cases[] = $expr->eq($fieldName, $elbValue);
                            break;
                        case Eligibility::TYPE_NOT:
                            $cases[] = $expr->neq($fieldName, $elbValue);
                            break;
                        case Eligibility::TYPE_LESS_THAN:
                            $cases[] = $expr->lt($fieldName, $elbValue);
                            break;
                        case Eligibility::TYPE_LESS_THAN_OR_EQUAL:
                            $cases[] = $expr->lte($fieldName, $elbValue);
                            break;
                        case Eligibility::TYPE_GREATER_THAN:
                            $cases[] = $expr->gt($fieldName, $elbValue);
                            break;
                        case Eligibility::TYPE_GREATER_THAN_OR_EQUAL:
                            $cases[] = $expr->gte($fieldName, $elbValue);
                            break;
                        default:
                            $cases[] = $expr->isNotNull($fieldName);
                            \Log::error("Unexpected comparison type [ {$comparisonType} ]");
                    }
                }

                $eligibilitiesWhere[] = '('.$expr->andX(new Expr\Orx($cases))->__toString().')';
            }
        }

        return empty($eligibilitiesWhere) ? '' : ' and '. implode(' and ', $eligibilitiesWhere);
    }

    /**
     * Calculates time in seconds needed to delay execution of the next batch update operation for the purpose of mitigation
     * overlapping queries and to avoid database locks.
     *
     * @param int $targetItemsCount
     */
    protected function calcDelay(int $targetItemsCount)
    {
        // 700 - it's approximate amount of accounts to which a scholarship added/deleted in 1 second.
        $delaySeconds = ceil(($targetItemsCount ?: 1)/700);

        return $delaySeconds;
    }

    /**
     * @return string
     */
    protected function generateProcHash()
    {
        return  md5(microtime(true) + rand(0, 9e6));
    }
}
