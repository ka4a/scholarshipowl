<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTable extends Migration {
	public function up() {
		\DB::statement("DROP TABLE IF EXISTS `scholarship_online_data`;");
		\DB::statement("
			CREATE TABLE `form` (
  			`form_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
  			`scholarship_id` int(11) unsigned NOT NULL COMMENT 'Scholarship id. Foreign key to scholarship table.',
  			`form_field` varchar(255) DEFAULT NULL COMMENT 'Form field name',
  			`system_field` varchar(255) DEFAULT NULL COMMENT 'System field name',
  			`value` mediumtext COMMENT 'Form field value.',
  			`mapping` mediumtext COMMENT 'Mapping between form values and system values. Json serialized.',
  			PRIMARY KEY (`form_id`),
  			KEY `ix_form_scholarship_id` (`scholarship_id`),
  			CONSTRAINT `fk_form_scholarship` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarship` (`scholarship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds forms for online scholarships.';
		");
	}
	
	public function down() {
		return \DB::statement("
			DROP TABLE `form`;
		");
	}
}
