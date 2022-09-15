<?php

use App\Policies\RoutePolicy;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendAdminPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \Schema::dropIfExists('admin_permission');

        \Schema::create('admin', function(Blueprint $table) {
            $table->increments( 'admin_id' );
            $table->unsignedInteger('account_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 64);
            $table->string('status', 100);
            $table->unsignedInteger('admin_role_id');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('account_id')->references('account_id')->on('account');
        });

        \Schema::create('admin_role', function(Blueprint $table) {
            $table->increments('admin_role_id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();

            $table->unique('name');
        });

        \Schema::create('admin_role_permission', function(Blueprint $table) {
            $table->unsignedInteger('admin_role_id');
            $table->string('permission');

            $table->primary(['admin_role_id', 'permission']);
            $table->foreign('admin_role_id')->references('admin_role_id')->on('admin_role');
        });

        \Schema::table('admin', function(Blueprint $table) {
            $table->foreign('admin_role_id')->references('admin_role_id')->on('admin_role');
        });

        $oldAdmins = \EntityManager::getRepository(\App\Entity\Account::class)
            ->findBy(['accountType' => \App\Entity\AccountType::ADMINISTRATOR], ['accountId' => 'ASC']);

        $rootRole = new \App\Entity\Admin\AdminRole('Root', 'Super Admin - Have all permissions.');
        \EntityManager::persist($rootRole);

        /** @var \App\Entity\Account $oldAdmin */
        foreach ($oldAdmins as $oldAdmin) {
            $admin = new \App\Entity\Admin\Admin(
                $oldAdmin->getUsername(),
                $oldAdmin->getEmail(),
                \App\Entity\Admin\Admin::STATUS_ACTIVE,
                'empty',
                $rootRole
            );
            $admin->setPassword($oldAdmin->getPassword());
            $admin->setRememberToken($oldAdmin->getRememberToken());
            $admin->setAccount($oldAdmin);

            \EntityManager::persist($admin);
        }

        \EntityManager::flush();

        /*
        $admins = \EntityManager::getRepository(\App\Entity\Account::class)
            ->findBy(['account_type' => \App\Entity\AccountType::ADMINISTRATOR]);

        /** @var \App\Entity\Account $admin *
        foreach ($admins as $admin) {
            foreach ($admin->getPermissions() as $permission) {
                $admin->removePermission($permission);
            }
            \EntityManager::flush();
        }

        \EntityManager::flush();
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('admin_role_permission');
        \Schema::dropIfExists('admin');
        \Schema::dropIfExists('admin_role');
    }
}
