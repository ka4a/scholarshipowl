<?php

namespace App\Listeners;

use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Events\Account\AccountEvent;
use App\Events\Account\ElbCachePurgedOnAccountUpdate;
use App\Events\Account\ElbCachePurgedOnAccountUpdateEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Services\EligibilityCacheService;
use Illuminate\Events\Dispatcher;

/**
 * MUST NOT be queueable, because cache needs to be cleaned up right after an account updated.
 *
 * Class EligibilityCacheCleaner
 * @package App\Listeners
 */
class EligibilityCacheCleaner
{

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    /**
     * @var EligibilityCacheRepository
     */
    protected $elbCacheRepo;

    public function __construct(EligibilityCacheService $elbCacheService)
    {
        $this->elbCacheService = $elbCacheService;
        $this->elbCacheRepo = \EntityManager::getRepository(EligibilityCache::class);
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(UpdateAccountEvent::class, static::class.'@onUpdateAccount');
    }

    /**
     * @param UpdateAccountEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onUpdateAccount(AccountEvent $event)
    {
        $isMyAccountAction = strpos($event->getReferer(), 'my-account') !== false;
        $isAdminAction = strpos($event->getReferer(), 'admin/accounts') !== false;

        // we purge cache on each registration step, because the next steps might require cache to be up to date
        // which is not the case when cache update is a queueable operation
        if (!$isMyAccountAction && !$isAdminAction) {
            $this->elbCacheRepo->purgeEligibilityCache([$event->getAccountId()]);
            \Event::dispatch(new ElbCachePurgedOnAccountUpdateEvent($event));
        }
    }
}
