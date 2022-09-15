<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupCmsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement(
			"CREATE  TABLE `popup_cms` (
				`popup_cms_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key.' ,
				`popup_id` INT(11) UNSIGNED NOT NULL COMMENT 'Id of the popup to be shown. Foreign key from popup table.' ,
				`cms_id` INT(11) UNSIGNED NOT NULL COMMENT 'Id of the page on which popup is to be shown. Foreign key from cms table.' ,
				PRIMARY KEY (`popup_cms_id`) )
				COMMENT = 'Table holds cms pages on which popup should appear.';"
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
			"DROP  TABLE `popup_cms`;"
		);
	}

}
