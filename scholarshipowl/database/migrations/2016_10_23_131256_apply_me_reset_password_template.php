<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplyMeResetPasswordTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("transactional_email")->insert(array(
            "event_name"    => "Forgot Password ApplyMe",
            "template_name" => 'forgot-password-apply-me',
            "from_email"    => "apply_me@apply.me",
            "from_name"     => "Apply.Me",
            "subject"       => "Password reset",
            "sending_cap"   => 0,
            "cap_period"    => "day",
            "active"        => 1
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM transactional_email WHERE template_name = 'forgot-password-apply-me';");
    }
}
