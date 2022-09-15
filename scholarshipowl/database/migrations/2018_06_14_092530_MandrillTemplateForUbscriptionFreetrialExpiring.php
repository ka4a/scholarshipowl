<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ScholarshipOwl\Util\Mailer;

class MandrillTemplateForUbscriptionFreetrialExpiring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("transactional_email")->insert([
            "subject" => "Free Trial Expiring",
            "event_name" => Mailer::MANDRILL_SUBSCRIPTION_FREETRIAL_EXPIRING,
            "template_name" => Mailer::MANDRILL_SUBSCRIPTION_FREETRIAL_EXPIRING,
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
            ->where(["template_name" => Mailer::MANDRILL_SUBSCRIPTION_FREETRIAL_EXPIRING])
            ->delete();
    }
}
