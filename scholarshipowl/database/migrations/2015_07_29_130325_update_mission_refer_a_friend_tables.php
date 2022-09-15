<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMissionReferAFriendTables extends Migration {
	public function up() {
		\DB::statement("
			INSERT INTO `mission_goal_type` 
			VALUES (2, 'Refer A Friend')
		;");
		
		\DB::statement("
			ALTER TABLE `referral_award`
			ADD COLUMN `description` varchar(2045) NULL DEFAULT '' COMMENT 'Referral award description.' AFTER `name`,
			ADD COLUMN `redirect_description` varchar(2045) NULL DEFAULT '' COMMENT 'Referral award redirect description.' AFTER `description`	
		;");
	}

	public function down() {
		\DB::statement("
			DELETE FROM `mission_goal_type`
			WHERE `mission_goal_type_id` = 2
		;");
		
		\DB::statement("
			ALTER TABLE `referral_award`
			DROP COLUMN `description`,
			DROP COLUMN `redirect_description`
		;");
	}
}
