<?php  namespace App\Console\Commands;

use App\Entities\Passport\OauthClient;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class BarnClient extends Command
{
    const CLIENT_NAME = 'Barn client';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barn:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate or return details for Barn OAuth2 client.';

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
        $repository = $this->em->getRepository(OauthClient::class);
        if (null === ($client = $repository->findOneBy(['name' => static::CLIENT_NAME]))) {
            $client = new OauthClient();
            $client->setName(static::CLIENT_NAME);
            $client->setRedirect(route('index'));
            $this->em->persist($client);
            $this->em->flush($client);
            $this->warn('New barn client created!');
        }

        $this->info(sprintf('Barn client id: %s', $client->getId()));
        $this->info(sprintf('Barn client secret: %s', $client->getSecret()));
    }
}
