<?php

namespace App\Console\Commands;

use App\Entities\Organisation;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipWebsite;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipTemplateCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:template:create {organisation : Organisation Id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create scholarship template manually.';

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
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Organisation $organisation */
        $organisation = RestRepository::create($this->em, Organisation::class)->findById($this->argument('organisation'));
        $template = new ScholarshipTemplate();
        $template->setOrganisation($organisation);

        $template->setTitle($this->ask('Scholarship title', 'Test scholarship template title'));
        $template->setDescription($this->ask('Scholarship description', 'Test scholarship template description'));

        $template->setAmount($this->ask('Award amount', 500));

        $template->setStart(
            $this->ask(sprintf('Scholarship start date in. Example: %s', (new \DateTime())->format('Y-m-d')), (new \DateTime())->format('Y-m-d'))
        );

        $template->setDeadline(
            $this->ask(sprintf('Scholarship deadline. Example: %s', (new \DateTime())->format('Y-m-d')), (new \DateTime('+1 day'))->format('Y-m-d'))
        );

        $template->setRecurringValue($this->ask('Provide recurrence period value', 1));
        foreach (array_keys(Scholarship::$recurrenceTypes) as $type) {
            $this->info(sprintf('[%s]', $type));
        }
        $template->setRecurringType($this->ask('Provide recurrence period type', 'day'));

        $website = new ScholarshipWebsite();
        $website->setDomain($this->ask('Website domain. Example: example.com', 'example.com'));
        $website->setLayout($this->ask('Layout design', 'kiwi'));
        $website->setVariant($this->ask('Layout variant', 'kiwi-navy'));
        $website->setCompanyName($this->ask('Company name', $organisation->getName()));
	$website->setLayout('default');
	$website->setVariant('default');
        $website->setTitle($this->ask('Website title', 'Best Scholarship!'));
        $website->setIntro($this->ask('Website subtitle', 'Test scholarship description subtitle'));
        $template->setWebsite($website);


        $this->em->persist($website);
        $this->em->persist($template);
        $this->em->flush();

        $this->warn(sprintf('Scholarship template created: %s', $template->getId()));
    }
}
