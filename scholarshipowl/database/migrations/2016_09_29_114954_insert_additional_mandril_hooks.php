<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAdditionalMandrilHooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("Account Welcome");
        $transactionalEmail->setSubject("Welcome to ScholarshipOwl");
        $transactionalEmail->setTemplateName(\ScholarshipOwl\Util\Mailer::MANDRILL_ACCOUNT_WELCOME);
        $transactionalEmail->setFromName("ScholarshipOwl Site Account");

        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail();
        $transactionalEmail->setEventName("You Deserve It Confirmation");
        $transactionalEmail->setSubject("You are entered into You Deserve It scholarship draw");
        $transactionalEmail->setTemplateName(\ScholarshipOwl\Util\Mailer::MANDRILL_YDIT_CONFIRMATION);
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
        \DB::table('transactional_email')->where('template_name', \ScholarshipOwl\Util\Mailer::MANDRILL_ACCOUNT_WELCOME)->delete();
        \DB::table('transactional_email')->where('template_name', \ScholarshipOwl\Util\Mailer::MANDRILL_YDIT_CONFIRMATION)->delete();
    }
}
