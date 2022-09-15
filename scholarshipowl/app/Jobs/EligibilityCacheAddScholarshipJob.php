<?php

namespace App\Jobs;


use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Scholarship;

class EligibilityCacheAddScholarshipJob extends Job
{
    const STAT_START_TIME_KEY = '%s-add-time';
    const STAT_COUNT_KEY = '%s-add-count';

    /**
     * @var array
     */
    protected $accountIds;

    /**
     * @var Scholarship
     */
    protected $scholarship;

    /**
     * @var string
     */
    protected $procHash;

    /**
     * @param Scholarship $scholarship
     * @param array $accountIds
     * @param string $procHash Identifies all jobs related to a particular add operation of a scholarship.
     */
    public static function dispatch(Scholarship $scholarship, array $accountIds, string $procHash = '')
    {
        dispatch(new static($scholarship, $accountIds, $procHash));
    }

    /**
     * EligibilityCacheAddScholarshipJob constructor.
     * @param Scholarship $scholarshipId
     * @param array $accountIds
     */
    public function __construct(Scholarship $scholarship, array $accountIds, string $procHash)
    {
        $this->accountIds = $accountIds;
        $this->scholarship = $scholarship;
        $this->procHash = $procHash;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        /** @var EligibilityCacheRepository $elbCacheRepo */
        $elbCacheRepo = \EntityManager::getRepository(EligibilityCache::class);
        $elbCacheRepo->addScholarshipToExistingItems($this->scholarship, $this->accountIds);
        \Log::debug(
            sprintf(
                '[ %s ] Added scholarship [ %s ] to [ %s ] accounts in [ %s ] sec.',
                $this->procHash,
                $this->scholarship->getScholarshipId(),
                count($this->accountIds),
                round(microtime(true) - $start, 5)
            )
        );

        $cacheCountKey = sprintf(self::STAT_COUNT_KEY, $this->procHash);
        $cacheTimeKey = sprintf(self::STAT_START_TIME_KEY, $this->procHash);

        $currentCount = $elbCacheRepo->getCacheSore()->decrement($cacheCountKey, count($this->accountIds));

        if ($currentCount === 0) {
            \Log::debug(
                sprintf(
                    '[ %s ] Finished adding scholarship [ %s ] to accounts in [ %s ] sec.',
                    $this->procHash,
                    $this->scholarship->getScholarshipId(),
                    round(microtime(true) - $elbCacheRepo->getCacheSore()->pull($cacheTimeKey, 0), 5)
                )
            );
        }
    }
}
