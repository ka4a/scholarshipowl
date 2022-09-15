<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(
			"CREATE TABLE `files` (
			  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `account_id` int(11) unsigned NOT NULL COMMENT 'Referral account id. Foreign key to account table.',
			  `file_name` VARCHAR(255) DEFAULT NULL COMMENT 'Fie name',
			  `file_description` VARCHAR(1055) DEFAULT NULL COMMENT 'File description.',
			  PRIMARY KEY (`file_id`,`account_id`),
			  KEY `fk_file_account_idx` (`account_id`),
			  CONSTRAINT `fk_file_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='Table with nfo aboutuploaded files.';"
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
			"DROP TABLE `files`;"
		);
	}

}
