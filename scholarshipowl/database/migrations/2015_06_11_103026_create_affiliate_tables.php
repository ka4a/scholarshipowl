<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateAffiliateTables extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `affiliate` (
			`affiliate_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`name` varchar(255) NOT NULL COMMENT 'Affiliate name.',
			`api_key` varchar(63) NOT NULL COMMENT 'Affiliate api key.',
			`email` varchar(511) NOT NULL COMMENT 'Affiliate contact email.',
			`phone` varchar(127) NOT NULL COMMENT 'Affiliate contact phone.',
			`website` varchar(2045) NOT NULL COMMENT 'Affiliate website.',
			`description` varchar(2045) NOT NULL COMMENT 'Affiliate description.',
			`is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is affiliate active.',
			PRIMARY KEY (`affiliate_id`),
			UNIQUE KEY (`api_key`),
			KEY `ix_affiliate_is_active` (`is_active`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds affiliates.';
		");
		
		\DB::statement("
			CREATE TABLE `affiliate_goal` (
			`affiliate_goal_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`affiliate_id` int(11) unsigned NOT NULL COMMENT 'Affiliate id. Foreign key to affiliate table.',
			`name` varchar(255) NOT NULL COMMENT 'Affiliate goal name.',
			`url` varchar(2045) NOT NULL COMMENT 'Affiliate goal url.',
			PRIMARY KEY (`affiliate_goal_id`),
			KEY `ix_affiliate_goal_affiliate_id` (`affiliate_id`),
			CONSTRAINT `fk_affiliate_goal_affiliate` FOREIGN KEY (`affiliate_id`) REFERENCES `affiliate` (`affiliate_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds affiliate goals.';
		");
		
		\DB::statement("
			CREATE TABLE `affiliate_goal_response` (
			`affiliate_goal_response_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`affiliate_goal_id` int(11) unsigned NOT NULL COMMENT 'Affiliate goal id. Foreign key to affiliate_goal table.',
			`account_id` int(11) unsigned NOT NULL COMMENT 'Account id. Foreign key to account table.',
			`url` varchar(2045) NOT NULL COMMENT 'Affiliate goal response url.',
			`response_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when response arrived.',
			PRIMARY KEY (`affiliate_goal_response_id`),
			KEY `ix_affiliate_goal_response_affiliate_goal_id` (`affiliate_goal_id`),
			KEY `ix_affiliate_goal_response_account_id` (`account_id`),
			CONSTRAINT `fk_affiliate_goal_response_affiliate_goal` FOREIGN KEY (`affiliate_goal_id`) REFERENCES `affiliate_goal` (`affiliate_goal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_affiliate_goal_response_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds affiliate responses.';
		");
		
		\DB::statement("
			CREATE TABLE `affiliate_goal_response_data` (
			`affiliate_goal_response_data_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`affiliate_goal_response_id` int(11) unsigned NOT NULL COMMENT 'Affiliate goal response id. Foreign key to affiliate_goal_response table.',
			`name` varchar(255) NOT NULL COMMENT 'Affiliate goal response data parameter name.',
			`value` varchar(1023) NOT NULL COMMENT 'Affiliate goal response data parameter value.',
			PRIMARY KEY (`affiliate_goal_response_data_id`),
			KEY `ix_affiliate_goal_response_data_affiliate_goal_response_id` (`affiliate_goal_response_id`),
			CONSTRAINT `fk_affiliate_goal_response_data_affiliate_goal_response` FOREIGN KEY (`affiliate_goal_response_id`) REFERENCES `affiliate_goal_response` (`affiliate_goal_response_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds affiliate responses data.';
		");
	}

	public function down() {
		\DB::statement("DROP TABLE `affiliate_goal_response_data`");
		\DB::statement("DROP TABLE `affiliate_goal_response`");
		\DB::statement("DROP TABLE `affiliate_goal`");
		\DB::statement("DROP TABLE `affiliate`");
	}
}
