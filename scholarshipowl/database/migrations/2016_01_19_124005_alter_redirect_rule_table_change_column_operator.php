<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRedirectRuleTableChangeColumnOperator extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `redirect_rule`
			CHANGE COLUMN `operator` `operator` VARCHAR(10) NOT NULL DEFAULT '=' COMMENT 'Comparation operator.' ;
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			ALTER TABLE `redirect_rule`
			CHANGE COLUMN `operator` `operator` enum('=','>','>=','<','<=','LIKE') NOT NULL DEFAULT '=' COMMENT 'Comparation operator.'
		");
	}

}
