<?php

namespace App\Console\Commands;

use App\Entities\ScholarshipTemplate;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class ScholarshipTemplateList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:template:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of scholarship templates.';

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
        $templates = $this->em->getRepository(ScholarshipTemplate::class)
            ->createQueryBuilder('t')
            ->getQuery()
            ->getResult();

        /** @var ScholarshipTemplate[] $templates */
        foreach ($templates as $template) {
            $this->info(sprintf('Scholarship template "%s" id: %s', $template->getTitle(), $template->getId()));
        }
    }
}
