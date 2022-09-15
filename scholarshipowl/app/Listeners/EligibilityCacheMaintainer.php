<?php

namespace App\Listeners;

use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Events\Account\AccountEvent;
use App\Events\Account\ApplicationsAddEvent;
use App\Events\Account\ApplicationsRemoveEvent;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\ElbCacheUpdatedOnAccountUpdate;
use App\Events\Account\ElbCacheUpdatedOnAccountUpdateEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Events\Scholarship\ScholarshipApplicationDeclinedEvent;
use App\Events\Scholarship\ScholarshipCreatedEvent;
use App\Events\Scholarship\ScholarshipDeletedEvent;
use App\Events\Scholarship\ScholarshipExpiredEvent;
use App\Events\Scholarship\ScholarshipPublishedEvent;
use App\Events\Scholarship\ScholarshipRecurredEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Services\EligibilityCacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class EligibilityCacheMaintainer implements ShouldQueue
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
        $events->listen(ScholarshipDeletedEvent::class, static::class.'@onScholarshipDeleted');
        $events->listen(ScholarshipUpdatedEvent::class, static::class.'@onScholarshipUpdated');
        $events->listen(ScholarshipCreatedEvent::class, static::class.'@onScholarshipCreated');
        $events->listen(ScholarshipRecurredEvent::class, static::class.'@onScholarshipRecurred');
        $events->listen(ScholarshipExpiredEvent::class, static::class.'@onScholarshipExpired');
        $events->listen(ScholarshipPublishedEvent::class, static::class.'@onScholarshipPublishedEvent');

        $events->listen(UpdateAccountEvent::class, static::class.'@onUpdateAccount');

        $events->listen(ApplicationsRemoveEvent::class, static::class . '@onApplicationRemove');
        $events->listen(ApplicationsAddEvent::class, static::class . '@onApplicationAdd');
    }

    /**
     * @param ApplicationsAddEvent $event
     */
    public function onApplicationAdd(ApplicationsAddEvent $event)
    {
        $this->elbCacheService->updateAccountEligibilityCache($event->getAccountId());
    }

    /**
     * @param ApplicationsRemoveEvent $event
     */
    public function onApplicationRemove(ApplicationsRemoveEvent $event)
    {
        $this->elbCacheService->updateAccountEligibilityCache($event->getAccountId());
    }

    /**
     * @param ScholarshipDeletedEvent $event
     */
    public function onScholarshipDeleted(ScholarshipDeletedEvent $event)
    {
        $this->elbCacheService->removeFromEligibilityCache($event->getScholarship());
    }

    /**
     * @param ScholarshipUpdatedEvent $event
     *
     * @throws \Exception
     */
    public function onScholarshipUpdated(ScholarshipUpdatedEvent $event)
    {
        if ($event->isStatusUpdated || $event->isEligibilityUpdated) {
            $this->elbCacheService->rotateScholarship($event->getScholarship());
        }
    }

    /**
     * @param ScholarshipRecurredEvent $event
     */
    public function onScholarshipRecurred(ScholarshipRecurredEvent $event)
    {
        $this->elbCacheService->addToEligibilityCache($event->newScholarship);
        $this->elbCacheService->removeFromEligibilityCache($event->scholarship);
    }

    /**
     * @param ScholarshipCreatedEvent $event\
     */
    public function onScholarshipCreated(ScholarshipCreatedEvent $event)
    {
        $this->elbCacheService->addToEligibilityCache($event->getScholarship());
    }

    /**
     * @param ScholarshipApplicationDeclinedEvent $event
     */
    public function onScholarshipExpired(ScholarshipExpiredEvent $event)
    {
        $this->elbCacheService->removeFromEligibilityCache($event->getScholarship());
    }

    /**
     * @param ScholarshipPublishedEvent $event
     */
    public function onScholarshipPublishedEvent(ScholarshipPublishedEvent $event)
    {
        $this->elbCacheService->addToEligibilityCache($event->getScholarship());
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

        // we update eligibility cache only on profile updates from my-account or admin.
        // So, no updates on registration steps.
        if ($isMyAccountAction || $isAdminAction) {
            $this->elbCacheService->updateAccountEligibilityCache($event->getAccount()->getAccountId());
            \Event::dispatch(new ElbCacheUpdatedOnAccountUpdateEvent($event));
        }
    }
}
