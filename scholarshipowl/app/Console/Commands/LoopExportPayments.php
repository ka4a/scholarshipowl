<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Log\LoginHistory;
use App\Entity\Resource\Resource;
use App\Entity\Scholarship;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;

class LoopExportPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loop:export-payments {--date= : Provide date for report. Format: YYYY-MM-DD}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export loop customer report with first payment';

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

        $fileName = "CustomerReportPayment_" . ($date?$this->option('date'):date("Y-m-d")) . ".csv";
        $writer = \CsvWriter::create(storage_path('framework/cache') . '/' . $fileName);

        $writer->writeLine(["ID", "First Name", "Last Name", "Phone", "Email", "Subscription type", "Subscription cost", "Payment date", "Recurring payment count", "Renewal date"]);

        $sqlDate = $date ? "'".$date->format('Y-m-d')."'" : "DATE(DATE_SUB(CURRENT_DATE(), 1, 'DAY'))";

        $dql = "SELECT t FROM App\Entity\Transaction t JOIN t.account a WHERE DATE(t.createdDate) = ".$sqlDate." and a.sellInformation != 1 AND t.recurrentNumber = 1 GROUP BY a.accountId ORDER BY t.createdDate DESC";

        $query = \EntityManager::createQuery($dql);

        $transactions = $query->getResult();

        if(!empty($transactions)) {
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                $account = $transaction->getAccount();

                $writer->writeLine([
                    $account->getAccountId(),
                    $account->getProfile()->getFirstName(),
                    $account->getProfile()->getLastName(),
                    $account->getProfile()->getPhone(),
                    $account->getEmail(),
                    $transaction->getSubscription()->getName(),
                    "$".$transaction->getSubscription()->getPrice(),
                    $transaction->getCreatedDate()->format("Y-m-d"),
                    $transaction->getRecurrentNumber(),
                    $transaction->getSubscription()->getRenewalDate()->format("Y-m-d"),
                ]);
            }
        }

        $writer->close();
        $to = \Config::get("scholarshipowl.mail.system.loop_report_payment.to");

        foreach ($to as $recipient){
            Mail::send("emails.system.cron.customer-report", array(), function($message) use ($fileName, $recipient){
                $message->from("ScholarshipOwl@ScholarshipOwl.com", "ScholarshipOwl Mailer");
                $message->subject("Paying members onboarding list");
                $message->attach(storage_path('framework/cache') . '/' . $fileName, array("as" => $fileName));
                $message->to($recipient);
            });
        }

        $this->info("LoopExport Ended: " . date("Y-m-d h:i:s"));
    }
}
