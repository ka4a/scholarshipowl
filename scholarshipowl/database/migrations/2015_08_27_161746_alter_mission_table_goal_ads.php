<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMissionTableGoalAds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement(
            "ALTER TABLE `mission`
                ADD COLUMN `show_ads` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Denotes whether the mission should show ads on goal page.' AFTER `is_visible`,
                ADD COLUMN `ads_number` TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Number of ads to be displayed on goals screen.' AFTER `show_ads`,
                ADD COLUMN `ads_positions` VARCHAR(45) NULL COMMENT 'Holds comma separated list of ad positions.' AFTER `ads_number`;"
        );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::statement(
            "ALTER TABLE `mission`
                DROP COLUMN `ads_positions`,
                DROP COLUMN `ads_number`,
                DROP COLUMN `show_ads`;"
        );
	}

}
