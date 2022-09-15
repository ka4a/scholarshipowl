<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateAffiliateGoalDescription extends Migration {
	public function up() {
		return \DB::statement("
         	ALTER TABLE `affiliate_goal`
          	ADD COLUMN `description` VARCHAR(2045) NULL COMMENT 'Affiliate goal description.'
        ");
	}

	public function down() {
		return \DB::statement("
          ALTER TABLE `affiliate_goal`
          	DROP COLUMN `description`;
        ");
	}
}
