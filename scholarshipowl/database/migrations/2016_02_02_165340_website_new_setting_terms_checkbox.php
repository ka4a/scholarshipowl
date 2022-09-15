<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebsiteNewSettingTermsCheckbox extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::table("setting")->insert(array(
            "name" => "register.checkbox.terms",
            "title" => "Term & Conditions checked by default",
            "group" => "Register",

            "type" => "select",
            "options" => '{"yes":"Yes","no":"No"}',
            "value" => '"yes"',
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::table('setting')->where('name', '=', 'register.checkbox.terms')->delete();
	}

}
