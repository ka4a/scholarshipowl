<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterAccountTableDatetime extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `profile` 
			CHANGE `date_of_birth` `date_of_birth` datetime NOT NULL COMMENT 'Profile date of birth.' 
		;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `profile` 
			CHANGE `date_of_birth` `date_of_birth` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Profile date of birth.'
		;");
	}
}
