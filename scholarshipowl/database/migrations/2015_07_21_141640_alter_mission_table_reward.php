<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterMissionTableReward extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `mission`
			ADD COLUMN `reward_message` varchar(2045) NULL DEFAULT '' COMMENT 'Mission reward message.' AFTER `success_message`
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `mission`
			DROP COLUMN `reward_message`
		;");
	}
}
