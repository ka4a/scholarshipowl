<?php

namespace App\Listeners;

use App\Entity\Account;
use App\Entity\Domain;
use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Events\Account\AccountEvent;
use App\Events\Account\ElbCacheAccountEvent;
use App\Events\Account\ElbCachePurgedOnAccountUpdate;
use App\Events\Account\ElbCachePurgedOnAccountUpdateEvent;
use App\Events\Account\ElbCacheUpdatedOnAccountUpdateEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Services\ApplicationService;
use App\Services\EligibilityCacheService;
use App\Services\PubSub\TransactionalEmailService;
use App\Services\ScholarshipService;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Util\Mailer;

/**
 * Apply for DYI Scholarship after profile completeness pass 86%
 */
class ApplyForDYIScholarshipListener implements ShouldQueue
{
    const NEEDED_COMPLETENESS = 86;
    const SESSION_APPLIED = 'YDIT-Applied';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * ApplyForDYIScholarshipListener constructor.
     *
     * @param ApplicationService $applicationService
     * @param ScholarshipService $scholarshipService
     */
    public function __construct(EntityManager $em, ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
        $this->em = $em;
        $this->repository = $em->getRepository(Scholarship::class);
    }


    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(ElbCachePurgedOnAccountUpdateEvent::class, static::class.'@onUpdateAccount');
        $events->listen(ElbCacheUpdatedOnAccountUpdateEvent::class, static::class.'@onUpdateAccount');
    }

    /**
     * @param UpdateAccountEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onUpdateAccount(ElbCacheAccountEvent $event)
    {
        $accountEvent = $event->getAccountEvent();
        $account = $accountEvent->getAccount();

        if ($account->getDomain()->is(Domain::APPLYME)) {
            return;
        }

        try {
            if ($account->getProfile()->getCompleteness() >= static::NEEDED_COMPLETENESS) {

                $automaticScholarships = $this->repository->findAutomaticScholarships($account);

                if (!$automaticScholarships->isEmpty() && $this->isNotFreemium($account)) {
                    $YDITScholarship = $automaticScholarships->first();
                    $this->applicationService->applyScholarship($account, $YDITScholarship, true);

                    $transactionEmailService = app(TransactionalEmailService::class);
                    $transactionEmailService->sendCommonEmail($account, TransactionalEmailService::YDIT_CONFIRMATION);
                }
            }
        } catch (\Exception $e) {
            \Sentry::captureException($e);
            \Log::error($e);
        }
    }

    protected function isNotFreemium(Account $account)
    {
        $result = true;
        /**
         * @var Subscription $activeSubscription
         */

        $historyRepository = \EntityManager::getRepository(\App\Entity\Log\LoginHistory::class)->findBy(
            ['account' => $account->getAccountId()],
            ['loginHistoryId' => 'DESC']
        );

        if (isset($historyRepository[0]) && $historyRepository[0]->getFeatureSet() == FeatureSet::FREEMIUM_MVP_NAME) {
           $result = false;
        }

        $activeSubscription = $account->getActiveSubscriptions()->first();
        if ($activeSubscription) {
            $packageAlias = $activeSubscription->getPackage()->getAlias();
            $result = (!$account->isFreemium() || $packageAlias != Package::FREEMIUM_MVP_ALIAS);
        }

        return $result;
    }
}
