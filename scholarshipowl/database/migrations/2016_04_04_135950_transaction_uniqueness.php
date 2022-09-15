<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionUniqueness extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::update('UPDATE transaction SET bank_transaction_id = NULL WHERE bank_transaction_id = ""');
        DB::update('UPDATE transaction SET provider_transaction_id = NULL WHERE provider_transaction_id = ""');

		Schema::table('transaction', function(Blueprint $table) {
            $table->unique(array('payment_method_id', 'bank_transaction_id', 'provider_transaction_id'), 'uq_transaction_uniqueness');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transaction', function(Blueprint $table) {
            $table->dropUnique('uq_transaction_uniqueness');
		});
	}

}
