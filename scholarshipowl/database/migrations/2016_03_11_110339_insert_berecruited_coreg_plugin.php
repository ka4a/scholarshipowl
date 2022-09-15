<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertBerecruitedCoregPlugin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("coreg_plugins")->insert(array(
			"name" => "Berecruited",
			"is_visible" => 1,
			"text" => "",
			"display_position" => "coreg5"
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete("DELETE FROM coreg_plugins WHERE name = 'Berecruited';");
	}

}
