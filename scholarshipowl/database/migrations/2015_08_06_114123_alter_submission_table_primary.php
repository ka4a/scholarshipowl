<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterSubmissionTablePrimary extends Migration {
	public function up() {
		\DB::statement("
			ALTER TABLE `submission`
			CHANGE `submission_id` `submission_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.' 
		;");
	}

	public function down() {
	
	}
}
