<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogPaymentMessage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('log_ipn_message', function(Blueprint $table) {
            $table->integer('payment_method_id', false, true)->after('log_ipn_message_id');
            $table->renameColumn('log_ipn_message_id', 'log_payment_message_id');
        });

        \DB::statement('UPDATE log_ipn_message SET payment_method_id = 1;');

        Schema::rename('log_ipn_message', 'log_payment_message');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement("ALTER TABLE `log_payment_message` DROP COLUMN `payment_method_id`");

        Schema::table('log_payment_message', function(Blueprint $table) {
            $table->renameColumn('log_payment_message_id', 'log_ipn_message_id');
        });

        Schema::rename('log_payment_message', 'log_ipn_message');
	}

}
