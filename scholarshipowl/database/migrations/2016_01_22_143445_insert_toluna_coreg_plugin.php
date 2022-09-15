<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertTolunaCoregPlugin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("coreg_plugins")->insert(array(
			"name" => "Toluna",
			"is_visible" => 1,
			"text" => "Send me an email survey to enter Toluna's $4,500 cash draw!"
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete("DELETE FROM coreg_plugins WHERE name = 'Toluna';");
	}

}
