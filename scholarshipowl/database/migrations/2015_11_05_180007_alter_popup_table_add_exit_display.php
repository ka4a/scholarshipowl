<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPopupTableAddExitDisplay extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(
			"ALTER TABLE `popup`
				CHANGE COLUMN `popup_display` `popup_display` ENUM('0', '1', '2', '3', '4') NOT NULL DEFAULT '0' COMMENT 'Is popup active.\n0 - No\n1 - Before payment\n2 - After payment\n3 - Both\n4 - Exit';"
		);
		DB::statement(
			"ALTER TABLE `popup`
				ADD COLUMN `popup_exit_dialogue_text` VARCHAR(255) NULL AFTER `trigger_upgrade`;"
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement(
			"ALTER TABLE `popup`
				CHANGE COLUMN `popup_display` `popup_display` ENUM('0', '1', '2', '3') NOT NULL DEFAULT '0' COMMENT 'Is popup active.\n0 - No\n1 - Before payment\n2 - After payment\n3 - Both';"
		);
		DB::statement(
		"ALTER TABLE `popup`
			DROP COLUMN `popup_exit_dialogue_text`;"
		);
	}

}
