<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReferralAwardAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement("DROP TABLE `referral_award_account`");
        \DB::statement("
            CREATE TABLE `referral_award_account` (
              `account_id` int(11) unsigned NOT NULL COMMENT 'Referral account id. Foreign key to account table.',
              `referral_award_id` int(11) unsigned NOT NULL COMMENT 'Referral award id. Foreign key to referral_award table.',
              `award_type` enum('referral','referred') NOT NULL DEFAULT 'referral' COMMENT 'Type of awarded reward.',
              `awarded_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when accounts are awarded.',
              PRIMARY KEY (`account_id`,`referral_award_id`,`award_type`),
              KEY `ix_referral_award_account_account_id` (`account_id`),
              KEY `ix_referral_award_account_referral_award_id` (`referral_award_id`),
              KEY `ix_referral_award_account_awarded_date` (`awarded_date`),
              CONSTRAINT `fk_referral_award_account_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT `fk_referral_award_account_referral_award` FOREIGN KEY (`referral_award_id`) REFERENCES `referral_award` (`referral_award_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds when and who is awarded by referrals.';
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement("CREATE TABLE `referral_award_account` (
              `referral_account_id` int(11) unsigned NOT NULL COMMENT 'Referral account id. Foreign key to account table.',
              `referred_account_id` int(11) unsigned NOT NULL COMMENT 'Referred account id. Foreign key to account table.',
              `referral_award_id` int(11) unsigned NOT NULL COMMENT 'Referral award id. Foreign key to referral_award table.',
              `awarded_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when accounts are awarded.',
              PRIMARY KEY (`referral_account_id`,`referred_account_id`),
              KEY `ix_referral_award_account_referral_account_id` (`referral_account_id`),
              KEY `ix_referral_award_account_referred_account_id` (`referred_account_id`),
              KEY `ix_referral_award_account_referral_award_id` (`referral_award_id`),
              KEY `ix_referral_award_account_awarded_date` (`awarded_date`),
              CONSTRAINT `fk_referral_award_account_referral_account` FOREIGN KEY (`referral_account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT `fk_referral_award_account_referral_award` FOREIGN KEY (`referral_award_id`) REFERENCES `referral_award` (`referral_award_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT `fk_referral_award_account_referred_account` FOREIGN KEY (`referred_account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds when and who is awarded by referrals.';
        ");
	}

}
