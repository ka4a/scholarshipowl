<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterSettingSelectTable extends Migration {
	public function up() {
		return \DB::statement("
			ALTER TABLE `setting`
			CHANGE `type` `type` enum('int','decimal','string','text','select','array') DEFAULT NULL COMMENT 'Setting type.'		
		;");		
	}

	public function down() {
		return \DB::statement("
			ALTER TABLE `setting`
			CHANGE `type` `type` enum('int','decimal','string','text','array') DEFAULT NULL COMMENT 'Setting type.'
		;");
	}
}
