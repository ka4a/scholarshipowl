<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropScholarshipFieldTable extends Migration {
	public function up() {
		\DB::statement("DROP TABLE IF EXISTS `scholarship_field`;");
	}

	public function down() {}
}
