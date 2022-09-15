<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSubscriptionAndPaymentRenewalTransactionalEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('transactional_email_send')
            ->whereIn('transactional_email_id', function(\Illuminate\Database\Query\Builder $query) {
                $query->select('transactional_email_id')
                  ->from('transactional_email')
                  ->where(['template_name' => 'subscription-and-payment-renewal']);
        })->delete();

        \DB::table("transactional_email")->where(["template_name" => "subscription-and-payment-renewal"])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
