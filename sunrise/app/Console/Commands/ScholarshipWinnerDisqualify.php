<?php

namespace App\Console\Commands;

use App\Entities\ApplicationWinner;
use App\Events\ApplicationWinnerDisqualified;
use App\Services\MauticService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipWinnerDisqualify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:winner:disqualify { id : Winner\'s ID }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disqualify winner without running new winner choosing mechanism.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var MauticService
     */
    protected $mautic;

    /**
     * Create a new command instance.
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
     * @return mixed
     */
    public function handle()
    {
        /** @var RestRepository $repository */
        $repository = $this->em->getRepository(ApplicationWinner::class);

        /** @var ApplicationWinner $winner */
        $winner = $repository->findById($this->argument('id'));
        $application = $winner->getApplication();

        $message = sprintf('Are you sure want to disqualify "%s" (%s)', $application->getName(), $application->getId());
        if (!$this->confirm($message)) {
            return;
        }

        $this->em->flush($winner->setDisqualifiedAt(new \DateTime()));
        ApplicationWinnerDisqualified::dispatch($winner);
        $this->mautic->notifyWinner($application, ScholarshipWinnerNotification::DISQUALIFICATION);
    }
}
