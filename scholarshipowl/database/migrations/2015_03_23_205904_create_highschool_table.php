<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHighschoolTable extends Migration {
	public function up() {
		return \DB::statement("
			CREATE TABLE `highschool` (
			  `highschool_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `name` varchar(511) NOT NULL COMMENT 'Highschool name.',
			  `address` varchar(511) COMMENT 'Highschool address.',
			  `city` varchar(511) COMMENT 'Highschool city.',
			  `state` varchar(63) COMMENT 'Highschool state.',
			  `zip` varchar(31) COMMENT 'Highschool zip.',
			  `phone` varchar(31) COMMENT 'Highschool phone.',
			  `is_verified` tinyint(1) DEFAULT '1' COMMENT 'Is highschool system verified.',
			  PRIMARY KEY (`highschool_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds Highschools.';
		");
	}
	
	public function down() {
		return \DB::statement("DROP TABLE `highschool`;");
	}
}
