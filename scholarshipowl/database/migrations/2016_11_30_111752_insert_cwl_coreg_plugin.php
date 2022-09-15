<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCwlCoregPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("redirect_rules_set")->insert(array(
            "name" => "Cwl",
            "type" => "AND",
            "table_name" => "profile",
        ));

        DB::table("coreg_plugins")->insert(array(
            "name" => "Cwl",
            "is_visible" => 1,
            "text" => "",
            "display_position" => "coreg6a",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table("redirect_rules_set")->where("name", "Cwl")->delete();
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'Cwl';");
    }
}
