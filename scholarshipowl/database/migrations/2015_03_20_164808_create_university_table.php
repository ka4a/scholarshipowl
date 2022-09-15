<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniversityTable extends Migration {
	public function up() {
		return \DB::statement("
			CREATE TABLE `university` (
			  `university_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `name` varchar(511) NOT NULL COMMENT 'University name.',
			  `address` varchar(511) COMMENT 'University address.',
			  `city` varchar(511) COMMENT 'University city.',
			  `state` varchar(63) COMMENT 'University state.',
			  `zip` varchar(31) COMMENT 'University zip.',
			  `phone` varchar(31) COMMENT 'University phone.',
			  `website` varchar(255) COMMENT 'University website.',
			  `is_verified` tinyint(1) DEFAULT '1' COMMENT 'Is university system verified.',
			  PRIMARY KEY (`university_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds universities.';
		");
	}
	
	public function down() {
		return \DB::statement("DROP TABLE `university`;");
	}
}
