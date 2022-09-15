<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\SuperCollegeScholarship;
use App\Entity\SuperCollegeScholarshipMatch;
use App\Services\SuperCollege\SuperCollegeService;
use Illuminate\Console\Command;

class GetSupercollegeEligibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supercollege:eligibility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate users eligibility for SuperCollege scholarships';

    /**
     * @var SuperCollegeService
     */
    protected $superCollegeService;

    /**
     * Create a new command instance.
     *
     * @param SuperCollegeService $superCollegeService
     *
     * @return void
     */
    public function __construct(SuperCollegeService $superCollegeService)
    {
        $this->superCollegeService = $superCollegeService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->superCollegeService->updateEligibilityForAllAccounts();
    }
}
