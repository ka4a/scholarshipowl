<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Services\PubSub\TransactionalEmailService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use ScholarshipOwl\Data\Entity\Scholarship\ApplicationStatus;
use ScholarshipOwl\Util\Mailer;

class ApplicationExpireEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "applications:expireEmails";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send users emails about forthcoming application expiration.";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(Scholarship::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transactionEmailService = app(TransactionalEmailService::class);
        /**
         * @var AccountRepository $accountRepo
         */
        $accountRepo = $this->em->getRepository(Account::class);
        //  Get the applications for scholarships expiring in next 48 hours
        $sql = "
			SELECT 
                a.account_id, s.title
            FROM
                application a
            LEFT JOIN
                scholarship s ON a.scholarship_id = s.scholarship_id
            WHERE
                s.expiration_date BETWEEN DATE_SUB(NOW(), INTERVAL 2 DAY) AND NOW()
                AND 
                a.application_status_id = ?;
		";

        $resultSet = \DB::select($sql, array(ApplicationStatus::IN_PROGRESS));

        $sendMailTo = array();

        foreach ($resultSet as $row) {
            $sendMailTo[$row->account_id][] = $row->title;
        }

        if (!empty($sendMailTo)) {
            foreach ($sendMailTo as $accountId => $scholarships) {
                $eligibilityCount = count($this->repository->findEligibleNotAppliedScholarshipsIds($accountId));

                $scholarshipNames = "";

                foreach ($scholarships as $scholarship){
                    $scholarshipNames.= $scholarship." ";
                }

                $params = array(
                    "eligible_scholarship_count" => $eligibilityCount,
                    "scholarship_names" => $scholarshipNames,
                    "number_of scholarships_expiring" => count($scholarships)
                );
                $account = $accountRepo->findById($accountId);
                $transactionEmailService->sendCommonEmail($account, TransactionalEmailService::APPLICATIONS_EXPIRE_48H, $params);
            }
        }
    }
}
