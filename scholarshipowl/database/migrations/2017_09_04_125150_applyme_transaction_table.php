<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplymeTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applyme_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->decimal('sum', 6, 2);
            $table->string('response', 255);
            $table->string('status', 150);
            $table->string('payment_method', 50);
            $table->string('data', 255)->nullable();
            $table->timestamp('updated_at');

            $table->foreign('account_id')->references('account_id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applyme_payments', function(Blueprint $table) {
            $table->dropForeign('applyme_payments_account_id_foreign');
        });
        Schema::dropIfExists('applyme_payments');
    }
}
