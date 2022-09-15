<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionGoalAccountTable extends Migration {
	public function up() {
		\DB::statement("DROP TABLE `mission_goal_account`");
		
		\DB::statement("
			CREATE TABLE `mission_goal_account` (
			`mission_goal_account_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`mission_account_id` int(11) unsigned NOT NULL COMMENT 'Mission account id. Foreign key to mission_account table.',
			`mission_goal_id` int(11) unsigned NOT NULL COMMENT 'Mission goal id. Foreign key to mission_goal table.',
			`is_accomplished` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is goal accomplished.',
			PRIMARY KEY(`mission_goal_account_id`),
			KEY `ix_mission_goal_account_mission_account_id` (`mission_account_id`),
			KEY `ix_mission_goal_account_mission_goal_id` (`mission_goal_id`),
			KEY `ix_mission_goal_account_is_accomplished` (`is_accomplished`),
			CONSTRAINT `fk_mission_goal_account_mission_account` FOREIGN KEY (`mission_account_id`) REFERENCES `mission_account` (`mission_account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_mission_goal_account_mission_goal` FOREIGN KEY (`mission_goal_id`) REFERENCES `mission_goal` (`mission_goal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds mission goal accomplishments.';
		");
	}

	public function down() {
		\DB::statement("DROP TABLE `mission_goal_account`");
	}
}
