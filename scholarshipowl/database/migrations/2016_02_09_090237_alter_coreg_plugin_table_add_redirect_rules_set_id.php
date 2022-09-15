<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoregPluginTableAddRedirectRulesSetId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `coreg_plugins`
			ADD COLUMN `redirect_rules_set_id` INT(11) UNSIGNED NULL COMMENT '' AFTER `display_position`,
			ADD INDEX `fk_coreg_plugins_rediredt_rules_set_idx` (`redirect_rules_set_id` ASC)  COMMENT '';
		");

		DB::statement("
			ALTER TABLE `coreg_plugins`
			ADD CONSTRAINT `fk_coreg_plugins_rediredt_rules_set`
			  FOREIGN KEY (`redirect_rules_set_id`)
			  REFERENCES `redirect_rules_set` (`redirect_rules_set_id`)
			  ON DELETE NO ACTION
			  ON UPDATE NO ACTION;
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
			DROP FOREIGN KEY `fk_coreg_plugins_rediredt_rules_set`;
		");
		DB::statement("
			ALTER TABLE `coreg_plugins`
			DROP COLUMN `redirect_rules_set_id`,
			DROP INDEX `fk_coreg_plugins_rediredt_rules_set_idx` ;
		");
	}

}
