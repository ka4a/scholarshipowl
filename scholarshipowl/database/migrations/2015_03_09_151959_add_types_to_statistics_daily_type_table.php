<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypesToStatisticsDailyTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        return \DB::statement("
			INSERT INTO `statistic_daily_type` VALUES
			(15, '‘Deposit corrections'), (16, '‘Deposit correction amount');
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        return \DB::statement("
			DELETE FROM `statistic_daily_type` WHERE statistic_daily_type_id (15, 16);
		");
	}

}
