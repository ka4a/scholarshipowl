<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionTransactionCountField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription', function (Blueprint $table) {
            $table->integer('recurrent_count', false, true)->nullable();
        });
        Schema::table('transaction', function (Blueprint $table) {
            $table->integer('recurrent_number', false, true)->nullable();
        });

        $query = \EntityManager::createQuery(
            "SELECT s FROM \\App\\Entity\\Subscription s LEFT JOIN s.transactions t " .
            "WHERE s.expiration_type = 'recurrent' AND t.transaction_status_id IN (1 , 6) "
        );

        /** @var \App\Entity\Subscription $subscription */
        foreach ($query->getResult() as $subscription) {

            $subscription->setRecurrentCount(count($subscription->getTransactions()));

            for ($i = 1; $i <= $subscription->getTransactions()->count(); $i++) {
                $transaction = $subscription->getTransactions()->get($i-1);
                $transaction->setRecurrentNumber($i);
            }

        }

        \EntityManager::flush();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription', function (Blueprint $table) {
            $table->dropColumn('recurrent_count');
        });
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn('recurrent_number');
        });
    }
}
