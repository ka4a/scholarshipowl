<?php

namespace App\Console\Commands;

use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use App\Mail\ChristianConnectorLeads;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SubmitChristianConnectorLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:christianconnector';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit Christian Connector leads';

    /**
     * @var SubmissionService
     */
    private $ss;

    /**
     * @var DegreeService
     */
    private $ds;

    /**
     * @var EthnicityService
     */
    private $es;

    /**
     * Create a new command instance.
     *
     * @param SubmissionService $ss
     *
     * @return void
     */
    public function __construct(SubmissionService $ss)
    {
        parent::__construct();

        $this->ss = $ss;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Send Christian Connector Started: " . date("Y-m-d h:i:s"));

        $fileName = "cc_" . date("d-m-Y") . ".csv";
        $file = storage_path('framework/cache/' . $fileName);
        $fp = fopen($file, 'w');
        $headers = array("First Name", "Last Name", "Address", "City", "State", "Zip Code", "Email Address", "High School Grad Year", "High School GPA", "Major of Interest", "Gender", "Ethnic Background", "High School Name", "Telephone");

        fputcsv($fp, $headers);

        $submissionsChristianConnector = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_CHRISTIAN_CONNECTOR, Submission::STATUS_PENDING);

        /** @var Submission $submission */
        foreach ($submissionsChristianConnector as $submission) {
            $account = $submission->getAccount();
            try {
                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                $degree = $profile->getDegree()->getName();
                $ethnicity = $profile->getEthnicity()->getName();

                $row = [
                    $profile->getFirstName(),
                    $profile->getLastName(),
                    $profile->getAddress(),
                    $profile->getCity(),
                    $profile->getState()->getName(),
                    $profile->getZip(),
                    $account->getEmail(),
                    $profile->getHighschoolGraduationYear(),
                    $profile->getGpa(),
                    $degree,
                    $profile->getGender(),
                    $ethnicity,
                    $profile->getHighSchool(),
                    str_replace("+1", "", $profile->getPhone()),
                ];

                fputcsv($fp, $row);

                $this->ss->updateSuccessSubmission($submission->getSubmissionId(),
                    "Added to CSV");
            }
            catch (EntityNotFoundException $notFound){
                \CoregLogger::error($notFound);
                $this->ss->updateErrorSubmission($submission->getSubmissionId(), 'Account was deleted');
            }
            catch (\Throwable $e){
                $this->info("Account not found: " . $account->getAccountId());
            }
        }

        fclose($fp);
        try {
            $data = [
                'from' => [
                    "address" => "ScholarshipOwl@ScholarshipOwl.com",
                    "name" => "ScholarshipOwl Mailer"
                ],
                'subject' => "Daily CSV Report",
                'attach' => [
                    'file' => $file,
                    'name' => $fileName
                ],
                'to' => "Thom@ChristianConnector.com"
            ];

            Mail::send(new ChristianConnectorLeads($data));

            $failedRecipients = Mail::failures();
            if (count($failedRecipients) > 0) {
                $failedRecipientsString = implode(', ',$failedRecipients);
                throw new \Exception("Could not send email to $failedRecipientsString");

            }
        }catch (\Exception $e){
            \Sentry::captureException($e);
            \CoregLogger::error("Christian Connector submission error: " . get_class($e). " - " . $e->getMessage());
        }

        $this->info("Send Christian Connector Ended: " . date("Y-m-d h:i:s"));
    }
}
