<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlansPagePaymentSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('feature_payment_set')->insert([
            'name' => 'PlansPage',
            'popup_title' => "<span style=\"font-family: 'Verdana','Helvetica','sans-serif'; font-size: 24px; font-weight: bold; white-space: pre-wrap; color: #000; box-shadow: 0 0 black;\">[[first_name]], Get a membership for Free for 7 days to activate automatic application to your [[eligible_scholarships_count]] scholarship matches.<br />Let us do the hard work for you!</span><br />",
            'packages' => '[{"id":59},{"id":72},{"id":70},{"id":71}]',
            'common_option' => '{"1":{"text":"<p>test </p>","status":{"59":"1","72":"0","70":"0","71":"1"}},"2":{"text":"<p>test 2<\/p>","status":{"59":"1","72":"0","70":"0","71":"1"}}}',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('feature_payment_set')
            ->where(['name' => 'PlansPage'])
            ->delete();

    }
}
