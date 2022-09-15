<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BraintreePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->string('braintree_plan')->nullable()->after('name');
        });

        \DB::table('payment_method')->insert([
            'payment_method_id' => 3,
            'name' => 'Braintree',
        ]);

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn('braintree_plan');
        });

        \DB::table('payment_method')->where(['payment_method_id' => 3])->delete();
        \DB::table('setting')->where(['name' => 'payment.braintree.enabled'])->delete();
    }
}
