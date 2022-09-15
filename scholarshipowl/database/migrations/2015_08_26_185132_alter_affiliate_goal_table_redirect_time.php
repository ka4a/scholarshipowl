<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAffiliateGoalTableRedirectTime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement(
            "ALTER TABLE `affiliate_goal`
            ADD COLUMN `redirect_time` INT(5) UNSIGNED NULL AFTER `redirect_description`;"
        );

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement(
            "ALTER TABLE `affiliate_goal`
            DROP COLUMN `redirect_time`;"
        );
	}

}
