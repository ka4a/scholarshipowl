<?php

namespace App\Jobs;


use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Scholarship;

class EligibilityCacheRemoveScholarshipJob extends Job
{
    const STAT_START_TIME_KEY = '%s-remove-time';
    const STAT_COUNT_KEY = '%s-remove-count';
    const STAT_PROCESS_START = 1;
    const STAT_PROCESS_WORK = 2;
    const STAT_PROCESS_FINISH = 3;

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
     * @var int
     */
    protected $progress;

    /**
     * @param Scholarship $scholarship
     * @param array $accountIds
     * @param string $procHash $procHash Identifies all jobs related to a particular add/delete operation of a scholarship.
     * @param \DateTime|null $delay
     * @param int $progress
     */
    public static function dispatch(
        Scholarship $scholarship,
        array $accountIds,
        string $procHash = '',
        \DateTime $delay = null,
        $progress = self::STAT_PROCESS_WORK
    )
    {
        $dispatch = dispatch(new static($scholarship, $accountIds, $procHash, $progress));
        if ($delay && !app()->environment('testing')) {
            $dispatch->delay($delay);
        }
    }

    /**
     * EligibilityCacheAddScholarshipJob constructor.
     * @param Scholarship $scholarshipId
     * @param array $accountIds
     */
    public function __construct(Scholarship $scholarship, array $accountIds, string $procHash, int $progress)
    {
        $this->accountIds = $accountIds;
        $this->scholarship = $scholarship;
        $this->procHash = $procHash;
        $this->progress = $progress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var EligibilityCacheRepository $elbCacheRepo */
        $elbCacheRepo = \EntityManager::getRepository(EligibilityCache::class);

        $cacheCountKey = sprintf(self::STAT_COUNT_KEY, $this->procHash);
        $cacheTimeKey = sprintf(self::STAT_START_TIME_KEY, $this->procHash);

        if (!$elbCacheRepo->getCacheSore()->get($cacheCountKey)) {
            $elbCacheRepo->getCacheSore()->put($cacheCountKey, 0, 600);
        }

        if ($this->progress === self::STAT_PROCESS_START) {
            $elbCacheRepo->getCacheSore()->put($cacheTimeKey, microtime(true), 600);
            \Log::debug(
                sprintf(
                    '[ %s ] Started deleting scholarship [ %s ] from accounts eligibility cache',
                    $this->procHash,
                    $this->scholarship->getScholarshipId()
                )
            );
        }

        $start = microtime(true);
        if (!is_array($this->accountIds)) {
            \Log::debug(
                sprintf(
                    '[ %s ] Skipped deletion of scholarship [ %s ] because accountIds is not an array!',
                    $this->procHash,
                    $this->scholarship->getScholarshipId()
                )
            );
        } else if (!count($this->accountIds)) {
            \Log::debug(
                sprintf(
                    '[ %s ] Skipped deletion of scholarship [ %s ] because accountIds count is 0',
                    $this->procHash,
                    $this->scholarship->getScholarshipId()
                )
            );
        } else {
            $elbCacheRepo->removeScholarshipFromAccounts($this->scholarship, $this->accountIds);

            \Log::debug(
                sprintf(
                    '[ %s ] Deleted scholarship [ %s ] from [ %s ] accounts in [ %s ] sec.',
                    $this->procHash,
                    $this->scholarship->getScholarshipId(),
                    count($this->accountIds),
                    round(microtime(true) - $start, 5)
                )
            );

            $elbCacheRepo->getCacheSore()->increment($cacheCountKey, count($this->accountIds));
        }

        if ($this->progress === self::STAT_PROCESS_FINISH) {
            $start = $elbCacheRepo->getCacheSore()->pull($cacheTimeKey, 0);
            $elapsed = round(microtime(true) - $start, 5);
            \Log::debug(
                sprintf(
                    '[ %s ] Finished deleting scholarship [ %s ] from [ %s ] accounts in [ %s ] sec.',
                    $this->procHash,
                    $this->scholarship->getScholarshipId(),
                    $elbCacheRepo->getCacheSore()->pull($cacheCountKey),
                    $elapsed
                )
            );
        }
    }
}
