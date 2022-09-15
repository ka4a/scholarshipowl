<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationMandrillTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $transactionalEmail = new \App\Entity\TransactionalEmail("Recurrence notify", \ScholarshipOwl\Util\Mailer::MANDRILL_RECURRENT_SCHOLARSHIPS_NOTIFY, "ScholarshipOwl Site Account", "Your recurrent scholarships are expiring");

        EntityManager::persist($transactionalEmail);

        EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('transactional_email')->where('template_name', \ScholarshipOwl\Util\Mailer::MANDRILL_RECURRENT_SCHOLARSHIPS_NOTIFY)->delete();
    }
}
