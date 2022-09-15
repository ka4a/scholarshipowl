<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertZipRecruiterCoreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("coreg_plugins")->insert(array(
            "name" => "Ziprecruiter",
            "is_visible" => 1,
            "monthly_cap" => null,
            "text" => "Sign me up for Job Alert Emails <small>sponsored by Ziprecruiter</small>",
            "display_position" => "coreg3a"
        ));

        DB::table("redirect_rules_set")->insert(array(
            "name" => "Ziprecruiter",
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
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'Ziprecruiter';");

        DB::delete("DELETE FROM redirect_rules_set WHERE name = 'Ziprecruiter';");
    }
}
