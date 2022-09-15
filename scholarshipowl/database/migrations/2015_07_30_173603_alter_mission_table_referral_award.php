<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionTableReferralAward extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `mission_goal`
			ADD COLUMN `referral_award_id` int(11) UNSIGNED COMMENT 'Referral award id. Foreign key to referral_award_table',
			ADD KEY `ix_mission_goal_referral_award_id` (`referral_award_id`),
			ADD CONSTRAINT `fk_mission_goal_referral_award` FOREIGN KEY (`referral_award_id`) REFERENCES `referral_award` (`referral_award_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `mission_goal` 
			DROP KEY `ix_mission_goal_referral_award_id`,
			DROP FOREIGN KEY `fk_mission_goal_referral_award`,
			DROP COLUMN `referral_award_id`
		;");
	}
}
