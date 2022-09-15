<?php

namespace App\Console\Commands;

use App\Entities\Passport\OauthClient;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class ZapierClient extends Command
{
    const CLIENT_NAME = 'Zapier client';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zapier:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Zapier OAuth 2 client details';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Create a new command instance.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle()
    {
        /** @var RestRepository $repository */
        $repository = $this->em->getRepository(OauthClient::class);
        if (null === ($client = $repository->findOneBy(['name' => static::CLIENT_NAME]))) {
            $client = new OauthClient();
            $this->em->persist($client);
            $this->warn('New SOWL client created!');
        }

        $client->setName(static::CLIENT_NAME);
        $client->setRedirect(config('services.zapier.redirect_uri'));
        $this->em->flush($client);

        $this->info(sprintf('SOWL client id: %s', $client->getId()));
        $this->info(sprintf('SOWL client secret: %s', $client->getSecret()));
    }
}
