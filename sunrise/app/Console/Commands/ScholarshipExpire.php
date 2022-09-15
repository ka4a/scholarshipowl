<?php

namespace App\Console\Commands;

use App\Entities\Scholarship;
use App\Services\ScholarshipManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:expire {scholarship : Id of scholarship to expire}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force expire (deadline) scholarship.';

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * Create a new command instance.
     *
     * @param ScholarshipManager $sm
     */
    public function __construct(ScholarshipManager $sm)
    {
        parent::__construct();
        $this->sm = $sm;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->sm->published()->findById($this->argument('scholarship'));

        $this->sm->expire($scholarship);

        $this->info(sprintf('Scholarship `%s` was expired!', $scholarship->getId()));
    }
}
