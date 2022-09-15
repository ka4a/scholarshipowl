<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAcademixCoregPlugin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table("coreg_plugins")->insert(array(
			"name" => "Academix",
			"is_visible" => 1,
			"text" => ""
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete("DELETE FROM coreg_plugins WHERE name = 'Academix';");
	}

}
