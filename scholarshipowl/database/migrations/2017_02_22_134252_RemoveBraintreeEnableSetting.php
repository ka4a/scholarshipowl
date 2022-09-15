<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBraintreeEnableSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::delete('payment.braintree.enabled');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->insert([
            'group' => 'Payments',
            'name' => 'payment.braintree.enabled',
            'title' => 'Enable braintree (overrides other payments)',
            'value' => '"no"',

            'type' => 'select',
            'default_value' => '"no"',
            'options' => '{"yes":"Yes","no":"No"}',
        ]);
    }
}
