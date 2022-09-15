<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralShareTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(
			"CREATE TABLE `referral_share` (
			  `referral_share_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `account_id` int(11) unsigned NOT NULL COMMENT 'Referral account id. Foreign key to account table.',
			  `referral_channel` enum('Facebook','Twitter','Pinterest','Whatsapp','SMS','Email','Link') DEFAULT NULL COMMENT 'Channel on which link is shared.',
			  `referral_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date when the link is shared.',
			  PRIMARY KEY (`referral_share_id`,`account_id`,`referral_date`),
			  KEY `fk_referral_share_account_idx` (`account_id`),
			  CONSTRAINT `fk_referral_share_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='Table holds when referral link is shared and on which channel.';"
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
			"DROP TABLE `referral_share`;"
		);
	}

}
