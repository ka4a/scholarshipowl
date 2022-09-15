<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPopupTableAddPopupDelay extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `popup`
			ADD COLUMN `popup_delay` INT(4) UNSIGNED NULL DEFAULT 0 AFTER `popup_cms_ids`,
			ADD COLUMN `popup_display_times` INT(4) UNSIGNED NULL DEFAULT 0 AFTER `popup_delay`;
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
			ALTER TABLE `popup`
			DROP COLUMN `popup_delay`,
			DROP COLUMN `popup_display_times`;
		");
	}
}
