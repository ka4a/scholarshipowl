<?php

namespace App\Console\Commands;

use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Services\ScholarshipService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class ScholarshipMaintain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:maintain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire/activate scholarships. Command should run each 10 minutes.';

    /**
     * @var ScholarshipService
     */
    protected $service;

    /**
     * ScholarshipExpire constructor.
     *
     * @param ScholarshipService $service
     */
    public function __construct(ScholarshipService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = $this->service->maintain();

        $this->info(sprintf('%s - scholarships where activated.', $results[0]));
        $this->info(sprintf('%s - scholarships where deactivated.', $results[1]));
        $this->info(sprintf('%s - scholarships where recur.', $results[2]));
    }
}
