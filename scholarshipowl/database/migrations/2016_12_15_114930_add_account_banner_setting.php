<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountBannerSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("setting")->insert(array(
            "name" => "marketing.account_banner",
            "title" => "Banner on my account page",
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
        DB::table('setting')->where('name', 'marketing.account_banner')->delete();
    }
}
