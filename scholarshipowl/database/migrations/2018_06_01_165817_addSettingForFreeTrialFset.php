<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingForFreeTrialFset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("setting")->insert(array(
            "name" => "marketing.free-trial-fset",
            "title" => "Send email to users on",
            "value" => '"15"',
            "default_value" => '"no"',
            "options" => '[]',
            "type" => "select",
            "group" => "Marketing",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM setting WHERE name = 'marketing.free-trial-fset';");
    }
}
