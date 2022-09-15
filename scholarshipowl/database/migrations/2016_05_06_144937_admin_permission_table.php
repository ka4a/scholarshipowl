<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission', function (Blueprint $table) {
            $table->integer('admin_id', false, true);
            $table->string('permission');

            $table->foreign('admin_id')->references('account_id')->on('account');

            $table->primary(['admin_id', 'permission']);
        });

        /** @var \App\Entity\Account $account */
        foreach(\EntityManager::getRepository(App\Entity\Account::class)->findBy(['account_type' => 1]) as $account) {
            foreach (\App\Policies\RoutePolicy::getAvailablePermissions() as $permission => $description) {
                $account->addPermission($permission);
            }
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
        Schema::drop('admin_permission');
    }
}
