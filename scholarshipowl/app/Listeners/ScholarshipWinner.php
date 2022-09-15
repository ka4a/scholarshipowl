<?php

namespace App\Listeners;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Scholarship;
use App\Events\Scholarship\ScholarshipPotentialWinnerEvent;
use App\Events\Scholarship\ScholarshipProvedWinnerEvent;
use App\Events\Scholarship\ScholarshipDisqualifiedWinnerEvent;
use App\Services\PubSub\AccountService;
use App\Services\PubSub\TransactionalEmailService;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class ScholarshipWinner implements ShouldQueue
{
    /**
     * @var TransactionalEmailService
     */
    protected $transactionEmailPubSubService;

    /**
     * @var EntityManager $eml
     */
    protected $em;

    public function __construct(TransactionalEmailService $tes, EntityManager $em){
        $this->transactionEmailPubSubService = $tes;
        $this->em = $em;
    }
    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(ScholarshipPotentialWinnerEvent::class, static::class.'@onPotentialWinner');
        $events->listen(ScholarshipProvedWinnerEvent::class, static::class.'@onProvedWinner');
        $events->listen(ScholarshipDisqualifiedWinnerEvent::class, static::class.'@onDisqualifiedWinner');
    }

    /**
     * @param ScholarshipPotentialWinnerEvent $event
     *
     * @throws \Exception
     */
    public function onPotentialWinner(ScholarshipPotentialWinnerEvent $event)
    {
        /**
         * @var Scholarship $scholarship
         */
        $scholarship = $event->getScholarship();

        /**
         * @var Account $account
         */
        $winnerAccount = $event->getApplication()->getAccount();

        $this->transactionEmailPubSubService->sendCommonEmail(
            $winnerAccount,
            TransactionalEmailService::SCHOLARSHIP_USER_WON,
            [
                'amount' => $scholarship->getAmount(),
                'scholarship_name' => $scholarship->getTitle(),
                'winner_form_url' => $scholarship->getWinnerFormUrl()
            ]
        );

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

        $this->transactionEmailPubSubService->sendCommonEmail(
            $winnerAccount,
            TransactionalEmailService::SCHOLARSHIP_USER_AWARDED,
            [
                'amount' => $scholarship->getAmount(),
                'scholarship_name' => $scholarship->getTitle(),
            ]
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
                    usleep(5e4); // limit to 20 per second
                    $this->transactionEmailPubSubService->sendCommonEmail(
                        $loserAccount,
                        TransactionalEmailService::SCHOLARSHIP_WINNER_CHOSEN,
                        [
                            'amount' => $scholarship->getAmount(),
                            'scholarship_name' => $scholarship->getTitle()
                        ]
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
        $scholarship = $event->getScholarship();
        $account = $event->getApplication()->getAccount();
        /** @var AccountService $accountService */
        $accountService = app()->get(AccountService::class);
        $result = $accountService->populateMergeFields([$account], [AccountService::FIELD_SCHOLARSHIP_EL_COUNT]);
        $eligibleCnt = $result[$account->getAccountId()]['scholarship_eligible_count'];
        $this->transactionEmailPubSubService->sendCommonEmail(
            $account,
            TransactionalEmailService::SCHOLARSHIP_USER_MISSED,
            [
                'scholarship_name' => $scholarship->getTitle(),
                'eligible_count' => $eligibleCnt
            ]
        );
    }
}
