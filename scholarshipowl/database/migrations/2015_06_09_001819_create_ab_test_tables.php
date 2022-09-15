<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateAbTestTables extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `ab_test` (
			`ab_test_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`name` varchar(255) NOT NULL COMMENT 'AB Test name.',
			`description` varchar(2045) NOT NULL COMMENT 'AB Test description.',
			`start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'AB Test start date.',
			`end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'AB Test end date.',
			`is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is AB Test active.',
			PRIMARY KEY (`ab_test_id`),
			KEY `ix_ab_test_start_date` (`start_date`),
			KEY `ix_ab_test_end_date` (`end_date`),
			KEY `ix_ab_test_is_active` (`is_active`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds AB tests.';		
		");
		
		
		\DB::statement("
			CREATE TABLE `ab_test_account` (
			`ab_test_id` int(11) unsigned NOT NULL COMMENT 'Primary key. Foreign key to ab_test table',
			`account_id` int(11) unsigned NOT NULL COMMENT 'Primary key. Foreign key to account table',
			`test_group` enum('A','B') NOT NULL DEFAULT 'A' COMMENT 'Group where account belongs.',
			`conversion_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when account added to test.',
			PRIMARY KEY (`ab_test_id`, `account_id`),
			KEY `ix_ab_test_account_ab_test_id` (`ab_test_id`),
			KEY `ix_ab_test_account_account_id` (`account_id`),
			KEY `ix_ab_test_account_conversion_date` (`conversion_date`),
			CONSTRAINT `fk_ab_test_account_ab_test_id` FOREIGN KEY (`ab_test_id`) REFERENCES `ab_test` (`ab_test_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_ab_test_account_account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds AB test accounts.';
		");
	}


	public function down() {
		\DB::statement("DROP TABLE `ab_test_account`");
		\DB::statement("DROP TABLE `ab_test`");
	}
}
