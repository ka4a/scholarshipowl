<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QueuePaymentMessageClassField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('queue_payment_message', function(Blueprint $table)
		{
            $table->string('listener')->after('queue_payment_message_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('queue_payment_message', function(Blueprint $table)
		{
            $table->dropColumn('listener');
		});
	}

}
