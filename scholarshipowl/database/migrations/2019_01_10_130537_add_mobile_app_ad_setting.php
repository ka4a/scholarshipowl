<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMobileAppAdSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("setting")->insert(array(
            "name" => "scholarships.mobile_app_ad",
            "title" => "Show mobile ad enabled/disabled",
            "group" => "Scholarships",

            "type" => "select",
            "options" => '{"yes":"Yes","no":"No"}',
            "value" => '"yes"',
            "default_value" => '"yes"'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where(['name' => 'scholarships.mobile_app_ad'])->delete();
    }
}
