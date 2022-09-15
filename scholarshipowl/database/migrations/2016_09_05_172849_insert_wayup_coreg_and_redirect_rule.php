<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertWayupCoregAndRedirectRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("redirect_rules_set")->insert(array(
            "name" => "WayUp",
            "type" => "AND",
            "table_name" => "profile",
        ));

        DB::table("coreg_plugins")->insert(array(
            "name" => "WayUp",
            "is_visible" => true,
            "text" => "Discover millions of internship and job opportunities on WayUp.",
            "display_position" => "coreg5",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table("redirect_rules_set")->where("name", "WayUp")->delete();
        DB::table("coreg_plugins")->where("name", "WayUp")->delete();
    }
}
