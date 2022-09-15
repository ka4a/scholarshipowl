<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingMarketingSelectBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("setting")->insert(array(
            "name" => "marketing.select_banner",
            "title" => "Banner on select page",
            "group" => "Marketing",

            "type" => "select",
            "options" => '{"yes":"Yes","no":"No"}',
            "value" => '"no"',
            "default_value" => '"no"'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('setting')->where('name', 'marketing.select_banner')->delete();
    }
}
