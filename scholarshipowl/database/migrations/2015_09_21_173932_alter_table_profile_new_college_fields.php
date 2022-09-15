<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProfileNewCollegeFields extends Migration {

    public function up() {
        \DB::statement("
			ALTER TABLE `profile`
			add column `university1`  VARCHAR(511) NOT NULL COMMENT 'College 1',
			add column `university2`  VARCHAR(511) NOT NULL COMMENT 'College 2',
			add column `university3`  VARCHAR(511) NOT NULL COMMENT 'College 3',
			add column `university4`  VARCHAR(511) NOT NULL COMMENT 'College 4'
		;");

    }

    public function down() {
        \DB::statement("
			ALTER TABLE `profile`
			drop  `university1`,drop `university2`,drop `university3`,drop `university4`
		;");
    }

}
