<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateReferralAwardTables extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `referral_award_type` (
			`referral_award_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
			`name` varchar(255) NOT NULL COMMENT 'Referral award type name.',
			PRIMARY KEY(`referral_award_type_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds referral award types.';
		");
		
		\DB::statement("
			CREATE TABLE `referral_award` (
			`referral_award_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
			`referral_award_type_id` tinyint(3) unsigned NOT NULL COMMENT 'Referral award type id. Foreign key to referral_award_type table.',
			`name` varchar(255) NOT NULL COMMENT 'Referral award name.',
			`referrals_number` int(11) unsigned NOT NULL COMMENT 'Number of referrals needed for award.',
			`referral_package_id` int(11) unsigned NULL COMMENT 'Award package for referral. Foreign key to package table.',
			`referred_package_id` int(11) unsigned NOT NULL COMMENT 'Award package for referred. Foreign key to package table.',
			`is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is referral award active.',
			PRIMARY KEY(`referral_award_id`),
			KEY `ix_referral_award_referral_award_type_id` (`referral_award_type_id`),
			KEY `ix_referral_award_referral_package_id` (`referral_package_id`),
			KEY `ix_referral_award_referred_package_id` (`referred_package_id`),
			KEY `ix_referral_award_is_active` (`is_active`),
			CONSTRAINT `fk_referral_award_referral_award_type` FOREIGN KEY (`referral_award_type_id`) REFERENCES `referral_award_type` (`referral_award_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
			CONSTRAINT `fk_referral_award_referral_package` FOREIGN KEY (`referral_package_id`) REFERENCES `package` (`package_id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
			CONSTRAINT `fk_referral_award_referred_package` FOREIGN KEY (`referred_package_id`) REFERENCES `package` (`package_id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds referral awards.';
		");
		
		\DB::statement("
			CREATE TABLE `referral_award_account` (
			`referral_account_id` int(11) UNSIGNED NOT NULL COMMENT 'Referral account id. Foreign key to account table.',
            `referred_account_id` int(11) UNSIGNED NOT NULL COMMENT 'Referred account id. Foreign key to account table.',
            `referral_award_id` int(11) unsigned NOT NULL COMMENT 'Referral award id. Foreign key to referral_award table.',
			`awarded_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when accounts are awarded.',
			PRIMARY KEY(`referral_account_id`, `referred_account_id`),
			KEY `ix_referral_award_account_referral_account_id` (`referral_account_id`),
			KEY `ix_referral_award_account_referred_account_id` (`referred_account_id`),
			KEY `ix_referral_award_account_referral_award_id` (`referral_award_id`),
			KEY `ix_referral_award_account_awarded_date` (`awarded_date`),
			CONSTRAINT `fk_referral_award_account_referral_account` FOREIGN KEY (`referral_account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_referral_award_account_referred_account` FOREIGN KEY (`referred_account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_referral_award_account_referral_award` FOREIGN KEY (`referral_award_id`) REFERENCES `referral_award` (`referral_award_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds when and who is awarded by referrals.';
		");
		
		
		\DB::statement("
			INSERT INTO `referral_award_type` VALUES
			(1, 'Number Of Referrals'),
			(2, 'Number Of Paid Referrals');
		");
	}

	public function down() {
		\DB::statement("DROP TABLE `referral_award_account`");
		\DB::statement("DROP TABLE `referral_award`");
		\DB::statement("DROP TABLE `referral_award_type`");
	}
}
