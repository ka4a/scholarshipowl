<?php namespace App\Listeners;

use App\Entities\Application;
use App\Entities\Scholarship;
use App\Entities\ScholarshipWinner;
use App\Events\ApplicationAwardedEvent;
use App\Events\ApplicationCreatedEvent;
use App\Events\ApplicationStatusChangedEvent;
use App\Events\ApplicationWinnerDisqualified;
use App\Events\ApplicationWinnerFormFilledEvent;
use App\Events\ScholarshipDeadlineEvent;
use App\Events\ScholarshipPublishedEvent;
use App\Events\ScholarshipStatusChangedEvent;
use App\Events\ScholarshipWinnerPublished;
use App\Services\PubSubService;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Pz\Doctrine\Rest\RestRepository;
use Doctrine\ORM\EntityManager;

class PubSubSubscriber implements ShouldQueue
{
    /**
     * @param Dispatcher $dispatcher
     */
    public function subscribe($dispatcher)
    {
        $dispatcher->listen(ScholarshipPublishedEvent::class,           static::class.'@scholarshipPublished');
        $dispatcher->listen(ScholarshipStatusChangedEvent::class,       static::class.'@scholarshipStatusChanged');
        $dispatcher->listen(ScholarshipDeadlineEvent::class,            static::class.'@scholarshipDeadline');
        $dispatcher->listen(ApplicationCreatedEvent::class,             static::class.'@applicationCreated');
        $dispatcher->listen(ApplicationAwardedEvent::class,             static::class.'@applicationAwarded');
        $dispatcher->listen(ApplicationWinnerFormFilledEvent::class,    static::class.'@applicationWinnerFilled');
        $dispatcher->listen(ApplicationWinnerDisqualified::class,       static::class.'@applicationWinnerDisqualified');
        $dispatcher->listen(ScholarshipWinnerPublished::class,          static::class.'@applicationWinnerPublished');
        $dispatcher->listen(ApplicationStatusChangedEvent::class,       static::class.'@applicationStatusChanged');
    }

    /**
     * @param ScholarshipPublishedEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function scholarshipPublished(ScholarshipPublishedEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubScholarshipPublished($this->getScholarshipById($event->getScholarshipId()));
    }

    /**
     * @param ScholarshipStatusChangedEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function scholarshipStatusChanged(ScholarshipStatusChangedEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubScholarshipStatusChanged($this->getScholarshipById($event->getScholarshipId()));
    }

    /**
     * @param ScholarshipDeadlineEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function scholarshipDeadline(ScholarshipDeadlineEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubScholarshipDeadline($this->getScholarshipById($event->getScholarshipId()));
    }

    /**
     * @param ApplicationCreatedEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function applicationCreated(ApplicationCreatedEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubApplicationApplied($this->getApplicationById($event->getApplicationId()));
    }

    /**
     * @param ApplicationStatusChangedEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function applicationStatusChanged(ApplicationStatusChangedEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubApplicationStatusChanged($this->getApplicationById($event->getApplicationId()));
    }

    /**
     * @param ApplicationAwardedEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function applicationAwarded(ApplicationAwardedEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubApplicationWinner($this->getApplicationById($event->getApplicationId()));
    }

    /**
     * @param ApplicationWinnerFormFilledEvent $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function applicationWinnerFilled(ApplicationWinnerFormFilledEvent $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubApplicationWinnerFilled($this->getApplicationById($event->getApplicationId()));
    }

    /**
     * @param ApplicationWinnerDisqualified $event
     * @throws \Exception
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function applicationWinnerDisqualified(ApplicationWinnerDisqualified $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubApplicationWinnerDisqualified($this->getApplicationById($event->getApplicationId()));
    }

    /**
     * @param ScholarshipWinnerPublished $event
     * @throws \Exception
     */
    public function applicationWinnerPublished(ScholarshipWinnerPublished $event)
    {
        /** @var PubSubService $pubsub */
        $pubsub = app(PubSubService::class);
        $pubsub->pubApplicationWinnerPublished($this->getScholarshipWinnerById($event->getScholarshipWinnerId()));
    }

    /**
     * @param int $id
     * @return Scholarship|JsonApiResource
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function getScholarshipById($id)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return RestRepository::create($em, Scholarship::class)->findById($id);
    }

    /**
     * @param int $id
     * @return Application|JsonApiResource
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function getApplicationById($id)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return RestRepository::create($em, Application::class)->findById($id);
    }

    /**
     * @param $id
     * @return ScholarshipWinner|JsonApiResource
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function getScholarshipWinnerById($id)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return RestRepository::create($em, ScholarshipWinner::class)->findById($id);
    }
}
