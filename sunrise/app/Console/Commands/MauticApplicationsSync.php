<?php

namespace App\Console\Commands;

use App\Doctrine\QueryIterator;
use App\Entities\Application;
use App\Entities\Scholarship;
use App\Repositories\ScholarshipRepository;
use App\Services\MauticService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class MauticApplicationsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mautic:applications:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync application from active scholarships into mautic.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var MauticService
     */
    protected $mautic;

    /**
     * MauticApplicationsSync constructor.
     *
     * @param EntityManager $em
     * @param MauticService $mautic
     */
    public function __construct(EntityManager $em, MauticService $mautic)
    {
        parent::__construct();
        $this->em = $em;
        $this->mautic = $mautic;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle()
    {
        $scholarshipsQuery = $this->em->getRepository(Scholarship::class)
            ->createQueryBuilder('s')
            ->where('s.expiredAt IS NULL')
            ->getQuery();

        /** @var Scholarship[] $scholarships */
        foreach (QueryIterator::create($scholarshipsQuery) as $scholarships) {
            foreach ($scholarships as $scholarship) {
                $this->syncScholarshipApplications($scholarship);
                $this->info(sprintf('Synced "%s" scholarship (%s).', $scholarship->getTitle(), $scholarship->getId()));
            }
            $this->em->clear(Scholarship::class);
        }
        $this->warn('Synchronization finished');
    }

    /**
     * Synchronize all applications of the scholarship.
     *
     * @param Scholarship $scholarship
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function syncScholarshipApplications(Scholarship $scholarship)
    {
        $applicationsQuery = $this->em->getRepository(Application::class)
            ->createQueryBuilder('a')
            ->where('a.scholarship = :scholarship')
            ->setParameter('scholarship', $scholarship)
            ->getQuery();

        /** @var Application[] $applications */
        foreach (QueryIterator::create($applicationsQuery) as $applications) {
            foreach ($applications as $application) {
                $this->mautic->syncApplication($application);
            }
            $this->em->clear(Application::class);
        }
    }
}
