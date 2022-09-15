<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSubscriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        return \DB::statement("
            ALTER TABLE `subscription`
            ADD COLUMN `renewal_date` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00' AFTER `transaction_id`;
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        return \DB::statement("
            ALTER TABLE `subscription`
            DROP COLUMN `renewal_date`;
        ");
	}

}
