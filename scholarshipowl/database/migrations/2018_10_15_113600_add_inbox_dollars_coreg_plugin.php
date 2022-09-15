<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInboxDollarsCoregPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $displayPosition = "coreg6a";
        $linkText = "Join Inbox Dollars and earn by reading emails, taking surveys, watching video commercials and searching the web. Sign up for free. $5 signup bonus, today! Terms of use and Privacy Policy. 
                     URLs: 
                     <a target='_blank' href='https://www.inboxdollars.com/pages/privacy'>https://www.inboxdollars.com/pages/privacy</a>
                     <a target='_blank' href='https://www.inboxdollars.com/pages/terms'>https://www.inboxdollars.com/pages/terms</a>.";
        \DB::table("coreg_plugins")->insert([
            "name" => "InboxDollars",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table("coreg_plugins")
            ->where(["name" => "InboxDollars"])
            ->delete();
    }
}
