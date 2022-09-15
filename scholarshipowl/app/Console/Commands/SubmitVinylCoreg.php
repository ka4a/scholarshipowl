<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Log\LoginHistory;
use App\Entity\Marketing\Submission;
use App\Entity\Marketing\SubmissionSources;
use App\Entity\Profile;
use App\Services\Marketing\SubmissionService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SubmitVinylCoreg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:vinyl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit Vinyl coreg daily leads';

    /**
     * @var SubmissionService
     */
    private $ss;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(SubmissionService $ss, EntityManager $em)
    {
        parent::__construct();

        $this->ss = $ss;
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Send Vinyl coreg started: " . date("Y-m-d h:i:s"));

        $fileName = "#vinyl_coreg_Lnkreport leads_" . date("d-m-Y") . ".csv";
        $file = storage_path('framework/cache/' . $fileName);
        $fp = fopen($file, 'w');
        $headers = ["sid", "survey1", "sourceurl", "age", "address2",
                    "areaofstudy", "city", "dob_day",
                    "dob_month", "dob_year", "tcpadate",
                    "email", "firstname", "gender", "home_areacode",
                    "home_prefix", "home_home_suffix", "lastname", "education",
                    "state", "address1", "zipcode"];

        fputcsv($fp, $headers);

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('p')
            ->from(Profile::class, 'p')
            ->leftJoin(Account::class, 'a', 'WITH', 'a.accountId = p.account')
            ->where(' FLOOR(DATEDIFF(CURRENT_DATE(), p.dateOfBirth) / 365.25) > 18' )
            ->andWhere('p.enrolled != 1 ')
            ->andWhere('p.country = 1')
            ->andWhere('a.domain = 1')
            ->andWhere('a.sellInformation != 1')
            ->andWhere('DATE(a.createdDate) = CURRENT_DATE()')
            ->getQuery();

        $profileList = $query->getResult();
        $profileCount = count($profileList);
        $this->info("Working for  $profileCount accounts");

        /**
         * @var LoginHistory
         */
        $history = $this->em->getRepository(\App\Entity\Log\LoginHistory::class);

        /** @var Profile $profile */
        foreach ($profileList as $profile) {
            $account = $profile->getAccount();
            try {
                /**
                 * @var LoginHistory
                 */
                $loginHistory = $history->findBy(
                    ['account' => $account->getAccountId()],
                    ['loginHistoryId' => 'DESC']
                );
                $educationYears = 0;

                if ($profile->getEnrollmentDate() != '') {
                    $enrollmentDate = Carbon::createFromFormat(
                        'm/d/Y', $profile->getEnrollmentDate()
                    );
                    $created = Carbon::instance($account->getCreatedDate());
                    $educationYears = $created->diffInYears($enrollmentDate);
                }

                $ip = isset($loginHistory[0]) ? $loginHistory[0]->getIpAddress()
                    : '';
                $degree = !is_null($profile->getDegree())
                    ? $profile->getDegree()->getName() : '';

                $fullPhone = str_replace("+1", "", $profile->getPhone());

                $numberLen = strlen($fullPhone);
                $phoneAreaCode = substr($fullPhone, -$numberLen,
                    $numberLen - 7);
                $phonePrefix = substr($fullPhone, -7, 3);
                $phoneLocal = substr($fullPhone, -4);

                $row = [
                    'DSA0102',
                    $ip,
                    'scholarshipowl.com',
                    $profile->getAge(),
                    $profile->getAddress2(),
                    $degree,
                    $profile->getCity(),
                    $profile->getDateOfBirthDay(),
                    $profile->getDateOfBirthMonth(),
                    $profile->getDateOfBirthYear(),
                    $account->getCreatedDate()->format('Y-m-d\TH:i:s'),
                    $account->getEmail(),
                    $profile->getFirstName(),
                    $profile->getGender(),

                    $phoneAreaCode,
                    $phonePrefix,
                    $phoneLocal,

                    $profile->getLastName(),
                    $educationYears . 'y',
                    $profile->getState() != null ? $profile->getState()
                        ->getAbbreviation() : '',
                    $profile->getAddress(),
                    $profile->getZip()
                ];

                //store data about this coreg to DB
                $this->ss->addSubmissions(
                    [
                        Submission::NAME_VINYL => [
                            'checked' => 1,
                            'extra'   => $row
                        ]
                    ], $account, $ip, SubmissionSources::SYSTEM
                );

                fputcsv($fp, $row);
            }
            catch (\Throwable $exception){
                \CoregLogger::error($exception);
            }
        }

        fclose($fp);

        Mail::send("emails.system.cron.vinyl-report", array(), function($message) use ($file, $fileName){
            $message->from("ScholarshipOwl@ScholarshipOwl.com", "ScholarshipOwl Mailer");
            $message->subject("Daily CSV Report");
            $message->attach($file, array("as" => $fileName));
            $message->to(array("bernadette@vinylinteractive.com", "ken@scholarshipowl.com", "alexandras@scholarshipowl.com"));
        });

        $this->updateInactiveSubmissions();

        $this->info("Send Vinyl coreg Ended: " . date("Y-m-d h:i:s"));
    }

    protected function updateInactiveSubmissions()
    {
        $submissionsVinyl = $this->ss->getSubmissionsAccountsByNameAndStatus(
            Submission::NAME_VINYL, Submission::STATUS_PENDING
        );

        /** @var Submission $submission */
        foreach ($submissionsVinyl as $submission) {
            $this->ss->updateSuccessSubmission(
                $submission->getSubmissionId(), "Added to CSV"
            );
        }
    }
}
