<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedirectRuleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		return \DB::statement("
            CREATE TABLE `redirect_rule` (
			  `redirect_rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `redirect_rules_set_id` int(11) unsigned NOT NULL COMMENT 'Foreign key from redirect_rule_set table.',
			  `field` varchar(255) DEFAULT NULL COMMENT 'Name of the table field.',
			  `operator` enum('=','>','>=','<','<=','LIKE') NOT NULL DEFAULT '=' COMMENT 'Comparation operator.',
			  `value` varchar(255) DEFAULT NULL COMMENT 'Condition to meet.',
			  `active` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is rule active.',
			  PRIMARY KEY (`redirect_rule_id`),
			  KEY `fk_redirect_rule_redirect_rules_set_idx` (`redirect_rules_set_id`),
			  CONSTRAINT `fk_redirect_rule_redirect_rules_set` FOREIGN KEY (`redirect_rules_set_id`) REFERENCES `redirect_rules_set` (`redirect_rules_set_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Holds redirect rule for affiliate goals.';
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP TABLE `redirect_rule`;");
	}

}
