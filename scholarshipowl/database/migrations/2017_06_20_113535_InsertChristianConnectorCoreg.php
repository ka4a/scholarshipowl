<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertChristianConnectorCoreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("coreg_plugins")->insert(array(
            "name" => "ChristianConnector",
            "is_visible" => 1,
            "monthly_cap" => 5500,
            "text" => "Send me Christian college information and I'm considering attending a Christian college. I'd also like to enter the $2,500 Christian College Scholarship drawing. Privacy Policy <a href=\"http://www.christianconnector.com/privacy-policy-copyright.cfm\" target=\"_blank\">here</a>.",
            "display_position" => "coreg6a"
        ));

        DB::table("redirect_rules_set")->insert(array(
            "name" => "ChristianConnector",
            "type" => "AND",
            "table_name" => "profile",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'ChristianConnector';");

        DB::delete("DELETE FROM redirect_rules_set WHERE name = 'ChristianConnector';");
    }
}
