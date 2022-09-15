<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEssayFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(
			"CREATE TABLE `essay_files` (
			  `essay_file_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `essay_id` int(11) unsigned NOT NULL COMMENT 'Essay id',
			  `scholarship_id` int(11) unsigned NOT NULL COMMENT 'Scholarship id',
			  `file_id` int(11) unsigned NOT NULL COMMENT 'File id',
			  PRIMARY KEY (`essay_file_id`),
			  KEY `fk_essay_idx` (`essay_id`),
			  KEY `fk_scholarship_idx` (`scholarship_id`),
			  KEY `fk_file_idx` (`file_id`),
			  CONSTRAINT `fk_essay` FOREIGN KEY (`essay_id`) REFERENCES `essay` (`essay_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `fk_scholarship` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarship` (`scholarship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `fk_file` FOREIGN KEY (`file_id`) REFERENCES `files` (`file_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='Table with nfo about relation to essays and scholarships.';"
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement(
			"DROP TABLE `essay_files`;"
		);
	}

}
