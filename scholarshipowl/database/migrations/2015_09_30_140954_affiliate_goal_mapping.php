<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AffiliateGoalMapping extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("CREATE TABLE `affiliate_goal_mapping` (
			  `affiliate_goal_mapping_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `affiliate_goal_id` int(11) unsigned NOT NULL COMMENT 'Foreign key from affiliate_goal table.',
			  `url_parameter` varchar(45) NOT NULL COMMENT 'In link replacement for our goal ID.',
			  PRIMARY KEY (`affiliate_goal_mapping_id`),
			  KEY `fk_affiliate_goal_mapping_affiliate_goal_idx` (`affiliate_goal_id`),
				  CONSTRAINT `fk_affiliate_goal_mapping_affiliate_goal` FOREIGN KEY (`affiliate_goal_id`) REFERENCES `affiliate_goal` (`affiliate_goal_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
				);");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP TABLE `affiliate_goal_mapping`;");
	}

}
