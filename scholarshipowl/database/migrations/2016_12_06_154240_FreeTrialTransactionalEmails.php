<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ScholarshipOwl\Util\Mailer;
use App\Entity\TransactionalEmail;

class FreeTrialTransactionalEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \EntityManager::persist(new TransactionalEmail(
            'Free trial activated',
            Mailer::MANDRILL_FREETRIAL_ACTIVATED,
            'ScholarshipOwl Site Account',
            'Your free trial package activated!'
        ));

        \EntityManager::persist(new TransactionalEmail(
            'Free trial cancelled',
            Mailer::MANDRILL_FREETRIAL_CANCELLED,
            'ScholarshipOwl Site Account',
            'Your free trial package was cancelled!'
        ));

        \EntityManager::persist(new TransactionalEmail(
            'First charge from free trial',
            Mailer::MANDRILL_FREETRIAL_FIRST_CHARGE,
            'ScholarshipOwl Site Account',
            'Your first charge after free trial!'
        ));

        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_FREETRIAL_ACTIVATED)->delete();
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_FREETRIAL_CANCELLED)->delete();
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_FREETRIAL_FIRST_CHARGE)->delete();
    }
}
