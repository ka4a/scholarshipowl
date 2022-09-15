<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Payment\Braintree\PaymentMethodRepository;

class SettingBraintreeBillingAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('setting')->insert([
            'group' => 'Payments',
            'name' => PaymentMethodRepository::BT_REGISTER_BILLING_ADDRESS,
            'title' => 'Braintree send register data as billing address',
            'value' => '"no"',

            'type' => 'select',
            'default_value' => '"no"',
            'options' => '{"yes":"Yes","no":"No"}',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where(['name' => PaymentMethodRepository::BT_REGISTER_BILLING_ADDRESS])->delete();
    }
}
