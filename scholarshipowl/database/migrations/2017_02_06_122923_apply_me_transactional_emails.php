<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ScholarshipOwl\Util\Mailer;
use App\Entity\TransactionalEmail;

class ApplyMeTransactionalEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `transactional_email` t SET t.from_email='support@apply.me' WHERE t.template_name='forgot-password-apply-me'");

        \EntityManager::persist(new TransactionalEmail(
            'Mailbox Welcome',
            Mailer::MANDRILL_MAILBOX_WELCOME_APPLY_ME,
            'Apply.me Site Account',
            '*|FNAME|*, welcome to your Apply.me application inbox',
            'support@apply.me',
            false
        ));

        \EntityManager::persist(new TransactionalEmail(
            'Account Welcome',
            Mailer::MANDRILL_ACCOUNT_WELCOME_APPLY_ME,
            'Apply.me Site Account',
            'Apply.me - *|FNAME|*, get applied',
            'support@apply.me',
            false,
            1
        ));

        \EntityManager::persist(new TransactionalEmail(
            'You Deserve It Confirmation',
            Mailer::MANDRILL_YDIT_CONFIRMATION_APPLY_ME,
            'Apply.me Site Account',
            'You Deserve It Scholarship confirmation',
            'support@apply.me',
            false
        ));

        \EntityManager::persist(new TransactionalEmail(
            'New Email',
            Mailer::MANDRILL_NEW_EMAIL_APPLY_ME,
            'Maja [Scholarship Advisor]',
            '*|FNAME|*, you have *|UNREADM|* unread message(s) related to your Scholarship applications',
            'support@apply.me',
            false,
            1
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
        DB::statement("UPDATE `transactional_email` t SET t.from_email='apply_me@apply.me' WHERE t.template_name='forgot-password-apply-me'");
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_MAILBOX_WELCOME_APPLY_ME)->delete();
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_ACCOUNT_WELCOME_APPLY_ME)->delete();
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_YDIT_CONFIRMATION_APPLY_ME)->delete();
        \DB::table('transactional_email')->where('template_name', Mailer::MANDRILL_NEW_EMAIL_APPLY_ME)->delete();
    }
}
