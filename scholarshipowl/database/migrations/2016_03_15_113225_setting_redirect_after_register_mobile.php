<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingRedirectAfterRegisterMobile extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("setting")->insert(array(
			"name" => "register.redirect_page_mobile",
			"title" => "URL to redirect to for mobile",
			"value" => "\"select\"",
			"type" => "select",
			"group" => "Register",
			"options" => '{"select":"Select","upgrade-mobile":"Payment"}',
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table("setting")->where("name", "register.redirect_page_mobile")->delete();
	}


}
