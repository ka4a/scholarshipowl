<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipOnlineDataTable extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `scholarship_online_data` (
		  	`scholarship_online_data_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
		  	`scholarship_id` int(11) unsigned NOT NULL COMMENT 'Scholarship id. Foreign key to scholarship table.',
		  	`form_field` varchar(255) DEFAULT NULL COMMENT 'Form field name',
		  	`system_field` varchar(255) DEFAULT NULL COMMENT 'System field name',
		  	`value` mediumtext COMMENT 'Form field value.',
		  	`mapping` mediumtext COMMENT 'Mapping between form values and system values. Json serialized.',
		  	PRIMARY KEY (`scholarship_online_data_id`),
		  	KEY `ix_scholarship_online_data_scholarship_id` (`scholarship_id`),
		  	CONSTRAINT `fk_scholarship_online_data_scholarship` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarship` (`scholarship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds scholarships online data.';
		");
	}
	
	public function down() {
		\DB::statement("DROP TABLE IF EXISTS `scholarship_online_data`");
	}
}
