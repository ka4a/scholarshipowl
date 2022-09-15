<?php

namespace App\Console\Commands;

use App\Entities\Organisation;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class OrganisationCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organisation:create {name : Organisation Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new organisation.';

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
        $organisation = new Organisation();
        $organisation->setName($this->argument('name'));

        $this->em->persist($organisation);
        $this->em->flush($organisation);

        $this->warn(sprintf('Congratulations "%s" organisation created!', $organisation));
        $this->info(sprintf('Organisation ID: %s', $organisation->getId()));
        $this->info(sprintf('Organisation API token: %s', $organisation->getApiToken()));
    }
}
