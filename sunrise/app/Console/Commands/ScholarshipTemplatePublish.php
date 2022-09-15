<?php

namespace App\Console\Commands;

use App\Entities\ScholarshipTemplate;
use App\Services\ScholarshipManager;

use Illuminate\Console\Command;

use Doctrine\ORM\EntityManager;

use Pz\Doctrine\Rest\RestRepository;

class ScholarshipTemplatePublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:template:publish {template : Provide scholarship template id.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish template scholarship.';

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
        $this->em = $em;
        $this->sm = $sm;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var RestRepository $templates */
        $templates = $this->em->getRepository(ScholarshipTemplate::class);
        /** @var ScholarshipTemplate $template */
        $template = $templates->findById($this->argument('template'));
        $scholarship = $this->sm->publish($template);

        $this->warn(sprintf('New scholarship created: %s', $scholarship->getId()));
        $this->info(sprintf('Scholarship title: %s', $scholarship->getTitle()));
    }
}
