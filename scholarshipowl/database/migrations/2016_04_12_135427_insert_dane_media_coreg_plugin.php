<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDaneMediaCoregPlugin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("coreg_plugins")->insert(array(
			"name" => "DaneMedia",
			"is_visible" => 1,
			"text" => "",
			"display_position" => "coreg6"
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete("DELETE FROM coreg_plugins WHERE name = 'DaneMedia';");
	}

}
