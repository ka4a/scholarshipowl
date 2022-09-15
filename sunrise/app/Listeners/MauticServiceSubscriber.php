<?php namespace App\Listeners;

use App\Doctrine\QueryIterator;
use App\Entities\Application;
use App\Entities\MauticContact;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplateSubscription;
use App\Events\ApplicationAwardedEvent;
use App\Events\ApplicationCreatedEvent;
use App\Events\ScholarshipPublishedEvent;
use App\Repositories\ScholarshipTemplateSubscriptionRepository;
use App\Services\MauticService;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

/**
 * All events that mautic should listen for should be in this subscriber.
 */
class MauticServiceSubscriber implements ShouldQueue
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var MauticService
     */
    protected $mautic;

    /**
     * MauticServiceSubscriber constructor.
     *
     * @param EntityManager $em
     * @param MauticService $service
     */
    public function __construct(EntityManager $em, MauticService $service)
    {
        $this->em = $em;
        $this->mautic = $service;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function subscribe($dispatcher)
    {
        $dispatcher->listen(ApplicationCreatedEvent::class,   static::class.'@onApplicationCreated');
        $dispatcher->listen(ApplicationAwardedEvent::class,   static::class.'@onApplicationAwarded');
        $dispatcher->listen(ScholarshipPublishedEvent::class, static::class.'@onScholarshipPublished');
    }

    /**
     * @param ApplicationCreatedEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function onApplicationCreated(ApplicationCreatedEvent $event)
    {
        /** @var Application $application */
        $application = $this->em->find(Application::class, $event->getApplicationId());

        $this->mautic->syncApplication($application);
        $this->mautic->notifyApplied($application);
    }

    /**
     * @param ApplicationAwardedEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function onApplicationAwarded(ApplicationAwardedEvent $event)
    {
        /** @var Application $application */
        $application = $this->em->find(Application::class, $event->getApplicationId());

        $this->mautic->markContactAsWinner($application);
    }

    /**
     * @param ScholarshipPublishedEvent $event
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function onScholarshipPublished(ScholarshipPublishedEvent $event)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->em->find(Scholarship::class, $event->getScholarshipId());

        /** @var ScholarshipTemplateSubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->em->getRepository(ScholarshipTemplateSubscription::class);
        $query = $subscriptionRepository->queryWaitingByTemplate($scholarship->getTemplate());

        /** @var ScholarshipTemplateSubscription[] $subscriptions */
        foreach (QueryIterator::create($query) as $subscriptions) {
            foreach ($subscriptions as $subscription) {
                $this->mautic->notifyScholarshipPublished($scholarship, $subscription->getEmail());
                $subscription->setStatus(ScholarshipTemplateSubscription::STATUS_NOTIFIED);
            }
            $this->em->flush();
            $this->em->clear(MauticContact::class);
            $this->em->clear(ScholarshipTemplateSubscription::class);
        }
    }
}
