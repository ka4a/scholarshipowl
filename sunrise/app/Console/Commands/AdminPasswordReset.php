<?php

namespace App\Console\Commands;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;

class AdminPasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:password-reset {email : Admin user email to reset the password.}';

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
     * @var Hasher
     */
    protected $hasher;

    /**
     * Create a new command instance.
     *
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
        $email = $this->argument('email');
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->argument('email')]);
        if (!$user) {
            throw new \RuntimeException(sprintf('User with email `%s` not found!', $email));
        }

        /** @var User $user */
        $user->setPassword($this->hasher->make($password = str_random()));
        $this->em->flush($user);
        $this->warn(sprintf('New user password was set: %s', $password));
    }
}
