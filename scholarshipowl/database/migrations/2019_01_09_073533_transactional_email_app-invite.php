<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionalEmailAppInvite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $email = new \App\Entity\TransactionalEmail(
            'Mobile app membership invite',
            \App\Services\PubSub\TransactionalEmailService::APP_MEMBERSHIP_INVITE,
            'ScholarshipOwl',
            'Premium Membership Invitation',
            'info@scholarshipowl.com',
            true
        );
        EntityManager::persist($email);
        EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('transactional_email')->where('template_name', \App\Services\PubSub\TransactionalEmailService::APP_MEMBERSHIP_INVITE)->delete();
    }
}
