<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMissionGoalsTableAddOrderingParameters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `mission_goal`
			ADD COLUMN `ordering` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'Ordering of goal on page.' AFTER `referral_award_id`,
			ADD COLUMN `parameters` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Additional parameters for mission goal.' AFTER `ordering`;

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
			ALTER TABLE `mission_goal`
			DROP COLUMN `parameters`,
			DROP COLUMN `ordering`;
		");
	}

}
