<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateApplicationTable extends Migration {
	public function up() {
		return \DB::statement("
			ALTER TABLE `application` ADD KEY `ix_application_date_applied` (`date_applied`);
		");
	}
	
	public function down() {}
}
