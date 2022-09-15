<?php

namespace App\Console\Commands;

use App\Entities\Application;
use App\Events\ApplicationAwardedEvent;
use App\Services\ScholarshipManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;

class ScholarshipMaintain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:maintain {--date=now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maintain scholarship template.';

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
        $start = microtime(true);

        Event::listen(ApplicationAwardedEvent::class, function(ApplicationAwardedEvent $event) {
            /** @var Application $application */
            $application = $this->em->getRepository(Application::class)->find($event->getApplicationId());

            $this->warn(sprintf('Application awarded: %s', $application->getId()));
            $this->info(sprintf('Application name: %s', $application->getName()));
            $this->info(sprintf('Application email: %s', $application->getEmail()));
            $this->info(sprintf('Application phone: %s', $application->getPhone()));
        });

        $this->sm->maintain(new \DateTime($this->option('date')));
        $this->info(sprintf('Scholarship maintain finished. Time: %s', microtime(true) - $start));
    }
}
