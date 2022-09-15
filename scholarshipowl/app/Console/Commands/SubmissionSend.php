<?php namespace App\Console\Commands;

use App\Entity\Marketing\Submission;
use App\Services\Marketing\CoregService;
use App\Services\Marketing\SubmissionService;
use App\Submissions\AcademixSubmission;
use App\Submissions\BerecruitedSubmission;
use App\Submissions\BirdDogSubmission;
use App\Submissions\CappexDataDealSubmission;
use App\Submissions\CappexSubmission;
use App\Submissions\CollegeExpressSubmission;
use App\Submissions\CwlSubmission;
use App\Submissions\DaneMediaSubmission;
use App\Submissions\GossamerSubmission;
use App\Submissions\InboxDollarsSubmission;
use App\Submissions\ISaySubmission;
use App\Submissions\OpinionOutpostSubmission;
use App\Submissions\TolunaSubmission;
use App\Submissions\WayUpSubmission;
use App\Submissions\ZipRecruiterSubmission;
use App\Submissions\ZuUsaSubmission;
use Illuminate\Console\Command;


/**
 * Submissions cron
 */
class SubmissionSend extends Command
{
    protected $signature = "submission:send {batch? : Number of leads to send in a batch}";
    protected $description = "Sends submissions to endpoints.";

    /** @var SubmissionService */
    private $submissionService;

    /** @var CoregService */
    private $coregService;

    public function __construct(SubmissionService $ss, CoregService $cs)
    {
        $this->submissionService = $ss;
        $this->coregService = $cs;
        parent::__construct();
    }

    protected function storeLog($message)
    {
        \CoregLogger::error($message);
    }

    public function handle()
    {
        $this->info("SubmissionSend Started: " . date("Y-m-d h:i:s"));

        $batch = $this->argument('batch');

//        try {
//            /** @var GossamerSubmission $gossamer */
//            $gossamer = new GossamerSubmission(Submission::NAME_GOSSAMERSCIENCE);
//            $gossamer->submissionSend($batch);
//        } catch (\Throwable $e) {
//            \Log::error('Gossamer submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var GossamerSubmission $gossamer */
//            $gossamer = new GossamerSubmission(Submission::NAME_GOSSAMERSCIENCE);
//            $gossamer->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('Gossamer submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var TolunaSubmission $toluna */
//            $toluna = new TolunaSubmission(Submission::NAME_TOLUNA);
//            $toluna->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('Toluna submission fail. Error: '.$e->getMessage());
//        }
//
//        /** @var AcademixSubmission $academix */
//        /*
//         * Disabled per Kenny request
//         */
//        /*$academix = new AcademixSubmission(Submission::NAME_ACADEMIX);
//        $academix->submissionSend($batch);*/
//
//        try {
//            /** @var BerecruitedSubmission $berecruited */
//            $berecruited = new BerecruitedSubmission(Submission::NAME_BERECRUITED);
//            $berecruited->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('Berecruited submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var DaneMediaSubmission $daneMedia */
//            $daneMedia = new DaneMediaSubmission(Submission::NAME_DANE_MEDIA);
//            $daneMedia->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('DaneMedia submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var ZuUsaSubmission $zuUsa */
//            $zuUsa = new ZuUsaSubmission(Submission::NAME_ZU_USA);
//            $zuUsa->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('ZuUsa submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var WayUpSubmission $wayUp */
//            $wayUp = new WayUpSubmission(Submission::NAME_WAY_UP);
//            $wayUp->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('WayUp submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var CwlSubmission $cwl */
//            $cwl = new CwlSubmission(Submission::NAME_CWL);
//            $cwl->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('Cwl submission fail. Error: '.$e->getMessage());
//        }

//        try {
//            /** @var CappexSubmission $cappex */
//            $cappex = new CappexSubmission(Submission::NAME_CAPPEX);
//            $cappex->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('Cappex submission fail. Error: '.$e->getMessage());
//        }
//
        try {
            /** @var CappexDataDealSubmission $cappexDataDealSubmission */
            $cappexDataDealSubmission = new CappexDataDealSubmission(Submission::NAME_CAPPEXDATADEAL);
            $cappexDataDealSubmission->submissionSend($batch);
        } catch (\Throwable $e) {
            \Log::error('CappexDataDealSubmission submission fail. Error: '.$e->getMessage());
        }
//
//        try {
//            /** @var OpinionOutpostSubmission $opinionOutpost */
//            $opinionOutpost = new OpinionOutpostSubmission(Submission::NAME_OPINION_OUTPOST);
//            $opinionOutpost->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('OpinionOutpost submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var CollegeExpressSubmission $collegeExpress */
//            $collegeExpress = new CollegeExpressSubmission(Submission::NAME_COLLEGE_EXPRESS);
//            $collegeExpress->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('CollegeExpress submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var ZipRecruiterSubmission $collegeExpress */
//            $zipRecruiter = new ZipRecruiterSubmission(Submission::NAME_ZIPRECRUITER);
//            $zipRecruiter->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('ZipRecruiter submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            $birdDogMedia = new BirdDogSubmission(Submission::NAME_BIRDDOG);
//            $birdDogMedia->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('BirdDog submission fail. Error: '.$e->getMessage());
//        }
//
//        try {
//            /** @var InboxDollarsSubmission $inboxDollars */
//            $inboxDollars = new InboxDollarsSubmission(Submission::NAME_INBOXDOLLARS);
//            $inboxDollars->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('InboxDollar submission fail. Error: '.$e->getMessage());
//        }
//
//
//
//        try {
//            /** @var ISaySubmission $iSay */
//            $iSay = new ISaySubmission(Submission::NAME_ISAY);
//            $iSay->submissionSend($batch);
//        } catch (\Throwable $e) {
//            $this->storeLog('I-Say submission fail. Error: '.$e->getMessage());
//        }

        $this->info("SubmissionSend Ended: " . date("Y-m-d h:i:s"));
    }

    protected function getArguments()
    {
        return array();
    }

    protected function getOptions()
    {
        return array();
    }
}
