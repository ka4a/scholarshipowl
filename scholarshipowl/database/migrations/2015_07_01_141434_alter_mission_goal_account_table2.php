<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionGoalAccountTable2 extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `mission_goal_account`
			ADD COLUMN `is_started` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is goal started.' AFTER `mission_goal_id`,
			ADD COLUMN `date_started` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when mission goal started.',
  			ADD COLUMN `date_accomplished` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when mission goal ended.',
			ADD KEY `ix_mission_goal_account_is_started` (`is_started`),
			ADD KEY `ix_mission_goal_account_date_started` (`date_started`),
			ADD KEY `ix_mission_goal_account_date_accomplished` (`date_accomplished`)
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `mission_goal_account`
			DROP KEY `ix_mission_goal_account_is_started`,
			DROP KEY `ix_mission_goal_account_date_started`,
			DROP KEY `ix_mission_goal_account_date_accomplished`,
			DROP COLUMN `is_started`,
			DROP COLUMN `date_started`,
			DROP COLUMN `date_accomplished`
		;");
	}
}
