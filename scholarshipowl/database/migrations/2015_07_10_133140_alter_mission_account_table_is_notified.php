<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMissionAccountTableIsNotified extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement("
          ALTER TABLE `mission_account`
          ADD COLUMN `is_notified` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Has the account been notified of mission completeness.' AFTER `date_ended`;
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
          ALTER TABLE `mission_account`
          DROP COLUMN `is_notified`;
        ");
	}

}
