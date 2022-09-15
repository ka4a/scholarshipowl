<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateMissionTables extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `mission` (
			`mission_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`package_id` int(11) unsigned NOT NULL COMMENT 'Package id. Foreign key to package table.',
			`name` varchar(255) NOT NULL COMMENT 'Mission name.',
			`description` varchar(2045) NOT NULL COMMENT 'Mission description.',
			`message` varchar(2045) NOT NULL COMMENT 'Mission message.',
			`success_message` varchar(2045) NOT NULL COMMENT 'Mission success message.',
			`is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is mission active.',
			PRIMARY KEY (`mission_id`),
			KEY `ix_mission_package_id` (`package_id`),
			KEY `ix_mission_is_active` (`is_active`),
			CONSTRAINT `fk_mission_package` FOREIGN KEY (`package_id`) REFERENCES `package` (`package_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds missions.';
		");
	
		\DB::statement("
			CREATE TABLE `mission_goal_type` (
			`mission_goal_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`name` varchar(255) NOT NULL COMMENT 'Mission goal type name.',
			PRIMARY KEY (`mission_goal_type_id`)	
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds mission goal types.';
		");
	
		\DB::statement("
			CREATE TABLE `mission_goal` (
			`mission_goal_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`mission_goal_type_id` tinyint(3) unsigned NOT NULL COMMENT 'Mission goal type id. Foreign key to mission_goal_type table.',
			`mission_id` int(11) unsigned NOT NULL COMMENT 'Mission id. Foreign key to mission table.',
			`name` varchar(255) NOT NULL COMMENT 'Mission goal name.',
			`points` tinyint(3) NOT NULL COMMENT 'Mission goal points.',
			`affiliate_goal_id` int(11) unsigned COMMENT 'Affiliate goal id when type is affiliate. Foreign key to affiliate_goal table.',
			PRIMARY KEY (`mission_goal_id`),
			KEY `ix_mission_goal_mission_goal_type_id` (`mission_goal_type_id`),
			KEY `ix_mission_goal_mission_id` (`mission_id`),
			KEY `ix_mission_goal_affiliate_goal_id` (`affiliate_goal_id`),
			CONSTRAINT `fk_mission_goal_mission_goal_type` FOREIGN KEY (`mission_goal_type_id`) REFERENCES `mission_goal_type` (`mission_goal_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
			CONSTRAINT `fk_mission_goal_mission` FOREIGN KEY (`mission_id`) REFERENCES `mission` (`mission_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_mission_goal_affiliate_goal` FOREIGN KEY (`affiliate_goal_id`) REFERENCES `affiliate_goal` (`affiliate_goal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds mission goals.';
		");
	
		\DB::statement("
			CREATE TABLE `mission_account` (
			`mission_account_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`mission_id` int(11) unsigned NOT NULL COMMENT 'Mission id. Foreign key to mission table.',
			`account_id` int(11) unsigned NOT NULL COMMENT 'Account id. Foreign key to account table.',
			`status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending' COMMENT 'Mission status.',
			`points` tinyint(3) NOT NULL DEFAULT 0 COMMENT 'Mission total points completed.',
			`date_started` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when mission started.',
			`date_ended` timestamp DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when mission ended.',
			PRIMARY KEY (`mission_account_id`),
			KEY `ix_mission_account_mission_id` (`mission_id`),
			KEY `ix_mission_account_account_id` (`account_id`),
			CONSTRAINT `fk_mission_account_mission` FOREIGN KEY (`mission_id`) REFERENCES `mission` (`mission_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_mission_account_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds mission goals.';
		");
		
		
		\DB::statement("INSERT INTO `mission_goal_type` VALUES(1, 'Affiliate')");
	}
	
	public function down() {
		\DB::statement("DROP TABLE `mission_goal`");
		\DB::statement("DROP TABLE `mission_goal_type`");
		\DB::statement("DROP TABLE `mission_account`");
		\DB::statement("DROP TABLE `mission`");
	}
}
