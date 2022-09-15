<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateMarketingSystemTables extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `marketing_system` (
				`marketing_system_id` TINYINT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
			  	`name` VARCHAR(255) NOT NULL,
			  	`url_identifier` VARCHAR(255) NOT NULL,
		  		PRIMARY KEY (`marketing_system_id`)
			) ENGINE=INNODB DEFAULT CHARSET=utf8;
		");
		
		\DB::statement("
			CREATE TABLE `marketing_system_account` (
				`account_id` INT(11) UNSIGNED NOT NULL,
			  	`marketing_system_id` TINYINT(4) UNSIGNED NOT NULL,
			  	`conversion_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
			  	PRIMARY KEY (`account_id`),
			  	KEY `ix_marketing_system_account_account_id` (`account_id`),
			  	KEY `ix_marketing_system_account_marketing_id` (`marketing_system_id`),
			  	KEY `ix_marketing_system_conversion_date` (`conversion_date`),
			  	CONSTRAINT `fk_marketing_system_account` FOREIGN KEY (`marketing_system_id`) REFERENCES `marketing_system` (`marketing_system_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  	CONSTRAINT `fk_marketing_system_account_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=INNODB DEFAULT CHARSET=utf8;
		");
		
		\DB::statement("
			CREATE TABLE `marketing_system_account_data` (
				`account_id` INT(11) UNSIGNED NOT NULL,
				`name` VARCHAR(255) NOT NULL,
				`value` VARCHAR(1023) NOT NULL,
				PRIMARY KEY (`account_id`,`name`),
				KEY `ix_application_account_id` (`account_id`),
				CONSTRAINT `fk_marketing_system_account_data_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=INNODB DEFAULT CHARSET=utf8;
		");
		
		
		\DB::statement("INSERT INTO `marketing_system` VALUES ('1', 'Has Offers', 'transaction_id')");
	}
	
	public function down() {
		\DB::statement("DROP TABLE `marketing_system_account_data`");
		\DB::statement("DROP TABLE `marketing_system_account`");
		\DB::statement("DROP TABLE `marketing_system`");
	}
}
