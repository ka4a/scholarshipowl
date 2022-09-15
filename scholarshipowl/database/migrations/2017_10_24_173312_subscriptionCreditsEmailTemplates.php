<?php

use Illuminate\Database\Migrations\Migration;

class SubscriptionCreditsEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $transactionalEmail = new \App\Entity\TransactionalEmail(
            "Subscription credits exhausted",
            \ScholarshipOwl\Util\Mailer::MANDRILL_SUBSCRIPTION_CREDIT_EXHAUSTED,
            "ScholarshipOwl Site Account",
            "Credits were exhausted"
        );
        EntityManager::persist($transactionalEmail);

        $transactionalEmail = new \App\Entity\TransactionalEmail(
            "Subscription credits increases",
            \ScholarshipOwl\Util\Mailer::MANDRILL_SUBSCRIPTION_CREDIT_INCREASES,
            "ScholarshipOwl Site Account",
            "Credits were increases"
        );

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
        \DB::table('transactional_email')->where(
            'template_name',
            \ScholarshipOwl\Util\Mailer::MANDRILL_SUBSCRIPTION_CREDIT_EXHAUSTED
        )->delete();
        \DB::table('transactional_email')->where(
            'template_name',
            \ScholarshipOwl\Util\Mailer::MANDRILL_SUBSCRIPTION_CREDIT_INCREASES
        )->delete();
    }
}
