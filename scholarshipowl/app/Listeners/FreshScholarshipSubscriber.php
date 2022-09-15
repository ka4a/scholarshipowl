<?php namespace App\Listeners;

use App\Entity\Scholarship;
use App\Entity\Repository\ScholarshipRepository;
use App\Events\Account\AccountEligibilityUpdateEvent;
use App\Events\Account\AccountEligibilityUpdateEvent\ReasonNewScholarship;
use App\Events\Account\AccountEligibilityUpdateEvent\ReasonUpdateScholarship;
use App\Events\Account\AccountEligibilityUpdateEvent\ReasonDisabledScholarship;
use App\Events\Scholarship\FreshScholarshipEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use Illuminate\Events\Dispatcher;

class FreshScholarshipSubscriber
{
    /**
     * @var EligibilityService
     */
    protected $service;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * @var AccountRepository
     */
    protected $accounts;

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(FreshScholarshipEvent::class, static::class . '@onUpdateFreshScholarship');
    }

    /**
     * Update `account_eligibility` table for new scholarship updated.
     *
     * @param FreshScholarshipEvent $event
     */
    public function onUpdateFreshScholarship(FreshScholarshipEvent $event)
    {
        $accList = $event->getAccounts();
        $newFreshList = $event->getNewFreshAccountsScholarships();
        $lastFreshList = $event->getLastFreshAccountsScholarships();
        $freshScholarshipsList = [];
        if(!empty($lastFreshList) ) {
                $newScholarships = [];
                foreach ($accList as $key => $acc) {
                    if (isset($lastFreshScholarshipsList[$key])){
                        $accountNewScholarships = array_slice(
                            $acc, array_search(end($lastFreshList[$key]), $acc)+1
                        );
                        if(!empty($accountNewScholarships)){
                            $newScholarships[$key] = $accountNewScholarships;
                        }
                    }else{
                        //fresh scholarships for new accounts
                        $newScholarships[$key] = $acc;
                    }
                }
                $freshScholarshipsList = array_replace_recursive($freshScholarshipsList, $newScholarships);
            }
        $maxScholarshipsInMail = 10;
        $query = $this->em->createQueryBuilder()
            ->select(['a.accountId'])
            ->from(Account::class, 'a')
            ->orderBy('a.accountId')
            ->getQuery();

        foreach (QueryIterator::create($query, 1000) as $profiles) {
            $freshScholarships = $this->accountRepo->findByLastFreshScholarshipsListByIds(array_flip(array_map('current', $profiles)));
            /** @var Profile $profile */
            foreach ($profiles as $profile) {
                $limitScholarship = $scholarships = $this->repository->findBy(['scholarshipId' => $freshScholarships[$profile['accountId']]], ['scholarshipId' => 'DESC']);
                if(count($scholarships) > $maxScholarshipsInMail) {
                    $limitScholarship = array_slice($scholarships, $maxScholarshipsInMail);
                }

                $data = "<table>";
                /** @var Scholarship $scholarship */
                foreach ($limitScholarship as $scholarship){
                    $data .= "<tr>";
                    $data .= "<td>".$scholarship->getTitle()."</td>";
                    $data .= "<td>".$scholarship->getExpirationDate()->format("m/d/Y")."</td>";
                    $data .= "<td>".$scholarship->getAmount()."</td>";
                    $data .= "</tr>";
                }

                $data .= "</table>";

                Mailer::sendMandrillTemplate(
                    Mailer::MANDRILL_RECURRENT_SCHOLARSHIPS_NOTIFY,
                    $profile['accountId'],
                    array(
                        "scholarships" => $limitScholarship,
                        "count" => count($scholarships)
                    )
                );
            }
            $this->em->flush();
            $this->em->clear();
        }

    }

}
