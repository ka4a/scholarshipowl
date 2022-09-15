<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentPageRename extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			UPDATE
				`setting`
			SET
				`value`='secure-upgrade',
				`options`='{\"select\":\"Select\",\"secure-upgrade\":\"Payment\"}'
			WHERE `setting_id`='34';
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			UPDATE
				`setting`
			SET
				`value`='select',
				`options`='{\"select\":\"Select\",\"payment-page\":\"Payment\"}'
			WHERE `setting_id`='34';
		");
	}

}
