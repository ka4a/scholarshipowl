<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountHasoffersFlag extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			CREATE TABLE `account_hasoffers_flag` (
			  `account_id` INT UNSIGNED NOT NULL COMMENT '',
			  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
			  `is_sent` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
			  PRIMARY KEY (`account_id`)  COMMENT '',
			  UNIQUE INDEX `account_id_UNIQUE` (`account_id` ASC)  COMMENT '',
			  CONSTRAINT `fk_account_hasoffers_flag_account`
				FOREIGN KEY (`account_id`)
				REFERENCES `account` (`account_id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION);
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
			DROP TABLE IF EXISTS `account_hasoffers_flag`;
		");
	}

}
