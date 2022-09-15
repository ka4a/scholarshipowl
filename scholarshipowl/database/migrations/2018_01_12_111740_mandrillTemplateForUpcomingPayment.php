<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ScholarshipOwl\Util\Mailer;

class MandrillTemplateForUpcomingPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("transactional_email")->insert([
            "subject" => "Subscription Activated",
            "event_name" => Mailer::MANDRILL_SUBSCRIPTION_ACTIVATED,
            "template_name" => Mailer::MANDRILL_SUBSCRIPTION_ACTIVATED,
        ]);

        \DB::table("transactional_email")->insert([
            "subject" => "Upcoming subscription",
            "event_name" => Mailer::MANDRILL_SUBSCRIPTION_UPCOMING,
            "template_name" => Mailer::MANDRILL_SUBSCRIPTION_UPCOMING,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table("transactional_email")
            ->where(["template_name" => Mailer::MANDRILL_SUBSCRIPTION_UPCOMING])
            ->delete();
        \DB::table("transactional_email")
            ->where(["template_name" => Mailer::MANDRILL_SUBSCRIPTION_ACTIVATED])
            ->delete();
    }
}
