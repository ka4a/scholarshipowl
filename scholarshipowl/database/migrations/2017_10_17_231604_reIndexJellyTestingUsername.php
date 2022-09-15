<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReIndexJellyTestingUsername extends Migration
{

    /**
     * @var \App\Services\Account\AccountService
     */
    protected $as;

    public function __construct()
    {
        $this->as = app(\App\Services\Account\AccountService::class);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \EntityManager::getRepository(\App\Entity\Account::class)
            ->createQueryBuilder('a')
            ->where("a.username LIKE '%jellytesting%'")
            ->getQuery()->execute();

        /**
         * @var \App\Entity\Account $user
         */
        foreach ($users as $user){
            $userProfile = $user->getProfile();
            $newUserName = $this->as->generateUsername($user->getEmail());
            $user->setUsername($newUserName);
            EntityManager::persist($user);
            EntityManager::flush($user);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
