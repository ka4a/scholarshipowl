<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionGoalTable extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `mission_goal`
			ADD COLUMN `is_active` tinyint(1) DEFAULT '0' COMMENT 'Is mission goal active.' AFTER `points`,
			ADD INDEX `ix_mission_goal_is_active` (`is_active`);
		");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `mission_goal`
			DROP COLUMN `is_active`,
			DROP INDEX `ix_mission_goal_is_active`;
		");
	}
}
