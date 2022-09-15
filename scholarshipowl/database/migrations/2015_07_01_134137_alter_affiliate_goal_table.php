<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterAffiliateGoalTable extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `affiliate_goal`
			ADD COLUMN `value` decimal(10,2) NULL DEFAULT '0' COMMENT 'Affiliate goal monetary value.';	
		");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `affiliate_goal`
			DROP COLUMN `value`;	
		");
	}
}
