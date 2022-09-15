<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAgreeCallSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("setting")->insert(array(
            "name" => "register.checkbox.call_visible",
            "title" => "Allow call checkbox visible",
            "group" => "Register",

            "type" => "select",
            "options" => '{"yes":"Yes","no":"No"}',
            "value" => '"yes"',
            "default_value" => '"yes"'
        ));
        DB::table("setting")->insert(array(
            "name" => "register.checkbox.call",
            "title" => "Allow call checkbox preticked",
            "group" => "Register",

            "type" => "select",
            "options" => '{"yes":"Yes","no":"No"}',
            "value" => '"yes"',
            "default_value" => '"yes"'
        ));
        DB::table("setting")->insert(array(
            "name" => "register.checkbox.call_text",
            "title" => "Allow call checkbox text",
            "group" => "Register",

            "type" => 'text',
            "value" => '"I consent to be contacted at the phone number provided by Owl Marketing ltd, and other partner companies. Consent is not required as a condition of using this service."',
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where(['name' => 'register.checkbox.call_visible'])->delete();
        \DB::table('setting')->where(['name' => 'register.checkbox.call'])->delete();
        \DB::table('setting')->where(['name' => 'register.checkbox.call_text'])->delete();
    }
}
