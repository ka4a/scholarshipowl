<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateMissionGoalTables extends Migration {
	public function up() {
		\DB::statement("
			UPDATE `mission_goal_account`
			SET mission_goal_id = 191 
			WHERE mission_goal_id IN (189, 190)
		;");
		
		\DB::statement("
			DELETE FROM `mission_goal`
			WHERE mission_goal_id IN (189, 190)
		;");
	}
	
	public function down() {
	}
}
