<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAffiliateGoalMappingTableAddRedirectRule extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement("
			ALTER TABLE `affiliate_goal_mapping`
				DROP FOREIGN KEY `fk_affiliate_goal_mapping_affiliate_goal`;");
		\DB::statement("
				ALTER TABLE `affiliate_goal_mapping`
				ADD COLUMN `affiliate_goal_id_secondary` INT(11) UNSIGNED NULL AFTER `url_parameter`,
				ADD COLUMN `redirect_rules_set_id` INT(11) UNSIGNED NULL AFTER `affiliate_goal_id_secondary`,
				ADD INDEX `fk_affiliate_goal_mapping_affiliate_goal_idx1` (`affiliate_goal_id` ASC, `affiliate_goal_id_secondary` ASC),
				ADD INDEX `fk_affiliate_goal_mapping_redirect_rules_set_idx` (`redirect_rules_set_id` ASC);");
		\DB::statement("
				ALTER TABLE `affiliate_goal_mapping`
				ADD CONSTRAINT `fk_affiliate_goal_mapping_affiliate_goal`
				  FOREIGN KEY (`affiliate_goal_id`)
				  REFERENCES `affiliate_goal` (`affiliate_goal_id`)
				  ON DELETE NO ACTION
				  ON UPDATE NO ACTION,
			    ADD CONSTRAINT `fk_affiliate_goal_mapping_affiliate_goal_secondary`
				  FOREIGN KEY (`affiliate_goal_id_secondary`)
				  REFERENCES `affiliate_goal` (`affiliate_goal_id`)
				  ON DELETE NO ACTION
				  ON UPDATE NO ACTION,
				ADD CONSTRAINT `fk_affiliate_goal_mapping_redirect_rules_set`
				  FOREIGN KEY (`redirect_rules_set_id`)
				  REFERENCES `redirect_rules_set` (`redirect_rules_set_id`)
				  ON DELETE NO ACTION
				  ON UPDATE NO ACTION;
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
			ALTER TABLE `affiliate_goal_mapping`
			DROP FOREIGN KEY `fk_affiliate_goal_mapping_redirect_rules_set`,
			DROP FOREIGN KEY `fk_affiliate_goal_mapping_affiliate_goal_secondary`;
			ALTER TABLE `affiliate_goal_mapping`
			DROP COLUMN `redirect_rules_set_id`,
			DROP COLUMN `affiliate_goal_id_secondary`,
			DROP INDEX `fk_affiliate_goal_mapping_affiliate_goal_secondary` ,
			DROP INDEX `fk_affiliate_goal_mapping_redirect_rules_set_idx` ,
			DROP INDEX `fk_affiliate_goal_mapping_affiliate_goal_idx1` ;
		");
	}

}
