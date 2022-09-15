<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpecialOffersActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailchimp_sync', function (Blueprint $table) {
            $table->timestamp('l_special_offers_activity_check_time')->after('l_special_offers_status')->nullable();
            $table->timestamp('l_special_offers_activity_time')->after('l_special_offers_activity_check_time')->nullable();

            $table->index('l_special_offers_time');
            $table->index('l_special_offers_activity_check_time');
            $table->index('l_special_offers_activity_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('l_special_offers_activity_check_time');
        $table->dropColumn('l_special_offers_activity_time');

        $table->dropIndex('l_special_offers_time');
        $table->dropIndex('l_special_offers_activity_check_time');
        $table->dropIndex('l_special_offers_activity_time');
    }
}
