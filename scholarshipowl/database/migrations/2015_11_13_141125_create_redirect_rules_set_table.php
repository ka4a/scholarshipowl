<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedirectRulesSetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		return \DB::statement("
            CREATE TABLE `redirect_rules_set` (
			  `redirect_rules_set_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `name` VARCHAR(255) NULL COMMENT 'Name of the rule set.',
			  `type` ENUM('AND', 'OR') NOT NULL DEFAULT 'AND' COMMENT 'Type of the rule set.',
			  `table_name` varchar(255) DEFAULT NULL COMMENT 'Table name',
			  PRIMARY KEY (`redirect_rules_set_id`))
			COMMENT = 'Holds set of redirect rules for affiliate goals.';
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP TABLE `redirect_rules_set`;");
	}
}
