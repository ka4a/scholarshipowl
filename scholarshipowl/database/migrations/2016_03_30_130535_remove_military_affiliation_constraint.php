<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMilitaryAffiliationConstraint extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `profile`
			DROP FOREIGN KEY `fk_profile_military_affiliation`;
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
			ADD CONSTRAINT `fk_profile_military_affiliation`
			  FOREIGN KEY (`military_affiliation_id`)
			  REFERENCES `military_affiliation` (`military_affiliation_id`)
			  ON DELETE NO ACTION
			  ON UPDATE NO ACTION;
		");
	}

}
