<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentSetConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_payment_set', function(Blueprint $table) {
            $table->boolean('mobile_special_offer_only')->default(true);
        });

        DB::update("UPDATE feature_payment_set set mobile_special_offer_only = true;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_payment_set', function(Blueprint $table) {
            $table->dropColumn('mobile_special_offer_only');
        });
    }
}
