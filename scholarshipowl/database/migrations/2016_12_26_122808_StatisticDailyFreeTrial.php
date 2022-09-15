<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ScholarshipOwl\Data\Service\IDDL;

class StatisticDailyFreeTrial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table(IDDL::TABLE_STATISTIC_DAILY_TYPE)->insert([
            ['name' => 'Free trial new'],
            ['name' => 'Free trial 1st charge']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table(IDDL::TABLE_STATISTIC_DAILY_TYPE)->delete([
            ['name' => 'Free trial new'],
            ['name' => 'Free trial 1st charge']
        ]);
    }
}
