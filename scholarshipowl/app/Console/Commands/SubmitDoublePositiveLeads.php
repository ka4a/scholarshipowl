<?php

namespace App\Console\Commands;

use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use App\Services\Marketing\CoregService;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SubmitDoublePositiveLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:doublepositive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit Double Positive leads';

    /**
     * @var CoregService
     */
    private $cs;

    /**
     * @var SubmissionService
     */
    private $ss;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CoregService $cs, SubmissionService $ss)
    {
        $this->cs = $cs;
        $this->ss = $ss;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Send DoublePositive Started: " . date("Y-m-d h:i:s"));

        if($this->cs->getCoregPluginByName(Submission::NAME_DOUBLE_POSITIVE)->getVisible()){
            $fileName = "dp_" . date("d-m-Y") . ".csv";
            $file = storage_path('framework/cache/' . $fileName);
            $fp = fopen($file, 'w');
            $headers = array("First Name", "Last Name", "Address", "City", "State", "Zip", "Home Phone", "Email Address", "DegreeOfInterest", "ProgramOfInterest", "UniversalLeadID", "HighSchoolGradYear");

            fputcsv($fp, $headers);

            /** @var Submission[] $simpleTuitionSubmissionService */
            $submissions = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_DOUBLE_POSITIVE, Submission::STATUS_PENDING);

            /** @var Submission $submission */
            foreach ($submissions as $submission) {
                $account = $submission->getAccount();
                $submissionId = $submission->getSubmissionId();
                try {
                    /** @var Profile $profile */
                    $profile = $submission->getAccount()->getProfile();

                    $params = json_decode($submission->getParams());

                    $row = [
                        $profile->getFirstName(),
                        $profile->getLastName(),
                        $profile->getAddress(),
                        $profile->getCity(),
                        $profile->getState()->getAbbreviation(),
                        $profile->getZip(),
                        $profile->getPhone(),
                        $account->getEmail(),
                        $this->formatDegree($profile->getDegreeType()->getId()),
                        $params->program,
                        $params->universalLeadID,
                        $profile->getGraduationYear()
                    ];

                    fputcsv($fp, $row);

                    $this->ss->updateSuccessSubmission($submissionId,
                        "Added to CSV");
                }
                catch (EntityNotFoundException $notFound){
                    \Log::info("Account not found while sending submission [$submissionId]");
                    $this->ss->updateErrorSubmission($submissionId, 'Account was deleted');
                }
                catch (\Throwable $e){
                    $this->ss->updateErrorSubmission($submission->getSubmissionId(), $e->getMessage());
                }
            }

            fclose($fp);

            if(is_production()){
                Mail::send("emails.system.cron.double-positive-email", array(), function($message) use ($file, $fileName){
                    $message->from("ScholarshipOwl@ScholarshipOwl.com", "ScholarshipOwl Mailer");
                    $message->subject("Daily CSV Report");
                    $message->attach($file, array("as" => $fileName));
                    $message->to(array("mfraser@doublepositive.com", "ken@scholarshipowl.com"));
                });
            }else{
                Mail::send("emails.system.cron.double-positive-email", array(), function($message) use ($file, $fileName){
                    $message->from("ScholarshipOwl@ScholarshipOwl.com", "ScholarshipOwl Mailer");
                    $message->subject("Daily CSV Report");
                    $message->attach($file, array("as" => $fileName));
                    $message->to(array("ivank@scholarshipowl.com", "jelenas@scholarshipowl.com"));
                });
            }
        }

        $this->info("Send DoublePositive Ended: " . date("Y-m-d h:i:s"));

        return;
    }

    private function formatDegree($degreeId){
        switch ($degreeId){
            case 3:
                return "Associates";
            case 4:
                return "Bachelors";
            case 6:
                return "Masters";
        }

        return;
    }
}
