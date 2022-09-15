<?php

namespace App\Console\Commands;

use App\Entities\Passport\OauthClient;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class SowlClient extends Command
{
    const CLIENT_NAME = 'SOWL client';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sowl:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate or return new scholarshipowl.com OAuth2 client.';

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
     * @return void
     */
    public function handle()
    {
        /** @var RestRepository $repository */
        $repository = $this->em->getRepository(OauthClient::class);
        if (null === ($client = $repository->findOneBy(['name' => static::CLIENT_NAME]))) {
            $client = new OauthClient();
            $client->setName(static::CLIENT_NAME);
            $client->setRedirect(route('index'));
            $this->em->persist($client);
            $this->em->flush($client);
            $this->warn('New SOWL client created!');
        }

        $this->info(sprintf('SOWL client id: %s', $client->getId()));
        $this->info(sprintf('SOWL client secret: %s', $client->getSecret()));
    }
}
