<?php

namespace App\Console\Commands;

use App\Entities\User;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;

class AdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new admin user.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Hasher
     */
    protected $hasher;

    /**
     * Create a new command instance.
     * @param EntityManager $em
     * @param Hasher $hasher
     */
    public function __construct(EntityManager $em, Hasher $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = new User();

        $user->setEmail($email = $this->ask('User email'));

        /** @var UserRepository $repository */
        $repository = $this->em->getRepository(User::class);
        if ($repository->findByEmail($email)) {
            throw new \RuntimeException(sprintf('User with email `%s` already registered!', $email));
        }

        $user->setName($this->ask('User name'));
        $user->setPassword($this->hasher->make($password = str_random()));

        $this->em->persist($user);
        $this->em->flush($user);

        $this->warn(sprintf('Generated admin user: %s', $email));
        $this->info(sprintf('Password: %s', $password));
    }
}
