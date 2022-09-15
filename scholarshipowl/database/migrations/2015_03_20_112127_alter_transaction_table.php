<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionTable extends Migration {
	public function up() {
		return \DB::statement("
			ALTER TABLE `transaction` 
			ADD COLUMN `failed_reason` varchar(1023) DEFAULT NULL COMMENT 'Failed transaction reason.' 
			AFTER `credit_card_type`;
		");
	}
	
	public function down() {
		return \DB::statement("
			ALTER TABLE `transaction`
			DROP COLUMN `failed_reason`;
		");
	}
}
