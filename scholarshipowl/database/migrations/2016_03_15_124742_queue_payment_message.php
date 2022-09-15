<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QueuePaymentMessage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        /** @var \Illuminate\Database\Schema\Blueprint $table */
        $table = \Schema::create('queue_payment_message', function(Blueprint $table) {
            $table->increments('queue_payment_message_id');

            /** Serialized IMessage intance */
            $table->text('message');

            $table->string('status');
            $table->string('status_message');

            $table->timestamp('lastrun_at');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \Schema::dropIfExists('queue_payment_message');
	}

}
