<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoregPluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement("
			CREATE TABLE `coreg_plugins` (
			  `coreg_plugin_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
			  `name` VARCHAR(255) NULL COMMENT 'Plugin name',
			  `is_visible` SMALLINT(1) NULL DEFAULT 0 COMMENT 'Is the plugin visible',
			  `text` MEDIUMTEXT NULL COMMENT 'Text for front end display',
			  PRIMARY KEY (`coreg_plugin_id`),
			  UNIQUE INDEX `coreg_plugin_id_UNIQUE` (`coreg_plugin_id` ASC))
			COMMENT = 'Table holds settings for 3rd party monetization plugins.';
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::statement("
			DROP TABLE `coreg_plugins`;
		");
	}

}
