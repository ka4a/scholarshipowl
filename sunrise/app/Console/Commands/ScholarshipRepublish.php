<?php

namespace App\Console\Commands;

use App\Entities\Scholarship;
use App\Repositories\ScholarshipRepository;
use App\Services\ScholarshipManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class ScholarshipRepublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:republish {scholarshipId : Published scholarship id.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-run scholarship publish event.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $em
     * @param ScholarshipManager $sm
     */
    public function __construct(EntityManager $em, ScholarshipManager $sm)
    {
        parent::__construct();
        $this->sm = $sm;
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var ScholarshipRepository $repository */
        $repository = $this->em->getRepository(Scholarship::class);
        $scholarshipId = $this->argument('scholarshipId');

        if ($scholarshipId === 'all') {
            foreach ($repository->findAllPublished() as $scholarship) {
                $this->republishScholarship($scholarship);
            }

            return;
        }

        $scholarship = $repository->findById($this->argument('scholarshipId'));
        $this->republishScholarship($scholarship);
    }

    /**
     * @param Scholarship $scholarship
     */
    private function republishScholarship(Scholarship $scholarship)
    {
        $this->sm->republish($scholarship);
        $this->info(sprintf('Scholarship "%s" was republished!', $scholarship->getId()));
    }
}
