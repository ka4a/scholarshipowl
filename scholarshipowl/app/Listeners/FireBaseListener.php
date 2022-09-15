<?php

namespace App\Listeners;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Marketing\MobilePushNotificationSettings;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Scholarship;
use App\Events\Email\NewEmailEvent;
use App\Events\Firebase\NewMatchEvent;
use App\Events\Scholarship\ScholarshipPotentialWinnerEvent;
use App\Events\Scholarship\ScholarshipProvedWinnerEvent;
use App\Events\Scholarship\ScholarshipDisqualifiedWinnerEvent;
use App\Services\FireBaseService;
use App\Services\PubSub\TransactionalEmailService;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class FireBaseListener implements ShouldQueue
{
    /**
     * @var FireBaseService
     */
    protected $fireBaseService;

    /**
     * @var EntityManager $eml
     */
    protected $em;

    public function __construct(FireBaseService $fireBaseService, EntityManager $em)
    {
        $this->fireBaseService = $fireBaseService;
        $this->em = $em;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function subscribe($dispatcher)
    {
        $eventWithListeners = [
           ScholarshipPotentialWinnerEvent::class => static::class.'@onPotentialWinner',
           ScholarshipProvedWinnerEvent::class => static::class.'@onProvedWinner',
           ScholarshipDisqualifiedWinnerEvent::class => static::class.'@onDisqualifiedWinner',
           NewEmailEvent::class => static::class.'@onNewEmail',
           NewMatchEvent::class => static::class.'@onNewMatchEvent',
        ];

        $this->checkStatusAndSubscribeEvents($eventWithListeners, $dispatcher);

    }

    /**
     * @param ScholarshipPotentialWinnerEvent $event
     *
     * @throws \Exception
     */
    public function onPotentialWinner(ScholarshipPotentialWinnerEvent $event)
    {
        /**
         * @var Account $account
         */
        $winnerAccount = $event->getApplication()->getAccount();

        $this->fireBaseService->sendScholarshipEventToUser($winnerAccount, TransactionalEmailService::SCHOLARSHIP_USER_WON, $event->getApplication());
    }

    /**
     * @param ScholarshipProvedWinnerEvent $event
     *
     * @throws \Exception
     */
    public function onProvedWinner(ScholarshipProvedWinnerEvent $event)
    {
        /**
         * @var Scholarship $scholarship
         */
        $scholarship = $event->getScholarship();

        /**
         * @var Account $account
         */
        $winnerAccount = $event->getApplication()->getAccount();

        $this->fireBaseService->sendScholarshipEventToUser(
            $winnerAccount, TransactionalEmailService::SCHOLARSHIP_USER_AWARDED, $event->getApplication()
        );

        /**
         * @var ApplicationRepository $applicationRepository
         */
        $applicationRepository = $this->em->getRepository(Application::class);
        $iterator = new QueryIterator($applicationRepository->getAppliedAccountsQuery($scholarship));

        foreach ($iterator as $loserAccountApplications) {
            /** @var Application $application */
            foreach ($loserAccountApplications as $application) {
                $loserAccount = $application->getAccount();

                if ($winnerAccount->getAccountId() != $loserAccount->getAccountId()) {
                    $this->fireBaseService->sendScholarshipEventToUser(
                        $loserAccount,  TransactionalEmailService::SCHOLARSHIP_WINNER_CHOSEN, $application
                    );
                }
            }
        }
    }

    /**
     * @param ScholarshipDisqualifiedWinnerEvent $event
     *
     * @throws \Exception
     */
    public function onDisqualifiedWinner(ScholarshipDisqualifiedWinnerEvent $event)
    {
        $account = $event->getApplication()->getAccount();
        $this->fireBaseService->sendScholarshipEventToUser($account,  TransactionalEmailService::SCHOLARSHIP_USER_MISSED, $event->getApplication());
    }

    /**
     * @param NewEmailEvent $newEmailEvent
     * @throws \Exception
     */
    public function onNewEmail(NewEmailEvent $newEmailEvent)
    {
        $notification = [
            'title' => 'E-mail swoop',
            'body' => 'A new e-mail landed in your nest.'
        ];

        $data  = [
            "notificationId" => "notification.email.open",
            "emailId" =>  strval($newEmailEvent->getEmail()->getEmailId()),
            "isInboxEmail" => "true"
        ];

        $this->fireBaseService->sendMessageToUser($newEmailEvent->getAccount(), $notification, $data);
    }

    /**
     * @param NewMatchEvent $newMatchEvent
     * @throws \Exception
     */
    public function onNewMatchEvent(NewMatchEvent $newMatchEvent)
    {

        $account = \App\Facades\EntityManager::getReference(Account::class, $newMatchEvent->getAccountId());
        $newScholarships = $newMatchEvent->getNewScholarshipList();
        $scholarshipCount = count($newScholarships);
        $notification = [
            'title' => 'Look no feather!',
            'body' => str_replace('%count%', $scholarshipCount, "You’ve got %count% new scholarship matches! Check it out now!")
        ];

        if(empty($newMatchEvent->getNewScholarshipList())) {
            $notification['body'] = "You’ve got new scholarship matches! Check it out now!";
        }

        $data  = [
            "notificationId" => "notification.new.matches",
            "newMatches"     => strval($scholarshipCount)
        ];

        $this->fireBaseService->sendMessageToUser($account, $notification, $data);
    }

    protected function checkStatusAndSubscribeEvents(array $eventsList, Dispatcher $dispatcher)
    {
        try {
            $repo = $this->em->getRepository(MobilePushNotificationSettings::class);
            $settingsEntities = $repo->findAll();

            $sortedSettings = [];

            /**
             * @var MobilePushNotificationSettings $entity
             */
            foreach ($settingsEntities as $entity) {
                $sortedSettings[$entity->getEventName()] = $entity;
            }

            foreach ($eventsList as $event => $listener) {
                if (isset($sortedSettings[$event]) && $sortedSettings[$event]->isActive()) {
                    $dispatcher->listen($event, $listener);
                }
            }
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
