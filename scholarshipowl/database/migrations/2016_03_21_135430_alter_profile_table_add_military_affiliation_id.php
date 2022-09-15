<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileTableAddMilitaryAffiliationId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `profile`
				ADD COLUMN `military_affiliation_id` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'Military affiliation id. Foreign key to military_affiliation table.' AFTER `signup_method`,
				ADD INDEX `fk_profile_military_affiliation_idx` (`military_affiliation_id` ASC);
		");

		DB::statement("
			ALTER TABLE `profile`
			ADD CONSTRAINT `fk_profile_military_affiliation`
			  FOREIGN KEY (`military_affiliation_id`)
			  REFERENCES `military_affiliation` (`military_affiliation_id`)
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
			ALTER TABLE `profile`
			DROP FOREIGN KEY `fk_profile_military_affiliation`;
		");

		DB::statement("
			ALTER TABLE `profile`
			DROP COLUMN `military_affiliation_id`,
			DROP INDEX `fk_profile_military_affiliation_idx` ;
		");
	}

}
