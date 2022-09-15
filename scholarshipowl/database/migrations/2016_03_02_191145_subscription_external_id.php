<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionExternalId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('subscription', function(Blueprint $table) {
            $table->integer('payment_method_id', false, true)->nullable()->default(null);
            $table->string('external_id')->nullable()->default(null);

            $table->unique(array('payment_method_id', 'external_id'));
        });

        /*
        \DB::statement(
            "UPDATE subscription s
             JOIN transaction t ON t.subscription_id = s.subscription_id
             JOIN subscription_paypal sp ON sp.subscription_id = s.subscription_id
             SET s.payment_method_id = t.payment_method_id, s.external_id = sp.paypal_id;"
        );

        Schema::table('subscription', function(Blueprint $table) {
            $table->foreign('payment_method_id')->references('payment_method_id')->on('payment_method');
        });

        Schema::dropIfExists('subscription_paypal');
        */
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement("ALTER TABLE `subscription` DROP COLUMN `payment_method_id`, DROP COLUMN `external_id`;");

        /*
        Schema::table('subscription', function(Blueprint $table) {
            $table->dropColumn('payment_method_id');
            $table->dropColumn('external_id');
        });
        */

        // $subscriptionPaypalMigration = new SubscriptionPaypal();
        // $subscriptionPaypalMigration->up();
	}

}
