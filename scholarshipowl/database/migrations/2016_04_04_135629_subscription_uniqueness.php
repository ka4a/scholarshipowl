<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionUniqueness extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::update('UPDATE subscription SET external_id = NULL WHERE external_id = ""');

        Schema::table('subscription', function(Blueprint $table) {
            $table->unique(array('payment_method_id', 'external_id'), 'uq_subscription_external_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('subscription', function(Blueprint $table) {
            $table->dropUnique('uq_subscription_external_id');
		});
	}

}
