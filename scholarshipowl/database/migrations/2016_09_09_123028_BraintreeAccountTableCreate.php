<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BraintreeAccountTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::create('braintree_account', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('merchant_id');
            $table->string('public_key');
            $table->string('private_key');
            $table->timestamps();
        });

        $braintreeAccount = new \App\Entity\BraintreeAccount(
            'Default',
            'z9pqz639cwvvn6pq',
            '6tpbzspfygh7q3yd',
            '8bf6b5d5567acf41f7c34efa7d65ec9a'
        );

        \EntityManager::persist($braintreeAccount);
        \EntityManager::flush($braintreeAccount);

        \DB::table("setting")->insert([
            "name" => "payment.braintree.default",
            "title" => "Default account for braintree.",
            "group" => "Payments",

            "type" => "int",
            "value" => 1,
        ]);

        \DB::statement('
            UPDATE transaction
            SET
                bank_transaction_id = CONCAT(\'1-\', bank_transaction_id),
                provider_transaction_id = CONCAT(\'1-\', provider_transaction_id)
            WHERE
                payment_method_id = 3;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('braintree_account');
        \DB::table('setting')->where('name', 'payment.braintree.default')->delete();
    }
}
