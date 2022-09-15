<?php

namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\Eligibility;
use App\Entity\EligibilityCache;
use App\Entity\Log\LoginHistory;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Events\Account\AccountEvent;
use App\Events\Account\ElbCachePurgedOnAccountUpdate;
use App\Events\Account\UpdateAccountEvent;
use App\Services\EligibilityCacheService;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query;
use Illuminate\Cache\Repository;
use Illuminate\Cache\TaggedCache;

class EligibilityCacheRepository extends EntityRepository
{
    /**
     * Any cache per account
     */
    const MUTEX_CACHE_UPDATE_ACCOUNT = 'elb-cache-updating-account-%s';

    /**
     * @var Repository
     */
    private $cacheStore;

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->cacheStore = \Cache::store('redisShared');
    }

    /**
     * @return Repository|\Illuminate\Contracts\Cache\Repository
     */
    public function getCacheSore()
    {
        return $this->cacheStore;
    }

    /**
     * @param array $accountIds
     * @return bool
     */
    public function purgeEligibilityCache(array $accountIds): bool
    {
        try {
             $this->getEntityManager()->getConnection()->executeUpdate(
                'delete from eligibility_cache where account_id in (:accountIds)',
                ['accountIds' => $accountIds],
                ['accountIds' => Connection::PARAM_INT_ARRAY]
            );

            foreach ($accountIds as $accountId) {
                $this->cacheStore->forget(
                    sprintf(EligibilityCache::CACHE_KEY_ACCOUNT, $accountId)
                );
            }
            $result = true;
        } catch (\Exception $e) {
            \Log::error($e);
            $result = false;
        }

        return $result;
    }

    /**
     * @param Scholarship $scholarship
     * @param array $accountIds
     * @return bool
     */
    public function removeScholarshipFromAccounts(Scholarship $scholarship, array $accountIds): bool
    {
        $jsonParameter = sprintf('$."%s"', $scholarship->getScholarshipId());

        $query = "
            update eligibility_cache
            set eligible_scholarship_ids = JSON_REMOVE(COALESCE(eligible_scholarship_ids, '{}'), :jsonParameter),
                updated_at = NOW()
            where account_id in (:accountIds);
         ";

        try {
             $this->getEntityManager()->getConnection()->executeUpdate(
                $query,
                [
                    'jsonParameter' => $jsonParameter,
                    'accountIds' => $accountIds,
                ],
                [
                    'jsonParameter' => \PDO::PARAM_STR,
                    'accountIds' => Connection::PARAM_INT_ARRAY,
                ]
            );

            foreach ($accountIds as $accountId) {
                $this->cacheStore->forget(
                    sprintf(EligibilityCache::CACHE_KEY_ACCOUNT, $accountId)
                );
            }

            $result = true;
        } catch (\Exception $e) {
            \Log::error($e);
            $result = false;
        }

        return $result;
    }

    /**
     * @param Scholarship $scholarship
     * @param array $accountIds
     * @return bool
     */
    public function addScholarshipToExistingItems(Scholarship $scholarship, array $accountIds): bool
    {
        $query = "
            update eligibility_cache
            set eligible_scholarship_ids = JSON_SET(COALESCE(eligible_scholarship_ids, '{}'), :jsonKey, :jsonValue),
                updated_at = NOW()
            where account_id in (:accountIds);
         ";

        try {
             $this->getEntityManager()->getConnection()->executeUpdate(
                $query,
                [
                    'jsonKey' => sprintf('$."%s"', $scholarship->getScholarshipId()),
                    'jsonValue' => (int)$scholarship->getAmount(),
                    'accountIds' => $accountIds,
                ],
                [
                    'jsonKey' => \PDO::PARAM_STR,
                    'jsonValue' => \PDO::PARAM_INT,
                    'accountIds' => Connection::PARAM_INT_ARRAY,
                ]
            );

            $result = true;
        } catch (\Exception $e) {
            \Log::error($e);
            $result = false;
        }

        foreach ($accountIds as $accountId) {
            $this->cacheStore->forget(
                sprintf(EligibilityCache::CACHE_KEY_ACCOUNT, $accountId)
            );
        }

        return $result;
    }

    /**
     * @param int $accountId
     * @return EligibilityCache|null
     */
    /**
     * @param int $accountId
     * @param bool $ignoreMutex TRUE - do not waite for MUTEX on account update to be released
     * @return EligibilityCache|null
     */
    public function fetchAccountEligibilityCache(int $accountId, bool $ignoreMutex = false): ?EligibilityCache
    {
        $mutexKey = sprintf(self::MUTEX_CACHE_UPDATE_ACCOUNT, $accountId);

        $fetch = function() use($accountId, $mutexKey, $ignoreMutex) {
            $isCacheBeingUpdated = (bool)(!$ignoreMutex && $this->cacheStore->get($mutexKey));
            if ($isCacheBeingUpdated) {
                $checkMutexInterval = 1e5;
                // wait in total for 5 seconds
                for ($i = 0; $i < 5e6; $i += $checkMutexInterval) {
                    usleep($checkMutexInterval);
                    $isCacheBeingUpdated = (bool)$this->cacheStore->get($mutexKey);
                    if (!$isCacheBeingUpdated) {
                        break;
                    }
                }
            }

            // we must clear Doctrine cache to force findOneBy get a fresh record from db, because Eligibility cache
            // is managed with plain db queries and Doctrine does not kow about changes
            $this->_em->clear(EligibilityCache::class);

            return $this->findOneBy(['account' => $accountId]);
        };

        /** @var EligibilityCache $item */
        $item = $fetch();

        return $item;
    }

    /**
     * @param array $accountIds
     * @return EligibilityCache[]
     */
    public function fetchAccountsEligibilityCache(array $accountIds): ?array
    {
        // we must clear Doctrine cache to force findBy get a fresh record from db, because Eligibility cache
        // is managed with plain db queries and Doctrine does not kow about changes
        $this->_em->clear(EligibilityCache::class);

        return $this->findBy(['account' => $accountIds]);
    }

    /**
     * @param EligibilityCache $item
     * @param array $scholarshipsData
     * @return EligibilityCache
     * @throws \Exception
     */
    public function updateLastShownScholarships(EligibilityCache $item, array $scholarshipsData): EligibilityCache
    {
        $scholarshipsJson = json_encode(
            array_combine(
                array_column($scholarshipsData, 'scholarship_id'),
                array_column($scholarshipsData, 'amount')
            )
        );

        // use plain SQL instead of Doctrine because $item might come here not persisted (if it's a new one)
        // but already saved to DB (because elb cache is managed with plain SQL queries)
        $query = "
            update eligibility_cache 
            set
                last_shown_scholarship_ids = :scholarshipsJson,
                updated_at =  NOW()
            where account_id = :accountId
        ";

        $this->getEntityManager()->getConnection()->executeUpdate(
            $query,
            [
                'scholarshipsJson' => $scholarshipsJson,
                'accountId' => $item->getAccount()->getAccountId(),
            ]
        );

        // we do not save here Doctrine entity because item might came here not persisted (without id)
        // which would cause account_id unique key constraint violation
        $item->setLastShownScholarshipIds($scholarshipsJson);
        $this->_em->persist($item);

        $cacheKey = sprintf(EligibilityCache::CACHE_KEY_ACCOUNT, $item->getAccount()->getAccountId());
        $this->cacheStore->put($cacheKey, $item->toArray(true), 60 * 60 * 24 * 7);

        return $item;
    }


    /**
     * Update or create new eligibilityCache record an Account
     *
     * @param array $scholarshipsData
     * @param int $accountId
     * @param bool $create Cache item does not exist and it's an insert of a new item instead of update
     * @return EligibilityCache
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateAccountEligibilityCache(array $scholarshipsData, int $accountId, $create = false): EligibilityCache
    {
        $isNew = false;
        if ($create || (!$eligibilityCache = $this->fetchAccountEligibilityCache($accountId, true))) {
            $isNew = true;
            $eligibilityCache = new EligibilityCache();
            $eligibilityCache->setAccount($accountId);
        }

        $scholarshipsJson = json_encode(
            array_combine(
                array_column($scholarshipsData, 'scholarship_id'),
                array_column($scholarshipsData, 'amount')
            ),
            JSON_FORCE_OBJECT
        );

        $eligibilityCache->setEligibleScholarshipIds($scholarshipsJson);

        if ($isNew) {
            // sometimes race condition happens and we must not use Doctrine to avoid unique key constraint violation
            $query = "
                insert into eligibility_cache (eligible_scholarship_ids, updated_at, account_id) 
                values (:scholarshipsJson, NOW(), :accountId)
                on duplicate key update 
                eligible_scholarship_ids = :scholarshipsJson,
                updated_at = NOW()
            ";

            $this->getEntityManager()->getConnection()->executeUpdate(
                $query,
                [
                    'scholarshipsJson' => $scholarshipsJson,
                    'accountId' => $accountId,
                ]
            );
        } else {
            $eligibilityCache->setEligibleScholarshipIds($scholarshipsJson);

            try {
                $this->_em->persist($eligibilityCache);
                $this->_em->flush($eligibilityCache);
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

        $cacheKey = sprintf(EligibilityCache::CACHE_KEY_ACCOUNT, $accountId);
        $item = $eligibilityCache->toArray(true);
        $this->cacheStore->put($cacheKey, $item, 60 * 60 * 24 * 7);

        return $eligibilityCache;
    }

    /**
     * Creates and returns not persisted Eligibility cache item. Supposed to be used on MUTEX timeout
     *
     * @param array $scholarshipsData
     * @param int $accountId
     * @return EligibilityCache
     */
    public function getNotPersisted(array $scholarshipsData, int $accountId)
    {
        $eligibilityCache = new EligibilityCache();
        $eligibilityCache->setAccount($accountId);

        $scholarshipsJson = json_encode(
            array_combine(
                array_column($scholarshipsData, 'scholarship_id'),
                array_column($scholarshipsData, 'amount')
            ),
            JSON_FORCE_OBJECT
        );

        $eligibilityCache->setEligibleScholarshipIds($scholarshipsJson);

        return $eligibilityCache;
    }

    /**
     * Remove cache for inactive or without subscription accounts.
     *
     * @param int $noActivityDays Days since last activity for an account
     * @return int
     * @throws \Exception
     */
    public function removeStaleCache(int $noActivityDays = 7)
    {
        $iterator = $this->fetchStaleElbCacheAccountIds(1000, 0, null, $noActivityDays);
        $totalCount = 0;
        foreach ($iterator as $accountIds) {
            $totalCount += count($accountIds);
            $this->purgeEligibilityCache($accountIds);
        }

        return $totalCount;
    }

    /**
     * Check if Eligibility cache item exists for a particular user
     *
     * @param int $accountId
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function doesItemExist(int $accountId)
    {
        // executeUpdate instead executeQuery to force master connection to be used
        $result = $this->getEntityManager()->getConnection()->executeUpdate(
            "select account_id from eligibility_cache where account_id = :accountId",
            ['accountId' => $accountId]
        );

        return (bool)$result;
    }

    /**
     * Retrieves account ids for the stale cache (cache which might be removed)
     *
     * @param int $batchSize
     * @param int $offsetAccountId
     * @param int|null $maxResults
     * @param int $noActivityDays
     * @return \Generator
     * @throws \Exception
     */
    protected function fetchStaleElbCacheAccountIds(int $batchSize = 1000, int $offsetAccountId = 0, int $maxResults = null, int $noActivityDays = 7): \Generator
    {
        $totalCount = 0;
        $isLastIteration = false;

        $queryBuilder = $this->createQueryBuilder('ec')
            ->select(['DISTINCT(IDENTITY(ec.account))'])
            ->leftJoin(LoginHistory::class, 'lh', Query\Expr\Join::WITH, 'lh.account = ec.account')
            ->leftJoin(Subscription::class, 's', Query\Expr\Join::WITH, 'ec.account = s.account')
            ->where('lh.account is null OR lh.actionDate < :lastActivityDay')
            ->andWhere('s.subscriptionId is null OR (s.subscriptionStatus != :activeStatus and s.activeUntil < NOW())')
            ->andWhere('ec.account > :offsetAccountId')
            ->setParameter('offsetAccountId', $offsetAccountId)
            ->setParameter('activeStatus', SubscriptionStatus::ACTIVE)
            ->setParameter('lastActivityDay', Carbon::instance(new \DateTime())->subDays($noActivityDays))
            ->orderBy('ec.account', 'ASC')
            ->setMaxResults($batchSize);


        do {
            if (!is_null($maxResults) && $batchSize > $maxResults) {
                $batchSize = $maxResults;
                $isLastIteration = true;
            }

            $result = $queryBuilder->getQuery()->getResult();
            $accountIds = array_map('current', $result);
            $resultCount = count($accountIds);
            $offsetAccountId = $resultCount ? $accountIds[$resultCount-1] : $offsetAccountId + 1;

            $totalCount += $resultCount;
            if (!is_null($maxResults) && $totalCount + $batchSize >= $maxResults) {
                $batchSize = $maxResults - $totalCount;
            }

            $queryBuilder->setParameter('offsetAccountId', $offsetAccountId);
            $queryBuilder->setMaxResults($batchSize);

            yield array_unique($accountIds);

            if ($isLastIteration || $resultCount < $batchSize || (!is_null($maxResults) && $totalCount >= $maxResults)) {
                break;
            }
        } while($result);
    }


    public function fetchAccountIds(
        int $batchSize = 1000,
        int $offsetAccountId = 0,
        int $maxResults = null,
        array $exceptAccountIds = [],
        int $targetScholarshipId = null
    ): \Generator
    {
        $params = [
            'offsetAccountId' => $offsetAccountId,
            'batchSize' => $batchSize
        ];
        $paramTypes = [
            'offsetAccountId' => \PDO::PARAM_INT,
            'batchSize' => \PDO::PARAM_INT
        ];

        $queryClause = '';
        if ($targetScholarshipId) {
            $queryClause .= ' and JSON_CONTAINS_PATH(eligible_scholarship_ids, \'one\', :jsonParameter)';
            $jsonParameter = sprintf('$."%s"', $targetScholarshipId);
            $params['jsonParameter'] = $jsonParameter;
            $paramTypes['jsonParameter'] = \PDO::PARAM_STR;
        }

        if ($exceptAccountIds) {
            $queryClause .= ' and account_id not in (:accountIds)';
            $params['accountIds'] = $exceptAccountIds;
            $paramTypes['accountIds'] = Connection::PARAM_INT_ARRAY;
        }

        $generateQuery = function($queryClause) {
            $query = "
                select account_id from eligibility_cache
                where account_id > :offsetAccountId
                {$queryClause}
                order by account_id ASC
                limit :batchSize
            ";

            return $query;
        };

        $totalCount = 0;
        $isLastIteration = false;
        do {
            if (!is_null($maxResults) && $batchSize > $maxResults) {
                $batchSize = $maxResults;
                $isLastIteration = true;
            }

            $accountIds = $this->getEntityManager()->getConnection()->executeQuery(
                $generateQuery($queryClause),
                $params,
                $paramTypes
            )->fetchAll(\PDO::FETCH_COLUMN);

            $resultCount = count($accountIds);
            $offsetAccountId = $resultCount ? $accountIds[$resultCount-1] : $offsetAccountId + 1;

            $totalCount += $resultCount;
            if (!is_null($maxResults) && $totalCount + $batchSize >= $maxResults) {
                $batchSize = $maxResults - $totalCount;
            }

            $params['offsetAccountId'] = $offsetAccountId;
            $params['batchSize'] = $batchSize;

            yield array_unique($accountIds);

            if ($isLastIteration || $resultCount < $batchSize || (!is_null($maxResults) && $totalCount >= $maxResults)) {
                break;
            }
        } while($accountIds);
    }
}
