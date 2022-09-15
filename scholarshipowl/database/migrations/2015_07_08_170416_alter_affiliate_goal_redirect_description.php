<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterAffiliateGoalRedirectDescription extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `affiliate_goal`
			ADD COLUMN `redirect_description` varchar(2045) NULL DEFAULT '' COMMENT 'Affiliate goal redirect description.' AFTER description
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `affiliate_goal`
			DROP COLUMN `redirect_description`
		;");
	}
}
