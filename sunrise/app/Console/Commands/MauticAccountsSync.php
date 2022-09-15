<?php

namespace App\Console\Commands;

use App\Doctrine\QueryIterator;
use App\Entities\MauticContact;
use App\Services\MauticService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use League\CLImate\CLImate;
use League\CLImate\TerminalObject\Dynamic\Progress;
use Pz\Doctrine\Rest\RestRepository;

class MauticAccountsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mautic:accounts:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @throws \Exception
     */
    public function handle()
    {
        /** @var RestRepository $repository */
        $repository = $this->em->getRepository(MauticContact::class);
        $climate = new CLImate();
        $total = $repository->createQueryBuilder('mc')
            ->select('COUNT(1)')
            ->getQuery()
            ->getSingleScalarResult();

        /** @var Progress $progress */
        $progress = $climate->progress(intval($total));

        $this->info(sprintf('Syncing %d mautic contacts', $total));

        /** @var MauticContact[] $contacts */
        foreach (QueryIterator::create($repository->createQueryBuilder('mc')->getQuery()) as $contacts) {
            foreach ($contacts as $contact) {
                $synced = $this->mautic->syncContact($contact);
                if ($synced->getId() !== $contact->getId()) {
                    $this->info(sprintf('New mautic contact generated for: %s', $synced->getEmail()));
                }

                $progress->advance();
            }
        }

        $this->info('Finished sync');
    }
}
