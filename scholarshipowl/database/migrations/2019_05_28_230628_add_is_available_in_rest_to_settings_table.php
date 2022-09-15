<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAvailableInRestToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->integer('is_available_in_rest')->default(0);
        });

        \DB::table('setting')
            ->whereIn('name', [ 'memberships.active_text',
                'memberships.cancelled_text',
                'memberships.free_trial_active_text',
                'memberships.free_trial_cancelled_text',
                'memberships.cancel_subscription_text',
                'memberships.freeTrial.cancel_subscription'])
            ->update([
                "is_available_in_rest" => 1
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn('is_available_in_rest');
        });
    }
}
