<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionPaymentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_payment_type', function (Blueprint $table) {
            $table->increments('transaction_payment_type_id');
            $table->string('name');
        });
        \DB::table('transaction_payment_type')->insert([
            ['name' => 'Credit Card'],
            ['name' => 'PayPal'],
        ]);
        Schema::table('transaction', function (Blueprint $table) {
            $table->unsignedInteger('payment_type_id')->after('payment_method_id');
        });

        \DB::statement('UPDATE transaction SET payment_type_id = 1 WHERE payment_method_id = 1');
        \DB::statement('UPDATE transaction SET payment_type_id = 2 WHERE payment_method_id = 2');
        \DB::statement('UPDATE transaction SET payment_type_id = 1 WHERE payment_method_id = 3 AND credit_card_type IS NOT NULL');
        \DB::statement('UPDATE transaction SET payment_type_id = 2 WHERE payment_method_id = 3 AND credit_card_type IS NULL');

        Schema::table('transaction', function (Blueprint $table) {
            $table->foreign('payment_type_id', 'fk_transaction_payment_type')
                ->references('transaction_payment_type_id')
                ->on('transaction_payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropForeign('fk_transaction_payment_type');
            $table->dropColumn('payment_type_id');
        });
        Schema::dropIfExists('transaction_payment_type');
    }
}
