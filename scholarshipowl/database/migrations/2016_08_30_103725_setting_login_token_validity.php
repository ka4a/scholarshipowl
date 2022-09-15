<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingLoginTokenValidity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('setting')->insert([
            'group' => 'Register',
            'name' => 'register.login_token_validity',
            'title' => 'Validity of login token sent in email in days',
            'value' => '"7"',
            'type' => 'int',
            'default_value' => '"7"',
            'options' => '[]',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where(['name' => 'register.login_token_validity'])->delete();
    }
}
