<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterAffiliateGoalTableLogo extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `affiliate_goal`
			ADD COLUMN `logo` varchar(2045) NULL DEFAULT '' COMMENT 'Affiliate goal logo image.'
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `affiliate_goal`
			DROP COLUMN `logo`
		;");
	}
}
