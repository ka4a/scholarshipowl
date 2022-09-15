<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionTable extends Migration {
	public function up() {
		return \DB::statement("
			ALTER TABLE `mission`
			ADD COLUMN `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Mission start date.' AFTER `name`,
			ADD COLUMN `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Mission end date.' AFTER `start_date`,
			ADD KEY `ix_mission_start_date` (`start_date`),
  			ADD KEY `ix_mission_end_date` (`end_date`)
		");
	}

	public function down() {
		return \DB::statement("
			ALTER TABLE `mission`
			DROP COLUMN `start_date`,
			DROP COLUMN `end_date`,
			DROP KEY `ix_mission_start_date`,
			DROP KEY `ix_mission_end_date`
		");
	}
}
