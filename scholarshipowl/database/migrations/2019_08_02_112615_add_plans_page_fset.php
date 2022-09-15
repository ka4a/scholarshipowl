<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlansPageFset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("feature_set")->insert([
            'name' => 'PlansPage',
            'desktop_payment_set' => 1,
            'mobile_payment_set' => 1,
            'content_set' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('feature_set')
            ->where(['name' => 'FreemiumMVP'])
            ->delete();
    }
}
