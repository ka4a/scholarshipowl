<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionalEmailsScholarshipWinning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $transactionalEmailWon
            = new \App\Entity\TransactionalEmail("Application Status Won",
            \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_USER_WON,
            "ScholarshipOwl",
            "You won, claim your award");
        EntityManager::persist($transactionalEmailWon);

        $transactionalEmailChosen
            = new \App\Entity\TransactionalEmail("Application winner chosen",
            \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_WINNER_CHOSEN,
            "ScholarshipOwl",
            "Winner chosen");
        EntityManager::persist($transactionalEmailChosen);

        $transactionalEmailAwarded
            = new \App\Entity\TransactionalEmail("Application user awarded",
            \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_USER_AWARDED,
            "ScholarshipOwl",
            "Application user awarded");
        EntityManager::persist($transactionalEmailAwarded);

        $transactionalEmailMissed
            = new \App\Entity\TransactionalEmail("Application user missed",
            \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_USER_MISSED,
            "ScholarshipOwl",
            "Application user missed");

        EntityManager::persist($transactionalEmailMissed);

        EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('transactional_email')->where('template_name', \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_USER_WON)->delete();
        \DB::table('transactional_email')->where('template_name', \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_WINNER_CHOSEN)->delete();
        \DB::table('transactional_email')->where('template_name', \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_USER_AWARDED)->delete();
        \DB::table('transactional_email')->where('template_name', \App\Services\PubSub\TransactionalEmailService::SCHOLARSHIP_USER_MISSED)->delete();
    }
}
