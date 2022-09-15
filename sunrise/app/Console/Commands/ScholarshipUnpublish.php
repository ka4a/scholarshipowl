<?php

namespace App\Console\Commands;

use App\Entities\Scholarship;
use App\Services\ScholarshipManager;
use Illuminate\Console\Command;

class ScholarshipUnpublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:unpublish { scholarship : Id of scholarship to unpublish. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish scholarship without running recurrence mechanism.';

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

        $this->sm->unpublish($scholarship);

        $this->info(sprintf('Scholarship `%s` was unpublished!', $scholarship->getId()));
    }
}
