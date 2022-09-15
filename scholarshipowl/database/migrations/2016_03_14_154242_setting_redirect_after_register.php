<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingRedirectAfterRegister extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("setting")->insert(array(
			"name" => "register.redirect_page",
			"title" => "URL to redirect to after registration",
			"value" => "\"select\"",
			"type" => "select",
			"group" => "Register",
			"options" => '{"select":"Select","register-payment":"Payment"}',
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table("setting")->where("name", "register.redirect_page")->delete();
	}

}
