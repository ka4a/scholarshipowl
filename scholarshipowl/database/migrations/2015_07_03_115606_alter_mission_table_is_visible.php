<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionTableIsVisible extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `mission`
			ADD COLUMN `is_visible` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is mission visible.',
			ADD KEY `ix_mission_is_visible` (`is_visible`)
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `mission`
			DROP KEY `ix_mission_is_visible`,
			DROP COLUMN `is_visible`
		;");
	}
}
