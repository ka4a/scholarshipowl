<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionPaypal extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement("
			CREATE TABLE `subscription_paypal` (
			  `subscription_id` INT(11) UNSIGNED NOT NULL,
			  `paypal_id` VARCHAR(255) NOT NULL,
              `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`subscription_id`, `paypal_id`)
			);
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement("DROP TABLE `subscription_paypal`;");
	}

}
