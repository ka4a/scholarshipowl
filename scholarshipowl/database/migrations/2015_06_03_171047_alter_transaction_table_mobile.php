<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterTransactionTableMobile extends Migration {
	public function up() {
		return \DB::statement("
			ALTER TABLE `transaction`
			ADD COLUMN `device` enum('desktop','mobile') NOT NULL DEFAULT 'desktop' COMMENT 'Device from which transaction is made.' AFTER `failed_reason`
		");
	}
	
	public function down() {
		return \DB::statement("
			ALTER TABLE `transaction`
			DROP COLUMN `device`
		");
	}
}
