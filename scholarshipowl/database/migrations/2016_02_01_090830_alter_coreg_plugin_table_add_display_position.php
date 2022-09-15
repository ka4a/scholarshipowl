<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoregPluginTableAddDisplayPosition extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `coreg_plugins`
			ADD COLUMN `display_position` ENUM('none', 'coreg1', 'coreg2', 'coreg3', 'coreg4', 'coreg5', 'coreg5a', 'coreg6', 'coreg6a') NULL DEFAULT 'none' COMMENT 'Table stores display positions for front end rendering.' AFTER `text`;
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			ALTER TABLE `coreg_plugins`
			DROP COLUMN `display_position`;
		");
	}

}
