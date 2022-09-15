<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayPositionsToCoregPlugins extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			UPDATE `coreg_plugins` SET `display_position`='coreg1' WHERE name = 'Toluna';
		");

		DB::statement("
			UPDATE `coreg_plugins` SET `display_position`='coreg2' WHERE name = 'Academix';
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
