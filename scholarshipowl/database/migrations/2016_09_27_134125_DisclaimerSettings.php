<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisclaimerSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("setting")->insert(array(
            "name" => "disclaimer.enabled",
            "title" => "Disclaimer enabled/disabled",
            "group" => "Disclaimer",

            "type" => "select",
            "options" => '{"yes":"Yes","no":"No"}',
            "value" => '"yes"',
            "default_value" => '"yes"'
        ));
        DB::table("setting")->insert(array(
            "name" => "disclaimer.text",
            "title" => "Disclaimer text",
            "group" => "Disclaimer",

            "type" => 'text',
            "value" => '"Note: ScholarshipOwl does not guarantee the receipt of any scholarship. Upgrading to premium membership does not increase your chances of receiving a specific scholarship."',
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where(['name' => 'disclaimer.enabled'])->delete();
        \DB::table('setting')->where(['name' => 'disclaimer.text'])->delete();
    }
}
