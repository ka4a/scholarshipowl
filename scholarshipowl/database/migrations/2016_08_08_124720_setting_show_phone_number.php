<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingShowPhoneNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("setting")->insert(array(
            "name" => "content.phone.show",
            "title" => "Show phone number on website",
            "group" => "Content",

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
        \DB::table('setting')->where('name', 'content.phone.show')->delete();
    }
}
