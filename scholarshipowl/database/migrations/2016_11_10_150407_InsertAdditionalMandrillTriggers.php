<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAdditionalMandrillTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("New Email");
        $transactionalEmail->setSubject("You have new messages");
        $transactionalEmail->setTemplateName(\ScholarshipOwl\Util\Mailer::MANDRILL_NEW_EMAIL);
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("New Eligible Scholarships");
        $transactionalEmail->setSubject("You Have New Eligible Scholarships");
        $transactionalEmail->setTemplateName(\ScholarshipOwl\Util\Mailer::MANDRILL_NEW_ELIGIBLE_SCHOLARSHIPS);
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");

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
        \DB::table('transactional_email')->where('template_name', \ScholarshipOwl\Util\Mailer::MANDRILL_NEW_EMAIL)->delete();
        \DB::table('transactional_email')->where('template_name', \ScholarshipOwl\Util\Mailer::MANDRILL_NEW_ELIGIBLE_SCHOLARSHIPS)->delete();
    }
}
