<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationEssayStatus extends Migration {
	public function up()
	{
		\DB::statement("
			CREATE TABLE IF NOT EXISTS `application_essay_status` (
			  `application_essay_status_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `name` varchar(15) NOT NULL COMMENT 'Essay status.',
			  PRIMARY KEY (`application_essay_status_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds application essay statuses.';
		");
		
		\DB::statement("
			INSERT INTO `application_essay_status` (name) VALUES
			('Not Started'), ('In Progress'), ('Done');
		");
		
		\DB::statement("
			ALTER TABLE `application_essay` ADD COLUMN `application_essay_status_id` tinyint(1) unsigned NULL;
		");
		
		\DB::statement("
			ALTER TABLE `application_essay` ADD CONSTRAINT `fk_application_essay_application_essay_status` FOREIGN KEY (application_essay_status_id) REFERENCES `application_essay_status` (`application_essay_status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
		");
	}

	public function down()
	{
		\DB::statement("ALTER TABLE `application_essay` DROP FOREIGN KEY `fk_application_essay_status`;");
		\DB::statement("ALTER TABLE `application_essay` DROP COLUMN `application_essay_status_id`;");
		\DB::statement("DROP TABLE IF EXISTS `application_essay_status`;");
	}

}
