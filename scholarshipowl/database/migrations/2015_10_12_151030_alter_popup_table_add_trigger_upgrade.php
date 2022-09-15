<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPopupTableAddTriggerUpgrade extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `popup`
			ADD COLUMN `trigger_upgrade` SMALLINT(1) UNSIGNED NULL DEFAULT 0 AFTER `end_date`;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE `popup`
			DROP COLUMN `trigger_upgrade`;");
	}

}
