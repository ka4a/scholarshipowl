<?php

namespace App\Console\Commands;

use App\Entity\Marketing\Submission;
use App\Services\Marketing\CoregService;
use App\Services\Marketing\SubmissionService;
use App\Submissions\SimpleTuitionSubmission;
use Illuminate\Console\Command;

class SubmitSimpleTuitionLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "submission:simpletuition";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Submit Simple Tuition leads";

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
        $this->info("Send SimpleTuition Started: " . date("Y-m-d h:i:s"));

        /** @var SimpleTuitionSubmission $simpleTuition */
        $simpleTuition = new SimpleTuitionSubmission(Submission::NAME_SIMPLE_TUITION);
        $simpleTuition->submissionSend();

        $this->info("Send SimpleTuition Ended: " . date("Y-m-d h:i:s"));

        return;
    }
}
