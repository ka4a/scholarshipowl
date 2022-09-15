<?php

namespace App\Console\Commands;

use App\Entities\Scholarship;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class ScholarshipList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scholarship list.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scholarships = $this->em->getRepository(Scholarship::class)
            ->createQueryBuilder('s')
            ->join('s.template', 't')
            ->where('s.expiredAt IS NULL AND t.deletedAt IS NULL')
            ->getQuery()
            ->getResult();

        /** @var Scholarship $scholarship */
        foreach ($scholarships as $scholarship) {
            $website = $scholarship->getTemplate()->getWebsite();
            $this->info(sprintf(
                '[%s] Scholarship "%s" id: %s',
                $website ? $website->getDomain() : 'NULL',
                $scholarship->getTitle(),
                $scholarship->getId()
            ));
        }
    }
}
