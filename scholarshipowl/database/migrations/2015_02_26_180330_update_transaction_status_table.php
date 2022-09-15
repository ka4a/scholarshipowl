<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateTransactionStatusTable extends Migration {
	public function up() {
		return \DB::statement("
			INSERT INTO `transaction_status` VALUES
			(3, 'Void'), (4, 'Refund'), (5, 'Chargeback'), (6, 'Other');
		");
	}

	public function down() {
		return \DB::statement("
			DELETE FROM `transaction_status` WHERE transaction_status_id (3, 4, 5, 6);
		");
	}
}
