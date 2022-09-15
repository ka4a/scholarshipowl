<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Log\LoginHistory;
use App\Entity\Resource\Resource;
use App\Entity\Scholarship;
use App\Entity\SubscriptionAcquiredType;
use App\Mail\LoopLeads;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;

class LoopExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loop:export {--date= : Provide date for report. Format: YYYY-MM-DD}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export loop customer report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("LoopExport Started: " . date("Y-m-d h:i:s"));

        $date = $this->option('date')?new \DateTime($this->option('date')):null;

        $fileName = "CustomerReport_" . ($date?$this->option('date'):date("Y-m-d")) . ".csv";
        $writer = \CsvWriter::create(storage_path('framework/cache') . '/' . $fileName);

        $writer->writeLine(["ID", "Domain", "First Name", "Last Name", "Phone", "Email", "Last active Membership ID", "Membership name", "Membership status", 'Membership free trial', "Membership initial purchase date", "Last renewal date", "Upcoming renewal date", "Decline reason", "Payment Processor", "Login days", "Last login date", "# of submitted scholarships", "# of submitted scholarships with requirement", "Last scholarship submission date", "# of times logged in for the current month", "# of times logged in for the immediate prior month", "Date last essay submitted", "Last amount paid", "# of Essays Submitted", "# of Scholarships Eligible", "Credit card Type", "Consent to be called", "Call1", "Call2", "Call3", "Call4", "Call5"]);

        $sqlDate = $date ? "'".$date->format('Y-m-d')."'" : "DATE(DATE_SUB(CURRENT_DATE(), 1, 'DAY'))";

        $dql = "SELECT a FROM App\Entity\Account a JOIN a.subscriptions s WHERE s.subscriptionAcquiredType IN (?1) and a.sellInformation != 1 AND DATE(a.createdDate) = ".$sqlDate." ORDER BY a.accountId DESC";

        $query = \EntityManager::createQuery($dql)
            ->setParameter(1, array(SubscriptionAcquiredType::MISSION, SubscriptionAcquiredType::PURCHASED, SubscriptionAcquiredType::REFERRAL, SubscriptionAcquiredType::REFERRED));

        $accounts = $query->getResult();

        $data = array();
        $accountIds = array();

        if(!empty($accounts)) {
            /** @var Account $account */
            foreach ($accounts as $account) {
                $data[$account->getAccountId()]["account"] = $account;

                /** @var \App\Entity\Subscription $subscription */
                $subscription = $account->getSubscriptions()->last();
                if ($subscription !== null) {
                    $data[$account->getAccountId()]["subscription"] = $subscription;
                    $transaction = $subscription->getTransactions()->last();
                    if ($transaction !== null) {
                        $data[$account->getAccountId()]["transaction"] = $transaction;
                    }
                }

                $data[$account->getAccountId()]["textCount"] = $account->getApplicationText()->count();

                $loginCount = \EntityManager::createQuery("SELECT COUNT(lh) FROM \App\Entity\Log\LoginHistory lh WHERE lh.action = ?1 AND lh.account = ?2");
                $loginCount->setParameter(1, LoginHistory::ACTION_LOGIN);
                $loginCount->setParameter(2, $account);

                $data[$account->getAccountId()]["loginCount"] = $loginCount->getSingleScalarResult();

                $loginCountMonth = \EntityManager::createQuery("SELECT COUNT(lh) FROM \App\Entity\Log\LoginHistory lh WHERE lh.action = ?1 AND lh.account = ?2 AND YEAR(lh.actionDate) = YEAR(CURRENT_DATE()) AND MONTH(lh.actionDate) = MONTH(CURRENT_DATE())");
                $loginCountMonth->setParameter(1, LoginHistory::ACTION_LOGIN);
                $loginCountMonth->setParameter(2, $account);
                $data[$account->getAccountId()]["loginCountMonth"] = $loginCountMonth->getSingleScalarResult();

                $loginCountPreviousMonth = \EntityManager::createQuery("SELECT COUNT(lh) FROM \App\Entity\Log\LoginHistory lh WHERE lh.action = ?1 AND lh.account = ?2 AND YEAR(lh.actionDate) = YEAR(DATE_SUB(CURRENT_DATE() , 1, 'MONTH')) AND MONTH(lh.actionDate) = MONTH(DATE_SUB(CURRENT_DATE() , 1, 'MONTH'))");
                $loginCountPreviousMonth->setParameter(1, LoginHistory::ACTION_LOGIN);
                $loginCountPreviousMonth->setParameter(2, $account);
                $data[$account->getAccountId()]["loginCountPreviousMonth"] = $loginCountPreviousMonth->getSingleScalarResult();

                $historyRepository = \EntityManager::getRepository(LoginHistory::class)->findBy(
                    ['account' => $account->getAccountId(), 'action' => LoginHistory::ACTION_LOGIN],
                    ['loginHistoryId' => 'DESC'],
                    1
                );
                $data[$account->getAccountId()]["lastLogin"] = Resource::getResourceCollection($historyRepository);

                $data[$account->getAccountId()]["lastApplication"] = !$account->getApplications()->isEmpty() ? $account->getApplications()->last() : null;

                /** @var ScholarshipRepository $scholarshipRepository */
                $scholarshipRepository = \EntityManager::getRepository(Scholarship::class);
                $data[$account->getAccountId()]["scholarshipCount"] = $scholarshipRepository->findEligibleScholarships($account);

                $data[$account->getAccountId()]["onboardingCalls"] = $account->getAccountOnBoardingCall();
                $data[$account->getAccountId()]["agreeCall"] = $account->getProfile()->getAgreeCall();

                $accountIds[] = $account->getAccountId();
            }

            $applicationService = new ApplicationService();
            $data["applicationsCount"] = $applicationService->getSubmittedApplicationsCount($accountIds);
            $data["applicationsWithRequirementsCount"] = $applicationService->getSubmittedApplicationsWithRequirementsCount($accountIds);
            $data["lastEssaySubmitted"] = $applicationService->getLastSubmittedApplicationWithEssay($accountIds);

            foreach ($data as $account) {
                if(isset($account["account"])) {
                    $writer->writeLine([
                        $account["account"]->getAccountId(),
                        $account['account']->getDomain(),
                        $account["account"]->getProfile()->getFirstName(),
                        $account["account"]->getProfile()->getLastName(),
                        $account["account"]->getProfile()->getPhone(),
                        $account["account"]->getEmail(),
                        $account["subscription"] ? $account["subscription"]->getSubscriptionId() : "",
                        $account["subscription"] ? $account["subscription"]->getName() : "",
                        $account["subscription"] ? $account["subscription"]->getSubscriptionStatus()->getName() : "",
                        ($account['subscription'] && $account['subscription']->isFreeTrial()) ? 'Yes' : 'No',
                        $account["subscription"] ? $account["subscription"]->getStartDate()->format("Y-m-d") : "",
                        $account["transaction"] ? $account["transaction"]->getCreatedDate()->format("Y-m-d") : "",
                        $account["subscription"] ? $account["subscription"]->getEndDate()->format("Y-m-d") : "",
                        $account["transaction"] ? $account["transaction"]->getFailedReason() : "",
                        $account["transaction"] ? $account["transaction"]->getPaymentMethod()->getName() : "",
                        $account["loginCount"],
                        $account["lastLogin"]->first()->getActionDate()->format("Y-m-d"),
                        isset($applicationsCount[$account["account"]->getAccountId()]) ? $applicationsCount[$account["account"]->getAccountId()] : "0",
                        isset($applicationsWithRequirementsCount[$account["account"]->getAccountId()]) ? $applicationsWithRequirementsCount[$account["account"]->getAccountId()] : "0",
                        isset($account["lastApplication"]) ? $account["lastApplication"]->getDateApplied()->format("Y-m-d") : "",
                        $account["loginCountMonth"] ? $account["loginCountMonth"] : "0",
                        $account["loginCountPreviousMonth"] ? $account["loginCountPreviousMonth"] : "0",
                        isset($lastEssaySubmitted[$account["account"]->getAccountId()]) ? $lastEssaySubmitted[$account["account"]->getAccountId()] : "",
                        $account["transaction"] ? "$" . $account["transaction"]->getAmount() : "",
                        $account["textCount"] ? $account["textCount"] : "0",
                        $account["scholarshipCount"] ? $account["scholarshipCount"] : "0",
                        $account["transaction"] ? $account["transaction"]->getCreditCardType() : "",
                        $account["agreeCall"]?"Yes":"No",
                        $account["onboardingCalls"][0] ? ($account["onboardingCalls"][0]->getCall1() ? "Yes" : "No") : "No",
                        $account["onboardingCalls"][0] ? ($account["onboardingCalls"][0]->getCall2() ? "Yes" : "No") : "No",
                        $account["onboardingCalls"][0] ? ($account["onboardingCalls"][0]->getCall3() ? "Yes" : "No") : "No",
                        $account["onboardingCalls"][0] ? ($account["onboardingCalls"][0]->getCall4() ? "Yes" : "No") : "No",
                        $account["onboardingCalls"][0] ? ($account["onboardingCalls"][0]->getCall5() ? "Yes" : "No") : "No"
                    ]);
                }
            }
        }

        $writer->close();
        $to = \Config::get("scholarshipowl.mail.system.loop_report.to");

        foreach ($to as $recipient){
            $data = [
                'from' => [
                    "address" => "ScholarshipOwl@ScholarshipOwl.com",
                    "name" => "ScholarshipOwl Mailer"
                ],
                'subject' => "Daily CSV Report",
                'attach' => [
                    'file' => storage_path('framework/cache') . '/' . $fileName,
                    'name' => $fileName
                ],
                'to' => $recipient
            ];

            Mail::send(new LoopLeads($data));
        }

        $this->info("LoopExport Ended: " . date("Y-m-d h:i:s"));
    }
}
