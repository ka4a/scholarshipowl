<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminStatisticsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admins = \EntityManager::getRepository(App\Entity\Account::class)
            ->findBy(['account_type' => \App\Entity\AccountType::ADMINISTRATOR]);

        /** @var \App\Entity\Account $account */
        foreach($admins as $account) {
            $account->addPermission(\App\Policies\RoutePolicy::addPrefix('statistics'));
            $account->addPermission(\App\Policies\RoutePolicy::addPrefix('static-data'));

            \EntityManager::flush($account);
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
