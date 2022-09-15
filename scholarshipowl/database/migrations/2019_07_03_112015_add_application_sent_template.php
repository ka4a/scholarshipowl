<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplicationSentTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $email = new \App\Entity\TransactionalEmail(
            'Application sent event',
            \App\Services\PubSub\TransactionalEmailService::APPLICATION_SENT,
            'ScholarshipOwl',
            'We have successfully submitted',
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
        \DB::table('transactional_email')->where('template_name', \App\Services\PubSub\TransactionalEmailService::APPLICATION_SENT)->delete();
    }
}
